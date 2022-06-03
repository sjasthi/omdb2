<?php
  include("./nav.php");
    $song_id = $_GET['song_id'];
    $query1 = mysqli_query($db,"DELETE FROM movie_song WHERE song_id='$song_id'");
    $query2 = mysqli_query($db,"DELETE FROM song_keywords WHERE song_id='$song_id'");
    $query3 = mysqli_query($db,"DELETE FROM song_matches WHERE song_id='$song_id'");
    $query4 = mysqli_query($db,"DELETE FROM song_media WHERE song_id='$song_id'");
    $query5 = mysqli_query($db,"DELETE FROM song_people WHERE song_id='$song_id'");
    $query6 = mysqli_query($db,"DELETE FROM song_trivia WHERE song_id='$song_id'");
    $query = mysqli_query($db,"DELETE FROM songs WHERE song_id='$song_id'");
    if($query){
    db_disconnect($db);
        header('location: songs.php?delete=Success');
    }
    db_disconnect($db);
?>
