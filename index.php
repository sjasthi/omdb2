<?php

  // set the current page to one of the main buttons
  $nav_selected = "HOME";

  // make the left menu buttons visible; options: YES, NO
  $left_buttons = "NO";

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
<div class = "container">
<h2 style = "color: #01B0F1;">Welcome to OMDB </h3>





        <?php

            $sql_movies = mysqli_query($db,"select * from movies");
            $movies_count = mysqli_num_rows($sql_movies);

            $sql_songs = mysqli_query($db,"select * from songs");
            $songs_count = mysqli_num_rows($sql_songs);

            $sql_people = mysqli_query($db,"select * from people");
            $people_count = mysqli_num_rows($sql_people);

            ?>



          


            <table class="table">
                        <thead>
                          <tr>
                        <td>Number of movies</td>
                        <td><?php echo $movies_count;?></td>
                        </tr>

                        <tr>
                      <td>Number of Songs</td>
                      <td><?php echo $songs_count;?></td>
                      </tr>

                      <tr>
                    <td>Number of People</td>
                    <td><?php echo $people_count;?></td>
                    </tr>
                        </thead>
                        <tbody>



                        </tbody>
                      </table>
            <!-- <h3>Number of movies are : <?php //echo $movies_count;?></h3>
            <h3>Number of Songs are : <?php //echo $songs_count;?></h3>
            <h3>Number of People are : <?php //echo $people_count;?></h3> -->
</div>
            <script type="text/javascript" language="javascript">
              $(document).ready(function() {

                $('#info').DataTable({
                  dom: 'lfrtBip',
                  buttons: [
                    'copy', 'excel', 'csv', 'pdf'
                  ]
                });

                $('#info thead tr').clone(true).appendTo('#info thead');
                $('#info thead tr:eq(1) th').each(function(i) {
                  var title = $(this).text();
                  $(this).html('<input type="text" placeholder="Search ' + title + '" />');

                  $('input', this).on('keyup change', function() {
                    if (table.column(i).search() !== this.value) {
                      table
                        .column(i)
                        .search(this.value)
                        .draw();
                    }
                  });
                });

                var table = $('#info').DataTable({
                  orderCellsTop: true,
                  fixedHeader: true,
                  retrieve: true
                });

              });
            </script>

            <?php
                db_disconnect($db);
                include("./footer.php");
            ?>
