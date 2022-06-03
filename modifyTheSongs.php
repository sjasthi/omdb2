<?php
include("./nav.php");
  if(isset($_POST['song_id'])){
    $song_id = mysqli_real_escape_string($db, $_POST['song_id']);
    $title = $_POST['title'];
    $lyrics = $_POST['lyrics'];
    $theme = $_POST['theme'];
    $song_trivia_id = [];
    $song_trivia_name_update = [];
    $song_keyword_update = [];
    $song_media_id = [];
    $s_link_update = [];
    $s_link_type_update = [];
      
    echo $song_id;
    foreach($_POST as $k => $v) {
      if(strpos($k, 'song_trivia_name_update') === 0) {
          $song_trivia_name_update[] = $v;
      }
      if(strpos($k, 'song_trivia_id') === 0) {
          $song_trivia_id[] = $v;
      }
      if(strpos($k, 'song_keyword_update') === 0) {
          $song_keyword_update[] = $v;
      }
      if(strpos($k, 's_link_update') === 0) {
          $s_link_update[] = $v;
      }
      if(strpos($k, 's_link_type_update') === 0) {
          $s_link_type_update[] = $v;
      }
      if(strpos($k, 'song_media_id') === 0) {
          $song_media_id[] = $v;
      }
    }

      //Songs Update
      if(isset($_POST['title'])){
          $sql = "UPDATE songs SET
          title='$title', lyrics='$lyrics', theme='$theme' WHERE song_id=$song_id";
          mysqli_query($db , $sql);
      }
      
      if(!empty($song_trivia_id)){
          for ($i = 0; $i < sizeof($song_trivia_id); $i++){
              $sql2 = "UPDATE song_trivia SET song_trivia_name = '$song_trivia_name_update[$i]' WHERE song_id = '$song_id' and song_trivia_id = '$song_trivia_id[$i]'";
              mysqli_query($db, $sql2);
          }
      }
      
      //Song_Keywords Update
      if(!empty($song_keyword_update)){
          for ($i = 0; $i < sizeof($song_keyword_update); $i++){
              $sql3= "UPDATE song_keywords SET keyword = '$song_keyword_update[$i]' WHERE song_id = '$song_id'";
              mysqli_query($db, $sql3);
          }
      }
      
      //Song_Media Update
      if(!empty($song_media_id)){
          for ($i = 0; $i < sizeof($song_media_id); $i++){
              $sql4 = "UPDATE song_media SET s_link = '$s_link_update[$i]', s_link_type = '$s_link_type_update[$i]' WHERE song_id = '$song_id' and song_media_id = '$song_media_id[$i]'";
               mysqli_query($db, $sql4);
          }
      }
      
      db_disconnect($db);
      header('location: songs.php?updated=Success');
  }

?>
