
<?php
   
include("./nav.php");


    $movie = $_POST['english_name'];
    $native = $_POST['native_name'];
    $nativeJSON = strtolower(str_replace(" ", "", $native));
    $year = $_POST['year'];
    $query = mysqli_query($db, "select movie_id from movies order by movie_id desc limit 1;");
    $row = $query->fetch_assoc();
    $id = $row['movie_id'] + 1;
    $sql1 = "INSERT INTO movies(movie_id, native_name,english_name,year_made) values('$id','$native','$movie','$year')";
      
    mysqli_query($db, $sql1);
    
    //Get movie_id in order to update movie_numbers
    $sql2 = "SELECT movie_id from movies where native_name = '$native' and english_name = '$movie' and year_made = '$year'";
    
    $result = mysqli_query($db, $sql2);
    $row=mysqli_fetch_row($result);
    $movie_id = intval($row[0]);

    //Make API call to find base_chars
    $jsonLog = "http://indic-wp.thisisjava.com/api/getBaseCharacters.php?string=".$nativeJSON."&language=Telugu";
    $jsonfile = file_get_contents($jsonLog);
    $decodedData = json_decode(strstr($jsonfile, '{'));
    $base_chars = implode(", ", $decodedData->data);
    //sort($string_array);
    //$string_array = array_map('strtolower', $string_array);
    //$base_chars = implode(", ", $string_array);
    //$base_chars = sort(strtolower(implode(", ", $decodedData->data)));

    //Make API call to find length of string for length
    $jsonLength = "http://indic-wp.thisisjava.com/api/getLength.php?string=".$nativeJSON."&language=English";
    $jsonfile= file_get_contents($jsonLength);
    $decodedData = json_decode(strstr($jsonfile, '{'));
    $length = intval($decodedData->data);

    
    //Insert movie into movie_numbers with movie_id, base_chars, and length
    $sql3 = "INSERT INTO movie_numbers(movie_id, length, base_chars) values ($movie_id, $length, '$base_chars')";
    mysqli_query($db, $sql3);
    
    db_disconnect($db);
	header('Location: movies.php?create=Success');
				
?>
