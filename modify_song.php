<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page=true; require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
$id=filter_var($_POST['song_id'] ?? $_GET['song_id'] ?? null,FILTER_VALIDATE_INT);
if (!$id || $id<1) { security_fail(400,'Choose a song from the Songs list first.'); }
$row=db_query($db,'SELECT * FROM songs WHERE song_id=?',[$id])->fetch_assoc();
if (!$row) { security_fail(404,'This song is no longer in the catalog.'); }
$values=$row; $errors=[];
if (is_post_request()) {
    $values=$_POST;
    try {
        db_transaction($db,function () use ($db) { catalog_update_song($db,$_POST); });
        ui_set_notice('Song updated.'); redirect_to('songs.php?updated=Success');
    } catch (InvalidArgumentException $error) { $errors[]=$error->getMessage(); http_response_code(400); }
}
$values['song_id']=$id;
$nav_selected='SONGS'; $left_buttons='YES'; $left_selected='SONGS';
include __DIR__ . '/nav.php';
ui_entity_form('song','modify_song.php?song_id='.$id,$values,$errors,true);
include __DIR__ . '/footer.php';
