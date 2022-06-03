<?php
   
include("./nav.php");
    
    if (isset($_POST['movie_id'])) {
        
          $movie_id =  mysqli_real_escape_string($db, $_POST['movie_id']);
          $english_update = $_POST['english_name_update'];
          $year_update = $_POST['year_update'];
          $running_time_update = $_POST['running_time_update'];
          $budget_update = $_POST['budget_update'];
          $box_office_update = $_POST['box_office_update'];
          $language_update = $_POST['language_update'];
          $country_update = $_POST['country_update'];
          $genre_update = $_POST['genre_update'];
          $plot_update = $_POST['plot_update'];
          $tag_line_update = $_POST['tag_line_update'];
          $anagram_update = [];
          $anagram_id = [];
          $keyword_update = [];
          $m_link_update = [];
          $m_link_type_update = [];
          $movie_media_id = [];
          $movie_quote_name = [];
          $movie_quote_id = [];
          $movie_trivia_name = [];
          $movie_trivia_id = [];
        
          foreach($_POST as $k => $v) {
              if(strpos($k, 'anagram_update') === 0) {
                  $anagram_update[] = $v;
              }
              if(strpos($k, 'anagram_id') === 0) {
                  $anagram_id[] = $v;
              }
              if(strpos($k, 'keyword_update') === 0) {
                  $keyword_update[] = $v;
              }
              if(strpos($k, 'm_link_update') === 0) {
                  $m_link_update[] = $v;
              }
              if(strpos($k, 'm_link_type_update') === 0) {
                  $m_link_type_update[] = $v;
              }
              if(strpos($k, 'movie_media_id') === 0) {
                  $movie_media_id[] = $v;
              }
              if(strpos($k, 'movie_quote_name_update') === 0) {
                  $movie_quote_name[] = $v;
              }
              if(strpos($k, 'movie_quote_id') === 0) {
                  $movie_quote_id[] = $v;
              }
              if(strpos($k, 'movie_trivia_name_update') === 0) {
                  $movie_trivia_name[] = $v;
              }
              if(strpos($k, 'movie_trivia_id') === 0) {
                  $movie_trivia_id[] = $v;
              }
          }

        
        //Movies Update
        if (isset($_POST['english_name_update'])) {
            $sql1 = "UPDATE movies SET english_name = '$english_update', year_made = '$year_update' WHERE movie_id = '$movie_id'";
            mysqli_query($db, $sql1);
        }
        
        if (isset($_POST['native_name_update']) && !empty($_POST['native_name_update'])){
            $native_update = $_POST['native_name_update'];
            $nativeJSON = strtolower(str_replace(" ", "", $native_update));
            $sql2 = "UPDATE movies SET native_name = '$native_update', english_name = '$english_update', year_made = '$year_update' WHERE movie_id = '$movie_id'";
            
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
            
            $sql3 = "UPDATE movie_numbers SET length = $length, base_chars = '$base_chars' WHERE movie_id = '$movie_id'";
            mysqli_query($db, $sql2);
            mysqli_query($db, $sql3);
        }
        
        //Movie_Data Update
        if (isset($_POST['language_update']) && isset($_POST['country_update']) && isset($_POST['genre_update']) && isset($_POST['plot_update']) && isset($_POST['tag_line_update'])) {
            
            $sqlCheck = "select * from movie_data where movie_id = $movie_id;";
            $flag = mysqli_query($db, $sqlCheck);
            
            if(mysqli_num_rows($flag) > 0){
                $sql4 = "UPDATE movie_data SET language = '$language_update', country = '$country_update', genre = '$genre_update', plot = '$plot_update', tag_line = '$tag_line_update' WHERE movie_id = '$movie_id'";
           } else {
            $sql4 = "insert into movie_data (movie_id, language, country, genre, plot, tag_line) values (".$movie_id.", '".$language_update."', '".$country_update."', '".$genre_update."', '".$plot_update."', '".$tagline_update."');";
            }
            mysqli_query($db, $sql4);
            
        }
        
        //Movie_Numbers Update
        if(isset($_POST['box_office_update']) && isset($_POST['budget_update']) && isset($_POST['running_time_update'])){
            
            $sql5= "UPDATE movie_numbers SET box_office = '$box_office_update', budget = '$budget_update', running_time = '$running_time_update' WHERE movie_id = '$movie_id'";
            mysqli_query($db, $sql5);
        }
        
        //Movie_Anagrams Update
        if (!empty($anagram_id)){
            for ($i = 0; $i < sizeof($anagram_id); $i++){
                $sql6 = "UPDATE movie_anagrams SET anagram = '$anagram_update[$i]' WHERE movie_id = '$movie_id'
                    and anagram_id = '$anagram_id[$i]'";
                mysqli_query($db, $sql6);
            }
        }
             
        //Movie_Keywords Update
        if(!empty($keyword_update)){
            for ($i = 0; $i < sizeof($keyword_update); $i++){
                $sql7= "UPDATE movie_keywords SET keyword = '$keyword_update[$i]' WHERE movie_id = '$movie_id'";
                mysqli_query($db, $sql7);
            }
        }

        //Movie_Media Update
        if(!empty($movie_media_id)){
            for ($i = 0; $i < sizeof($movie_media_id); $i++){
                $sql8 = "UPDATE movie_media SET m_link = '$m_link_update[$i]', m_link_type = '$m_link_type_update[$i]' WHERE movie_id = '$movie_id' and movie_media_id = '$movie_media_id[$i]'";
                 mysqli_query($db, $sql8);
            }
        }

        //Movie_Quotes Update
        if(!empty($movie_quote_id)){
            for ($i = 0; $i < sizeof($movie_quote_id); $i++){
                $sql9 = "UPDATE movie_quotes SET movie_quote_name = '$movie_quote_name[$i]' WHERE movie_id = '$movie_id' and movie_quote_id = '$movie_quote_name[$i]'";
                mysqli_query($db, $sql9);
            }
        }

        //Movie_Trivia Update
        if(!empty($movie_trivia_id)){
            for ($i = 0; $i < sizeof($movie_trivia_id); $i++){
                $sql10 = "UPDATE movie_trivia SET movie_trivia_name = '$movie_trivia_name[$i]' WHERE movie_id = '$movie_id' and movie_trivia_id = '$movie_trivia_id[$i]'";
                mysqli_query($db, $sql10);
            }
        }

    }
    
    db_disconnect($db);
	header('location: movies.php?updated=Success');
    				
?>
    
