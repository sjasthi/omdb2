<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";

     include("./nav.php");
    
    $song_id = $_GET["song_id"];
    $movie_id = $_GET["movie_id"];
    $people_id = $_GET["people_id"];

    $movieModify = "";
    $addSong = "";
    $addPerson = "";

    if(isset($_GET["movie_id"])){
        $movieModify = "autofocus";
    }
    elseif(isset($_GET["song_id"])){
        $addSong = "autofocus";
    }
    elseif(isset($_GET["people_id"])){
        $addPerson = "autofocus";
    }
  ?>

<!DOCTYPE html>
<html>
<body >
<?php
if(isset($_GET['movie_id'])){
    echo '<form id="movieModify" class="movieModify" action="modifyTheMovies.php" method= "POST">';

    echo '<h1>Modify a movie</h1>';

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
  
    <label for="native_name"> Native Name </label>
    <p><input name= "native_name_update" id="native_name" value="<?php echo $native_name; ?>" placeholder="Modify Native Name" class="form-control" <?php echo $movieModify; ?> oninput="this.className =''"></p>
    <label for="english_name"> English Name </label>
    <p><input name= "english_name_update" id="english_name" value="<?php echo $english_name; ?>" placeholder="Modify English Name" class="form-control" oninput="this.className = ''"></p>
    <label for="year"> Year </label>
    <p><input name= "year_update" id="year" value="<?php echo $year_made; ?>" class="form-control" placeholder="Modify Year" oninput="this.className = ''"></p>
    <label for="running_time"> Running Time </label>
    <p><input name= "running_time_update" id="running_time" value="<?php echo $running_time; ?>" placeholder="Modify Running Time" class="form-control" oninput="this.className =''"></p>
    <label for="budget"> Budget </label>
    <p><input name= "budget_update" id="budget" value="<?php echo $budget; ?>" placeholder="Modify Budget" class="form-control" oninput="this.className = ''"></p>
    <label for="box_office"> Box Office </label>
    <p><input name= "box_office_update" id="box_office" value="<?php echo $box_office; ?>" class="form-control" placeholder="Modify Box Office" oninput="this.className = ''"></p>
    <label for="language"> Language </label>
    <p><input name= "language_update" id="language" value="<?php echo $language; ?>" placeholder="Modify Language" class="form-control" oninput="this.className = ''"></p>
    <label for="country"> Country </label>
    <p><input name= "country_update" id="country" value="<?php echo $country; ?>" class="form-control" placeholder="Modify Country" oninput="this.className = ''"></p>
    <label for="genre"> Genre </label>
    <p><input name= "genre_update" id="genre" value="<?php echo $genre; ?>" placeholder="Modify Genre" class="form-control" oninput="this.className =''"></p>
    <label for="plot"> Plot </label>
    <p><input name= "plot_update" id="plot" value="<?php echo $plot; ?>" placeholder="Modify Plot" class="form-control" oninput="this.className = ''"></p>
    <label for="tag_line"> Tag Line </label>
    <p><input name= "tag_line_update" id="tag_line" value="<?php echo $tag_line; ?>" class="form-control" placeholder="Modify Tag Line" oninput="this.className = ''"></p>
   
<?php
    if(sizeof($anagram_id) > 0){
        for($i = 0; $i < sizeof($anagram_id); $i++){
            echo '<label for="anagram'.$i.'"> Anagram #'.($i+1).' </label>';
            echo '<p><input id="anagram'.$i.'" name= "anagram_update'.$i.'" value="'.$anagram[$i].'" class="form-control" placeholder="Modify Anagram for '.$anagram_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="anagram_id'.$i.'" value="'.$anagram_id[$i].'"><br>';
        }
    }
    if(sizeof($keyword) > 0){
        for($i = 0; $i < sizeof($keyword); $i++){
            echo '<label for="keyword'.$i.'"> Keyword #'.($i+1).' </label>';
            echo '<p><input id="keyword'.$i.'" name= "keyword_update'.$i.'" value="'.$keyword[$i].'" class="form-control" placeholder="Modify Keyword" oninput="this.className = """></p><br>';
        }
    }
    if(sizeof($movie_media_id) > 0){
        for($i = 0; $i < sizeof($movie_media_id); $i++){
            echo '<label for="m_link'.$i.'"> Media Link #'.($i+1).' </label>';
            echo '<p><input id="m_link'.$i.'" name= "m_link_update'.$i.'" value="'.$m_link[$i].'" class="form-control" placeholder="Modify Media Link for '.$movie_media_id[$i].'" oninput="this.className = """></p>';
            echo '<label for="m_link_type'.$i.'"> Media Link Type #'.($i+1).' </label>';
            echo '<p><input id="m_link_type'.$i.'" name= "m_link_type_update'.$i.'" value="'.$m_link_type[$i].'" class="form-control" placeholder="Modify Media Link type for '.$movie_media_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="movie_media_id'.$i.'" value="'.$movie_media_id[$i].'"><br>';
        }
    }
    if(sizeof($movie_quote_id) > 0){
        for($i = 0; $i < sizeof($movie_quote_id); $i++){
            echo '<label for="quote'.$i.'"> Quote #'.($i+1).' </label>';
            echo '<p><input id="quote'.$i.'" name= "movie_quote_name_update'.$i.'" value="'.$movie_quote_name[$i].'" class="form-control" placeholder="Modify Quote for '.$movie_quote_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="movie_quote_id'.$i.'" value="'.$movie_quote_id[$i].'"><br>';
        }
    }
    if(sizeof($movie_trivia_id) > 0){
        for($i = 0; $i < sizeof($movie_trivia_id); $i++){
            echo '<label for="trivia'.$i.'"> Trivia #'.($i+1).' </label>';
            echo '<p><input id="trivia'.$i.'" name= "movie_trivia_name_update'.$i.'" value="'.$movie_trivia_name[$i].'" class="form-control" placeholder="Modify Trivia for '.$movie_trivia_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="movie_trivia_id'.$i.'" value="'.$movie_trivia_id[$i].'"><br>';
        }
    }
?>
    
    <input type="hidden" name="movie_id" value="<?php echo $id; ?>">

    <div style="overflow:auto;">
        <div  class="text-left">
            <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Modify Movie</button>
        </div>
    </div>
    
    <div style="text-align:center;margin-top:40px;">
        <span class="step"></span>
    </div>

</form>
<?PHP
}

if(isset($_GET['song_id'])){
    echo '<form id="movieModify" class="addSong" action="modifyTheSongs.php" method= "POST">';
    echo '<h1>Modify a Song</h1>';

    $title = "";
    $lyrics = "";
    $theme = "";
    $song_trivia_id = [];
    $song_trivia_name = [];
    $keyword = [];
    $song_media_id = [];
    $s_link = [];
    $s_link_type = [];
    
    $sql = mysqli_query($db, "SELECT * FROM songs WHERE song_id = '".$song_id."'");
    if(mysqli_num_rows($sql)>0){
        while($row = mysqli_fetch_assoc($sql)){
          $song_id = $row['song_id'];
          $title = $row['title'];
          $lyrics = $row['lyrics'];
          $theme = $row['theme'];
        }
    }
    
    $sql2 = mysqli_query($db,"SELECT * FROM song_trivia WHERE song_id = '".$song_id."'");
    if(mysqli_num_rows($sql2)>0){
      while($row = mysqli_fetch_assoc($sql2)){
        $song_trivia_id[] = $row['song_trivia_id'];
        $song_trivia_name[] = $row['song_trivia_name'];
      }
    }
    
    $sql3 = mysqli_query($db,"SELECT * FROM song_keywords WHERE song_id = '".$song_id."'");
    if(mysqli_num_rows($sql3)>0){
      while($row = mysqli_fetch_assoc($sql3)){
        $keyword[] = $row['keyword'];
      }
    }
    
    $sql4 = mysqli_query($db,"SELECT * FROM song_media WHERE song_id = '".$song_id."'");
    if(mysqli_num_rows($sql4)>0){
      while($row = mysqli_fetch_assoc($sql4)){
        $song_media_id[] = $row['song_media_id'];
        $s_link[] = $row['s_link'];
        $s_link_type[] = $row['s_link_type'];
      }
    }
?>
        
    <label for="title"> Title </label>
    <p><input type="text" name="title" class="form-control" id="title" value="<?php echo $title; ?>" <?php echo $addSong; ?> ></p>
    <label for="lyrics">Lyrics</label>
    <p><input type="text" name="lyrics" class="form-control" id="lyrics" value="<?php echo $lyrics; ?>"></p>
    <label for="theme">Theme</label>
    <p><input type="text" name="theme" class="form-control" id="theme" value=" <?php echo $theme; ?> "></p>
        
    <input type="hidden" name="song_id" value="<?php echo $song_id; ?>">
        
<?php
    if(sizeof($song_trivia_id) > 0){
        for($i = 0; $i < sizeof($song_trivia_id); $i++){
            echo '<label for="trivia'.$i.'"> Trivia #'.($i+1).' </label>';
            echo '<p><input id="trivia'.$i.'" name= "song_trivia_name_update'.$i.'" value="'.$song_trivia_name[$i].'" class="form-control" placeholder="Modify Trivia for '.$song_trivia_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="song_trivia_id'.$i.'" value="'.$song_trivia_id[$i].'"><br>';
        }
    }
    if(sizeof($keyword) > 0){
        for($i = 0; $i < sizeof($keyword); $i++){
            echo '<label for="keyword'.$i.'"> Keyword #'.($i+1).' </label>';
            echo '<p><input id="keyword'.$i.'" name= "song_keyword_update'.$i.'" value="'.$keyword[$i].'" class="form-control" placeholder="Modify Keyword" oninput="this.className = """></p>';
        }
    }
    
    if(sizeof($song_media_id) > 0){
        for($i = 0; $i < sizeof($song_media_id); $i++){
            echo '<label for="s_link'.$i.'"> Song Link #'.($i+1).' </label>';
            echo '<p><input id="s_link'.$i.'" name= "s_link_update'.$i.'" value="'.$s_link[$i].'" class="form-control" placeholder="Modify Media Link for '.$song_media_id[$i].'" oninput="this.className = """></p>';
            echo '<label for="s_link_type'.$i.'"> Media Link Type #'.($i+1).' </label>';
            echo '<p><input id="s_link_type'.$i.'" name= "s_link_type_update'.$i.'" value="'.$s_link_type[$i].'" class="form-control" placeholder="Modify Media Link type for '.$song_media_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="song_media_id'.$i.'" value="'.$song_media_id[$i].'"><br>';
        }
    }
?>
        
    <div style="overflow:auto;">
      <div  class="text-left">
        <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">  Modify Song  </button>
      </div>
    </div>
        
    <div style="text-align:center;margin-top:40px;">
        <span class="step"></span>
    </div>

</form>
<?PHP
}

if(isset($_GET['people_id'])){
    echo '<form id="movieModify" class="addPerson" action="modifyThePeople.php" method= "POST">';
    echo '<h1>Modify People</h1>';

    $stage_name = "";
    $first_name = "";
    $middle_name = "";
    $last_name = "";
    $gender = "";
    $image_name = "";
    $movie_id = [];
    $role = [];
    $screen_name = [];
    $people_trivia_id = [];
    $people_trivia_name = [];
    $song_id = [];
    $song_role = [];
    
    $sql = mysqli_query($db, "SELECT * FROM people WHERE people_id = '".$people_id."'");
    if(mysqli_num_rows($sql)>0){
        while($row = mysqli_fetch_assoc($sql)){
          $people_id = $row['people_id'];
          $stage_name = $row['stage_name'];
          $first_name = $row['first_name'];
          $middle_name = $row['middle_name'];
          $last_name = $row['last_name'];
          $gender = $row['gender'];
          $image_name = $row['image_name'];
        }
    }
    
    $sql2 = mysqli_query($db,"SELECT * FROM movie_people WHERE people_id = '".$people_id."'");
    if(mysqli_num_rows($sql2)>0){
      while($row = mysqli_fetch_assoc($sql2)){
        $movie_id[] = $row['movie_id'];
        $role[] = $row['role'];
        $screen_name[] = $row['screen_name'];
      }
    }
    
    $sql3 = mysqli_query($db,"SELECT * FROM people_trivia WHERE people_id = '".$people_id."'");
    if(mysqli_num_rows($sql3)>0){
      while($row = mysqli_fetch_assoc($sql3)){
        $people_trivia_id[] = $row['people_trivia_id'];
        $people_trivia_name[] = $row['people_trivia_name'];
      }
    }
    
    $sql4 = mysqli_query($db,"SELECT * FROM song_people WHERE people_id = '".$people_id."'");
    if(mysqli_num_rows($sql4)>0){
      while($row = mysqli_fetch_assoc($sql4)){
        $song_id[] = $row['song_id'];
        $song_role[] = $row['role'];
      }
    }
?>
        
    <label for="stage_name">Stage Name</label>
    <p><input type="text" name="stage_name" class="form-control" id="stage_name" value="<?php echo $stage_name; ?>" <?php echo $addPerson; ?> ></p>
    <label for="first_name">First Name</label>
    <p><input type="text" name="first_name" class="form-control" id="first_name" value="<?php echo $first_name; ?>"></p>
    <label for="middle_name">Middle Name</label>
    <p><input type="text" name="middle_name" class="form-control" id="middle_name" value="<?php echo $middle_name; ?>"></p>
    <label for="last_name">Last Name</label>
    <p><input type="text" name="last_name" class="form-control" id="last_name" value="<?php echo $last_name; ?>"></p>
    <label for="gender">Gender</label>
    <p><input type="text" name="gender" class="form-control" id="gender" value="<?php echo $gender; ?>"></p>
    <label for="image_name">Image Name</label>
    <p><input type="text" name="image_name" class="form-control" id="image_name" value="<?php echo $image_name; ?>"></p>
        
    <input type="hidden" name="people_id" value="<?php echo $people_id; ?>">
        
<?php
    if(sizeof($movie_id) > 0){
        for($i = 0; $i < sizeof($movie_id); $i++){
            echo '<label for="screen_name'.$i.'"> Movie #'.($i+1).': Screen Name </label>';
            echo '<p><input id="screen_name'.$i.'" name= "screen_name_update'.$i.'" value="'.$screen_name[$i].'" class="form-control" placeholder="Modify Screen Name for '.$movie_id[$i].'" oninput="this.className = """></p>';
            echo '<label for="role'.$i.'"> Movie #'.($i+1).': Role </label>';
            echo '<p><input id="role'.$i.'" name= "role_update'.$i.'" value="'.$role[$i].'" class="form-control" placeholder="Modify Role for '.$movie_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="movie_id'.$i.'" value="'.$movie_id[$i].'"><br>';
        }
    }
    if(sizeof($people_trivia_id) > 0){
        for($i = 0; $i < sizeof($people_trivia_id); $i++){
            echo '<label for="people_trivia_name'.$i.'"> People Trivia #'.($i+1).' </label>';
            echo '<p><input id="people_trivia_name'.$i.'" name= "people_trivia_name_update'.$i.'" value="'.$people_trivia_name[$i].'" class="form-control" placeholder="Modify Keyword for '.$people_trivia_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="people_trivia_id'.$i.'" value="'.$people_trivia_id[$i].'"><br>';
        }
    }
    
    if(sizeof($song_id) > 0){
        for($i = 0; $i < sizeof($song_id); $i++){
            echo '<label for="song_role'.$i.'"> Song #'.($i+1).' </label>';
            echo '<p><input id="song_role'.$i.'" name= "song_role_update'.$i.'" value="'.$song_role[$i].'" class="form-control" placeholder="Modify Song Role for '.$song_id[$i].'" oninput="this.className = """></p>';
            echo '<input type="hidden" name="song_id'.$i.'" value="'.$song_id[$i].'"><br>';
        }
    }
?>
        
    <div style="overflow:auto;">
      <div  class="text-left">
        <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">  Modify Person  </button>
      </div>
    </div>
        
    <div style="text-align:center;margin-top:40px;">
        <span class="step"></span>
    </div>

</form>


<?php
}
    db_disconnect($db);
?>

<style type="text/css">
#movieModify {
  background-color: #ffffff;
  margin: 10px auto;
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
</body>
</html>

