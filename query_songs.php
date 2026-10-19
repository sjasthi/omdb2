<?php
require_once __DIR__ . '/security.php';
?>
<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "SONGS";
    $left_buttons = "YES";
    $left_selected = "SEARCH";
     include("./nav.php");


          if(isset($_POST['search'])){
            $role = $_POST['role'];
            $people = $_POST['people'];

          }


  ?>



<h1>Search Song of People</h1>
<form method="post"><?php echo csrf_field(); ?>
      <div class="form-group">
            <label for="exampleFormControlSelect1">Select Role</label>
            <select class="form-control" name="role" id="exampleFormControlSelect1">
             <option value="music_director">Music Director</option>
             <option value="lyricist">Lyricist</option>
             <option value="singer">Singer</option>

            </select>
            </div>


            <div class="form-group">
                  <label for="exampleFormControlSelect1">Select People</label>
                  <select class="form-control" name="people" id="exampleFormControlSelect1">
                <?php
                $query = db_query($db, "select * from people");
                if(mysqli_num_rows($query)>0){
                  while($row = mysqli_fetch_assoc($query)){
                    $people_id = $row['people_id'];
                    $name = $row['first_name'].' '.$row['middle_name'].''.$row['last_name'];
                    ?>

                     <option value="<?php echo h($people_id); ?>"><?php echo h($name); ?></option>
                   <?php }
                }


                ?>

                  </select>
                  </div>


<button type="submit" name="search" class="btn btn-primary">Submit</button>
</form>
    <hr/>
            <h2>Songs</h2>

                          <table class="table">
                          <thead>
                          <tr>
                          <th scope="col">#</th>
                          <th scope="col">People Full Name</th>
                          <th scope="col">Song Title</th>
                          <th scope="col">Song Lyrics</th>
                          <th scope="col">Song Theme</th>
                          <th scope="col">Role</th>

                          </tr>
                          </thead>
                          <tbody>


              <?php
              if(!empty($role)){
              $id = $people;
              $count = '1';
              $que = ['sql' => 'select songs.title,songs.lyrics, songs.theme, people.first_name, people.middle_name, people.last_name, people.gender,song_people.role
               from song_people
               LEFT JOIN people
               ON song_people.people_id = people.people_id
               LEFT JOIN songs
               ON song_people.song_id = songs.song_id

               where song_people.people_id = ?
               AND
               song_people.role = ?', 'params' => [$id, $role]];
               // print_r($que);
               // exit();
               $query = db_query($db, $que);
              $num = mysqli_num_rows($query);
              if($num>0){
                while($row = mysqli_fetch_assoc($query))
                {
                  $name = $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];
                  $song_title = $row['title'];
                  $song_lyrics = $row['lyrics'];
                  $song_theme = $row['theme'];
                  $role = $row['role'];

              ?>


                        <tr>

                        <th scope="row"><?php echo h($count); ?></th>
                        <td><?php echo h($name); ?></td>
                        <td><?php echo h($song_title); ?></td>
                        <td><?php echo h($song_lyrics); ?></td>
                        <td><?php echo h($song_theme); ?></td>
                        <td><?php echo h($role); ?></td>


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
