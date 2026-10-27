<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
?>
<?php

if (session_status() == PHP_SESSION_NONE) {
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
$stmt = db_prepare($db, "SELECT movie_id
     FROM movies
     WHERE movie_id = ?
     LIMIT 1");

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
require_once __DIR__ . '/catalog.php';
try {
    $anagram=catalog_text($_POST,'anagram',180);
    $keyword=catalog_text($_POST,'keyword',15);
    $movie_quote_name=catalog_text($_POST,'movie_quote_name',300);
    $movie_trivia_name=catalog_text($_POST,'movie_trivia_name',300);
    $m_link=catalog_text($_POST,'m_link',100);
    $m_link_type=catalog_text($_POST,'m_link_type',10);
    $language=catalog_text($_POST,'language',10);
    $country=catalog_text($_POST,'country',20);
    $genre=catalog_text($_POST,'genre',15);
    $plot=catalog_text($_POST,'plot',500);
    $tag_line=catalog_text($_POST,'tag_line',100);
    if (($m_link === '') !== ($m_link_type === '')) { throw new InvalidArgumentException('Enter both a media link and its type, or leave both fields blank.'); }
} catch (InvalidArgumentException $error) { security_fail(400,$error->getMessage()); }


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

        $stmt = db_prepare($db, "INSERT INTO movie_data
            (
                movie_id,
                language,
                country,
                genre,
                plot,
                tag_line
            )
            VALUES (?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE language=VALUES(language), country=VALUES(country), genre=VALUES(genre), plot=VALUES(plot), tag_line=VALUES(tag_line)");

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

        db_query($db, 'INSERT INTO movie_trivia (movie_id, movie_trivia_name) VALUES (?, ?)', [$movie_id, $movie_trivia_name]);
    }


    /*
     * Media
     *
     * Require both values so an incomplete media record
     * is not added.
     */
    if ($m_link !== '' && $m_link_type !== '') {

        db_query($db, 'INSERT INTO movie_media (movie_id, m_link, m_link_type) VALUES (?, ?, ?)', [$movie_id, $m_link, $m_link_type]);
    }


    /*
     * Keyword
     */
    if ($keyword !== '') {

        $stmt = db_prepare($db, "INSERT INTO movie_keywords
            (
                movie_id,
                keyword
            )
            VALUES (?, ?)");

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

        db_query($db, 'INSERT INTO movie_quotes (movie_id, movie_quote_name) VALUES (?, ?)', [$movie_id, $movie_quote_name]);
    }


    /*
     * Anagram
     */
    if ($anagram !== '') {

        $stmt = db_prepare($db, "INSERT INTO movie_anagrams
            (
                movie_id,
                anagram
            )
            VALUES (?, ?)");

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