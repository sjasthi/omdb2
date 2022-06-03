<?php
$nav_selected = "SONGS";
$left_buttons = "YES";
$left_selected = "NO";

include("./nav.php");
require 'bin/functions.php';
?>




<?php
if (isset($_GET['song_id'])) {
  $song_id = mysqli_real_escape_string($db, $_GET['song_id']);
}
?>




<div class="right-content">
  <div class="container">
    <h3 style="color: #01B0F1;"> Songs More Data </h3>

    <table id="info" cellpadding="0" cellspacing="0" border="0"
        class="datatable table table-striped table-bordered datatable-style table-hover"
        width="100%" style="width: 100px;">
          <thead>
            <tr id="table-first-row">

                    <th>Song ID</th>
                    <th>Song Name</th>
                    <th>Lyrics</th>
                    <th>Theme</th>
            </tr>
          </thead>

          <tbody>

    <?php
    $sql_A1 = "SELECT song_id, title, lyrics, theme
              FROM songs
              WHERE song_id =" . $song_id;

    if (!$sql_A1_result = $db->query($sql_A1)) {
      die('There was an error running query[' . $connection->error . ']');
    }

    if ($sql_A1_result->num_rows > 0) {
      $a1_tuple = $sql_A1_result->fetch_assoc(); ?>
    <td><?php echo $a1_tuple["song_id"] ?></td>
    <td><?php echo $a1_tuple["title"] ?></td>
    <td><?php echo $a1_tuple["lyrics"] ?></td>
    <td><?php echo $a1_tuple["theme"]?></td>
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





<div class="right-content">
  <div class="container">
    <h3 style="color: #01B0F1;">Songs People </h3>

    <table id="info1" cellpadding="0" cellspacing="0" border="0"
        class="datatable table table-striped table-bordered datatable-style table-hover"
        width="100%" style="width: 100px;">
          <thead>
            <tr id="table-first-row">

                    <th>Song ID</th>
                    <th>People ID</th>
                    <th>Role</th>

            </tr>
          </thead>

          <tbody>

    <?php

    //TODO:
    $sql_A2 =  "SELECT song_id, people_id, role
                 FROM song_people
                 WHERE song_id =" . $song_id;

    if (!$sql_A2_result = $db->query($sql_A2)) {
      die('There was an error running query[' . $connection->error . ']');
    }

    if ($sql_A2_result->num_rows > 0) {
      $a2_tuple = $sql_A2_result->fetch_assoc(); ?>
      <td><?php echo $a2_tuple["song_id"] ?></td>
      <td><?php echo $a2_tuple["people_id"] ?></td>
      <td><?php echo $a2_tuple["role"] ?></td>

  <?php  } //end if
    else {
      echo "0 results";
    } //end else

    $sql_A2_result->close();
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

<script type="text/javascript" language="javascript">
  $(document).ready(function() {

    $('#info1').DataTable({
      dom: 'lfrtBip',
      buttons: [
        'copy', 'excel', 'csv', 'pdf'
      ]
    });

    $('#info1 thead tr').clone(true).appendTo('#info1 thead');
    $('#info1 thead tr:eq(1) th').each(function(i) {
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

    var table = $('#info1').DataTable({
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
