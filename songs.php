<?php
require_once __DIR__ . '/initialize.php';
$nav_selected = 'SONGS'; $left_buttons = 'YES'; $left_selected = 'SONGS';
$year_filter = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1888,'max_range'=>2155]]);
include __DIR__ . '/nav.php';
?>
<div class="page-actions">
<?php if (is_admin()) { ?>
    <a class="btn btn-primary" href="create_song.php">Create song</a>

<?php } ?>
    <a class="btn btn-default" href="search_data.php?type=songs">Search songs</a>
</div>

<table id="catalog-table" class="table table-striped catalog-table" data-catalog="song">
    <caption class="sr-only">Songs catalog</caption>
    <thead><tr><th scope="col">ID</th><th scope="col">Title</th><th scope="col">Lyrics</th><th scope="col">Theme</th><th scope="col"><?php echo is_admin() ? 'Actions' : 'Details'; ?></th></tr></thead>
    <tbody></tbody>
</table>
<p class="catalog-status" role="status">Loading the catalog…</p>
<script type="application/json" id="catalog-config"><?php echo js_value([
    'kind'=>'song', 'ajax'=>'songs_ajax.php', 'columns'=>[['data'=>'song_id','label'=>'ID'],['data'=>'title','label'=>'Title'],['data'=>'lyrics','label'=>'Lyrics'],['data'=>'theme','label'=>'Theme']],
    'actions'=>array_merge([['label'=>'View','path'=>'song_info.php','class'=>'btn-default']], is_admin() ? [['label'=>'Edit','path'=>'modify_song.php','class'=>'btn-default'],['label'=>'Credits','path'=>'songs_people_list.php','class'=>'btn-default'],['label'=>'Delete','path'=>'delete_song.php','class'=>'btn-danger']] : [])
]); ?></script>
<noscript><p class="notice notice--info">Enable JavaScript to browse and filter the catalog table. You can also use <a href="advance_movie_search.php">movie search</a>.</p></noscript>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
