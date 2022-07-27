<?php
require_once('initialize.php'); // Trying to grab the DB connection. This could be changed to anything smaller.

$sql = "SELECT * from people"; // Change this to required SQL
$db->set_charset("utf8");
$result = $db->query($sql);
$data = array();

while($row = mysqli_fetch_array($result)){ // This is how to format array for Datatables + wrap it in final array['data'].
    $sub_array = array();
    $sub_array['people_id'] = $row["people_id"];
    $sub_array['stage_name'] = $row["stage_name"];
    $sub_array['first_name'] = $row["first_name"];
    $sub_array['middle_name'] = $row['middle_name'];
    $sub_array['last_name'] = $row['last_name'];
    $sub_array['gender'] = $row['gender'];
    $sub_array['image_name'] = $row['image_name'];
    $data[] = $sub_array;
}

echo json_encode(array("data" => $data));

?>
