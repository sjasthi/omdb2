<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page=true; require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
$id=filter_var($_GET['people_id'] ?? $_POST['id'] ?? null,FILTER_VALIDATE_INT);
if (!$id || $id<1) { security_fail(400,'Choose a person from the People list first.'); }
$row=db_query($db,'SELECT * FROM people WHERE people_id=?',[$id])->fetch_assoc();
if (!$row) { security_fail(404,'This person is no longer in the catalog.'); }
$values=$form_values ?? $row; $values['id']=$id;
$nav_selected='PEOPLE'; $left_buttons='YES'; $left_selected='PEOPLE';
include __DIR__ . '/nav.php';
ui_entity_form('person','post_modify_people.php',$values,$form_errors ?? [],true);
include __DIR__ . '/footer.php';
