<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin_write();
require_once __DIR__ . '/initialize.php';
// Identify a stored media record, never an arbitrary client-supplied filesystem path.
$id = filter_input(INPUT_POST, 'movie_media_id', FILTER_VALIDATE_INT);
if (!$id || $id < 1) { security_fail(400, 'Select a valid poster.'); }
$record = db_query($db, 'SELECT movie_id, m_link, m_link_type FROM movie_media WHERE movie_media_id = ? LIMIT 1', [$id])->fetch_assoc();
if (!$record) { security_fail(404, 'Poster not found.'); }
$name = (string)$record['m_link'];
$type = strtolower((string)$record['m_link_type']);
if ($name === '' || basename($name) !== $name || strpbrk($name, "/\\") !== false
    || !in_array($type, ['jpg', 'jpeg', 'png', 'webp'], true)) {
    security_fail(400, 'Invalid poster record.');
}
$directory = realpath(__DIR__ . '/posters');
$target = realpath(__DIR__ . '/posters/' . $name . '.' . $type);
if ($target !== false && ($directory === false || dirname($target) !== $directory || is_link($target))) {
    security_fail(400, 'Invalid poster record.');
}
db_query($db, 'DELETE FROM movie_media WHERE movie_media_id = ? AND movie_id = ?', [$id, (int)$record['movie_id']]);
if ($target !== false && !unlink($target)) {
    error_log('OMDB poster file could not be removed.');
}
redirect_to('movie_info.php?movie_id=' . (int)$record['movie_id']);
