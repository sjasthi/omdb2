
<?php $page_title = 'Delete your movie'; ?>
<?php 
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";
    include("./nav.php");

?>
<div class="container">
<style>#title {text-align: center; color: darkgoldenrod;}</style>
<?php
     $id = $_GET['movie_id'];
    $sql10 = "SELECT m_link, m_link_type from movie_media where movie_id = '$id'";
    $query = mysqli_query($db, $sql10);

    if(mysqli_num_rows($query) > 0){
        $row = mysqli_fetch_assoc($query);
    }
    $file_name = 'posters/'.$row['m_link'].'.'.$row['m_link_type'];
    unlink($file_name);
    // Queries to delete from all tables containing movie_id as FK, then from movies
    $sql1 = "DELETE FROM movie_anagrams WHERE movie_id = '$id'";
    $sql2 = "DELETE FROM movie_numbers WHERE movie_id = '$id'";
    $sql3 = "DELETE FROM movie_people WHERE movie_id = '$id'";
    $sql4 = "DELETE FROM movie_data WHERE movie_id = '$id'";
    $sql5 = "DELETE FROM movie_media WHERE movie_id = '$id'";
    $sql6 = "DELETE FROM movie_keywords WHERE movie_id = '$id'";
    $sql7 = "DELETE FROM movie_quotes WHERE movie_id = '$id'";
    $sql8 = "DELETE FROM movie_song WHERE movie_id = '$id'";
    $sql9 = "DELETE FROM movie_trivia WHERE movie_id = '$id'";
    $sql = "DELETE FROM movies WHERE movie_id = '$id'";
    

	


        if (!$db) {
        echo "Error: Unable to connect to MySQL." . PHP_EOL;
        echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
        echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
            
    }
        mysqli_query($db, $sql1);
        mysqli_query($db, $sql2);
        mysqli_query($db, $sql3);
        mysqli_query($db, $sql4);
        mysqli_query($db, $sql5);
        mysqli_query($db, $sql6);
        mysqli_query($db, $sql7);
        mysqli_query($db, $sql8);
        mysqli_query($db, $sql9);
        mysqli_query($db, $sql);
        
        db_disconnect($db);
        header('Location: movies.php?delete=Success');

        ?>










