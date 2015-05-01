<?php
$title = 'Materials';
$action = 'edit';
if(isset($type) && $type == 'purchase') {
	$title = 'Purchases';
	$action = 'edit_purchase';
}
?>
<h4><?php echo $title; ?></h4>
<?php
$this->Html->script('jquery/jquery.jeditable', false);
$workerStatuses = array();
$workerStatuses[ORDER_MATERIAL_STATUS_INPROCESS] = $statuses[ORDER_MATERIAL_STATUS_INPROCESS];
$workerStatuses[ORDER_MATERIAL_STATUS_SUBMIT] = $statuses[ORDER_MATERIAL_STATUS_SUBMIT];
$adminStatuses = array();
$adminStatuses[ORDER_MATERIAL_STATUS_INPROCESS] = $statuses[ORDER_MATERIAL_STATUS_INPROCESS];
$adminStatuses[ORDER_MATERIAL_STATUS_SUBMIT] = $statuses[ORDER_MATERIAL_STATUS_SUBMIT];
$adminStatuses[ORDER_MATERIAL_STATUS_APPROVED] = $statuses[ORDER_MATERIAL_STATUS_APPROVED];

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
?>
<?php if (!empty($materials)): ?>
	<?php foreach ($materials as $result): 
			$current_date_session = '';  ?>
		<table id="order_materials_index_table" class="standard order nohover">
			<?php if(!empty($result['OrderMaterial'])) : ?>
			<?php 	foreach ($result['OrderMaterial'] as $result_om): 
						$controller = 'order_materials';
						$editClass = 'edit-status-material';
						if(array_key_exists('model', $result_om['OrderMaterial'])) {
							if($result_om['OrderMaterial']['model'] == 'OrderExpense') {
								$controller = 'order_expenses';
								$editClass = 'edit-status-expense';
							}
						} ?>
			<tr id="timer-<?php echo $result_om['OrderMaterial']['id']; ?>">
				<td><?php echo $this->Web->dt($result_om['OrderMaterial']['date_session'], 'M j Y'); ?></td>
				<td><?php echo $result['Creator']['name_first'] . ' ' . $result['Creator']['name_last']; ?></td>
				<!-- 
				<td><?php echo ucfirst($result_om['OrderMaterial']['type']); ?></td>
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
				 -->
				<td>
					<?php if(!empty($result_om['OrderMaterial']['description'])) {
						echo ucfirst(nl2br($result_om['OrderMaterial']['description'])) . '<br />';
					} 
					if(!empty($result_om['OrderMaterialItem'])) {
						foreach($result_om['OrderMaterialItem'] as $mi) {
							echo $mi['name'] . ' ' . '(' . number_format($mi['qty'],2) . ')<br />'; 
						}
					} ?>
				</td>
				<td class="actions">
					<?php echo $this->Html->link(__('Details'), array('controller' => 'order_materials', 'action' => $action, $result_om['OrderMaterial']['id']), array('class' => 'row-click')); ?>
				</td>
			</tr>
		<?php 		endforeach;
				endif; 
		 ?>
	</table>
	<br /><br /><br />
	<?php endforeach; ?>
<?php else: ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array('no_items',))); ?>
	</div>
<?php endif; ?>