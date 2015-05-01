<?php echo $this->element('js'.DS.'scrollpane'); ?>
<?php 
/*
 * DEFAULT SETTINGS
 */
if(empty($client_default_view_type)) {
	$client_default_view_type = 'items';
}
?>
<div id="schedule_table_container" class="single_order">
	<div id="master-schedule-edit-container" class=" clear hide">
		<?php echo $this->Form->create('Schedule', array('class' => 'ScheduleIndexForm standard schedule', 'novalidate' => true, 'url' => array('controller' => 'schedules', 'action' => 'ajax_add'))); ?>
		<div id="order-title" class="order-title"></div>
		<div class="container-pad">
			<div  id="master-schedule-edit-content" class="schedule-container order-detail-container"></div>
		</div>
		<div id="schedule-actions" class="schedule-actions">
			<fieldset>
				<div class="row fieldset-wrapper available_action_container">
					<div id="schedule-action-status-container" class="col-xs-12 col-sm-6 col-md-6 col-lg-6 schedule-action-status-container">
						<div id="ajax_message_fail" class="ajax_message_fail"></div>
						<div id="ajax_message_success" class="ajax_message_success"></div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<div class="title-buttons">
							<?php echo $this->Html->link('Delete', '#', array('id' => 'delete_schedule', 'class' => 'right')); ?>
							<?php echo $this->Form->submit(__('Save'), array('id' => 'submit', 'class' => 'right post title-buttons', 'escape' => false)); ?>
						</div>	
					</div>	  
				</div>
			</fieldset>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
	
	<div id="orders_container" class="right">
		<?php echo $this->Form->hidden('default_view_type', array('value' => $client_default_view_type, 'id' => 'default_view_type')); ?>
		<div id="order-loader"><?php echo $this->Html->image('loader-large.gif', array('id' => 'order-loader-image')); ?></div>
		<div id="scheduled_jobs" class="jobs"></div>
		<div id="order-schedule-container" class="order-schedule-container hide clear">
			<?php echo $this->Form->hidden('Schedule.schedule_type', array('name' => 'data[Schedule][schedule_type]', 'value' => $schedule_type))?>
			<div id="schedule-title" class="schedule-title">
				<div id="schedule-mode">Add Schedule</div>
			</div>
			<fieldset>
				<div class="fieldset-wrapper">
					<h4>Who</h4>
					<div id="schedule-who-employee" class="schedule-who-employee clear"></div>
					<div id="schedule-who-jobtype" class="schedule-who-jobtype clear"></div>
					<div class="clear">&nbsp;</div>
				</div>
			</fieldset>
			<fieldset>
				<div class="fieldset-wrapper">
					<h4>What</h4>
					<div id="schedule-what" class=""></div>
				</div>
			</fieldset>
			<fieldset>
				<div id="schedule-when" class="fieldset-wrapper">
					<h4>When</h4>
					<div id="schedule-when-container" class="schedule-when-container">
						<div class="time_select_container clear">
							<span class="label"><b>From</b></span><br />
							<div class="date-container">
								<div id="datepicker_schedule_display_from" class="datepicker_schedule_display"></div>
								<div class="datepicker-calendar-container">
									<?php echo $this->Form->input('datepicker_schedule_from', array('class' => 'datepicker_schedule_from', 'name' => 'data[Schedule][date_session_start]', 'label' => false, 'div' => false)); ?>
								</div>
							</div>
							<div id="start-time-container" class="time-container">
								<?php echo $this->Form->input('hour_start', array('class' => 'time_input hour_start', 'id' => 'hour_start', 'type' => 'number', 'label' => false, 'div' => false)); ?>&nbsp;:
								<?php #echo $this->Form->input('minute_start', array('class' => 'time_input minute_start', 'id' => 'minute_start', 'label' => false, 'div' => false, 'options' => array(0 =>'00', 15 => '15', 30 =>'30', 45 => '45'))); ?>
								<?php echo $this->Form->input('minute_start', array('class' => 'time_input minute_start minutes_only', 'id' => 'minute_start', 'label' => false, 'div' => false)); ?>
								<?php echo $this->Form->input('post_meridiem', array('class' => 'time_input post_meridiem_start', 'id' => 'post_meridiem_start', 'label' => false, 'div' => false, 'options' => array('am' =>'am', 'pm' => 'pm'))); ?>
							</div>
						</div>
						<div class="time_select_container clear">
							<span class="label"><b>To</b></span><br />
							<div class="date-container">
								<div id="datepicker_schedule_display_to" class="datepicker_schedule_display"></div>
								<div class="datepicker-calendar-container">
									<?php echo $this->Form->input('datepicker_schedule_to', array('class' => 'datepicker_schedule_to', 'name' => 'data[Schedule][date_session_end]', 'label' => false, 'div' => false)); ?>
								</div>
							</div>
							<div class="time-container">
								<div id="end-time-container">
									<?php echo $this->Form->input('time_end_display', array('class' => 'time_end_display', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
									<?php echo $this->Form->hidden('time_end', array('class' => 'time_end', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
								</div>
								<div id="time-end-select-container" class="time-end-select-container jscrollpane5555">
									<table id="time-end-select" class="time-end-select"></table>
									<?php echo $this->Html->image('icon-arrow-down.png', array('id'=>'icon-arrow-down', 'class'=>'icon-arrow')); ?>
									<?php echo $this->Html->image('icon-arrow-up.png', array('id'=>'icon-arrow-up', 'class'=>'icon-arrow')); ?>
								</div>
							</div>
							<div id="duration-container" class="duration-container left">
								<b>Duration</b>&nbsp;&nbsp;
								<?php echo $this->Form->input('duration', array('id' => 'duration', 'class' => 'duration num_only', 'label' => false, 'div' => false)); ?> hrs.
							</div>
						</div>
						<div class="error-message clear" id="error-message-hour_start"></div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<div id="schedule-comment" class="fieldset-wrapper">
					<h4>Comments</h4>
					<?php echo $this->Form->input('Message.content', array('class' => 'full schedule-comment', 'label' => false, 'div' => false)); ?>
				</div>
			</fieldset>
		</div>
	</div>
</div>