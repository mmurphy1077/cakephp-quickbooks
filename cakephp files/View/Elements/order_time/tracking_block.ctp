<h4>
	<?php echo __('Labor Hours'); ?>&nbsp;&nbsp;
	<span class="small alert"><?php echo $permission_explination; ?></span>
</h4>
<fieldset>
	<div class="fieldset-wrapper">
		<div class="clear">
			<label><?php echo __('Type'); ?></label>
			<div class="grid">
				<div class="col-1of2">
					<div class="buttonset"><?php echo $this->Form->radio('OrderTime.type', $types, array_merge($permission_attr, array('div' => array('class' => 'input larger_label_space'), 'legend' => false))); ?></div><br />&nbsp;
				</div>
				<div class="col-1of2">&nbsp;</div>
			</div>
		</div>
		<div class="clear">
			<label><?php echo __('Status'); ?></label>
			<?php 
			$value = $this->data['OrderTime']['status'];
			if(array_key_exists($value, $statuses)) {
				echo $statuses[$value] . '<br />&nbsp;';
			} ?>
		<?php #echo $this->Form->input('Order.status', array('value' => $value, 'empty' => 'Select', 'options' => $statuses)); ?>
		</div>
		
		<?php 
		$hidden_worker_id = null;
		$hidden_worker_name = '';
		if(!empty($workers)) {
			if(count($workers) == 1) {
				reset($workers);
				$hidden_worker_id = key($workers);
				$hidden_worker_name = $workers[$hidden_worker_id];
			} 
		} else {
			$hidden_worker_id = $__user['User']['id'];
			$hidden_worker_name = $__user['User']['name_first'] . ' ' . $__user['User']['name_last'];
		}
		if(!empty($hidden_worker_id)) : 
			echo $this->Form->hidden('OrderTime.worker_id', array('value' => $hidden_worker_id));
			echo $this->Form->hidden('OrderTime.worker_name', array('value' => $hidden_worker_name));
		else : ?>
		<div class="clear">
			<label><?php echo __('Worker'); ?></label>
			<select <?php echo implode(', ', $permission_attr); ?> id="worker-select">
				<?php foreach($workers as $key=>$data) : ?>
				<?php 	echo $data; ?>
				<?php endforeach; ?>
			</select>
			<?php echo $this->Form->hidden('OrderTime.worker_id', array('value' => $this->data['OrderTime']['worker_id'])); ?>
			<?php 
			$name = $this->data['Worker']['name_first'] . ' ' . $this->data['Worker']['name_last'];
			echo $this->Form->hidden('OrderTime.worker_name', array('value' => $name)); ?>
			<br /><br />&nbsp;
		</div>
		<?php 
		endif; ?>
		
		<div class="clear">
			<label><?php echo __('Date'); ?></label>
			<div class="inline left">
				<div class="time_select_container">
					<?php 
					if($__browser_view_mode['view_device'] == 'computer') :
						echo $this->Form->input('OrderTime.date_session', array_merge($permission_attr, array('type' => 'text', 'label' => false, 'class' => 'datepicker', 'value' => date('m/d/Y', strtotime($this->data['OrderTime']['date_session'])), 'div' => array('class' => 'input'))));				
					else :
						$readonly = '';
						$disabled = '';
						if(array_key_exists('readonly', $permission_attr)) {
							$readonly = 'readonly="' . $permission_attr['readonly'] . '"';
						}
						if(array_key_exists('disabled', $permission_attr)) {
							$disabled = 'disabled="' . $permission_attr['disabled'] . '"';
						}
						// Mobile display devices will use the devices date picker. ?>
						<div class="input">
							<input id="OrderTimeDateSession" <?php echo $readonly; ?> <?php echo $disabled; ?> class="" type="date" value="<?php echo $this->data['OrderTime']['date_session']; ?>" name="data[OrderTime][date_session]">
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="clear">
			<label><?php echo __('Started'); ?></label>
			<div class="inline left">
				<div class="time_select_container">
					<?php echo $this->Form->input('OrderTime.hour_start_work', array_merge($permission_attr, array('class' => 'hours_only time_input hour_start', 'id' => 'hour_start_work', 'label' => false, 'div' => false))); ?>&nbsp;:
					<?php echo $this->Form->input('OrderTime.minute_start_work', array_merge($permission_attr, array('class' => 'minutes_only time_input minute_start', 'id' => 'minute_start_work', 'label' => false, 'div' => false))); ?>
					<?php echo $this->Form->input('OrderTime.post_meridiem_work', array_merge($permission_attr, array('class' => 'time_input post_meridiem_start', 'id' => 'post_meridiem_start_work', 'label' => false, 'div' => false, 'options' => array('am' =>'am', 'pm' => 'pm')))); ?>
					<?php echo $this->Form->hidden('OrderTime.time_start_work', array_merge($permission_attr, array('id' => 'time_start_work', 'class' => 'time_start', 'label' => false, 'div' => false))); ?>
				</div>
				<div class="error-message clear" id="error-message-hour_start"></div>
			</div>
		</div>
		<div class="clear">
			<label><?php echo __('Ended'); ?></label>
			<div class="inline left">
				<div class="time_select_container">
					<?php 
					$end_disabled = 'disabled';
					if(!empty($this->data['OrderTime']['hour_end_work'])) {
						$end_disabled = '';
					} ?>
					<?php #echo $this->Form->input('OrderTime.time_end_display_work', array('type' => 'select', 'empty' => '', 'options' => null, 'id' => 'time_end_display_work', 'class' => 'time_end_display', 'label' => false, 'div' => false, 'readonly' => 'readonly', 'disabled' => 'disabled')); ?>
					<?php echo $this->Form->input('OrderTime.hour_end_work', array_merge($permission_attr, array('class' => 'hours_only time_input hour_end', 'id' => 'hour_end_work', 'label' => false, 'div' => false, 'disabled' => $end_disabled))); ?>&nbsp;:
					<?php echo $this->Form->input('OrderTime.minute_end_work', array_merge($permission_attr, array('class' => 'minutes_only time_input minute_end', 'id' => 'minute_end_work', 'label' => false, 'div' => false, 'disabled' => $end_disabled))); ?>
					<?php echo $this->Form->input('OrderTime.post_meridiem_end_work', array_merge($permission_attr, array('class' => 'time_input post_meridiem_end', 'id' => 'post_meridiem_end_work', 'label' => false, 'div' => false, 'disabled' => $end_disabled, 'options' => array('am' =>'am', 'pm' => 'pm')))); ?>
					<?php echo $this->Form->hidden('OrderTime.time_end_work', array('id' => 'time_end_work', 'class' => 'time_end', 'label' => false, 'div' => false, 'readonly' => 'readonly')); ?>
				</div>
				<div class="error-message clear" id="error-message-hour_end"></div>
			</div>
			<br /><br />&nbsp;
		</div>
		
		<?php 
		if($timer_enabled && empty($permission_attr)) {
			echo $this->element('timer', array('data' => $this->data)); 
		} ?>
		<div class="clear">
			<label><?php echo __('Adjust'); ?></label>
			<div id="timesheet-hours">
				<div>
					<label><?php echo __('Reg'); ?></label><br />
					<?php echo $this->Form->input('OrderTime.time_reg', array_merge($permission_attr, array('label' => false, 'id' => 'time_input_reg', 'class' => 'num_only numerical', 'div' => false))); ?>
				</div>
				<div>
					<label><?php echo __('OT'); ?></label><br />
					<?php echo $this->Form->input('OrderTime.time_ot', array_merge($permission_attr, array('label' => false, 'id' => 'time_input_ot', 'class' => 'num_only numerical', 'div' => false))); ?>
				</div>
				<div>
					<label><?php echo __('DT'); ?></label><br />
					<?php echo $this->Form->input('OrderTime.time_dt', array_merge($permission_attr, array('label' => false, 'id' => 'time_input_dt', 'class' => 'num_only numerical', 'div' => false))); ?>
				</div>
			</div>
			<?php echo $this->Form->hidden('OrderTime.session-time', array('id' => 'session-time')); ?>
		</div>
		<div class="clear">
			<label><?php echo __('Submit'); ?></label>
			<div class="">
				<?php echo $this->Form->input('submit', array_merge($permission_attr, array('type'=>'checkbox', 'label' => false, 'div' => false))); ?>
			</div>
		</div>
		<br />&nbsp;
	</div>
</fieldset>