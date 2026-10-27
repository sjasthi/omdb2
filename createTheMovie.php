<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';

try {
    db_transaction($db, function () use ($db): void { catalog_insert_movie($db, $_POST); });
} catch (InvalidArgumentException $error) {
    http_response_code(400);
    $form_errors=[$error->getMessage()]; $form_values=$_POST;
    include __DIR__ . '/create_movie.php';
    exit;
}
ui_set_notice('Movie created. You can now add people, songs, and other details.');
redirect_to('movies.php?create=Success');
