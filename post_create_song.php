<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
try { catalog_insert_song($db, $_POST); }
catch (InvalidArgumentException $error) { security_fail(400, $error->getMessage()); }
redirect_to('songs.php?create=Success');
