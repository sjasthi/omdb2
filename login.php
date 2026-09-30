

<?php

  // set the current page to one of the main buttons
  $nav_selected = "";

  // make the left menu buttons visible; options: YES, NO
  $left_buttons = "NO";

  // set the left menu button selected; options will change based on the main selection
  $left_selected = "";
  include("./nav.php");
?>

<html>

<head>
<style>
  table.center {
      margin-left:auto;
      margin-right:auto;
    }

    .container{
      width: 300px;           /* Set this to your convenience */
      height: 200px;          /* Set this to your convenience */
      position: absolute;
      top: 50%;
      left: 50%;
      margin-top: -100px;     /* Half of height */
      margin-left: -150px;
  }​

</style>
</head>

<body>
<div class="container">
<h2 style = "color: #01B0F1;">Sign In </h3>


      <form action="login.php" method="post">
        <!-- Email input -->
        <div class="form-outline mb-4">
          <input type="email" id="email" name="email" class="form-control" />
          <label class="form-label" for="email_label">Email address</label>
        </div>

        <!-- Password input -->
        <div class="form-outline mb-4">
          <input type="password" id="password" name="password" class="form-control" />
          <label class="form-label" for="password_label">Password</label>
        </div>

        <!-- 2 column grid layout for inline styling -->
        <div class="row mb-4">
          <div class="col d-flex justify-content-center">
            <!-- Checkbox -->
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="" id="checkbox" checked />
              <label class="form-check-label" for="remember_label"> Remember me </label>
            </div>
          </div>
        </div>

        <!-- Submit button -->
        <button type="submit" class="btn btn-primary btn-block mb-4">Sign in</button>

        <div class="col">
          <!-- Simple link -->
          <a href="#">Forgot password?</a>
          or
          <a href="sign_up.php">Not signed up?</a>
        </div>

      </form>
</div>




<?php
 if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $my_email = trim($_POST['email'] ?? '');
    $my_password = $_POST['password'] ?? '';

    // Do not allow blank or invalid credentials.
    if (
        $my_email === '' ||
        $my_password === '' ||
        !filter_var($my_email, FILTER_VALIDATE_EMAIL)
    ) {
        echo "<p style='color:red;'>Invalid email or password.</p>";
    } else {

        // Prepared statement prevents SQL injection.
        $stmt = $db->prepare("
           SELECT id, email, password, role
FROM users
WHERE email = ?
LIMIT 1
        ");

        $stmt->bind_param("s", $my_email);
        $stmt->execute();

        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $login_valid = false;

        if ($user) {

            $stored_password = $user['password'];

            // Support newer hashed passwords.
            $password_info = password_get_info($stored_password);

if ($password_info['algoName'] !== 'unknown') {
                $login_valid = password_verify(
                    $my_password,
                    $stored_password
                );
            } else {
                // Temporary support for existing plaintext accounts.
                $login_valid = hash_equals(
                    (string)$stored_password,
                    (string)$my_password
                );

                // Automatically upgrade an old plaintext password
                // to a secure hash after a successful login.
                if ($login_valid) {

                    $new_hash = password_hash(
                        $my_password,
                        PASSWORD_DEFAULT
                    );

                    $update = $db->prepare(
                        "UPDATE users SET password = ? WHERE id = ?"
                    );

                    $update->bind_param(
                        "si",
                        $new_hash,
                        $user['id']
                    );

                    $update->execute();
                    $update->close();
                }
            }
        }

        if ($login_valid) {

            session_regenerate_id(true);

            $_SESSION['username'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            header("Location: index.php");
            exit();

        } else {

            echo "<p style='color:red;'>Invalid email or password.</p>";
        }

        $stmt->close();
    }
}
    


    db_disconnect($db);
    include("./footer.php");
?>