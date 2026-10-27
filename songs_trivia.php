<?php
require_once __DIR__ . '/initialize.php';
$nav_selected='SONGS'; $left_buttons='YES'; $left_selected='TRIVIA';
$rows=db_query($db,'SELECT t.song_id,t.song_trivia_id,t.song_trivia_name,s.title FROM song_trivia t JOIN songs s ON s.song_id=t.song_id ORDER BY s.title,t.song_trivia_id');
include __DIR__ . '/nav.php';
?>
<table class="table table-striped"><caption class="sr-only">Song trivia</caption><thead><tr><th scope="col">Song</th><th scope="col">Trivia</th><th scope="col">Details</th></tr></thead><tbody>
<?php if (!$rows->num_rows) { ?><tr><td colspan="3">No song trivia has been added yet.</td></tr><?php } ?>
<?php while ($row=$rows->fetch_assoc()) { ?><tr><td><?php echo h($row['title']); ?></td><td><?php echo h($row['song_trivia_name']); ?></td><td><a class="btn btn-default" href="song_info.php?song_id=<?php echo h($row['song_id']); ?>">View song</a></td></tr><?php } ?>
</tbody></table>
<?php db_disconnect($db); include __DIR__ . '/footer.php'; ?>
