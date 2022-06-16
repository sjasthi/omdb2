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


      <form action="user_functions.php" method="post">
        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="email" class="form-control" />
          <label class="form-label" for="email_label">Email address</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="password" class="form-control" />
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

          <div class="col">
            <!-- Simple link -->
            <a href="#">Forgot password?</a>
          </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>


      </form>
</div>




<?php
    db_disconnect($db);
    include("./footer.php");
?>
