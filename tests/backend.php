<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/catalog.php';
require_once dirname(__DIR__) . '/tools/migrate.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$testServer=getenv('OMDB_TEST_DB_SERVER') ?: '127.0.0.1';
$testUser=getenv('OMDB_TEST_DB_USER') ?: 'root';
$testPass=getenv('OMDB_TEST_DB_PASSWORD') ?: '';
$server=null; $failed=0;
$databases=[]; $checks=0;
function check_backend(bool $condition, string $label): void {
    global $checks; $checks++;
    if (!$condition) { throw new RuntimeException('FAIL: '.$label); }
}
function rejects(callable $operation, string $label): void {
    try { $operation(); } catch (Throwable $error) { check_backend(true,$label); return; }
    check_backend(false,$label);
}
function fixture_database(string $schema): mysqli {
    global $server,$databases,$testServer,$testUser,$testPass;
    $name='omdb_backend_'.bin2hex(random_bytes(6)).'_test';
    $databases[]=$name;
    $server->query("CREATE DATABASE `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $db=new mysqli($testServer,$testUser,$testPass,$name); $db->set_charset('utf8mb4');
    $db->query("SET SESSION sql_mode='STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");
    $db->multi_query(file_get_contents($schema));
    do { if ($r=$db->store_result()) { $r->free(); } } while ($db->more_results() && $db->next_result());
    return $db;
}
function scalar(mysqli $db, string $sql): int { return (int)$db->query($sql)->fetch_row()[0]; }
function catalog_contract(mysqli $db): array {
    $columns=$db->query('SELECT TABLE_NAME,COLUMN_NAME,COLUMN_TYPE,IS_NULLABLE,COLUMN_DEFAULT,EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA=DATABASE() ORDER BY TABLE_NAME,COLUMN_NAME')->fetch_all(MYSQLI_ASSOC);
    foreach ($columns as &$c) { $c['COLUMN_TYPE']=preg_replace('/int\(\d+\)/','int',$c['COLUMN_TYPE']); } unset($c);
    $keys=$db->query('SELECT TABLE_NAME,INDEX_NAME,SEQ_IN_INDEX,COLUMN_NAME,NON_UNIQUE FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() ORDER BY TABLE_NAME,INDEX_NAME,SEQ_IN_INDEX')->fetch_all(MYSQLI_ASSOC);
    $foreigns=$db->query('SELECT TABLE_NAME,CONSTRAINT_NAME,DELETE_RULE FROM information_schema.REFERENTIAL_CONSTRAINTS WHERE CONSTRAINT_SCHEMA=DATABASE() ORDER BY TABLE_NAME,CONSTRAINT_NAME')->fetch_all(MYSQLI_ASSOC);
    return [$columns,$keys,$foreigns];
}
try {
    $server=new mysqli($testServer,$testUser,$testPass);
    $_SESSION=['user_id'=>1,'username'=>'backend-fixture@example.invalid','role'=>'ADMIN','csrf_token'=>bin2hex(random_bytes(32))];
    $_SERVER['REQUEST_METHOD']='POST'; $_POST=['csrf_token'=>$_SESSION['csrf_token']];
    $db=fixture_database(dirname(__DIR__).'/sql/schema.sql');
    $freshContract=catalog_contract($db);
    check_backend(count($freshContract[2])===17,'All 17 ownership/association foreign keys exist');
    check_backend(scalar($db,'SELECT COUNT(*) FROM users')===0,'Fresh schema seeds no credentials');
    $movie=db_transaction($db,function () use($db) { return catalog_insert_movie($db,['native_name'=>'నమస్తే','english_name'=>"O'Reilly <b>Movie</b>",'year'=>2020]); });
    $person=catalog_insert_person($db,['stage_name'=>"O'Reilly"]);
    $song=catalog_insert_song($db,['title'=>"Song <b>1</b>",'lyrics'=>"a\nb"]);
    check_backend($movie>0 && $person>0 && $song>0,'Generated entity IDs returned');
    check_backend(scalar($db,"SELECT COUNT(*) FROM movie_numbers WHERE movie_id=$movie")===1,'Movie numbers created atomically');
    $second=new mysqli($testServer,$testUser,$testPass,$db->query('SELECT DATABASE()')->fetch_row()[0]);
    $second->set_charset('utf8mb4');
    $song2=catalog_insert_song($second,['title'=>'Second connection']);
    check_backend($song2!==$song,'Independent connections receive distinct IDs');
    $second->close();
    $count=scalar($db,'SELECT COUNT(*) FROM movies');
    rejects(function () use($db) { db_transaction($db,function () use($db) { catalog_insert_movie($db,['native_name'=>'rollback','english_name'=>'rollback','year'=>2020]); throw new RuntimeException('Injected second-step failure'); }); },'Multi-table failure propagates');
    check_backend(scalar($db,'SELECT COUNT(*) FROM movies')===$count,'Failed movie operation rolls back parent');
    check_backend(scalar($db,'SELECT COUNT(*) FROM movie_numbers')===$count,'Failed movie operation rolls back numbers');
    foreach (['movie_anagrams'=>['anagram_id','anagram'], 'movie_trivia'=>['movie_trivia_id','movie_trivia_name'], 'movie_quotes'=>['movie_quote_id','movie_quote_name']] as $table=>[$pk,$text]) {
        db_query($db,"INSERT INTO `$table` (movie_id,`$text`) VALUES (?,?)",[$movie,'sample']);
        check_backend((int)$db->insert_id>0,$table.' generates IDs');
    }
    db_query($db,'INSERT INTO movie_data (movie_id,language,country,genre,plot,tag_line) VALUES (?,?,?,?,?,?)',[$movie,'Telugu','India','Drama','plot','tag']);
    db_query($db,'INSERT INTO movie_media (movie_id,m_link,m_link_type) VALUES (?,?,?)',[$movie,'poster','jpg']);
    db_query($db,'INSERT INTO movie_keywords (movie_id,keyword) VALUES (?,?),(?,?)',[$movie,'first',$movie,'second']);
    catalog_relationships($db,'movie_people',$movie,['add'=>1,'people_id4'=>$person,'role4'=>'Actor','screen_name4'=>'']);
    catalog_relationships($db,'movie_people',$movie,['add'=>1,'people_id4'=>$person,'role4'=>'Actor','screen_name4'=>'']);
    check_backend(scalar($db,'SELECT COUNT(*) FROM movie_people')===1,'Repeat relationship addition is idempotent');
    rejects(function () use($db,$movie,$person) { catalog_relationships($db,'movie_people',$movie,['add'=>1,'people_id2'=>$person,'role2'=>'Director','people_id7'=>2147483647,'role7'=>'Actor']); },'Invalid FK fails entire relationship batch');
    check_backend(scalar($db,'SELECT COUNT(*) FROM movie_people')===1,'Earlier relationship in failed batch rolls back');
    catalog_relationships($db,'movie_song',$movie,['add'=>1,'song_id1'=>$song]);
    catalog_relationships($db,'song_people',$song,['add'=>1,'people_id3'=>$person,'role3'=>'Singer']);
    foreach (['song_media'=>['s_link','s_link_type'], 'song_trivia'=>['song_trivia_name'], 'song_keywords'=>['keyword']] as $t=>$cols) {
        $names=implode(',',$cols); $marks=implode(',',array_fill(0,count($cols),'?'));
        db_query($db,"INSERT INTO $t (song_id,$names) VALUES (?,$marks)",array_merge([$song],array_fill(0,count($cols),'sample')));
    }
    db_query($db,'INSERT INTO people_trivia (people_id,people_trivia_name) VALUES (?,?)',[$person,'sample']);
    rejects(function () use($db,$movie) { db_query($db,'INSERT INTO movie_song (movie_id,song_id) VALUES (?,?)',[$movie,2147483647]); },'Missing parent is rejected');
    rejects(function () use($db,$movie) { db_query($db,'INSERT INTO movies (movie_id,native_name,english_name,year_made) VALUES (?,?,?,?)',[$movie,'duplicate','duplicate',2020]); },'Duplicate entity ID is rejected');
    $hash=password_hash(bin2hex(random_bytes(16)),PASSWORD_DEFAULT);
    db_query($db,'INSERT INTO users (email,password,role) VALUES (?,?,?)',['unique@example.invalid',$hash,'USER']);
    rejects(function () use($db,$hash) { db_query($db,'INSERT INTO users (email,password,role) VALUES (?,?,?)',['unique@example.invalid',$hash,'USER']); },'Email uniqueness survives setup');
    $trivia=scalar($db,'SELECT movie_trivia_id FROM movie_trivia LIMIT 1');
    db_transaction($db,function () use($db,$movie,$trivia) { catalog_update_movie($db,['movie_id'=>$movie,'english_name_update'=>'Updated','year_update'=>2020,'movie_trivia_id6'=>$trivia,'movie_trivia_name6'=>'updated trivia','keyword_update9'=>'changed','original_keyword9'=>'first']); });
    check_backend(scalar($db,"SELECT COUNT(*) FROM movie_keywords WHERE movie_id=$movie AND keyword='second'")===1,'Editing one keyword preserves siblings');
    check_backend(scalar($db,"SELECT COUNT(*) FROM movie_trivia WHERE movie_trivia_id=$trivia AND movie_trivia_name='updated trivia'")===1,'Sparse child suffix pairs correctly');
    rejects(function () use($db,$movie) { db_transaction($db,function () use($db,$movie) { catalog_update_movie($db,['movie_id'=>$movie,'english_name_update'=>'Must roll back','year_update'=>2020,'keyword_update9'=>'second','original_keyword9'=>'changed']); }); },'Conflict in later table rejects edit');
    check_backend(scalar($db,"SELECT COUNT(*) FROM movies WHERE movie_id=$movie AND english_name='Updated'")===1,'Multi-table edit rolls back earlier title update');
    $csv=fopen('php://temp','w+'); fwrite($csv,"CSV1,\"lyrics with\nnewline\",theme\nCSV2,lyrics,theme\n"); rewind($csv);
    $counts=catalog_import_csv($db,'songs',$csv); fclose($csv);
    check_backend($counts['imported']===2,'CSV parser handles quoted multiline records');
    $before=scalar($db,'SELECT COUNT(*) FROM movies');
    $csv=fopen('php://temp','w+'); fwrite($csv,"valid,valid,2020\nbad,bad,not-a-year\n"); rewind($csv);
    rejects(function () use($db,$csv) { catalog_import_csv($db,'movies',$csv); },'Invalid CSV record rejects import'); fclose($csv);
    check_backend(scalar($db,'SELECT COUNT(*) FROM movies')===$before,'CSV rollback leaves no partial movies');
    check_backend(scalar($db,'SELECT COUNT(*) FROM movie_numbers')===$before,'CSV rollback leaves no partial numbers');
    $csv=fopen('php://temp','w+'); fwrite($csv,"duplicate,duplicate,2020\nduplicate,duplicate,2020\n"); rewind($csv);
    $counts=catalog_import_csv($db,'movies',$csv); fclose($csv);
    check_backend($counts===['imported'=>1,'skipped'=>1],'CSV duplicate policy avoids duplicate number rows');
    rejects(function () use($db,$song) { db_transaction($db,function () use($db,$song) { db_query($db,'DELETE FROM songs WHERE song_id=?',[$song]); throw new RuntimeException('Injected failure'); }); },'Delete rollback propagates');
    check_backend(scalar($db,"SELECT COUNT(*) FROM song_media WHERE song_id=$song")===1,'Cascaded deletions also roll back');
    db_query($db,'DELETE FROM songs WHERE song_id=?',[$song]);
    foreach (['songs','movie_song','song_people','song_media','song_trivia','song_keywords'] as $table) { check_backend(scalar($db,"SELECT COUNT(*) FROM $table WHERE song_id=$song")===0,$table.' cleaned on song deletion'); }
    db_query($db,'DELETE FROM people WHERE people_id=?',[$person]);
    foreach (['people','movie_people','song_people','people_trivia'] as $table) { check_backend(scalar($db,"SELECT COUNT(*) FROM $table WHERE people_id=$person")===0,$table.' cleaned on person deletion'); }
    check_backend(scalar($db,"SELECT COUNT(*) FROM movies WHERE movie_id=$movie")===1,'Deleting person preserves related movies');
    db_query($db,'DELETE FROM movies WHERE movie_id=?',[$movie]);
    foreach (['movies','movie_anagrams','movie_data','movie_numbers','movie_trivia','movie_quotes','movie_media','movie_keywords','movie_people','movie_song'] as $table) { check_backend(scalar($db,"SELECT COUNT(*) FROM $table WHERE movie_id=$movie")===0,$table.' cleaned on movie deletion'); }
    $legacy=fixture_database(__DIR__.'/fixtures/week6-schema.sql');
    $legacy->query("INSERT INTO movies VALUES (42,'legacy','legacy',2020)");
    $legacy->query("INSERT INTO movie_anagrams VALUES (42,0,'one'),(42,0,'two')");
    $legacy->query("INSERT INTO movie_people VALUES (42,999,'Actor','orphan')");
    $account=$legacy->prepare('INSERT INTO users (id,email,password,role) VALUES (?,?,?,?)');
    $accountId=7; $accountEmail='legacy@example.invalid'; $accountRole='ADMIN';
    $account->bind_param('isss',$accountId,$accountEmail,$hash,$accountRole); $account->execute();
    $userBefore=$legacy->query('SELECT * FROM users')->fetch_all(MYSQLI_ASSOC);
    $plan=migration_preflight($legacy);
    check_backend(!$plan['blockers'] && count($plan['repairs'])===2,'Migration reports known legacy defects');
    rejects(function () use($legacy) { migration_apply($legacy,false); },'Migration refuses unapproved archival repairs');
    check_backend(scalar($legacy,'SELECT COUNT(*) FROM movie_people')===1,'Preflight failure changes no data');
    $legacy->query("INSERT INTO movies VALUES (42,'duplicate','duplicate',2020)");
    check_backend((bool)migration_preflight($legacy)['blockers'],'Ambiguous duplicate entity IDs block migration');
    $legacy->query("DELETE FROM movies WHERE native_name='duplicate'");
    migration_apply($legacy,true);
    check_backend(scalar($legacy,'SELECT movie_id FROM movies')===42,'Migration preserves movie IDs');
    check_backend($legacy->query('SELECT * FROM users')->fetch_all(MYSQLI_ASSOC)===$userBefore,'Migration preserves account IDs, roles, emails and password hashes');
    $next=db_transaction($legacy,function () use($legacy) { return catalog_insert_movie($legacy,['native_name'=>'after upgrade','english_name'=>'after upgrade','year'=>2020]); });
    check_backend($next>42,'Migrated AUTO_INCREMENT continues beyond existing IDs');
    check_backend(scalar($legacy,'SELECT COUNT(DISTINCT anagram_id) FROM movie_anagrams')===2,'Migration assigns unique anagram IDs without losing text');
    check_backend(scalar($legacy,'SELECT COUNT(*) FROM omdb_migration_archive')===3,'All repaired/quarantined original rows archived');
    check_backend(scalar($legacy,'SELECT COUNT(*) FROM movie_people')===0,'Invalid relationship quarantined');
    check_backend(catalog_contract($legacy)===$freshContract,'Upgrade and fresh setup produce equivalent schema contracts');
    migration_apply($legacy,true);
    check_backend(scalar($legacy,'SELECT COUNT(*) FROM omdb_migration_archive')===3,'Repeat migration is idempotent');
    $legacy->query('DELETE FROM movies WHERE movie_id=42');
    check_backend(scalar($legacy,'SELECT COUNT(*) FROM movie_anagrams')===0,'Migrated foreign keys cascade');
    echo json_encode(['passed'=>$checks,'failed'=>0],JSON_PRETTY_PRINT),PHP_EOL;
} catch (Throwable $error) {
    fwrite(STDERR,(string)$error.PHP_EOL); $failed=1;
} finally {
    foreach ($databases as $name) {
        if (preg_match('/^omdb_backend_[a-f0-9]{12}_test$/D',$name)) { $server->query("DROP DATABASE `$name`"); }
    }
}
exit($failed);
