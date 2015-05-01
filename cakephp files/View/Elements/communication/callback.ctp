<div class="label">Callback</div>
&nbsp;&nbsp;
<?php
if(!isset($data)) {
	$data = null;
}
if(!isset($default_reminder_interval)) { 
	$default_reminder_interval = null;
}
if(!isset($datepicker_id)) {
	$datepicker_id = '';
} else {
	$datepicker_id = '-' . $datepicker_id;
}
$value = null;
if(!empty($data) && array_key_exists('date_reminder', $data) && !empty($data['date_reminder'])) {
	$value = date('m/d/Y', strtotime($data['date_reminder']));
}
if($__browser_view_mode['view_device'] == 'computer') :
	echo $this->Form->input('Reminder.date_reminder', array('id' => 'touchbase-datepicker' . $datepicker_id, 'type' => 'text', 'value' => $value, 'label' => false, 'class' => 'ReminderDateReminder datepicker', 'div' => array('class' => 'input short date inline')));				
else :
	// Mobile display devices will use the devices date picker. ?>
	<div class="input">
		<input id="touchbase-datepicker<?php echo $datepicker_id; ?>" class="ReminderDateReminder" type="date" value="<?php echo $value; ?>" name="data[Reminder][date_reminder]">
	</div>
<?php endif; ?>
&nbsp;
<?php echo $this->Form->input('Reminder.callback_type_select', array('options' => $__callback_options, 'id' => 'touch-base-type-select', 'empty' => 'Quick Pick', 'label' => false, 'div' => array('class' => 'input select medium inline'))); ?>
<?php echo $this->Form->hidden('Reminder.default_reminder_interval', array('value' => $default_reminder_interval, 'id' => 'default_reminder_interval')); ?>
			