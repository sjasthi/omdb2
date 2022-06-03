<?php
$nav_selected = "SONGS";
$left_buttons = "YES";
$left_selected = "NO";

include("./nav.php");
require 'bin/functions.php';

?>

<!-- =====================================================================================================

This page displays the information from all 12 tables.
The input is "movie_id".
This "movie_id" is passed to movie_info.php as a URL parameter.

This pages displays the movie information in three sections.

[A] MOVIE
[B] PEOPLE
[C] SONGS

The above three sections are outlined below

[A]  MOVIE Details

[A.1] Basic Data  (table: movies)
Display Type: Name - value pairs

id
native_name
english_name
year_made

[A.2] Extended Data (table: movie_data)
Display Type: Name - value pairs

language
country
genre
plot

[A.3] Movie Media (table: movie_media)
Display Type: Show this as a table

m_media_id
m_link  (preferable to display the media on the page)
m_link_type

[A.4] Movie Key Words (table: movie_keywords)
Display Type: Name - value pairs

keywords (show it as a comma separated list)


[A.5] Movie trivia (table: movie_trivia)
Display Type: Heading and set of values (ordered by serial number starting with 1)

trivia (show these as a list)


[B] PEOPLE Details

[B.1] People  (table: movie_people and people)
Display Type: Show this as a table

role
screen_name
first_name
middle_name
last_name
image_name (prefereable to display the image on the page)

[C] SONGS Details

[C.1] Songs (table: movie_song, songs, song_media, song_people, song_keywords)
Display Type: Show this as a table

title
lyrics
screen name (from people)
role (from song_people)
keywords (from song_keywords, show this info as comma separated list
media (from songs_media - show the IDs as comma separated list, media_link will be a hyper link)

===================================================================================================== -->

<!-- ========== Getting the movie id =====================================
// This is the movie_id coming to this page as GET parameter
// We will fetch it and save it as $movie_id to be used in our queries
======================================================================== -->
<?php
if (isset($_GET['song_id'])) {
  $song_id = mysqli_real_escape_string($db, $_GET['song_id']);
}
?>


<!-- ================ [A.1] Basic Data (table: movies) ======================
Display Type: Name - value pairs

movie_id
native_name
english_name
year_made
========================================================================= -->

<div class="right-content">
  <div class="container">
    <h3 style="color: #01B0F1;">[A.1] Song -> Basic Data</h3>


    <table id="info" cellpadding="0" cellspacing="0" border="0"
        class="datatable table table-striped table-bordered datatable-style table-hover"
        width="100%" style="width: 100px;">
          <thead>
            <tr id="table-first-row">

                    <th>Title</th>
                    <th>Lyrics</th>
                    <th>Theme</th>

            </tr>
          </thead>

          <tbody>

    <?php
    // query string for the Query A.1
    $sql_A1 = "select * from songs where song_id='$song_id'";

    if (!$sql_A1_result = $db->query($sql_A1)) {
      die('There was an error running query[' . $connection->error . ']');
    }

    if ($sql_A1_result->num_rows > 0) {
      $a1_tuple = $sql_A1_result->fetch_assoc(); ?>

        <td> <?php echo $a1_tuple["title"] ?></td>
        <td> <?php echo $a1_tuple["lyrics"] ?></td>
        <td> <?php echo $a1_tuple["theme"] ?></td>
      </tr>
    <?php } //end if
    else {
      echo "0 results";
    } //end else

    $sql_A1_result->close();
    ?>
  </tbody>
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
