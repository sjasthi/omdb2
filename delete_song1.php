<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
require __DIR__ . '/delete_song.php';
