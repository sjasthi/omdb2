<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "YES";
    $left_selected = "MOVIE";

    include("./nav.php");
    $movie_id = '';
    if(isset($_GET['movie_id'])){
        $movie_id = $_GET['movie_id'];
    }
$test = '';
    if(isset($_POST['add'])){
        $movie_id = $_POST['movie_id'];
        $people_id = [];
        $roles = [];
        $screen_name = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'people_id') === 0) {
                $people_id[] = $v;
            }
            if(strpos($k, 'screen_name') === 0) {
                if(!empty($v)){
                    $screen_name[] = $v;
                }
            }
            if(strpos($k, 'role') === 0) {
                if(!empty($v)){
                    $roles[] = $v;
                }
            }
        }
        for($i = 0, $size = count($roles); $i < $size; $i++){
            $sc = mysqli_real_escape_string($db, $screen_name[$i]);
            $sql = "insert ignore into movie_people (movie_id, people_id, role, screen_name) values (".$movie_id.", ".$people_id[$i].", '".$roles[$i]."', '".$sc."');";
            mysqli_query($db, $sql);
        }
    }
    else if(isset($_POST['delete'])){
        $movie_id = $_POST['movie_id'];
        $people_id = [];
        $roles = [];
        $screen_name = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'people_id') === 0) {
                $people_id[] = $v;
            }
            if(strpos($k, 'role') === 0) {
                $roles[] = $v;
            }
            if(strpos($k, 'screen_name') === 0) {
                $screen_name[] = $v;
            }
        }
        for($i = 0, $size = count($people_id); $i < $size; $i++){
            $sql = "delete from movie_people where movie_id = ".$movie_id." and people_id = ".$people_id[$i]." and role = '".$roles[$i]."' and screen_name = '".$screen_name[$i]."';";
            mysqli_query($db, $sql);
        }
    }

    $sql = mysqli_query($db,"select people.*, movie_people.* from people left join movie_people on people.people_id = movie_people.people_id where movie_people.movie_id = ".$movie_id.";");

    $sql2 = mysqli_query($db,"select native_name from movies where movie_id = ".$movie_id.";");
    $name = mysqli_fetch_assoc($sql2);
  
  ?>

<!DOCTYPE html>
<html>
<div class = "container">
<h1>Attach A Person to <?php echo $name['native_name'];?></h1>
<h3>People Library</h3>
<form name="add_person" id="add_person" class="add_person" method="post" action="add_people.php">
  <table id="info" cellpadding="0" cellspacing="0" border="0"
      class="datatable table table-striped table-bordered datatable-style table-hover"
      width="100%" style="width: 100px;">
              <thead>
              <tr id="table-first-row">
                <th>Select</th>
              <th scope="col">ID</th>
              <th scope="col">Stage Name</th>
              <th scope="col">Add Role</th>
              <th scope="col">Add Screen Name</th>
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
                  <option value="Lead Actor">Lead Actor</option>
                  <option value="Lead Actress">Lead Actress</option>
                  <option value="Supporting Actor">Supporting Actor</option>
                  <option value="Supporting Actress">Supporting Actress</option>
                  <option value="Producer">Producer</option>
                  <option value="Director">Director</option>
                  <option value="Music Director">Music Director</option>
                  <option value="Other">Other</option>
              </select>
          </td>
          <td><input type="text" class="add" id="add" name="screen_name<?php echo $count; ?>" placeholder="Add Screen Name" disabled></td>

            </tr>

            <?php
            $count++;
                }
                  }
            ?>

            </tbody>
            </table>
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>" />
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
    <h3>People for <?php echo $name['native_name'];?></h3>
    <form name="delete_person" id="delete_person" method="post" action="add_people.php">
      <table id="info1" cellpadding="0" cellspacing="0" border="0"
          class="datatable table table-striped table-bordered datatable-style table-hover"
          width="100%" style="width: 100px;">
                  <thead>
                  <tr id="table-first-row">
                    <th>Select</th>
                  <th scope="col">ID</th>
                  <th scope="col">Stage Name</th>
                  <th scope="col">Screen Name</th>
                  <th scope="col">Role</th>
                  </tr>
                  </thead>
                  <tbody>
    <?php
        while($row = mysqli_fetch_assoc($sql)){
    ?>
            <tr>
            <td><input type="checkbox" class="delete" name="people_id<?php echo $count; ?>" value="<?php echo $row['people_id']; ?>" onclick="this.nextSibling.nextSibling.disabled = false; this.nextSibling.nextSibling.nextSibling.nextSibling.disabled = false;" autofocus>
            <input type="hidden" name="screen_name<?php echo $count;?>" value="<?php echo $row['screen_name']; ?>" disabled>
            <input type="hidden" name="role<?php echo $count;?>" value="<?php echo $row['role']; ?>" disabled>
            <input type="hidden" name="test" value="test"></td>
            <td><?php echo $row['people_id']; ?></td>
            <td><?php echo $row['stage_name']; ?></td>
            <td><?php echo $row['screen_name']; ?></td>
            <td><?php echo $row['role']; ?></td>

            </tr>
            
            
    <?php
    $count++;
        }
    ?>
            </tbody>
            </table>
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>" />
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
    var next2 = document.getElementsByName(source.name)[0].parentElement.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.nextSibling.children[0];
    if(original.checked == true){
        original.required = true;
        next.required = true;
        next.disabled = false;
        next2.disabled = false;
    } else {
        original.required = false;
        next.required = false;
        next.disabled = true;
        next2.disabled = true;
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
                items[i].nextSibling.nextSibling.nextSibling.nextSibling.disabled = false;
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
