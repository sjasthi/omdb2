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
  if(isset($_POST['song_people_search_button'])){
    $lyricist = $_POST['lyricist'];
    $playback_singer = $_POST['playback_singer'];
    $music_director = $_POST['music_director'];
    $leading_actor = $_POST['leading_actor'];
    $leading_actress = $_POST['leading_actress'];
    $director = $_POST['director'];
    $producer = $_POST['producer'];




}
  ?>

<div class="right-content">
    <div class="container">

    <br><br>

      <?php
      if(!isset($_POST['movies']) AND !isset($_POST['people'])){ ?>
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
      if(isset($_POST['movies']) ){ ?>
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
    <input type="text" id="search_people"  class="form-control" name="people_search" placeholder="Enter People to search its record...">
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



<!-- Lyricist starts here-->

          <?php
          if(!empty($_POST['song_search'])){
            $name1 = $_POST['song_search']
             ?>


            <h3 style = "color: #01B0F1;">Song Result</h3>
          <table class="datatable table table-striped table-bordered datatable-style table-hover"
          id="info1" cellpadding="0" cellspacing="0" border="0">
          <thead>
          <tr id="table-first-row">
          <th scope="col">#</th>
          <th scope="col">Title</th>
          <th scope="col">Lyrics</th>
          <th scope="col">Theme</th>
          </tr>
          </thead>
          <tbody>


            <?php
            $count = 0;
            $query = mysqli_query($db, "SELECT * FROM `songs` WHERE title LIKE '%".$name1."%' OR lyrics LIKE '%".$name1."%' OR theme LIKE '%".$name1."%'");
            if(mysqli_num_rows($query)>0){
            while($song = mysqli_fetch_assoc($query)){
            $count++;
            ?>


        <tr>

        <th scope="row"><?php echo $count; ?></th>
        <td><?php echo $song['title']; ?></td>
        <td><?php echo $song['lyrics'] ?></td>
        <td><?php echo $song['theme'] ?></td>
        </tr>

        <?php
        $count++;
      } ?>
      <hr/>
    <?php  }
          else{
            echo "<td><h4>No Matches </h4></td>";
          }

        ?>

        </tbody>
        </table>


          <?php

          ?>

          <h3 style = "color: #01B0F1;">Song People</h3>
          <table id="info2" cellpadding="0" cellspacing="0" border="0"
              class="datatable table table-striped table-bordered datatable-style table-hover">
          <thead>
          <tr id="table-first-row">
          <th scope="col">#</th>
          <th scope="col">Song Title</th>
          <th scope="col">Song Lyrics</th>
          <th scope="col">Song Theme</th>
          <th scope="col">Stage Name</th>
          <th scope="col">People Full name</th>
          <th scope="col">People Role</th>
          </tr>
          </thead>
          <tbody>


          <?php
          $count1 = 0;
          $query1 = mysqli_query($db, "select songs.song_id, songs.title,songs.lyrics, songs.theme,song_people.role,people.stage_name, people.first_name,
          people.middle_name, people.last_name
            from song_people
            join songs on songs.song_id =song_people.song_id
            join people on people.people_id = song_people.people_id
            where songs.title LIKE '%".$name1."%'
            OR songs.lyrics LIKE '%".$name1."%'
            OR songs.theme LIKE '%".$name1."%'");
          if(mysqli_num_rows($query1)>0){
          while($song1 = mysqli_fetch_assoc($query1)){
          $count1++;
          ?>


      <tr>

      <th scope="row"><?php echo $count1; ?></th>
      <td><?php echo $song1['title']; ?></td>
      <td><?php echo $song1['lyrics'] ?></td>
      <td><?php echo $song1['theme'] ?></td>
      <td><?php echo $song1['stage_name']; ?></td>
      <td><?php echo $song1['first_name']." ".$song1['middle_name']." ".$song1['last_name']; ?></td>
      <td><?php echo $song1['role'] ?></td>
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

            ?>

            <h3 style = "color: #01B0F1;">Movie Song</h3>
            <table id="info3" cellpadding="0" cellspacing="0" border="0" width="100%"
                class="datatable table table-striped table-bordered datatable-style table-hover">
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
              where songs.title LIKE '%".$name1."%'
              OR songs.lyrics LIKE '%".$name1."%'
              OR songs.theme LIKE '%".$name1."%'
              ");

            if(mysqli_num_rows($query2)>0){
            while($song2 = mysqli_fetch_assoc($query2)){
            $count2++;
            ?>


          <tr>

          <th scope="row"><?php echo $count2; ?></th>
          <td><?php echo $song2['title']; ?></td>
          <td><?php echo $song2['lyrics'] ?></td>
          <td><?php echo $song2['theme'] ?></td>
          <td><?php echo $song2['native_name']; ?></td>
          <td><?php echo $song2['english_name']; ?></td>
          <td><?php echo $song2['year_made'] ?></td>
          </tr>

        <?php } ?>
        <hr/>
          <?php }
          else{
            echo "<td><h4>No Matches</h4></td>";
          }

          ?>

          </tbody>
          </table>



          <?php }
          else{
            if(!empty($_POST['lyricist'])){  $lyricist = $_POST['lyricist']; }
            if(!empty($_POST['playback_singer'])) { $playback_singer = $_POST['playback_singer']; }
            if(!empty($_POST['music_director'])){ $music_director = $_POST['music_director']; }
            if(!empty($_POST['leading_actor'])){ $leading_actor  = $_POST['leading_actor']; }
            if(!empty($_POST['leading_actress'])){ $leading_actress = $_POST['leading_actress']; }
            if(!empty($_POST['director'])) { $director = $_POST['director']; }
            if(!empty($_POST['producer'])) {$producer = $_POST['producer']; }
            if(!empty($lyricist)){ ?>

              <?php

              $query = mysqli_query($db,"select song_people.song_id , song_people.people_id,song_people.role,
              people.stage_name, people.first_name , people.middle_name, people.last_name
              from song_people
              JOIN people ON people.people_id = song_people.people_id
              JOIN songs ON songs.song_id = song_people.song_id
              where song_people.role ='lyricist'
              AND (people.stage_name LIKE '%".$lyricist."%'
              OR people.first_name LIKE '%".$lyricist."%'
              OR people.middle_name LIKE '%".$lyricist."%'
              OR people.last_name LIKE '%".$lyricist."%')") ;
              $count = 0;
              if(mysqli_num_rows($query)>0){ ?>
                <h3 style = "color: #01B0F1;">Songs Result</h3>
                <table id="info4" cellpadding="0" cellspacing="0" border="0" width="100%"
                    class="datatable table table-striped table-bordered datatable-style table-hover">
                <thead>
                <tr id="table-first-row">
                <th scope="col">#</th>
                <th scope="col">Song Title</th>
                <th scope="col">Song Lyrics</th>
                <th scope="col">Song Theme</th>
                <th scope="col">Role in Movie</th>
                </tr>
                </thead>
                <tbody>

                <?php
                  while($row = mysqli_fetch_assoc($query)){
                  $song_id = $row['song_id'];
                  $query1 = mysqli_query($db,"select songs.title, songs.lyrics, songs.theme
                  from songs
                  JOIN song_people ON song_people.song_id = songs.song_id
                  WHERE song_people.song_id LIKE '%".$song_id."%'");
                  if(mysqli_num_rows($query1)>0){
                  while($row1 = mysqli_fetch_assoc($query1)){
                    $count++;
                     ?>


                      <tr>
                        <td><?php echo $count; ?></td>
                      <td><?php echo $row1['title']; ?></td>
                      <td><?php echo $row1['lyrics']; ?></td>
                      <td><?php echo $row1['theme']; ?></td>
                      <td><?php echo "Lyricist"; ?></td>
                    </tr>

                  <?php }
                }
                else{
                  echo "<h3>No Matches Found</h3>";
                }
                } ?>
              </tbody>
            </table>
              <?php }
              else{
              echo "<h3>No Matches Found</h3>";
              }
             }





             if(!empty($playback_singer)){ ?>

               <?php
               $query = mysqli_query($db,"select song_people.song_id , song_people.people_id,song_people.role,
               people.stage_name, people.first_name , people.middle_name, people.last_name
               from song_people
               JOIN people ON people.people_id = song_people.people_id
               JOIN songs ON songs.song_id = song_people.song_id
               where song_people.role ='singer'
               AND (people.stage_name LIKE '%".$playback_singer."%'
               OR people.first_name LIKE '%".$playback_singer."%'
               OR people.middle_name LIKE '%".$playback_singer."%'
               OR people.last_name LIKE '%".$playback_singer."%')") ;
               $count = 0;
               if(mysqli_num_rows($query)>0){ ?>
                 <h3 style = "color: #01B0F1;">Songs Result</h3>
                 <table id="info5" cellpadding="0" cellspacing="0" border="0" width="100%"
                     class="datatable table table-striped table-bordered datatable-style table-hover">
                 <thead>
                 <tr id="table-first-row">
                 <th scope="col">#</th>
                 <th scope="col">Song Title</th>
                 <th scope="col">Song Lyrics</th>
                 <th scope="col">Song Theme</th>
                 <th scope="col">Role in Movie</th>
                 </tr>
                 </thead>
                 <tbody>

                 <?php
                   while($row = mysqli_fetch_assoc($query)){
                     $song_id = $row['song_id'];
                     $query1 = mysqli_query($db,"select songs.title, songs.lyrics, songs.theme
                     from songs
                     JOIN song_people ON song_people.song_id = songs.song_id
                     WHERE song_people.song_id LIKE '%".$song_id."%'");
                   if(mysqli_num_rows($query1)>0){
                   while($row1 = mysqli_fetch_assoc($query1)){
                     $count++;
                      ?>


                       <tr>
                         <td><?php echo $count; ?></td>
                       <td><?php echo $row1['title']; ?></td>
                       <td><?php echo $row1['lyrics']; ?></td>
                       <td><?php echo $row1['theme']; ?></td>
                       <td><?php echo "Playback Singer"?></td>
                     </tr>

                   <?php }
                 }
                 else{
                   echo "<h3>No Matches Found</h3>";
                 }
                 } ?>
               </tbody>
             </table>
               <?php }
               else{
                 echo "<h3>No Matches Found</h3>";
               }
              }






              if(!empty($music_director)){ ?>

                <?php
                $query = mysqli_query($db,"select song_people.song_id , song_people.people_id,song_people.role,
                people.stage_name, people.first_name , people.middle_name, people.last_name
                from song_people
                JOIN people ON people.people_id = song_people.people_id
                JOIN songs ON songs.song_id = song_people.song_id
                where song_people.role ='music_director'
                AND (people.stage_name LIKE '%".$music_director."%'
                OR people.first_name LIKE '%".$music_director."%'
                OR people.middle_name LIKE '%".$music_director."%'
                OR people.last_name LIKE '%".$music_director."%')") ;
                $count = 0;
                if(mysqli_num_rows($query)>0){ ?>
                  <h3 style = "color: #01B0F1;">Songs Result</h3>
                  <table id="info6" cellpadding="0" cellspacing="0" border="0" width="100%"
                      class="datatable table table-striped table-bordered datatable-style table-hover">
                  <thead>
                  <tr id="table-first-row">
                  <th scope="col">#</th>
                  <th scope="col">Song Title</th>
                  <th scope="col">Song Lyrics</th>
                  <th scope="col">Song Theme</th>
                  <th scope="col">Role in Movie</th>
                  </tr>
                  </thead>
                  <tbody>

                  <?php
                    while($row = mysqli_fetch_assoc($query)){
                      $song_id = $row['song_id'];
                      $query1 = mysqli_query($db,"select songs.title, songs.lyrics, songs.theme
                      from songs
                      JOIN song_people ON song_people.song_id = songs.song_id
                      WHERE song_people.song_id LIKE '%".$song_id."%'");
                    if(mysqli_num_rows($query1)>0){
                    while($row1 = mysqli_fetch_assoc($query1)){
                      $count++;
                       ?>


                        <tr>
                          <td><?php echo $count; ?></td>
                        <td><?php echo $row1['title']; ?></td>
                        <td><?php echo $row1['lyrics']; ?></td>
                        <td><?php echo $row1['theme']; ?></td>
                        <td><?php echo "Music Director"; ?></td>
                      </tr>

                    <?php }
                  }
                  else{
                    echo "<h3>No Matches Found</h3>";
                  }
                  } ?>
                </tbody>
              </table>
                <?php }
                else{
                  echo "<h3>No Matches Found</h3>";
                }
               }




               if(!empty($leading_actor)){ ?>

                 <?php
                 $query = mysqli_query($db,"select movie_people.movie_id , movie_people.people_id,movie_people.role,
                 people.stage_name, people.first_name , people.middle_name, people.last_name
                 from movie_people
                 JOIN people ON people.people_id = movie_people.people_id
                 JOIN movies ON movies.movie_id = movie_people.movie_id
                 where movie_people.role ='lead_actor'
                 AND (people.stage_name LIKE '%".$leading_actor."%'
                 OR people.first_name LIKE '%".$leading_actor."%'
                 OR people.middle_name LIKE '%".$leading_actor."%'
                 OR people.last_name LIKE '%".$leading_actor."%')") ;
                 $count = 0;
                 if(mysqli_num_rows($query)>0){ ?>
                   <h3 style = "color: #01B0F1;">Songs Result</h3>
                   <table id="info7" cellpadding="0" cellspacing="0" border="0" width="100%"
                       class="datatable table table-striped table-bordered datatable-style table-hover">
                   <thead>
                   <tr id="table-first-row">
                   <th scope="col">#</th>
                   <th scope="col">Song Title</th>
                   <th scope="col">Song Lyrics</th>
                   <th scope="col">Song Theme</th>
                   <th scope="col">Role in Movie</th>
                   </tr>
                   </thead>
                   <tbody>

                   <?php
                     while($row = mysqli_fetch_assoc($query)){
                     $movie_id = $row['movie_id'];
                     $query1 = mysqli_query($db,"select songs.title, songs.lyrics, songs.theme
                     from songs
                     JOIN movie_song ON movie_song.song_id = songs.song_id
                     WHERE movie_song.movie_id LIKE '%".$movie_id."%'");
                     if(mysqli_num_rows($query1)>0){
                     while($row1 = mysqli_fetch_assoc($query1)){
                       $count++;
                        ?>


                         <tr>
                           <td><?php echo $count; ?></td>
                         <td><?php echo $row1['title']; ?></td>
                         <td><?php echo $row1['lyrics']; ?></td>
                         <td><?php echo $row1['theme']; ?></td>
                         <td><?php echo "Lead Actor"; ?></td>
                       </tr>

                     <?php }
                   }
                   else{
                     echo "<h3>No Matches Found</h3>";
                   }
                   } ?>
                 </tbody>
               </table>
                 <?php }
                 else{
                   echo "<h3>No Matches Found</h3>";
                 }
                }


                if(!empty($leading_actress)){ ?>

                  <?php
                  $query = mysqli_query($db,"select movie_people.movie_id , movie_people.people_id,movie_people.role,
                  people.stage_name, people.first_name , people.middle_name, people.last_name
                  from movie_people
                  JOIN people ON people.people_id = movie_people.people_id
                  JOIN movies ON movies.movie_id = movie_people.movie_id
                  where movie_people.role ='lead_actress'
                  AND (people.stage_name LIKE '%".$leading_actress."%'
                  OR people.first_name LIKE '%".$leading_actress."%'
                  OR people.middle_name LIKE '%".$leading_actress."%'
                  OR people.last_name LIKE '%".$leading_actress."%')") ;
                  $count = 0;
                  if(mysqli_num_rows($query)>0){ ?>
                    <h3 style = "color: #01B0F1;">Songs Result</h3>
                    <table id="info8" cellpadding="0" cellspacing="0" border="0" width="100%"
                        class="datatable table table-striped table-bordered datatable-style table-hover">
                    <thead>
                    <tr id="table-first-row">
                    <th scope="col">#</th>
                    <th scope="col">Song Title</th>
                    <th scope="col">Song Lyrics</th>
                    <th scope="col">Song Theme</th>
                    <th scope="col">Role in Movie</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                      while($row = mysqli_fetch_assoc($query)){
                      $movie_id = $row['movie_id'];
                      $query1 = mysqli_query($db,"select songs.title, songs.lyrics, songs.theme
                      from songs
                      JOIN movie_song ON movie_song.song_id = songs.song_id
                      WHERE movie_song.movie_id LIKE '%".$movie_id."%'");
                      if(mysqli_num_rows($query1)>0){
                      while($row1 = mysqli_fetch_assoc($query1)){
                        $count++;
                         ?>


                          <tr>
                            <td><?php echo $count; ?></td>
                          <td><?php echo $row1['title']; ?></td>
                          <td><?php echo $row1['lyrics']; ?></td>
                          <td><?php echo $row1['theme']; ?></td>
                          <td><?php echo "Lead Actress"; ?></td>
                        </tr>

                      <?php }
                    }
                    else{
                      echo "<h3>No Matches Found</h3>";
                    }
                    } ?>
                  </tbody>
                </table>
                  <?php }
                  else{
                    echo "<h3>No Matches Found</h3>";
                  }
                 }




                 if(!empty($director)){ ?>

                   <?php
                   $query = mysqli_query($db,"select movie_people.movie_id , movie_people.people_id,movie_people.role,
                   people.stage_name, people.first_name , people.middle_name, people.last_name
                   from movie_people
                   JOIN people ON people.people_id = movie_people.people_id
                   JOIN movies ON movies.movie_id = movie_people.movie_id
                   where movie_people.role ='director'
                   AND (people.stage_name LIKE '%".$director."%'
                   OR people.first_name LIKE '%".$director."%'
                   OR people.middle_name LIKE '%".$director."%'
                   OR people.last_name LIKE '%".$director."%')") ;
                   $count = 0;
                   if(mysqli_num_rows($query)>0){ ?>
                     <h3 style = "color: #01B0F1;">Songs Result</h3>
                     <table id="info9" cellpadding="0" cellspacing="0" border="0" width="100%"
                         class="datatable table table-striped table-bordered datatable-style table-hover">
                     <thead>
                     <tr id="table-first-row">
                     <th scope="col">#</th>
                     <th scope="col">Song Title</th>
                     <th scope="col">Song Lyrics</th>
                     <th scope="col">Song Theme</th>
                     <th scope="col">Role in Movie</th>
                     </tr>
                     </thead>
                     <tbody>

                     <?php
                       while($row = mysqli_fetch_assoc($query)){
                       $movie_id = $row['movie_id'];
                       $query1 = mysqli_query($db,"select songs.title, songs.lyrics, songs.theme
                       from songs
                       JOIN movie_song ON movie_song.song_id = songs.song_id
                       WHERE movie_song.movie_id LIKE '%".$movie_id."%'");
                       if(mysqli_num_rows($query1)>0){
                       while($row1 = mysqli_fetch_assoc($query1)){
                         $count++;
                          ?>


                           <tr>
                             <td><?php echo $count; ?></td>
                           <td><?php echo $row1['title']; ?></td>
                           <td><?php echo $row1['lyrics']; ?></td>
                           <td><?php echo $row1['theme']; ?></td>
                           <td><?php echo "Director"; ?></td>
                         </tr>

                       <?php }
                     }
                     else{
                       echo "<h3>No Matches Found</h3>";
                     }
                     } ?>
                   </tbody>
                 </table>
                   <?php }
                   else{
                     echo "<h3>No Matches Found</h3>";
                   }
                  }





                  if(!empty($producer)){ ?>

                    <?php
                    $query = mysqli_query($db,"select movie_people.movie_id , movie_people.people_id,movie_people.role,
                    people.stage_name, people.first_name , people.middle_name, people.last_name
                    from movie_people
                    JOIN people ON people.people_id = movie_people.people_id
                    JOIN movies ON movies.movie_id = movie_people.movie_id
                    where movie_people.role ='producer'
                    AND (people.stage_name LIKE '%".$producer."%'
                    OR people.first_name LIKE '%".$producer."%'
                    OR people.middle_name LIKE '%".$producer."%'
                    OR people.last_name LIKE '%".$producer."%')") ;
                    $count = 0;
                    if(mysqli_num_rows($query)>0){ ?>
                      <h3 style = "color: #01B0F1;">Songs Result</h3>
                      <table id="info10" cellpadding="0" cellspacing="0" border="0" width="100%"
                          class="datatable table table-striped table-bordered datatable-style table-hover">
                      <thead>
                      <tr id="table-first-row">
                      <th scope="col">#</th>
                      <th scope="col">Song Title</th>
                      <th scope="col">Song Lyrics</th>
                      <th scope="col">Song Theme</th>
                      <th scope="col">Role in Movie</th>
                      </tr>
                      </thead>
                      <tbody>

                      <?php
                        while($row = mysqli_fetch_assoc($query)){
                        $movie_id = $row['movie_id'];
                        $query1 = mysqli_query($db,"select songs.title, songs.lyrics, songs.theme
                        from songs
                        JOIN movie_song ON movie_song.song_id = songs.song_id
                        WHERE movie_song.movie_id LIKE '%".$movie_id."%'");
                        if(mysqli_num_rows($query1)>0){
                        while($row1 = mysqli_fetch_assoc($query1)){
                          $count++;
                           ?>


                            <tr>
                              <td><?php echo $count; ?></td>
                            <td><?php echo $row1['title']; ?></td>
                            <td><?php echo $row1['lyrics']; ?></td>
                            <td><?php echo $row1['theme']; ?></td>
                            <td><?php echo "Producer"; ?></td>
                          </tr>

                        <?php }

                      }
                      else{
                        echo "<h3>No Matches Found</h3>";
                      }
                      } ?>
                    </tbody>
                  </table>
                    <?php }
                    else{
                      echo "<h3>No Matches Found</h3>";
                    }
                   }



}
  ?>

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

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info3').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info3 thead tr').clone(true).appendTo( '#info3 thead' );
  $('#info3 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info3').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info4').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info4 thead tr').clone(true).appendTo( '#info4 thead' );
  $('#info4 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info4').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info5').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info5 thead tr').clone(true).appendTo( '#info5 thead' );
  $('#info5 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info5').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info6').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info6 thead tr').clone(true).appendTo( '#info6 thead' );
  $('#info6 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info6').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info7').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info7 thead tr').clone(true).appendTo( '#info7 thead' );
  $('#info7 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info7').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info8').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info8 thead tr').clone(true).appendTo( '#info8 thead' );
  $('#info8 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info8').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info9').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info9 thead tr').clone(true).appendTo( '#info9 thead' );
  $('#info9 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info9').DataTable( {
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
  } );

  } );

  </script>

  <script type="text/javascript" language="javascript">
  $(document).ready( function () {

  $('#info10').DataTable( {
      dom: 'lfrtBip',
      buttons: [
          'copy', 'excel', 'csv', 'pdf'
      ] }
  );

  $('#info10 thead tr').clone(true).appendTo( '#info10 thead' );
  $('#info10 thead tr:eq(1) th').each( function (i) {
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

  var table = $('#info10').DataTable( {
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

  <?php
    include("./footer.php");
    db_disconnect($db);
  ?>
