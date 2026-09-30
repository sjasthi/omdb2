<?php

require_once('db_credentials.php');

function db_connect() {
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);

    confirm_db_connect($connection);

    // Use full UTF-8 support
    mysqli_set_charset($connection, "utf8mb4");

    return $connection;
}

function db_disconnect($connection) {
    if ($connection instanceof mysqli) {
        mysqli_close($connection);
    }
}

function confirm_db_connect($connection) {
    if (!$connection) {
        // Log the actual database error instead of displaying it to the user
        error_log(
            "Database connection failed: " .
            mysqli_connect_error() .
            " (" . mysqli_connect_errno() . ")"
        );

        exit("Database connection failed.");
    }
}

function confirm_result_set($result_set) {
    if (!$result_set) {
        // Do not expose database details in the browser
        error_log("Database query failed.");

        exit("The database query failed.");
    }
}

?>