<?php
require_once __DIR__ . '/security.php';
$omdb_admin_page = true;
require_admin();
require_post();
csrf_validate();
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/catalog.php';
security_fail(410, 'This older action has been retired. Use the relationship forms in Movies, People, or Songs.');
