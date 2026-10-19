<?php
require_once __DIR__ . '/security.php';
?>
<?php
    require_once __DIR__ . '/initialize.php';
    $movie_id = "";
    $anagrams = [];
    $stringding = "";
    if(isset($_POST['anagrams'])){
        $anagrams = [];
        $anagrams = $_POST['anagrams'];
    }
    $flag = "";
$native_name = "";
$winner = null;
    

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

    if (isset($_POST['movie_id'])) {

    $movie_id = filter_input(
        INPUT_POST,
        'movie_id',
        FILTER_VALIDATE_INT
    );

    $guess = trim($_POST['input'] ?? '');

    // Normalize guess by removing spaces and making it lowercase
    $normalized_guess = mb_strtolower(
        str_replace(' ', '', $guess),
        'UTF-8'
    );

    if ($movie_id && $normalized_guess !== '') {

        $stmt = db_prepare($db, "SELECT
                movies.*,
                movie_numbers.length,
                movie_numbers.base_chars
             FROM movies
             LEFT JOIN movie_numbers
                ON movies.movie_id = movie_numbers.movie_id
             WHERE movies.movie_id = ?
               AND REPLACE(LOWER(movies.native_name), ' ', '') = ?");

        mysqli_stmt_bind_param(
            $stmt,
            "is",
            $movie_id,
            $normalized_guess
        );

        mysqli_stmt_execute($stmt);

        $winner = mysqli_stmt_get_result($stmt);
    }
}

    if($flag != "winner" && !isset($_POST['movie_id'])){
        $sql = "select movie_id from movies where movie_id in (select movie_id from movie_anagrams) order by rand() limit 1;";
        $result = db_query($db, $sql);
        if($result->num_rows == 1){
            $row = $result->fetch_assoc();
            $movie_id = $row["movie_id"];
        }
        $sql2 = ['sql' => 'select anagram from movie_anagrams where movie_id = ? order by rand() limit 3', 'params' => [$movie_id]];
        $result = db_query($db, $sql2);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                $anagrams[] = $row['anagram'];
            }//end while
        }//end if
    }


?>
<?php
$nav_selected='GAMES'; $left_buttons='YES'; $left_selected='ANAGRAMUSER';
$page_title='Anagrammer · OMDB';
include __DIR__ . '/nav.php';
?>
<link rel="stylesheet" href="vendor/toastr/toastr.min.css">
<link rel="stylesheet" href="vendor/animate/animate.min.css">
<link rel="stylesheet" href="basechars.css">
<section class="anagram-game">
<h1> ANAGRAMMER </h1>
    <div id="clues">
    <?php
if (!($winner instanceof mysqli_result) || $winner->num_rows != 1) {
    echo '<h5 style="color: purple;">
            Welcome! Guess the name of the movie based on the following clues:
          </h5><br>';
}
?>
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
                if ($winner instanceof mysqli_result && $winner->num_rows == 1) {
                    $flag = "winner";
                    // output data of each row
                    $row = $winner->fetch_assoc();
                        echo '<tr>
                                <td>' . h($row["movie_id"]) . '</td>
                                <td>' . h($row["native_name"]) . ' </span> </td>
                                <td>' . h($row["english_name"]) . '</td>
                                <td>' . h($row["year_made"]) . '</td>
                                <td>' . h($row["length"]) . '</td>
                                <td>' . h($row["base_chars"]) . '</td>
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

<script src="vendor/toastr/toastr.min.js"></script>
<script>
	function initBoard() {
        const NUMBER_OF_GUESSES = 1;
        let guessesRemaining = NUMBER_OF_GUESSES;
        let currentGuess = [];
        let nextLetter = 0;
        <?php if(empty($flag)) { ?>
        const characterCount = <?php echo h(mb_strlen($anagrams[0], 'utf8')); ?>;
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
            submit.setAttribute("value", <?php echo h($movie_id);?>)
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

</section>
<?php include __DIR__ . '/footer.php'; ?>
