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
$nav_selected = '';
$left_buttons = 'NO';
$left_selected = '';
include __DIR__ . '/nav.php';
?>
<h2>Sign out</h2>
<form action="log_out.php" method="post"><?php echo csrf_field(); ?>
    <button type="submit" class="btn btn-primary">Sign out</button>
</form>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
