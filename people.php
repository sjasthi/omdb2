<?php
require_once __DIR__ . '/initialize.php';
$nav_selected = 'PEOPLE'; $left_buttons = 'YES'; $left_selected = 'PEOPLE';
$year_filter = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT, ['options'=>['min_range'=>1888,'max_range'=>2155]]);
include __DIR__ . '/nav.php';
?>
<div class="page-actions">
<?php if (is_admin()) { ?>
    <a class="btn btn-primary" href="create_people.php">Create person</a>

<?php } ?>
    <a class="btn btn-default" href="search_data.php?type=people">Search people</a>
</div>

<table id="catalog-table" class="table table-striped catalog-table" data-catalog="person">
    <caption class="sr-only">People catalog</caption>
    <thead><tr><th scope="col">ID</th><th scope="col">Stage name</th><th scope="col">First name</th><th scope="col">Middle name</th><th scope="col">Last name</th><th scope="col">Gender</th><th scope="col"><?php echo is_admin() ? 'Actions' : 'Details'; ?></th></tr></thead>
    <tbody></tbody>
</table>
<p class="catalog-status" role="status">Loading the catalog…</p>
<script type="application/json" id="catalog-config"><?php echo js_value([
    'kind'=>'person', 'ajax'=>'people_ajax.php', 'columns'=>[['data'=>'people_id','label'=>'ID'],['data'=>'stage_name','label'=>'Stage name'],['data'=>'first_name','label'=>'First name'],['data'=>'middle_name','label'=>'Middle name'],['data'=>'last_name','label'=>'Last name'],['data'=>'gender','label'=>'Gender']],
    'actions'=>array_merge([['label'=>'View','path'=>'people_info.php','class'=>'btn-default']], is_admin() ? [['label'=>'Edit','path'=>'modify_people.php','class'=>'btn-default'],['label'=>'Delete','path'=>'delete_people.php','class'=>'btn-danger']] : [])
]); ?></script>
<noscript><p class="notice notice--info">Enable JavaScript to browse and filter the catalog table. You can also use <a href="advance_movie_search.php">movie search</a>.</p></noscript>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
