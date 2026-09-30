<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// set the current page to one of the main buttons
$nav_selected = "";

// make the left menu buttons visible; options: YES, NO
$left_buttons = "NO";

// set the left menu button selected
$left_selected = "";

include("./nav.php");

?>

<html>

<head>
<style>

table.center {
    margin-left: auto;
    margin-right: auto;
}

.container {
    width: 300px;
    height: 200px;
    position: absolute;
    top: 50%;
    left: 50%;
    margin-top: -100px;
    margin-left: -150px;
}

</style>
</head>

<body>

<div class="container">

    <h1 style="color: #01B0F1;">Sign up</h1>

    <form action="sign_up.php" method="POST">

        <div class="form-group">
            <label for="fName">First Name</label>
            <input
                type="text"
                class="form-control"
                id="fName"
                name="first_name"
                placeholder="Enter first name"
                required
            >
        </div>

        <div class="form-group">
            <label for="lName">Last Name</label>
            <input
                type="text"
                class="form-control"
                id="lName"
                name="last_name"
                placeholder="Enter last name"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Email address</label>
            <input
                type="email"
                class="form-control"
                id="email"
                name="email"
                placeholder="Enter email"
                required
            >

            <small class="form-text text-muted">
                We'll never share your email with anyone else.
            </small>
        </div>

        <div class="form-group">
            <label for="password">Password</label>
            <input
                type="password"
                class="form-control"
                id="password"
                name="password"
                placeholder="Password"
                minlength="8"
                required
            >
        </div>

        <div class="form-group">
            <label for="repeat_password">Repeat Password</label>
            <input
                type="password"
                class="form-control"
                id="repeat_password"
                name="repeat_password"
                placeholder="Repeat password"
                minlength="8"
                required
            >
        </div>

        <div class="form-group form-check">
            <input
                type="checkbox"
                class="form-check-input"
                id="exampleCheck1"
            >

            <label
                class="form-check-label"
                for="exampleCheck1"
            >
                Remember me
            </label>
        </div>

        <button type="submit" class="btn btn-primary">
            Sign Up
        </button>

    </form>


<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $my_first_name = trim($_POST["first_name"] ?? '');
    $my_last_name = trim($_POST["last_name"] ?? '');
    $my_email = trim($_POST["email"] ?? '');
    $my_password = $_POST["password"] ?? '';
    $my_repeat_password = $_POST["repeat_password"] ?? '';

    $error = "";

    // First and last name are required.
    if ($my_first_name === '' || $my_last_name === '') {

        $error = "First name and last name are required.";

    }

    // Email must exist and be valid.
    elseif (
        $my_email === '' ||
        !filter_var($my_email, FILTER_VALIDATE_EMAIL)
    ) {

        $error = "Please enter a valid email address.";

    }

    // Password cannot be blank.
    elseif ($my_password === '') {

        $error = "Password is required.";

    }

    // Minimum password length.
    elseif (strlen($my_password) < 8) {

        $error = "Password must be at least 8 characters long.";

    }

    // Password confirmation must match.
    elseif ($my_password !== $my_repeat_password) {

        $error = "Passwords do not match.";

    }

    else {

        // Check for duplicate email.
        $check = $db->prepare("
            SELECT id
            FROM users
            WHERE email = ?
            LIMIT 1
        ");

        $check->bind_param("s", $my_email);
        $check->execute();

        $existing_user = $check->get_result();

        if ($existing_user->num_rows > 0) {

            $error = "An account with that email already exists.";

        } else {

            // Securely hash password.
            $password_hash = password_hash(
                $my_password,
                PASSWORD_DEFAULT
            );

            // Prepared statement prevents SQL injection.
            $stmt = $db->prepare("
                INSERT INTO users
                (
                    password,
                    email,
                    first_name,
                    last_name
                )
                VALUES (?, ?, ?, ?)
            ");

            $stmt->bind_param(
                "ssss",
                $password_hash,
                $my_email,
                $my_first_name,
                $my_last_name
            );

            if ($stmt->execute()) {

                echo "
                    <p style='color:green;'>
                        Account created successfully.
                        You can now sign in.
                    </p>
                ";

            } else {

                echo "
                    <p style='color:red;'>
                        Unable to create account.
                    </p>
                ";
            }

            $stmt->close();
        }

        $check->close();
    }

    if ($error !== '') {

        echo "<p style='color:red;'>"
            . htmlspecialchars($error)
            . "</p>";
    }
}

?>

</div>

</body>

</html>

<?php

db_disconnect($db);
include("./footer.php");

?>