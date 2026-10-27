<?php
require_once __DIR__ . '/initialize.php';
$nav_selected='HOME'; $left_buttons='NO';
$counts=[];
foreach (['movies','people','songs'] as $table) { $counts[$table]=(int)db_query($db,'SELECT COUNT(*) FROM `'.$table.'`')->fetch_row()[0]; }
include __DIR__ . '/nav.php';
?>
<div class="stat-grid">
<?php foreach (['movies'=>'Movies','people'=>'People','songs'=>'Songs'] as $key=>$label) { ?>
    <a class="stat-card" href="<?php echo h($key); ?>.php"><span class="stat-number"><?php echo h(number_format($counts[$key])); ?></span><span><?php echo h($label); ?></span><span class="stat-link">Browse <?php echo h(strtolower($label)); ?> →</span></a>
<?php } ?>
</div>
<section class="welcome-panel"><h2>Find the connections</h2><p>Search for a movie by its cast and director, then open a result to explore its credits, songs, and other details.</p><div class="page-actions"><a class="btn btn-primary" href="advance_movie_search.php">Find movies</a><a class="btn btn-default" href="help.php">Getting started</a><?php if (is_admin()) { ?><a class="btn btn-default" href="setup.php">Admin tools</a><?php } ?></div></section>
<?php if (!is_logged_in()) { ?><p class="notice notice--info">You can browse the public catalog. <a href="login.php">Sign in</a> to use reports and member features.</p><?php } ?>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
