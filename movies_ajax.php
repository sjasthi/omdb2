<?php
require_once('initialize.php'); // Trying to grab the DB connection. This could be changed to anything smaller.

$sql = "SELECT * from movies"; // Change this to required SQL
$db->set_charset("utf8");
$result = $db->query($sql);
$data = array();

while($row = mysqli_fetch_array($result)){ // This is how to format array for Datatables + wrap it in final array['data'].
    $sub_array = array();
    $sub_array['movie_id'] = $row["movie_id"];
    $sub_array['native_name'] = $row["native_name"];
    $sub_array['english_name'] = $row["english_name"];
    $sub_array['year_made'] = $row['year_made'];
    $data[] = $sub_array;
}

echo json_encode(array("data" => $data));

?>
