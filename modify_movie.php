<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "MOVIES";
    $left_buttons = "YES";
    $left_selected = "NO";
  
    include("./nav.php");
    
    $movie_id = $_GET["movie_id"];

  ?>

<!DOCTYPE html>
<html>
<form id="movieModify" action="modifyTheMovies.php" method= "POST">

<h1>Modify a movie</h1>
<?PHP
  $native_name = "";
  $english_name = "";
  $year_made = "";
  $id = "";
    $sql = mysqli_query($db,"SELECT * FROM movies WHERE movie_id = '$movie_id'");
      if(mysqli_num_rows($sql)>0){
      while($row = mysqli_fetch_assoc($sql)){
        $native_name = $row['native_name'];
        $english_name = $row['english_name'];
        $year_made = $row['year_made'];
        $id = $row['movie_id'];
      }
    }
    
    ?>
<div class= "tab">
  <p><input name= "native_name_update" value="<?php echo $native_name; ?>" placeholder="Modify Native Name" class="form-control" ></p>
  <p><input name= "english_name_update" value="<?php echo $english_name; ?>" placeholder="Modify English Name" class="form-control" oninput="this.className = ''"></p>
  <p><input name= "year_update" value="<?php echo $year_made; ?>" class="form-control" placeholder="Modify Year" oninput="this.className = ''"></p>
  <input type="hidden" name="movie_id" value="<?php echo $id; ?>">
</div>
<div style="overflow:auto;">
  <div  class="text-left">
           <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify Movie</button>
  </div>
</div>
<div style="text-align:center;margin-top:40px;">
<span class="step"></span>
</div>

</form>
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
<?php
db_disconnect($db);
include("./footer.php");
?>
</html>
