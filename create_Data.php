<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";
     include("./nav.php");
    $movie_id = $_GET["movie_id"];
  ?>
  
<!DOCTYPE html>
<html>
<form id="movieData" action="createTheData.php" method= "post">

<?php
$native_name = "";
$english_name = "";
$year_made = "";
$id = "";
$running_time = "";
$budget = "";
$box_office = "";
$language = "";
$country = "";
$genre = "";
$plot = "";
$tag_line = "";
$keyword = [];
$anagram = [];
$anagram_id = [];
$movie_media_id = [];
$m_link = [];
$m_link_type = [];
$movie_quote_id = [];
$movie_quote_name = [];
$movie_trivia_id = [];
$movie_trivia_name = [];

  $sql = mysqli_query($db,"SELECT * FROM movies WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql)>0){
    while($row = mysqli_fetch_assoc($sql)){
      $native_name = $row['native_name'];
      $english_name = $row['english_name'];
      $year_made = $row['year_made'];
      $id = $row['movie_id'];
    }
  }
  $sql2 = mysqli_query($db,"SELECT * FROM movie_numbers WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql2)>0){
    while($row = mysqli_fetch_assoc($sql2)){
      $running_time = $row['running_time'];
      $budget = $row['budget'];
      $box_office = $row['box_office'];
    }
  }
  $sql3 = mysqli_query($db,"SELECT * FROM movie_data WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql3)>0){
    while($row = mysqli_fetch_assoc($sql3)){
      $language = $row['language'];
      $country = $row['country'];
      $genre = $row['genre'];
      $plot = $row['plot'];
      $tag_line = $row['tag_line'];
    }
  }
  $sql4 = mysqli_query($db,"SELECT * FROM movie_keywords WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql4)>0){
    while($row = mysqli_fetch_assoc($sql4)){
      $keyword[] = $row['keyword'];
    }
  }
  $sql5 = mysqli_query($db,"SELECT * FROM movie_anagrams WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql5)>0){
    while($row = mysqli_fetch_assoc($sql5)){
      $anagram[] = $row['anagram'];
      $anagram_id[] = $row['anagram_id'];
    }
  }
  $sql6 = mysqli_query($db,"SELECT * FROM movie_media WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql6)>0){
    while($row = mysqli_fetch_assoc($sql6)){
      $movie_media_id[] = $row['movie_media_id'];
      $m_link[] = $row['m_link'];
      $m_link_type[] = $row['m_link_type'];
    }
  }
  $sql7 = mysqli_query($db,"SELECT * FROM movie_quotes WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql7)>0){
    while($row = mysqli_fetch_assoc($sql7)){
      $movie_quote_id[] = $row['movie_quote_id'];
      $movie_quote_name[] = $row['movie_quote_name'];
    }
  }
  $sql8 = mysqli_query($db,"SELECT * FROM movie_trivia WHERE movie_id = '$movie_id'");
  if(mysqli_num_rows($sql8)>0){
    while($row = mysqli_fetch_assoc($sql8)){
      $movie_trivia_id[] = $row['movie_trivia_id'];
      $movie_trivia_name[] = $row['movie_trivia_name'];
    }
  }
  ?>
  <h1>Create Movie Data For <?php echo $native_name; ?></h1>
  <?php
  if(empty($language) && empty($country) && empty($genre) && empty($plot) && empty($tag_line)){ ?>
      <label for="language"> Language </label>
      <p><input name= "language" id="language" value="<?php echo $language; ?>" placeholder="Add Language" class="form-control" oninput="this.className = ''"></p>
      <label for="country"> Country </label>
      <p><input name= "country" id="country" value="<?php echo $country; ?>" class="form-control" placeholder="Add Country" oninput="this.className = ''"></p>
      <label for="genre"> Genre </label>
      <p><input name= "genre" id="genre" value="<?php echo $genre; ?>" placeholder="Add Genre" class="form-control" oninput="this.className =''"></p>
      <label for="plot"> Plot </label>
      <p><input name= "plot" id="plot" value="<?php echo $plot; ?>" placeholder="Add Plot" class="form-control" oninput="this.className = ''"></p>
      <label for="tag_line"> Tag Line </label>
      <p><input name= "tag_line" id="tag_line" value="<?php echo $tag_line; ?>" class="form-control" placeholder="Add Tag Line" oninput="this.className = ''"></p><br>
  <?php } ?>
 
<?php
  if(sizeof($anagram_id) >= 0){
      echo '<label for="anagram"> Add Anagram </label>';
      echo '<p><input id="anagram" name= "anagram" class="form-control" placeholder="Add Anagram" oninput="this.className = """></p><br>';
  }

  if(sizeof($keyword) >= 0){
      echo '<label for="keyword"> Add Keyword </label>';
      echo '<p><input id="keyword" name= "keyword" class="form-control" placeholder="Add Keyword" oninput="this.className = """></p><br>';
  }


  if(sizeof($movie_media_id) >= 0){
          echo '<label for="m_link"> Add Media Link </label>';
          echo '<p><input id="m_link" name= "m_link" class="form-control" placeholder="Add Media Link" oninput="this.className = """></p>';
          echo '<label for="m_link_type"> Add Media Link Type </label>';
          echo '<p><input id="m_link_type" name= "m_link_type" class="form-control" placeholder="Add Media Link Type" oninput="this.className = """></p><br>';
  }

  if(sizeof($movie_quote_id) >= 0){
      echo '<label for="quote"> Add Quote </label>';
      echo '<p><input id="quote" name= "movie_quote_name"  class="form-control" placeholder="Add Quote" oninput="this.className = """></p><br>';
  }

  if(sizeof($movie_trivia_id) >= 0){
      echo '<label for="trivia"> Add Trivia </label>';
      echo '<p><input id="trivia" name= "movie_trivia_name" class="form-control" placeholder="Add Trivia" oninput="this.className = """></p><br>';
  }
?>
  
  <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">

  <div style="overflow:auto;">
      <div  class="text-left">
          <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Create Data</button>
      </div>
  </div>
  
  <div style="text-align:center;margin-top:40px;">
      <span class="step"></span>
  </div>
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
    document.getElementById("movieData").submit();
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
