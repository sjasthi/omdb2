<?php

session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include("./nav.php");

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: create_movie.php");
    exit();
}

// Get and clean form input
$movie  = trim($_POST['english_name'] ?? '');
$native = trim($_POST['native_name'] ?? '');
$year   = trim($_POST['year'] ?? '');

// Validate required fields
if ($movie === '' || $native === '' || $year === '') {
    exit("All movie fields are required.");
}

// Validate year
if (!ctype_digit($year)) {
    exit("Year must be a valid number.");
}

$year = (int)$year;

if ($year < 1888 || $year > ((int)date('Y') + 10)) {
    exit("Please enter a valid movie year.");
}

// Prepare native name for the existing API calls
$nativeJSON = strtolower(str_replace(" ", "", $native));


// --------------------------------------------------
// Get next movie ID
// --------------------------------------------------

$result = mysqli_query(
    $db,
    "SELECT movie_id FROM movies ORDER BY movie_id DESC LIMIT 1"
);

if (!$result) {
    exit("Unable to determine the next movie ID.");
}

$row = $result->fetch_assoc();

$id = $row ? ((int)$row['movie_id'] + 1) : 1;


// --------------------------------------------------
// Insert movie using a prepared statement
// --------------------------------------------------

$stmt = mysqli_prepare(
    $db,
    "INSERT INTO movies
        (movie_id, native_name, english_name, year_made)
     VALUES (?, ?, ?, ?)"
);

if (!$stmt) {
    exit("Unable to prepare movie insert.");
}

mysqli_stmt_bind_param(
    $stmt,
    "issi",
    $id,
    $native,
    $movie,
    $year
);

if (!mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    exit("Unable to create movie.");
}

mysqli_stmt_close($stmt);

// We already know the movie ID because we inserted it
$movie_id = $id;


// --------------------------------------------------
// Existing API calls for movie_numbers
// --------------------------------------------------

$base_chars = '';
$length = 0;

// Get base characters
$jsonLog =
    "http://indic-wp.thisisjava.com/api/getBaseCharacters.php?string=" .
    urlencode($nativeJSON) .
    "&language=Telugu";

$jsonfile = @file_get_contents($jsonLog);

if ($jsonfile !== false) {
    $jsonStart = strstr($jsonfile, '{');

    if ($jsonStart !== false) {
        $decodedData = json_decode($jsonStart);

        if (
            $decodedData &&
            isset($decodedData->data) &&
            is_array($decodedData->data)
        ) {
            $base_chars = implode(", ", $decodedData->data);
        }
    }
}


// Get string length
$jsonLength =
    "http://indic-wp.thisisjava.com/api/getLength.php?string=" .
    urlencode($nativeJSON) .
    "&language=English";

$jsonfile = @file_get_contents($jsonLength);

if ($jsonfile !== false) {
    $jsonStart = strstr($jsonfile, '{');

    if ($jsonStart !== false) {
        $decodedData = json_decode($jsonStart);

        if ($decodedData && isset($decodedData->data)) {
            $length = (int)$decodedData->data;
        }
    }
}


// --------------------------------------------------
// Insert movie number information
// --------------------------------------------------

$stmt = mysqli_prepare(
    $db,
    "INSERT INTO movie_numbers
        (movie_id, length, base_chars)
     VALUES (?, ?, ?)"
);

if ($stmt) {

    mysqli_stmt_bind_param(
        $stmt,
        "iis",
        $movie_id,
        $length,
        $base_chars
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}


// --------------------------------------------------
// Finish
// --------------------------------------------------

db_disconnect($db);

header("Location: movies.php?create=Success");
exit();

?>