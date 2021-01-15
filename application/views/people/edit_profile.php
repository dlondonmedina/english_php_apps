<?= validation_errors(); ?>

<?php

	$hidden = ['employID' => "$id"];
	// equivalent to manually entering a hidden input:
	// <input type="hidden" name="employID" value="<?= $id ? >" />

	echo form_open('/edit_profile/' . $id, 'id="edit_profile"', $hidden);

	echo form_label('firstname', 'first');
	echo form_input(['name'=>'first', 'id'=>'first', 'value'=>$employee['first']]);

	echo form_label('middlename', 'middle');
	echo form_input(['name'=>'middle', 'id'=>'middle', 'value'=>$employee['middle']]);

	echo form_label('lastname', 'last');
	echo form_input(['name'=>'last', 'id'=>'last', 'value'=>$employee['last']]);

	echo form_label('drupal_name', 'drupal_name');
	echo form_input(['name'=>'drupal_name', 'id'=>'drupal_name', 'value'=>$employee['drupal_name']]);

	echo form_label('type', 'typeID');
	echo form_dropdown('typeID', $types, $employee['typeID'], 'id="typeID"');  // <select> name, <options> array, selected option

	echo form_label('hire year', 'hireyear');
	echo form_input(['name'=>'hireyear', 'id'=>'hireyear', 'value'=>$employee['hireyear']]);

	echo form_label('appointment', 'appointment');
	echo form_input(['name'=>'appointment', 'id'=>'appointment', 'value'=>$employee['appointment']]);

	echo form_label('proftitle', 'proftitle');
	echo form_input(['name'=>'proftitle', 'id'=>'proftitle', 'value'=>$employee['proftitle']]);

	echo form_label('status', 'status');
	echo form_dropdown('status', $dept_status, $employee['status'], 'id="status"');  // <select> name, <options> array, selected option

	echo form_label('usercode', 'usercode');
	echo form_input(['name'=>'usercode', 'id'=>'usercode', 'value'=>$employee['usercode']]);

	echo form_label('hours', 'hours');
	echo form_input(['name'=>'hours', 'id'=>'hours', 'value'=>$employee['hours']]);

	echo form_label('uwnetid', 'uwnetid');
	echo form_input(['name'=>'uwnetid', 'id'=>'uwnetid', 'value'=>$employee['uwnetid']]);

	echo form_label('alt_email', 'alt_email');
	echo form_input(['name'=>'alt_email', 'id'=>'alt_email', 'value'=>$employee['alt_email']]);

	echo form_submit('ok', 'OK', 'default="yes"');
?>

	&nbsp; &nbsp;
	<a href="" onClick="if(confirm('WARNING: Any changes will be lost.\nReally leave this page?')){history.go(-1); return true;} else {return false;}">Cancel</a>

<?php echo form_close(); 
