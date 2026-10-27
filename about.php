<?php
require_once __DIR__ . '/initialize.php';
$nav_selected='ABOUT'; $left_buttons='NO';
include __DIR__ . '/nav.php';
?>
<section class="help-panel"><h2>A connected movie catalog</h2><p>OMDB brings movie titles, cast and crew, songs, trivia, and other details into one place. Browse the library, find movies by their contributors, and explore the connections between records.</p><p>Members browse and search; administrators maintain the catalog. The Help page explains the current features and workflows.</p><a class="btn btn-primary" href="help.php">Read the guide</a></section>
<section><h2>Project contributors</h2><p>OMDB has grown through contributions from several classes and teams.</p><ul><li>Gada Abadulla (Fall 2021)</li><li>Yahya Mohammed (Fall 2021)</li><li>Kahin, Amadin (Fall 2021)</li><li>Wirtz, Jack (Fall 2021)</li><li>Boling-Green, Zachary (Spring 2022)</li><li>Group 1 — OMDB2 capstone project</li></ul></section>
<?php include __DIR__ . '/footer.php'; ?>
