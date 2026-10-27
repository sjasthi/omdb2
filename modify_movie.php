<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
?>
<?php

?>

<?php
if (!isset($_SESSION['username']))
     {
      header("Location: login.php");
      exit();
     }
$page_title = 'The Cow Layer';
$nav_selected = "MOVIES";
$left_buttons = "YES";
$left_selected = "NO";
include("./nav.php");
$movie_id = filter_input(INPUT_GET, 'movie_id', FILTER_VALIDATE_INT);
if (!$movie_id) {
    header("Location: movies.php");
    exit();
}
?>



<form id="movieModify" action="modifyTheMovies.php" method= "POST"><?php echo csrf_field(); ?>


<?php
$native_name = "";
$english_name = "";
$year_made = "";
$id = "";
$stmt = db_prepare($db, "SELECT movie_id, native_name, english_name, year_made
     FROM movies
     WHERE movie_id = ?");
mysqli_stmt_bind_param($stmt, "i", $movie_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
if ($row = mysqli_fetch_assoc($result)) {
    $native_name = $row['native_name'];
    $english_name = $row['english_name'];
    $year_made = $row['year_made'];
    $id = $row['movie_id'];
}
mysqli_stmt_close($stmt);
?>
<div class="tab">

  <p>
    <input name="native_name_update" maxlength="180"
           value="<?php
echo h($native_name);
?>"
           placeholder="Modify Native Name"
           class="form-control">
  </p>

  <p>
    <input name="english_name_update" maxlength="180"
           value="<?php
echo h($english_name);
?>"
           placeholder="Modify English Name"
           class="form-control">
  </p>

  <p>
    <input name="year_update"
           value="<?php
echo h($year_made);
?>"
           placeholder="Modify Year"
           class="form-control">
  </p>sss

  <input type="hidden"
         name="movie_id"
         value="<?php
echo h($id);
?>">

</div>
<div style="overflow:auto;">
  <div  class="text-left">
           <button type="submit" name="submit" class="btn btn-primary btn-md align-items-center">Save changes</button>
  </div>
</div>
<div style="text-align:center;margin-top:40px;">
<span class="step"></span>
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

<?php
db_disconnect($db);
include("./footer.php");
?>

<?php