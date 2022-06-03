<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "YES";
    $left_selected = "SEARCH";
  
     include("./nav.php");


          if(isset($_POST['search'])){
            $role = $_POST['role'];
            $people_id11 = $_POST['people'];
          }


  ?>

<!DOCTYPE html>
<html>
<h1>Search Movies of People</h1>
<form method="post">
      <div class="form-group">
            <label for="exampleFormControlSelect1">Select Role</label>
            <select class="form-control" name="role" id="exampleFormControlSelect1">

             <option value="lead_actor">Lead Actor</option>
             <option value="lead_actress">Lead Actress</option>
             <option value="supporting_actor">Supporting Actor</option>
             <option value="supporting_actress">Supporting Actress</option>
             <option value="producer">Producer</option>
             <option value="director">Director</option>
             <option value="music_director">Music Director</option>

            </select>

            <label for="exampleFormControlSelect1">Select People</label>
            <select class="form-control" name="people" id="exampleFormControlSelect1">

             <?php
             $query = mysqli_query($db, "select * from people");
             if(mysqli_num_rows($query)>0){
               while($row = mysqli_fetch_assoc($query)){
                 $people_id = $row['people_id'];
                 $name = $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];
                 ?>

                  <option value="<?php echo $people_id; ?>"><?php echo $name; ?></option>
                <?php }
             }

             ?>

            </select>


            </div>


<button type="submit" name="search" class="btn btn-primary">Submit</button>
</form>
    <hr/>
            <h2>Movies</h2>

                          <table class="table">
                          <thead>
                          <tr>
                          <th scope="col">#</th>
                          <th scope="col">People Full Name</th>
                          <th scope="col">Movie's native Name</th>
                          <th scope="col">Movie's English Name</th>
                          <th scope="col">Year Made</th>
                          <th scope="col">Role</th>

                          </tr>
                          </thead>
                          <tbody>


              <?php
              if(!empty($role)){
              $id11 = $people_id11;
              $count = '1';
              $que = "select movies.native_name, movies.english_name, movies.year_made, people.first_name, people.middle_name, people.last_name,people.gender,movie_people.role
              from movie_people
              LEFT JOIN movies
              ON movie_people.movie_id = movies.movie_id
              LEFT JOIN people
              ON movie_people.people_id = people.people_id
              where movie_people.people_id = '$id11'
              AND movie_people.role = '$role'";

               $query = mysqli_query($db,$que);
              $num = mysqli_num_rows($query);
              if($num>0){
                while($row = mysqli_fetch_assoc($query))
                {
                  $name = $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];
                  $song_title = $row['native_name'];
                  $song_lyrics = $row['english_name'];
                  $song_theme = $row['year_made'];
                  $role = $row['role'];

              ?>


                        <tr>

                        <th scope="row"><?php echo $count; ?></th>
                        <td><?php echo $name; ?></td>
                        <td><?php echo $song_title; ?></td>
                        <td><?php echo $song_lyrics; ?></td>
                        <td><?php echo $song_theme; ?></td>
                        <td><?php echo $role; ?></td>


                      </tr>
                        <?php
                        $count++;
                      }
                      }
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
