<?php
// CLI only: never let a web request run DDL or repair production data.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/database.php';

function migration_steps(): array {
    $sql = file_get_contents(dirname(__DIR__) . '/sql/migrations/007_database_cleanup.sql');
    $sql = preg_replace('/^--.*$/m', '', $sql);
    return array_values(array_filter(array_map('trim', explode(';', $sql))));
}
function migration_step_done(mysqli $db, string $sql): bool {
    preg_match('/ALTER TABLE `(\w+)`/', $sql, $table);
    $table = $table[1];
    if (preg_match('/ADD CONSTRAINT `(\w+)`/', $sql, $fk)) {
        $stmt = $db->prepare('SELECT DELETE_RULE FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?');
        $stmt->bind_param('ss', $table, $fk[1]);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if ($row && $row['DELETE_RULE'] !== 'CASCADE') { throw new RuntimeException('Unexpected existing foreign key: ' . $table); }
        if ($row) {
            preg_match('/FOREIGN KEY \(`(\w+)`\) REFERENCES `(\w+)` \(`(\w+)`\)/', $sql, $expected);
            $stmt=$db->prepare('SELECT COLUMN_NAME,REFERENCED_TABLE_NAME,REFERENCED_COLUMN_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME=? AND CONSTRAINT_NAME=? ORDER BY ORDINAL_POSITION');
            $stmt->bind_param('ss',$table,$fk[1]); $stmt->execute(); $actual=$stmt->get_result()->fetch_all(MYSQLI_NUM); $stmt->close();
            if ($actual !== [array_slice($expected,1)]) { throw new RuntimeException('Unexpected existing foreign-key target: ' . $table); }
        }
        return (bool)$row;
    }
    $indexes = $db->query("SHOW INDEX FROM `$table` WHERE Key_name = 'PRIMARY'")->fetch_all(MYSQLI_ASSOC);
    if (!$indexes) { return false; }
    preg_match('/PRIMARY KEY\s*\(([^)]+)\)/', $sql, $columns);
    $expected = isset($columns[1]) ? explode(',', str_replace(['`',' '], '', $columns[1])) : ['match_id'];
    if (array_column($indexes, 'Column_name') !== $expected) { throw new RuntimeException('Unexpected existing primary key: ' . $table); }
    if (strpos($sql, 'AUTO_INCREMENT') !== false) {
        $column = $expected[0];
        $info = $db->query("SHOW COLUMNS FROM `$table` LIKE '$column'")->fetch_assoc();
        if (strpos($info['Extra'], 'auto_increment') === false) { return false; }
    }
    return true;
}
function migration_preflight(mysqli $db): array {
    $blockers = []; $repairs = [];
    $engines=$db->query('SELECT TABLE_NAME,ENGINE FROM information_schema.TABLES WHERE TABLE_SCHEMA=DATABASE()')->fetch_all(MYSQLI_ASSOC);
    foreach ($engines as $table) {
        if ($table['ENGINE'] !== 'InnoDB') { $blockers[] = $table['TABLE_NAME'] . ': convert to InnoDB before migration.'; }
    }
    foreach (migration_steps() as $sql) {
        if (!preg_match('/ALTER TABLE `(\w+)`/', $sql, $match)) { continue; }
        $table = $match[1];
        if (preg_match('/FOREIGN KEY \(`(\w+)`\) REFERENCES `(\w+)` \(`(\w+)`\)/', $sql, $fk)) {
            [$unused, $column, $parent, $pk] = $fk;
            $n = (int)$db->query("SELECT COUNT(*) n FROM `$table` c LEFT JOIN `$parent` p ON c.`$column`=p.`$pk` WHERE p.`$pk` IS NULL")->fetch_assoc()['n'];
            if ($n) { $repairs[] = ['kind'=>'orphan', 'table'=>$table, 'column'=>$column, 'parent'=>$parent, 'pk'=>$pk, 'count'=>$n]; }
        } elseif (strpos($sql, 'ADD COLUMN') === false) {
            preg_match('/PRIMARY KEY\s*\(([^)]+)\)/', $sql, $keys);
            $columns = $keys[1];
            $duplicates = (int)$db->query("SELECT COUNT(*) n FROM (SELECT $columns FROM `$table` GROUP BY $columns HAVING COUNT(*)>1) d")->fetch_assoc()['n'];
            $zeroAnagrams = $table === 'movie_anagrams'
                && (int)$db->query('SELECT COUNT(*) n FROM movie_anagrams WHERE anagram_id <> 0')->fetch_assoc()['n'] === 0
                && (int)$db->query('SELECT COUNT(*) n FROM movie_anagrams')->fetch_assoc()['n'] > 0;
            if ($zeroAnagrams) { $repairs[] = ['kind'=>'anagram_ids', 'table'=>$table, 'count'=>(int)$db->query('SELECT COUNT(*) n FROM movie_anagrams')->fetch_assoc()['n']]; }
            elseif ($duplicates) { $blockers[] = "$table: $duplicates duplicate primary-key groups; resolve explicitly before migration."; }
            if (strpos($sql, 'AUTO_INCREMENT') !== false && !$zeroAnagrams) {
                $n = (int)$db->query("SELECT COUNT(*) n FROM `$table` WHERE $columns <= 0")->fetch_assoc()['n'];
                if ($n) { $blockers[] = "$table: $n nonpositive IDs; resolve explicitly before migration."; }
            }
        }
        // Validate existing keys even on a second run; never silently replace another design.
        migration_step_done($db, $sql);
    }
    $emailDuplicates = (int)$db->query('SELECT COUNT(*) n FROM (SELECT email FROM users GROUP BY email HAVING COUNT(*)>1) d')->fetch_assoc()['n'];
    if ($emailDuplicates) { $blockers[] = 'users: duplicate emails must be resolved explicitly.'; }
    return ['blockers'=>$blockers, 'repairs'=>$repairs];
}
function migration_archive(mysqli $db, string $table, string $reason, array $row): void {
    $json = json_encode($row, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR);
    $stmt = $db->prepare('INSERT INTO omdb_migration_archive (source_table, reason, row_data) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $table, $reason, $json); $stmt->execute(); $stmt->close();
}
function migration_apply(mysqli $db, bool $repair): array {
    $plan = migration_preflight($db);
    if ($plan['blockers'] || ($plan['repairs'] && !$repair)) { throw new RuntimeException('Preflight needs attention. Run --check; known archival repairs require --repair-legacy.'); }
    $schema = file_get_contents(dirname(__DIR__) . '/sql/schema.sql');
    foreach (['omdb_migration_archive', 'omdb_schema_migrations'] as $table) {
        preg_match('/CREATE TABLE IF NOT EXISTS `' . $table . '`.*?;/s', $schema, $match);
        $db->query($match[0]);
    }
    foreach ($plan['repairs'] as $item) {
        $table = $item['table'];
        if ($item['kind'] === 'orphan') {
            $column=$item['column']; $parent=$item['parent']; $pk=$item['pk'];
            $where = "NOT EXISTS (SELECT 1 FROM `$parent` p WHERE p.`$pk`=c.`$column`)";
            $db->begin_transaction();
            try {
                $rows = $db->query("SELECT c.* FROM `$table` c WHERE $where FOR UPDATE")->fetch_all(MYSQLI_ASSOC);
                foreach ($rows as $row) { migration_archive($db, $table, 'Missing parent: ' . $column, $row); }
                $db->query("DELETE c FROM `$table` c WHERE $where");
                $db->commit();
            } catch (Throwable $e) { $db->rollback(); throw $e; }
        } else {
            foreach ($db->query('SELECT * FROM movie_anagrams')->fetch_all(MYSQLI_ASSOC) as $row) {
                migration_archive($db, $table, 'Replaced placeholder anagram ID 0', $row);
            }
            // All IDs are placeholders and no table references anagram_id. Keep every anagram.
            if (!$db->query("SHOW COLUMNS FROM movie_anagrams LIKE 'omdb_new_anagram_id'")->num_rows) {
                $db->query('ALTER TABLE movie_anagrams ADD COLUMN omdb_new_anagram_id INT NOT NULL AUTO_INCREMENT UNIQUE');
            }
            $db->query('ALTER TABLE movie_anagrams DROP COLUMN anagram_id, CHANGE COLUMN omdb_new_anagram_id anagram_id INT NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY (anagram_id), DROP INDEX omdb_new_anagram_id');
        }
    }
    foreach (migration_steps() as $sql) {
        if (!migration_step_done($db, $sql)) {
            // A compatible existing PK may only need its AUTO_INCREMENT attribute.
            if (preg_match('/ALTER TABLE `(\w+)` ADD PRIMARY KEY.*?, (MODIFY .*?)$/s',$sql,$match)
                && $db->query("SHOW INDEX FROM `{$match[1]}` WHERE Key_name='PRIMARY'")->num_rows) {
                $sql='ALTER TABLE `'.$match[1].'` '.$match[2];
            }
            $db->query($sql);
        }
    }
    // Preserve any equivalent existing unique email index, regardless of its name.
    $indexes = $db->query('SHOW INDEX FROM users')->fetch_all(MYSQLI_ASSOC); $uniqueEmail = [];
    foreach ($indexes as $index) { if (!$index['Non_unique']) { $uniqueEmail[$index['Key_name']][] = $index['Column_name']; } }
    if (!in_array(['email'], $uniqueEmail, true)) { $db->query('ALTER TABLE users ADD UNIQUE KEY uq_users_email (email)'); }
    $db->query("INSERT INTO omdb_schema_migrations (version) VALUES ('007_database_cleanup') ON DUPLICATE KEY UPDATE version=VALUES(version)");
    return $plan;
}

if (realpath($_SERVER['SCRIPT_FILENAME']) === __FILE__) {
    $mode = $argv[1] ?? '--check';
    if (!in_array($mode, ['--check','--apply'], true)) { fwrite(STDERR, "Usage: php tools/migrate.php --check | --apply [--repair-legacy]\n"); exit(1); }
    $db = null;
    try {
        $db = db_connect();
        $lock = 'omdb_week7_' . DB_NAME;
        $stmt=$db->prepare('SELECT GET_LOCK(?, 0) acquired'); $stmt->bind_param('s',$lock); $stmt->execute();
        if (!$stmt->get_result()->fetch_assoc()['acquired']) { throw new RuntimeException('Another migration is running.'); }
        $stmt->close();
        $plan = $mode === '--check' ? migration_preflight($db) : migration_apply($db, in_array('--repair-legacy', $argv, true));
        echo json_encode(['mode'=>$mode] + $plan, JSON_PRETTY_PRINT), PHP_EOL;
        exit($plan['blockers'] ? 1 : 0);
    } catch (Throwable $error) {
        error_log('OMDB migration failed: ' . $error);
        fwrite(STDERR, "Migration stopped. Review preflight and the private PHP error log. Restore your backup if required.\n");
        exit(1);
    } finally { if ($db) { $db->close(); } }
}
