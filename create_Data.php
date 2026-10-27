<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
?>
<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";
     include("./nav.php");
    $movie_id = $_GET["movie_id"];
  ?>



<form class="form-panel" id="movieData" action="createTheData.php" method= "post"><?php echo csrf_field(); ?>

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

  $sql = db_query($db, ['sql' => 'SELECT * FROM movies WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql)>0){
    while($row = mysqli_fetch_assoc($sql)){
      $native_name = $row['native_name'];
      $english_name = $row['english_name'];
      $year_made = $row['year_made'];
      $id = $row['movie_id'];
    }
  }
  $sql2 = db_query($db, ['sql' => 'SELECT * FROM movie_numbers WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql2)>0){
    while($row = mysqli_fetch_assoc($sql2)){
      $running_time = $row['running_time'];
      $budget = $row['budget'];
      $box_office = $row['box_office'];
    }
  }
  $sql3 = db_query($db, ['sql' => 'SELECT * FROM movie_data WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql3)>0){
    while($row = mysqli_fetch_assoc($sql3)){
      $language = $row['language'];
      $country = $row['country'];
      $genre = $row['genre'];
      $plot = $row['plot'];
      $tag_line = $row['tag_line'];
    }
  }
  $sql4 = db_query($db, ['sql' => 'SELECT * FROM movie_keywords WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql4)>0){
    while($row = mysqli_fetch_assoc($sql4)){
      $keyword[] = $row['keyword'];
    }
  }
  $sql5 = db_query($db, ['sql' => 'SELECT * FROM movie_anagrams WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql5)>0){
    while($row = mysqli_fetch_assoc($sql5)){
      $anagram[] = $row['anagram'];
      $anagram_id[] = $row['anagram_id'];
    }
  }
  $sql6 = db_query($db, ['sql' => 'SELECT * FROM movie_media WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql6)>0){
    while($row = mysqli_fetch_assoc($sql6)){
      $movie_media_id[] = $row['movie_media_id'];
      $m_link[] = $row['m_link'];
      $m_link_type[] = $row['m_link_type'];
    }
  }
  $sql7 = db_query($db, ['sql' => 'SELECT * FROM movie_quotes WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql7)>0){
    while($row = mysqli_fetch_assoc($sql7)){
      $movie_quote_id[] = $row['movie_quote_id'];
      $movie_quote_name[] = $row['movie_quote_name'];
    }
  }
  $sql8 = db_query($db, ['sql' => 'SELECT * FROM movie_trivia WHERE movie_id = ?', 'params' => [$movie_id]]);
  if(mysqli_num_rows($sql8)>0){
    while($row = mysqli_fetch_assoc($sql8)){
      $movie_trivia_id[] = $row['movie_trivia_id'];
      $movie_trivia_name[] = $row['movie_trivia_name'];
    }
  }
  ?>
  <h2>Create Movie Data For <?php echo h($native_name); ?></h2>
  <?php
  if(empty($language) && empty($country) && empty($genre) && empty($plot) && empty($tag_line)){ ?>
      <label for="language"> Language </label>
      <p><input name= "language" maxlength="10" id="language" value="<?php echo h($language); ?>" placeholder="Add Language" class="form-control"></p>
      <label for="country"> Country </label>
      <p><input name= "country" maxlength="20" id="country" value="<?php echo h($country); ?>" class="form-control" placeholder="Add Country"></p>
      <label for="genre"> Genre </label>
      <p><input name= "genre" maxlength="15" id="genre" value="<?php echo h($genre); ?>" placeholder="Add Genre" class="form-control"></p>
      <label for="plot"> Plot </label>
      <p><input name= "plot" maxlength="500" id="plot" value="<?php echo h($plot); ?>" placeholder="Add Plot" class="form-control"></p>
      <label for="tag_line"> Tag Line </label>
      <p><input name= "tag_line" maxlength="100" id="tag_line" value="<?php echo h($tag_line); ?>" class="form-control" placeholder="Add Tag Line"></p><br>
  <?php } ?>

<?php
  if(sizeof($anagram_id) >= 0){
      echo '<label for="anagram"> Add Anagram </label>';
      echo '<p><input id="anagram" name= "anagram" maxlength="180" class="form-control" placeholder="Add Anagram"></p><br>';
  }

  if(sizeof($keyword) >= 0){
      echo '<label for="keyword"> Add Keyword </label>';
      echo '<p><input id="keyword" name= "keyword" maxlength="15" class="form-control" placeholder="Add Keyword"></p><br>';
  }


  if(sizeof($movie_media_id) >= 0){
          echo '<label for="m_link"> Add Media Link </label>';
          echo '<p><input id="m_link" name= "m_link" maxlength="100" class="form-control" placeholder="Add Media Link"></p>';
          echo '<label for="m_link_type"> Add Media Link Type </label>';
          echo '<p><input id="m_link_type" name= "m_link_type" maxlength="10" class="form-control" placeholder="Add Media Link Type"></p><br>';
  }

  if(sizeof($movie_quote_id) >= 0){
      echo '<label for="quote"> Add Quote </label>';
      echo '<p><input id="quote" name= "movie_quote_name" maxlength="300"  class="form-control" placeholder="Add Quote"></p><br>';
  }

  if(sizeof($movie_trivia_id) >= 0){
      echo '<label for="trivia"> Add Trivia </label>';
      echo '<p><input id="trivia" name= "movie_trivia_name" maxlength="300" class="form-control" placeholder="Add Trivia"></p><br>';
  }
?>

  <input type="hidden" name="movie_id" value="<?php echo h($movie_id); ?>">

  <div style="overflow:auto;">
      <div  class="text-left">
          <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Save details</button>
<a class="btn btn-default" href="movie_info.php?movie_id=<?php echo h($movie_id); ?>">Cancel</a>
      </div>
  </div>

  <div style="text-align:center;margin-top:40px;">
      <span class="step"></span>
  </div>
</div>

</form>
<style type="text/css">


/* Style the input fields */


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



<?php include __DIR__ . '/footer.php'; ?>
