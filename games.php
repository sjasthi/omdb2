<?php
require_once __DIR__ . '/security.php';
?>
<?php
  $nav_selected = "GAMES";
  $left_buttons = "YES";
  $left_selected = "ANAGRAMADMIN";

  include("./nav.php");

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

 ?>



<form id="basecharAdmin" action="games.php" method= "POST"><?php echo csrf_field(); ?>


<h4>Enter a word to find its base characters and all the movies that contain them.</h3>

<?php
if (isset($_POST['basecharinput']) && !empty($_POST['basecharinput'])){
    $basecharinput = $_POST['basecharinput'];
    $basecharJSON = strtolower(str_replace(" ", "", $basecharinput));

    //Make API call to find base_chars
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

    echo "<p>The base characters of " . h($basecharinput) . " are: ";
    echo "<b style='color:red'>" . h($base_chars) . "</b>";
    echo "</p>";
   }
?>

<div class= "tab">
  <p><input name= "basecharinput" placeholder="Enter a word to play!" class="form-control" ></p>
<div style="overflow:auto;">
  <div  class="text-left">
           <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Go!</button>
           <label id="label">Exact Length?<input name="length" type="checkbox" id="length" value='yes'></label>
  </div>
</div>
<br>
<table id="info" cellpadding="0" cellspacing="0" border="0"
    class="datatable table table-striped table-bordered datatable-style table-hover"
    width="100%" >
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
    if(isset($base_chars)){
        $conditions = [];
        $params = [];
        foreach (mb_count_chars($base_charRaw) as $character => $count) {
            $conditions[] = "(CHAR_LENGTH(base_chars) - CHAR_LENGTH(REPLACE(base_chars, ?, ''))) / CHAR_LENGTH(?) = ?";
            array_push($params, $character, $character, (int)$count);
        }
        if (isset($_POST['length'])) {
            $conditions[] = 'length = ?';
            $params[] = (int)$length;
        }
        $sql = 'SELECT movies.*, movie_numbers.length, movie_numbers.base_chars FROM movies INNER JOIN movie_numbers ON movies.movie_id = movie_numbers.movie_id WHERE '
            . ($conditions ? implode(' AND ', $conditions) : '1 = 0') . ' ORDER BY length ASC';
        $result = db_query($db, $sql, $params);
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>' . h($row["movie_id"]) . '</td>
                        <td>' . h($row["native_name"]) . ' </span> </td>
                        <td>' . h($row["english_name"]) . '</td>
                        <td>' . h($row["year_made"]) . '</td>
                        <td>' . h($row["length"]) . '</td>
                        <td>' . h($row["base_chars"]) . '</td>
                    </tr>';
            }//end while
        }//end if
        else {
            echo "0 results";
        }//end else
         $result->close();
    }
?>

      </tbody>
</table>

</div>

<div style="text-align:center;margin-top:40px;">
<span class="step"></span>
</div>

</form>
<style type="text/css">


/* Style the input fields */


#length{
        width: auto;
        margin: 5px;
        vertical-align: center;
        display: inline-block;

}



/* Mark input boxes that gets an error on validation: */
input.invalid {
  background-color: #ffdddd;
}

/* Hide all steps by default: */
.tab {
  display: none;
}

/* Make circles that indicate the steps of the form: */
.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}


/* Mark the active step: */
.step.active {
  opacity: 1;
}

/* Mark the steps that are finished and valid: */
.step.finish {
  background-color: #4CAF50;
}
</style>
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



<style>
tfoot {
display: table-header-group;
}
</style>




<?php
db_disconnect($db);
include("./footer.php");
?>
