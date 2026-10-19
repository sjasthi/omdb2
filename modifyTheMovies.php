<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
try {
    db_transaction($db, function () use ($db): void { catalog_update_movie($db, $_POST); });
} catch (InvalidArgumentException $error) { security_fail(400, $error->getMessage()); }
redirect_to('movies.php?updated=Success');
