<?php
  $nav_selected = "SETUP";
  $left_buttons = "NO";
  $left_selected = "";

  include("./nav.php");

  $query = mysqli_query($db,"select movie_id from movies order by movie_id desc limit 1;");
  $row = mysqli_fetch_row($query);
  $m_id = $row[0] + 1;
  $people_query = mysqli_query($db,"select people_id from people order by people_id desc limit 1");
  $people_row = mysqli_fetch_row($people_query);
  $p_id = $people_row[0] + 1;
  $song_query = mysqli_query($db,"select song_id from songs order by song_id desc limit 1");
  $song_row = mysqli_fetch_row($song_query);
  $s_id = $song_row[0] + 1;
  

 ?>

 <div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Setup</h3>
      <form enctype='multipart/form-data' method='post'>
        <label>Select Table name</label>
        <Select name="table_name" required class="form-control">
          <option value="" disabled>Select Table name</option>
          <option value="people">People Table</option>
          <option value="movies">Movies Table</option>
          <option value="songs">Songs Table</option>
        </select>
        <br>



      <label>Upload CSV file Here</label>

      <p><i><b>Note :</b> CSV file must have 3 columns -<br>
      1 - Native name<br>
      2 - English Name<br>
      3 - Year Made<br>
      --------------------<br>
      People<br>
      1 - Stage Name<br>
      2 - First Name<br>
      3 - Middle Name<br>
      4 - Last name<br>
      5 - Gender<br>
      ---------------------<br>
      Song<br>
      1 - Title<br>
      2 - Lyrics<br>
      3 - Theme</i></p>

      <input size='50' type='file' name='filename'>
      </br>
      <input type='submit' name='submit' value='Upload Products'>

      </form>

    </div>
</div>

<?php include("./footer.php"); ?>

<?php
if(isset($_POST['submit'])){
  $file = $_FILES['filename'];

if( 'text/csv' == $file['type'] ||  'application/vnd.ms-excel' == $file['type'] ) {

          $table_name = $_POST['table_name'];
						$handle=$_FILES["filename"]["tmp_name"];
						$data = array_map(function($line) {
					    return str_getcsv($line, ',');
					}, file($handle, FILE_IGNORE_NEW_LINES));
					$key = array_key_last($data);
          end($data);
					$last_index = key($data);
          for($i = 0; $i<=$last_index ; $i++){
            $data1 = "";
            $data2 = "";
            $data3 = "";
            $data4 = "";
            $data5 = "";
            if(!empty($data[$i][0])){$data1 = $data[$i][0];}
            if(!empty($data[$i][1])){$data2 = $data[$i][1];}
            if(!empty($data[$i][2])){$data3 = $data[$i][2];}
            if(!empty($data[$i][3])){$data4 = $data[$i][3];}
            if(!empty($data[$i][4])){$data5 = $data[$i][4];}
            if($table_name=="movies"){
                $query = mysqli_query($db,"INSERT INTO movies (movie_id, native_name, english_name, year_made)
                select '$m_id', '$data1','$data2','$data3'
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM movies
                    WHERE native_name = '$data1' AND year_made = '$data3'
                )
                ");
                $m_id = $m_id + 1;
                if($query){
                    $native_update = $data1;
                    $nativeJSON = strtolower(str_replace(" ", "", $native_update));
                    $query2 = "SELECT movie_id from movies where native_name = '$data1' and english_name = '$data2' and year_made = '$data3'";
                    
                    $result = mysqli_query($db, $query2);
                    $row=mysqli_fetch_row($result);
                    $movie_id = intval($row[0]);
                    
                    //Make API call to find base_chars
                    $jsonLog = "http://indic-wp.thisisjava.com/api/getBaseCharacters.php?string=".$nativeJSON."&language=Telugu";
                    $jsonfile = file_get_contents($jsonLog);
                    $decodedData = json_decode(strstr($jsonfile, '{'));
                    $base_chars = implode(", ", $decodedData->data);
                    
                    //Make API call to find length of string for length
                    $jsonLength = "http://indic-wp.thisisjava.com/api/getLength.php?string=".$nativeJSON."&language=English";
                    $jsonfile= file_get_contents($jsonLength);
                    $decodedData = json_decode(strstr($jsonfile, '{'));
                    $length = intval($decodedData->data);
                    
                    $query3 = "INSERT INTO movie_numbers(movie_id, length, base_chars) values ($movie_id, $length, '$base_chars')";
                    
                    mysqli_query($db, $query3);
                }
          }
          elseif ($table_name=="people") {
            $image_name = "image file name";
            $query = mysqli_query($db,"INSERT INTO people (people_id, stage_name, first_name, middle_name, last_name, gender, image_name)
            VALUES ('$p_id', '$data1','$data2','$data3','$data4','$data5','$image_name')");
              $p_id = $p_id + 1;
          }
          else{
            $query = mysqli_query($db,"INSERT INTO songs (song_id, title, lyrics, theme)
             VALUES ('$s_id', '$data1','$data2','$data3')");
              $s_id = $s_id + 1;
          }

          }
          echo '<script language="javascript">';
          echo 'alert("Successfully Imported")';
          echo '</script>';
        }
        else{
          echo '<script language="javascript">';
          echo 'alert("Kindly Upload a CSV file")';
          echo '</script>';
        }

      }
          ?>
<?php
    db_disconnect($db);
    include("./footer.php");
?>
