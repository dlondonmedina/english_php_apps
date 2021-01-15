<?php 
// rw:
// Problem: This view isn't independent of the model.  It should display lists generically.
// I think the controller should manipulate the data (order of names, punctuation, etc.) before it arrives here.
?>

<!-- <h2>Listing of <?=$title;?></h2> -->

<ul class="nodots">

<?php foreach ($employees as $employee) { ?>
	<li>
		<p><?="{$employee['Last']}, {$employee['First']} ({$employee['uwnetid']})";?><br>
			<?php
				if ($employee['Room']) echo $employee['Room'] . ', ';
				echo  "{$employee['Hours']}, hired {$employee['hireyear']}\n";
			?>
		</p>
	</li>
<?php } ?>

</ul>