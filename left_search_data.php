<?php require_once __DIR__ . '/security.php'; ?>
<nav id="menu-left" aria-label="Search categories">
<?php foreach (['MOVIES' => ['advance_movie_search.php', 'Movies'], 'SONGS' => ['search_data.php?type=songs', 'Songs'], 'PEOPLE' => ['search_data.php?type=people', 'People']] as $key => [$href, $label]) { ?>
    <a href="<?php echo h($href); ?>"<?php echo $left_selected === $key ? ' aria-current="page" class="menu-left-current-page"' : ''; ?>><?php echo h($label); ?></a>
<?php } ?>
</nav>
