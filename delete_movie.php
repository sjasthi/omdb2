<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include_once("./database.php");
$db = db_connect();

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

    $movie_id = filter_input(
        INPUT_POST,
        'movie_id',
        FILTER_VALIDATE_INT
    );

    $csrf_token = $_POST['csrf_token'] ?? '';

    /*
     * Validate movie ID.
     */
    if (!$movie_id || $movie_id < 1) {
        db_disconnect($db);
        header("Location: movies.php");
        exit();
    }

    /*
     * Validate CSRF token.
     */
    if (
        empty($csrf_token) ||
        !hash_equals($_SESSION['csrf_token'], $csrf_token)
    ) {
        http_response_code(403);
        db_disconnect($db);
        exit("Invalid security token.");
    }

    /*
     * Collect poster/media filenames before deleting
     * the movie_media records.
     */
    $media_files = array();

    $stmt = mysqli_prepare(
        $db,
        "SELECT m_link, m_link_type
         FROM movie_media
         WHERE movie_id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $movie_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    while ($row = mysqli_fetch_assoc($result)) {
        if (
            !empty($row['m_link']) &&
            !empty($row['m_link_type'])
        ) {
            $media_files[] =
                'posters/' .
                basename($row['m_link']) .
                '.' .
                basename($row['m_link_type']);
        }
    }

    mysqli_stmt_close($stmt);

    /*
     * Begin transaction so the database deletion happens
     * as one operation.
     */
    mysqli_begin_transaction($db);

    try {

        /*
         * Delete child records first because they reference movie_id.
         */
        $tables = array(
            'movie_anagrams',
            'movie_numbers',
            'movie_people',
            'movie_data',
            'movie_media',
            'movie_keywords',
            'movie_quotes',
            'movie_song',
            'movie_trivia'
        );

        foreach ($tables as $table) {

            $stmt = mysqli_prepare(
                $db,
                "DELETE FROM `$table`
                 WHERE movie_id = ?"
            );

            if (!$stmt) {
                throw new Exception(
                    "Could not prepare delete for " . $table
                );
            }

            mysqli_stmt_bind_param(
                $stmt,
                "i",
                $movie_id
            );

            if (!mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);

                throw new Exception(
                    "Could not delete related movie data."
                );
            }

            mysqli_stmt_close($stmt);
        }

        /*
         * Delete the movie itself.
         */
        $stmt = mysqli_prepare(
            $db,
            "DELETE FROM movies
             WHERE movie_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "i",
            $movie_id
        );

        if (!mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);

            throw new Exception(
                "Could not delete movie."
            );
        }

        mysqli_stmt_close($stmt);

        /*
         * Save database changes.
         */
        mysqli_commit($db);

        /*
         * Delete poster files only after the database deletion succeeds.
         */
        foreach ($media_files as $file_name) {
            if (is_file($file_name)) {
                unlink($file_name);
            }
        }

        /*
         * Rotate the CSRF token after successful deletion.
         */
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        db_disconnect($db);

        header(
            "Location: movies.php?delete=Success"
        );
        exit();

    } catch (Throwable $e) {

        mysqli_rollback($db);
        db_disconnect($db);

        http_response_code(500);

        echo "<h2>Unable to Delete Movie</h2>";
        echo "<p>The movie could not be deleted.</p>";

        exit();
    }
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
$stmt = mysqli_prepare(
    $db,
    "SELECT native_name, english_name, year_made
     FROM movies
     WHERE movie_id = ?
     LIMIT 1"
);

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
            <?php echo escape_html($movie['native_name']); ?>
        </p>

        <p>
            <strong>English Name:</strong>
            <?php echo escape_html($movie['english_name']); ?>
        </p>

        <p>
            <strong>Year:</strong>
            <?php echo escape_html($movie['year_made']); ?>
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
                value="<?php echo (int)$movie_id; ?>"
            >

            <input
                type="hidden"
                name="csrf_token"
                value="<?php
                    echo escape_html(
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