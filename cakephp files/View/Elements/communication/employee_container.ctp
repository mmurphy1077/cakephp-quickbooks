<?php
if(!isset($current_selected_users)) {
	$current_selected_users = array();
}
if(!empty($employees)) {
	$count = count($employees) / 3;
	$left = array();
	$middle = array();
	$right = $employees;
	$i = 0;
	foreach($employees as $key=>$data) {
		if($i < $count) {
			$left[$key] = $data;
			unset($right[$key]);
			unset($employees[$key]);
		}
		$i = $i + 1;
	}
	$i = 0;
	foreach($employees as $key=>$data) {
		if($i < $count) {
			$middle[$key] = $data;
			unset($right[$key]);
			unset($employees[$key]);
		}
		$i = $i + 1;
	}
	$result['left'] = $left;
	$result['middle'] = $middle;
	$result['right'] = $right;
} 
?>
<h2>Employee List</h2> (select employees to be included)
<div id="employee-container" class="clear">
	<div class="grid">
		<div class="col-1of3 clear">
			<?php if(!empty($result['left'])) : ?>
			<?php 	foreach($result['left'] as $key=>$data) : ?>
			<?php 		$isSelected = '';
						foreach($current_selected_users as $current_selected_user) {
							if(($current_selected_user['reply_to_model'] == 'User') &&  ($current_selected_user['reply_to_id'] == $key)) {
								$isSelected = 'checked';
							}
						} ?>
			<?php 		echo $this->Form->input('employee_check', array('checked' => $isSelected, 'type'=>'checkbox', 'label' => $data, 'id' => 'employee_check_' . $key, 'class' => 'employee_check')); ?>	
			<?php 	endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="col-1of3">
			<?php if(!empty($result['middle'])) : ?>
			<?php 	foreach($result['middle'] as $key=>$data) : ?>
			<?php 		echo $this->Form->input('employee_check', array('type'=>'checkbox', 'label' => $data, 'id' => 'employee_check_' . $key, 'class' => 'employee_check')); ?>	
			<?php 	endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="col-1of3">
			<?php if(!empty($result['right'])) : ?>
			<?php 	foreach($result['right'] as $key=>$data) : ?>
			<?php 		echo $this->Form->input('employee_check', array('type'=>'checkbox', 'label' => $data, 'id' => 'employee_check_' . $key, 'class' => 'employee_check')); ?>				
			<?php 	endforeach; ?>
			<?php endif; ?>
		</div>
		<div id="employee-data-container" class="hide">
			<?php 
			if(!empty($employee_emails)) {
				foreach($employee_emails as $key => $data) {
					echo $this->Form->hidden('employee_email_' . $key, array('id' => 'employee_email_' . $key, 'value' => $data, 'class' => 'employee_email'));
				}
			}
			?>
		</div>
	</div>
</div>