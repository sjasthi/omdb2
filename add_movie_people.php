<?php $page_title = 'The Cow Layer'; ?>
<script type="text/javascript" language="javascript">

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

</script>

<?php
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";

    include("./nav.php");
    $movie_id = '';
    if(isset($_GET['movie_id'])){
        $movie_id = $_GET['movie_id'];
    }

    if(isset($_POST['add'])){
        $movie_id = $_POST['movie_id'];
        $people_id = [];
        $roles = [];
        foreach($_POST as $k => $v) {
            if(strpos($k, 'people_id') === 0) {
                $song_id[] = $v;
            }
            if(strpos($k, 'role') === 0) {
                $roles[] = $v;
            }
        }
        for($i = 0, $size = count($people_id); $i < $size; $i++){
            $sql = "insert ignore into movie_people (movie_id, people_id, role) values (".$movie_id.", ".$people_id[$i].", '".$roles[$i]."');";
            mysqli_query($db, $sql);
        }
    }
    else if(isset($_POST['delete'])){
        $movie_id = $_POST['movie_id'];
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
            $sql = "delete from movie_people where movie_id = ".$movie_id." and song_id = ".$song." and role = '".$roles[$i]."';";
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
<form name="add_person" id="add_person" class="add_person" method="post" action="add_movie_people.php">
  <table id="info" cellpadding="0" cellspacing="0" border="0"
      class="datatable table table-striped table-bordered datatable-style table-hover"
      width="100%" style="width: 100px;">
              <thead>
              <tr id="table-first-row">
                <th>Select</th>
              <th scope="col">ID</th>
              <th scope="col">Stage Name</th>
              <th scope="col">First Name</th>
              <th scope="col">Middle Name</th>
              <th scope="col">Last Name</th>
              <th scope="col">Gender</th>
              <th scope="col">Image Name</th>
              </tr>
              </thead>
              <tbody>

  <?php

  $count = '1';
      $query = mysqli_query($db,"SELECT * from people;");


    if(mysqli_num_rows($query)>0){
      while($row = mysqli_fetch_assoc($query)){


  ?>


            <tr>
            <td><input type="checkbox" class="add" id="add" name="people_id<?php echo $count; ?>" value="<?php echo $row['people_id']; ?>"></td>
            <td><?php echo $row['people_id']; ?></td>
            <td><?php echo $row['stage_name']; ?></td>
            <td><?php echo $row['first_name']; ?></td>
            <td><?php echo $row['middle_name']; ?></td>
            <td><?php echo $row['last_name']; ?></td>
            <td><?php echo $row['gender']; ?></td>
            <td><?php echo $row['image_name']; ?></td>

            </tr>

            <?php
            $count++;
                }
                  }
            ?>

            </tbody>
            </table>
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>" />
            <input type="checkbox" name="add" value="add" style="display:none;" checked>
            </form>
            
              <button type="button" name="check" id="check" onClick="selectAll()">Check/Uncheck All</button>
              <button form="add_person" type="submit" style="margin-left:0px;">Add</button>
        </form>

<?php
if(mysqli_num_rows($sql)>0){
    $count = '1';
?>
    <br><br><br><br>
    <h3>People for <?php echo $name['native_name'];?></h3>
    <form name="delete_person" id="delete_person" method="post" action="add_movie_people.php">
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
            <td><input type="checkbox" class="delete" name="people_id<?php echo $count; ?>" value="<?php echo $row['people_id']; ?>"></td>
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
            <input type="checkbox" name="delete" value="delete" style="display:none;" checked>
            </form>

              <button type="button" name="check" id="check" onClick="deleteAll()">Check/Uncheck All</button>
              <button form="delete_person" type="submit" style="margin-left:0px;">Delete</button>
            </form>
    
<?php
    }
?>

</div>
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
      
    
      $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    
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

<?php
db_disconnect($db);
include("./footer.php");
?>

