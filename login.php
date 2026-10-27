<?php
require_once __DIR__ . '/initialize.php';
$error = '';
$email = '';
if (is_post_request()) {
    csrf_validate();
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    if (!is_string($email) || !is_string($password) || !filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Invalid email or password. Check both fields and try again.';
    } else {
        $email = trim($email);
        $stmt = db_prepare($db, 'SELECT id, email, password, role FROM users WHERE email = ? LIMIT 1');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $valid = false;
        $legacy = false;
        if ($user) {
            $legacy = password_get_info($user['password'])['algoName'] === 'unknown';
            $valid = $legacy ? hash_equals((string)$user['password'], $password) : password_verify($password, $user['password']);
        }
        if ($valid) {
            session_regenerate_id(true);
            $_SESSION = [
                'user_id' => (int)$user['id'],
                'username' => $user['email'],
                'role' => $user['role'],
                'csrf_token' => bin2hex(random_bytes(32)),
            ];
            // Authentication maintenance: upgrade only this authenticated account.
            // This is the sole non-admin password write, with no client-selected ID or role.
            if ($legacy || password_needs_rehash($user['password'], PASSWORD_DEFAULT)) {
                $newHash = password_hash($password, PASSWORD_DEFAULT);
                $upgrade = $db->prepare('UPDATE users SET password = ? WHERE id = ? AND password = ?');
                $upgrade->bind_param('sis', $newHash, $_SESSION['user_id'], $user['password']);
                $upgrade->execute();
                $upgrade->close();
            }
            redirect_to('index.php');
        }
        $error = 'Invalid email or password. Check both fields and try again.';
    }
}
$nav_selected = '';
$left_buttons = 'NO';
$left_selected = '';
include __DIR__ . '/nav.php';
?>
<div class="login-panel">
    <?php echo display_errors($error ? [$error] : []); ?>
    <form class="form-panel" action="login.php" method="post">
        <?php echo csrf_field(); ?>
        <label for="email">Email address</label>
        <input type="email" id="email" name="email" class="form-control" autocomplete="username" maxlength="100" value="<?php echo h(is_string($email) ? $email : ''); ?>" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-control" autocomplete="current-password" required>
        <button type="submit" class="btn btn-primary">Sign in</button>
    </form>
</div>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
