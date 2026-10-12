<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
?>
<?php
  include("./nav.php");
  $total_elements = $_POST['total_elements'];

  $song_id = $_POST['song_id'];

  for($i=1 ; $i<$total_elements ; $i++){
    if(!empty($_POST['song_people_id'.$i.''])){
      $id = $_POST['song_people_id'.$i.''];
      $query = ['sql' => 'DELETE FROM `song_people` WHERE song_people_id=?', 'params' => [$id]];
      $result = db_query($db, $query);
    }
    else{
      // break;
    }
  }

db_disconnect($db);
header("location:songs_people_list.php?song_id=$song_id")

?>
