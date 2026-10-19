<?php
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require_once dirname(__DIR__) . '/database.php';
try {
    $db = db_connect();
    if ((int)$db->query('SELECT COUNT(*) n FROM users')->fetch_assoc()['n'] !== 0) {
        throw new RuntimeException('Bootstrap is only allowed when users is empty.');
    }
    $email = $argv[1] ?? '';
    $password = getenv('OMDB_ADMIN_PASSWORD');
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 100 || !is_string($password) || strlen($password) < 12) {
        throw new RuntimeException('Supply an email argument and OMDB_ADMIN_PASSWORD (at least 12 characters).');
    }
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $db->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, 'ADMIN')");
    $stmt->bind_param('ss', $email, $hash); $stmt->execute();
    echo "Initial administrator created. Clear OMDB_ADMIN_PASSWORD from your environment.\n";
} catch (Throwable $error) {
    fwrite(STDERR, "Administrator bootstrap failed. Check the empty database, email, and password environment variable.\n");
    exit(1);
}
