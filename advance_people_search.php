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
  if(isset($_POST['search'])){
    $stage_name = $_POST['stage_name'];
    $role = $_POST['role'];

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
      if(isset($_POST['movies'])){ ?>
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
if(!isset($_POST['songs']) AND !isset($_POST['movies'])){ ?>
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
          if(!empty($_POST['people_search'])){
            $people = $_POST['people_search'];
            $query = mysqli_query($db,"select * from people where
            stage_name LIKE '%".$people."%'
            OR first_name LIKE '%".$people."%'
            OR middle_name LIKE '%".$people."%'
            OR last_name LIKE '%".$people."%'
            ");
            if(mysqli_num_rows($query)>0){
              $count=0;
              ?>
              <br>
              <table id="info" cellpadding="0" cellspacing="0" border="0"
                  class="datatable table table-striped table-bordered datatable-style table-hover"
                  >
                    <thead>
                      <tr id="table-first-row">
                              <th>#</th>
                              <th>Stage Name</th>
                              <th>First Name</th>
                              <th>Middle Name</th>
                              <th>Last Name</th>
                              <th>Gender</th>
                      </tr>
                    </thead>
                    <tbody>

                      <?php
                      while($row = mysqli_fetch_assoc($query))
                      {
                        $count++;
                        echo '<tr>';
                        echo '<td>'.$count.'</td>';
                        echo '<td>'.$row['stage_name'].'</td>';
                        echo '<td>'.$row['first_name'].'</td>';
                        echo '<td>'.$row['middle_name'].'</td>';
                        echo '<td>'.$row['last_name'].'</td>';
                        echo '<td>'.$row['gender'].'</td>';
                        echo '</tr>';
                      }
                      ?>
                    </tbody>
                  </table>
            <?php }


          }
          else{
          if(!empty($stage_name) AND !empty($role)){
            ?>
            <h3 style = "color: #01B0F1;">Search Result</h3>
            <?php
            $query =mysqli_query($db,"select people.stage_name, people.first_name, people.middle_name, people.last_name,
            people.gender,movie_people.role,movies.native_name,movies.english_name,movies.year_made
            from movie_people
            JOIN people ON people.people_id = movie_people.people_id
            JOIN movies ON movies.movie_id = movie_people.movie_id
            WHERE people.stage_name LIKE '%".$stage_name."%'
            AND movie_people.role LIKE '%".$role."%' ");

            if(mysqli_num_rows($query)>0){
            $count ='0';
            ?>
            <table class="datatable table table-striped table-bordered datatable-style table-hover"
            id="info" cellpadding="0" cellspacing="0" border="0">
            <thead>
            <tr id="table-first-row">
            <th scope="col">#</th>
            <th scope="col">People Name</th>
            <th scope="col">Stage Name</th>
            <th scope="col">Gender</th>
            <th scope="col">Role</th>
            <th scope="col">Movie Native Name</th>
            <th scope="col">Movie's English Name</th>
            <th scope="col">Year made</th>
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
                <td><?php echo $row['stage_name']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['role']; ?></td>
                <td><?php echo $row['native_name']; ?></td>
                <td><?php echo $row['english_name']; ?></td>
                <td><?php echo $row['year_made']; ?></td>
              </tr>
            <?php } ?>
            </tbody>
          </table>

        <?php }

      else{
        echo '<h3>No Matches</h3>';
      }
    }

  }?>

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
          <table class="table">
          <thead>
          <tr>
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
    db_disconnect($db);
    include("./footer.php");
  ?>
