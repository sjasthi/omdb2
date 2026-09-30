<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

   
require_once('initialize.php');
    
    if (isset($_POST['movie_id'])) {

    $movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);

    if (!$movie_id) {
        db_disconnect($db);
        header('Location: movies.php');
        exit();
    }
          $english_update = trim($_POST['english_name_update'] ?? '');
$year_update = filter_input(INPUT_POST, 'year_update', FILTER_VALIDATE_INT);

$running_time_update = filter_input(INPUT_POST, 'running_time_update', FILTER_VALIDATE_INT);
$budget_update = filter_input(INPUT_POST, 'budget_update', FILTER_VALIDATE_FLOAT);
$box_office_update = filter_input(INPUT_POST, 'box_office_update', FILTER_VALIDATE_FLOAT);

$language_update = trim($_POST['language_update'] ?? '');
$country_update = trim($_POST['country_update'] ?? '');
$genre_update = trim($_POST['genre_update'] ?? '');
$plot_update = trim($_POST['plot_update'] ?? '');
$tag_line_update = trim($_POST['tag_line_update'] ?? '');
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
    $stmt = mysqli_prepare(
        $db,
        "UPDATE movies SET english_name = ?, year_made = ? WHERE movie_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "sii",
        $english_update,
        $year_update,
        $movie_id
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}
        
      if (isset($_POST['native_name_update']) && !empty($_POST['native_name_update'])) {

    $native_update = $_POST['native_name_update'];

    $stmt2 = mysqli_prepare(
        $db,
        "UPDATE movies
         SET native_name = ?, english_name = ?, year_made = ?
         WHERE movie_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt2,
        "ssii",
        $native_update,
        $english_update,
        $year_update,
        $movie_id
    );

    mysqli_stmt_execute($stmt2);
    mysqli_stmt_close($stmt2);

}


        
        //Movie_Data Update
if (
    isset($_POST['language_update']) &&
    isset($_POST['country_update']) &&
    isset($_POST['genre_update']) &&
    isset($_POST['plot_update']) &&
    isset($_POST['tag_line_update'])
) {

    // Check whether movie_data already exists for this movie
    $stmtCheck = mysqli_prepare(
        $db,
        "SELECT movie_id FROM movie_data WHERE movie_id = ?"
    );

    mysqli_stmt_bind_param($stmtCheck, "i", $movie_id);
    mysqli_stmt_execute($stmtCheck);

    $flag = mysqli_stmt_get_result($stmtCheck);
    $movieDataExists = mysqli_num_rows($flag) > 0;

    mysqli_stmt_close($stmtCheck);

    if ($movieDataExists) {

        $stmt4 = mysqli_prepare(
            $db,
            "UPDATE movie_data
             SET language = ?, country = ?, genre = ?, plot = ?, tag_line = ?
             WHERE movie_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt4,
            "sssssi",
            $language_update,
            $country_update,
            $genre_update,
            $plot_update,
            $tag_line_update,
            $movie_id
        );

    } else {

        $stmt4 = mysqli_prepare(
            $db,
            "INSERT INTO movie_data
             (movie_id, language, country, genre, plot, tag_line)
             VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt4,
            "isssss",
            $movie_id,
            $language_update,
            $country_update,
            $genre_update,
            $plot_update,
            $tag_line_update
        );
    }

    mysqli_stmt_execute($stmt4);
    mysqli_stmt_close($stmt4);
}
        
       //Movie_Numbers Update
if (
    isset($_POST['box_office_update']) &&
    isset($_POST['budget_update']) &&
    isset($_POST['running_time_update'])
) {

    $stmt5 = mysqli_prepare(
        $db,
        "UPDATE movie_numbers
         SET box_office = ?, budget = ?, running_time = ?
         WHERE movie_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt5,
        "ddii",
        $box_office_update,
        $budget_update,
        $running_time_update,
        $movie_id
    );

    mysqli_stmt_execute($stmt5);
    mysqli_stmt_close($stmt5);
}
        
       //Movie_Anagrams Update
if (!empty($anagram_id)) {

    $stmt6 = mysqli_prepare(
        $db,
        "UPDATE movie_anagrams
         SET anagram = ?
         WHERE movie_id = ? AND anagram_id = ?"
    );

    for ($i = 0; $i < sizeof($anagram_id); $i++) {

        $current_anagram = $anagram_update[$i];
        $current_anagram_id = (int)$anagram_id[$i];

        mysqli_stmt_bind_param(
            $stmt6,
            "sii",
            $current_anagram,
            $movie_id,
            $current_anagram_id
        );

        mysqli_stmt_execute($stmt6);
    }

    mysqli_stmt_close($stmt6);
}
             
       //Movie_Keywords Update
if (!empty($keyword_update)) {

    $stmt7 = mysqli_prepare(
        $db,
        "UPDATE movie_keywords
         SET keyword = ?
         WHERE movie_id = ?"
    );

    for ($i = 0; $i < sizeof($keyword_update); $i++) {

        $current_keyword = $keyword_update[$i];

        mysqli_stmt_bind_param(
            $stmt7,
            "si",
            $current_keyword,
            $movie_id
        );

        mysqli_stmt_execute($stmt7);
    }

    mysqli_stmt_close($stmt7);
}

 //Movie_Media Update
if (!empty($movie_media_id)) {

    $stmt8 = mysqli_prepare(
        $db,
        "UPDATE movie_media
         SET m_link = ?, m_link_type = ?
         WHERE movie_id = ? AND movie_media_id = ?"
    );

    for ($i = 0; $i < sizeof($movie_media_id); $i++) {

        $current_link = $m_link_update[$i];
        $current_link_type = $m_link_type_update[$i];
        $current_media_id = (int)$movie_media_id[$i];

        mysqli_stmt_bind_param(
            $stmt8,
            "ssii",
            $current_link,
            $current_link_type,
            $movie_id,
            $current_media_id
        );

        mysqli_stmt_execute($stmt8);
    }

    mysqli_stmt_close($stmt8);
}


//Movie_Quotes Update
if (!empty($movie_quote_id)) {

    $stmt9 = mysqli_prepare(
        $db,
        "UPDATE movie_quotes
         SET movie_quote_name = ?
         WHERE movie_id = ? AND movie_quote_id = ?"
    );

    for ($i = 0; $i < sizeof($movie_quote_id); $i++) {

        $current_quote = $movie_quote_name[$i];
        $current_quote_id = (int)$movie_quote_id[$i];

        mysqli_stmt_bind_param(
            $stmt9,
            "sii",
            $current_quote,
            $movie_id,
            $current_quote_id
        );

        mysqli_stmt_execute($stmt9);
    }

    mysqli_stmt_close($stmt9);
}


//Movie_Trivia Update
if (!empty($movie_trivia_id)) {

    $stmt10 = mysqli_prepare(
        $db,
        "UPDATE movie_trivia
         SET movie_trivia_name = ?
         WHERE movie_id = ? AND movie_trivia_id = ?"
    );

    for ($i = 0; $i < sizeof($movie_trivia_id); $i++) {

        $current_trivia = $movie_trivia_name[$i];
        $current_trivia_id = (int)$movie_trivia_id[$i];

        mysqli_stmt_bind_param(
            $stmt10,
            "sii",
            $current_trivia,
            $movie_id,
            $current_trivia_id
        );

        mysqli_stmt_execute($stmt10);
    }

    mysqli_stmt_close($stmt10);
}
    				
// closes: if (isset($_POST['movie_id']))
}

db_disconnect($db);
header('Location: movies.php?updated=Success');
exit();

?>
