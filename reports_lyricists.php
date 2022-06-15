<?php

  $nav_selected = "PEOPLE";
  $left_buttons = "YES";
  $left_selected = "ACTORS";

  include("./nav.php");

  ?>


<div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Tom's Lyricist Page</h3>

        <h3><img src="images/trivia.png" style="max-height: 35px;" />Lyricist Page</h3>

        <form action="reports_lyricists.php" method="get">
          Lyricist's Name: <input type="text" name="name"><br>
          <input type="submit">
        </form>

        <table id="info" cellpadding="0" cellspacing="0" border="0"
            class="datatable table table-striped table-bordered datatable-style table-hover"
            width="100%" style="width: 100px;">
              <thead>
                <tr id="table-first-row">
                        <th>Native Name</th>
                        <th>Year Made</th>
                        <th>Song Title</th>





                </tr>
              </thead>

              <tbody>

              <?php

                $stage_name = $_GET['name'] ?? "";


                $sql = "SELECT `movies`.`native_name`,`movies`.`year_made`,`songs`.`title`FROM`movies`,`songs`,  `song_people`,  `people`,  `movie_song`  WHERE`song_people`.`role` = 'Lyricist' AND `people`.`stage_name` = '$stage_name' AND `movies`.`movie_id` = `movie_song`.`movie_id` AND `movie_song`.`song_id` = `songs`.`song_id` AND `song_people`.`song_id` = `songs`.`song_id` AND song_people.people_id = people.people_id";



              $result = $db->query($sql);

                if ($result->num_rows > 0) {
                    // output data of each row
                    // Add four more rows of data which you are getting from the database
                    while($row = $result->fetch_assoc()) {
                        echo '<tr>
                                <td>'.$row["native_name"].'</td>
                                <td>'.$row["year_made"].'</td>
                                <td>'.$row["title"].'</td>


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
