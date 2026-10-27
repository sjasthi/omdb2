<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page=true; require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
$errors=[]; $values=[];
if (is_post_request()) {
    $values=$_POST;
    try {
        catalog_insert_song($db,$_POST);
        ui_set_notice('Song created.');
        redirect_to('songs.php?create=Success');
    } catch (InvalidArgumentException $error) { $errors[]=$error->getMessage(); http_response_code(400); }
}
$nav_selected='SONGS'; $left_buttons='YES'; $left_selected='SONGS';
include __DIR__ . '/nav.php';
ui_entity_form('song','create_song.php',$values,$errors);
include __DIR__ . '/footer.php';
