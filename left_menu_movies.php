<head>
<link rel="stylesheet" href="styles/mainStyleSheet.css">
</head>
<div id="menu-left">

<a href="movies.php">
  	<div <?php if($left_selected == "MOVIES")
  	{ echo 'class="menu-left-current-page"'; } ?> >
  	<img src="./images/movies.png">
  	<br/>Movies<br/></div>
  </a>


  <a href="movies_data.php">
  	<div <?php if($left_selected == "DATA")
  	{ echo 'class="menu-left-current-page"'; } ?> >
  	<img src="./images/data.png">
  	<br/>Data<br/></div>
  </a>

  <a href = "movies_anagram.php">
    <div <?php if($left_selected == "ANAGRAM")
    { echo 'class="menu-left-current-page"'; } ?> >
    <img src="./images/anagram.png">
    <br/>Anagram<br/></div>
  </a>

  <a href = "movies_keywords.php">
    <div <?php if($left_selected == "KEYWORD")
    { echo 'class="menu-left-current-page"'; } ?> >
    <img src="./images/keyword.png">
    <br/>Keyword<br/></div>
  </a>

  <a href = "movies_media.php">
    <div <?php if($left_selected == "MEDIA")
    { echo 'class="menu-left-current-page"'; } ?> >
    <img src="./images/media.png">
    <br/>MEDIA<br/></div>
  </a>

  <a href = "movies_numbers.php">
    <div <?php if($left_selected == "NUMBERS")
    { echo 'class="menu-left-current-page"'; } ?> >
    <img src="./images/number.png">
    <br/>Numbers<br/></div>
  </a>

  <a href = "movies_quotes.php">
    <div <?php if($left_selected == "QUOTES")
    { echo 'class="menu-left-current-page"'; } ?> >
    <img src="./images/quote.png">
    <br/>Quotes<br/></div>
  </a>

  <a href = "movies_trivia.php">
    <div <?php if($left_selected == "TRIVIA")
    { echo 'class="menu-left-current-page"'; } ?> >
    <img src="./images/trivia.png">
    <br/>Trivia<br/></div>
  </a><br>


  <a href = "query_movies.php">
    <div <?php if($left_selected == "SEARCH")
    { echo 'class="menu-left-current-page"'; } ?> >
    <i class="fa fa-search" aria-hidden="true"></i>
    <br/>Search People's<br/></div>
  </a>


</div>
