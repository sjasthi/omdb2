<?php
$nav_selected = "MOVIES";
$left_buttons = "YES";
$left_selected = "NO";

include("./nav.php");
require 'bin/functions.php';
?>

<!-- =====================================================================================================

This page displays the information about people given a people_id.
The input is "people_id".
This "people_id" is passed to people_info.php as a URL parameter.

This pages displays the people information in four sections.

[A] PEOPLE data
[B] PEOPLE aggregation
[C] PEOPLE - Movies
[D] PEOPLE - Songs

The above three sections are outlined below

[A] PEOPLE data
people_id
stage_name
first_name
middle_name
last_name
gender
image_name

[B] PEOPLE aggegation
(display this as a table or name value pairs;
Do whatever is easier for you)

No of Movies as <role1>:
No of Movies as <role2>:
No of Movies as <role3>:
No of Songs as Composer:
No of Songs as Lyricist:
No of Songs as Music Director:

[C] PEOPLE - Movies
(display this as a table)

movie_id, native_name, english_name, year_made, role, screen_name


[D} PEOPLE - Songs

Display Type: Show this as a table

song_id
title
lyrics (show first 30 characters)
role (from song_people)

===================================================================================================== -->

<!-- ========== Getting the movie id =====================================
// This is the movie_id coming to this page as GET parameter
// We will fetch it and save it as $movie_id to be used in our queries
======================================================================== -->
<?php
if (isset($_GET['people_id'])) {
  $people_id = mysqli_real_escape_string($db, $_GET['people_id']);
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
    <h3 style="color: #01B0F1;">[A.1] People -> Basic Data</h3>

    <table id="info" cellpadding="0" cellspacing="0" border="0"
        class="datatable table table-striped table-bordered datatable-style table-hover"
        width="100%" style="width: 100px;">
          <thead>
            <tr id="table-first-row">
                    <th>People Id</th>
                    <th>Stage Name</th>
                    <th>First Name</th>
                    <th>Middle Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
            </tr>
          </thead>
          <tbody>

    <?php
    // query string for the Query A.1
    $sql_A1 = "select * from people where people_id='$people_id'";

    if (!$sql_A1_result = $db->query($sql_A1)) {
      die('There was an error running query[' . $connection->error . ']');
    }

    if ($sql_A1_result->num_rows > 0) {
      $a1_tuple = $sql_A1_result->fetch_assoc();
      ?>
      <tr>
        <td><?php echo $a1_tuple['people_id'] ?></td>
        <td><?php echo $a1_tuple['stage_name']?></td>
        <td><?php echo $a1_tuple["first_name"]?></td>
        <td><?php echo $a1_tuple["middle_name"]?></td>
        <td><?php echo $a1_tuple["last_name"] ?></td>
        <td><?php echo $a1_tuple["gender"] ?></td>
        </tr>

  <?php  } //end if
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
