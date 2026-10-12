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

  $movie_id = $_POST['movie_id'];

  for($i=1 ; $i<$total_elements ; $i++){
    if(!empty($_POST['movie_people_id'.$i.''])){
      $id = $_POST['movie_people_id'.$i.''];
      $query = ['sql' => 'DELETE FROM `movie_people` WHERE movie_people_id =?', 'params' => [$id]];
      $result = db_query($db, $query);
    }
    else{
      // break;
    }
  }
db_disconnect($db);
header("location:add_movie_people.php?movie_id=$movie_id")

?>
