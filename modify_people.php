<?php
  session_start();
 if (!isset($_SESSION['username']))
      { 
       header("Location: login.php");
       exit();
      }     
?>
<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";
  
     include("./nav.php");
    
    $people_id = $_GET["people_id"];

  ?>

<!DOCTYPE html>
<html>


<h1>Modify a People</h1>
<?PHP
    $sql = "SELECT * FROM people WHERE people_id = '$people_id'";
    if (!$sql_A1_result= $db->query($sql)) {
      die('There was an error running query[' . $connection->error . ']');
    }

    if ($sql_A1_result->num_rows > 0) {
      $a1_tuple = $sql_A1_result->fetch_assoc(); ?>

      <form action="post_modify_people.php"method= "POST">

        <input type="hidden" name="id" value="<?php echo $a1_tuple['people_id']; ?>">
  <div class="form-group">
    <label for="stage_name">Stage Name</label>
    <input type="text" name="stage_name" class="form-control" id="stage_name" value="<?php echo $a1_tuple['stage_name']; ?>">
  </div>
  <div class="form-group">
    <label for="first_name">First Name</label>
    <input type="text" name="first_name" class="form-control" id="first_name" value="<?php echo $a1_tuple['first_name']; ?>">
  </div>

  <div class="form-group">
    <label for="middle_name">Middle Name</label>
    <input type="text" name="middle_name" class="form-control" id="middle_name" value="<?php echo $a1_tuple['middle_name']; ?>">
  </div>

  <div class="form-group">
    <label for="last_name">Last Name</label>
    <input type="text" name="last_name" class="form-control" id="last_name" value="<?php echo $a1_tuple['last_name']; ?>">
  </div>

  <select name="gender" class="form-control" value="<?php echo $a1_tuple['gender']; ?>">
    <option value="male">Male</option>
    <option value="female">Female</option>
  </select>
  <br><br>

  <button type="submit" name="submit" class="btn btn-primary">Submit</button>


</form>

<?php    } //end if
    else {
      echo "0 results";
    } //end else

    $sql_A1_result->close();
    db_disconnect($db);
    ?>




<style type="text/css">
#movieModify {
  background-color: #ffffff;
  margin: 100px auto;
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
    document.getElementById("movieModify").submit();
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
