<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
?>
<?php
   
include("./nav.php");
    $songs = [];
    $songs =$_POST['new_songs'];
    $songs_ar = explode(PHP_EOL, $songs);
    $i=0;
    $movie_id =  $_POST['movie_id'];
    echo h(sizeof($songs_ar));
    for ($i ;$i< sizeof($songs_ar); $i++){
        echo h($i);
                    echo h($songs_ar[$i]);
        if(isset($_POST['movie_id'])){
            $sql= ['sql' => 'INSERT INTO movie_song(movie_id) values(?)', 'params' => [$movie_id]];
            db_query($db, $sql);
        }
        $sql2= ['sql' => 'INSERT INTO songs(title) values(?)', 'params' => [$songs_ar[$i]]];
                 
        db_query($db, $sql2);
         }
   
        
    
   
    
                    
        header('location: movies.php?create=Success');
        db_disconnect($db);
                    
    ?>
    
