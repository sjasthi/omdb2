<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

include("./nav.php");

if (isset($_POST['submit'])) {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    $stage_name  = trim($_POST['stage_name'] ?? '');
    $first_name  = trim($_POST['first_name'] ?? '');
    $middle_name = trim($_POST['middle_name'] ?? '');
    $last_name   = trim($_POST['last_name'] ?? '');
    $gender      = trim($_POST['gender'] ?? '');

    if (!$id) {
        db_disconnect($db);
        header("Location: people.php");
        exit();
    }

    $stmt = mysqli_prepare(
        $db,
        "UPDATE people
         SET stage_name = ?,
             first_name = ?,
             middle_name = ?,
             last_name = ?,
             gender = ?
         WHERE people_id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "sssssi",
        $stage_name,
        $first_name,
        $middle_name,
        $last_name,
        $gender,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        db_disconnect($db);

        header("Location: people.php?updated=Success");
        exit();
    }

    mysqli_stmt_close($stmt);
}

db_disconnect($db);
header("Location: people.php");
exit();