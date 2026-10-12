<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin_write();
require_once __DIR__ . '/initialize.php';
$movie_id = filter_input(INPUT_POST, 'movie_id', FILTER_VALIDATE_INT);
if (!$movie_id || $movie_id < 1) { security_fail(400, 'Select a valid movie.'); }
db_query($db, 'DELETE FROM movie_media WHERE movie_id = ?', [$movie_id]);
redirect_to('movie_info.php?movie_id=' . $movie_id);
