<?php
  session_start();

  $nav_selected = "PEOPLE";
  $left_buttons = "YES";
  $left_selected = "PEOPLE";

  include("./nav.php");

  ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">People -> People List</h3>

        <h3><img src="images/people.png" style="max-height: 35px;" />People List</h3>


          <?php
            if(isset($_SESSION['username'])){
              ?>
            
          <button title="Create Person"><a class="btn btn-sm" href="create_people.php"><i class = "fa fa-plus"></i></a></button>
          <br><br>
        <?php } ?>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>id</th>
                        <th>Screen Name </th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Gender</th>
                        <th>Image </th>
                        <th>Action</th>
                </tr>
              </thead>
              <tbody>

              <?php

$sql = "SELECT * from people ORDER BY first_name ASC;";
$result = $db->query($sql);
                if(isset($_GET['create'])){
                       if($_GET["create"] == "Success"){
                           echo '<br><h3 style="color:#01B0F1;">Success! The person has been added.</h3>';
                       }
                }
                if(isset($_GET['updated'])){
                       if($_GET['updated'] == "Success"){
                           echo '<br><h3 style="color:orange;">Success! The person has been updated.</h3>';
                       }
                }
                if(isset($_GET['delete'])){
                       if($_GET['delete'] == "Success"){
                           echo '<br><h3 style="color:#FF0000;">Success! The person has been deleted.</h3>';
                       }
                }

                if ($result->num_rows > 0) {
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$row["people_id"].'</td>
                                <td>'.$row["stage_name"].' </span> </td>
                                <td>'.$row["first_name"].'</td>
                                <td>'.$row["middle_name"].'</td>
                                <td>'.$row["last_name"].'</td>
                                <td>'.$row["gender"].'</td>
                                <td>'.$row["image_name"].'</td>
                                <td><a title="View Person" class="btn btn-info btn-sm" href="people_info.php?people_id='.$row["people_id"].'"><i class="fa fa-eye"></i></a>
                                <a title="Modify Person" class="btn btn-warning btn-sm" href="modify.php?people_id='.$row["people_id"].'"><i class="fa fa-pencil"></i></a>
                                <a title="Delete Person"class="btn btn-danger btn-sm" href="delete_people.php?people_id='.$row["people_id"].'"><i class="fa fa-close"></i></a>
                                <a title="More Info" class="btn btn-success btn-sm" href="people_more_info.php?people_id='.$row["people_id"].'"><i class="fa fa-database"></i></a>

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

<?php
    db_disconnect($db);
    include("./footer.php");
?>
