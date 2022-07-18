<div id="menu-left">

<a href="reports.php">
    <div <?php if($left_selected == "REPORTS")
    { echo 'class="menu-left-current-page"'; } ?>>
    <img src="./images/reports.png">
    <br/>Reports<br/></div>
  </a>

<a href="reports_actors.php">
  	<div <?php if($left_selected == "ACTORS")
  	{ echo 'class="menu-left-current-page"'; } ?>>
  	<img src="./images/employee.png">
  	<br/>Actors<br/></div>
  </a>


  <a href="reports_actors_actresses.php">
  	<div <?php if($left_selected == "ACTORS/ACTRESSES")
  	{ echo 'class="menu-left-current-page"'; } ?>>
  	<img src="./images/training_cop.png">
  	<br/>Actors/Actresses<br/></div>
  </a>

  <a href = "reports_lyricists.php">
  	<div <?php if($left_selected == "LYRICIST")
  	{ echo 'class="menu-left-current-page"'; } ?>>
  	<img src="./images/music_notes.png">
  	<br/>Lyricist<br/></div>
  </a>

  


</div>
