<?php
    include('database.php');
    $db = db_connect();
    $movie_id = "";
    $anagrams = [];
    $stringding = "";
    if(isset($_POST['anagrams'])){
        $anagrams = [];
        $anagrams = $_POST['anagrams'];
    }
    $flag = "";
    $native_name = "";
    

    function mb_count_chars($input) {
        $l = mb_strlen($input, 'utf8');
        $unique = array();
        for($i = 0; $i < $l; $i++) {
            $char = mb_substr($input, $i, 1, 'utf8');
            if(!array_key_exists($char, $unique))
                $unique[$char] = 0;
            $unique[$char]++;
        }
        return $unique;
    }

    if(isset($_POST['movie_id'])){
        $basecharinput = [];
        $movie_id = $_POST['movie_id'];
        
        foreach($_POST as $k => $v) {
            if(strpos($k, 'input') === 0) {
                $basecharinput[] = $v;
            }
        }
        $basecharinput = strtolower(str_replace(" ", "", implode("", $basecharinput)));
        $basecharJSON = $basecharinput;
        
        $jsonLog = "http://indic-wp.thisisjava.com/api/getBaseCharacters.php?string=".$basecharJSON."&language=Telugu";
        $jsonfile = file_get_contents($jsonLog);
        $decodedData = json_decode(strstr($jsonfile, '{'));
        $base_charRaw = implode("", $decodedData->data);
        $base_chars = implode(", ", $decodedData->data);

        //Make API call to find length of string for length
        $jsonLength = "http://indic-wp.thisisjava.com/api/getLength.php?string=".$basecharJSON."&language=Telugu";
        $jsonfile= file_get_contents($jsonLength);
        $decoder = json_decode(strstr($jsonfile, '{'));
        $length = intval($decoder->data);

        if(isset($base_chars)){
            $query_conditions = "";
            $characters = mb_count_chars($base_charRaw);
            $count = count($characters);
            foreach ($characters as $key => $value){
                if($count > 1){
                   $query_conditions .= "(char_length(base_chars) - char_length(replace(base_chars, '".$key."', ''))/char_length('".$key."')) = ".$value." and ";
                    $count = $count - 1;
                } else {
                    $query_conditions .= "(char_length(base_chars) - char_length(replace(base_chars, '".$key."', ''))/char_length('".$key."')) = ".$value."";
                }
            }

            $sql3 = "SELECT movies.*, movie_numbers.length as length, movie_numbers.base_chars as base_chars from movies inner join movie_numbers on movies.movie_id = movie_numbers.movie_id where ".$query_conditions." ORDER BY length asc;";
            $basechar_check = mysqli_query($db, $sql3);
            if($basechar_check->num_rows >= 1){
                $sql4 = "select movies.*, movie_numbers.length, movie_numbers.base_chars from movies inner join movie_numbers on movies.movie_id = movie_numbers.movie_id where movies.movie_id = ".$movie_id." and replace(lower(movies.native_name), ' ', '') = '".$basecharinput."';";
                $winner = mysqli_query($db, $sql4);
            } else {
                $winner = '';
                $here = "we're here";
            }
        }
    }

    if($flag != "winner" && !isset($_POST['movie_id'])){
        $sql = "select movie_id from movies where movie_id in (select movie_id from movie_anagrams) order by rand() limit 1;";
        $result = mysqli_query($db, $sql);
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $movie_id = $row["movie_id"];
        }
        $sql2 = "select anagram from movie_anagrams where movie_id = '".$movie_id."' order by rand() limit 3";
        $result = mysqli_query($db, $sql2);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $anagrams[] = $row['anagram'];
            }//end while
        }//end if
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anagrammer</title>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>A Basic Composer</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="http://fonts.cdnfonts.com/css/games" rel="stylesheet">
                
    <link href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.12/css/dataTables.bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="styles/mainStyleSheet.css">
    <link rel="stylesheet" href="fonts/css/all.css">
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
	<link
	rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
	  />
	<link rel="stylesheet" href="basechars.css"/>
</head>
<body>
    <h1> ANAGRAMMER </h1>
    <div id="clues">
    <?php if($winner->num_rows != 1){echo '<h5 style="color: purple;"> Welcome! Guess the name of the movie based on the following clues:</h5><br>';} ?>
    </div>
    <div id="display-board">
    </div>
    <br>
    <div id="game-board">
    </div>
    <br>
    <?php if(isset($winner)){ ?>
    <div class= "tab">
        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>id</th>
                        <th>Native Name</th>
                        <th>English Name</th>
                        <th>Year</th>
                        <th>Length</th>
                        <th>Base Characters</th>

                </tr>
              </thead>
              <tbody>

        <?php
                if ($winner->num_rows == 1) {
                    $flag = "winner";
                    // output data of each row
                    $row = $winner->fetch_assoc();
                        echo '<tr>
                                <td>'.$row["movie_id"].'</td>
                                <td>'.$row["native_name"].' </span> </td>
                                <td>'.$row["english_name"].'</td>
                                <td>'.$row["year_made"].'</td>
                                <td>'.$row["length"].'</td>
                                <td>'.$row["base_chars"].'</td>
                            </tr>';
                }//end if
                else {
                    echo "<div style='color:red;'>Whoops! Please Try Again.</div>";
                }//end else
        ?>
              </tbody>
        </table>
    </div>
    <?php } ?>
<script
src="https://code.jquery.com/jquery-3.6.0.min.js"
integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4="
crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
	function initBoard() {
        const NUMBER_OF_GUESSES = 1;
        let guessesRemaining = NUMBER_OF_GUESSES;
        let currentGuess = [];
        let nextLetter = 0;
        <?php if(empty($flag)) { ?>
        const characterCount = <?php echo mb_strlen($anagrams[0], 'utf8'); ?>;
        var passedArray = <?php echo json_encode($anagrams);?>;
        let display = document.getElementById("display-board")
            
        for (let i = 0; i < NUMBER_OF_GUESSES; i++) {
            let display_row = document.createElement("div")
            display_row.className = "display-row"
            var characters = passedArray[i]
            for (let j = 0; j < characterCount; j++) {
                let display_box = document.createElement("div")
                display_box.className = "display-box"
                var character = characters.charAt(j);
                display_box.innerHTML = character
                display_row.appendChild(display_box)
            }
            display.appendChild(display_row)
        }
        let board = document.getElementById("game-board")
        for (let i = 0; i < NUMBER_OF_GUESSES; i++) {
            let row = document.createElement("form")
            row.className = "letter-row"
            row.setAttribute("action","anagrammer.php")
            row.setAttribute("name","guess"+i)
            row.setAttribute("method", "post")
            row.setAttribute("id", "anagrammer")
            let box = document.createElement("input")
            box.className = "input"
            box.setAttribute("name", "input")
            box.setAttribute("minlength", "1")
            box.setAttribute("maxlength", "100")
            box.setAttribute("placeholder", "Guess The Movie")
            box.setAttribute("required", "")
            row.appendChild(box)
            let submit = document.createElement("button")
            submit.innerHTML = "Good Luck!"
            submit.className = "submit-button"
            submit.setAttribute("type","submit")
            submit.setAttribute("form", "anagrammer")
            submit.setAttribute("name", "movie_id")
            submit.setAttribute("value", <?php echo $movie_id;?>)
            let input = document.createElement("input")
            input.setAttribute("type", "hidden")
            input.setAttribute("name", "anagrams[]")
            input.setAttribute("value", <?php echo json_encode($anagrams); ?>)
            input.setAttribute("form", "anagrammer")
            board.appendChild(row)
            board.appendChild(submit)
            board.appendChild(input)
        }
        <?php } else { ?>
            let board = document.getElementById("game-board")
            let row = document.createElement("form")
            row.className = "letter-row"
            row.setAttribute("action","createTheAnagram.php")
            row.setAttribute("name","createAnagram")
            row.setAttribute("method", "post")
            row.setAttribute("id", "anagrammer")
            row.innerHTML = "You win! Would you like to contribute an anagram?"
            let submit = document.createElement("button")
            submit.innerHTML = "Yes!"
            submit.className = "submit-button"
            submit.setAttribute("type","submit")
            submit.setAttribute("form", "anagrammer")
            board.appendChild(row)
            board.appendChild(submit)
        <?php } ?>
	}
    initBoard()

    var container = document.getElementsByClassName("letter-row")[0];
    container.onkeyup = function(e) {
        var target = e.srcElement || e.target;
        var maxLength = parseInt(target.attributes["maxlength"].value);
        var myLength = target.value.length;
        if (myLength >= maxLength) {
            var next = target;
            while (next = next.nextElementSibling) {
                if (next == null)
                    break;
                if (next.tagName.toLowerCase() === "input") {
                    next.focus();
                    break;
                }
            }
        }
        // Move to previous field if empty (user pressed backspace)
        else if (myLength === 0) {
            var previous = target;
            while (previous = previous.previousElementSibling) {
                if (previous == null)
                    break;
                if (previous.tagName.toLowerCase() === "input") {
                    previous.focus();
                    break;
                }
            }
        }
    }
</script>
<script type="text/javascript" language="javascript">
$(document).ready( function () {

$('#info').DataTable( {
    "order": [[ 4, "asc" ]],
    "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
    dom: 'lfrtBip',
    buttons: [
        'copy', 'excel', 'csv', 'pdf'
    ] }
);

var table = $('#info').DataTable( {
    orderCellsTop: true,
    fixedHeader: true,
    retrieve: true
} );

} );

</script>
<?php
    db_disconnect($db);
?>

</body>
</html>
