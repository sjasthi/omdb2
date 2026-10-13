<?php
require_once __DIR__ . '/security.php';
function url_for($path) { return WWW_ROOT . '/' . ltrim($path, '/'); }
function u($value = '') { return urlencode((string)$value); }
function raw_u($value = '') { return rawurlencode((string)$value); }
function h($value = '') {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
function js_value($value): string {
    return json_encode($value, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR);
}
function safe_url($value): string {
    $url = trim((string)$value);
    $parts = parse_url($url);
    if (!$parts || !in_array(strtolower($parts['scheme'] ?? ''), ['http', 'https'], true)
        || empty($parts['host']) || isset($parts['user']) || isset($parts['pass'])
        || preg_match('/[\x00-\x20\x7f]/', $url)) {
        return '';
    }
    return $url;
}
function error_404() { security_fail(404, 'Page not found.'); }
function error_500() { security_fail(500, 'Unable to complete this request. Please try again later.'); }
function redirect_to($location) { header('Location: ' . $location); exit; }
function is_post_request() { return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'; }
function is_get_request() { return ($_SERVER['REQUEST_METHOD'] ?? '') === 'GET'; }
function display_errors($errors = []) {
    $output = '';
    if ($errors) {
        $output = '<div class="errors">Please fix the following errors:<ul>';
        foreach ($errors as $error) { $output .= '<li>' . h($error) . '</li>'; }
        $output .= '</ul></div>';
    }
    return $output;
}
function mangleurl($value, $type = '') {
    $url = safe_url($value);
    if ($url === '') { return ''; }
    $parts = parse_url($url);
    $host = strtolower($parts['host']);
    if (in_array($host, ['youtube.com', 'www.youtube.com', 'm.youtube.com'], true)) {
        parse_str($parts['query'] ?? '', $query);
        $id = $query['v'] ?? '';
        if (is_string($id) && preg_match('/^[A-Za-z0-9_-]{11}$/D', $id)) {
            return 'https://www.youtube.com/embed/' . $id;
        }
    }
    if ($host === 'drive.google.com' && strcasecmp($type, 'image') === 0) {
        parse_str($parts['query'] ?? '', $query);
        $id = $query['id'] ?? '';
        if (is_string($id) && preg_match('/^[A-Za-z0-9_-]+$/D', $id)) {
            return 'https://drive.google.com/thumbnail?id=' . rawurlencode($id) . '&sz=w400-h400';
        }
    }
    return $url;
}
function handlemultipleitems($item) { return explode(';', (string)$item); }
function countitems($item) {
    return count(array_filter(handlemultipleitems($item), function ($part) { return trim($part) !== ''; }));
}
function show_flash_message() {
    $message = $_SESSION['action_message'] ?? null;
    unset($_SESSION['action_message']);
    return $message;
}
