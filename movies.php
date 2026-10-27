<?php
require_once __DIR__ . '/initialize.php';
$nav_selected = 'MOVIES'; $left_buttons = 'YES'; $left_selected = 'MOVIES';
$year_filter = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1888,'max_range'=>2155]]);
include __DIR__ . '/nav.php';
?>
<div class="page-actions">
<?php if (is_admin()) { ?>
    <a class="btn btn-primary" href="create_movie.php">Create movie</a>
    <a class="btn btn-default" href="add_movie_posters.php">Upload posters</a>
<?php } ?>
    <a class="btn btn-default" href="advance_movie_search.php">Find movies</a>
</div>
<?php if ($year_filter) { echo ui_notice('Showing movies released in ' . $year_filter . '.'); ?><a href="movies.php">Clear year filter</a><?php } ?>
<table id="catalog-table" class="table table-striped catalog-table" data-catalog="movie">
    <caption class="sr-only">Movies catalog</caption>
    <thead><tr><th scope="col">ID</th><th scope="col">Native title</th><th scope="col">English title</th><th scope="col">Year</th><th scope="col"><?php echo is_admin() ? 'Actions' : 'Details'; ?></th></tr></thead>
    <tbody></tbody>
</table>
<p class="catalog-status" role="status">Loading the catalog…</p>
<script type="application/json" id="catalog-config"><?php echo js_value([
    'kind'=>'movie', 'ajax'=>'movies_ajax.php?year=' . ($year_filter ?: ''), 'columns'=>[['data'=>'movie_id','label'=>'ID'],['data'=>'native_name','label'=>'Native title'],['data'=>'english_name','label'=>'English title'],['data'=>'year_made','label'=>'Year']],
    'actions'=>array_merge([['label'=>'View','path'=>'movie_info.php','class'=>'btn-default']], is_admin() ? [['label'=>'Edit','path'=>'modify.php','class'=>'btn-default'],['label'=>'People','path'=>'add_people.php','class'=>'btn-default'],['label'=>'Songs','path'=>'add_song.php','class'=>'btn-default'],['label'=>'More details','path'=>'create_Data.php','class'=>'btn-default'],['label'=>'Delete','path'=>'delete_movie.php','class'=>'btn-danger']] : [])
]); ?></script>
<noscript><p class="notice notice--info">Enable JavaScript to browse and filter the catalog table. You can also use <a href="advance_movie_search.php">movie search</a>.</p></noscript>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
