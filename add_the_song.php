<?php
   
include("./nav.php");
    $songs = [];
    $songs =$_POST['new_songs'];
    $songs_ar = explode(PHP_EOL, $songs);
    $i=0;
    $movie_id =  mysqli_real_escape_string($link, $_POST['movie_id']);
    echo sizeof($songs_ar);
    for ($i ;$i< sizeof($songs_ar); $i++){
        echo $i;
                    echo $songs_ar[$i];
        if(isset($_POST['movie_id'])){
            $sql= "INSERT INTO movie_song(movie_id) values('$movie_id')";
            mysqli_query($db, $sql);
        }
        $sql2= "INSERT INTO songs(title) values('$songs_ar[$i]')";
                 
        mysqli_query($db, $sql2);
         }
   
        
    
   
    
                    
        header('location: movies.php?create=Success');
        db_disconnect($db);
                    
    ?>
    
