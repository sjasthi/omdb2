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
    $nav_selected = "PEOPLE";
    $left_buttons = "YES";
    $left_selected = "PEOPLE";

     include("./nav.php");

  ?>

<!DOCTYPE html>
<html>
<div class = "container">
<form method= "post">
<h1>Create new People</h1>
<div class="tab">
  <p><input name= "stage_name" placeholder="Stage Name" ></p>
  <p><input name= "first_name" placeholder="First Name"></p>
  <p><input name= "middle_name" placeholder="Middle Name" ></p>
  <p><input name= "last_name" placeholder="Last Name"></p>
  <p><select name="gender" class="form-control">
    <option value="">Select Gender</option>
    <option value="male">Male</option>
    <option value="female">Female</option>
  </select>
  </p>
</div>
<div style="overflow:auto;">
 <div class="text-left">
  <button type="submit" name="create_people" class="btn btn-primary btn-md align-items-center">Create People</button>
</div>
</div>

<div style="text-align:center;margin-top:40px;">
  <span class="step"></span>
</div>

</form>
</div>
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
    document.getElementById("nextBtn").innerHTML = "next";
  }
  // ... and run a function that displays the correct step indicator:
  fixStepIndicator(n)
}

function nextPrev(n) {
  // This function will figure out which tab to display
  var x = document.getElementsByClassName("tab");
  // Exit the function if any field in the current tab is invalid:
  if (n == 1 && !validateForm()) return false;
  // Hide the current tab:
  x[currentTab].style.display = "none";
  // Increase or decrease the current tab by 1:
  currentTab = currentTab + n;
  // if you have reached the end of the form... :
  if (currentTab >= x.length) {
    //...the form gets submitted:
    document.getElementById("movieCreate").submit();
    return false;
  }
  // Otherwise, display the correct tab:
  showTab(currentTab);
}

function validateForm() {
  // This function deals with validation of the form fields
  var x, y, i, valid = true;
  x = document.getElementsByClassName("tab");
  y = x[currentTab].getElementsByTagName("input");
  // A loop that checks every input field in the current tab:
  for (i = 0; i < y.length; i++) {
    // If a field is empty...
    if (y[i].value == "") {
      // add an "invalid" class to the field:
      y[i].className += " invalid";
      // and set the current valid status to false:
      valid = false;
    }
  }
  // If the valid status is true, mark the step as finished and valid:
  if (valid) {
    document.getElementsByClassName("step")[currentTab].className += " finish";
  }
  return valid; // return the valid status
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
      if(isset($_POST['create_people'])){
        $people_query = mysqli_query($db,"select people_id from people order by people_id desc limit 1");
        $people_row = mysqli_fetch_row($people_query);
        $p_id = $people_row[0] + 1;
        $stage_name = $_POST['stage_name'];
        $first_name = $_POST['first_name'];
        $middle_name = $_POST['middle_name'];
        $last_name  = $_POST['last_name'];
        $gender = $_POST['gender'];
        $image = "image file name";

        $query = mysqli_query($db,"INSERT INTO people (people_id, stage_name, first_name, middle_name, last_name, gender, image_name)
         VALUES ('$p_id', '$stage_name','$first_name','$middle_name','$last_name','$gender','$image')");
        db_disconnect($db);
        header('Location: people.php?create=Success');
      }

?>
