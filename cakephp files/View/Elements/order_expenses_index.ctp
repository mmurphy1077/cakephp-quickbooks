<?php
$this->Html->script('jquery/jquery.jeditable', false);
$workerStatuses = array();
$workerStatuses[ORDER_MATERIAL_STATUS_INPROCESS] = $statuses[ORDER_MATERIAL_STATUS_INPROCESS];
$workerStatuses[ORDER_MATERIAL_STATUS_SUBMIT] = $statuses[ORDER_MATERIAL_STATUS_SUBMIT];
$adminStatuses = array();
$adminStatuses[ORDER_MATERIAL_STATUS_INPROCESS] = $statuses[ORDER_MATERIAL_STATUS_INPROCESS];
$adminStatuses[ORDER_MATERIAL_STATUS_SUBMIT] = $statuses[ORDER_MATERIAL_STATUS_SUBMIT];
$adminStatuses[ORDER_MATERIAL_STATUS_APPROVED] = $statuses[ORDER_MATERIAL_STATUS_APPROVED];

echo $this->element('js'.DS.'editable', array(
	'editClass' => 'edit-status',
	'postback' => $this->Html->url(array('controller' => 'order_expenses', 'action' => 'edit_field')),
	'values' => $workerStatuses,
	'output' => $workerStatuses,
	'submitOnChange' => 1,
));
echo $this->element('js'.DS.'editable', array(
	'editClass' => 'edit-status-admin',
	'postback' => $this->Html->url(array('controller' => 'order_expenses', 'action' => 'edit_field')),
	'values' => $adminStatuses,
	'output' => $adminStatuses,
	'submitOnChange' => 1,
)); ?>
<?php if (!empty($expenses)): ?>
	<?php foreach ($expenses as $result): 
			$current_date_session = '';  ?>
		<table id="order_expense_index_table" class="standard order nohover">
			<tr>
				<th colspan="4"><?php echo $result['Creator']['name_first'] . ' ' . $result['Creator']['name_last']; ?></th>
				<!-- 
				<th>&nbsp;</th>
				<th><?php echo __('Date'); ?></th>
				<th><?php echo __('Description'); ?></th>
				 -->
				<th>&nbsp;</th>
			</tr>
			<?php if(!empty($result['OrderExpense'])) : ?>
			<?php 	foreach ($result['OrderExpense'] as $result_om):	
						$date_session_display = '';
						if($result_om['OrderExpense']['date_session'] != $current_date_session) {
							if(!empty($current_date_session)) {
								echo $this->element('table_bottom_border', array('colspan' => 5));
							}
							$date_session_display = $this->Web->dt($result_om['OrderExpense']['date_session'], 'D, M j');
						}
						$current_date_session = $result_om['OrderExpense']['date_session']; ?>
			<tr id="timer-<?php echo $result_om['OrderExpense']['id']; ?>">
				<td><?php echo $date_session_display; ?></td>
				<td><?php echo ucfirst($result_om['OrderExpense']['type']); ?></td>
				<td><?php echo ucfirst(nl2br($result_om['OrderExpense']['description'])); ?></td>
				<td class="nowrap inline-edit"><?php /* 
					Can the current user update the status
					*/ 
					$can_update_status = false;
					if($permissions['material_approve'] == 1) {
						$can_update_status = true;
						$editClass = 'edit-status-admin';
					} else if (($permissions['material_read_only'] != 1 && (($result_om['OrderExpense']['status'] > ORDER_TIME_STATUS_REJECT) && ($result_om['OrderExpense']['status'] < ORDER_TIME_STATUS_APPROVE)) && ($permissions['labor_view_all'] == 1 || ($permissions['labor_view_own'] == 1 && $result_om['OrderExpense']['worker_id'] == $__user['User']['id'])))) {
						$can_update_status = true;
						$editClass = 'edit-status';
					}
					if($can_update_status) {
						echo '<div id="OrderExpense-' . $result_om['OrderExpense']['id'] . '-status" class="inline-edit inline-edit-status ' . $editClass . '">' . $statuses[$result_om['OrderExpense']['status']] . '</div>';
					} else {
						echo $statuses[$result_om['OrderExpense']['status']]; 
					} ?>
				</td>
				<td class="actions"><?php echo $this->Html->link(__('Details'), array('controller' => 'order_expenses', 'action' => 'edit', $result_om['OrderExpense']['id'], $type), array('class' => 'row-click')); ?></td>
			</tr>
		<?php 		endforeach;
				endif; 
			echo $this->element('table_bottom_border', array('colspan' => 5)); ?>
	</table>
	<br /><br /><br />
	<?php endforeach; ?>
<?php else: ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array('no_items',))); ?>
	</div>
<?php endif; ?>