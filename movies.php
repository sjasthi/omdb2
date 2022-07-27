<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



  $nav_selected = "MOVIES";
  $left_buttons = "YES";
  $left_selected = "MOVIES";


  include("./nav.php");


  ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Movies -> Movies List</h3>
      <?php
      if (isset($_SESSION['username']))
      { ?>
       <button title='Create Movie'><a class='btn btn-sm' href='create_movie.php'><i class = 'fa fa-plus'></i></a></button>
       <button title="Poster Upload"><a class="btn btn-sm" href="add_movie_posters.php"><i class = "fa fa-file-image-o"></i></a></button>
      <?php } ?>


<br>
<br>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width:100px;">
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

              </tbody>
        </table>


        <script type="text/javascript" language="javascript">
    $(document).ready( function () {



        $('#info').DataTable( {
            "processing" : true,
            'ajax': 'movies_ajax.php',
            "columns" : [
              {"data" : "movie_id"},
              {"data" : "native_name"},
              {"data" : "english_name"},
              {"data" : "year_made"},
            ],

            columnDefs: [
      {  targets: 4,
         render: function (data, type, row) {
            return '<a title="View" class="btn btn-info btn-sm" href="movie_info.php?movie_id='+row.movie_id+'"><i class="fa fa-eye"></i></a>'
                                 + '<a title="Modify" class="btn btn-warning btn-sm" href="modify.php?movie_id='+row.movie_id+'"><i class="fa fa-pencil"></i></a>'
                                 + '<a title="Delete" class="btn btn-danger btn-sm" href="delete_movie.php?movie_id='+row.movie_id+'"><i class="fa fa-close"></i></a>'
                                 + ' <a title="Add Song" class="btn btn-success btn-sm" href="add_song.php?movie_id='+row.movie_id+'"><i class="fa fa-music"></i></a>'
                                 + '<a title="Add People" class="btn btn-info btn-sm" href="add_people.php?movie_id='+row.movie_id+'"><i class = "fa fa-id-badge"></i></a>'
                                 + '  <a title="Create Data" class="btn btn-default btn-sm" href="create_Data.php?movie_id='+row.movie_id+'"><i class="fa fa-database"></i></a>';
         }

      }
    ],


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
            retrieve: true,

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
