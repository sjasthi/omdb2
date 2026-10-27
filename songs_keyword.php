<?php
require_once __DIR__ . '/security.php';
?>
<?php
  $nav_selected = "SONGS";
  $left_buttons = "YES";
  $left_selected = "KEYWORD";

  include("./nav.php");

 ?>

 <div class="right-content">
    <div class="container">




    <br>

            <table id="info" cellpadding="0" cellspacing="0" border="0"
                class="datatable table table-striped table-bordered datatable-style table-hover"
                width="100%" >
                  <thead>
                    <tr id="table-first-row">
                            <th>Song id</th>
                            <th>Song Keyword</th>
                    </tr>
                  </thead>

                  <tbody>

                  <?php

$sql = "SELECT * FROM `song_keywords`";
    $result = db_query($db, $sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>' . h($row["song_id"]) . '</td>
                                    <td>' . h($row["keyword"]) . ' </span> </td>
                                    ';
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


    </div>
</div>

<?php
    db_disconnect($db);
    include("./footer.php");
?>
