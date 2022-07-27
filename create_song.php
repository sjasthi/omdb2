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
    $nav_selected = "SONGS";
    $left_buttons = "YES";
    $left_selected = "SONGS";

     include("./nav.php");

  ?>

<!DOCTYPE html>
<html>
<h1>Add a song</h1>

<form method="post" action="create_song.php">
    <label for="title">Title</label>
    <p><input name= "title" placeholder="Title" ></p>
    <label for="lyrics">Lyrics</label>
    <p><input name= "lyrics" placeholder="Lyrics"></p>
    <label for="theme">Theme</label>
    <p><input name= "theme" placeholder="Theme" ></p>

  <button type="submit" name="create_song" class="btn btn-primary">Submit</button>
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
<?php
if(isset($_POST['create_song'])){
  $title = $_POST['title'];
  $lyrics = $_POST['lyrics'];
  $theme = $_POST['theme'];

  $query = mysqli_query($db, "select song_id from songs order by song_id desc limit 1;");
  $row = $query->fetch_assoc();
  $song_id = $row['song_id'] + 1;

  $result = mysqli_query($db, "INSERT INTO `songs`(`song_id`, `title`, `lyrics`, `theme`) VALUES ($song_id, '$title', '$lyrics', '$theme')");

  db_disconnect($db);
  header('Location: songs.php?create=Success');
}
?>
</html>
