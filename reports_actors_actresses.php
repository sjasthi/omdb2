<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
  $nav_selected = "REPORTS";
  $left_buttons = "YES";
  $left_selected = "ACTORS_ACTRESSES";

  include("./nav.php");

  ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Jerridd's Actors/Actress' Page</h3>

        <h3><img src="images/trivia.png" style="max-height: 35px;" />Which Actors and Actresses' Films are to be viewed?</h3>

        <form action="reports_actors_actresses.php" method="get">
        Actor Name: <input type="text" name="name1"><br>
        Actresses Name: <input type="text" name="name2"><br>
        <input type="submit">
        </form>



        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>native_name</th>
                        <th>year_made</th>
                </tr>
              </thead>

              <tbody>

              <?php

$actor_name = trim($_GET['name1'] ?? '');
$actress_name = trim($_GET['name2'] ?? '');

$actor_role = 'Lead Actor';
$actress_role = 'Lead Actress';

if ($actor_name !== '' && $actress_name !== '') {

    $stmt = mysqli_prepare(
        $db,
        "SELECT DISTINCT
            m.native_name,
            m.year_made
         FROM movies m

         INNER JOIN movie_people mp_actor
            ON m.movie_id = mp_actor.movie_id

         INNER JOIN people p_actor
            ON p_actor.people_id = mp_actor.people_id

         INNER JOIN movie_people mp_actress
            ON m.movie_id = mp_actress.movie_id

         INNER JOIN people p_actress
            ON p_actress.people_id = mp_actress.people_id

         WHERE mp_actor.role = ?
           AND p_actor.stage_name = ?
           AND mp_actress.role = ?
           AND p_actress.stage_name = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "ssss",
        $actor_role,
        $actor_name,
        $actress_role,
        $actress_name
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result->num_rows > 0) {

        while ($row = $result->fetch_assoc()) {

            echo '<tr>
                    <td>' . htmlspecialchars($row["native_name"]) . '</td>
                    <td>' . htmlspecialchars($row["year_made"]) . '</td>
                  </tr>';
        }

    } else {

        echo '<tr><td colspan="2">0 results</td></tr>';
    }

    $result->close();
    mysqli_stmt_close($stmt);

} else {

    echo '<tr><td colspan="2">0 results</td></tr>';
}



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
ss
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
