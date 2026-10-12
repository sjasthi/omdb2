<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
?>
<?php
          include(".\nav.php");
        if(isset($_POST['submit'])){
          $title = $_POST['title'];
          $lyrics = $_POST['lyrics'];
          $theme = $_POST['theme'];
          $a = ['sql' => 'INSERT INTO `songs`(`title`, `lyrics`, `theme`) VALUES (?,?,?)', 'params' => [$title, $lyrics, $theme]];
          $query = db_query($db, $a);
          db_disconnect($db);
          header('Location: songs.php?create=Success');

        }
?>

