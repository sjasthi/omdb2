<?php $page_title = 'The Cow Layer'; ?>
<?php
    $nav_selected = "MOVIES";
    $left_buttons = "YES";
    $left_selected = "NO";
    $string_delete = "";

     include("./nav.php");

    $results_per_page = 20;
    $sql = "select * from movies where movie_id not in (select movie_id from movie_media) order by movie_id desc;";
    $result = mysqli_query($db, $sql);
    $number_of_result = mysqli_num_rows($result);

    $number_of_page = ceil($number_of_result / $results_per_page);

    if (!isset ($_GET['page']) || $_GET['page'] > $number_of_page) {
        $page = 1;
    } else {
        $page = $_GET['page'];
    }

    $page_first_result = ($page-1) * $results_per_page;

    $sql2 = "SELECT * FROM movies where movie_id not in (select movie_id from movie_media) order by movie_id desc LIMIT " . $page_first_result . ',' . $results_per_page;
    $result = mysqli_query($db, $sql2);
  ?>

<!DOCTYPE html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="utf-8">
    <link rel="shortcut icon" href="/assets/favicon.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

</head>
<html>
<body >

    <form name="poster_form" action="add_movie_posters.php" method='post' id="poster_form" class="poster" enctype = "multipart/form-data" style="position:absolute;">
<?php
        if($page != 1){
            echo '<a style="position:relative; left:68em;" href = "add_movie_posters.php?page=' . $page - 1 . '">Previous    </a>';
        } else if ($page > $number_of_page){
            $page = 1;
        }
        echo '<a style="position:relative; left:68.5em;" href = "add_movie_posters.php?page=' . $page + 1 . '">Next</a><br>';
        $index = 0;
?>
    <div class="container">
        <?php
        $count = 0;
        while($row = mysqli_fetch_array($result)){
            echo '<div class="drop-zone">';
            echo '<span class="drop-zone__prompt">'.$row['native_name'].'</span>';
            echo '<input type="file" name = "'.$count.'" id="media" form="poster_form" class="drop-zone__input">';
            echo '<input type="hidden" name = "movie'.$count.'" value = "'.$row['movie_id'].'">';
            echo '<input type="hidden" name = "native'.$count.'" value = "'.$row['native_name'].'">';
            echo '<input type="hidden" name = "year'.$count.'" value = "'.$row['year_made'].'">';
            echo '</div>';
            $count++;
            
        }?>
    </div>
    </form>


<?php
$flag = 0;
$movie_id = [];
$native_name = [];
$year_made = [];
foreach($_POST as $k => $v) {
    if(strpos($k, 'movie') === 0) {
        $movie_id[] = $v;
    }
    if(strpos($k, 'native') === 0) {
        $native_name[] = $v;
    }
    if(strpos($k, 'year') === 0) {
        $year_made[] = $v;
    }
}
for($count = 0; $count < 20; $count++){
    if (is_uploaded_file($_FILES[$count]['tmp_name'])) {
        $movie_id = $movie_id[$count];
        $uploads_dir = 'posters/';
        $tmp_name = $_FILES[$count]['tmp_name'];
        $og_name = $_FILES[$count]['name'];
        $extension = end(explode('.', $og_name));
        echo $movie_id;
        $pic_name = $native_name[$count].'_'.$year_made[$count];
        if(move_uploaded_file($tmp_name, $uploads_dir.$pic_name.'.'.$extension)){
            $m_link = str_replace("'", "''", $pic_name);
            $m_link_type = $extension;
            $sql = "insert ignore into movie_media (movie_id, m_link, m_link_type, movie_media_id) values (".$movie_id.", '".$m_link."', '".$m_link_type."', $movie_id);";
            mysqli_query($db, $sql);
        } else {
            echo "Failed to Upload Images";
        }
    }
}

?>

<?php
        
    db_disconnect($db);
?>

<style type="text/css">

.container {
    display: flex;
    flex-wrap: wrap;
    padding: 5px;
}

.drop-zone {
  max-width: 200px;
  min-width: 200px;
  height: 200px;
  padding: 2px;
  display: flex;
  align-items: center;
  justify-content: center;
  text-align: center;
  font-family: "Quicksand", sans-serif;
  font-weight: 500;
  font-size: 20px;
  cursor: pointer;
  color: #000000;
  border: 4px solid #000000;
  background-color: #cccccc;
  border-radius: 10px;
  margin: 5px;
}

.drop-zone--over {
  border-color: green;
}

input {
    display:none;
}

.drop-zone__thumb {
  width: 100%;
  height: 100%;
  border-radius: 10px;
  overflow: hidden;
  background-color: #cccccc;
  background-size: cover;
  position: relative;
}

.drop-zone__thumb::after {
  content: attr(data-label);
  position: relative;
  bottom: 0;
  left: 0;
  width: 100%;
  padding: 5px 0;
  color: #ffffff;
  background: rgba(0, 0, 0, 0.75);
  font-size: 14px;
  text-align: center;
}

</style>
<script>
document.querySelectorAll(".drop-zone__input").forEach((inputElement) => {
  const dropZoneElement = inputElement.closest(".drop-zone");
                                                       $('input[type="file"]').hide();
  var count = 0;

  inputElement.addEventListener("change", (e) => {
    if (inputElement.files.length) {
        if(count == 0){
            count++;
            updateThumbnail(dropZoneElement, inputElement.files[0]);
        }
    }
  });

  dropZoneElement.addEventListener("dragover", (e) => {
    e.preventDefault();
    dropZoneElement.classList.add("drop-zone--over");
  });

  ["dragleave", "dragend"].forEach((type) => {
    dropZoneElement.addEventListener(type, (e) => {
      dropZoneElement.classList.remove("drop-zone--over");
    });
  });
                                                       
    dropZoneElement.addEventListener("drop", (e) => {
                                     e.preventDefault();
                                     console.log(e.dataTransfer.files)
                                     
                                     
                                     });
            

  dropZoneElement.addEventListener("drop", (e) => {
    e.preventDefault();

    if (e.dataTransfer.files.length) {
        if(count == 0){
          count++;
          inputElement.files = e.dataTransfer.files;
          updateThumbnail(dropZoneElement, e.dataTransfer.files[0]);
        }
    }
    

   // Prevent the default form submit
   e.preventDefault();

    dropZoneElement.classList.remove("drop-zone--over");
  });
                                                       
/**
 * Updates the thumbnail on a drop zone element.
 *
 * @param {HTMLElement} dropZoneElement
 * @param {File} file
 */
function updateThumbnail(dropZoneElement, file) {
  let thumbnailElement = dropZoneElement.querySelector(".drop-zone__thumb");
  // First time - remove the prompt
  if (dropZoneElement.querySelector(".drop-zone__prompt")) {
    var elem = dropZoneElement.querySelector(".drop-zone__prompt");
    dropZoneElement.querySelector(".drop-zone__prompt").remove();
  }

  // First time - there is no thumbnail element, so lets create it
  if (!thumbnailElement) {
    thumbnailElement = document.createElement("div");
    thumbnailElement.classList.add("drop-zone__thumb");
    dropZoneElement.appendChild(thumbnailElement);
  }
  var movie_id = dropZoneElement.firstChild.nextElementSibling.value;
  var native_name = dropZoneElement.firstChild.nextElementSibling.nextElementSibling.value;
  var year_made = dropZoneElement.firstChild.nextElementSibling.nextElementSibling.nextElementSibling.value;
  var extension = file.name.split('.').pop();
  var file_name = native_name+"_"+year_made+"."+extension;
  thumbnailElement.dataset.label = file_name;

  // Show thumbnail for image files
  if (file.type.startsWith("image/")) {
    const reader = new FileReader();

    reader.readAsDataURL(file);
    reader.onload = () => {
      thumbnailElement.style.backgroundImage = `url('${reader.result}')`;
    };
      
    const form = document.getElementById('poster_form');

     // Post data using the Fetch API
    fetch(form.action, {
        method: form.method,
        body: new FormData(form),
    });
    
    form.reset();
    var button = document.createElement('button');
    button.innerHTML = '<i class = "fa fa-times">';
    button.type = "button";
    button.style.position = "absolute";
    button.style.bottom = '0';
    button.style.left = '5px';
    button.style.color = 'black';
    thumbnailElement.appendChild(button);
    button.onclick = function () {
        deleteImage(file_name);
        deleteMovie(movie_id);
        thumbnailElement.style.backgroundImage = null;
        form.reset();
        thumbnailElement.remove();
        dropZoneElement.appendChild(elem);
        dropZoneElement.classList.remove("drop-zone--over");
        count = 0;
    };
      e.preventDefault();
  } else {
    thumbnailElement.style.backgroundImage = null;
  }
}

});



function deleteImage(file_name, movie_id)
{
    var check = confirm("Are you sure you want to delete this Image?")
    if(check == true)
    {
        $.ajax({
          url: 'delete_poster.php',
          data: {'file' : "<?php echo dirname(__FILE__) . '/posters/'?>" + file_name },
          success: function (response) {
             
              
          },
          error: function () {
               alert('Could not delete image');
          }
        });
    }
}

function deleteMovie(movie_id)
{
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.open("GET","delete_media.php?movie_id="+movie_id,true);
    xmlhttp.send();
}

</script>
</body>
</html>
