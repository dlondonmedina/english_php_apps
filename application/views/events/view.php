<h2 class="text-center"><?php echo $event['title']; ?></h2>
<p class="text-center"><?php echo $event['speaker']; ?></p>
<p class="text-center"><?php
   $date = date_create($event['dt']); ?>
   <em><?php echo date_format($date, 'g:i A');?></em></br>
   <em><?php echo date_format($date, 'm/d/y');?></em></br>
   <em><?php echo $event['place']; ?></em></br>
</p>
<p class="text-center well"><?php echo $event['description'] ?></p>
<?php echo isset($edit_button) ? $edit_button : ''; ?>
<?php echo isset($flyer_button) ? $flyer_button : ''; ?>
<button type="button" name="button2" class="btn btn-primary"
      onclick="location.href='/events'">All Events</button>
