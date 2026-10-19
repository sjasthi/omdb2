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

/*
 * Create a CSRF token for delete confirmations.
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
 * ---------------------------------------------------------
 * POST = actually delete the movie
 * ---------------------------------------------------------
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
    if (!$id || $id < 1) { security_fail(400, 'Select a valid record.'); }
    $media = db_transaction($db, function () use ($db, $id) {
        $media = db_query($db, 'SELECT m_link, m_link_type FROM movie_media WHERE movie_id = ? FOR UPDATE', [$id])->fetch_all(MYSQLI_ASSOC);
            db_query($db, 'DELETE FROM movies WHERE movie_id = ?', [$id]);
            return $media;
    });
    // Filesystem changes happen only after the database commit.
    foreach ($media as $row) {
        $file = __DIR__ . '/posters/' . basename($row['m_link']) . '.' . basename($row['m_link_type']);
        $resolved = realpath($file);
        if ($resolved !== false && dirname($resolved) === realpath(__DIR__ . '/posters') && !is_link($file)) {
            if (!@unlink($resolved)) { error_log('OMDB poster cleanup failed after movie deletion.'); }
        }
    }
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    header('Location: movies.php?delete=Success');
    exit;
}

/*
 * ---------------------------------------------------------
 * GET = show confirmation page only
 * ---------------------------------------------------------
 */

$movie_id = filter_input(
    INPUT_GET,
    'movie_id',
    FILTER_VALIDATE_INT
);

if (!$movie_id || $movie_id < 1) {
    db_disconnect($db);
    header("Location: movies.php");
    exit();
}

/*
 * Make sure the movie exists and get its name.
 */
$stmt = db_prepare($db, "SELECT native_name, english_name, year_made
     FROM movies
     WHERE movie_id = ?
     LIMIT 1");

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $movie_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$movie = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$movie) {
    db_disconnect($db);
    header("Location: movies.php");
    exit();
}

/*
 * Navigation settings.
 */
$page_title = 'Delete Movie';
$nav_selected = "LIST";
$left_buttons = "NO";
$left_selected = "";

include("./nav.php");

?>

<div class="container">

    <h2 style="color: darkgoldenrod;">
        Delete Movie
    </h2>

    <div
        style="
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 20px;
        "
    >

        <h3>
            Are you sure you want to delete this movie?
        </h3>

        <p>
            <strong>Native Name:</strong>
            <?php echo h($movie['native_name']); ?>
        </p>

        <p>
            <strong>English Name:</strong>
            <?php echo h($movie['english_name']); ?>
        </p>

        <p>
            <strong>Year:</strong>
            <?php echo h($movie['year_made']); ?>
        </p>

        <p style="color: #b30000;">
            This action will permanently remove the movie
            and its related database records.
        </p>

        <form
            method="post"
            action="delete_movie.php"
            style="display: inline-block;"
        >

            <input
                type="hidden"
                name="movie_id"
                value="<?php echo h((int)$movie_id); ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?php
                    echo h(
                        $_SESSION['csrf_token']
                    );
                ?>"
            >

            <button
                type="submit"
                class="btn btn-danger"
            >
                Yes, Delete Movie
            </button>

        </form>

        <a
            href="movies.php"
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