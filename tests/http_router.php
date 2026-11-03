<?php
// The CLI development server may otherwise fall back to index.php for missing URLs.
// Production Apache uses .htaccess; this router is only for the isolated HTTP suite.
if (PHP_SAPI !== 'cli-server') { http_response_code(404); exit; }
$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$path = is_string($requestPath) ? rawurldecode($requestPath) : '';
$root = realpath(dirname(__DIR__));
$file = realpath($root . $path);
if ($path === '/') { $file = $root . DIRECTORY_SEPARATOR . 'index.php'; }
if (!$file || !is_file($file) || strpos($file, $root . DIRECTORY_SEPARATOR) !== 0
    || preg_match('~^/(?:\.git|\.github|\.githooks|docs|sql|bin|tests|tools)(?:/|$)~i', $path)) {
    http_response_code(404);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Page not found.';
    return true;
}
return false;
