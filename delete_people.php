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

/*
 * Create CSRF token.
 */
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/*
 * ---------------------------------------------------------
 * POST = actually delete the person
 * ---------------------------------------------------------
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $people_id = filter_input(
        INPUT_POST,
        'people_id',
        FILTER_VALIDATE_INT
    );

    $csrf_token = $_POST['csrf_token'] ?? '';

    if (!$people_id || $people_id < 1) {
        db_disconnect($db);
        header("Location: people.php");
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

    $stmt = mysqli_prepare(
        $db,
        "DELETE FROM people WHERE people_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $people_id
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        // Rotate token after successful deletion.
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        db_disconnect($db);

        header("Location: people.php?delete=Success");
        exit();
    }

    mysqli_stmt_close($stmt);
    db_disconnect($db);

    header("Location: people.php?delete=Failed");
    exit();
}

/*
 * ---------------------------------------------------------
 * GET = confirmation page only
 * ---------------------------------------------------------
 */

$people_id = filter_input(
    INPUT_GET,
    'people_id',
    FILTER_VALIDATE_INT
);

if (!$people_id || $people_id < 1) {
    db_disconnect($db);
    header("Location: people.php");
    exit();
}

/*
 * Load the person so we can show who is being deleted.
 */
$stmt = mysqli_prepare(
    $db,
    "SELECT
        people_id,
        stage_name,
        first_name,
        middle_name,
        last_name
     FROM people
     WHERE people_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $people_id
);

mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$person = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$person) {
    db_disconnect($db);
    header("Location: people.php");
    exit();
}

$page_title = "Delete Person";
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

$display_name = trim(
    $person['stage_name'] !== ''
        ? $person['stage_name']
        : $person['first_name'] . ' ' .
          $person['middle_name'] . ' ' .
          $person['last_name']
);
?>

<div class="container">

    <h2 style="color: darkgoldenrod;">
        Delete Person
    </h2>

    <div style="
        max-width: 600px;
        padding: 20px;
        border: 1px solid #ddd;
        border-radius: 6px;
        margin-top: 20px;
    ">

        <h3>
            Are you sure you want to delete this person?
        </h3>

        <p>
            <strong>Name:</strong>
            <?php echo escape_html($display_name); ?>
        </p>

        <p>
            <strong>People ID:</strong>
            <?php echo (int)$people_id; ?>
        </p>

        <p style="color: #b30000;">
            This action permanently deletes this person.
        </p>

        <form
            method="post"
            action="delete_people.php"
            style="display: inline-block;"
        >

            <input
                type="hidden"
                name="people_id"
                value="<?php echo (int)$people_id; ?>"
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
                Yes, Delete Person
            </button>

        </form>

        <a
            href="people.php"
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