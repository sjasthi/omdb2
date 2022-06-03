<?php
$nav_selected = "PEOPLE";
$left_buttons = "YES";
$left_selected = "NO";

include("./nav.php");
require 'bin/functions.php';
?>




<?php
if (isset($_GET['people_id'])) {
  $people_id = mysqli_real_escape_string($db, $_GET['people_id']);
}
?>




<div class="right-content">
  <div class="container">
    <h3 style="color: #01B0F1;"> People More Data </h3>


    <table id="info" cellpadding="0" cellspacing="0" border="0"
        class="datatable table table-striped table-bordered datatable-style table-hover"
        width="100%" style="width: 100px;">
          <thead>
            <tr id="table-first-row">
                    <th>People ID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Middle Name</th>
                    <th>Stage Name</th>
                    <th>Gender</th>
            </tr>
          </thead>

          <tbody>

    <?php
    $sql_A1 = "SELECT people_id, stage_name, first_name, middle_name, last_name, gender
               FROM people
               WHERE people_id=" . $people_id;




    if (!$sql_A1_result = $db->query($sql_A1)) {
      die('There was an error running query[' . $connection->error . ']');
    }

    if ($sql_A1_result->num_rows > 0) {
      $a1_tuple = $sql_A1_result->fetch_assoc();
      ?>
      <td><?php echo $a1_tuple["people_id"] ?></td>
      <td><?php echo $a1_tuple["first_name"] ?></td>
      <td><?php echo $a1_tuple["last_name"] ?></td>
      <td><?php echo $a1_tuple["middle_name"] ?></td>
      <td><?php echo $a1_tuple["stage_name"] ?></td>
      <td><?php echo $a1_tuple["gender"] ?></td>
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
