<?php
include('./nav.php');

$id = intval($_GET['movie_id']);

$sql2 = 'delete from movie_media where movie_id = '.$id.';';
mysqli_query($db, $sql2);
db_disconnect($db);
?>
