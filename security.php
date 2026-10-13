<?php
// Load before any output, database connection, or request processing.
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
ini_set('log_errors', '1');
ini_set('default_charset', 'UTF-8');
error_reporting(E_ALL);
// An unlimited application buffer also keeps older pages' redirects valid.
ob_start();

function security_fail(int $status, string $message): void {
    while (ob_get_level() > 0) {
        ob_end_clean();
    }
    http_response_code($status);
    header('Content-Type: text/plain; charset=utf-8');
    echo $message;
    exit;
}
set_exception_handler(function (Throwable $error): void {
    error_log('OMDB request failed: ' . $error);
    security_fail(500, 'Unable to complete this request. Please try again later.');
});
register_shutdown_function(function (): void {
    $error = error_get_last();
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        security_fail(500, 'Unable to complete this request. Please try again later.');
    }
});
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    ini_set('session.use_only_cookies', '1');
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cache-Control: no-store');
function is_logged_in(): bool {
    return isset($_SESSION['user_id'], $_SESSION['username'])
        && is_int($_SESSION['user_id']) && $_SESSION['user_id'] > 0
        && is_string($_SESSION['username']) && $_SESSION['username'] !== '';
}
function is_admin(): bool {
    return is_logged_in() && in_array($_SESSION['role'] ?? '', ['ADMIN', 'SUPER-ADMIN'], true);
}
function is_super_admin(): bool {
    return is_logged_in() && ($_SESSION['role'] ?? '') === 'SUPER-ADMIN';
}
function is_user(): bool {
    return is_logged_in() && in_array($_SESSION['role'] ?? '', ['USER', 'ADMIN', 'SUPER-ADMIN'], true);
}
function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php', true, 302);
        exit;
    }
}
function require_admin(): void {
    require_login();
    if (!is_admin()) {
        security_fail(403, 'Administrator access is required.');
    }
}
function require_post(): void {
    if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
        header('Allow: POST');
        security_fail(405, 'This action requires a POST request.');
    }
}
function csrf_token(): string {
    if (!isset($_SESSION['csrf_token']) || !is_string($_SESSION['csrf_token'])
        || strlen($_SESSION['csrf_token']) !== 64) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="'
        . htmlspecialchars(csrf_token(), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '">';
}
function csrf_validate(): void {
    $provided = $_POST['csrf_token'] ?? null;
    $expected = $_SESSION['csrf_token'] ?? null;
    if (!is_string($provided) || !is_string($expected) || strlen($provided) !== 64
        || !hash_equals($expected, $provided)) {
        security_fail(403, 'Invalid form token. Reload the form and try again.');
    }
}
function require_admin_write(): void {
    require_admin();
    require_post();
    csrf_validate();
}
// Includes are not HTTP endpoints. Apache also denies these paths.
$internal_files = ['security.php', 'initialize.php', 'database.php', 'db_credentials.php',
    'db_credentials.example.php', 'functions.php', 'user_functions.php', 'query_functions.php',
    'validation_functions.php', 'api_methods.php', 'nav.php', 'footer.php',
    'left_menu_games.php', 'left_menu_movies.php', 'left_menu_people.php',
    'left_menu_puzzles.php', 'left_menu_reports.php', 'left_menu_songs.php', 'left_search_data.php'];
if (in_array(basename($_SERVER['SCRIPT_FILENAME'] ?? ''), $internal_files, true)
    && PHP_SAPI !== 'cli') {
    security_fail(404, 'Page not found.');
}
