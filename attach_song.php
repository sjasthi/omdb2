<?php
    include("./nav.php");
    $movie_id = $_POST['movie_id'];
    $total_elements = $_POST['total_elements'];
    for($i=1 ; $i<$total_elements ; $i++){
      if(!empty($_POST['song_id'.$i.''])){
      $song_id[$i] = $_POST['song_id'.$i.''];
      $query = "INSERT INTO `movie_song`(`movie_id`, `song_id`) VALUES ('$movie_id','$song_id[$i]')";
    $result = mysqli_query($db,$query);
    }
    else{
      // break;
    }

    }
     header("location:add_song.php?movie_id=$movie_id");
    db_disconnect($db);
?>
