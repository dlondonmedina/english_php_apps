<h2>Listing of <?=$title;?></h2>
<h3>Something else</h3>

<?php foreach ($employees as $employee) { ?>

	<h3><?=$employee['Last'] . ' ' . $employee['First'];?></h3>
	<div class="main">
		<?=$employee['Room'] . ' ' . $employee['Hours'];?>
	</div>

<?php } ?>

