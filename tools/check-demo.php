<?php
// CLI-only, read-only presentation preflight. Never print credentials or technical exceptions.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
$checks = [];
$add = static function (string $name, bool $passed, $actual) use (&$checks): void {
    $checks[] = ['check'=>$name, 'passed'=>$passed, 'actual'=>$actual];
};
try {
    require_once dirname(__DIR__) . '/database.php';
    $connection = parse_url('mysql://' . DB_SERVER);
    $host = $connection['host'] ?? DB_SERVER;
    $port = $connection['port'] ?? (int)ini_get('mysqli.default_port');
    if (in_array('--port', $argv, true)) {
        echo json_encode(['host'=>$host,'port'=>$port ?: 3306]), PHP_EOL; exit;
    }
    $db = db_connect();
    $add('Database connection', true, 'connected');
    $counts = [];
    foreach (['movies','people','songs'] as $table) {
        $counts[$table] = (int)db_query($db, 'SELECT COUNT(*) FROM `' . $table . '`')->fetch_row()[0];
    }
    $add('Catalog tables', true, $counts);
    $foreignKeys = (int)db_query($db, "SELECT COUNT(*) FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() AND DELETE_RULE='CASCADE'")->fetch_row()[0];
    $add('Cascading foreign keys', $foreignKeys === 17, $foreignKeys);
    $emailIndex = db_query($db, "SELECT INDEX_NAME FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='users' AND COLUMN_NAME='email' AND NON_UNIQUE=0")->num_rows;
    $add('Unique account email', $emailIndex > 0, $emailIndex > 0 ? 'present' : 'missing');
    $add('Administrator account', (bool)db_query($db, "SELECT id FROM users WHERE role IN ('ADMIN','SUPER-ADMIN') LIMIT 1")->num_rows, 'account presence checked');
    $add('Member account', (bool)db_query($db, "SELECT id FROM users WHERE role='USER' LIMIT 1")->num_rows, 'account presence checked');
    if (in_array('--require-demo', $argv, true)) {
        require_once dirname(__DIR__) . '/movie_search.php';
        $matches = movie_search_results($db, movie_search_filters(['cast_member'=>'Avery Stone','director'=>'Morgan River']));
        $add('Prepared cast and director search', $matches['count'] === 1 && $matches['rows'][0]['english_name'] === 'Harbor Lights', $matches['count'] . ' matching movie(s)');
    }
    $db->close();
} catch (Throwable $error) {
    $add('Database and configuration', false, 'Unavailable. Check the local configuration, server port and schema using private logs.');
}
$passed = !in_array(false, array_column($checks, 'passed'), true);
echo json_encode(['ready'=>$passed, 'checks'=>$checks], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), PHP_EOL;
exit($passed ? 0 : 1);
