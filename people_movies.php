<?php

  $nav_selected = "PEOPLE";
  $left_buttons = "YES"; 
  $left_selected = "MOVIES";

  include("./nav.php");

  ?>


<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Movies -> Movies List with Songs</h3>

        <h3><img src="images/movies.png" style="max-height: 35px;" />Movies List with Songs</h3>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>id</th>
                        <th>Local Name</th>
                        <th>People ID</th>
                        <th>Role</th>
                        <th>Screen Name</th>
                        <th>Fisrt Name </th>
                        <th>Last Name </th>
                        <th>Gender</th>

                        <!-- TODO: Instead of these four columns, we now have to show the following columns in Iteration 6
                        id, 
                        native_name, 
                        english_name, 
                        year, 
                        title (song)
                        country, 
                        genre, 
                        plot (show the first 30 characters) -->

                </tr>
              </thead>

              <tbody>

              <?php

$sql = "SELECT movies.`movie_id`, `native_name`, `first_name`, `last_name`, `role`, `screen_name`, `gender`, `people_id` AS `id` FROM `movies` INNER JOIN `movie_people` ON `movie_people`.`movie_id`= `movies`.`movie_id`INNER JOIN `people` ON `people`.`id`= `movie_people`.`people_id`";

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
                                <td>'.$row["native_name"].' </span> </td>
                                <td>'.$row["id"].'</td>
                                <td>'.$row["role"].'</td>
                                <td>'.$row["screen_name"].'</td>
                                <td>'.$row["first_name"].'</td>
                                <td>'.$row["last_name"].'</td>
                                <td>'.$row["gender"].'</td>
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
