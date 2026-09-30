<?php
session_start();

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
$stmt = mysqli_prepare(
    $db,
    "SELECT song_id, title, lyrics, theme
     FROM songs
     WHERE song_id = ?"
);

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

    <form action="modify_song.php?song_id=<?php echo $song_id; ?>" method="POST">

        <input
            type="hidden"
            name="song_id"
            value="<?php echo htmlspecialchars($song_id); ?>"
        >

        <div class="form-group">
            <label for="title">Title</label>

            <input
                type="text"
                id="title"
                name="title"
                class="form-control"
                value="<?php echo htmlspecialchars($title); ?>"
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
            ><?php echo htmlspecialchars($lyrics); ?></textarea>
        </div>

        <br>

        <div class="form-group">
            <label for="theme">Theme</label>

            <input
                type="text"
                id="theme"
                name="theme"
                class="form-control"
                value="<?php echo htmlspecialchars($theme); ?>"
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

// Process modification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $posted_song_id = filter_input(
        INPUT_POST,
        'song_id',
        FILTER_VALIDATE_INT
    );

    $new_title = trim($_POST['title'] ?? '');
    $new_lyrics = trim($_POST['lyrics'] ?? '');
    $new_theme = trim($_POST['theme'] ?? '');

    if (!$posted_song_id || $new_title === '') {
        exit("Invalid song information.");
    }

    $update_stmt = mysqli_prepare(
        $db,
        "UPDATE songs
         SET title = ?, lyrics = ?, theme = ?
         WHERE song_id = ?"
    );

    mysqli_stmt_bind_param(
        $update_stmt,
        "sssi",
        $new_title,
        $new_lyrics,
        $new_theme,
        $posted_song_id
    );

    if (mysqli_stmt_execute($update_stmt)) {

        mysqli_stmt_close($update_stmt);
        db_disconnect($db);

        header("Location: songs.php?updated=Success");
        exit();
    }

    mysqli_stmt_close($update_stmt);

    echo "<p>Unable to update song.</p>";
}

db_disconnect($db);

include("./footer.php");
?>

</body>
</html>