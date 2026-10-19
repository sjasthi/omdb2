<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
?>
<?php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$page_title = "Modify Song";

$nav_selected = "SONGS";
$left_buttons = "YES";
$left_selected = "SONGS";

include("./nav.php");

// Validate song ID
$song_id = filter_input(INPUT_GET, 'song_id', FILTER_VALIDATE_INT);

if (!$song_id) {
    header("Location: songs.php");
    exit();
}

// Get existing song
$stmt = db_prepare($db, "SELECT song_id, title, lyrics, theme
     FROM songs
     WHERE song_id = ?");

mysqli_stmt_bind_param($stmt, "i", $song_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if (!$row = mysqli_fetch_assoc($result)) {
    mysqli_stmt_close($stmt);
    db_disconnect($db);

    header("Location: songs.php");
    exit();
}

$title = $row['title'];
$lyrics = $row['lyrics'];
$theme = $row['theme'];

mysqli_stmt_close($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Modify Song</title>
</head>

<body>

<div class="container">

    <h1>Modify Song</h1>

    <form action="modify_song.php?song_id=<?php echo h($song_id); ?>" method="POST"><?php echo csrf_field(); ?>

        <input
            type="hidden"
            name="song_id"
            value="<?php echo h($song_id); ?>"
        >

        <div class="form-group">
            <label for="title">Title</label>

            <input
                type="text"
                id="title"
                name="title"
                class="form-control"
                value="<?php echo h($title); ?>"
                required
            >
        </div>

        <br>

        <div class="form-group">
            <label for="lyrics">Lyrics</label>

            <textarea
                id="lyrics"
                name="lyrics"
                class="form-control"
                rows="6"
            ><?php echo h($lyrics); ?></textarea>
        </div>

        <br>

        <div class="form-group">
            <label for="theme">Theme</label>

            <input
                type="text"
                id="theme"
                name="theme"
                class="form-control"
                value="<?php echo h($theme); ?>"
            >
        </div>

        <br>

        <button
            type="submit"
            name="submit"
            class="btn btn-primary"
        >
            Modify Song
        </button>

    </form>

</div>

<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once __DIR__ . '/catalog.php';
    try { db_transaction($db, function () use ($db): void { catalog_update_song($db, $_POST); }); }
    catch (InvalidArgumentException $error) { security_fail(400, $error->getMessage()); }
    redirect_to('songs.php?updated=Success');
}

db_disconnect($db);

include("./footer.php");
?>

</body>
</html>