<?php
  session_start();
  $nav_selected = "SONGS";
  $left_buttons = "YES";
  $left_selected = "SONGS";

  include("./nav.php");

 ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 <div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Songs --> Songs List</h3>
    

      <?php
        if (isset($_SESSION['username']))
        { ?>
         <button title='Create Song'><a class='btn btn-sm' href='create_song.php'><i class = 'fa fa-plus'></i></a></button>
      <?php } ?>
    <br>

            <table id="info" cellpadding="0" cellspacing="0" border="0"
                class="datatable table table-striped table-bordered datatable-style table-hover"
                width="100%" style="width: 100px;">
                  <thead>
                    <tr id="table-first-row">
                            <th>id</th>
                            <th>Song Name</th>
                            <th>Lyrics</th>
                            <th>Theme</th>
                            <th> Actions </th>
                    </tr>
                  </thead>

                  <tbody>

                  <?php

    $sql = "SELECT * from songs ORDER BY song_id;";
    $result = $db->query($sql);
    if(isset($_GET['updated'])){
           if($_GET['updated'] == "Success"){
               echo '<br><h3 style="color:orange;">Success! The song has been updated.</h3>';
           }
    }
    if(isset($_GET['create'])){
           if($_GET['create'] == "Success"){
               echo '<br><h3 style="color:#01B0F1;">Success! The song has been added.</h3>';
           }
    }
    if(isset($_GET['delete'])){
           if($_GET['delete'] == "Success"){
               echo '<br><h3 style="color:#FF0000;">Success! The song has been deleted.</h3>';
           }
    }

                    if ($result->num_rows > 0) {
                        // output data of each row
                        while($row = $result->fetch_assoc()) {
                            echo '<tr>
                                    <td>'.$row["song_id"].'</td>
                                    <td>'.$row["title"].' </span> </td>
                                    <td>'.$row["lyrics"].'</td>
                                    <td>'.$row["theme"].'</td>
                                    <td><a title="View" class="btn btn-info btn-sm" href="song_info.php?song_id='.$row["song_id"].'"><i class="fa fa-eye"></i></a>
                                        <a title="Modify" class="btn btn-warning btn-sm" href="modify.php?song_id='.$row["song_id"].'"><i class="fa fa-pencil"></i></a>
                                        <a title="Delete" class="btn btn-danger btn-sm" href="delete_song1.php?song_id='.$row["song_id"].'"><i class="fa fa-close"></i></a>
                                        <a title="More Info" class="btn btn-success btn-sm" href="songs_more_info.php?song_id='.$row["song_id"].'"><i class="fa fa-database"></i></a>
                                        <a title="Add People" class="btn btn-info btn-sm" href="songs_people_list.php?song_id='.$row["song_id"].'"><i class = "fa fa-id-badge"></i></a>
                                        </td>
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


    </div>
</div>

<?php
    db_disconnect($db);
    include("./footer.php");
?>
