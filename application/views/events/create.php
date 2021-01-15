
<?php echo validation_errors(); ?>
<?php
$str = isset($event) ? "events/create/" . $event['id'] : 'events/create';
echo form_open($str); ?>
<?php
$params = array(
   'type' => 'hidden',
   'name' => 'netid',
   'id' => 'hiddennetid',
   'value' => $this->config->item('uw_user'),
   'class' => 'hiddennetid'
);

echo form_input($params);
?>
<?php
echo form_label('Title: ', 'title');
$params = array(
   'type' => 'text',
   'id' => 'title',
   'name' => 'title',
   'placeholder' => 'Title',
   'value' => isset($event)? $event['title'] : '',
   'required'
);
echo form_input($params); ?>
<br/>
<?php
echo form_label('Speaker: ', 'speaker');
$params = array(
   'type' => 'text',
   'id' => 'speaker',
   'name' => 'speaker',
   'placeholder' => 'Speaker',
   'value' => isset($event)? $event['speaker'] : '',
   'required'
);
echo form_input($params); ?>
<br/>
<?php
echo form_label('Place: ', 'place');
$params = array(
   'type' => 'text',
   'id' => 'place',
   'name' => 'place',
   'placeholder' => 'Location',
   'value' => isset($event)? $event['place'] : '',
   'required'
);
echo form_input($params);
?>
<br/>
<?php
   $datetime = isset($event) ? date_create($event['dt']) : '';
   $date = isset($event) ? date_format($datetime, 'Y-m-d') : '';
   if (isset($event)) {
      $hour = isset($event) ? date_format($datetime, 'g') : '';
      $hour = $hour < 10 ? '0' . $hour : $hour;
      $minute = isset($event) ? date_format($datetime, 'i') : '';
      $minute = $minute < 10 ? '0' . $minute : $minute;
   } else {
      $hour = '';
      $minute = '';
   }

   $ampm = isset($event) ? date_format($datetime, 'A') : '';
 ?>
<label for="date">Date: </label>
<input type="date" name="date" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
      placeholder="YYYY-MM-DD" value="<?php echo $date ?>"/>
<span class="validity"></span>
<br/>
<?php
$hours = [];
for ($i=1; $i <= 12 ; $i++) {
   $i = $i < 10 ? "0" . $i : $i;
   $hours[$i] = $i;
}
$minutes = [];
for ($i=0; $i < 60 ; $i+=15) {
   $i = $i < 10 ? "0" . $i : $i;
   $minutes[$i] = $i;
}
$daypart = ['AM' => 'AM', 'PM' => 'PM'];
echo form_label('Time: ', 'hour');
echo form_dropdown('hour', $hours, $hour);
echo form_dropdown('minute', $minutes, $minute);
echo form_dropdown('daypart', $daypart, $ampm);
?>
<br>
<?php
echo form_label('Description: ', 'description');
echo '<br>';
$params = array(
   'type' => 'textarea',
   'id' => 'description',
   'name' => 'description',
   'placeholder' => 'None',
   'value' => isset($event)? $event['description'] : '',
   'required'
);
echo form_textarea($params); ?>
<br>
<?php echo form_submit('submit', 'Submit Event!'); ?>
<?php echo form_close(); ?>
