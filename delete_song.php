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

require_once("./initialize.php");


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
    $id = filter_input(INPUT_POST, 'song_id', FILTER_VALIDATE_INT);
    if (!$id || $id < 1) { security_fail(400, 'Select a valid record.'); }
    $media = db_transaction($db, function () use ($db, $id) {
        db_query($db, 'DELETE FROM songs WHERE song_id = ?', [$id]);
    });
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header('Location: songs.php?delete=Success');
    exit;
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

$stmt = db_prepare($db, "SELECT song_id, title
     FROM songs
     WHERE song_id = ?
     LIMIT 1");

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

?>

<div class="container">



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
            <?php echo h($song['title']); ?>
        </p>

        <p>
            <strong>Song ID:</strong>
            <?php echo h((int)$song_id); ?>
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
                value="<?php echo h((int)$song_id); ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?php echo h($_SESSION['csrf_token']); ?>"
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