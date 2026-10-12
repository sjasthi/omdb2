<?php
require_once __DIR__ . '/security.php';
// Compatibility functions use the current users schema and centralized write guard.
function user_exists($email, $unused = null) {
    global $db;
    return db_query($db, 'SELECT id, email, first_name, last_name, role FROM users WHERE email = ?', [$email]);
}
function login_exists($email, $password) {
    global $db;
    $result = db_query($db, 'SELECT password FROM users WHERE email = ? LIMIT 1', [$email]);
    $user = $result->fetch_assoc();
    return $user && password_verify($password, $user['password']);
}
function find_all_users() {
    global $db;
    require_admin();
    return db_query($db, 'SELECT id, first_name, last_name, email, role FROM users');
}
function find_user_by_id($id) {
    global $db;
    require_admin();
    return db_query($db, 'SELECT id, first_name, last_name, email, role FROM users WHERE id = ?', [$id])->fetch_assoc();
}
function insert_user($first, $last, $email, $password, $unused = null, $role = 'USER', $active = 0) {
    global $db;
    require_admin_write();
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8
        || !in_array($role, ['USER', 'ADMIN', 'SUPER-ADMIN'], true)) {
        security_fail(400, 'Invalid account details.');
    }
    return db_query($db, 'INSERT INTO users (first_name, last_name, email, password, role) VALUES (?, ?, ?, ?, ?)',
        [$first, $last, $email, password_hash($password, PASSWORD_DEFAULT), $role]);
}
function update_user($user) {
    global $db;
    require_admin_write();
    if (!in_array($user['role'] ?? '', ['USER', 'ADMIN', 'SUPER-ADMIN'], true)
        || !filter_var($user['email'] ?? '', FILTER_VALIDATE_EMAIL)) {
        security_fail(400, 'Invalid account details.');
    }
    return db_query($db, 'UPDATE users SET email = ?, role = ? WHERE id = ?', [$user['email'], $user['role'], $user['id']]);
}
function delete_user($id) {
    global $db;
    require_admin_write();
    return db_query($db, 'DELETE FROM users WHERE id = ?', [$id]);
}
function delete_user_old($id) { return delete_user($id); }
