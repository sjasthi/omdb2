<?php
$nav_selected = "MOVIES";
$left_buttons = "YES";
$left_selected = "NO";

include("./nav.php");
require 'bin/functions.php';

?>


<!-- ========== Getting the movie id =====================================
// This is the movie_id coming to this page as GET parameter
// We will fetch it and save it as $movie_id to be used in our queries
======================================================================== -->
<?php
if (isset($_GET['movie_id'])) {
  $movie_id = mysqli_real_escape_string($db, $_GET['movie_id']);
}
?>




<div class="right-content">
  <div class="container">
    <h3 style="color: #01B0F1;">Movies Information</h3>

    <?php


    // query string for the Query A.1
    $sql_A1 = "SELECT movie_id, native_name, english_name, year_made 
               FROM movies 
               WHERE movie_id =" . $movie_id;

    if (!$sql_A1_result = $db->query($sql_A1)) {
      die('There was an error running query[' . $connection->error . ']');
    }

    if ($sql_A1_result->num_rows > 0) {
      $a1_tuple = $sql_A1_result->fetch_assoc();
      echo '<br> Movie ID : ' . $a1_tuple["movie_id"] .
        '<br> Native Name : ' . $a1_tuple["native_name"] .
        '<br> English Name : ' . $a1_tuple["english_name"] .
        '<br> Year Made :  ' . $a1_tuple["year_made"];
    } //end if
    else {
      echo "0 results";
    } //end else

    $sql_A1_result->close();
    ?>
  </div>
</div>




<div class="right-content">
  <div class="container">
    
          
    <?php

    $sql_A2 = "SELECT language, country, genre, plot FROM `movie_data` WHERE movie_data.movie_id =" . $movie_id;

   if (!$sql_A2_result = $db->query($sql_A2)) { 
     die('There was an error running query[' . $connection->error . ']');
    }

  if ($sql_A2_result->num_rows > 0) {
    $a2_tuple = $sql_A2_result->fetch_assoc();
      echo '<br> Language : ' . $a2_tuple["language"] .
      '<br> Country : ' . $a2_tuple["country"] .
      '<br> Genre : ' . $a2_tuple["genre"] .
      '<br> Plot :  ' . $a2_tuple["plot"];
  } //end if
  else {
    echo "0 results";
  } //end else

  $sql_A2_result->close(); 
    ?>

  </div>
</div>




<div class="right-content">
  <div class="container">
    


    <table class="display" id="movie_media_table" style="width:100%">
      <div class="table responsive">

        <thead>
          <tr>
            <th> Movie ID </th>
            <th> Media Id</th>
            <th> Media Link</th>
            <th> Link Type</th>
          </tr>
        </thead>

        <?php
        
        // query string for the Query A.1
        $sql_A3 = "SELECT movie_id, movie_media_id, m_link, m_link_type FROM movie_media WHERE movie_id =" . $movie_id;
        
        if (!$sql_A3_result = $db->query($sql_A3)) {
          die('There was an error running query[' . $connection->error . ']');
        }

        // this is 1 to many relationship
        // So, many tuples may be returned
        // We will display those in a table in a while loop
            $m_link='';
             $shirtloc='images/movie_media/';
        if ($sql_A3_result->num_rows > 0) {
          // output data of each row
          while ($a3_tuple = $sql_A3_result->fetch_assoc()) {
            echo '<tr>
                      <td>' . $a3_tuple["movie_id"] . '</td>
                      <td>' . $a3_tuple["movie_media_id"] . '</td>
                      <td>' . $a3_tuple["m_link"] . '</td>
                      <td>' . $a3_tuple["m_link_type"] . ' </span> </td>
                  </tr>';
            echo '<img src="'.$shirtloc.$a3_tuple["m_link"].'" width="100" height="100" />';
          } //end while
            
            
           
        } //end second if 
            
        $sql_A3_result->close();
        ?>
        <figure>

          
          <figcaption>Some more description</figcaption>
        </figure>
    </table>
  </div>
</div>





<div class="right-content">
  <div class="container">
    

    <table class="display" id="keywords_table" style="width:100%">
      <div class="table responsive">

        <thead>
          <tr>
            <th> Keywords </th>
            
          </tr>
        </thead>

        <?php

        // query string for the Query A.1
        $sql_A4 = "SELECT keyword FROM movie_keywords WHERE movie_id=" . $movie_id;

        // echo $sql_A4;

        if (!$sql_A4_result = $db->query($sql_A4)) {
          die('There was an error running query[' . $db->error . ']');
        }

        // this is 1 to many relationship
        // So, many tuples may be returned
        // We will display those in a table in a while loop
        if ($sql_A4_result->num_rows > 0) {
          // output data of each row
          while ($a4_tuple = $sql_A4_result->fetch_assoc()) {
            echo '<tr>
                      <td>' . $a4_tuple["keyword"] . ' </span> </td>
                  </tr>';
          } //end while

        } //end second if 

        $sql_A4_result->close();
        ?>

    </table>
  </div>
</div>




<div class="right-content">
  <div class="container">

    <table class="display" id="trivia_table" style="width:100%">
      <div class="table responsive">

        <thead>
          <tr>
            <th> Trivia </th>
            
          </tr>
        </thead>

        <?php

        // query string for the Query A.1
        $sql_A5 = "SELECT movie_trivia_name FROM `movie_trivia` WHERE movie_id=" . $movie_id;

        if (!$sql_A5_result = $db->query($sql_A5)) {
          die('There was an error running query[' . $db->error . ']');
        }

        // this is 1 to many relationship
        // So, many tuples may be returned
        // We will display those in a table in a while loop
        if ($sql_A5_result->num_rows > 0) {
          // output data of each row
          $s_no = 1;
          while ($a5_tuple = $sql_A5_result->fetch_assoc()) {
            echo '<tr>
                     <td> ' .$s_no .' : </td>
                      <td>' . $a5_tuple["movie_trivia_name"] . ' </span> </td>
                  </tr>';
            $s_no = $s_no + 1;
          } //end while

        } //end second if 

        $sql_A5_result->close();
        ?>

    </table>
  </div>
</div>





<div class="right-content">
  <div class="container">
    

    <table class="display" id="movie_people_table" style="width:100%">
      <div class="table responsive">

        <thead>
          <tr>
            <th> Stage Name</th>
            <th> First Name</th>
            <th> Middle Name</th>
            <th> Last Name</th>
            <th> Role </th>
            <th> Screen Name</th>
            <th> Image name</th>

          </tr>
        </thead>

        <?php

        // query string for the Query A.1
        $sql_B1 = "SELECT stage_name, first_name, middle_name, last_name, gender, `role`, screen_name, image_name 
                   FROM movie_people INNER JOIN people 
                   ON movie_people.people_id = people.people_id 
                   WHERE movie_people.movie_id=" . $movie_id;


        if (!$sql_B1_result = $db->query($sql_B1)) {
          die('There was an error running query[' . $connection->error . ']');
        }

        // this is 1 to many relationship
        // So, many tuples may be returned
        // We will display those in a table in a while loop
        if ($sql_B1_result->num_rows > 0) {
          // output data of each row
          while ($b1_tuple = $sql_B1_result->fetch_assoc()) {
            echo '<tr>
                      <td>' . $b1_tuple["stage_name"] . '</td>
                      <td>' . $b1_tuple["first_name"] . '</td>
                      <td>' . $b1_tuple["middle_name"] . '</td>
                      <td>' . $b1_tuple["last_name"] . '</td>
                      <td>' . $b1_tuple["role"] . '</td>
                      <td>' . $b1_tuple["screen_name"] . '</td>
                      <td>' . $b1_tuple["image_name"] . ' </span> </td>
                  </tr>';
          } //end while

        } //end second if 

        $sql_B1_result->close();
        ?>

    </table>
  </div>
</div>


<div class="right-content">
  <div class="container">
    

    <table class="display" id="songs_table" style="width:100%">
      <div class="table responsive">

        <thead>
          <tr>
            <th> Title </th>
            <th> Lyrics </th>
          </tr>
        </thead>

        <?php

        // query string for the Query A.1
        $sql_C1 = "SELECT title FROM songs INNER JOIN movie_song ON (movie_song.song_id = songs.song_id)
 WHERE movie_id=" . $movie_id;

        if (!$sql_C1_result = $db->query($sql_C1)) {
          die('There was an error running query[' . $db->error . ']');
        }

        // this is 1 to many relationship
        // So, many tuples may be returned
        // We will display those in a table in a while loop
        if ($sql_C1_result->num_rows > 0) {
          // output data of each row
          while ($c1_tuple = $sql_C1_result->fetch_assoc()) {
            echo '<tr>
                      <td>' . $c1_tuple["title"] . '</td>
                      <td>' . $c1_tuple["lyrics10"] . '</td>
                  </tr>';
          } //end while

        } //end second if 

        $sql_C1_result->close();
        ?>

    </table>
  </div>
</div>


<!-- ================== JQuery Data Table script ================================= -->

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

    var table = $('#info').DataTable({
      orderCellsTop: true,
      fixedHeader: true,
      retrieve: true
    });

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
