<?php
session_start();

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

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Create Movie</title>
</head>

<body>

<form id="movieCreate" action="createTheMovie.php" method="post">

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
                max="<?php echo date('Y') + 10; ?>"
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
#movieCreate {
    background-color: #ffffff;
    margin: 50px auto;
    padding: 40px;
    width: 70%;
    min-width: 300px;
}

#movieCreate input {
    padding: 10px;
    width: 100%;
    font-size: 17px;
    border: 1px solid #aaaaaa;
    box-sizing: border-box;
}

#movieCreate label {
    font-weight: bold;
}

.movie-form {
    margin-top: 20px;
}
</style>

</body>
</html>