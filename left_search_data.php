<div id="menu-left">

    <form method="post">
  	<div <?php if($left_selected == "SONGS")
  	{ echo 'class="menu-left-current-page"'; } ?>>
    <button type="submit" name="songs"><img src="./images/songs.png">
  	<br/>Songs<br/> </button></div>

</br>
    	<div <?php if($left_selected == "MOVIES")
    	{ echo 'class="menu-left-current-page"'; } ?>>
    	<button type="submit" name="movies"><img src="./images/movies.png">
    	<br/>Movies<br/></button>
    </div>

</br>
      	<div <?php if($left_selected == "PEOPLE")
      	{ echo 'class="menu-left-current-page"'; } ?>>
      	<button type="submit" name="people"><img src="./images/people.png">
      	<br/>People<br/></button>
      </div>
    </form>



</div>
