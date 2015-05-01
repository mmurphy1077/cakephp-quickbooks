<?php
echo $this->Html->script('creationsite/order.material.log', false);
$permission_attr = null; 
if($permissions['material_read_only'] == 1) {
	$permission_attr = 'readonly disabled';
} ?>
<?php echo $this->Form->hidden('OrderExpense.id'); ?>
<?php echo $this->Form->hidden('OrderExpense.order_id'); ?>
<?php echo $this->Form->hidden('OrderExpense.status'); ?>
<?php echo $this->Form->hidden('OrderExpense.type', array('value' => $type)); ?>
<?php echo $this->Form->hidden('Order.name', array('value'=>$order['Order']['name'])); ?>
<?php echo $this->Form->hidden('Order.customer_name', array('value'=>$order['Order']['customer_name'])); ?>
<div class="clear">
	<label><?php echo __('Status'); ?></label>
	<?php 
	$value = $this->data['OrderExpense']['status'];
	if(array_key_exists($value, $statuses)) {
		echo $statuses[$value] . '<br />&nbsp;';
	} ?>
</div>
<div class="clear">
	<label><?php echo __('Date'); ?></label>
	<div class="inline left">
		<div class="time_select_container">
			<?php echo $this->Form->input('OrderExpense.date_session', array($permission_attr, 'value' => date('m/d/Y', strtotime($this->data['OrderExpense']['date_session'])), 'div' => array('class' => 'input'), 'type' => 'text', 'label' => false)); ?>
		</div>
	</div>
</div>
<div class="clear">
	<div class="">
		<?php echo $this->Form->input('OrderExpense.description', array($permission_attr)); ?>
	</div>
</div>
<div class="clear">
	<label><?php echo __('Submit'); ?></label>
	<div class="">
		<?php echo $this->Form->input('submit', array($permission_attr, 'type'=>'checkbox', 'label' => false, 'div' => false)); ?>
	</div>
</div>

<?php if($permissions['material_approve'] == 1) : ?>
		<br />&nbsp;
		<h5><?php echo __('Administrative'); ?></h5>
		<div class="grid">
			<div class="col-1of1">
				<div class="">
					<label><?php echo __('Approve'); ?></label>
					<?php echo $this->Form->input('approve', array($permission_attr, 'type'=>'checkbox', 'label' => false, 'div' => false)); ?>
				</div>
			</div>
		</div>
<?php else : ?>
<?php echo $this->Form->hidden('OrderExpense.approve'); ?>
<?php endif; ?>

<div class="grid">
	<div class="col-1of1">
		<div class="title-buttons">
			<?php 
			if(($permissions['material_delete'] == 1) || ($this->data['OrderExpense']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderExpense']['creator_id'] == $__user['User']['id'])) {
				echo $this->Html->link(__('Delete', true), array('controller' => 'order_expenses', 'action' => 'delete', $this->data['OrderExpense']['id']), array('class' => 'title-buttons'), __('delete_confirm')); 
			} 
			if(($permissions['material_approve'] == 1) || ($permissions['material_create'] == 1 && $this->data['OrderExpense']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderExpense']['creator_id'] == $__user['User']['id'])) {
				echo $this->Form->submit(__('Save', true), array('id' => 'post-material', 'class' => 'post title-buttons')); 
			} ?>
		</div>
	</div>
</div>
<div class="grid"><div class="col-1of1">&nbsp;</div></div>