<?php
require_once __DIR__ . '/security.php';
if (is_file(__DIR__ . '/db_credentials.php')) {
    require_once __DIR__ . '/db_credentials.php';
}
foreach (['DB_SERVER', 'DB_USER', 'DB_PASS', 'DB_NAME'] as $setting) {
    if (!defined($setting)) {
        $value = getenv('OMDB_' . $setting);
        if ($value === false) {
            throw new RuntimeException('Database configuration is incomplete.');
        }
        define($setting, $value);
    }
}
function db_connect(): mysqli {
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    $connection->set_charset('utf8mb4');
    // Refresh the role so account deletion and demotion take effect immediately.
    if (is_logged_in()) {
        $stmt = $connection->prepare('SELECT email, role FROM users WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $_SESSION['user_id']);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        if (!$user) {
            $_SESSION = [];
            require_login();
        }
        $_SESSION['username'] = $user['email'];
        $_SESSION['role'] = $user['role'];
        if (!empty($GLOBALS['omdb_admin_page'])) {
            require_admin();
        }
    }
    return $connection;
}
function db_disconnect($connection): void {
    if ($connection instanceof mysqli) {
        $connection->close();
    }
}
function confirm_db_connect($connection): void {
    if (!$connection) {
        throw new RuntimeException('Database connection failed.');
    }
}
function confirm_result_set($result): void {
    if (!$result) {
        throw new RuntimeException('Database query failed.');
    }
}
function db_prepare(mysqli $connection, string $sql): mysqli_stmt {
    if (!preg_match('/^\s*(SELECT|SHOW|DESCRIBE|EXPLAIN)\b/i', $sql)) {
        require_admin_write();
    }
    return $connection->prepare($sql);
}
// Values are bound by MySQL, never interpolated into SQL text.
function db_query(mysqli $connection, $query, array $params = []) {
    if (is_array($query)) {
        $params = $query['params'];
        $query = $query['sql'];
    }
    $stmt = db_prepare($connection, $query);
    if ($params) {
        $types = '';
        foreach ($params as $param) {
            if (!is_scalar($param) && $param !== null) {
                security_fail(400, 'Invalid form value.');
            }
            $types .= is_int($param) ? 'i' : (is_float($param) ? 'd' : 's');
        }
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    return $result === false ? true : $result;
}
