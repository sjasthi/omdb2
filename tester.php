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
$nav_selected = "MOVIES";
$left_buttons = "YES";
$left_selected = "MOVIES";


include("./nav.php");
// Index of letters used for hints.
$mp_hint_indexes = array();
// Array of movies for user to solve from.
$movie_poster_hints = array();
// An array of movie title strings.

	 // getting data from db
	$sql = "SELECT english_name from movies"; // only grabbing english_name, logic changes if native_name or english_name possible
 	$data = array();
	$result = $db->query($sql);

	                if ($result->num_rows > 0) {
	                    // output data of each row
	                    while($row = $result->fetch_assoc()) {
												$data[] = $row; // currently has all rows of movie titles
											}
}
// A random number picker the size of the movie list.
$random_number = rand(0, count($data) - 1);

while(True){
	if(strlen($data[$random_number]['english_name']) > 8 ){ // TODO I don't think this is working.
		$random_number = rand(0, count($data) - 1);
	}else{
		Break;
	}
}

// Picking a random movie title from the array.
$movie_choice = strtolower($data[$random_number]['english_name']);
$movie_string = $movie_choice;
$language = "English"; // Hardcoded for now TODO

// URL encode urlencode(), allows for SPACES to be inside Strings passed
$jsonLog = "https://wpapi.telugupuzzles.com/api/parseToLogicalCharacters.php?string=".urlencode($movie_choice)."&language=".urlencode($language);
$jsonfile = file_get_contents($jsonLog);
$decodedData = json_decode(strstr($jsonfile, '{'));

$stdClass_to_array = (array)$decodedData;

$movie_choice = str_split($stdClass_to_array['string']);

// Randomize movie list to get random hints.
//
// Loop through the movie choice array, letter by letter.
foreach($movie_choice as $letters){
	// If the letter is a empty space between words, don't look for match. TODO handle special characters like "-"
	if($letters == " "){
		$movie_poster_hints[] = " ";
	}else{
		// While the count of the loop is less than the length of movie titles...
	$count = 0;
	while($count < count($data)){ // total rows
		// ... check if a movie title has the desired letter for the hidden movie choice. If it does...
		if(str_contains(strtolower($data[$count]['english_name']), $letters)){
			// ... loop through each in the array of movies already picked as clues.
			$is_present = false;
			foreach($movie_poster_hints as $poster_in_use){
				// If it is in the hints already, mark as "is present".
				if(strtolower($poster_in_use) == strtolower($data[$count]['english_name']) || strtolower($poster_in_use) == $movie_choice){
					$is_present = true;
					Break;
				}
			}
			// If the movie is not already in the hints array, add it and break loop.
			if(!$is_present){
				$movie_poster_hints[] = $data[$count]['english_name'];
				$mp_hint_indexes[] = strpos(trim($data[$count]['english_name']), $letters) + (1)."/".(strlen(trim($data[$count]['english_name'])));
				Break;
			}
		// If it does not contain the letter required, keep looping.
		} else{
		}
		// Iterates for how many movies have been found.
		$count += 1;
	}
	}

}

?>

	<table style="width:100%">


<?php


$random_number = $movie_string //TODO this came from earlier. this is the random movie.

?>


<?php

// check if random number has been added AND a user has submitted something
if(isset($_SESSION['random_number']) && isset($_GET['answer'])){ // IF == someone hit submit

	?>

	<tr>
		<?php
	foreach ($_SESSION['poster_array'] as $number) {
		echo "<td><div style='background-color: lightgrey; width: 15px; border: 5px solid darkgrey; padding: 30px;  margin: 20px;'>This is a box.</div></td>";
	}
	?>
	</tr>

	<tr>
		<?php
	foreach ($_SESSION['hint_array'] as $number) {
		echo "<td>".$number."</td>";
	}
	?>
	</tr>
	<?php
	echo "<h2> random number: ".$_SESSION['random_number'];
	echo "<h2> user guess: ".$_GET['answer'];
	if($_GET['answer'] == $_SESSION['random_number']){
		echo "<h2> user guess is correct";
	} else{
		echo "<h2> User did not guess correctly";
	}
}else{																													 // ELSE == first run through
	// store the randomly generated number so that it can be retrieved.
	echo "<h2> Hidden movie name is : $random_number </h2>";
	$_SESSION['random_number'] = $random_number;
?>

  <tr>

  	<?php
  	foreach ($movie_poster_hints as $number){

  		echo "<td><div style='background-color: lightgrey; width: 100px; height: 150px; border: 5px solid darkgrey; padding: 50px 0 0 0;  margin: 20px; text-align: center; '>$number</div></td>";

  	}
		// SET POSTER ARRAY
		$_SESSION['poster_array'] = $movie_poster_hints; // TODO update to real information
  	?>
  </tr>
   <tr>
  	<?php
  	foreach ($mp_hint_indexes as $number){
  		echo "<td style='text-align: center;'>".$number."</td>";
  	}
		// SET HINT ARRAY
		$_SESSION['hint_array'] = $mp_hint_indexes; // TODO update to real information
  	?>
  </tr>


<?php
} // END ELSE

  // submit to the same page or create a validation interstep

?>
</table>

<form action ="tester.php" style="padding: 30px 0 0 0">
	Guess answer: <input type="text" name="answer" value="">
	<button type="submit" onclick="check_answer()">Click Me!</button>
</form>

</table>
</body>
</html>
