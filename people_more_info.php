<?php
require_once __DIR__ . '/security.php';
?>
<?php
$nav_selected = "PEOPLE";
$left_buttons = "YES";
$left_selected = "NO";

include("./nav.php");
require 'bin/functions.php';
?>




<?php
if (isset($_GET['people_id'])) {
  $people_id = $_GET['people_id'];
}
?>




<div class="right-content">
  <div class="container">



    <table id="info" cellpadding="0" cellspacing="0" border="0"
        class="datatable table table-striped table-bordered datatable-style table-hover"
        width="100%" >
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
    $sql_A1 = ['sql' => 'SELECT people_id, stage_name, first_name, middle_name, last_name, gender
               FROM people
               WHERE people_id=?', 'params' => [$people_id]];




    if (!$sql_A1_result = db_query($db, $sql_A1)) {
      security_fail(500, 'Unable to complete this request. Please try again later.');
    }

    if ($sql_A1_result->num_rows > 0) {
      $a1_tuple = $sql_A1_result->fetch_assoc();
      ?>
      <td><?php echo h($a1_tuple["people_id"]) ?></td>
      <td><?php echo h($a1_tuple["first_name"]) ?></td>
      <td><?php echo h($a1_tuple["last_name"]) ?></td>
      <td><?php echo h($a1_tuple["middle_name"]) ?></td>
      <td><?php echo h($a1_tuple["stage_name"]) ?></td>
      <td><?php echo h($a1_tuple["gender"]) ?></td>
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
