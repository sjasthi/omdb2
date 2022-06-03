<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";

     include("./nav.php");
  ?>

<!DOCTYPE html>
<html>
<h1>Search Lists</h1>




                  <h2>Movies Table</h2>
                  <table class="table">
                  <thead>
                  <tr>
                  <th scope="col">#</th>
                  <th scope="col">Native name</th>
                  <th scope="col">English name</th>
                  <th scope="col">Year made</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php
                    $name = $_POST['text_search'];
                    $count = 0;
                    $query = mysqli_query($db, "SELECT * FROM `movies` WHERE native_name LIKE '%".$name."%' OR english_name LIKE '%".$name."%'");
                    if(mysqli_num_rows($query)>0){

                      while($movies = mysqli_fetch_assoc($query)){
                        $count++;
                        ?>


            <tr>

            <th scope="row"><?php echo $count; ?></th>
            <td><?php echo $movies['native_name']; ?></td>
            <td><?php echo $movies['english_name'] ?></td>
            <td><?php echo $movies['year_made'] ?></td>
            </tr>

            <?php
            $count++;
          }
        }
        else{
          echo "<td><h4>No Matches</h4></td>";
        }
          ?>
        </tbody>
        </table>
        <hr/>




            <h2>People Table</h2>
            <table class="table">
            <thead>
            <tr>
            <th scope="col">#</th>
            <th scope="col">Stage name</th>
            <th scope="col">First name</th>
            <th scope="col">Middle name</th>
            <th scope="col">Last name</th>
            <th scope="col">Gender</th>
            </tr>
            </thead>
            <tbody>



          <?php
          $name1 = $_POST['text_search'];
          $count1 = 0;
          $query1 = mysqli_query($db, "SELECT * FROM `people` WHERE stage_name LIKE '%".$name1."%' OR first_name LIKE '%".$name1."%'
          OR middle_name LIKE '%".$name1."%' OR last_name LIKE '%".$name1."%' OR gender LIKE '%".$name1."%'");
          if(mysqli_num_rows($query1)>0){
           while($people = mysqli_fetch_assoc($query1)){
              $count1++;


            ?>


          <tr>

          <th scope="row"><?php echo $count1; ?></th>
          <td><?php echo $people['stage_name']; ?></td>
          <td><?php echo $people['first_name'] ?></td>
          <td><?php echo $people['middle_name'] ?></td>
          <td><?php echo $people['last_name'] ?></td>
          <td><?php echo $people['gender'] ?></td>
          </tr>

          <?php
        }
      }
      else{
        echo "<td><h4>No Matches</h4></td>";
      }
      ?>
      </tbody>
      </table>
      <hr/>


          <h2>Songs Table</h2>
          <table class="table">
          <thead>
          <tr>
            <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Lyrics</th>
          <th scope="col">Theme</th>
          </tr>
          </thead>
          <tbody>



        <?php
        $name2 = $_POST['text_search'];
        $count2 = 0;
        $query2 = mysqli_query($db, "SELECT * FROM `songs` WHERE title LIKE '%".$name2."%'
        OR lyrics LIKE '%".$name2."%' OR theme LIKE '%".$name2."%'");
        if(mysqli_num_rows($query2)>0){
          while($songs = mysqli_fetch_assoc($query2)){
            $count2++;


          ?>


        <tr>

        <th scope="row"><?php echo $count2; ?></th>
        <td><?php echo $songs['title']; ?></td>
        <td><?php echo $songs['lyrics'] ?></td>
        <td><?php echo $songs['theme'] ?></td>
      </tr>
    <?php }
  }
  else{
    echo "<td><h4>No Matches</h4></td>";
  }
   ?>
    </tbody>
    </table>






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
    db_disconnect($db);
    include("./footer.php");
?>
</html>
