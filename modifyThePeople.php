<?php
include("./nav.php");
  if(isset($_POST['people_id'])){
    $people_id = mysqli_real_escape_string($db, $_POST['people_id']);
    $stage_name = $_POST['stage_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $image_name = $_POST['image_name'];
    $movie_id = [];
    $role_update = [];
    $screen_name_update = [];
    $people_trivia_id = [];
    $people_trivia_name_update = [];
    $song_id = [];
    $song_role_update = [];
      
    foreach($_POST as $k => $v) {
      if(strpos($k, 'movie_id') === 0) {
          $movie_id[] = $v;
      }
      if(strpos($k, 'screen_name_update') === 0) {
          $screen_name_update[] = $v;
      }
      if(strpos($k, 'role_update') === 0) {
          $role_update[] = $v;
      }
      if(strpos($k, 'people_trivia_id') === 0) {
          $people_trivia_id[] = $v;
      }
      if(strpos($k, 'people_trivia_name_update') === 0) {
          $people_trivia_name_update[] = $v;
      }
      if(strpos($k, 'song_media_id') === 0) {
          $song_id[] = $v;
      }
      if(strpos($k, 'song_role_update') === 0) {
          $song_role_update[] = $v;
      }
    }

      //People Update
      if(isset($_POST['stage_name']) && isset($_POST['first_name']) && isset($_POST['middle_name']) && isset($_POST['last_name']) && isset($_POST['gender']) && isset($_POST['image_name'])){
          $sql = "UPDATE people SET stage_name='$stage_name', first_name='$first_name', middle_name='$middle_name', last_name='$last_name', gender='$gender', image_name='$image_name' WHERE people_id='$people_id'";
          mysqli_query($db , $sql);
      }
      
      //People_Trivia Update
      if(!empty($people_trivia_id)){
          for ($i = 0; $i < sizeof($people_trivia_id); $i++){
              $sql2 = "UPDATE people_trivia SET people_trivia_name = '$people_trivia_name_update[$i]' WHERE people_id = '$people_id' and people_trivia_id = '$people_trivia_id[$i]'";
              mysqli_query($db, $sql2);
          }
      }
      
      //Movie_People Update
      if(!empty($movie_id)){
          for ($i = 0; $i < sizeof($movie_id); $i++){
              $sql3= "UPDATE movie_people SET role = '$role_update[$i]', screen_name = '$screen_name_update[$i]' WHERE movie_id = '$movie_id[$i]' and people_id = '$people_id'";
              mysqli_query($db, $sql3);
          }
      }
      
      //Song_People Update
      if(!empty($song_id)){
          for ($i = 0; $i < sizeof($song_id); $i++){
              $sql4 = "UPDATE song_people SET role = '$song_role_update[$i]' WHERE song_id = '$song_id[$i]' and people_id = '$people_id'";
               mysqli_query($db, $sql4);
          }
      }
      
      db_disconnect($db);
      header('location: people.php?updated=Success');
  }

?>
