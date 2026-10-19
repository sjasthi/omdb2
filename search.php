<?php
require_once __DIR__ . '/security.php';
?>
<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "LIST";
    $left_buttons = "NO";
    $left_selected = "";

     include("./nav.php");
  ?>



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
                   $name = trim($_POST['text_search'] ?? '');
$count = 0;

$search_term = '%' . $name . '%';

$stmt = db_prepare($db, "SELECT * FROM movies
     WHERE native_name LIKE ?
        OR english_name LIKE ?");

mysqli_stmt_bind_param($stmt, "ss", $search_term, $search_term);
mysqli_stmt_execute($stmt);

$query = mysqli_stmt_get_result($stmt);
                    if(mysqli_num_rows($query)>0){

                      while($movies = mysqli_fetch_assoc($query)){
                        $count++;
                        ?>


            <tr>

            <th scope="row"><?php echo h($count); ?></th>

<td>
    <a href="movie_info.php?movie_id=<?php echo h(urlencode($movies['movie_id'])); ?>">
        <?php echo h($movies['native_name']); ?>
    </a>
</td>

<td>
    <a href="movie_info.php?movie_id=<?php echo h(urlencode($movies['movie_id'])); ?>">
        <?php echo h($movies['english_name']); ?>
    </a>
</td>

<td><?php echo h($movies['year_made']); ?></td>
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
          $name1 = trim($_POST['text_search'] ?? '');
$count1 = 0;

$people_search_term = '%' . $name1 . '%';

$stmt_people = db_prepare($db, "SELECT * FROM people
     WHERE stage_name LIKE ?
        OR first_name LIKE ?
        OR middle_name LIKE ?
        OR last_name LIKE ?
        OR gender LIKE ?");

mysqli_stmt_bind_param(
    $stmt_people,
    "sssss",
    $people_search_term,
    $people_search_term,
    $people_search_term,
    $people_search_term,
    $people_search_term
);

mysqli_stmt_execute($stmt_people);

$query1 = mysqli_stmt_get_result($stmt_people);
          if(mysqli_num_rows($query1)>0){
           while($people = mysqli_fetch_assoc($query1)){
              $count1++;


            ?>


          <tr>

          <th scope="row"><?php echo h($count1); ?></th>
          <td>
    <a href="people_info.php?people_id=<?php echo h(urlencode($people['people_id'])); ?>">
        <?php echo h($people['stage_name']); ?>
    </a>
</td>
          <td><?php echo h($people['first_name']); ?></td>
<td><?php echo h($people['middle_name']); ?></td>
<td><?php echo h($people['last_name']); ?></td>
<td><?php echo h($people['gender']); ?></td>
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
        $name2 = trim($_POST['text_search'] ?? '');
$count2 = 0;

$song_search_term = '%' . $name2 . '%';

$stmt_songs = db_prepare($db, "SELECT * FROM songs
     WHERE title LIKE ?
        OR lyrics LIKE ?
        OR theme LIKE ?");

mysqli_stmt_bind_param(
    $stmt_songs,
    "sss",
    $song_search_term,
    $song_search_term,
    $song_search_term
);

mysqli_stmt_execute($stmt_songs);

$query2 = mysqli_stmt_get_result($stmt_songs);
        if(mysqli_num_rows($query2)>0){
          while($songs = mysqli_fetch_assoc($query2)){
            $count2++;


          ?>


        <tr>
    <th scope="row"><?php echo h($count2); ?></th>

    <td>
        <a href="song_info.php?song_id=<?php echo h(urlencode($songs['song_id'])); ?>">
            <?php echo h($songs['title']); ?>
        </a>
    </td>

    <td><?php echo h($songs['lyrics']); ?></td>
    <td><?php echo h($songs['theme']); ?></td>
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
