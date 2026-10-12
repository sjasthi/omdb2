<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
?>
<?php

if (session_status() == PHP_SESSION_NONE) {
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}



$nav_selected = "SETUP";
  $left_buttons = "NO";
  $left_selected = "";

  include("./nav.php");

  $query = db_query($db, "select movie_id from movies order by movie_id desc limit 1;");
  $row = mysqli_fetch_row($query);
  $m_id = $row[0] + 1;
  $people_query = db_query($db, "select people_id from people order by people_id desc limit 1");
  $people_row = mysqli_fetch_row($people_query);
  $p_id = $people_row[0] + 1;
  $song_query = db_query($db, "select song_id from songs order by song_id desc limit 1");
  $song_row = mysqli_fetch_row($song_query);
  $s_id = $song_row[0] + 1;
  

 ?>

 <div class="right-content">
    <div class="container">

      <h3 style = "color: #01B0F1;">Setup</h3>
      <form enctype='multipart/form-data' method='post'><?php echo csrf_field(); ?>
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
if (isset($_POST['submit'])) {

    $file = $_FILES['filename'] ?? null;

    if (
        $file &&
        $file['error'] === UPLOAD_ERR_OK &&
        strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) === 'csv'
    ) {

          $table_name = $_POST['table_name'] ?? '';
          if (!is_string($table_name) || !in_array($table_name, ['movies', 'people', 'songs'], true)
              || !is_uploaded_file($file['tmp_name']) || $file['size'] > 5 * 1024 * 1024) {
              security_fail(400, 'Select a valid table and a CSV file of at most 5 MB.');
          }
						$handle=$_FILES["filename"]["tmp_name"];
						$data = array_map(function($line) {
					    return str_getcsv($line, ',');
					}, file($handle, FILE_IGNORE_NEW_LINES));
					$key = array_key_last($data);
          end($data);
					$last_index = key($data);
          if (count($data) > 10000) { security_fail(400, 'Upload at most 10,000 rows at a time.'); }
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
                $query = db_query($db, ['sql' => 'INSERT INTO movies (movie_id, native_name, english_name, year_made)
                select ?, ?,?,?
                WHERE NOT EXISTS (
                    SELECT 1
                    FROM movies
                    WHERE native_name = ? AND year_made = ?
                )
                ', 'params' => [$m_id, $data1, $data2, $data3, $data1, $data3]]);
                $m_id = $m_id + 1;
                if($query){
                    $native_update = $data1;
                    $nativeJSON = strtolower(str_replace(" ", "", $native_update));
                    $query2 = ['sql' => 'SELECT movie_id from movies where native_name = ? and english_name = ? and year_made = ?', 'params' => [$data1, $data2, $data3]];
                    
                    $result = db_query($db, $query2);
                    $row=mysqli_fetch_row($result);
                    $movie_id = intval($row[0]);
                    
                    // Default values if the external API is unavailable
$base_chars = "";
$length = 0;

// Try to get base characters
$jsonLog = "http://indic-wp.thisisjava.com/api/getBaseCharacters.php?string="
    . urlencode($nativeJSON)
    . "&language=Telugu";

$jsonfile = @file_get_contents($jsonLog, false, stream_context_create(['http' => ['timeout' => 3]]));

if ($jsonfile !== false) {
    $jsonStart = strstr($jsonfile, '{');

    if ($jsonStart !== false) {
        $decodedData = json_decode($jsonStart);

        if (
            $decodedData !== null &&
            isset($decodedData->data) &&
            is_array($decodedData->data)
        ) {
            $base_chars = implode(", ", $decodedData->data);
        }
    }
}

// Try to get string length
$jsonLength = "http://indic-wp.thisisjava.com/api/getLength.php?string="
    . urlencode($nativeJSON)
    . "&language=English";

$jsonfile = @file_get_contents($jsonLength, false, stream_context_create(['http' => ['timeout' => 3]]));

if ($jsonfile !== false) {
    $jsonStart = strstr($jsonfile, '{');

    if ($jsonStart !== false) {
        $decodedData = json_decode($jsonStart);

        if (
            $decodedData !== null &&
            isset($decodedData->data)
        ) {
            $length = intval($decodedData->data);
        }
    }
}

// Insert movie number data safely
$stmt_numbers = db_prepare($db, "INSERT INTO movie_numbers (movie_id, length, base_chars)
     VALUES (?, ?, ?)");

mysqli_stmt_bind_param(
    $stmt_numbers,
    "iis",
    $movie_id,
    $length,
    $base_chars
);

mysqli_stmt_execute($stmt_numbers);
mysqli_stmt_close($stmt_numbers);
                }
          }
          elseif ($table_name=="people") {
            $image_name = "image file name";
            $query = db_query($db, ['sql' => 'INSERT INTO people (people_id, stage_name, first_name, middle_name, last_name, gender, image_name)
            VALUES (?, ?,?,?,?,?,?)', 'params' => [$p_id, $data1, $data2, $data3, $data4, $data5, $image_name]]);
              $p_id = $p_id + 1;
          }
          else{
            $query = db_query($db, ['sql' => 'INSERT INTO songs (song_id, title, lyrics, theme)
             VALUES (?, ?,?,?)', 'params' => [$s_id, $data1, $data2, $data3]]);
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
