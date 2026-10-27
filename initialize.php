<?php
require_once __DIR__ . '/security.php';
define('PRIVATE_PATH', __DIR__);
define('PROJECT_PATH', dirname(__DIR__));
define('PUBLIC_PATH', PROJECT_PATH . '/public');
define('SHARED_PATH', PRIVATE_PATH . '/shared');
define('DEPLOYMENT_URL', getenv('OMDB_DEPLOYMENT_URL') ?: '');
define('SERVER_ROOT_PATH', DEPLOYMENT_URL);
define('WWW_ROOT', rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/')), '/'));
define('DEBUG_MODE', 'OFF');
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/ui.php';
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/query_functions.php';
require_once __DIR__ . '/validation_functions.php';
$db = db_connect();
$errors = [];
$config = [];
