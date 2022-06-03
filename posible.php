if (isset($_GET['native_name_update'])) {
    $movie_modify = mysqli_real_escape_string($db, $_GET['native_name_update']);
      echo $GLOBALS['movie_id'];
      $sql_prueba = "UPDATE movies Set native_name ='".$movie_modify."' WHERE movie_id =".$movie_id.";";
      mysqli_query($db,$sql_prueba);
      
      
  }
  else{
      echo "no llego";
  }
				
				
				posible
