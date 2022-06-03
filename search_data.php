<?php
  $nav_selected = "search_data";
  $left_buttons = "YES";
  $left_selected = "search_data";
  include("./nav.php");

?>

  <?php if(isset($_POST['song_search_button'])){
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
      if(isset($_POST['songs'])){ ?>
          <h3 style = "color: #01B0F1;">Search Songs</h3>


        <form method="post" action="advance_song_search.php">

          <div class="form-group">
            <label for="song_search">Search Songs</label>
            <input type="text"
            style="width: 100%;
            margin: 0 auto;
            height:30px;"
             class="form-control" id="song_search" name="song_search" placeholder="Enter song to search its record...">
          </div>
          <br>

          <div class="form-group">
            <label for="lyricist">Lyricist</label>
            <input type="text"
            style="width: 100%;
            margin: 0 auto;
            height:30px;"
            class="form-control" id="lyricist" name="lyricist" placeholder="lyricist Name">
          </div><br>

          <div class="form-group">
            <label for="playback_singer">Playback Singer</label>
            <input type="text"
            style="width: 100%;
            margin: 0 auto;
            height:30px;"
             class="form-control" id="playback_singer" name="playback_singer" placeholder="Playback Singer Name">
          </div>
<br>
          <div class="form-group">
            <label for="music_director">Music Director</label>
            <input type="text"
            style="width: 100%;
            margin: 0 auto;
            height:30px;"
             class="form-control" id="music_director" name="music_director" placeholder="Music Director Name">
          </div>
<br>
        <div class="form-group">
          <label for="leading_actor">Leading Actor</label>
          <input type="text"
          style="width: 100%;
          margin: 0 auto;
          height:30px;"
           class="form-control" id="leading_actor" name="leading_actor" placeholder="Leading Actor Name">
        </div>
<br>
        <div class="form-group">
          <label for="leading_actress">Leading Actress</label>
          <input type="text"
          style="width: 100%;
          margin: 0 auto;
          height:30px;"
           class="form-control" id="leading_actress" name="leading_actress" placeholder="Leading Actress Name">
        </div>
<br>
        <div class="form-group">
          <label for="director">Director</label>
          <input type="text"
          style="width: 100%;
          margin: 0 auto;
          height:30px;"
           class="form-control" id="director" name="director" placeholder="director Name">
        </div>
<br>
        <div class="form-group">
          <label for="producer">Producer</label>
          <input type="text"
          style="width: 100%;
          margin: 0 auto;
          height:30px;"
           class="form-control" id="producer" name="producer" placeholder="producer Name">
        </div>
<br>

        <button type="submit"
        style="
         border-radius: 8px;
         font-weight: bold;
         border: 0;
         padding: 7px 15px 7px 15px;
         font-size: 20px;
         margin-right: 10px;"

         name="song_people_search_button" class="btn btn-primary">Search</button>
      </form>


      <?php }
      if(isset($_POST['movies'])){ ?>

        <h3 style = "color: #01B0F1;">Search movies by role</h3>



  <form method="post" action="advance_movie_search.php">

    <div class="form-group">
      <label for="movie_search">Search Movie</label>
      <input type="text"
      style="width: 100%;
      margin: 0 auto;
      height:30px;"
       class="form-control" id="movie_search" name="movie_search" placeholder="Enter movie to search its record...">
    </div><br>

  <div class="form-group">
    <label for="lead_actor">Lead Actor</label>
    <input type="text"
    style="width: 100%;
    margin: 0 auto;
    height:30px;"
     class="form-control" id="lead_actor" name="lead_actor" placeholder="Lead Actor Name">
  </div><br>

  <div class="form-group">
    <label for="lead_actress">Lead Actress</label>
    <input type="text"
    style="width: 100%;
    margin: 0 auto;
    height:30px;"
    class="form-control" id="lead_actress" name="lead_actress" placeholder="Lead Actress Name">
  </div><br>

  <div class="form-group">
    <label for="producer">Producer</label>
    <input type="text"
    style="width: 100%;
    margin: 0 auto;
    height:30px;"
     class="form-control" id="producer" name="producer" placeholder="producer Name">
  </div><br>

  <div class="form-group">
    <label for="director">Director</label>
    <input type="text"
    style="width: 100%;
    margin: 0 auto;
    height:30px;"
    class="form-control" id="director" name="director" placeholder="director Name">
  </div>
<br>
  <div class="form-group">
    <label for="music_director">Music Director</label>
    <input type="text"
    style="width: 100%;
    margin: 0 auto;
    height:30px;"
    class="form-control" id="music_director" name="music_director" placeholder="Music Director Name">
  </div>
<br>


  <button type="submit"   style="
    border-radius: 8px;
    font-weight: bold;
    border: 0;
    padding: 7px 15px 7px 15px;
    font-size: 20px;
    margin-right: 10px;" name="movie_people_search_button" class="btn btn-primary">Search</button>
</form>






      <?php }
      if(isset($_POST['people'])){ ?>
          <h3 style = "color: #01B0F1;">Search Peoples</h3>


          <form method="post" action="advance_people_search.php">

            <div class="form-group">
              <label for="search_people">Search People</label>
            <input type="text" id="search_people" style="width: 100%;
            margin: 0 auto;
            height:30px;"
             name="people_search" placeholder="Enter People to search its record...">
          </div><br/>

          <div class="form-group" >
            <label for="stage_name" >Stage Name</label>
            <select name="stage_name" id='selUser' style="width: 100%;
            margin: 0 auto;
            padding: 10px;">

              <?php
              $query = mysqli_query($db,"select * from people");
              while($options = mysqli_fetch_assoc($query)){ ?>
                <option value="<?php echo $options['stage_name']?>"><?php echo $options['stage_name'];?></option>

              <?php }
              ?>
            </select>
          </div><br/>

        <div class="form-group">
          <label for="role">Role</label>
          <select name="role" style="width: 100%;
          margin: 0 auto;
          padding: 10px;">
            <option value="" disabled>Select One</option>
            <option value="lead_actor">Lead Actor</option>
            <option value="lead_actress">Lead Actress</option>
            <option value="producer">Producer</option>
            <option value="director">Director</option>
            <option value="music_director">Music Director</option>
          </select>
        </div><br/>


        <button type="submit"  name="search" class="btn btn-primary"
        style="
        border-radius: 8px;
        font-weight: bold;
        border: 0;
        padding: 7px 15px 7px 15px;
        font-size: 20px;
        margin-right: 10px;">Search</button>
      </form>
      <?php }


      ?>


      <?php
      if(!empty($name1)){
        ?>
        <h3 style = "color: #01B0F1;">Search Songs</h3>
      <form method="post">
        <input type="text" name="song_search" placeholder="Enter song to search its record...">
        <button type="submit" name="song_search_button"><i class="fa fa-search" aria-hidden="true"></i></button>
      </form>

        <h3 style = "color: #01B0F1;">Song Result</h3>
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
      <table class="table">
      <thead>
      <tr>
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
        <table class="table">
        <thead>
        <tr>
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



  <?php }  ?>

















            <?php
            if(!empty($name2)){ ?>

              <h3 style = "color: #01B0F1;">Search Movies</h3>
            <form method="post">
              <input type="text" name="movie_search" placeholder="Enter movie to search its record...">
              <button type="submit" name="movie_search_button"><i class="fa fa-search" aria-hidden="true"></i></button>
            </form>

              <h3 style = "color: #01B0F1;">Movies Result</h3>
            <table class="table">
            <thead>
            <tr>
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
            <table class="table">
            <thead>
            <tr>
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
              <table class="table">
              <thead>
              <tr>
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



        <?php }  ?>













          <?php
          if(!empty($name3)){ ?>
            <h3 style = "color: #01B0F1;">Search Peoples</h3>
          <form method="post">
            <input type="text" name="people_search" placeholder="Enter People to search its record...">
            <button type="submit" name="people_search_button"><i class="fa fa-search" aria-hidden="true"></i></button>
          </form>


            <h3 style = "color: #01B0F1;">People Result</h3>
          <table class="table">
          <thead>
          <tr>
          <th scope="col">#</th>
          <th scope="col">Stage Name</th>
          <th scope="col">People full name</th>
          <th scope="col">Gender</th>
          </tr>
          </thead>
          <tbody>


            <?php
            $count = 0;
            $query = mysqli_query($db, "SELECT * FROM `people` WHERE stage_name LIKE '%".$name3."%'
              OR first_name LIKE '%".$name3."%'
              OR middle_name LIKE '%".$name3."%'
              OR last_name LIKE '%".$name3."%'
              OR gender LIKE '%".$name3."%'
               ");
            if(mysqli_num_rows($query)>0){
            while($people = mysqli_fetch_assoc($query)){
            $count++;
            ?>


        <tr>

        <th scope="row"><?php echo $count; ?></th>
        <td><?php echo $people['stage_name']; ?></td>
        <td><?php echo $people['first_name']." ".$people['middle_name']." ".$people['last_name']; ?></td>
        <td><?php echo $people['gender'] ?></td>
        </tr>

        <?php
        $count++;
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

          <h3 style = "color: #01B0F1;">Movie People</h3>
          <table class="table">
          <thead>
          <tr>
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
            where people.stage_name LIKE '%".$name3."%'
            OR people.first_name LIKE '%".$name3."%'
            OR people.middle_name LIKE '%".$name3."%'
            OR people.last_name LIKE '%".$name3."%'
            OR people.gender LIKE '%".$name3."%'
            ");
          if(mysqli_num_rows($query1)>0){
          while($people1 = mysqli_fetch_assoc($query1)){
          $count1++;
          ?>


      <tr>

      <th scope="row"><?php echo $count1; ?></th>
      <td><?php echo $people1['first_name']." ".$people1['middle_name']." ".$people1['last_name']; ?></td>
      <td><?php echo $people1['role'] ?></td>
      <td><?php echo $people1['native_name'] ?></td>
      <td><?php echo $people1['english_name']; ?></td>
      <td><?php echo $people1['year_made'] ?></td>
      </tr>

      <?php
    } ?>
    <hr/>
  <?php  }
  else{
  echo "<td><h4>No Matches</h4></td>";
}
      ?>

      </tbody>
      </table>



            <?php

            ?>

            <h3 style = "color: #01B0F1;">Song People</h3>
            <table class="table">
            <thead>
            <tr>
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
            $count2 = 0;
            $query2 = mysqli_query($db, "select songs.song_id, songs.title,songs.lyrics, songs.theme,song_people.role,people.stage_name, people.first_name,
            people.middle_name, people.last_name
              from song_people
              join songs on songs.song_id =song_people.song_id
              join people on people.people_id = song_people.people_id
              where people.stage_name LIKE '%".$name3."%'
              OR people.first_name LIKE '%".$name3."%'
              OR people.middle_name LIKE '%".$name3."%'
              OR people.last_name LIKE '%".$name3."%'
              OR people.gender LIKE '%".$name3."%'
              ");

            if(mysqli_num_rows($query2)>0){
            while($people2 = mysqli_fetch_assoc($query2)){
            $count1++;
            ?>


        <tr>

        <th scope="row"><?php echo $count1; ?></th>
        <td><?php echo $people2['title']; ?></td>
        <td><?php echo $people2['lyrics'] ?></td>
        <td><?php echo $people2['theme'] ?></td>
        <td><?php echo $people2['stage_name']; ?></td>
        <td><?php echo $people2['first_name']." ".$people2['middle_name']." ".$people2['last_name']; ?></td>
        <td><?php echo $people2['role'] ?></td>
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



      <?php }  ?>


 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

 <script>
 $(document).ready(function(){

     // Initialize select2
     $("#selUser").select2();


 });
 </script>
<?php
    db_disconnect($db);
    include("./footer.php");
?>
