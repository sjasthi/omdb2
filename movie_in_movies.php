<!DOCTYPE html>
<html>
<style>
/*table, th, td {
  border:1px solid black;
}*/
</style>
	<body>

	<?php
	// We're using sessions to store values.
	if (session_status() == PHP_SESSION_NONE) {
	    session_start();
	}


	if(isset($_GET['telugu'])){
		$_SESSION['language'] = 'native_name';
		//echo 'Is native_name';

	}else{
		$_SESSION['language'] = 'english_name';
		//echo 'Is english_name';
	}

	$nav_selected = "GAMES";
	$left_buttons = "YES";
	$left_selected = "GAMES";

	// Included link to file with api methods used in this file.
	include("./api_methods.php");
	include("./nav.php");
	// Index of letters used for hints.
	$mp_hint_indexes = array();

	// Array of movies for user to solve from.
	$movie_poster_hints = array();
	// An array of movie title strings.

	$movie_name_language = $_SESSION['language'];

	 // getting data from db
	$sql = "SELECT $movie_name_language from movies"; // only grabbing english_name, logic changes if native_name or english_name possible
	$data = array();
	$result = $db->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
			$data[] = $row; // currently has all rows of movie titles
		}
	}

	// A random movie picker the size of the movie list.
	$hidden_movie_name = rand(0, count($data) - 1);

	// Constrants on the size of the title of the movie. Pick again if too big or too small.
	// while(True){
	// 	if(strlen($data[$hidden_movie_name][$movie_name_language]) > 8 || strlen($data[$hidden_movie_name][$movie_name_language]) < 2 ){
	// 		$hidden_movie_name = rand(0, count($data) - 1);
	// 	}else{
	// 		Break;
	// 	}
	// }

	// Picking a random movie title from the array.
	$movie_choice = strtolower($data[$hidden_movie_name][$movie_name_language]);
	$movie_string = $movie_choice;
	$language = $movie_name_language; // Hardcoded for now TODO

	$movie_choice = api_parseToLogicalCharacters($movie_choice,$language);
	// print_r($movie_choice);
	// Randomize movie list to get random hints.
	shuffle($data);
	// Loop through the movie choice array, letter by letter.
	foreach($movie_choice as $letters){

		if($letters == " " or ""){
			// because of the way the api acts, must check for blank nonspaces too.
			//echo "<br> empty space <br>";
			// $movie_poster_hints[] = " ";
		}else{
			// While the count of the loop is less than the length of movie titles...
			//echo "<br>---$letters ---<br>";
			$sql = "SELECT `$language` FROM `movies` WHERE `$language` LIKE '%$letters%'";
			$data = array();
			$result = $db->query($sql);

				if ($result->num_rows > 0) {
				    // output data of each row
				    while($row = $result->fetch_assoc()) {
						$data[] = $row; // currently has all rows of movie titles
					}
				}
			// var_dump($data);

			$count = 0;

			while($count < count($data)){ // total rows
				// ... check if a movie title has the desired letter for the hidden movie choice. If it does...
				// echo "<br>".$count."<br>";
				if(api_containsChar($data[$count][$movie_name_language],$language,$letters)){

				// if(str_contains($data[0][$movie_name_language], $letters)){
					// ... loop through each in the array of movies already picked as clues.
					//echo "match <br>";
					$is_present = false;
					foreach($movie_poster_hints as $poster_in_use){
						// If it is in the hints already, mark as "is present".
						if(strtolower($poster_in_use) == strtolower($data[$count][$movie_name_language]) || strtolower($poster_in_use) == $movie_choice){
							$is_present = true;
							//echo "poster already in hints. <br> <br>";
							Break;
						}

					}
						// If the movie is not already in the hints array, add it and break loop.
						if(!$is_present){
							$movie_poster_hints[] = $data[0][$movie_name_language];
							$spaceless_name = strtolower(str_replace(' ', '', $data[$count][$movie_name_language]));
							$mp_hint_indexes[] = api_indexOf($spaceless_name,$language,$letters) + (1)."/".api_getLengthNoSpaces($data[$count][$movie_name_language],$language);
							//echo "Not yet in hints, adding. <br>";
							Break;
						}
				// If it does not contain the letter required, keep looping.
				} else{
					//echo "searching... <br>";
				}
				// Iterates for how many movies have been found.
				$count += 1;
			}
		}
	}



	$hidden_movie_name = $movie_string; //TODO this came from earlier. this is the random movie.


	// check if random movie has been added AND a user has submitted something
	if(isset($_SESSION['hidden_movie_name']) && isset($_GET['answer'])){
			echo "<h2> Hidden movie name is : ".$_SESSION['hidden_movie_name']." </h2>";

		?>
		<table style="width:100%">
			<tr>
				<?php
			foreach ($_SESSION['poster_array'] as $movie) {
				echo "<td><div style='background-color: lightgrey; width: 100px; height: 150px; border: 5px solid darkgrey; padding: 50px 0 0 0;  margin: auto; text-align: center; '>$movie</div></td>";
			}
	?>
			</tr>

			<tr>
				<?php
			foreach ($_SESSION['hint_array'] as $movie) {
				echo "<td style='text-align: center;'>".$movie."</td>";
			}
			?>
			</tr>
		</table>
			<?php


	}else{																													 // ELSE == first run through
		// store the randomly generated movie so that it can be retrieved.
		echo "<h2> Hidden movie name is : $hidden_movie_name </h2>";
		$_SESSION['hidden_movie_name'] = $hidden_movie_name;
	?>
		<table style="width:100%">
		  	<tr>

		  	<?php
		  	foreach ($movie_poster_hints as $movie){
		  		echo "<td><div style='background-color: lightgrey; width: 100px; height: 150px; border: 5px solid darkgrey; padding: 50px 0 0 0;  margin: auto; text-align: center; '>$movie</div></td>";
		  	}
				// SET POSTER ARRAY
				$_SESSION['poster_array'] = $movie_poster_hints;
		  	?>
		  	</tr>
		   	<tr>

		  	<?php
		  	foreach ($mp_hint_indexes as $movie){
		  		echo "<td style='text-align: center;'>".$movie."</td>";
		  		// echo "<td><img src='img_girl.jpg' alt='Girl in a jacket' width='500' height='600'><td>";
		  	}
				// SET HINT ARRAY
				$_SESSION['hint_array'] = $mp_hint_indexes;
		  	?>

		  	</tr>
		</table>



	<?php
	} // END ELSE
	  // submit to the same page or create a validation interstep
	?>
		<form action ="movie_in_movies.php" method="get">
			<input type="checkbox" id="telugu" name="telugu" value="telugu" onClick = 'this.form.submit() '<?php if(isset($_GET['telugu'])) echo "checked='checked'"; ?>>
				<label>Telugu</label><br>

		</form>

		<form action ="movie_in_movies.php" style="padding: 30px 0 0 0">
			Guess answer: <input type="text" name="answer" value="">
			<button type="submit">Click Me!</button>
		</form>


	<?php
		if(isset($_GET['answer'])){
			if($_GET['answer'] == $_SESSION['hidden_movie_name']){
				echo "<h2> user guess is correct</h2>";
			} else{
				echo "<h2> User did not guess correctly</h2>";
			}
		}



	?>

	<script type="text/javascript">
		$('').click(function(){
			if($(#telugu).is(':checked')){
				<?php $_SESSION['language'] = 'native_name' ?>;
				$(form).submit();
			}else{
				<?php $_SESSION['language'] = 'english_name' ?>;
				$(form).submit();
			}
		});



	</script>

	</body>
</html>
