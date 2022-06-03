<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "SONGS";
    $left_buttons = "YES";
    $left_selected = "SONGS";

    include("./nav.php");
    $song_id = '';
    if(isset($_GET['song_id'])){
        $song_id = $_GET['song_id'];
    }
$test = '';
    if(isset($_POST['add'])){
        $song_id = $_POST['song_id'];
        $people_id = [];
        $roles = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'people_id') === 0) {
                $people_id[] = $v;
            }
            if(strpos($k, 'role') === 0) {
                if(!empty($v)){
                    $roles[] = $v;
                }
            }
        }
        for($i = 0, $size = count($people_id); $i < $size; $i++){
            
            $sql = "insert ignore into song_people (song_id, people_id, role) values (".$song_id.", ".$people_id[$i].", '".$roles[$i]."');";
            mysqli_query($db, $sql);
        }
    }
    else if(isset($_POST['delete'])){
        $song_id = $_POST['song_id'];
        $people_id = [];
        $roles = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'people_id') === 0) {
                $people_id[] = $v;
            }
            if(strpos($k, 'role') === 0) {
                $roles[] = $v;
            }
        }
        for($i = 0, $size = count($people_id); $i < $size; $i++){
            $sql = "delete from song_people where song_id = ".$song_id." and people_id = ".$people_id[$i]." and role = '".$roles[$i]."';";
            mysqli_query($db, $sql);
        }
    }

    $sql = mysqli_query($db,"select people.*, song_people.* from people left join song_people on people.people_id = song_people.people_id where song_people.song_id = ".$song_id.";");

    $sql2 = mysqli_query($db,"select title from songs where song_id = ".$song_id.";");
    $name = mysqli_fetch_assoc($sql2);
  
  ?>

<!DOCTYPE html>
<html>
<div class = "container">
<h1>Attach A Person to <?php echo $name['title'];?></h1>

<h3>People Library</h3>
<form name="add_person" id="add_person" class="add_person" method="post" action="songs_people_list.php">
  <table id="info" cellpadding="0" cellspacing="0" border="0"
      class="datatable table table-striped table-bordered datatable-style table-hover"
      width="100%" style="width: 100px;">
              <thead>
              <tr id="table-first-row">
                <th>Select</th>
              <th scope="col">ID</th>
              <th scope="col">Stage Name</th>
              <th scope="col">Add Role</th>
              </tr>
              </thead>
              <tbody>

  <?php

  $count = 1;
      $query = mysqli_query($db,"SELECT * from people;");


    if(mysqli_num_rows($query)>0){
      while($row = mysqli_fetch_assoc($query)){


  ?>


            <tr>
            <td><input type="checkbox" class="add" id="add" name="people_id<?php echo $count; ?>" value="<?php echo $row['people_id']; ?>" onclick="addPeople(this)"></td>
          <td><?php echo $row['people_id']; ?></td>
          <td><?php echo $row['stage_name']; ?></td>
          <td>
              <select class="add" name="role<?php echo $count; ?>" disabled>
                  <option value="" disabled selected>Select Role</option>
                  <option value="Composer">Composer</option>
                  <option value="Songwriter">Songwriter</option>
                  <option value="Producer">Producer</option>
                  <option value="Performer">Performer</option>
                  <option value="Lyricist">Lyricist</option>
                  <option value="Other">Other</option>
              </select>
          </td>

            </tr>

            <?php
            $count++;
                }
                  }
            ?>

            </tbody>
            </table>
            <input type="hidden" name="song_id" value="<?php echo $song_id; ?>" />
            <input type="checkbox" name="add" id='addFinal' value="add" style="display:none;" required>
            </form>
            
              <button type="button" name="check" id="check" onClick="selectAll()">Check/Uncheck All</button>
              <button form="add_person" type="submit" style="margin-left:0px;" onclick="return checkAdd()">Add</button>
        </form>

<?php
if(mysqli_num_rows($sql)>0){
    $count = '1';
?>
    <br><br><br><br>
    <h3>People for <?php echo $name['title'];?></h3>
    <form name="delete_person" id="delete_person" method="post" action="songs_people_list.php">
      <table id="info1" cellpadding="0" cellspacing="0" border="0"
          class="datatable table table-striped table-bordered datatable-style table-hover"
          width="100%" style="width: 100px;">
                  <thead>
                  <tr id="table-first-row">
                    <th>Select</th>
                  <th scope="col">ID</th>
                  <th scope="col">Name</th>
                  <th scope="col">Role</th>
                  </tr>
                  </thead>
                  <tbody>
    <?php
        while($row = mysqli_fetch_assoc($sql)){
    ?>
            <tr>
            <td><input type="checkbox" class="delete" name="people_id<?php echo $count; ?>" value="<?php echo $row['people_id']; ?>" onclick="this.nextSibling.nextSibling.disabled = false;" autofocus>
            <input type="hidden" name="role<?php echo $count;?>" value="<?php echo $row['role']; ?>" disabled>
            <td><?php echo $row['people_id']; ?></td>
            <td><?php echo $row['stage_name']; ?></td>
            <td><?php echo $row['role']; ?></td>

            </tr>
            
            
    <?php
    $count++;
        }
    ?>
            </tbody>
            </table>
            <input type="hidden" name="song_id" value="<?php echo $song_id; ?>" />
            <input type="checkbox" name="delete" id='deleteFinal' value="delete" style="display:none;" required>
            </form>

              <button type="button" name="check" id="check" onClick="deleteAll()">Check/Uncheck All</button>
              <button form="delete_person" type="submit" style="margin-left:0px;" onclick="return checkDelete()">Delete</button>
            </form>
    
<?php
    }
?>

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
  font-size = 3px;
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
      $(this).html('<input type="text" placeholder="Search" />');
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
function addPeople(source){
    var original = document.getElementsByName(source.name)[0];
    var next = document.getElementsByName(source.name)[0].parentElement.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.children[0];
    if(original.checked == true){
        original.required = true;
        next.required = true;
        next.disabled = false;
    } else {
        original.required = false;
        next.required = false;
        next.disabled = true;
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
                items[i].nextSibling.nextSibling.disabled = false;
            }
        }
    }
    if(delCount > 0){
        finalDelCheck.checked = true;
        delCount = 0;
        return true;
    } else {
        alert('Must Select Person to Delete from Movie');
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
        alert('Must Select Person to Add');
        addCount = 0;
        return false;
    }
}

var flag = false;
function selectAll() {
    var items = document.querySelectorAll('input[type=checkbox]');
    for (var i = 0; i < items.length; i++) {
        if(flag == false){
            if(items[i].classList.contains('add')){
                items[i].checked = true;
                addPeople(items[i]);
            }
        } else {
            if(items[i].classList.contains('add')){
                items[i].checked = false;
                addPeople(items[i]);
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

</script>
<?php
db_disconnect($db);
include("./footer.php");
?>

