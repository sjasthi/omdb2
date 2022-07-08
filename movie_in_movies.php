<!-- Logic:
1. Select a random movie.
	- Create an array of strings that are movie titles.
	- Randomly pick one string out of an array of movie titles.
2. Break it up into letters.
	- Turn the string into its own array of characters.
3. For each letter, select another movie whose name contains the letter.
	- Loop through the array of characters.
	- For each character, search another movie  for the same character in its name.
4. Grab the corresponding poster. If the poser is not there, default to "not found" image.
	- Take that movie title,
	- Find the index of that letter in the movie title.
	- Find the length of the movie title.
5. Construct the number box for that letter based on the movie name.
	- Create html text display,
6. Display the equation
	- Have in it (index of the letter in the movie title +1)/(length of the movie title).
7. Validate the users guess.
	- Create user input text box.
	- User's input is matched against the hidden movie title of the random choice.
	- If user inputs the correct movie title, message displays they win.
	- If user inputs the incorrect movie title, message displays they must try again.

	TODO - remove empty space in string length for mp hint.

-->

<?php

$nav_selected = "MOVIES";
$left_buttons = "YES";
$left_selected = "MOVIES";


include("./nav.php");
// Index of letters used for hints.
$mp_hint_indexes = array();

// Array of movies for user to solve from.
$movie_poster_hints = array();
// An array of movie title strings.

//TODO cleanup, This is no longer being used.
$movie_titles = array(
	"Fight Club",
	"Neverending Story",
	"PeeWees Big Adventure",
	"Kung Fu Panda",
	"StarWars",
	"Indiana Jones",
	"Karate Kid",
	"Last Star Fighter",
	"Friday the 13th",
	"Three Amigos",
	"Beetlejuice",
	"Labyrinth",
	"The Goonies",
	"Ledgend",
	"Batman",
	"Tron",
	"Top Gun",
	"Footloose",
	"Howard the Duck",
	"Superman",
	"Back to the Future",
	"Conan the Barbarian",
	"Scrooged",
	"Splash",
	"Time Bandits",
	"Dune",
	"ET",
	"Spaceballs",
	"The Thing",
	"the Evil Dead",
	"Flash Gordon",
	"Twins",
	"Bill and Teds Excellent Adventure",
	"The Lost Boys",
	"the Burbs",
	"Short Circuit",
	"Blood Sport",
	"They Live",
	"Weird Science",
	"Willow"
	);

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

// Picking a random movie title from the array.
$movie_choice = strtolower($data[$random_number]['english_name']);

$language = "English"; // Hardcoded for now TODO

echo $movie_choice."<br>";

// From URL to get webpage contents.
// $url = "wpapi.telugupuzzles.com/api/getLength.php?string=$movie_choice&language=English";

// // Initialize a CURL session.
// $ch = curl_init();

// // Return Page contents.
// curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// //grab URL and pass it to the variable.
// curl_setopt($ch, CURLOPT_URL, $url);

// $result = curl_exec($ch);

// echo "The results are: ".$result;


// URL encode urlencode(), allows for SPACES to be inside Strings passed
$jsonLog = "https://wpapi.telugupuzzles.com/api/parseToLogicalCharacters.php?string=".urlencode($movie_choice)."&language=".urlencode($language);
$jsonfile = file_get_contents($jsonLog);
$decodedData = json_decode(strstr($jsonfile, '{'));
// $base_chars = implode(", ", $decodedData->data);

$stdClass_to_array = (array)$decodedData;
print_r($stdClass_to_array) ;
echo "<br>Did this work?: ".$stdClass_to_array['string']."<br>";


//$movie_json = json_decode($result, false); //  LINE 120 - 126 REDACTED.
//echo "This is: ".$movie_json;

$movie_choice = str_split($stdClass_to_array['string']);

// Randomize movie list to get random hints.
//
// Loop through the movie choice array, letter by letter.
foreach($movie_choice as $letters){
	// If the letter is a empty space between words, don't look for match.
	if($letters == " "){
		echo "<br> empty space <br>";
		$movie_poster_hints[] = " ";
	}else{
		// While the count of the loop is less than the length of movie titles...
	echo "<br>---$letters ---<br>";
	$count = 0;
	while($count < count($movie_titles)){
		// ... check if a movie title has the desired letter for the hidden movie choice. If it does...
		if(str_contains(strtolower($movie_titles[$count]), $letters)){
			// ... loop through each in the array of movies already picked as clues.
			echo "match <br>";
			echo "$movie_titles[$count] <br>";
			$is_present = false;
			foreach($movie_poster_hints as $poster_in_use){
				// If it is in the hints already, mark as "is present".
				if(strtolower($poster_in_use) == strtolower($movie_titles[$count]) || strtolower($poster_in_use) == $movie_choice){
					$is_present = true;
					echo "poster already in hints. <br> <br>";
					Break;
				}
			}
			// If the movie is not already in the hints array, add it and break loop.
			if(!$is_present){
				$movie_poster_hints[] = $movie_titles[$count];
				$mp_hint_indexes[] = strpos($movie_titles[$count], $letters)."/".strlen($movie_titles[$count]);
				echo "Not yet in hints, adding. <br>";
				Break;
			}
		// If it does not contain the letter required, keep looping.
		} else{
			echo "searching... <br>";
		}
		// Iterates for how many movies have been found.
		$count += 1;
	}
	}

}
// Prints out the collection of hint posters
echo "<br>";
print_r($movie_poster_hints);
echo "<br> <br>";
print_r($mp_hint_indexes);


?>
