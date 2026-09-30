<?php

require_once('initialize.php');

$db->set_charset("utf8mb4");

$sql = "SELECT
            song_id,
            title,
            lyrics,
            theme
        FROM songs";

$result = $db->query($sql);

$data = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "song_id" => $row["song_id"],
            "title"    => $row["title"],
            "lyrics"   => $row["lyrics"],
            "theme"    => $row["theme"]
        );
    }

    $result->free();
}

header('Content-Type: application/json; charset=utf-8');

echo json_encode(
    array("data" => $data),
    JSON_UNESCAPED_UNICODE
);

?>