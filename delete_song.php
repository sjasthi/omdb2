<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require_once("./database.php");
$db = db_connect();

if (!$db) {
    die("Database connection failed.");
}

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
 * ---------------------------------------------------------
 * POST = actually delete the song
 * ---------------------------------------------------------
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $song_id = filter_input(
        INPUT_POST,
        'song_id',
        FILTER_VALIDATE_INT
    );

    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!$song_id || $song_id < 1) {
        db_disconnect($db);
        header("Location: songs.php");
        exit();
    }

    if (
        empty($csrf_token) ||
        !hash_equals($_SESSION['csrf_token'], $csrf_token)
    ) {
        db_disconnect($db);
        http_response_code(403);
        exit("Invalid security token.");
    }

    mysqli_begin_transaction($db);

    try {

        // Remove movie/song relationships.
        $stmt = mysqli_prepare(
            $db,
            "DELETE FROM movie_song WHERE song_id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $song_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Could not remove movie relationship.");
        }

        mysqli_stmt_close($stmt);

        // Remove people/song relationships.
        $stmt = mysqli_prepare(
            $db,
            "DELETE FROM song_people WHERE song_id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $song_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Could not remove people relationship.");
        }

        mysqli_stmt_close($stmt);

        // Delete the song.
        $stmt = mysqli_prepare(
            $db,
            "DELETE FROM songs WHERE song_id = ?"
        );

        mysqli_stmt_bind_param($stmt, "i", $song_id);

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Could not delete song.");
        }

        mysqli_stmt_close($stmt);

        mysqli_commit($db);

        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        db_disconnect($db);

        header("Location: songs.php?deleted=Success");
        exit();

    } catch (Throwable $e) {

        mysqli_rollback($db);
        db_disconnect($db);

        http_response_code(500);

        echo "<h2>Unable to Delete Song</h2>";
        echo "<p>The song could not be deleted.</p>";

        exit();
    }
}

/*
 * ---------------------------------------------------------
 * GET = confirmation page only
 * ---------------------------------------------------------
 */

$song_id = filter_input(
    INPUT_GET,
    'song_id',
    FILTER_VALIDATE_INT
);

if (!$song_id || $song_id < 1) {
    db_disconnect($db);
    header("Location: songs.php");
    exit();
}

$stmt = mysqli_prepare(
    $db,
    "SELECT song_id, title
     FROM songs
     WHERE song_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $song_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$song = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$song) {
    db_disconnect($db);
    header("Location: songs.php");
    exit();
}

$page_title = "Delete Song";
$nav_selected = "LIST";
$left_buttons = "NO";
$left_selected = "";

include("./nav.php");

function escape_html($value)
{
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<div class="container">

    <h2 style="color: darkgoldenrod;">
        Delete Song
    </h2>

    <div style="
        max-width: 600px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-top: 20px;
    ">

        <h3>
            Are you sure you want to delete this song?
        </h3>

        <p>
            <strong>Title:</strong>
            <?php echo escape_html($song['title']); ?>
        </p>

        <p>
            <strong>Song ID:</strong>
            <?php echo (int)$song_id; ?>
        </p>

        <p style="color: #b30000;">
            This action permanently deletes this song
            and its movie/person relationships.
        </p>

        <form
            method="post"
            action="delete_song.php"
            style="display: inline-block;"
        >

            <input
                type="hidden"
                name="song_id"
                value="<?php echo (int)$song_id; ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?php echo escape_html($_SESSION['csrf_token']); ?>"
            >

            <button
                type="submit"
                class="btn btn-danger"
            >
                Yes, Delete Song
            </button>

        </form>

        <a
            href="songs.php"
            class="btn btn-default"
            style="margin-left: 10px;"
        >
            Cancel
        </a>

    </div>

</div>

<?php
db_disconnect($db);
include("./footer.php");
?>