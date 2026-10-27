<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
try {
    db_transaction($db, function () use ($db): void { catalog_update_person($db, $_POST); });
} catch (InvalidArgumentException $error) {
    http_response_code(400); $form_errors=[$error->getMessage()]; $form_values=$_POST;
    include __DIR__ . '/modify_people.php'; exit;
}
ui_set_notice('Person updated.');
redirect_to('people.php?updated=Success');
