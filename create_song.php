<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    try {
        catalog_insert_song($db, $_POST);
        redirect_to('songs.php?create=Success');
    } catch (InvalidArgumentException $error) { security_fail(400, $error->getMessage()); }
}
?>
<?php
  if (!isset($_SESSION['username']))
      { 
       header("Location: login.php");
       exit();
      }     
?>
<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "SONGS";
    $left_buttons = "YES";
    $left_selected = "SONGS";

     include("./nav.php");

  ?>



<h1>Add a song</h1>

<form method="post" action="create_song.php"><?php echo csrf_field(); ?>
    <label for="title">Title</label>
    <p><input name= "title" placeholder="Title" ></p>
    <label for="lyrics">Lyrics</label>
    <p><input name= "lyrics" placeholder="Lyrics"></p>
    <label for="theme">Theme</label>
    <p><input name= "theme" placeholder="Theme" ></p>

  <button type="submit" name="create_song" class="btn btn-primary">Submit</button>
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
