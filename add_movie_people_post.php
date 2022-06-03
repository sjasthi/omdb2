<?php
    include("./nav.php");
    
    $movie_id = $_POST['movie_id'];

    $total_elements = $_POST['total_elements'];
    

    if(isset($_POST['lead_actor'])){
      $role = "lead_actor";
    }

    if(isset($_POST['lead_actress']))
    {
        $role = "lead_actress";
    }

    if(isset($_POST['supporting_actor'])){
        $role = "supporting_actor";
    }

    if(isset($_POST['supporting_actress']))
    {
        $role = "supporting_actress";
    }

    if(isset($_POST['producer'])){
        $role = "producer";
    }

    if(isset($_POST['director']))
    {
        $role = "director";
    }

    if(isset($_POST['music_director']))
    {
        $role = "music_director";
    }




    for($i=1 ; $i<$total_elements ; $i++){
      if(!empty($_POST['people_id'.$i.''])){
      $people_id[$i] = $_POST['people_id'.$i.''];
      $query = "INSERT INTO `movie_people`(`movie_id`, `people_id`, `role`) VALUES ('$movie_id','$people_id[$i]','$role')";
       $result = mysqli_query($db,$query);
       // print_r($result);
       // exit();
    }
    else{
      // break;
    }
    db_disconnect($db);
    }
     header("location:add_movie_people.php?movie_id=$movie_id");

?>
