<?php

  $nav_selected = "MOVIES";
  $left_buttons = "YES";
  $left_selected = "NUMBERS";

  include("./nav.php");

  ?>


<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Movies -> Movies Numbers</h3>

        <h3><img src="images/number.png" style="max-height: 35px;" />Movies Number</h3>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>Movie Id</th>
                        <th>Running Time</th>
                        <th>Base Characters</th>
                        <th>Length </th>
                        <th>Strngth</th>
                        <th>Weight</th>
                        <th>Box Office</th>




                </tr>
              </thead>

              <tbody>

              <?php

$sql = "SELECT * FROM `movie_numbers`";

// TODO: The above SQL statement becomes a  JOIN between movies and movie_data
// If there is no corresponding movie_data, then show those as blanks
//NOTE: Whenever you see ., that means + in PHP

$result = $db->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    // Add four more rows of data which you are getting from the database
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$row["movie_id"].'</td>
                                <td>'.$row["running_time"].' </span> </td>
                                <td>'.$row["base_chars"].'</td>
                                <td>'.$row["length"].'</td>
                                <td>'.$row["strength"].'</td>
                                <td>'.$row["weight"].'</td>
                                <td>'.$row["box_office"].'</td>
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
