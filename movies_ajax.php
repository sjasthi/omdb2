<?php

require_once('initialize.php');

$db->set_charset("utf8mb4");

$year = $_GET['year'] ?? '';

$data = array();

// If a valid year was supplied, only return movies from that year.
if ($year !== '' && preg_match('/^\d{4}$/', $year)) {

    $stmt = $db->prepare("
        SELECT
            movie_id,
            native_name,
            english_name,
            year_made
        FROM movies
        WHERE year_made = ?
        ORDER BY movie_id
    ");

    $stmt->bind_param("s", $year);
    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "movie_id"     => $row["movie_id"],
            "native_name"  => $row["native_name"],
            "english_name" => $row["english_name"],
            "year_made"    => $row["year_made"]
        );
    }

    $stmt->close();

} else {

    // Normal Movies page: return every movie.
    $stmt = $db->prepare("
        SELECT
            movie_id,
            native_name,
            english_name,
            year_made
        FROM movies
        ORDER BY movie_id
    ");

    $stmt->execute();

    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "movie_id"     => $row["movie_id"],
            "native_name"  => $row["native_name"],
            "english_name" => $row["english_name"],
            "year_made"    => $row["year_made"]
        );
    }

    $stmt->close();
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode(array(
    "data" => $data
));
?>