<?php 
echo $this->Html->script('creationsite/reminders', false);

$display_status = '';
$display_assigned = 'Unassigned';
$display_touch_back = false;
switch($model) {
	case 'Contact' :
		$status = $activeStatuses;
		$current_status = $data['Contact']['status'];
		$current_assigned_id = $data['Contact']['assigned_to_id'];
		if(array_key_exists($current_status, $status)) {
			$display_status = $status[$current_status];
		}
		if(!empty($current_assigned_id) && array_key_exists($current_assigned_id, $__assigned_tos)) {
			$display_assigned = $__assigned_tos[$current_assigned_id];
		}
		$display_touch_back = true;
		break;
		
	case 'Order' :
		$status = $statuses_order;
		$current_status = $data['Order']['status'];
		$current_assigned_id = $data['Order']['assigned_to_id'];
		if(array_key_exists($current_status, $status)) {
			$display_status = $status[$current_status];
		}
		if(!empty($current_assigned_id) && array_key_exists($current_assigned_id, $__assigned_tos)) {
			$display_assigned = $__assigned_tos[$current_assigned_id];
		}
		$display_touch_back = true;
		break;
		
	case 'Quote' :
		$status = $statuses_quote;
		$current_status = $data['Quote']['status'];
		$current_assigned_id = $data['Quote']['assigned_to_id'];
		if(array_key_exists($current_status, $status)) {
			$display_status = $status[$current_status];
		}
		if(!empty($current_assigned_id) && array_key_exists($current_assigned_id, $__assigned_tos)) {
			$display_assigned = $__assigned_tos[$current_assigned_id];
		}
		$display_touch_back = true;
		break;
		
	case 'Invoice' :
		$status = $statuses_quote;
		break;
}
if(!isset($data)) {
	$data = null;
}
if(!isset($current_status)) {
	$current_status = null;
}
if(!isset($current_assigned_id) || empty($current_assigned_id)) {
	$current_assigned_id = 0;
}  ?>
<div id="status-container-display" class="status-container-display">
	<div id="status-display">
		<div class="label pad">Status:</div>&nbsp;&nbsp;
		<div id="status-value" class="inline"><?php echo $display_status; ?></div>
	</div>
	<div id="assigned-to-display">
		<div class="label pad">Assigned To:</div>&nbsp;&nbsp;
		<div id="assigned-to-value" class="inline"><?php echo $display_assigned; ?></div>
	</div>
	
	<?php 
	if($display_touch_back) : ?>
	<div id="reminder-display">
		<?php if(array_key_exists('date_reminder', $data['Reminder']) && !empty($data['Reminder']['date_reminder'])) : 
			// Determine if the reminder is today or tomorrow 
			$now = time(); // or your date as well
	     	$reminder = strtotime($data['Reminder']['date_reminder']);
	     	$datediff = ceil(($reminder - $now)/(60*60*24));
	     	$value = '';
	     	$class = '';
	     	if($datediff == 0) {
				$value = 'Today';
				$class = 'alert';
			} else if($datediff == 1) {
				$value = 'Tomorrow';
				$class = 'warning';
			} else if($datediff < 0) {
				$value = 'OVERDUE';
				$class = 'alert';
			}
			if(!empty($value)) : ?>
				<div class="label pad">Callback:</div>&nbsp;&nbsp;
				<div id="reminder-value" class="inline"><span class="<?php echo $class; ?>"><b><?php echo $value; ?></b></span></div>
			<?php endif; 
		endif; ?>
	</div>
	<?php endif; ?>
</div>
<div id="status-assignment-container" class="status-assignment-container">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php echo $this->Form->create($model, array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
			<?php echo $this->Form->input('status', array('value' => $current_status, 'options' => $status, 'id' => 'status-container-status-select')); ?>
			<?php echo $this->Form->input('assign_to_id', array('value' => $current_assigned_id, 'options' => $__assigned_tos, 'id' => 'status-container-assigned-select')); ?>
			<?php echo $this->Form->hidden('model', array('value' => $model, 'id' => 'status-container-model')); ?>
			<?php echo $this->Form->hidden('foreign_key', array('value' => $foreign_key, 'id' => 'status-container-foreign-key')); ?>
			<?php if($display_touch_back) : ?>
				<div id="touchbase-container" class="status-container-options">
					<?php echo $this->element('communication/callback', array('data' => $data['Reminder'])); ?>
				</div>
			<?php endif; ?>
			<?php echo $this->Form->end(); ?>
			<div id="status-container-options" class="status-container-options">
			<?php 
			switch($model) {
				case 'Contact' :
					echo $this->element('header_tabs', array('data' => $data, 'permissions' => $permissions, 'wrapper' => 'ul'));
					break;
					
				case 'Order' :
					echo $this->element('header_tabs', array('order' => $data, 'permissions' => $permissions, 'wrapper' => 'ul'));
					break;
					
				case 'Quote' :
					echo $this->element('header_tabs', array('quote' => $data, 'permissions' => $permissions, 'wrapper' => 'ul'));
					break;
					
				case 'Invoice' :
					break;
			} ?>
			</div>
		</div>
	</div>
	<div id="status-container-button-close"></div>
</div>
<div id="status-container-button-open"></div>