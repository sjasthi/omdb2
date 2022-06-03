<?php
  $nav_selected = "SONGS";
  $left_buttons = "YES";
  $left_selected = "MEDIA";

  include("./nav.php");

 ?>

 <div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Media --> Song with Media</h3>


    <br>

            <table id="info" cellpadding="0" cellspacing="0" border="0"
                class="datatable table table-striped table-bordered datatable-style table-hover"
                width="100%" style="width: 100px;">
                  <thead>
                    <tr id="table-first-row">
                            <th>id</th>
                            <th>Song Link</th>
                            <th>Song Link Type</th>
                            <th> Song </th>
                    </tr>
                  </thead>

                  <tbody>

                  <?php

  $sql = "SELECT * FROM `song_media`";
    $result = $db->query($sql);

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>'.$row["song_media_id"].'</td>
                                    <td>'.$row["s_link"].' </span> </td>
                                    <td>'.$row["s_link_type"].'</td>
                                    <td>'.$row["song_i"].'</td>'
                          ;
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
