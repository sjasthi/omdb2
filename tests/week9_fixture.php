<?php
// CLI only: this fixture never reads an application's local configuration.
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$name = getenv('OMDB_TEST_DB');
if (!is_string($name) || !preg_match('/^omdb_week9_[a-f0-9]{16}_test$/D', $name)) {
    throw new RuntimeException('Refusing a database outside the Week 9 test namespace.');
}
$db = new mysqli(getenv('OMDB_TEST_DB_SERVER') ?: '127.0.0.1', getenv('OMDB_TEST_DB_USER') ?: 'root', getenv('OMDB_TEST_DB_PASSWORD') ?: '');
if (($argv[1] ?? '') === '--drop') { $db->query("DROP DATABASE `$name`"); exit; }
// CREATE deliberately fails if the name already exists; never erase another database.
$db->query("CREATE DATABASE `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
try {
    $db->select_db($name); $db->set_charset('utf8mb4');
    $db->multi_query(file_get_contents(dirname(__DIR__) . '/sql/schema.sql'));
    do { if ($r = $db->store_result()) { $r->free(); } } while ($db->more_results() && $db->next_result());
    foreach (['ADMIN', 'USER'] as $role) {
        $email = getenv('OMDB_TEST_' . $role . '_EMAIL');
        $password = getenv('OMDB_TEST_' . $role . '_PASSWORD');
        if (!$email || !$password) { throw new RuntimeException('Missing ephemeral test account.'); }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $db->prepare('INSERT INTO users (email,password,role) VALUES (?,?,?)');
        $stmt->bind_param('sss', $email, $hash, $role); $stmt->execute(); $stmt->close();
    }
    $db->query("INSERT INTO movies (native_name,english_name,year_made) VALUES ('Demo film 1','Together - search test fixture',2020),('Demo film 2','Actor only - search test fixture',2021),('Demo film 3','Director only - search test fixture',2022)");
    $db->query("INSERT INTO movie_numbers (movie_id,base_chars) VALUES (1,'Demo'),(2,'Demo'),(3,'Demo')");
    $db->query("INSERT INTO people (stage_name,first_name,middle_name,last_name,gender,image_name) VALUES ('Avery Stone','Avery','','Stone','',''),('Morgan River','Morgan','','River','','')");
    $db->query("INSERT INTO movie_people (movie_id,people_id,role,screen_name) VALUES (1,1,'Supporting Actor',''),(1,2,'Director',''),(2,1,'Leading Actor',''),(3,2,'Director','')");
    $db->query("INSERT INTO songs (title,lyrics,theme) VALUES ('Demo song','Demo lyrics','Fixture')");
    $db->query("INSERT INTO movie_song (movie_id,song_id) VALUES (1,1)");
    $db->query("INSERT INTO song_people (song_id,people_id,role) VALUES (1,1,'Singer')");
    $db->query("INSERT INTO movie_anagrams (movie_id,anagram) VALUES (1,'1 mlif omeD')");
} catch (Throwable $error) {
    $db->query("DROP DATABASE `$name`"); throw $error;
}
echo json_encode(['fixture_created'=>true, 'database_server'=>$db->server_info]), PHP_EOL;
