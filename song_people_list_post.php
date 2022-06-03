<?php
    include("./nav.php");
    $song_id = $_POST['song_id'];
    $total_elements = $_POST['total_elements'];

    if(isset($_POST['lyricist']))
    {
        $role = "lyricist";
    }

    if(isset($_POST['singer'])){
        $role = "singer";
    }


    if(isset($_POST['music_director']))
    {
        $role = "music_director";
    }




    for($i=1 ; $i<$total_elements ; $i++){
      if(!empty($_POST['people_id'.$i.''])){
      $people_id[$i] = $_POST['people_id'.$i.''];
      $query = "INSERT INTO `song_people`(`song_id`, `people_id`, `role`) VALUES ('$song_id','$people_id[$i]','$role')";
     $result = mysqli_query($db,$query);
    }
    else{
      // break;
    }

    }

    db_disconnect($db);

     header("location:songs_people_list.php?song_id=$song_id");

?>
