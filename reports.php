<?php

  // set the current page to one of the main buttons
  $nav_selected = "REPORTS";

  // make the left menu buttons visible; options: YES, NO
  $left_buttons = "YES";

  // set the left menu button selected; options will change based on the main selection
  $left_selected = "";
  include("./nav.php");
?>

<html>

<head>
<style>
table.center {
    margin-left:auto;
    margin-right:auto;
  }
</style>
</head>

<body>
<div class="container">
<h2 style = "color: #01B0F1;">Year Made Count </h3>



  <table id="info" cellpadding="0" cellspacing="0" border="0"
      class="datatable table table-striped table-bordered datatable-style table-hover"
      width="100%" style="width: 100px;">

  <thead>
      <tr id="table-first-row">
        <th scope="col">#</th>
        <th scope="col">Year Made</th>
        <th scope="col">Movies Count</th>
        <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>

        <?php
        // $query = "select count(year_made), year_made from movies GROUP BY year_made";
        $query = mysqli_query($db,"select count(year_made), year_made from movies GROUP BY year_made");
        $count = 0;
        while($row = mysqli_fetch_assoc($query)){
          $count++;
          ?>

          <tr>
         <th scope="row"><?php echo $count; ?></th>
         <td><?php echo $row['year_made']; ?></td>
         <td><?php echo $row['count(year_made)']; ?></td>
         <td><a href="movies.php?id=<?php echo $row['year_made']; ?>">View all movies</a></td>
        </tr>


          <?php }
            ?>


                    <script type="text/javascript" language="javascript">
                $(document).ready( function () {

                    $('#info').DataTable( {
                        "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
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
