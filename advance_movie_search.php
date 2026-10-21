<?php
require_once __DIR__ . '/initialize.php';
require_once __DIR__ . '/movie_search.php';
if (isset($_POST['songs']) || isset($_POST['people'])) {
    header('Location: search_data.php?type=' . (isset($_POST['songs']) ? 'songs' : 'people'), true, 303);
    exit;
}
try {
    // Accept legacy POST forms as well as shareable GET searches.
    $input = ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' ? $_POST : $_GET;
    $filters = movie_search_filters($input);
    $page = filter_var($input['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    if ($page === false) { throw new InvalidArgumentException('Enter a valid results page.'); }
} catch (InvalidArgumentException $error) {
    security_fail(400, $error->getMessage());
}
$has_filters = (bool)array_filter($filters, static function ($value) { return $value !== ''; });
$results = $has_filters ? movie_search_results($db, $filters, $page) : null;
$page_title = 'Find movies by cast and director';
$nav_selected = 'search_data'; $left_buttons = 'YES'; $left_selected = 'MOVIES';
include __DIR__ . '/nav.php';
?>
<h1>Find movies</h1>
<p>Combine a cast member and a director to find movies they worked on together. Every filled filter must match the same movie.</p>
<p class="search-help">Use a stage name, full name, or part of a name. Cast includes lead, supporting, and cameo roles.
    You can also type <q>movies with Brad Pitt directed by Steven Spielberg</q> in the main search box.</p>
<?php movie_search_form($filters); ?>
<?php if ($results !== null) { ?>
<section aria-labelledby="movie-results-heading">
    <h2 id="movie-results-heading"><?php echo h($results['count']); ?> matching <?php echo $results['count'] === 1 ? 'movie' : 'movies'; ?></h2>
    <ul class="movie-search-summary">
    <?php foreach (movie_search_fields() as $key => $label) { if ($filters[$key] !== '') { ?>
        <li><strong><?php echo h($label); ?>:</strong> <?php echo h($filters[$key]); ?></li>
    <?php } } ?>
    </ul>
    <?php if (!$results['rows']) { ?>
        <p>No movies match all these filters. Try a shorter name or clear one filter. Both people and their movie credits must be in OMDB.</p>
    <?php } else { ?>
        <table class="table table-striped">
            <caption>Movies matching every selected filter</caption>
            <thead><tr><th scope="col">Native title</th><th scope="col">English title</th><th scope="col">Year</th></tr></thead>
            <tbody>
            <?php foreach ($results['rows'] as $movie) { ?>
                <tr>
                    <td><a href="movie_info.php?movie_id=<?php echo h($movie['movie_id']); ?>"><?php echo h($movie['native_name'] ?: '(No native title)'); ?></a></td>
                    <td><a href="movie_info.php?movie_id=<?php echo h($movie['movie_id']); ?>"><?php echo h($movie['english_name'] ?: '(No English title)'); ?></a></td>
                    <td><?php echo h($movie['year_made']); ?></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <?php if ($results['pages'] > 1) { ?>
            <nav class="movie-search-pagination" aria-label="Movie results pages">
                <?php if ($results['page'] > 1) { ?><a class="btn btn-default" rel="prev" href="<?php echo h(movie_search_url($filters, $results['page'] - 1)); ?>">Previous</a><?php } ?>
                <span>Page <?php echo h($results['page']); ?> of <?php echo h($results['pages']); ?></span>
                <?php if ($results['page'] < $results['pages']) { ?><a class="btn btn-default" rel="next" href="<?php echo h(movie_search_url($filters, $results['page'] + 1)); ?>">Next</a><?php } ?>
            </nav>
        <?php } ?>
    <?php } ?>
</section>
<?php } else { ?>
    <p>Enter at least one filter to search the catalog.</p>
<?php } ?>
<?php include __DIR__ . '/footer.php'; ?>
