<?php
require_once __DIR__ . '/security.php';
?>
<?php

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

  $nav_selected = "REPORTS";
  $left_buttons = "YES";
  $left_selected = "ACTORS";

  include("./nav.php");

  ?>



<div class="right-content">
    <div class="container">





        <form action="reports_actors.php" method="get">
        Actor Name: <input type="text" name="name"><br>
        <input type="submit">
        </form>



        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" >
              <thead>
                <tr id="table-first-row">
                        <th>native_name</th>
                        <th>year_made</th>
                </tr>
              </thead>

              <tbody>

              <?php

              $actor_name = trim($_GET['name'] ?? '');

$role = 'Lead Actor';

$stmt = db_prepare($db, "SELECT movies.native_name, movies.year_made
     FROM movies
     INNER JOIN movie_people
        ON movies.movie_id = movie_people.movie_id
     INNER JOIN people
        ON people.people_id = movie_people.people_id
     WHERE movie_people.role = ?
       AND people.stage_name = ?");

mysqli_stmt_bind_param(
    $stmt,
    "ss",
    $role,
    $actor_name
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

                if ($result->num_rows > 0) {
                    // output data of each row
                    // Add four more rows of data which you are getting from the database
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . h($row["native_name"]) . '</td>
                                <td>' . h($row["year_made"]) . ' </span> </td>


                            </tr>';
                    }//end while
                }//end if
                else {
                    echo "0 results";
                }//end else

                 $result->close();
                mysqli_stmt_close($stmt);


                ?>

              </tbody>
        </table>


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



 <style>
   tfoot {
     display: table-header-group;
   }
 </style>

<?php
    db_disconnect($db);
    include("./footer.php");
?>
