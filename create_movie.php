<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page=true; require_admin();
if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST') { csrf_validate(); }
require_once __DIR__ . '/initialize.php';
$nav_selected='MOVIES'; $left_buttons='YES'; $left_selected='MOVIES';
include __DIR__ . '/nav.php';
ui_entity_form('movie','createTheMovie.php',$form_values ?? [],$form_errors ?? []);
include __DIR__ . '/footer.php';
