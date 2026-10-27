<?php
require_once __DIR__ . '/initialize.php';
require_login();
require_once __DIR__ . '/movie_search.php';
try {
    $filters=movie_search_filters(['lead_actor'=>$_GET['name1'] ?? '', 'lead_actress'=>$_GET['name2'] ?? '']);
    $page=filter_var($_GET['page'] ?? 1,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
    if (!$page) { throw new InvalidArgumentException('Choose a valid results page.'); }
} catch (InvalidArgumentException $error) { security_fail(400,$error->getMessage()); }
$results=$filters['lead_actor'] !== '' && $filters['lead_actress'] !== '' ? movie_search_results($db,$filters,$page) : null;
$nav_selected='REPORTS'; $left_buttons='YES'; $left_selected='ACTORS/ACTRESSES';
include __DIR__ . '/nav.php';
?>
<form class="form-panel" method="get" action="reports_actors_actresses.php"><div class="form-group"><label for="name1">Lead actor</label><input class="form-control" id="name1" name="name1" maxlength="180" value="<?php echo h($filters['lead_actor']); ?>" required></div><div class="form-group"><label for="name2">Lead actress</label><input class="form-control" id="name2" name="name2" maxlength="180" value="<?php echo h($filters['lead_actress']); ?>" required></div><div class="form-actions"><button class="btn btn-primary" type="submit">Find shared movies</button><a class="btn btn-default" href="reports_actors_actresses.php">Clear filters</a></div></form>
<?php if ($results === null) { ?><p>Enter both names to find movies they share.</p><?php } elseif (!$results['rows']) { echo ui_notice('No movies match both people. Try a shorter name or check their credits.'); } else { ?>
<h2><?php echo h($results['count']); ?> matching movies</h2>
<table class="table table-striped"><thead><tr><th scope="col">Native title</th><th scope="col">English title</th><th scope="col">Year</th></tr></thead><tbody>
<?php foreach ($results['rows'] as $movie) { ?><tr><td><a href="movie_info.php?movie_id=<?php echo h($movie['movie_id']); ?>"><?php echo h($movie['native_name']); ?></a></td><td><?php echo h($movie['english_name']); ?></td><td><?php echo h($movie['year_made']); ?></td></tr><?php } ?>
</tbody></table>
<?php if ($results['pages']>1) { ?><nav class="movie-search-pagination" aria-label="Report pages"><?php foreach (['Previous'=>$results['page']-1,'Next'=>$results['page']+1] as $label=>$number) { if ($number>=1 && $number<=$results['pages']) { ?><a class="btn btn-default" href="reports_actors_actresses.php?<?php echo h(http_build_query(['name1'=>$filters['lead_actor'],'name2'=>$filters['lead_actress'],'page'=>$number])); ?>"><?php echo h($label); ?></a><?php } } ?><span>Page <?php echo h($results['page']); ?> of <?php echo h($results['pages']); ?></span></nav><?php } } ?>
<?php include __DIR__ . '/footer.php'; ?>
