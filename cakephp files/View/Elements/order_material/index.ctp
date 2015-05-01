<?php
$this->Html->script('jquery/jquery.jeditable', false);
$workerStatuses = array();
$workerStatuses[ORDER_MATERIAL_STATUS_INPROCESS] = $statuses[ORDER_MATERIAL_STATUS_INPROCESS];
$workerStatuses[ORDER_MATERIAL_STATUS_SUBMIT] = $statuses[ORDER_MATERIAL_STATUS_SUBMIT];
$adminStatuses = array();
$adminStatuses[ORDER_MATERIAL_STATUS_INPROCESS] = $statuses[ORDER_MATERIAL_STATUS_INPROCESS];
$adminStatuses[ORDER_MATERIAL_STATUS_SUBMIT] = $statuses[ORDER_MATERIAL_STATUS_SUBMIT];
$adminStatuses[ORDER_MATERIAL_STATUS_APPROVED] = $statuses[ORDER_MATERIAL_STATUS_APPROVED];
$adminStatuses[ORDER_MATERIAL_STATUS_UNBILLABLE] = $statuses[ORDER_MATERIAL_STATUS_UNBILLABLE];

$can_update_status = false;
$editClass = 'edit-status-material';
$editible_status = $workerStatuses;
if($permissions['material_approve'] == 1) {
	$can_update_status = true;
	$editible_status = $adminStatuses;
} 
echo $this->element('js'.DS.'editable', array(
	'editClass' => 'edit-status-material',
	'postback' => $this->Html->url(array('controller' => 'order_materials', 'action' => 'edit_field')),
	'values' => $editible_status,
	'output' => $editible_status,
	'submitOnChange' => 1,
));
echo $this->element('js'.DS.'editable', array(
	'editClass' => 'edit-status-expense',
	'postback' => $this->Html->url(array('controller' => 'order_expenses', 'action' => 'edit_field')),
	'values' => $editible_status,
	'output' => $editible_status,
	'submitOnChange' => 1,
)); 
$edit_action = 'edit';
if(isset($type)) {
	$edit_action = 'edit_'.$type;
}
?>
<?php if (!empty($materials)): ?>
	<?php foreach ($materials as $result): 
			$current_date_session = '';  ?>
		<table id="order_materials_index_table" class="standard order nohover">
			<tr>
				<th colspan="3"><?php echo $result['Creator']['name_first'] . ' ' . $result['Creator']['name_last']; ?></th>
				<!-- 
				<th>&nbsp;</th>
				<th><?php echo __('Date'); ?></th>
				<th><?php echo __('Description'); ?></th>
				 -->
				<th>Invoiced On</th>
				<th>&nbsp;</th>
			</tr>
			<?php if(!empty($result['OrderMaterial'])) : ?>
			<?php 	foreach ($result['OrderMaterial'] as $result_om):  
						$controller = 'order_materials';
						$editClass = 'edit-status-material';
						if(array_key_exists('model', $result_om['OrderMaterial'])) {
							if($result_om['OrderMaterial']['model'] == 'OrderExpense') {
								$controller = 'order_expenses';
								$editClass = 'edit-status-expense';
							}
						}
			
						$date_session_display = '';
						if($result_om['OrderMaterial']['date_session'] != $current_date_session) {
							if(!empty($current_date_session)) {
								echo $this->element('table_bottom_border', array('colspan' => 6));
							}
							$date_session_display = $this->Web->dt($result_om['OrderMaterial']['date_session'], 'D, M j Y');
						}
						$current_date_session = $result_om['OrderMaterial']['date_session']; ?>
			<tr id="timer-<?php echo $result_om['OrderMaterial']['id']; ?>">
				<td><?php echo $date_session_display; ?></td>
				<!-- <td><?php echo ucfirst($result_om['OrderMaterial']['type']); ?></td> -->
				<td><?php echo ucfirst(nl2br($result_om['OrderMaterial']['description'])); ?></td>
				<td class="nowrap inline-edit"><?php /* 
					Can the current user update the status
					*/ 
					if (($permissions['material_read_only'] != 1 && (($result_om['OrderMaterial']['status'] < ORDER_MATERIAL_STATUS_APPROVED)) && ($permissions['material_view_all'] == 1 || ($permissions['material_view_own'] == 1 && $result_om['OrderMaterial']['creator_id'] == $__user['User']['id'])))) {
						$can_update_status = true;
					}
					
					if($can_update_status) {
						echo '<div id="OrderMaterial-' . $result_om['OrderMaterial']['id'] . '-status" class="inline-edit inline-edit-status ' . $editClass . '">' . $statuses[$result_om['OrderMaterial']['status']] . '</div>';
					} else {
						echo $statuses[$result_om['OrderMaterial']['status']]; 
					} ?>
				</td>
				<td>
					<?php if(!empty($result_om['Invoice'])) : ?>
					<?php echo $this->Web->dt($result_om['Invoice']['created'], 'text_short'); ?>&nbsp;&nbsp;
					<b>(<?php echo $invoice_status[$result_om['Invoice']['status']]; ?>)</b>
					<?php endif; ?>
				</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Details'), array('controller' => $controller, 'action' => $edit_action, $result_om['OrderMaterial']['id']), array('class' => 'row-click')); ?>
				</td>
			</tr>
		<?php 		endforeach;
				endif; 
				echo $this->element('table_bottom_border', array('colspan' => 6)); ?>
	</table>
	<br /><br /><br />
	<?php endforeach; ?>
<?php else: ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array('no_items',))); ?>
	</div>
<?php endif; ?>