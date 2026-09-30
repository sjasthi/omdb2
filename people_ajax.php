<?php

require_once('initialize.php');

$db->set_charset("utf8mb4");

$sql = "SELECT
            people_id,
            stage_name,
            first_name,
            middle_name,
            last_name,
            gender,
            image_name
        FROM people";

$result = $db->query($sql);

$data = array();

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $data[] = array(
            "people_id"   => $row["people_id"],
            "stage_name"  => $row["stage_name"],
            "first_name"  => $row["first_name"],
            "middle_name" => $row["middle_name"],
            "last_name"   => $row["last_name"],
            "gender"      => $row["gender"],
            "image_name"  => $row["image_name"]
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