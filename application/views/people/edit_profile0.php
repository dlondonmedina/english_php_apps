<?= validation_errors(); ?>

<?= form_open('/edit_profile/' . $id, 'id="edit_profile"'); ?>

	<?php // form_open() inserts <form> tag ?>

	<input type="hidden" name="employID" id="employID" value="<?= $id ?>" />

	<label for="first">firstname</label> 
	<input type="text" name="first" id="first" value="<?= $employee['first'] ?>" /><br>

	<label for="middle">middlename</label>
	<input type="text" name="middle" id="middle" value="<?= $employee['middle'] ?>" /><br>

	<label for="last">lastname</label>
	<input type="text" name="last" id="last" value="<?= $employee['last'] ?>" /><br>

	<label for="drupal_name">drupal_name</label>
	<input type="text" name="drupal_name" id="drupal_name" value="<?= $employee['drupal_name'] ?>" /><br>

	<label for="typeID">type</label>
	<select name="typeID" id="typeID">
		<?php for ($i = 0, $max = count($typeID); $i < $max; $i++ ) { 
			$selected = ($employee['typeID'] === $typeID[$i]['id']) ? ' selected="selected"' : '';
		?>
		<option value="<?= $typeID[$i]['id'] ?>"<?= $selected ?>><?= $typeID[$i]['description'] ?></option>
		<?php } ?>
	</select><br>

	<label for="hireyear">hireyear</label>
	<input type="text" name="hireyear" id="hireyear" value="<?= $employee['hireyear'] ?>" /><br>

	<label for="appointment">appointment</label>
	<input type="text" name="appointment" id="appointment" value="<?= $employee['appointment'] ?>" /><br>

	<label for="proftitle">proftitle</label>
	<input type="text" name="proftitle" id="proftitle" value="<?= $employee['proftitle'] ?>" /><br>

	<label for="status">status</label>
	<select name="status" id="status">
		<?php for ($i = 0, $max = count($status); $i < $max; $i++ ) { 
			$selected = ($employee['status'] === $status[$i]['id']) ? ' selected="selected"' : '';
		?>
		<option value="<?= $status[$i]['id'] ?>"<?= $selected ?>><?= $status[$i]['description'] ?></option>
		<?php } ?>
	</select><br>

	<label for="usercode">usercode</label>
	<input type="text" name="usercode" id="usercode" value="<?= $employee['usercode'] ?>" /><br>

	<label for="hours">hours</label>
	<input type="text" name="hours" id="hours" value="<?= $employee['hours'] ?>" /><br>

	<label for="uwnetid">uwnetid</label>
	<input type="text" name="uwnetid" id="uwnetid" value="<?= $employee['uwnetid'] ?>" /><br>

	<label for="alt_email">alt_email</label>
	<input type="text" name="alt_email" id="alt_email" value="<?= $employee['alt_email'] ?>" /><br>

	<input type="submit" id="ok" name="ok" value="OK" default="yes" /> &nbsp; &nbsp;
	<a href="" onClick="if(confirm('WARNING: Any changes will be lost.\nReally leave this page?')){history.go(-1); return true;} else {return false;}">Cancel</a>
<?php 
// 	<input type="submit" id="cancel" name="cancel" value="cancel" />
?>

</form>