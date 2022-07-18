

<?php

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
<div class="container">
<h2 style = "color: #01B0F1;">Sign In </h3>


      <form action="login.php" method="post">
        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="email" name="email" class="form-control" />
          <label class="form-label" for="email_label">Email address</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="password" name="password" class="form-control" />
          <label class="form-label" for="password_label">Password</label>
        </div>

        <!-- 2 column grid layout for inline styling -->
        <div class="row mb-4">
          <div class="col d-flex justify-content-center">
            <!-- Checkbox -->
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="checkbox" checked />
              <label class="form-check-label" for="remember_label"> Remember me </label>
            </div>
          </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

        <div class="col">
          <!-- Simple link -->
          <a href="#">Forgot password?</a>
          or
          <a href="sign_up.php">Not signed up?</a>
        </div>

      </form>
</div>




<?php
  if($_SERVER["REQUEST_METHOD"] == "POST") {

        $my_email = mysqli_real_escape_string($db, $_POST['email']);
        $my_password = mysqli_real_escape_string($db, $_POST['password']);

        $sql = "SELECT * FROM users WHERE password = '$my_password' AND email = '$my_email'";

        // $sql = "SELECT native_name, year_made from movies, movie_people, people where `movies`.`movie_id` = `movie_people`.`movie_id` AND `movie_people`.`role` = 'leading actor' AND `people`.`people_id` = `movie_people`.`people_id` AND `people`.`stage_name` = 'Brad Pitt' ";

        echo $sql;

        $result = $db->query($sql);

 // while ($row = $result->fetch_assoc()) {
        //     echo $row['users']."<br>";
        // }

        if (mysqli_num_rows($result) == 0) {
          echo " zero columns";
        }
        else{
          while($row = mysqli_fetch_array( $result)){
          
            echo '<br>'.$row['email'].'<br>';
            $_SESSION['username'] = $my_email;
            $_SESSION['password'] = $my_password;

            header("Location: index.php");


            exit();
          }
        }
          
        
    }
    


    db_disconnect($db);
    include("./footer.php");
?>