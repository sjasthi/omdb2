<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "YES";
    $left_selected = "MOVIES";

    include("./nav.php");
    $movie_id = '';
    if(isset($_GET['movie_id'])){
        $movie_id = $_GET['movie_id'];
    }

    if(isset($_POST['add'])){
        $movie_id = $_POST['movie_id'];
        $song_id = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'song_id') === 0) {
                $song_id[] = $v;
            }
        }
        foreach($song_id as $song){
            $sql = "insert ignore into movie_song (movie_id, song_id) values (".$movie_id.", ".$song.");";
            mysqli_query($db, $sql);
        }
    }
    else if(isset($_POST['delete'])){
        $movie_id = $_POST['movie_id'];
        $song_id = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'song_id') === 0) {
                $song_id[] = $v;
            }
        }
        foreach($song_id as $song){
            $sql = "delete from movie_song where movie_id = ".$movie_id." and song_id = ".$song.";";
            mysqli_query($db, $sql);
        }
    }

    $sql = mysqli_query($db,"select songs.* from songs left join movie_song on songs.song_id = movie_song.song_id where movie_song.movie_id = ".$movie_id.";");

    $sql2 = mysqli_query($db,"select native_name from movies where movie_id = ".$movie_id.";");
    $name = mysqli_fetch_assoc($sql2);
  
  ?>

<!DOCTYPE html>
<html>
<div class = "container">
<h1>Attach A Song to <?php echo $name['native_name'];?></h1>
<?php
if(mysqli_num_rows($sql)>0){
    $count = '1';
?>
    <br><br><br><br>
    <h3>Songs for <?php echo $name['native_name'];?></h3>
    <form name="delete_song" id="delete_song" method="post" action="add_song.php">
      <table id="info1" cellpadding="0" cellspacing="0" border="0"
          class="datatable table table-striped table-bordered datatable-style table-hover"
          width="100%" style="width: 100px;">
                  <thead>
                  <tr id="table-first-row">
                    <th>Select</th>
                  <th scope="col">ID</th>
                  <th scope="col">Title</th>
                  <th scope="col">Lyrics</th>
                  <th scope="col">Theme</th>
                  </tr>
                  </thead>
                  <tbody>
    <?php
        while($row = mysqli_fetch_assoc($sql)){
    ?>
            <tr>
            <td><input type="checkbox" class="delete" name="song_id<?php echo $count; ?>" value="<?php echo $row['song_id']; ?>"></td>
            <td><?php echo $row['song_id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['lyrics']; ?></td>
            <td><?php echo $row['theme']; ?></td>

            </tr>
            
            
    <?php
    $count++;
        }
    ?>
            </tbody>
            </table>
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>" />
            <input type="checkbox" name="delete" id="deleteFinal" value="delete" style="display:none;" required>
            </form>

              <button type="button" name="check" id="check" onClick="deleteAll()">Check/Uncheck All</button>
              <button form="delete_song" type="submit" style="margin-left:0px;" onclick="return checkDelete()">Delete</button>
            </form>
    
<?php
    }
if(mysqli_num_rows($sql)>0){
?>
<br><br><br><br>
<?php } else {?>
<br>
<?php } ?>
<h3>Song Library</h3>
<form name="add_song" id="add_song" class="add_song" method="post" action="add_song.php">
  <table id="info" cellpadding="0" cellspacing="0" border="0"
      class="datatable table table-striped table-bordered datatable-style table-hover"
      width="100%" style="width: 100px;">
              <thead>
              <tr id="table-first-row">
                <th>Select</th>
              <th scope="col">ID</th>
              <th scope="col">Title</th>
              <th scope="col">Lyrics</th>
              <th scope="col">Theme</th>
              </tr>
              </thead>
              <tbody>

  <?php

  $count = '1';
      $query1 = mysqli_query($db,"select songs.song_id from songs left join movie_song on movie_song.song_id = songs.song_id where movie_song.movie_id = $movie_id");
      $avoid_songs = [];
      $attempt = '';
    if(mysqli_num_rows($query1)>0){
      while($row = mysqli_fetch_assoc($query1)){
          $avoid_songs[] = $row['song_id'];
      }
        $attempt = "select * from songs where ";

        $count = 0;
        while ($count < count($avoid_songs)){
            if(($count + 1) != count($avoid_songs)){
                $attempt .= 'song_id != '.$avoid_songs[$count].' and ';
            } else {
                $attempt .= 'song_id != '.$avoid_songs[$count];
            }
            $count++;
        }
    } else {
        $attempt = 'select * from songs';
    }

    

    $query = mysqli_query($db, $attempt);
    if(mysqli_num_rows($query)>0){
      while($row = mysqli_fetch_assoc($query)){


  ?>


            <tr>
            <td><input type="checkbox" class="add" id="add" name="song_id<?php echo $count; ?>" value="<?php echo $row['song_id']; ?>"></td>
            <td><?php echo $row['song_id']; ?></td>
            <td><?php echo $row['title']; ?></td>
            <td><?php echo $row['lyrics']; ?></td>
            <td><?php echo $row['theme']; ?></td>

            </tr>

            <?php
            $count++;
                }
                  }
            ?>

            </tbody>
            </table>
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>" />
            <input type="checkbox" name="add" id="addFinal" value="add" style="display:none;" required>
            </form>
            
              <button type="button" name="check" id="check" onClick="selectAll()">Check/Uncheck All</button>
              <button form="add_song" type="submit" style="margin-left:0px;" onclick="return checkAdd()">Add</button>
        </form>

<div>
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
<script type="text/javascript" language="javascript">
  $(document).ready(function() {

    $('#info').DataTable({
      dom: 'lfrtBip',
      buttons: [
        'copy', 'excel', 'csv', 'pdf'
      ]
    });

    $('#info thead tr').clone(true).appendTo('#info thead');
    $('#info thead tr:eq(1) th').each(function(i) {
      var title = $(this).text();
      if(title != 'Select'){
      $(this).html('<input type="text" placeholder="Search ' + title + '" />');
}
      $('input', this).on('keyup change', function() {
        if (table.column(i).search() !== this.value) {
          table
            .column(i)
            .search(this.value)
            .draw();
        }
      });
    });

    var table = $('#info').DataTable({
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
    });

  });
</script>


<script type="text/javascript" language="javascript">
  $(document).ready(function() {

    $('#info1').DataTable({
      dom: 'lfrtBip',
      buttons: [
        'copy', 'excel', 'csv', 'pdf'
      ]
    });

    $('#info1 thead tr').clone(true).appendTo('#info1 thead');
    $('#info1 thead tr:eq(1) th').each(function(i) {
      var title = $(this).text();
    if(title != 'Select'){
      $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    }
      $('input', this).on('keyup change', function() {
        if (table.column(i).search() !== this.value) {
          table
            .column(i)
            .search(this.value)
            .draw();
        }
      });
    });

    var table = $('#info1').DataTable({
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
    });

  });
</script>
<script>
var flag = false;
function selectAll() {
    var items = document.querySelectorAll('input[type=checkbox]')
    for (var i = 0; i < items.length; i++) {
        if(flag == false){
            if(items[i].classList.contains('add')){
                items[i].checked = true;
            }
        } else {
            if(items[i].classList.contains('add')){
                items[i].checked = false;
            }
        }
    }
    if(flag == false){
        flag = true;
    } else {
        flag = false;
    }
}

var delflag = false;
function deleteAll() {
    var elements = document.querySelectorAll('input[type=checkbox]')
    for (var i = 0; i < elements.length; i++) {
        if(delflag == false){
            if(elements[i].classList.contains('delete')){
            elements[i].checked = true;
                }
        } else {
            if(elements[i].classList.contains('delete')){
            elements[i].checked = false;
                }
        }
    }
    if(delflag == false){
        delflag = true;
    } else {
        delflag = false;
    }
}

var delCount = 0;
function checkDelete(){
    var finalDelCheck = document.getElementById('deleteFinal');
    var items = document.querySelectorAll('input[type=checkbox]');
    for (var i = 0; i < items.length; i++) {
        if(items[i].classList.contains('delete')){
            if(items[i].checked == true){
                delCount++;
            }
        }
    }
    if(delCount > 0){
        finalDelCheck.checked = true;
        delCount = 0;
        return true;
    } else {
        alert('Must Select Song to Delete from Movie');
        delCount = 0;
        return false;
    }
}

var addCount = 0;
function checkAdd(){
    var finalAddCheck = document.getElementById('addFinal');
    var items = document.querySelectorAll('input[type=checkbox]');
    for (var i = 0; i < items.length; i++) {
        if(items[i].classList.contains('add')){
            if(items[i].checked == true){
                addCount++;
            }
        }
    }
    if(addCount > 0){
        finalAddCheck.checked = true;
        addCount = 0;
        return true;
    } else {
        alert('Must Select Song to Add');
        addCount = 0;
        return false;
    }
}

</script>
<?php
db_disconnect($db);
include("./footer.php");
?>
