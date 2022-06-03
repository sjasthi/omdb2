<div id="menu-left">

<a href="songs.php">
  	<div <?php if($left_selected == "SONGS")
  	{ echo 'class="menu-left-current-page"'; } ?>>
  	<img src="./images/songs.png">
  	<br/>Songs<br/></div>
  </a>

<a href="songs_trivia.php">
  <div <?php if($left_selected == "TRIVIA")
  {echo 'class="menu-left-current-page"'; } ?>>
  <img src="./images/trivia.png">
  <br/>Trivia<br/></div>
</a>

<a href="songs_keyword.php">
  <div <?php if($left_selected == "KEYWORD")
  {echo 'class="menu-left-current-page"'; } ?>>
  <img src="./images/keyword.png">
  <br/>Keyword<br/></div>
</a>

  <a href = "songs_media.php">
  	<div <?php if($left_selected == "MEDIA")
  	{ echo 'class="menu-left-current-page"'; } ?>>
  	<img src="./images/media.png">
  	<br/>Media<br/></div>
  </a><br>

  <a href = "query_songs.php">
    <div <?php if($left_selected == "SEARCH")
    { echo 'class="menu-left-current-page"'; } ?>>
    <i class="fa fa-search"  aria-hidden="true"></i>
    <br/>Search People<br/></div>
  </a>


</div>
