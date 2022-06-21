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
  if(isset($_GET['people_id'])){
    $id = $_GET['people_id'];
    $query = mysqli_query($db,"DELETE FROM `people` WHERE people_id = '$id'");
    if($query){
      db_disconnect($db);
      header('location: people.php?delete=Success');
    }
  }
  db_disconnect($db);
    
?>
