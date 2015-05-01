<?php 
$enableSave = false;
$enableDelete = false;
if(($permissions['labor_delete'] == 1) || ($this->data['OrderTime']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderTime']['worker_id'] == $__user['User']['id'])) {
	$enableDelete = true;
}
if(($permissions['labor_approve'] == 1) || ($permissions['labor_create'] == 1 && $this->data['OrderTime']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderTime']['worker_id'] == $__user['User']['id'])) {
	$enableSave = true;
} 
?>
<div id="mobile-menu-container" class="hidden-md hidden-lg hidden">
<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'list')); ?>
</div>
<div id="mobile-content-container">
	<?php echo $this->Form->create('OrderTime', array('id' => 'OrderTimeAddForm', 'class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'order_times', 'action' => 'ajax_add', $order['Order']['id']))); ?>
	<?php echo $this->element('order_tracking_nav', array('selected' => 'labor', 'order_id' => $this->data['OrderTime']['order_id'])); ?>
	<?php echo $this->Form->hidden('OrderTime.id'); ?>
	<?php echo $this->Form->hidden('OrderTime.order_id'); ?>
	<?php echo $this->Form->hidden('OrderTime.status'); ?>
	<?php echo $this->Form->hidden('Order.name', array('value'=>$order['Order']['name'])); ?>
	<?php echo $this->Form->hidden('Order.customer_name', array('value'=>$order['Order']['customer_name'])); ?>
	<?php echo $this->Form->hidden('submit_type', array('id' => 'submit-type', 'value' => 'ajax')); ?>	
	<div class="row fieldset-wrapper">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<?php echo $this->element('order_time/tracking_block', array('permissions' => $permissions, 'permission_explination' => $permission_explination, 'permission_attr' => $permission_attr)); ?>
			<?php if($permissions['labor_approve'] == 1) : ?>
				<?php echo $this->Html->link('view admin section', '#', array('id' => 'admin', 'class' => 'right toggle_display_button'))?>
				<div id="admin_toggle_display" class="clear">
					<?php echo $this->element('order_time/tracking_admin_block', array('permissions' => $permissions, 'permission_attr' => $permission_attr)); ?>
					<br />
				</div>
			<?php endif; ?>
		</div>	
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">		
			<div class="center">
				<h4>Work Performed</h4>	
				<?php echo $this->element('communication/message_view', array('messages' => $comments_labor, 'style' => 'comment', 'result' => null, 'selected_filter_val' => 'labor', 'section_types' => false, 'default_type' => 'labor', 'allow_filter' => false, 'display' => 'inline', 'display_prev_messages' => false)); ?>
				<div class="hidden-xs">
					<?php echo $this->element('communication/message_view', array('messages' => $comments_labor, 'style' => 'comment', 'mode' => 'view_message_thread_only', 'display_prev_messages' => true, 'display' => 'inline')); ?>		
				</div>
			</div>
		</div>					  
	</div>
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php if(empty($permission_attr)) : ?>
				<div class="title-buttons">
					<?php 
					if($enableDelete) {
						echo $this->Html->link(__('Delete', true), array('controller' => 'order_times', 'action' => 'delete', $this->data['OrderTime']['id']), array('class' => 'title-buttons'), __('delete_confirm')); 
					} 
					if($enableSave) {
						echo $this->Form->submit(__('Save', true), array('id' => 'post-labor', 'class' => 'post title-buttons')); 
					} ?>
				</div>
				<?php endif; ?>											  
			</div>
		</div>
	</fieldset>
	<div class="row fieldset-wrapper visible-xs-block">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<br />		
			<?php echo $this->element('communication/message_view', array('messages' => $comments_labor, 'style' => 'comment', 'mode' => 'view_message_thread_only', 'display_prev_messages' => true, 'display' => 'inline')); ?>	
		</div>					  
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'tab')); ?>
<?php $this->end(); ?>