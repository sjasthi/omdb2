

<?php

  session_start();
  // set the current page to one of the main buttons
  $nav_selected = "";

  // make the left menu buttons visible; options: YES, NO
  $left_buttons = "NO";

  // set the left menu button selected; options will change based on the main selection
  $left_selected = "";
  include("./nav.php");
?>

<html>

<head>
<style>
  table.center {
      margin-left:auto;
      margin-right:auto;
    }

    .container{
      width: 300px;           /* Set this to your convenience */
      height: 200px;          /* Set this to your convenience */
      position: absolute;
      top: 50%;
      left: 50%;
      margin-top: -100px;     /* Half of height */
      margin-left: -150px;
  }​

</style>
</head>
<body>

<div class = "container">

  <h1 style = "color: #01B0F1;">Sign up</h1>
  <form action="sign_up.php" method="POST">
    <div class="form-group">
      <label for="fname">First Name</label>
      <input type="textarea" class="form-control" id="fName" name="first_name" aria-describedby="emailHelp" placeholder="Enter first name">
    </div>
    <div class="form-group">
      <label for="lname">Last Name</label>
      <input type="textarea" class="form-control" id="lName" name="last_name" aria-describedby="emailHelp" placeholder="Enter last name">
    </div>
    <div class="form-group">
      <label for="email">Email address</label>
      <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>
    <div class="form-group">
      <label for="password">Password</label>
      <input type="password" class="form-control" id="password" name="password" placeholder="Password">
    </div>
    <div class="form-group">
      <label for="repeat_password">Repeat Password</label>
      <input type="password" class="form-control" id="repeat_password" name="repeat_password" placeholder="Repeat password">
    </div>
    <div class="form-group form-check">
      <input type="checkbox" class="form-check-input" id="exampleCheck1">
      <label class="form-check-label" for="exampleCheck1">Remember me</label>
    </div>
    <button type="submit" class="btn btn-primary">Sign Up</button>
  </form>
</div>

</body>



<?php

    if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      // $my_email = mysqli_real_escape_string($db,$_POST["email"]);
      // $my_password = mysqli_real_escape_string($db,$_POST["password"]); 

      $my_first_name = $_POST["first_name"];
      $my_last_name = $_POST["last_name"];
      $my_email = mysqli_real_escape_string($db, $_POST['email']);
      $my_password = mysqli_real_escape_string($db, $_POST['password']);
      $my_repeat_password = $_POST["repeat_password"];

      $sql = "INSERT INTO users (password, email, first_name, last_name) VALUES ('$my_password','$my_email','$my_first_name', '$my_last_name')";

      echo $sql;

      $result = $db->query($sql);

    }

    db_disconnect($db);
    include("./footer.php");
?>