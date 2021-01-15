<p>(Not <b><?="$fname $lname"?></b>?  Please exit this browser and try again.)</p>

<?=validation_errors();?>
<?=$manage_form;?>

<?php
	// TEST CODE: handle year_q differently.
	$hidden = ['uwnetid' => $uwnetid, 'year_q' => $year_q];
	echo form_open("/ewp/get_prefs/$uwnetid/$year_q", 'id="get_prefs_form"', $hidden);
?>

<h2>First Choice</h2>

<ul class="nodots">
	<li>
		<?=form_label('Preferred Days:', 'days1');?>
		<?=form_dropdown('days1', $dayoptions, $prefs['days1'], 'id="days1" onchange="xtimes.setSelect(this,\'times1\',1)"');?>
		</li>
		<li>
		<?=form_label('Preferred Times:', 'times1');?>
		<?=form_dropdown('times1', $timeoptions, $prefs['times1'], 'id="times1" onchange="xtimes.adjustOptions(this,1)"');?>
	</li>
</ul>

<h2>Second Choice</h2>

<ul class="nodots">
	<li>
		<?=form_label('Preferred Days:', 'days2');?>
		<?=form_dropdown('days2', $dayoptions, $prefs['days2'], 'id="days2" onchange="xtimes.setSelect(this,\'times2\',2)"');?>
		</li>
		<li>
		<?=form_label('Preferred Times:', 'times2');?>
		<?=form_dropdown('times2', $timeoptions, $prefs['times2'], 'id="times2" onchange="xtimes.adjustOptions(this,2)"');?>
	</li>
</ul>

<h2>Third Choice</h2>

<ul class="nodots">
	<li>
		<?=form_label('Preferred Days:', 'days3');?>
		<?=form_dropdown('days3', $dayoptions, $prefs['days3'], 'id="days3" onchange="xtimes.setSelect(this,\'times3\',3)"');?>
		</li>
		<li>
		<?=form_label('Preferred Times:', 'times3');?>
		<?=form_dropdown('times3', $timeoptions, $prefs['times3'], 'id="times3" onchange="xtimes.adjustOptions(this,3)"');?>
	</li>
</ul>

<h2>Fourth Choice</h2>

<ul class="nodots">
	<li>
		<?=form_label('Preferred Days:', 'days4');?>
		<?=form_dropdown('days4', $dayoptions, $prefs['days4'], 'id="days4" onchange="xtimes.setSelect(this,\'times4\',4)"');?>
		</li>
		<li>
		<?=form_label('Preferred Times:', 'times4');?>
		<?=form_dropdown('times4', $timeoptions, $prefs['times4'], 'id="times4" onchange="xtimes.adjustOptions(this,4)"');?>
	</li>
</ul>

<h2><?=form_label('Other information', 'notes');?></h2>
	<?php 
		$v = [
			'name' => 'notes',
			'id' => 'notes',
			'value' => $prefs['notes'],
			'style' => 'height:150px'
		]
	?>
	<?=form_textarea($v);?>



<h2>Save your choices?</h2>

<?=form_submit('ok', 'Submit Preferences', 'default="yes" id="ok"'); ?>

	&nbsp; &nbsp;
	<a href="" onClick="if(confirm('WARNING: Any changes will be lost.\nReally leave this page?')){history.go(-1); return true;} else {return false;}">Cancel</a>

<?=form_close();?>

<script>
var xtimes = {
		// 'daychoices stores user's days-per-week selections.
		// This is only used to seed the form upon loading.
		<?php
			$s = '';
			for ($i=1; $i<5; $i++) $s .= $prefs["days$i"] .',';
			$s = substr($s,0,-1);
		?>
		daychoices : [0,<?=$s;?>],

		// 'used' stores selected times so options can be disabled as needed.
		<?php 
			$s = '';
			for ($i=1; $i<5; $i++) $s .= $prefs["times$i"] . ',';
			$s = substr($s,0,-1);
		?>
		used : [0,<?=$s;?>],

		adjustOptions : function(obj, n) {
				this.used[n] = obj.value;
				var timeIDs = ['times1','times2','times3','times4'],
						currentTime,
						condition,
						currentVal;
				for (var i=0, max=timeIDs.length; i<max; i++) {
						currentTime = document.getElementById( timeIDs[i] );
						if (currentTime.value) {
								for (var j=0, optMax=currentTime.length; j < optMax; j++) {
										// Don't disable time for the SELECT in which it is chosen.
										optVal = currentTime.options[j].value;
										condition = (this.used.includes( optVal ) && currentTime.value !== optVal) ? true : false;
										currentTime.options[j].disabled = condition;
								}
						}
				}
		},

		// Adjust "Preferred Times" based on "Preferred Days" selection.
		// 'n' will be 1,2,3, or 4, storing value of time1, 2, 3 or 4 SELECT inputs.
		setSelect : function(days, times, n) {
				var s2 = document.getElementById(times),
						origVal = s2.value,  // Remember original SELECT value, to avoid disabling it in OPTIONs.
						pair,
						newOption;
				s2.innerHTML = "";
				if (days.value == "0") {
						this.used[n] = null;  // Reset 'used'.
						return;  // Bail out.  Times SELECT list is empty.
				}
				// REDO to get values from codes table.
				if (days.value == "1") {
						var optionArray = ["0|","1|8:30","3|10:30","5|12:30","6|1:30","7|2:30","8|3:30"];
				} else { 
						var optionArray = ["0|","1|8:30","2|9:30","3|10:30","4|11:30","5|12:30","6|1:30","7|2:30","8|3:30"];
				}
				for (var option in optionArray) {
						pair = optionArray[option].split("|");
						newOption = document.createElement("option");
						newOption.value = pair[0];
						// Disable if already chosen in a different SELECT field.
						if (this.used.includes(pair[0]) && pair[0] !== origVal) newOption.disabled = true;
						newOption.innerHTML = pair[1];
						s2.options.add(newOption);
				}
				if (origVal) s2.value = origVal;
		}
}

// Initialize selection inputs.
var f = document.forms[0];
for (var i=0; i < xtimes.used.length-1; i++) {
	var i_str = (i+1).toString();
	var d = f['days' + i_str];
	var t = f['times' + i_str];
	d.value=xtimes.daychoices[i+1];
	xtimes.setSelect(d,'times'+i_str, i+1)
	t.value=xtimes.used[i+1];
	xtimes.adjustOptions(t, i+1);
}

</script>
