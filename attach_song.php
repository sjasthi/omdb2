<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
?>
<?php
    include("./nav.php");
    $movie_id = $_POST['movie_id'];
    $total_elements = $_POST['total_elements'];
    for($i=1 ; $i<$total_elements ; $i++){
      if(!empty($_POST['song_id'.$i.''])){
      $song_id[$i] = $_POST['song_id'.$i.''];
      $query = ['sql' => 'INSERT INTO `movie_song`(`movie_id`, `song_id`) VALUES (?,?)', 'params' => [$movie_id, $song_id[$i]]];
    $result = db_query($db, $query);
    }
    else{
      // break;
    }

    }
     header("location:add_song.php?movie_id=$movie_id");
    db_disconnect($db);
?>
