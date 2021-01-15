<div>
	<p>I'll scrub this stuff when I'm done.</p>
	<ul>
		<?php 
			// $list is an array passed in the $data array from controllers/Pages.php
			foreach($list as $v) { 
		?>
		<li><?=$v;?></li>
		<?php } ?>
	</ul>
</div>