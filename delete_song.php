<?php
  session_start();
 if (!isset($_SESSION['username']))
      { 
       header("Location: login.php");
       exit();
      }     
?>
<?php
  include("./nav.php");
  $total_elements = $_POST['total_elements'];

  $movie_id = $_POST['movie_id'];

  for($i=1 ; $i<$total_elements ; $i++){
    if(!empty($_POST['song_id'.$i.''])){
      $id = $_POST['song_id'.$i.''];
      $query = "delete from movie_song where id='$id'";
      $result = mysqli_query($db,$query);
      $query = "delete from song_people where id='$id'";
      $result = mysqli_query($db,$query);
      $query = "delete from movie_song where id='$id'";
      $result = mysqli_query($db,$query);
      $query = "delete from songs where id='$id'";
      $result = mysqli_query($db,$query);
    }
    else{
      // break;
    }
  }
db_disconnect($db);
header("location:add_song.php?movie_id=$movie_id")

?>
