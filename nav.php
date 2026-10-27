<?php
require_once __DIR__ . '/initialize.php';
$nav_selected = $nav_selected ?? '';
$left_selected = $left_selected ?? '';
$left_buttons = $left_buttons ?? 'NO';
$document_title = ui_page_info()[0] . ' | OMDB';
$navigation = [
    ['HOME','index.php','home.png','Home'], ['MOVIES','movies.php','movies.png','Movies'],
    ['PEOPLE','people.php','people.png','People'], ['SONGS','songs.php','songs.png','Songs'],
    ['REPORTS','reports.php','reports.png','Reports'], ['search','advance_movie_search.php','search.png','Search'],
    ['SETUP','setup.php','setup.png','Admin'], ['ABOUT','about.php','about.png','About'],
    ['GAMES','games.php','puzzles.png','Games'], ['HELP','help.php','help.png','Help']
];
$sidebars = ['MOVIES'=>'left_menu_movies.php','LIST'=>'left_menu_movies.php','PEOPLE'=>'left_menu_people.php',
    'SONGS'=>'left_menu_songs.php','REPORTS'=>'left_menu_reports.php','PUZZLES'=>'left_menu_puzzles.php',
    'GAMES'=>'left_menu_games.php','search_data'=>'left_search_data.php'];
$admin_navigation = $nav_selected === 'SETUP' && is_admin();
$sidebar = $admin_navigation ? 'admin' : ($left_buttons === 'YES' ? ($sidebars[$nav_selected] ?? null) : null);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo h($document_title); ?></title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/datatables/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="fonts/css/all.css">
    <link rel="stylesheet" href="fonts/css/v4-shims.min.css">
    <link rel="stylesheet" href="ajax/select2/dist/css/select2.min.css">
    <link rel="stylesheet" href="styles/mainStyleSheet.css">
    <link rel="stylesheet" href="styles/responsive.css">
    <link rel="stylesheet" href="styles/ui.css">
    <script src="ajax/jquery-3.2.1.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="vendor/datatables/dataTables.bootstrap.min.js"></script>
    <script src="ajax/select2/dist/js/select2.min.js"></script>
    <script src="scripts/layout.js" defer></script>
    <script src="scripts/catalog-ui.js" defer></script>
</head>
<body class="omdb-app">
<a class="skip-link" href="#main-content">Skip to content</a>
<div id="wrap">
    <header class="site-header">
        <a class="site-brand" href="index.php">
            <img src="images/omdb_logo.png" alt="OMDB" width="160" height="44">
            <span>Online Movie Database</span>
        </a>
        <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-nav">Menu <span aria-hidden="true">☰</span></button>
        <nav id="primary-nav" aria-label="Main navigation">
            <ul>
            <?php foreach ($navigation as [$key,$href,$icon,$label]) {
                if ($key === 'SETUP' && !is_admin()) { continue; }
                $active = $nav_selected === $key || ($key === 'search' && $nav_selected === 'search_data'); ?>
                <li><a href="<?php echo h($href); ?>"<?php echo $active ? ' aria-current="page"' : ''; ?>>
                    <span><?php echo h($label); ?></span>
                </a></li>
            <?php } ?>
            <?php if (is_logged_in()) { ?>
                <li><form action="log_out.php" method="post"><?php echo csrf_field(); ?><button class="nav-logout" type="submit">Log out</button></form></li>
            <?php } else { ?>
                <li><a href="login.php"<?php echo basename($_SERVER['SCRIPT_NAME']) === 'login.php' ? ' aria-current="page"' : ''; ?>>Sign in</a></li>
            <?php } ?>
            </ul>
        </nav>
    </header>
    <div class="utility-bar"><span class="account-badge"><?php echo is_admin() ? 'Administrator' : (is_logged_in() ? 'Member' : 'Guest'); ?></span>
    <form class="site-search" method="get" action="search.php" role="search">
        <label class="sr-only" for="global-search">Search OMDB</label>
        <input type="search" id="global-search" name="text_search" class="form-control" maxlength="500" placeholder="Search titles, people, or cast + director">
        <button type="submit" name="search" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
    </form></div>
    <div class="app-layout<?php echo $sidebar ? ' with-sidebar' : ''; ?>">
        <?php if ($sidebar) { ?><aside class="section-sidebar" aria-label="Section navigation"><?php if ($sidebar === 'admin') { ui_section_nav('ADMIN', basename($_SERVER['SCRIPT_NAME']) === 'sign_up.php' ? 'ACCOUNTS' : 'IMPORT'); } else { include __DIR__ . '/' . $sidebar; } ?></aside><?php } ?>
        <main id="main-content" class="omdb-content" tabindex="-1">
            <?php ui_page_header(); ui_feedback(); ?>
