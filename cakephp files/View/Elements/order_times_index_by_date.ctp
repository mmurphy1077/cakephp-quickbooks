<?php
$this->Html->script('jquery/jquery.jeditable', false);
$workerStatuses = array();
$workerStatuses[ORDER_TIME_STATUS_INPROCESS] = $statuses[ORDER_TIME_STATUS_INPROCESS];
$workerStatuses[ORDER_TIME_STATUS_SUBMIT] = $statuses[ORDER_TIME_STATUS_SUBMIT];
$adminStatuses = array();
$adminStatuses[ORDER_TIME_STATUS_INPROCESS] = $statuses[ORDER_TIME_STATUS_INPROCESS];
$adminStatuses[ORDER_TIME_STATUS_SUBMIT] = $statuses[ORDER_TIME_STATUS_SUBMIT];
$adminStatuses[ORDER_TIME_STATUS_APPROVE] = $statuses[ORDER_TIME_STATUS_APPROVE];
$adminStatuses[ORDER_TIME_STATUS_REJECT] = $statuses[ORDER_TIME_STATUS_REJECT];
$adminStatuses[ORDER_TIME_STATUS_UNBILLABLE] = $statuses[ORDER_TIME_STATUS_UNBILLABLE];

if($permissions['labor_approve'] == 1) {
 	echo $this->element('js'.DS.'editable.order.time.status.init', array('adminStatuses' => $adminStatuses)); 
} else {
	echo $this->element('js'.DS.'editable', array(
		'editClass' => 'edit-status',
		'postback' => $this->Html->url(array('controller' => 'order_times', 'action' => 'edit_field')),
		'values' => $workerStatuses,
		'output' => $workerStatuses,
		'submitOnChange' => 1,
	));
}
 ?>
<?php if (!empty($labor_hours)): ?>
	<?php foreach ($labor_hours as $result):
			$current_worker_id = '';  ?>
		<table id="order_times-index_table" class="standard order nohover">
			<tr>
				<th colspan="4"><?php echo $result['date']; ?></th>
				<th class="hidden-xs hidden-sm"><?php echo __(''); ?></th>
				<!-- 
				<th>&nbsp;</th>
				<th><?php echo __('Date'); ?></th>
				<th><?php echo __('Hours Entered'); ?></th>
				<th><?php echo __('Time'); ?></th>
				<th class="hidden-xs hidden-sm"><?php echo __(''); ?></th>
				-->
				<th><?php echo __('Status'); ?></th>
				<th><?php echo __('Invoiced On'); ?></th>
				<th>&nbsp;</th>
			</tr>
			<?php $total_date_time = 0; ?>
			<?php if(!empty($result['OrderTime'])) : ?>
			<?php 	foreach ($result['OrderTime'] as $result_ot):  
						$worker_name_display = ''; 
						if($result_ot['OrderTime']['worker_id'] != $current_worker_id) {
							if(!empty($current_worker_id)) {
								echo $this->element('order_times_index_daily_total', array('total_daily_time' => $total_date_time, 'border' => true));
							}
							$worker_name_display = $this->Web->humanName($result_ot['Worker'], 'full') . ' <span class="small">(' . $result_ot['Worker']['Group']['name'] . ')</span>';
							$total_date_time = 0;
						}
						
						$current_worker_id = $result_ot['OrderTime']['worker_id']; ?>
			<tr id="timer-<?php echo $result_ot['OrderTime']['id']; ?>">
				<td><?php echo $worker_name_display; ?></td>
				<td><?php echo ucfirst($result_ot['OrderTime']['type']); ?></td>
				<td>
					<?php 
					$endTime = null;
					$totalTime = '';
					if(!empty($result_ot['OrderTime']['time_end_work'])) {
						$endTime = ' - ' . date('g:i a', strtotime($result_ot['OrderTime']['time_end_work']));
						$totalTime = '<b>(' . number_format($result_ot['OrderTime']['time_total'], 2) . ' hours)</b>';
					}
					$total_date_time = $total_date_time + $result_ot['OrderTime']['time_total'];
					?>
					<?php echo date('g:i a', strtotime($result_ot['OrderTime']['time_start_work'])) . $endTime; ?>
					&nbsp;&nbsp;<?php echo $totalTime; ?>
				</td>
				<td class="hidden-xs hidden-sm">
					<?php 
					$ot = 0;
					$dt = 0;
					$reg = 0;
					if(!empty($result_ot['OrderTime']['time_reg'])) {
						$reg = number_format($result_ot['OrderTime']['time_reg'], 2);
					}
					if(!empty($result_ot['OrderTime']['time_ot'])) {
						$ot = number_format($result_ot['OrderTime']['time_ot'], 2);
					}
					if(!empty($result_ot['OrderTime']['time_dt'])) {
						$dt = number_format($result_ot['OrderTime']['time_dt'], 2);
					}
					?>
					Reg: <b><?php echo $reg; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;OT: <b><?php echo $ot; ?></b>&nbsp;&nbsp;&nbsp;&nbsp;DT: <b><?php echo $dt; ?></b>
				</td>
				<td>
					<?php 
					$timer_class = 'hide';
					if(!empty($result_ot['Timer'])) {
						$timer_class = '';
					}
					echo $this->Html->image('timer/timer00.png', array('id' => 'timer-image-'.$result_ot['OrderTime']['id'], 'class' => $timer_class))?>
				</td>
				<td class="nowrap inline-edit"><?php /* 
					Can the current user update the status
					*/ 
					$can_update_status = false;
					if($permissions['labor_approve'] == 1) {
						$can_update_status = true;
						$editClass = 'edit-status-admin';
					} else if (($permissions['labor_read_only'] != 1 && (($result_ot['OrderTime']['status'] > ORDER_TIME_STATUS_REJECT) && ($result_ot['OrderTime']['status'] < ORDER_TIME_STATUS_APPROVE)) && ($permissions['labor_view_all'] == 1 || ($permissions['labor_view_own'] == 1 && $result_ot['OrderTime']['worker_id'] == $__user['User']['id'])))) {
						$can_update_status = true;
						$editClass = 'edit-status';
					}
					if($can_update_status) {
						echo '<div id="OrderTime-' . $result_ot['OrderTime']['id'] . '-status" class="inline-edit inline-edit-status ' . $editClass . '">' . $statuses[$result_ot['OrderTime']['status']] . '</div>';
					} else {
						echo $statuses[$result_ot['OrderTime']['status']]; 
					} ?>
				</td>
				<td>
				<?php if(!empty($result_ot['Invoice'])) : ?>
				<?php echo $this->Web->dt($result_ot['Invoice']['created'], 'text_short'); ?> (<b><?php echo $invoice_statuses[$result_ot['Invoice']['status']]; ?></b>)
				<?php endif; ?>
				</td>
				<td class="actions"><?php echo $this->Html->link(__('Details'), array('controller' => 'order_times', 'action' => 'edit', $result_ot['OrderTime']['id']), array('class' => 'row-click')); ?></td>
			</tr>
			<?php if(!empty($result_ot['Message'])) : ?>
			<tr>
				<td colspan="2">&nbsp;</td>
				<td colspan="1">Work Performed:</td>
				<td colspan="4">
				<?php foreach($result_ot['Message'] as $message): ?>
				<?php echo $message['content']; ?> <br />
				<?php endforeach; ?>
				</td>
			</tr>
			<?php endif; ?>
		<?php 		endforeach;
				endif; 
				echo $this->element('order_times_index_daily_total', array('total_daily_time' => $total_date_time, 'border' => false)); ?>
	</table>
	<br />
	<?php endforeach; ?>
<?php else: ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array('no_items',))); ?>
	</div>
<?php endif; ?>