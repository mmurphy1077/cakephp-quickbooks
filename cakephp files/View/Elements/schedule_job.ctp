<?php
if(empty($job)) {
	$job['address'] = '';
}
?>
<div class="widget center ct-bg widget_container" id="widget_container_">
	<?php echo $this->Form->create('Schedule', array('class' => 'standard wide scheduleJobForm', 'id' => 'scheduleJob', 'action' => 'ajax_add')); ?>
	<?php echo $this->Form->input('job', array('class' => 'schedule_job_input')); ?>
	<div class="input select">
	<?php 
	if(!empty($orders_list)) : 
		echo $this->Form->input('orders', array('class' => 'order_select', 'empty' => 'Select Job', 'options' => $orders_list, 'label' => false, 'div' => false)); ?>
		<div class="error-message" id="error-message-ScheduleOrderId"></div>
	<?php endif; ?>
	</div>
	<?php echo $this->Form->hidden('schedule_id'); ?>
	<?php echo $this->Form->hidden('order_id'); ?>
	<?php echo $this->Form->hidden('display'); ?>
	<div class="input select">
		<?php echo $this->Form->input('employee', array('class' => 'employee_select', 'div' => false)); ?>
		<div class="error-message" id="error-message-ScheduleEmployee"></div>
	</div>
	<?php 
	// Get Current Date
	$today = date('m/d/Y')
	?>
	<div class="input select">
		<?php echo $this->Form->input('date', array('class' => 'datepicker_cluetip date_schedule', 'div' => false)); ?>
		<div class="error-message" id="error-message-ScheduleDate"></div>
	</div>
	<?php echo $this->Form->hidden('date_session', array('class' => 'datepicker_cluetip date_schedule')); ?>
	<div class="time_select_container">
		<?php echo $this->Form->input('hour_start', array('class' => 'time_input hour_start', 'id' => 'hour_start', 'label' => false, 'div' => false)); ?>&nbsp;:
		<?php echo $this->Form->input('minute_start', array('class' => 'time_input minute_start', 'id' => 'minute_start', 'label' => false, 'div' => false, 'options' => array(0 =>'00', 15 => '15', 30 =>'30', 45 => '45'))); ?>
		<?php echo $this->Form->input('post_meridiem', array('class' => 'time_input post_meridiem_start', 'id' => 'post_meridiem_start', 'label' => false, 'div' => false, 'options' => array('am' =>'am', 'pm' => 'pm'))); ?>
		<span class="label">&nbsp;&nbsp;&nbsp;<b>to</b>&nbsp;&nbsp;&nbsp;&nbsp;</span>
	</div>
	<div class="time_select_container">
		<?php echo $this->Form->input('time_end_display', array('class' => 'time_end_display', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
		<?php echo $this->Form->hidden('time_end', array('class' => 'time_end', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
		<div id="time-end-select-container" class="time-end-select-container">
			<table id="time-end-select" class="time-end-select"></table>
			<?php echo $this->Html->image('icon-arrow-down.png', array('id'=>'icon-arrow-down', 'class'=>'icon-arrow')); ?>
			<?php echo $this->Html->image('icon-arrow-up.png', array('id'=>'icon-arrow-up', 'class'=>'icon-arrow')); ?>
		</div>
	</div>
	<div class="error-message clear" id="error-message-hour_start"></div>
	<div id="order_line_item_container" class="input select">
	<?php #echo $this->Form->input('task', array('type'=>'select', 'multiple'=>'checkbox', 'div'=>false, 'label'=> false, 'options'=> array( '1'=>'Install new main panel', '2'=>'Expose first floor wiring', '3'=>'Remove nob and tube wiring, ...', '4'=>'Install 23 outlets and 9 switches.'))); ?>
	</div>
	<br />
	<?php echo $this->Form->submit(__('Save'), array('div' => false, 'id' => 'save_job_schedule')); ?>
	<div id="delete_container">
		<?php echo $this->Html->link(__('Delete'), '#', array('id' => 'delete_schedule_session', 'class' => 'button', 'escape' => false, 'title' => __('Delete')), __('delete_confirm')); ?>
	</div>
	<div id="ajax_result_message" class="error-message ajax_result_message"></div>
	<?php echo $this->Form->end(); ?>
	<div id="ajax_loader_schedule" class="ajax_loader_schedule"><?php echo $this->Html->image('loader-large.gif'); ?></div>
</div>