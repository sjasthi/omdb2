<?php
  require_once('initialize.php');
?>

<!DOCTYPE html>

<html>
    <head>


        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <!-- jQuery library -->
        <script src="https://code.jquery.com/jquery-3.3.1.js"></script>
        <!-- Latest compiled JavaScript -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        
        <title>Online Movie Database (OMDB)</title>
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
        <link rel="stylesheet" href="styles/mainStyleSheet.css">
        <link rel="stylesheet" href="fonts/css/fontawesome.min.css">
        <link rel="stylesheet" href="fonts/css/all.css">

        <script src='ajax/jquery-3.2.1.min.js' type='text/javascript'></script>
        <script src='ajax/select2/dist/js/select2.min.js' type='text/javascript'></script>
        <link href='ajax/select2/dist/css/select2.min.css' rel='stylesheet' type='text/css'>

    </head>

<body class="body_background">
<div id="wrap">
    <div id="nav">
        <ul>
            <a href="index.php">
              <li class="horozontal-li-logo">
              <img src ="./images/omdb_logo.png">
              <br/>Online Movie Database</li>
            </a>

            <a href="index.php">
              <li <?php if($nav_selected == "HOME"){ echo 'class="current-page"'; } ?>>
              <img src="./images/home.png">
              <br/>Home</li>
            </a>

            <a href="movies.php">
              <li <?php if($nav_selected == "MOVIES"){ echo 'class="current-page"'; } ?>>
                <img src="./images/movies.png">
                <br/>Movies</li>
            </a>

            <a href="people.php">
              <li <?php if($nav_selected == "PEOPLE"){ echo 'class="current-page"'; } ?>>
              <img src="./images/people.png">
              <br/>People</li>
            </a>

            <a href="songs.php">
              <li <?php if($nav_selected == "SONGS"){ echo 'class="current-page"'; } ?>>
                <img src="./images/songs.png">
                <br/>Songs</li>
            </a>

            <a href="reports.php">
              <li <?php if($nav_selected == "REPORTS"){ echo 'class="current-page"'; } ?>>
              <img src="./images/reports.png">
              <br/>Reports</li>
            </a>

           <a href="search_data.php">
              <li <?php if($nav_selected == "search"){ echo 'class="current-page"'; } ?>>
                <img src="./images/search.png">
                <!-- <i class="fa fa-search" aria-hidden="true"></i> -->
                <br/>Search</li>
            </a>


        <a href="setup.php">
          <li <?php if($nav_selected == "SETUP"){ echo 'class="current-page"'; } ?>>
            <img src="./images/setup.png">
            <br/>Setup</li>
        </a>

        <a href="about.php">
          <li <?php if($nav_selected == "ABOUT"){ echo 'class="current-page"'; } ?>>
            <img src="./images/about.png">
            <br/>About</li>
        </a>

        <a href="games.php">
          <li <?php if($nav_selected == "GAMES"){ echo 'class="current-page"'; } ?>>
            <img src="./images/puzzles.png">
            <br/>Games</li>
        </a>

        <a href="help.php">
          <li <?php if($nav_selected == "HELP"){ echo 'class="current-page"'; } ?>>
            <img src="./images/help.png">
            <br/>Help</li>
        </a>

        <li>
        
    </li>

      </ul>
      <br />
    </div>
    <form method="post" action="search.php">
    <input type="search" id="form1" style="width:12%; margin-right:5px; position:relative; right:90px; top:10px; display:inline-block;"name="text_search" class="form-control" />

    <button type="submit" style=" display:inline-block; position:relative; right:90px; top:10px;" name="search" class="btn btn-primary">
    <i class="fa fa-search" aria-hidden="true"></i>
    </button>
    </form>

    <table style="width:1250px">
      <tr>
        <?php
            if ($left_buttons == "YES") {
        ?>

        <td style="width: 120px;" valign="top">
        <?php
            if ($nav_selected == "HOME") {
                include("./index.php");
            } elseif ($nav_selected == "MOVIES") {
                include("./left_menu_movies.php");
            } elseif ($nav_selected == "PEOPLE") {
                include("./left_menu_people.php");
            } elseif ($nav_selected == "SONGS") {
                include("./left_menu_songs.php");
            } elseif ($nav_selected == "REPORTS") {
                include("./left_menu_reports.php");
            } elseif ($nav_selected == "PUZZLES") {
                include("./left_menu_puzzles.php");
            }  elseif ($nav_selected == "SETUP") {
                include("./left_menu_setup.php");
            } elseif ($nav_selected == "ABOUT") {
                include("./left_menu_about.php");
            } elseif ($nav_selected == "GAMES"){
                include("./left_menu_games.php");
            } elseif ($nav_selected == "HELP") {
                include("./left_menu_help.php");
            } elseif ($nav_selected == "search_data") {
                include("./left_search_data.php");
            } else {
                include("./left_menu_movies.php");
            }

        ?>
        </td>
        <td style="width: 1100px;" valign="top">
        <?php
          } else {
        ?>
        <td style="width: 100%;" valign="top">
        <?php
          }
        ?>


