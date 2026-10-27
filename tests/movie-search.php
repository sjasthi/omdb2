<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/movie_search.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$name = 'omdb_search_' . bin2hex(random_bytes(6)) . '_test';
$server = null; $db = null; $failed = 0; $checks = 0;
function check_search(bool $ok, string $label): void {
    global $checks; $checks++;
    if (!$ok) { throw new RuntimeException('FAIL: ' . $label); }
}
function search_ids(mysqli $db, array $input): array {
    return array_map('intval', array_column(movie_search_results($db, movie_search_filters($input))['rows'], 'movie_id'));
}
function seed_search(mysqli $db, string $sql, array $values): int {
    $stmt = $db->prepare($sql); $types = str_repeat('s', count($values));
    $stmt->bind_param($types, ...$values); $stmt->execute(); $stmt->close();
    return (int)$db->insert_id;
}
try {
    $server = new mysqli(DB_SERVER, DB_USER, DB_PASS);
    $server->query("CREATE DATABASE `$name` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $db = new mysqli(DB_SERVER, DB_USER, DB_PASS, $name); $db->set_charset('utf8mb4');
    $db->multi_query(file_get_contents(dirname(__DIR__) . '/sql/schema.sql'));
    do { if ($r = $db->store_result()) { $r->free(); } } while ($db->more_results() && $db->next_result());
    $person = static function (string $stage, string $first = '', string $middle = '', string $last = '') use ($db): int {
        return seed_search($db, 'INSERT INTO people (stage_name,first_name,middle_name,last_name,gender,image_name) VALUES (?,?,?,?,?,?)', [$stage,$first,$middle,$last,'','']);
    };
    $movie = static function (string $title) use ($db): int {
        return seed_search($db, 'INSERT INTO movies (native_name,english_name,year_made) VALUES (?,?,?)', ['నమస్తే',$title,2020]);
    };
    $credit = static function (int $movie, int $person, string $role, string $screen = '') use ($db): void {
        seed_search($db, 'INSERT INTO movie_people (movie_id,people_id,role,screen_name) VALUES (?,?,?,?)', [$movie,$person,$role,$screen]);
    };
    $actor = $person("Avery O'Neil", 'Avery', '', "O'Neil");
    $director = $person('Morgan Stage', 'Morgan', 'Lee', 'River');
    $actress = $person('Casey Star'); $producer = $person('Taylor Producer'); $music = $person('Robin Music');
    $both = $movie('Combined <b>Fixture</b> 100%_!');
    foreach ([[$actor,'Leading Actor'],[$director,'Director'],[$actress,'Lead Actress'],[$producer,'Producer'],[$music,'Music Director']] as [$p,$r]) { $credit($both,$p,$r); }
    $credit($both,$actor,'supporting_actor'); $credit($both,$actor,'Leading Actor','second screen name');
    $actorOnly = $movie('Actor only'); $credit($actorOnly,$actor,'Actor');
    $directorOnly = $movie('Director only'); $credit($directorOnly,$director,'Director');
    $wrongRole = $movie('Wrong roles'); $credit($wrongRole,$actor,'Director'); $credit($wrongRole,$director,'Music Director');
    $support = $movie('Supporting fixture'); $credit($support,$actor,'Supporting Actor'); $credit($support,$director,'director');
    $legacy = $movie('Legacy fixture'); $credit($legacy,$actor,'lead_actor'); $credit($legacy,$director,'Director and produce');
    $composite = $movie('Composite fixture'); $credit($composite,$actor,'Cameo'); $credit($composite,$director,'Director, writer and');
    $bothRoles = $movie('One person with two roles'); $credit($bothRoles,$actor,'Lead Actor'); $credit($bothRoles,$actor,'Director');
    $all = ['movie_search'=>'Combined','cast_member'=>"Avery O'Neil",'director'=>'Morgan Lee River',
        'lead_actor'=>"Avery O'Neil",'lead_actress'=>'Casey','producer'=>'Taylor','music_director'=>'Robin'];
    // Exercise every subset of the seven filters: adding a filter must keep applying the others.
    $keys = array_keys($all);
    for ($mask = 1; $mask < (1 << count($keys)); $mask++) {
        $input = [];
        foreach ($keys as $bit => $key) { if ($mask & (1 << $bit)) { $input[$key] = $all[$key]; } }
        $ids = search_ids($db,$input);
        check_search(in_array($both,$ids,true), 'Matching fixture survives filter combination ' . $mask);
        if (isset($input['movie_search']) || isset($input['lead_actress']) || isset($input['producer']) || isset($input['music_director'])) {
            check_search($ids === [$both], 'Narrowing filter excludes unrelated movies ' . $mask);
        }
    }
    $ids = search_ids($db,['cast_member'=>"Avery O'Neil",'director'=>'Morgan']); sort($ids);
    $expected = [$both,$support,$legacy,$composite]; sort($expected);
    check_search($ids === $expected, 'AND joins cast and director on the same movie, including legacy roles');
    check_search(search_ids($db,['cast_member'=>"O'Neil",'director'=>"O'Neil"]) === [$bothRoles], 'One person can hold both requested roles');
    check_search(search_ids($db,['movie_search'=>'100%_!']) === [$both], 'LIKE wildcards and escape characters are literal');
    check_search(search_ids($db,['cast_member'=>"' OR 1=1 --"]) === [], 'SQL injection remains a bound name');
    check_search(search_ids($db,['director'=>'missing person']) === [], 'Unknown names return no results');
    $caseIds = search_ids($db,['cast_member'=>'avery o\'neil','director'=>'MORGAN']); sort($caseIds);
    check_search($caseIds === $expected, 'Names are case insensitive and partially match');
    check_search(count(search_ids($db,['cast_member'=>"Avery O'Neil",'movie_search'=>'Combined'])) === 1, 'Multiple matching credits never duplicate a movie');
    check_search(search_ids($db,['movie_search'=>'నమస్తే']) !== [], 'Native Unicode titles are searchable');
    check_search(movie_search_filters(['cast_member'=>" Avery\t O'Neil "])['cast_member'] === "Avery O'Neil", 'Whitespace is normalized');
    foreach (['movies with Brad Pitt directed by Steven Spielberg', 'movies that have Brad Pitt and is directed by Steven Spielberg',
        'Show me films starring Brad Pitt and directed by Steven Spielberg.', 'movies directed by Steven Spielberg with Brad Pitt'] as $phrase) {
        $parsed = movie_search_phrase($phrase);
        check_search($parsed !== null && $parsed['cast_member'] === 'Brad Pitt' && $parsed['director'] === 'Steven Spielberg', 'Phrase extracts independent cast and director names');
    }
    foreach (['Brad Pitt', 'A Movie With Friends', 'movies with Brad Pitt', 'movies directed by Spielberg'] as $phrase) {
        check_search(movie_search_phrase($phrase) === null, 'Ordinary search is preserved');
    }
    foreach ([['director'=>['invalid']], ['cast_member'=>str_repeat('x',181)], ['movie_search'=>"\xff"]] as $invalid) {
        try { movie_search_filters($invalid); $rejected = false; } catch (InvalidArgumentException $error) { $rejected = true; }
        check_search($rejected, 'Malformed, excessive or invalid UTF-8 input is rejected');
    }
    ob_start(); movie_search_form(movie_search_filters(['cast_member'=>'"><script>alert(1)</script>'])); $html = ob_get_clean();
    check_search(strpos($html,'<script>') === false && strpos($html,'&lt;script&gt;') !== false, 'Filter values are HTML escaped');
    $url = movie_search_url(movie_search_filters(['cast_member'=>'A & B','director'=>'C']),2);
    parse_str(parse_url($url,PHP_URL_QUERY),$params);
    check_search($params['cast_member'] === 'A & B' && $params['director'] === 'C' && $params['page'] === '2', 'Share and pagination URLs preserve all filters');
    for ($i=0;$i<55;$i++) { $movie('Pagination ' . $i); }
    $first = movie_search_results($db,movie_search_filters(['movie_search'=>'Pagination']));
    $second = movie_search_results($db,movie_search_filters(['movie_search'=>'Pagination']),2);
    check_search($first['count'] === 55 && count($first['rows']) === 50 && count($second['rows']) === 5, 'Results paginate without losing the total');
    check_search(array_intersect(array_column($first['rows'],'movie_id'),array_column($second['rows'],'movie_id')) === [], 'Pages have no overlapping movies');
    check_search(movie_search_results($db,movie_search_filters(['movie_search'=>'Pagination']),999)['page'] === 2, 'Out-of-range page clamps to the final page');
    $db->begin_transaction(MYSQLI_TRANS_START_READ_ONLY);
    check_search(movie_search_results($db,movie_search_filters($all))['count'] === 1, 'Search works inside a read-only transaction');
    $db->rollback();
    echo json_encode(['passed'=>$checks,'failed'=>0],JSON_PRETTY_PRINT),PHP_EOL;
} catch (Throwable $error) {
    fwrite(STDERR,(string)$error.PHP_EOL); $failed=1;
} finally {
    if ($db) { $db->close(); }
    if ($server && preg_match('/^omdb_search_[a-f0-9]{12}_test$/D',$name)) { $server->query("DROP DATABASE IF EXISTS `$name`"); }
}
exit($failed);
