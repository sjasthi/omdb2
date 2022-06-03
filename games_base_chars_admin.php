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

<!DOCTYPE html>
<html>
<form id="basecharAdmin" action="games_base_chars_admin.php" method= "POST">

<h2>Welcome to the Base Characters Game!</h1>
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
    
    echo "<p>The base characters of ".$basecharinput." are: ";
    echo "<b style='color:red'>".$base_chars."</b>";
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
    if(isset($base_chars)){
        $query_conditions = "";
        $query_conditions2 = "";
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
        if(isset($_POST['length'])){
            $query_conditions2 = " and length = $length";
        }
        
    $sql = "SELECT movies.*, movie_numbers.length as length, movie_numbers.base_chars as base_chars from movies inner join movie_numbers on movies.movie_id = movie_numbers.movie_id where ".$query_conditions." ".$query_conditions2." ORDER BY length asc;";

    $result = $db->query($sql);
        
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                echo '<tr>
                        <td>'.$row["movie_id"].'</td>
                        <td>'.$row["native_name"].' </span> </td>
                        <td>'.$row["english_name"].'</td>
                        <td>'.$row["year_made"].'</td>
                        <td>'.$row["length"].'</td>
                        <td>'.$row["base_chars"].'</td>
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
#basecharAdmin {
  background-color: #ffffff;
  margin: auto;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}

/* Style the input fields */
input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

#length{
        width: auto;
        margin: 5px;
        vertical-align: center;
        display: inline-block;

}

#label{
    text-indent: 530px;
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
<script>
var currentTab = 0; // Current tab is set to be the first tab (0)
showTab(currentTab); // Display the current tab

function showTab(n) {
  // This function will display the specified tab of the form ...
  var x = document.getElementsByClassName("tab");
  x[n].style.display = "block";
  // ... and fix the Previous/Next buttons:
  if (n == 0) {
    document.getElementById("prevBtn").style.display = "none";
  } else {
    document.getElementById("prevBtn").style.display = "inline";
  }
  if (n == (x.length - 1)) {
    document.getElementById("nextBtn").innerHTML = "Submit";
  } else {
    document.getElementById("nextBtn").innerHTML = "Next";
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n)
}
function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    //...the form gets submitted:
    document.getElementById("basecharAdmin").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}
function fixStepIndicator(n) {
  // This function removes the "active" class of all steps...
  var i, x = document.getElementsByClassName("step");
  for (i = 0; i < x.length; i++) {
    x[i].className = x[i].className.replace(" active", "");
  }
  //... and adds the "active" class to the current step:
  x[n].className += " active";
}
</script>
</html>


<?php
db_disconnect($db);
include("./footer.php");
?>

