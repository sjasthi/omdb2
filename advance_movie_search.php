<?php
  $nav_selected = "search_data";
  $left_buttons = "YES";
  $left_selected = "search_data";
  include("./nav.php");
  if(isset($_POST['song_search_button'])){
  $name1 = $_POST['song_search'];
  }

  if(isset($_POST['movie_search_button'])){
  $name2 = $_POST['movie_search'];
  }

  if(isset($_POST['people_search_button'])){
  $name3 = $_POST['people_search'];
  }
  if(isset($_POST['movie_people_search_button'])){
    $lead_actor = $_POST['lead_actor'];
    $lead_actress = $_POST['lead_actress'];
    $producer = $_POST['producer'];
    $director = $_POST['director'];
    $music_director = $_POST['music_director'];


}
  ?>

<div class="right-content">
    <div class="container">

    <br><br>

      <?php
      if(isset($_POST['songs'])){

        ?>
        <h3 style = "color: #01B0F1;">Search Songs</h3>


      <form method="post" action="advance_song_search.php">

        <div class="form-group">
          <label for="song_search">Search Songs</label>
          <input type="text" class="form-control" id="song_search" name="song_search" placeholder="Enter song to search its record...">
        </div>

          <div class="form-group">
            <label for="lyricist">Lyricist</label>
            <input type="text" class="form-control" id="lyricist" name="lyricist" placeholder="lyricist Name">
          </div>

          <div class="form-group">
            <label for="playback_singer">Playback Singer</label>
            <input type="text" class="form-control" id="playback_singer" name="playback_singer" placeholder="Playback Singer Name">
          </div>

          <div class="form-group">
            <label for="music_director">Music Director</label>
            <input type="text" class="form-control" id="music_director" name="music_director" placeholder="Music Director Name">
          </div>

        <div class="form-group">
          <label for="leading_actor">Leading Actor</label>
          <input type="text" class="form-control" id="leading_actor" name="leading_actor" placeholder="Leading Actor Name">
        </div>

        <div class="form-group">
          <label for="leading_actress">Leading Actress</label>
          <input type="text" class="form-control" id="leading_actress" name="leading_actress" placeholder="Leading Actress Name">
        </div>

        <div class="form-group">
          <label for="director">Director</label>
          <input type="text" class="form-control" id="director" name="director" placeholder="director Name">
        </div>

        <div class="form-group">
          <label for="producer">Producer</label>
          <input type="text" class="form-control" id="producer" name="producer" placeholder="producer Name">
        </div>


        <button type="submit"  name="song_people_search_button" class="btn btn-primary">Search</button>
      </form>


      <?php }
      if(!isset($_POST['songs']) AND !isset($_POST['people'])){ ?>
          <!-- <h3 style = "color: #01B0F1;">Search Movies</h3>
        <form method="post" action="search_data.php">
          <input type="text" name="movie_search" placeholder="Enter movie to search its record...">
          <button type="submit" name="movie_search_button"><i class="fa fa-search" aria-hidden="true"></i></button>
        </form>
        <hr/> -->
        <h3 style = "color: #01B0F1;">Search movies by role</h3>






        <form method="post" action="advance_movie_search.php">

          <div class="form-group">
            <label for="movie_search">Search Movie</label>
            <input type="text" class="form-control" id="movie_search" name="movie_search" placeholder="Enter movie to search its record...">
          </div>


        <div class="form-group">
          <label for="lead_actor">Lead Actor</label>
          <input type="text" class="form-control" id="lead_actor" name="lead_actor" placeholder="Lead Actor Name">
        </div>

        <div class="form-group">
          <label for="lead_actress">Lead Actress</label>
          <input type="text" class="form-control" id="lead_actress" name="lead_actress" placeholder="Lead Actress Name">
        </div>

        <div class="form-group">
          <label for="producer">Producer</label>
          <input type="text" class="form-control" id="producer" name="producer" placeholder="producer Name">
        </div>

        <div class="form-group">
          <label for="director">Director</label>
          <input type="text" class="form-control" id="director" name="director" placeholder="director Name">
        </div>

        <div class="form-group">
          <label for="music_director">Music Director</label>
          <input type="text" class="form-control" id="music_director" name="music_director" placeholder="Music Director Name">
        </div>



        <button type="submit"  name="movie_people_search_button" class="btn btn-primary">Search</button>
      </form>



<?php }
if(isset($_POST['people'])){ ?>
  <h3 style = "color: #01B0F1;">Search Peoples</h3>


  <form method="post" action="advance_people_search.php">

    <div class="form-group">
      <label for="search_people">Search People</label>
    <input type="text" id="search_people" class="form-control" name="people_search" placeholder="Enter People to search its record...">
  </div>
  <div class="form-group">
    <label for="stage_name">Stage Name</label>

    <select name="stage_name" id='selUser' class="form-control">
      <?php
      $query = mysqli_query($db,"select * from people");
      while($options = mysqli_fetch_assoc($query)){ ?>
        <option value="<?php echo $options['stage_name']?>"><?php echo $options['stage_name'];?></option>

      <?php }
      ?>
    </select>
  </div>

  <div class="form-group">
    <label for="role">Role</label>
    <select name="role" class="form-control">
      <option value="" disabled>Select One</option>
      <option value="lead_actor">Lead Actor</option>
      <option value="lead_actress">Lead Actress</option>
      <option value="producer">Producer</option>
      <option value="director">Director</option>
      <option value="music_director">Music Director</option>
    </select>
  </div>


  <button type="submit"  name="search" class="btn btn-primary">Search</button>
</form>
<?php } ?>



<!-- Lead actor starts here-->

          <?php

          if(!empty($_POST['movie_search'])){
            $name2 = $_POST['movie_search'];
            ?>



              <h3 style = "color: #01B0F1;">Movies Result</h3>
            <table class="datatable table table-striped table-bordered datatable-style table-hover"
            id="info" cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr id="table-first-row">
            <th scope="col">#</th>
            <th scope="col">Native Name</th>
            <th scope="col">English Name</th>
            <th scope="col">Year made</th>
            </tr>
            </thead>
            <tbody>


              <?php
              $count = 0;
              $query = mysqli_query($db, "SELECT * FROM `movies` WHERE native_name LIKE '%".$name2."%'
                OR english_name LIKE '%".$name2."%'
                OR year_made LIKE '%".$name2."%'
              ");
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
              ?>
              <hr/>
          <?php  }
          else{
          echo "<td><h4>No Matches</h4></td>";
          }
          ?>

          </tbody>
          </table>


            <h3 style = "color: #01B0F1;">Movie People</h3>
            <table class="datatable table table-striped table-bordered datatable-style table-hover"
            id="info1" cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr id="table-first-row">
            <th scope="col">#</th>
            <th scope="col">People Full name</th>
            <th scope="col">Role</th>
            <th scope="col">Movies native name</th>
            <th scope="col">Movie's English name</th>
            <th scope="col">Year made</th>
          </tr>
            </thead>
            <tbody>


            <?php
            $count1 = 0;
            $query1 = mysqli_query($db, "select people.stage_name, people.first_name,people.middle_name, people.last_name,movie_people.role,movies.native_name, movies.english_name,
            movies.year_made
              from movie_people
              join movies on movies.movie_id =movie_people.movie_id
              join people on people.people_id = movie_people.people_id
              where movies.native_name LIKE '%".$name2."%'
              OR movies.english_name LIKE '%".$name2."%'
              OR movies.year_made LIKE '%".$name2."%'
              ");
            if(mysqli_num_rows($query1)>0){
            while($movie1 = mysqli_fetch_assoc($query1)){
            $count1++;
            ?>


        <tr>

        <th scope="row"><?php echo $count1; ?></th>
        <td><?php echo $movie1['first_name']." ".$movie1['middle_name']." ".$movie1['last_name']; ?></td>
        <td><?php echo $movie1['role'] ?></td>
        <td><?php echo $movie1['native_name'] ?></td>
        <td><?php echo $movie1['english_name']; ?></td>
        <td><?php echo $movie1['year_made'] ?></td>
        </tr>

        <?php
      } ?> <hr/>
          <?php }
          else{
            echo "<td><h4>No Matches</h4></td>";
          }

        ?>

        </tbody>
        </table>


              <h3 style = "color: #01B0F1;">Movie Song</h3>
              <table class="datatable table table-striped table-bordered datatable-style table-hover"
              id="info2" cellpadding="0" cellspacing="0" border="0">
              <thead>
              <tr id="table-first-row">
              <th scope="col">#</th>
              <th scope="col">Song Title</th>
              <th scope="col">Song Lyrics</th>
              <th scope="col">Song Theme</th>
              <th scope="col">Native Name</th>
              <th scope="col">English name</th>
              <th scope="col">Year made</th>
              </tr>
              </thead>
              <tbody>


              <?php
              $count2 = 0;
              $query2 = mysqli_query($db, "select songs.song_id, songs.title,songs.lyrics, songs.theme,movie_song.movie_id, movies.native_name,
              movies.english_name, movies.year_made
                from movie_song
                join songs on songs.song_id =movie_song.song_id
                join movies on movies.movie_id = movie_song.movie_id
                where movies.native_name LIKE '%".$name2."%'
                OR movies.english_name LIKE '%".$name2."%'
                OR movies.year_made LIKE '%".$name2."%'
              ");

              if(mysqli_num_rows($query2)>0){
              while($movie2 = mysqli_fetch_assoc($query2)){
              $count2++;
              ?>


            <tr>

            <th scope="row"><?php echo $count2; ?></th>
            <td><?php echo $movie2['title']; ?></td>
            <td><?php echo $movie2['lyrics'] ?></td>
            <td><?php echo $movie2['theme'] ?></td>
            <td><?php echo $movie2['native_name']; ?></td>
            <td><?php echo $movie2['english_name']; ?></td>
            <td><?php echo $movie2['year_made'] ?></td>
            </tr>

            <?php
          } ?>
              <hr/>
            <?php }
            else{
              echo "<td><h4>No Matches</h4></td>";
            }

            ?>

            </tbody>
            </table>



        <?php


          }
          else{
          if(!empty($lead_actor) AND empty($lead_actress) AND empty($producer) AND empty($director)
        AND empty($music_director)){
            ?>
            <h3 style = "color: #01B0F1;">Search Result</h3>
            <?php
            $query =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
            people.first_name,people.middle_name,people.last_name
            from movie_people
            JOIN people ON people.people_id = movie_people.people_id
            JOIN movies ON movies.movie_id = movie_people.movie_id
            WHERE movie_people.role='lead_actor'
            AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
            OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name LIKE '%".$lead_actor."%')");

            if(mysqli_num_rows($query)>0){
            $count ='0';
            ?>
            <table class="datatable table table-striped table-bordered datatable-style table-hover"
            id="info" cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr id="table-first-row">
            <th scope="col">#</th>
            <th scope="col">People Name</th>
            <th scope="col">Movie's Native Name</th>
            <th scope="col">Movie's English Name</th>
            <th scope="col">Movie's Year Made</th>
            <th scope="col">Role</th>
            </tr>
            </thead>
            <tbody>
              <?php
              while($row = mysqli_fetch_assoc($query)){
                $count++;
              ?>
              <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];?></td>
                <td><?php echo $row['native_name']; ?></td>
                <td><?php echo $row['english_name']; ?></td>
                <td><?php echo $row['year_made']; ?></td>
                <td><?php echo $row['role']; ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>

        <?php }

      else{
        echo '<h3>No Matches</h3>';
      }
    } ?>

<!-- Lead Actor Ends here -->
<!-- Lead Actress Starts here -->

        <?php
        if(empty($lead_actor) AND !empty($lead_actress) AND empty($producer) AND empty($director)
      AND empty($music_director)){
          ?>
          <h3 style = "color: #01B0F1;">Search Result</h3>
          <?php
          $query =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
          people.first_name,people.middle_name,people.last_name
          from movie_people
          JOIN people ON people.people_id = movie_people.people_id
          JOIN movies ON movies.movie_id = movie_people.movie_id
          WHERE movie_people.role='lead_actress'
          AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
          OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name LIKE '%".$lead_actress."%')");
          if(mysqli_num_rows($query)>0){
          $count ='0';
          ?>
          <table class="datatable table table-striped table-bordered datatable-style table-hover"
          id="info" cellpadding="0" cellspacing="0" border="0">
          <thead>
          <tr id="table-first-row">
          <th scope="col">#</th>
          <th scope="col">People Name</th>
          <th scope="col">Movie's Native Name</th>
          <th scope="col">Movie's English Name</th>
          <th scope="col">Movie's Year Made</th>
          <th scope="col">Role</th>
          </tr>
          </thead>
          <tbody>
            <?php
            while($row = mysqli_fetch_assoc($query)){
              $count++;
            ?>
            <tr>
              <td><?php echo $count; ?></td>
              <td><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];?></td>
              <td><?php echo $row['native_name']; ?></td>
              <td><?php echo $row['english_name']; ?></td>
              <td><?php echo $row['year_made']; ?></td>
              <td><?php echo $row['role']; ?></td>
            </tr>
          <?php } ?>
          </tbody>
        </table>

      <?php }

    else{
      echo '<h3>No Matches</h3>';
    }

  }  ?>
<!-- Lead Actress Ends here -->

<!-- Producers Starts here -->

          <?php
          if(empty($lead_actor) AND empty($lead_actress) AND !empty($producer) AND empty($director)
          AND empty($music_director)){
            ?>
            <h3 style = "color: #01B0F1;">Search Result</h3>
            <?php
            $query =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
            people.first_name,people.middle_name,people.last_name
            from movie_people
            JOIN people ON people.people_id = movie_people.people_id
            JOIN movies ON movies.movie_id = movie_people.movie_id
            WHERE movie_people.role='producer'
            AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
            OR people.last_name LIKE '%".$producer."%' OR people.stage_name LIKE '%".$producer."%')");
            if(mysqli_num_rows($query)>0){
            $count ='0';
            ?>
            <table class="datatable table table-striped table-bordered datatable-style table-hover"
            id="info" cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr id="table-first-row">
            <th scope="col">#</th>
            <th scope="col">People Name</th>
            <th scope="col">Movie's Native Name</th>
            <th scope="col">Movie's English Name</th>
            <th scope="col">Movie's Year Made</th>
            <th scope="col">Role</th>
            </tr>
            </thead>
            <tbody>
              <?php
              while($row = mysqli_fetch_assoc($query)){
                $count++;
              ?>
              <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];?></td>
                <td><?php echo $row['native_name']; ?></td>
                <td><?php echo $row['english_name']; ?></td>
                <td><?php echo $row['year_made']; ?></td>
                <td><?php echo $row['role']; ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>

          <?php }

        else{
          echo '<h3>No Matches</h3>';
        }
      }
        ?>
<!-- Producers Ends here -->

<!-- Director's Starts here -->

            <?php
            if(empty($lead_actor) AND empty($lead_actress) AND empty($producer) AND !empty($director)
            AND empty($music_director)){
              ?>
              <h3 style = "color: #01B0F1;">Search Result</h3>
              <?php
              $query =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
              people.first_name,people.middle_name,people.last_name
              from movie_people
              JOIN people ON people.people_id = movie_people.people_id
              JOIN movies ON movies.movie_id = movie_people.movie_id
              WHERE movie_people.role='director'
              AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
              OR people.last_name LIKE '%".$director."%' OR people.stage_name LIKE '%".$director."%')");
              if(mysqli_num_rows($query)>0){
              $count ='0';
              ?>
              <table class="datatable table table-striped table-bordered datatable-style table-hover"
              id="info" cellpadding="0" cellspacing="0" border="0">
              <thead>
              <tr id="table-first-row">
              <th scope="col">#</th>
              <th scope="col">People Name</th>
              <th scope="col">Movie's Native Name</th>
              <th scope="col">Movie's English Name</th>
              <th scope="col">Movie's Year Made</th>
              <th scope="col">Role</th>
              </tr>
              </thead>
              <tbody>
                <?php
                while($row = mysqli_fetch_assoc($query)){
                  $count++;
                ?>
                <tr>
                  <td><?php echo $count; ?></td>
                  <td><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];?></td>
                  <td><?php echo $row['native_name']; ?></td>
                  <td><?php echo $row['english_name']; ?></td>
                  <td><?php echo $row['year_made']; ?></td>
                  <td><?php echo $row['role']; ?></td>
                </tr>
              <?php } ?>
              </tbody>
            </table>

            <?php }
            else{
              echo '<h3>No Matches</h3>';
            }
          }

           ?>

<!-- Director's End here -->
<!-- Music Director's Starts here -->
              <?php
              if(empty($lead_actor) AND empty($lead_actress) AND empty($producer) AND empty($director)
              AND !empty($music_director)){
                ?>
                <h3 style = "color: #01B0F1;">Search Result</h3>
                <?php
                $query =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
                people.first_name,people.middle_name,people.last_name
                from movie_people
                JOIN people ON people.people_id = movie_people.people_id
                JOIN movies ON movies.movie_id = movie_people.movie_id
                WHERE movie_people.role='music_director'
                AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
                OR people.last_name LIKE '%".$music_director."%' OR people.stage_name LIKE '%".$music_director."%')");
                if(mysqli_num_rows($query)>0){
                $count ='0';
                ?>
                <table class="datatable table table-striped table-bordered datatable-style table-hover"
                id="info" cellpadding="0" cellspacing="0" border="0">
                <thead>
                <tr id="table-first-row">
                <th scope="col">#</th>
                <th scope="col">People Name</th>
                <th scope="col">Movie's Native Name</th>
                <th scope="col">Movie's English Name</th>
                <th scope="col">Movie's Year Made</th>
                <th scope="col">Role</th>
                </tr>
                </thead>
                <tbody>
                  <?php
                  while($row = mysqli_fetch_assoc($query)){
                    $count++;
                  ?>
                  <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row['first_name'].' '.$row['middle_name'].' '.$row['last_name'];?></td>
                    <td><?php echo $row['native_name']; ?></td>
                    <td><?php echo $row['english_name']; ?></td>
                    <td><?php echo $row['year_made']; ?></td>
                    <td><?php echo $row['role']; ?></td>
                  </tr>
                <?php } ?>
                </tbody>
              </table>

              <?php }

            else{
              echo '<h3>No Matches</h3>';
            }
          }
            ?>

<!-- Music Director's End here -->

  <!-- Lead Actor and Lead Actress Starts here -->
  <?php
  if(!empty($lead_actor) AND !empty($lead_actress) AND empty($producer) AND empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php

    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name LIKE '%".$lead_actor."%')");
    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='lead_actress'
      AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
      OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name LIKE '%".$lead_actress."%')");
      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }

}

}

?>
  <!-- Lead Actor and Lead Actress Ends here -->


  <!-- Lead Actor and Producer Starts here -->
  <?php
  if(!empty($lead_actor) AND empty($lead_actress) AND !empty($producer) AND empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='producer'
      AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
      OR people.last_name LIKE '%".$producer."%' OR people.stage_name LIKE '%".$producer."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
}
else{
  echo '<h3>No Matches</h3>';
}
}

?>
  <!-- Lead Actor and Producer Ends here -->


  <!-- Lead Actor and Director Starts here -->
  <?php
  if(!empty($lead_actor) AND empty($lead_actress) AND empty($producer) AND !empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php

    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='director'
      AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
      OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
 ?>
  <!-- Lead Actor and Director Ends here -->




  <!-- Lead Actor and Music Director Starts here -->
  <?php
  if(!empty($lead_actor) AND empty($lead_actress) AND empty($producer) AND empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name  LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='music_director'
      AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
      OR people.last_name LIKE '%".$music_director."%' OR people.stage_name  LIKE '%".$music_director."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actor and Music Director Ends here -->






  <!-- Lead Actress and Producer Starts here -->
  <?php
  if(empty($lead_actor) AND !empty($lead_actress) AND !empty($producer) AND empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actress'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='music_director'
      AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
      OR people.last_name LIKE '%".$producer."%' OR people.stage_name LIKE '%".$producer."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actress and Producer Ends here -->



  <!-- Lead Actress and director Starts here -->
  <?php
  if(empty($lead_actor) AND !empty($lead_actress) AND empty($producer) AND !empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actress'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name  LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='director'
      AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
      OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actress and director Ends here -->

  <!-- Lead Actress and music director Starts here -->
  <?php
  if(empty($lead_actor) AND !empty($lead_actress) AND empty($producer) AND empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actress'
    AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
    OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='music_director'
      AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
      OR people.last_name LIKE '%".$music_director."%' OR people.stage_name  LIKE '%".$music_director."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actress and music director Ends here -->








  <!-- Producer and Director Starts here -->
  <?php
  if(empty($lead_actor) AND empty($lead_actress) AND !empty($producer) AND !empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='producer'
    AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
    OR people.last_name LIKE '%".$producer."%' OR people.stage_name  LIKE '%".$producer."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='director'
      AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
      OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Producer and Director Ends here -->



  <!-- Producer and Music Director Starts here -->
  <?php
  if(empty($lead_actor) AND empty($lead_actress) AND !empty($producer) AND empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='producer'
    AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
    OR people.last_name LIKE '%".$producer."%' OR people.stage_name  LIKE '%".$producer."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='music_director'
      AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
      OR people.last_name LIKE '%".$music_director."%' OR people.stage_name  LIKE '%".$music_director."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Producer and  Music Director Ends here -->



  <!-- Director and Music Director Starts here -->
  <?php
  if(empty($lead_actor) AND empty($lead_actress) AND empty($producer) AND !empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='director'
    AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
    OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='music_director'
      AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
      OR people.last_name LIKE '%".$music_director."%' OR people.stage_name LIKE '%".$music_director."%')");

      if(mysqli_num_rows($query2)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Director and Music Director Ends here -->


  <!-- Lead Actor and Lead Actress and Producer Starts here -->
  <?php
  if(!empty($lead_actor) AND !empty($lead_actress) AND !empty($producer) AND empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name  LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='lead_actress'
      AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
      OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='producer'
        AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
        OR people.last_name LIKE '%".$producer."%' OR people.stage_name  LIKE '%".$producer."%')");
        if(mysqli_num_rows($query3)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actor and Lead Actress and Producer Ends here -->



  <!-- Lead Actor and Lead Actress and Director Starts here -->
  <?php
  if(!empty($lead_actor) AND !empty($lead_actress) AND empty($producer) AND !empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name  LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='lead_actress'
      AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
      OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='director'
        AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
        OR people.last_name LIKE '%".$director."%' OR people.stage_name LIKE '%".$director."%')");
        if(mysqli_num_rows($query3)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actor and Lead Actress and director Ends here -->



  <!-- Lead Actor and Lead Actress and Music Director Starts here -->
  <?php
  if(!empty($lead_actor) AND !empty($lead_actress) AND empty($producer) AND empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name  LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='lead_actress'
      AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
      OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='music_director'
        AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
        OR people.last_name LIKE '%".$music_director."%' OR people.stage_name  LIKE '%".$music_director."%')");
        if(mysqli_num_rows($query3)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actor and Lead Actress and Music Director Ends here -->



  <!-- Lead Actress and Producer and Director Starts here -->
  <?php
  if(empty($lead_actor) AND !empty($lead_actress) AND !empty($producer) AND !empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actress'
    AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
    OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='producer'
      AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
      OR people.last_name LIKE '%".$producer."%' OR people.stage_name  LIKE '%".$producer."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='director'
        AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
        OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");
        if(mysqli_num_rows($query3)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actress and Producer and Director Ends here -->

  <!-- Lead Actress and Producer and Music Director Starts here -->
  <?php
  if(empty($lead_actor) AND !empty($lead_actress) AND !empty($producer) AND empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actress'
    AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
    OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='producer'
      AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
      OR people.last_name LIKE '%".$producer."%' OR people.stage_name  LIKE '%".$producer."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='music_director'
        AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
        OR people.last_name LIKE '%".$music_director."%' OR people.stage_name  LIKE '%".$music_director."%')");
        if(mysqli_num_rows($query3)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actress ,Producer and Music Director Ends here -->



  <!-- Producer, Director and Music Director Starts here -->
  <?php
  if(empty($lead_actor) AND empty($lead_actress) AND !empty($producer) AND !empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='producer'
    AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
    OR people.last_name LIKE '%".$producer."%' OR people.stage_name  LIKE '%".$producer."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='director'
      AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
      OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='music_director'
        AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
        OR people.last_name LIKE '%".$music_director."%' OR people.stage_name  LIKE '%".$music_director."%')");
        if(mysqli_num_rows($query3)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
} ?>
  <!-- Lead Actress ,Producer and Music Director Ends here -->





  <!-- Lead Actor , Lead Actress ,Producer and Director Starts here -->
  <?php
  if(!empty($lead_actor) AND !empty($lead_actress) AND !empty($producer) AND !empty($director)
  AND empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name  LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='lead_actress'
      AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
      OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='producer'
        AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
        OR people.last_name LIKE '%".$producer."%' OR people.stage_name  LIKE '%".$producer."%')");
        if(mysqli_num_rows($query3)>0){
          $query4 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
          people.first_name,people.middle_name,people.last_name
          from movie_people
          JOIN people ON people.people_id = movie_people.people_id
          JOIN movies ON movies.movie_id = movie_people.movie_id
          WHERE movie_people.role='director'
          AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
          OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");
          if(mysqli_num_rows($query4)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>



<?php
while($row4 = mysqli_fetch_assoc($query4)){
  $count++;
?>
<tr>
  <td><?php echo $count; ?></td>
  <td><?php echo $row4['first_name'].' '.$row4['middle_name'].' '.$row4['last_name'];?></td>
  <td><?php echo $row4['native_name']; ?></td>
  <td><?php echo $row4['english_name']; ?></td>
  <td><?php echo $row4['year_made']; ?></td>
  <td><?php echo $row4['role']; ?></td>
</tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
?>
  <!-- Lead Actress ,Producer and Music Director Ends here -->





  <!-- Lead Actor , Producer , Director and Music Director Starts here -->
  <?php
  if(!empty($lead_actor) AND empty($lead_actress) AND !empty($producer) AND !empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name  LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='producer'
      AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
      OR people.last_name LIKE '%".$producer."%' OR people.stage_name LIKE '%".$producer."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='director'
        AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
        OR people.last_name LIKE '%".$director."%' OR people.stage_name  LIKE '%".$director."%')");
        if(mysqli_num_rows($query3)>0){
          $query4 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
          people.first_name,people.middle_name,people.last_name
          from movie_people
          JOIN people ON people.people_id = movie_people.people_id
          JOIN movies ON movies.movie_id = movie_people.movie_id
          WHERE movie_people.role='music_director'
          AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
          OR people.last_name LIKE '%".$music_director."%' OR people.stage_name LIKE '%".$music_director."%')");
          if(mysqli_num_rows($query4)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>



<?php
while($row4 = mysqli_fetch_assoc($query4)){
  $count++;
?>
<tr>
  <td><?php echo $count; ?></td>
  <td><?php echo $row4['first_name'].' '.$row4['middle_name'].' '.$row4['last_name'];?></td>
  <td><?php echo $row4['native_name']; ?></td>
  <td><?php echo $row4['english_name']; ?></td>
  <td><?php echo $row4['year_made']; ?></td>
  <td><?php echo $row4['role']; ?></td>
</tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
?>
  <!-- Lead Actor , Producer, director and Music Director Ends here -->





  <!-- Lead Actress , Producer , Director and Music Director Starts here -->
  <?php
  if(empty($lead_actor) AND !empty($lead_actress) AND !empty($producer) AND !empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actress'
    AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
    OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name LIKE '%".$lead_actress."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='producer'
      AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
      OR people.last_name LIKE '%".$producer."%' OR people.stage_name LIKE '%".$producer."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='director'
        AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
        OR people.last_name LIKE '%".$director."%' OR people.stage_name LIKE '%".$director."%')");
        if(mysqli_num_rows($query3)>0){
          $query4 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
          people.first_name,people.middle_name,people.last_name
          from movie_people
          JOIN people ON people.people_id = movie_people.people_id
          JOIN movies ON movies.movie_id = movie_people.movie_id
          WHERE movie_people.role='music_director'
          AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
          OR people.last_name LIKE '%".$music_director."%' OR people.stage_name LIKE '%".$music_director."%')");
          if(mysqli_num_rows($query4)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
  <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>



<?php
while($row4 = mysqli_fetch_assoc($query4)){
  $count++;
?>
<tr>
  <td><?php echo $count; ?></td>
  <td><?php echo $row4['first_name'].' '.$row4['middle_name'].' '.$row4['last_name'];?></td>
  <td><?php echo $row4['native_name']; ?></td>
  <td><?php echo $row4['english_name']; ?></td>
  <td><?php echo $row4['year_made']; ?></td>
  <td><?php echo $row4['role']; ?></td>
</tr>
<?php } ?>
    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
?>
  <!-- Lead Actress , Producer, director and Music Director Ends here -->




  <!-- Lead Actor ,Lead Actress , Producer , Director and Music Director Starts here -->
  <?php
  if(!empty($lead_actor) AND !empty($lead_actress) AND !empty($producer) AND !empty($director)
  AND !empty($music_director)){
    ?>
    <h3 style = "color: #01B0F1;">Search Result</h3>
    <?php
    $query1 =mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
    people.first_name,people.middle_name,people.last_name
    from movie_people
    JOIN people ON people.people_id = movie_people.people_id
    JOIN movies ON movies.movie_id = movie_people.movie_id
    WHERE movie_people.role='lead_actor'
    AND (people.first_name LIKE '%".$lead_actor."%' OR people.middle_name LIKE '%".$lead_actor."%'
    OR people.last_name LIKE '%".$lead_actor."%' OR people.stage_name LIKE '%".$lead_actor."%')");

    if(mysqli_num_rows($query1)>0){
      $query2 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
      people.first_name,people.middle_name,people.last_name
      from movie_people
      JOIN people ON people.people_id = movie_people.people_id
      JOIN movies ON movies.movie_id = movie_people.movie_id
      WHERE movie_people.role='lead_actress'
      AND (people.first_name LIKE '%".$lead_actress."%' OR people.middle_name LIKE '%".$lead_actress."%'
      OR people.last_name LIKE '%".$lead_actress."%' OR people.stage_name  LIKE '%".$lead_actress."%')");

      if(mysqli_num_rows($query2)>0){
        $query3 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
        people.first_name,people.middle_name,people.last_name
        from movie_people
        JOIN people ON people.people_id = movie_people.people_id
        JOIN movies ON movies.movie_id = movie_people.movie_id
        WHERE movie_people.role='producer'
        AND (people.first_name LIKE '%".$producer."%' OR people.middle_name LIKE '%".$producer."%'
        OR people.last_name LIKE '%".$producer."%' OR people.stage_name LIKE '%".$producer."%')");
        if(mysqli_num_rows($query3)>0){
          $query4 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
          people.first_name,people.middle_name,people.last_name
          from movie_people
          JOIN people ON people.people_id = movie_people.people_id
          JOIN movies ON movies.movie_id = movie_people.movie_id
          WHERE movie_people.role='director'
          AND (people.first_name LIKE '%".$director."%' OR people.middle_name LIKE '%".$director."%'
          OR people.last_name LIKE '%".$director."%' OR people.stage_name LIKE '%".$director."%')");
          if(mysqli_num_rows($query4)>0){

            $query5 = mysqli_query($db,"select movies.native_name,movies.english_name,movies.year_made,movie_people.role,
            people.first_name,people.middle_name,people.last_name
            from movie_people
            JOIN people ON people.people_id = movie_people.people_id
            JOIN movies ON movies.movie_id = movie_people.movie_id
            WHERE movie_people.role='music_director'
            AND (people.first_name LIKE '%".$music_director."%' OR people.middle_name LIKE '%".$music_director."%'
            OR people.last_name LIKE '%".$music_director."%' OR people.stage_name LIKE '%".$music_director."%')");
            if(mysqli_num_rows($query5)>0){
    $count ='0';
    ?>
    <table class="datatable table table-striped table-bordered datatable-style table-hover"
    id="info" cellpadding="0" cellspacing="0" border="0">
    <thead>
  <tr id="table-first-row">
    <th scope="col">#</th>
    <th scope="col">People Name</th>
    <th scope="col">Movie's Native Name</th>
    <th scope="col">Movie's English Name</th>
    <th scope="col">Movie's Year Made</th>
    <th scope="col">Role</th>
    </tr>
    </thead>
    <tbody>
      <?php
      while($row1 = mysqli_fetch_assoc($query1)){
        $count++;
      ?>
      <tr>
        <td><?php echo $count; ?></td>
        <td><?php echo $row1['first_name'].' '.$row1['middle_name'].' '.$row1['last_name'];?></td>
        <td><?php echo $row1['native_name']; ?></td>
        <td><?php echo $row1['english_name']; ?></td>
        <td><?php echo $row1['year_made']; ?></td>
        <td><?php echo $row1['role']; ?></td>
      </tr>
    <?php } ?>

    <?php
    while($row2 = mysqli_fetch_assoc($query2)){
      $count++;
    ?>
    <tr>
      <td><?php echo $count; ?></td>
      <td><?php echo $row2['first_name'].' '.$row2['middle_name'].' '.$row2['last_name'];?></td>
      <td><?php echo $row2['native_name']; ?></td>
      <td><?php echo $row2['english_name']; ?></td>
      <td><?php echo $row2['year_made']; ?></td>
      <td><?php echo $row2['role']; ?></td>
    </tr>
  <?php } ?>

  <?php
  while($row3 = mysqli_fetch_assoc($query3)){
    $count++;
  ?>
  <tr>
    <td><?php echo $count; ?></td>
    <td><?php echo $row3['first_name'].' '.$row3['middle_name'].' '.$row3['last_name'];?></td>
    <td><?php echo $row3['native_name']; ?></td>
    <td><?php echo $row3['english_name']; ?></td>
    <td><?php echo $row3['year_made']; ?></td>
    <td><?php echo $row3['role']; ?></td>
  </tr>
<?php } ?>



<?php
while($row4 = mysqli_fetch_assoc($query4)){
  $count++;
?>
<tr>
  <td><?php echo $count; ?></td>
  <td><?php echo $row4['first_name'].' '.$row4['middle_name'].' '.$row4['last_name'];?></td>
  <td><?php echo $row4['native_name']; ?></td>
  <td><?php echo $row4['english_name']; ?></td>
  <td><?php echo $row4['year_made']; ?></td>
  <td><?php echo $row4['role']; ?></td>
</tr>
<?php } ?>


<?php
while($row5 = mysqli_fetch_assoc($query5)){
  $count++;
?>
<tr>
  <td><?php echo $count; ?></td>
  <td><?php echo $row5['first_name'].' '.$row5['middle_name'].' '.$row5['last_name'];?></td>
  <td><?php echo $row5['native_name']; ?></td>
  <td><?php echo $row5['english_name']; ?></td>
  <td><?php echo $row5['year_made']; ?></td>
  <td><?php echo $row5['role']; ?></td>
</tr>
<?php } ?>

    </tbody>
  </table>

  <?php }
  else{
    echo '<h3>No Matches</h3>';
  }
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
else{
  echo '<h3>No Matches</h3>';
}
}
}
?>
  <!-- Lead Actor,Lead Actress , Producer, director and Music Director Ends here -->




  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

    $('#info thead tr').clone(true).appendTo( '#info thead' );
  $('#info thead tr:eq(1) th').each( function (i) {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

      $( 'input', this ).on( 'keyup change', function () {
          if ( table.column(i).search() !== this.value ) {
              table
                  .column(i)
                  .search( this.value )
                  .draw();
          }
      } );
  } );

  var table = $('#info').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info1').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

    $('#info1 thead tr').clone(true).appendTo( '#info1 thead' );
  $('#info1 thead tr:eq(1) th').each( function (i) {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

      $( 'input', this ).on( 'keyup change', function () {
          if ( table.column(i).search() !== this.value ) {
              table
                  .column(i)
                  .search( this.value )
                  .draw();
          }
      } );
  } );

  var table = $('#info1').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info2').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

    $('#info2 thead tr').clone(true).appendTo( '#info2 thead' );
  $('#info2 thead tr:eq(1) th').each( function (i) {
      var title = $(this).text();
      $(this).html( '<input type="text" placeholder="Search '+title+'" />' );

      $( 'input', this ).on( 'keyup change', function () {
          if ( table.column(i).search() !== this.value ) {
              table
                  .column(i)
                  .search( this.value )
                  .draw();
          }
      } );
  } );

  var table = $('#info2').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>
  <script>
  $(document).ready(function(){

      // Initialize select2
      $("#selUser").select2();


  });
  </script>
 <style>
   tfoot {
     display: table-header-group;
   }
 </style>




<!-- If we want to add any footer to the web pages, update the footer.-->

<div id="foot"></div>



  <?php
    include("./footer.php");
    db_disconnect($db);
?>
