<?php

  $nav_selected = "PEOPLE";
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

              $actor_name = $_GET['name1'] ?? "";
              $actress_name = $_GET['name2'] ?? "";
// $sql = "SELECT * from movies ORDER BY native_name ASC;";

// $sql = "SELECT native_name, year_made from movies, movie_people, people where `movies`.`movie_id` = `movie_people`.`movie_id` AND `movie_people`.`role` = 'leading actor' AND `people`.`people_id` = `movie_people`.`people_id` AND `people`.`stage_name` = '$actor_name' ";

$sql = "SELECT
    `native_name`,
    `year_made`
FROM
    `movies`,
    `movie_people`,
    `people`
WHERE
    `movies`.`movie_id` = `movie_people`.`movie_id` AND `movie_people`.`role` = 'leading actor' AND `people`.`people_id` = `movie_people`.`people_id` AND `people`.`stage_name` = '$actor_name' AND `movies`.`movie_id` 

    IN(
    SELECT
        `movie_id`
    FROM
        `movie_people`,
        `people`
    WHERE
        `people`.`people_id` = `movie_people`.`people_id` AND `people`.`stage_name` = '$actress_name' AND `movie_people`.`role` = 'Leading Actress' )";

// TODO: The above SQL statement becomes a  JOIN between movies and movie_data
// If there is no corresponding movie_data, then show those as blanks
//NOTE: Whenever you see ., that means + in PHP

 

$result = $db->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    // Add four more rows of data which you are getting from the database
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$row["native_name"].'</td>
                                <td>'.$row["year_made"].' </span> </td>
                              

                            </tr>';
                    }//end while
                }//end if
                else {
                    echo "0 results";
                }//end else

                 $result->close();



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
