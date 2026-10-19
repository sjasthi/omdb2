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

$page_title = 'Create Movie';

$nav_selected = "MOVIES";
$left_buttons = "YES";
$left_selected = "MOVIES";

include("./nav.php");
?>










<form id="movieCreate" action="createTheMovie.php" method="post"><?php echo csrf_field(); ?>

    <h1>Create a Movie</h1>

    <div class="movie-form">

        <p>
            <label for="native_name">Native Name</label>
            <input
                type="text"
                id="native_name"
                name="native_name"
                placeholder="Native Name"
                required
            >
        </p>

        <p>
            <label for="english_name">English Name</label>
            <input
                type="text"
                id="english_name"
                name="english_name"
                placeholder="English Name"
                required
            >
        </p>

        <p>
            <label for="year">Year</label>
            <input
                type="number"
                id="year"
                name="year"
                placeholder="Year"
                min="1888"
                max="<?php echo h(date('Y') + 10); ?>"
                required
            >
        </p>

        <button
            type="submit"
            name="submit"
            class="btn btn-primary btn-md"
        >
            Create Movie
        </button>

    </div>

</form>

<style>
#movieCreate label {
    font-weight: bold;
}

.movie-form {
    margin-top: 20px;
}
</style>



<?php include __DIR__ . '/footer.php'; ?>
