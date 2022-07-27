<?php
require_once('initialize.php'); // Trying to grab the DB connection. This could be changed to anything smaller.

$sql = "SELECT * from songs"; // Change this to required SQL
$db->set_charset("utf8");
$result = $db->query($sql);
$data = array();

while($row = mysqli_fetch_array($result)){ // This is how to format array for Datatables + wrap it in final array['data'].
    $sub_array = array();
    $sub_array['song_id'] = $row["song_id"];
    $sub_array['title'] = $row["title"];
    $sub_array['lyrics'] = $row["lyrics"];
    $sub_array['theme'] = $row['theme'];
    $data[] = $sub_array;
}

echo json_encode(array("data" => $data));

?>
