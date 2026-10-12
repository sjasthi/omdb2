<?php
require_once __DIR__ . '/security.php';
require_login();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') {
    csrf_validate();
    $_SESSION = [];
    $cookie = session_get_cookie_params();
    setcookie(session_name(), '', [
        'expires' => time() - 3600, 'path' => $cookie['path'], 'domain' => $cookie['domain'],
        'secure' => $cookie['secure'], 'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_destroy();
    header('Location: login.php');
    exit;
}
require_once __DIR__ . '/functions.php';
?>
<!doctype html><html><head><meta charset="utf-8"><title>Sign Out</title></head><body>
<form action="log_out.php" method="post"><?php echo csrf_field(); ?>
    <button type="submit">Sign out</button>
</form>
</body></html>
