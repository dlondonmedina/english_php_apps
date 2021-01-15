<h2>Set the month and day the form opens and closes.</h2>

<?=validation_errors(); ?>

<p>Use a 2-digit month and day separated by a dash (ex: <strong>09-13</strong>).</p>

<?=form_open("/ewp/get_prefs_manage/");?>

<table>
	<tbody>
		<tr><th></th><th>open</th><th>close</th></tr>
		<tr><th>Summer</th>
			<td><input name="summer_open" id="summer_open" type="text" value="<?=$form_dates[3]['open']?>"></td>
			<td><input name="summer_closed" id="summer_closed" type="text" value="<?=$form_dates[3]['closed']?>"></td>
		</tr>
		<tr><th>Autumn</th>
			<td><input name="autumn_open" id="autumn_open" type="text" value="<?=$form_dates[4]['open']?>"></td>
			<td><input name="autumn_closed" id="autumn_closed" type="text" value="<?=$form_dates[4]['closed']?>"></td>
		</tr>
		<tr><th>Winter</th>
			<td><input name="winter_open" id="winter_open" type="text" value="<?=$form_dates[1]['open']?>"></td>
			<td><input name="winter_closed" id="winter_closed" type="text" value="<?=$form_dates[1]['closed']?>"></td>
		</tr>
		<tr><th>Spring</th>
			<td><input name="spring_open" id="spring_open" type="text" value="<?=$form_dates[2]['open']?>"></td>
			<td><input name="spring_closed" id="spring_closed" type="text" value="<?=$form_dates[2]['closed']?>"></td>
		</tr>
	</tbody>
</table>

<?=form_submit('ok', 'Update', 'default="yes" id="ok"'); ?>

	&nbsp; &nbsp;
	<a href="" onClick="if(confirm('WARNING: Any changes will be lost.\nReally leave this page?')){history.go(-1); return true;} else {return false;}">Cancel</a>

<?=form_close();?>


