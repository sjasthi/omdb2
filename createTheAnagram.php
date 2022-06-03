<?php
    include('database.php');
    $db = db_connect();
    $movie_id = "";
    $anagrams = [];
    $flag = "";
    $native_name = "";
    $native_nameArr = [];

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

    if(isset($_POST['addAnagram'])){
        $flag = 'guess';
        $movie_id = $_POST['addAnagram'];
        $basecharinput = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'input') === 0) {
                $basecharinput[] = $v;
            }
        }
        $basecharinput = implode("", $basecharinput);
        $basecharJSON = strtolower(str_replace(" ", "", $basecharinput));
        $native_check = $_POST['native_check'];
        
        $sql5 = "select movies.native_name as native_name, movie_numbers.length as length, movie_numbers.base_chars as base_char_check from movies inner join movie_numbers on movies.movie_id = movie_numbers.movie_id where movies.movie_id = ".$movie_id.";";
        $result = mysqli_query($db, $sql5);
        $row = $result->fetch_assoc();
        $native_name = $row['native_name'];
        $spaces = substr_count($native_name, " ");
        $length = $row['length'];
        $base_char_check = $row['base_char_check'];

        //Make API call to find base_chars
        $jsonLog = "http://indic-wp.thisisjava.com/api/getBaseCharacters.php?string=".$basecharJSON."&language=Telugu";
        $jsonfile = file_get_contents($jsonLog);
        $decodedData = json_decode(strstr($jsonfile, '{'));
        $base_charRaw = implode("", $decodedData->data);
        $base_chars = implode(", ", $decodedData->data);

        if(isset($base_chars)){
            $query_conditions = "";
            $characters = mb_count_chars($base_charRaw);
            $count = count($characters);
            $checkCount = count($characters);
            $totalChars = 0;
            foreach ($characters as $key => $value){
                if($count > 1){
                   $query_conditions .= "(char_length(base_chars) - char_length(replace(base_chars, '".$key."', ''))/char_length('".$key."')) = ".$value." and ";
                    $count = $count - 1;
                    $totalChars = $totalChars + $value;
                } else {
                    $query_conditions .= "(char_length(base_chars) - char_length(replace(base_chars, '".$key."', ''))/char_length('".$key."')) = ".$value."";
                    $totalChars = $totalChars + $value;
                }
            }
            $query_conditions2 = " and length = $totalChars + $spaces";

            $sql3 = "SELECT movies.*, movie_numbers.length as length, movie_numbers.base_chars as base_chars from movies inner join movie_numbers on movies.movie_id = movie_numbers.movie_id where ".$query_conditions." and movies.movie_id = ".$movie_id." ".$query_conditions2." ORDER BY length asc;";

            $winner = mysqli_query($db, $sql3);
        }
    }

    if(empty($flag)){
        $sql3 = "select movies.movie_id as movie_id, movies.native_name as native_name from movies left join movie_anagrams on movies.movie_id = movie_anagrams.movie_id group by movie_id having count(movie_anagrams.movie_id) < 3 order by rand() limit 1;";
        $result = mysqli_query($db, $sql3);
        if ($result->num_rows > 0){
            $row = $result->fetch_assoc();
            $movie_id = $row['movie_id'];
            $native_name = strtolower(str_replace(" ", "", $row['native_name']));
        }
        $jsonLog = "http://indic-wp.thisisjava.com/api/getLogicalChars.php?string=".$native_name."&language=Telugu";
        $jsonfile = file_get_contents($jsonLog);
        $decodedData = json_decode(strstr($jsonfile, '{'));
        $native_nameArr = $decodedData->data;
        $native_check = implode("", $native_nameArr);
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
    <link href="http://fonts.cdnfonts.com/css/games" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
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
    <h1> ADD AN ANAGRAM </h1>
    <div id="display-board">
    </div>
    <div id="game-board">
    </div>
    <br><br>
    <?php if($winner){ ?>
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
                if (($winner->num_rows > 0) && $basecharJSON != $native_check) {
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
                    $sqlFinal = "insert into movie_anagrams (movie_id, anagram) values (".$movie_id.", '".$basecharJSON."');";
                    mysqli_query($db, $sqlFinal);
                }//end if
                else {
                    $yourURL = "./createTheAnagram.php";
                    $alert = '<script language="javascript">alert("Whoops! Please Try Again.")</script>';
                    echo $alert;
                    echo ("<script>location.href='$yourURL'</script>");
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
        const characterCount = <?php echo count($native_nameArr); ?>;
        var passedArray = <?php echo json_encode($native_nameArr);?>;
        let display = document.getElementById("display-board")
        for (let i = 0; i < NUMBER_OF_GUESSES; i++) {
            let display_row = document.createElement("div")
            let display_row2 = document.createElement("div")
            display_row2.className = "display-row"
            let display_box2 = document.createElement("div")
            display_box2.className = "display-box"
            display_box2.innerHTML = "<?php if(!isset($_POST['movie_id']) ){ echo $row['native_name'];}?>"
            display_box2.style.width = "100%"
            display_box2.style.padding = "10px"
            display_row2.appendChild(display_box2)
            display_row.className = "display-row"
            for (let j = 0; j < characterCount; j++) {
                let display_box = document.createElement("div")
                display_box.className = "display-box"
                var character = passedArray[j];
                display_box.innerHTML = character
                display_row.appendChild(display_box)
            }
            display.appendChild(display_row2)
            display.appendChild(display_row)
        }
        let board = document.getElementById("game-board")
        for (let i = 0; i < NUMBER_OF_GUESSES; i++) {
            let row = document.createElement("form")
            row.className = "letter-row"
            row.setAttribute("action","createTheAnagram.php")
            row.setAttribute("name","guess"+i)
            row.setAttribute("method", "post")
            row.setAttribute("id", "anagrammer")
            let box = document.createElement("input")
            box.setAttribute("name", "native_check")
            box.setAttribute("type", "hidden")
            box.setAttribute("value", '<?php echo $native_check;?>')
            row.appendChild(box)
            
            let box2 = document.createElement("input")
            box2.className = "input"
            box2.style.width = "400px"
            box2.setAttribute("name", "input")
            box2.setAttribute("minlength", "1")
            box2.setAttribute("placeholder", "CREATE THE ANAGRAM")
            box2.setAttribute("required", "")
            row.appendChild(box2)
            
            let submit = document.createElement("button")
            submit.innerHTML = "Good Luck!"
            submit.className = "submit-button"
            submit.setAttribute("type","submit")
            submit.setAttribute("form", "anagrammer")
            submit.setAttribute("name", "addAnagram")
            submit.setAttribute("value", <?php echo $movie_id;?>)
    
            board.appendChild(row)
            board.appendChild(submit)
        }
        <?php } if($flag == 'winner') { ?>
            let board = document.getElementById("game-board")
            let row = document.createElement("form")
            row.className = "letter-row"
            row.setAttribute("action","anagrammer.php")
            row.setAttribute("name","createAnagram")
            row.setAttribute("method", "post")
            row.setAttribute("id", "anagrammer")
            row.innerHTML = "Thanks for the contribution! Would you like to go back to the Anagrammer?"
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
