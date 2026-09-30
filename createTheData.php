<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once("./database.php");

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$db = db_connect();

if (!$db) {
    die("Database connection failed.");
}

$db->set_charset("utf8mb4");

/*
 * This file should only process submitted forms.
 */
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    db_disconnect($db);
    header("Location: movies.php");
    exit();
}

/*
 * Validate movie ID.
 */
$movie_id = filter_input(
    INPUT_POST,
    'movie_id',
    FILTER_VALIDATE_INT
);

if (!$movie_id || $movie_id < 1) {
    db_disconnect($db);
    header("Location: movies.php");
    exit();
}

/*
 * Make sure the movie actually exists.
 */
$stmt = $db->prepare(
    "SELECT movie_id
     FROM movies
     WHERE movie_id = ?
     LIMIT 1"
);

$stmt->bind_param("i", $movie_id);
$stmt->execute();

$result = $stmt->get_result();

if (!$result->fetch_assoc()) {
    $stmt->close();
    db_disconnect($db);

    header("Location: movies.php");
    exit();
}

$stmt->close();

/*
 * Read submitted values safely.
 */
$anagram          = trim($_POST['anagram'] ?? '');
$keyword          = trim($_POST['keyword'] ?? '');
$movie_quote_name = trim($_POST['movie_quote_name'] ?? '');
$movie_trivia_name = trim($_POST['movie_trivia_name'] ?? '');
$m_link            = trim($_POST['m_link'] ?? '');
$m_link_type       = trim($_POST['m_link_type'] ?? '');
$language          = trim($_POST['language'] ?? '');
$country           = trim($_POST['country'] ?? '');
$genre             = trim($_POST['genre'] ?? '');
$plot              = trim($_POST['plot'] ?? '');
$tag_line          = trim($_POST['tag_line'] ?? '');


/*
 * Helper for tables that still use manually assigned IDs.
 */
function get_next_id(
    mysqli $db,
    string $table,
    string $column
): int {

    $allowed = array(
        'movie_trivia' => 'movie_trivia_id',
        'movie_quotes' => 'movie_quote_id',
        'movie_media'  => 'movie_media_id'
    );

    if (
        !isset($allowed[$table]) ||
        $allowed[$table] !== $column
    ) {
        throw new Exception("Invalid ID request.");
    }

    $sql =
        "SELECT COALESCE(MAX(`$column`), 0) + 1 AS next_id
         FROM `$table`";

    $result = $db->query($sql);
    $row = $result->fetch_assoc();

    return (int)$row['next_id'];
}


try {

    $db->begin_transaction();

    /*
     * Movie data
     */
    if (
        $language !== '' ||
        $country !== '' ||
        $genre !== '' ||
        $plot !== '' ||
        $tag_line !== ''
    ) {

        $stmt = $db->prepare(
            "INSERT INTO movie_data
            (
                movie_id,
                language,
                country,
                genre,
                plot,
                tag_line
            )
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "isssss",
            $movie_id,
            $language,
            $country,
            $genre,
            $plot,
            $tag_line
        );

        $stmt->execute();
        $stmt->close();
    }


    /*
     * Trivia
     */
    if ($movie_trivia_name !== '') {

        $movie_trivia_id = get_next_id(
            $db,
            'movie_trivia',
            'movie_trivia_id'
        );

        $stmt = $db->prepare(
            "INSERT INTO movie_trivia
            (
                movie_id,
                movie_trivia_id,
                movie_trivia_name
            )
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "iis",
            $movie_id,
            $movie_trivia_id,
            $movie_trivia_name
        );

        $stmt->execute();
        $stmt->close();
    }


    /*
     * Media
     *
     * Require both values so an incomplete media record
     * is not added.
     */
    if ($m_link !== '' && $m_link_type !== '') {

        $movie_media_id = get_next_id(
            $db,
            'movie_media',
            'movie_media_id'
        );

        $stmt = $db->prepare(
            "INSERT INTO movie_media
            (
                movie_id,
                m_link,
                m_link_type,
                movie_media_id
            )
            VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "issi",
            $movie_id,
            $m_link,
            $m_link_type,
            $movie_media_id
        );

        $stmt->execute();
        $stmt->close();
    }


    /*
     * Keyword
     */
    if ($keyword !== '') {

        $stmt = $db->prepare(
            "INSERT INTO movie_keywords
            (
                movie_id,
                keyword
            )
            VALUES (?, ?)"
        );

        $stmt->bind_param(
            "is",
            $movie_id,
            $keyword
        );

        $stmt->execute();
        $stmt->close();
    }


    /*
     * Quote
     */
    if ($movie_quote_name !== '') {

        $movie_quote_id = get_next_id(
            $db,
            'movie_quotes',
            'movie_quote_id'
        );

        $stmt = $db->prepare(
            "INSERT INTO movie_quotes
            (
                movie_id,
                movie_quote_name,
                movie_quote_id
            )
            VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "isi",
            $movie_id,
            $movie_quote_name,
            $movie_quote_id
        );

        $stmt->execute();
        $stmt->close();
    }


    /*
     * Anagram
     */
    if ($anagram !== '') {

        $stmt = $db->prepare(
            "INSERT INTO movie_anagrams
            (
                movie_id,
                anagram
            )
            VALUES (?, ?)"
        );

        $stmt->bind_param(
            "is",
            $movie_id,
            $anagram
        );

        $stmt->execute();
        $stmt->close();
    }


    /*
     * Everything succeeded.
     */
    $db->commit();

    db_disconnect($db);

    header("Location: movies.php?updated=Success");
    exit();


} catch (Throwable $e) {

    $db->rollback();

    /*
     * Log the technical error instead of exposing it
     * to the browser.
     */
    error_log(
        "createTheData.php error: " .
        $e->getMessage()
    );

    db_disconnect($db);

    header("Location: movies.php?updated=Failed");
    exit();
}