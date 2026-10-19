<?php
require_once __DIR__ . '/security.php';
?>
<?php

  $nav_selected = "MOVIES"; 
  $left_buttons = "YES"; 
  $left_selected = "MOVIES"; 

    include("./nav.php");


  ?>


<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Movies -> Movies List</h3>

    <button title="Create Movie"><a class="btn btn-sm" href="create_movie.php"><i class = "fa fa-plus"></i></a></button>
    <button title="Poster Upload"><a class="btn btn-sm" href="add_movie_posters.php"><i class = "fa fa-file-image-o"></i></a></button>

<br>
<br>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" >
              <thead>
                <tr id="table-first-row">
                        <th>id</th>
                        <th>Native Name</th>
                        <th>English Name</th>
                        <th>Year </th>
                        <th> Actions </th>
                </tr>
              </thead>

              <tbody>

              <?php

$sql = "SELECT * from movies ORDER BY year_made ASC;";
$result = db_query($db, $sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>' . h($row["movie_id"]) . '</td>
                                <td>' . h($row["native_name"]) . ' </span> </td>
                                <td>' . h($row["english_name"]) . '</td>
                                <td>' . h($row["year_made"]) . '</td>
                                <td><a title="Display" class="btn btn-info btn-sm" href="movie_info.php?movie_id=' . h($row["movie_id"]) . '"><i class="fa fa-search"></i></a>
                                    <a title="Modify" class="btn btn-warning btn-sm" href="modify.php?movie_id=' . h($row["movie_id"]) . '"><i class="fa fa-pencil"></i></a>
                                    <a title="Delete" class="btn btn-danger btn-sm" href="delete_movie.php?movie_id=' . h($row["movie_id"]) . '"><i class="fa fa-close"></i></a></td>
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
