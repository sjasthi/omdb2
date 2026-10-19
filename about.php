<?php
require_once __DIR__ . '/security.php';
?>
<?php

  // set the current page;
  $nav_selected = "ABOUT";

  // make the left menu buttons visible; options: YES, NO
  $left_buttons = "NO";

  // set the left menu button selected; options will change based on the main selection
  $left_selected = "";

  include("./nav.php");
?>











    <!-- Latest compiled and minified CSS -->

    <!-- jQuery library -->

    <!-- Latest compiled JavaScript -->











<h3 style = "color: #01B0F1;">Problem Solution</h3>


<h3 style = "color: #01B0F1;">About</h3>

	<h3>Who Helped?</h3>
	<p>
		<ul>
      <li>Gada Abadulla (Fall 2021)</li>
      <li>Yahya Mohammed (Fall 2021)</li>
      <li>Kahin, Amadin (Fall 2021)</li>
      <li>Wirtz, Jack (Fall 2021)</li>
      <li>Boling-Green, Zachary (Spring 2022)</li>

		</ul>
  </p>







<?php include __DIR__ . '/footer.php'; ?>
