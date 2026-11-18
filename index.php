<?php
require_once __DIR__ . '/initialize.php';
$nav_selected='HOME'; $left_buttons='NO';
$counts=[];
foreach (['movies','people','songs'] as $table) { $counts[$table]=(int)db_query($db,'SELECT COUNT(*) FROM `'.$table.'`')->fetch_row()[0]; }
$recent_movies = db_query($db, 'SELECT movie_id, native_name, english_name, year_made FROM movies ORDER BY movie_id DESC LIMIT 6')->fetch_all(MYSQLI_ASSOC);
include __DIR__ . '/nav.php';
?>
<div class="stat-grid">
<?php foreach (['movies'=>'Movies','people'=>'People','songs'=>'Songs'] as $key=>$label) { ?>
    <a class="stat-card" href="<?php echo h($key); ?>.php"><span class="stat-number"><?php echo h(number_format($counts[$key])); ?></span><span><?php echo h($label); ?></span><span class="stat-link">Browse <?php echo h(strtolower($label)); ?> →</span></a>
<?php } ?>
</div>
<section class="recent-movies" aria-labelledby="recent-movies-heading">
    <div class="section-heading"><h2 id="recent-movies-heading">Recently added movies</h2><a href="movies.php">View all movies</a></div>
    <?php if ($recent_movies) { ?>
    <ul class="recent-movie-list">
    <?php foreach ($recent_movies as $movie) { ?>
        <li><a href="movie_info.php?movie_id=<?php echo h($movie['movie_id']); ?>"><span><?php echo h($movie['english_name'] ?: $movie['native_name']); ?></span><span class="recent-movie-year"><?php echo h($movie['year_made']); ?></span></a></li>
    <?php } ?>
    </ul>
    <?php } else { ?><p>No movies yet. <?php if (is_admin()) { ?><a href="create_movie.php">Create the first movie</a> or <a href="setup.php">import a CSV file</a>.<?php } else { ?>An administrator can add movies to this catalog.<?php } ?></p><?php } ?>
</section>
<section class="welcome-panel"><h2>Find the connections</h2><p>Search for a movie by its cast and director, then open a result to explore its credits, songs, and other details.</p><div class="page-actions"><a class="btn btn-primary" href="advance_movie_search.php">Find movies</a><a class="btn btn-default" href="help.php">Getting started</a><?php if (is_admin()) { ?><a class="btn btn-default" href="setup.php">Admin tools</a><?php } ?></div></section>
<?php if (!is_logged_in()) { ?><p class="notice notice--info">You can browse the public catalog. <a href="login.php">Sign in</a> to use reports and member features.</p><?php } ?>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
