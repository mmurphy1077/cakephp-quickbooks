<?php echo $this->element('js'.DS.'time_log', array('sessions' => $sessions, 'order' => $order)); ?>
<?php $this->start('modal'); ?>
<div id="add-time-log-container" class="modal_box">
	<h3 class="cluetip-title ui-widget-header ui-cluetip-header">
		<div id="close-time-log-container" class="cluetip-close"><a href="#">X</a></div>
		<?php echo __('Order'); ?>
	</h3>
	<div id="schedule_session_time_navigation" class="title-buttons">
		<a class="button long blue right" id="nav_materials" href="#">Material & Equipment</a>
		<a class="button medium blue right" id="nav_time" href="#">Labor Hours</a>
	</div>
	<h3 class="label inline">
		<span id="label_labor">Labor Hours</span>
		<span id="label_materials">Material & Equipment</span>
	</h3>
	<?php echo $this->Form->create('Order', array('class' => 'standard wide scheduleJobForm', 'id' => 'logTime', 'action' => 'add_session_data')); ?>
	<div id="schedule_session_input_container">	
		<div id="schedule_session_container">
			<fieldset class="modal">
				<div id="select">
					<div class="fieldset-wrapper">
						<h4>Schedule Session Selection</h4>
						<label>
							(Optional) Choose which schedule session the time is being entered.
						</label><br />
						<div id="select-session-container" class="jscrollpane">
							<table class="standard">
							<?php if(!empty($sessions)) : ?>
							<?php 	foreach($sessions as $session) : ?>
								<tr class="time_log_session_select" id="<?php echo $session['ScheduleSession']['id']; ?>">
									<td>
										<div class="grid small">
											<div class="col-1of2"><?php echo $this->Web->humanName($session['Worker']); ?></div>
											<div class="col-1of4"><?php echo date('M n, Y', strtotime($session['ScheduleSession']['date_session'])); ?></div>
											<div class="col-1of4"><?php echo date('g:i a', strtotime($session['ScheduleSession']['time_start'])); ?> to <?php echo date('g:i a', strtotime($session['ScheduleSession']['time_end'])); ?></div>
										</div>
										<div class="grid small light">
											<div class="col-1of1">
											<?php if(!empty($session['ScheduleSessionsTask'])) : ?>
											<?php 	foreach($session['ScheduleSessionsTask'] as $task) : ?>
											<?php 		echo $task['Task']['name'] . '<br/>'; ?>
											<?php 	endforeach;?>
											<?php endif; ?>
											</div>
										</div>
										<div id="schedule_session_data_bank_<?php echo $session['ScheduleSession']['id']; ?>" class="schedule_session_data_bank">
											<?php echo $this->Form->hidden('ScheduleSession.date_session', array('value' => $session['ScheduleSession']['date_session'])); ?>
											<?php echo $this->Form->hidden('ScheduleSession.date_session_display', array('value' => date('F d, Y', strtotime($session['ScheduleSession']['date_session'])))); ?>
											<?php echo $this->Form->hidden('ScheduleSession.time_start', array('value' => $session['ScheduleSession']['time_start'])); ?>
											<?php echo $this->Form->hidden('ScheduleSession.time_end', array('value' => $session['ScheduleSession']['time_end'])); ?>
											<?php echo $this->Form->hidden('ScheduleSession.id', array('value' => $session['ScheduleSession']['id'])); ?>
											<?php echo $this->Form->hidden('ScheduleSession.worker_id', array('value' => $session['ScheduleSession']['worker_id'])); ?>
											<?php echo $this->Form->hidden('ScheduleSession.worker', array('value' => $this->Web->humanName($session['Worker'], 'full'))); ?>
											<?php echo $this->Form->hidden('ScheduleSession.estimate', array('value' => $session['ScheduleSession']['estimate'])); ?>
											<?php echo $this->Form->hidden('ScheduleSessionsTask.task_id', array('value' => $session['ScheduleSession']['task_list'])); ?>
										</div>
									</td>
								</tr>
							<?php 	endforeach;?>
							<?php endif; ?>
							</table>
						</div>
					</div>
				</div>
				<div id="update_schedule_session_time_container">
					<div class="col-1of1 fieldset-wrapper">
						<h4 class="fieldset-wrapper">Selected Session Information</h4>
						<label id="optional_instructions">(Optional)  Labor, Materials and Expenses can be associated to a specific task by selecting the appropriate session tasks from the list below.</label>
					</div>
					<div id="job_info_description_container" class="col-1of3 fieldset-wrapper">
						<div id="selected_worker"></div>
						<div id="selected_date_session"></div>
						<div id="selected_time"></div>
						<div id="estimate"></div>
						<br />
					</div>
					<div id="order_line_item_container" class="col-1of3 fieldset-wrapper">
						<label>Scheduled Tasks</label>
						<?php 
						if(!empty($order['OrderLineItem'])):
							foreach($order['OrderLineItem'] as $line_item) : ?>
								<?php echo $this->Form->input('ScheduleSessionsTasks.task_id', array('type' => 'checkbox', 'label' => $line_item['name'], 'value' => $line_item['id'], 'class' => 'schedule_session_task_select', 'name' => 'data[ScheduleSessionsTasks]['.$line_item['id'].'][task_id]')); ?>
						<?php endforeach;
						endif; ?>	
					</div>
					<?php echo $this->Html->link('Return to Session Selection', array('#'), array('id' => 'return_to_schedule_list')); ?>
				</div>
			</fieldset>
		</div>
		<div id="schedule_session_time_container">
			<div class="col-timesheet-1">
				<fieldset class="modal">	
					<div class="left fieldset-wrapper">
						<label><?php echo __('Drive Time'); ?></label><br />
						<div>
							<div class="time_select_container">
								<?php echo $this->Form->input('ScheduleSessionTime.hour_start_drive', array('class' => 'time_input hour_start', 'id' => 'hour_start_drive', 'label' => false, 'div' => false)); ?>&nbsp;:
								<?php echo $this->Form->input('ScheduleSessionTime.minute_start_drive', array('class' => 'time_input minute_start', 'id' => 'minute_start_drive', 'label' => false, 'div' => false, 'options' => array(0 =>'00', 15 => '15', 30 =>'30', 45 => '45'))); ?>
								<?php echo $this->Form->input('ScheduleSessionTime.post_meridiem_drive', array('class' => 'time_input post_meridiem_start', 'id' => 'post_meridiem_start_drive', 'label' => false, 'div' => false, 'options' => array('am' =>'am', 'pm' => 'pm'))); ?>
								<span class="label">&nbsp;&nbsp;&nbsp;<b>to</b>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php echo $this->Form->hidden('ScheduleSessionTime.time_start_drive', array('id' => 'time_start_drive', 'class' => 'time_start', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
							</div>
							<div class="time_select_container">
								<?php echo $this->Form->input('ScheduleSessionTime.time_end_display_drive', array('id' => 'time_end_display_drive', 'class' => 'time_end_display', 'label' => false, 'div' => false, 'readonly' => 'readonly', 'disabled' => 'disabled')); ?>
								<?php echo $this->Form->hidden('ScheduleSessionTime.time_end_drive', array('id' => 'time_end_drive', 'class' => 'time_end', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
								<div id="time-end-select-container-drive" class="time-end-select-container jscrollpane_mini">
									<table id="time-end-select-drive" class="time-end-select"></table>
									<?php echo $this->Html->image('icon-arrow-down.png', array('id'=>'icon-arrow-down', 'class'=>'icon-arrow')); ?>
									<?php echo $this->Html->image('icon-arrow-up.png', array('id'=>'icon-arrow-up', 'class'=>'icon-arrow')); ?>
								</div>
							</div>
							<div class="error-message clear" id="error-message-hour_start"></div>
						</div>
					
						<label><?php echo __('Work Time'); ?></label><br />
						<div>
							<div class="time_select_container">
								<?php echo $this->Form->input('ScheduleSessionTime.hour_start_work', array('class' => 'time_input hour_start', 'id' => 'hour_start_work', 'label' => false, 'div' => false)); ?>&nbsp;:
								<?php echo $this->Form->input('ScheduleSessionTime.minute_start_work', array('class' => 'time_input minute_start', 'id' => 'minute_start_work', 'label' => false, 'div' => false, 'options' => array(0 =>'00', 15 => '15', 30 =>'30', 45 => '45'))); ?>
								<?php echo $this->Form->input('ScheduleSessionTime.post_meridiem_work', array('class' => 'time_input post_meridiem_start', 'id' => 'post_meridiem_start_work', 'label' => false, 'div' => false, 'options' => array('am' =>'am', 'pm' => 'pm'))); ?>
								<span class="label">&nbsp;&nbsp;&nbsp;<b>to</b>&nbsp;&nbsp;&nbsp;&nbsp;</span>
								<?php echo $this->Form->hidden('ScheduleSessionTime.time_start_work', array('id' => 'time_start_work', 'class' => 'time_start', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
							</div>
							<div class="time_select_container">
								<?php echo $this->Form->input('ScheduleSessionTime.time_end_display_work', array('id' => 'time_end_display_work', 'class' => 'time_end_display', 'label' => false, 'div' => false, 'readonly' => 'readonly', 'disabled' => 'disabled')); ?>
								<?php echo $this->Form->hidden('ScheduleSessionTime.time_end_work', array('id' => 'time_end_work', 'class' => 'time_end', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
								<div id="time-end-select-container-work" class="time-end-select-container static jscrollpane_mini">
									<table id="time-end-select-work" class="time-end-select"></table>
									<?php echo $this->Html->image('icon-arrow-down.png', array('id'=>'icon-arrow-down', 'class'=>'icon-arrow')); ?>
									<?php echo $this->Html->image('icon-arrow-up.png', array('id'=>'icon-arrow-up', 'class'=>'icon-arrow')); ?>
								</div>
							</div>
							<div class="error-message clear" id="error-message-hour_start"></div>
						</div>
					</div>
				</fieldset>
				<fieldset class="modal">
					<div class="fieldset-wrapper">
						<div id="timesheet-hours">
							<div><?php echo $this->Form->input('ScheduleSessionTime.reg', array('label' => 'Reg', 'id' => 'time_input_reg', 'class' => 'time_input', 'div' => false)); ?></div>
							<div><?php echo $this->Form->input('ScheduleSessionTime.ot', array('label' => 'OT', 'id' => 'time_input_ot', 'class' => 'time_input', 'div' => false)); ?></div>
							<div><?php echo $this->Form->input('ScheduleSessionTime.dt', array('label' => 'DT', 'id' => 'time_input_dt', 'class' => 'time_input', 'div' => false)); ?></div>
						</div>
						<div id="timesheet-hours" class="right">
							<div><?php echo $this->Form->input('ScheduleSessionTime.rate', array('label' => 'Rate', 'class' => '', 'div' => false, 'value' => number_format(HOURLY_RATE, 2))); ?></div>
						</div>
						<?php echo $this->Form->hidden('ScheduleSessionTime.session-time', array('id' => 'session-time')); ?>
					</div>
				</fieldset>
			</div>
			<div class="col-timesheet-2">
				<fieldset class="modal">	
					<div class="left fieldset-wrapper">
						<?php echo $this->Form->input('ScheduleSessionTime.notes', array('label' => 'Comments')); ?>
					</div>
				</fieldset>
				<fieldset class="modal">
					<div class="fieldset-wrapper">
						<?php 
						$value = $order['Order']['status'];
						if(!array_key_exists($order['Order']['status'], $field_statuses)) {
							$value = 0;
						} ?>
						<?php echo $this->Form->input('Order.status', array('value' => $value, 'empty' => 'Select', 'options' => $field_statuses)); ?>
					</div>
				</fieldset>
			</div>
		</div>
		<div id="schedule_session_materials_container">	
			<div class="col-timesheet-1">
				<fieldset class="modal">	
					<div class="left fieldset-wrapper">
						<label><?php echo __('Materials'); ?></label>
						<div id="material_select_container">
							<?php for($i = 0; $i <= 4; $i++) : ?>
							<div class="material_select_item" id="material_select_item_<?php echo $i; ?>">
								<?php echo $this->Form->input('OrderMaterial.material_id', array('id'=>'material_'.$i, 'class'=>'material_select', 'name' => 'data[OrderMaterial]['.$i.'][material_id]', 'label' => false, 'div' => false, 'options' => $materials, 'empty' => 'Select')); ?>
								<?php echo $this->Form->input('OrderMaterial.qty', array('id'=>'material_qty_'.$i,'class' => 'material_qty qty_input num_only', 'name' => 'data[OrderMaterial]['.$i.'][qty]', 'label' => false, 'div' => false)); ?>
								<div class="units inline" id="material_<?php echo $i; ?>_units"></div>
							</div>
							<?php endfor; ?>
						</div>
						<?php echo $this->Html->link('add more', array('#'), array('id'=>'add_more_materials', 'class'=>'add_more clear left')); ?>
					</div>
				</fieldset>
			</div>
			<div class="col-timesheet-2">
				<fieldset class="modal">	
					<div class="left fieldset-wrapper">
						<label><?php echo __('Purchases'); ?></label>
						<div id="material_purchase_container">
							<div class="material_purchase_item" id="material_purchase_item_0">
								<?php echo $this->Form->input('PurchasesOrder.description', array('type'=>'text', 'id'=>'purchase_0', 'class'=>'material_purchase', 'name' => 'data[PurchasesOrder][0][description]', 'label' => false, 'div' => false)); ?>
								<?php echo $this->Form->input('PurchasesOrder.amount', array('class' => 'material_purchase_amount amount_input num_only', 'id'=>'purchase_amount_0', 'name' => 'data[PurchasesOrder][0][amount]', 'label' => false, 'div' => false, 'placeholder' => '$')); ?>
							</div>
						</div>
						<?php echo $this->Html->link('add more', array('#'), array('id'=>'add_more_store_materials', 'class'=>'add_more clear left')); ?>
						<br /><br />
						<label><?php echo __('Equipment'); ?></label>
						<div id="equipment_input_container">
							<div class="equipment_input_item" id="equipment_input_item_0">
								<?php echo $this->Form->input('EquipmentItemsOrder.description', array('type'=>'text', 'id'=>'equipment_0', 'class'=>'equipment_input', 'name' => 'data[EquipmentItemsOrder][0][description]', 'label' => false, 'div' => false)); ?>
								<?php echo $this->Form->input('EquipmentItemsOrder.amount', array('class' => 'equipment_amount amount_input num_only', 'id'=>'equipment_amount_0', 'name' => 'data[EquipmentItemsOrder][0][amount]', 'label' => false, 'div' => false, 'placeholder' => '$')); ?>
							</div>
						</div>
						<?php echo $this->Html->link('add more', array('#'), array('id'=>'add_more_equipment', 'class'=>'add_more clear left')); ?>
					</div>
				</fieldset>
			</div>
		</div>
		<?php echo $this->Form->hidden('ScheduleSessionTime.id'); ?>
		<?php echo $this->Form->hidden('ScheduleSessionTime.schedule_session_id'); ?>
		<?php echo $this->Form->hidden('ScheduleSessionTime.worker_id'); ?>
		<?php echo $this->Form->hidden('ScheduleSessionTime.date_session'); ?>
		<?php echo $this->Form->hidden('Order.id', array('value' => $order['Order']['id'])); ?>
		<?php echo $this->Form->submit(__('Save', true)); ?>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<?php $this->end(); ?>