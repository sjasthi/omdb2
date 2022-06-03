<?php
   
include("./nav.php");

    $sql_trivia = mysqli_query($db,"SELECT movie_trivia_id FROM movie_trivia order by movie_trivia_id desc limit 1");
    $movie_trivia_id = "";
    if(mysqli_num_rows($sql_trivia)>0){
      if($row = mysqli_fetch_assoc($sql_trivia)){
        $movie_trivia_id = $row['movie_trivia_id'] + 1;
      }
    } else {
        $movie_trivia_id = 1;
    }

    $sql_quote = mysqli_query($db,"SELECT movie_quote_id FROM movie_quotes order by movie_quote_id desc limit 1");
    $movie_quote_id = "";
    if(mysqli_num_rows($sql_quote)>0){
      if($row = mysqli_fetch_assoc($sql_quote)){
        $movie_quote_id = $row['movie_quote_id'] + 1;
      }
    } else {
        $movie_quote_id = 1;
    }

    $sql_media = mysqli_query($db,"SELECT movie_media_id FROM movie_media order by movie_media_id desc limit 1");
    $movie_media_id = "";
    if(mysqli_num_rows($sql_media)>0){
      if($row = mysqli_fetch_assoc($sql_media)){
        $movie_media_id = $row['movie_media_id'] + 1;
      }
    } else {
        $movie_media_id = 1;
    }

    $movie_id =  mysqli_real_escape_string($db, $_POST['movie_id']);
    $anagram = $_POST['anagram'];
    $keyword = $_POST['keyword'];
    $movie_quote_name = $_POST['movie_quote_name'];
    $movie_trivia_name = $_POST['movie_trivia_name'];
    $m_link = $_POST['m_link'];
    $m_link_type = $_POST['m_link_type'];
    $language = $_POST['language'];
    $country = $_POST['country'];
    $genre = $_POST['genre'];
    $plot = $_POST['plot'];
    $tag_line = $_POST['tag_line'];


    if(!empty($language) || !empty($country) || !empty($genre) || !empty($plot) || !empty($tag_line)){
        $sql1 = "INSERT INTO movie_data(movie_id,language,country,genre,plot,tag_line) values('$movie_id','$language','$country','$genre','$plot','$tag_line')";
        mysqli_query($db, $sql1);
    }
    if(!empty($movie_trivia_name)){
        $sql2 = "INSERT INTO movie_trivia(movie_id, movie_trivia_id, movie_trivia_name) values('$movie_id', '$movie_trivia_id', '$movie_trivia_name')";
        mysqli_query($db, $sql2);
    }
    if(!empty($m_link) || !empty($m_link_type)){
        $sql3 = "INSERT INTO movie_media(movie_id,m_link,m_link_type,movie_media_id) VALUES('$movie_id','$m_link', $m_link_type, '$movie_media_id')";
        mysqli_query($db, $sql3);
    }
    if(!empty($keyword)){
        $sql4= "INSERT INTO movie_keywords(movie_id,keyword) values('$movie_id','$keyword')";
        mysqli_query($db, $sql4);
    }
    if(!empty($movie_quote_name)){
        $sql5= "INSERT INTO movie_quotes(movie_id, movie_quote_name, movie_quote_id) VALUES ('$movie_id','$movie_quote_name', '$movie_quote_id')";
        mysqli_query($db, $sql5);
    }
    if(!empty($anagram)){
        $sql6= "INSERT INTO movie_anagrams(movie_id, anagram) VALUES ('$movie_id','$anagram')";
        mysqli_query($db, $sql6);
    }

	header('location: movies.php?updated=Success');
    db_disconnect($db);
				
?>
    
