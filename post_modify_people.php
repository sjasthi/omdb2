<?php
include(".\nav.php");
  if(isset($_POST['submit'])){
    $id = $_POST['id'];
    $stage_name = $_POST['stage_name'];
    $first_name = $_POST['first_name'];
    $middle_name = $_POST['middle_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $query = mysqli_query($db,"UPDATE `people` SET `stage_name`='$stage_name',`first_name`='$first_name',`middle_name`='$middle_name',`last_name`='$last_name',
      `gender`='$gender' WHERE people_id='$id'");
      db_disconnect($db);
      if($query){
        echo '<script type="text/javascript">';
        echo 'alert("Data updated successfully");';
        echo 'window.location.href = "people.php";';
        echo '</script>';
      }
  }

?>
