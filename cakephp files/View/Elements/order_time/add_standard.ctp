<div class="clear">
	<fieldset>
		<div class="fieldset-wrapper available_action_container">
			<div class="left"><b>Available Actions:</b></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<?php echo $this->element('tabs_order_production', array('tab' => ORDER_PRODUCTION_LABOR, 'order' => $order)); ?>			
			<div class="title-buttons">
				<?php echo $this->element('nav_context', array('permission' => $permissions)); 
				if($permissions['labor_create'] == 1) {
					#echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
				} ?>
			</div>					  
		</div>
	</fieldset>
	<?php echo $this->Form->create('OrderTime', array('id' => 'OrderTimeAddForm', 'class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'order_times', 'action' => 'ajax_add', $order['Order']['id']))); ?>
	<?php echo $this->Form->hidden('OrderTime.id'); ?>
	<?php echo $this->Form->hidden('OrderTime.order_id'); ?>
	<?php echo $this->Form->hidden('OrderTime.status'); ?>
	<?php echo $this->Form->hidden('Order.name', array('value'=>$order['Order']['name'])); ?>
	<?php echo $this->Form->hidden('Order.customer_name', array('value'=>$order['Order']['customer_name'])); ?>
	<?php echo $this->Form->hidden('submit_type', array('id' => 'submit-type', 'value' => 'ajax')); ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<?php echo $this->element('order_time/tracking_block', array('permissions' => $permissions, 'permission_explination' => $permission_explination, 'permission_attr' => $permission_attr)); ?>
				<?php if($permissions['labor_approve'] == 1) {
					echo $this->element('order_time/tracking_admin_block', array('permissions' => $permissions, 'permission_attr' => $permission_attr, 'admin_statuses' => $admin_statuses));
				} ?>	
				<fieldset>
					<div class="fieldset-wrapper available_action_container">
						&nbsp;
						<?php if(empty($permission_attr)) : ?>
						<div class="title-buttons">
							<?php 
							if(($permissions['labor_delete'] == 1) || ($this->data['OrderTime']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderTime']['worker_id'] == $__user['User']['id'])) {
								echo $this->Html->link(__('Delete', true), array('controller' => 'order_times', 'action' => 'delete', $this->data['OrderTime']['id']), array('class' => 'title-buttons'), __('delete_confirm')); 
							} 
							if(($permissions['labor_approve'] == 1) || ($permissions['labor_create'] == 1 && $this->data['OrderTime']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderTime']['worker_id'] == $__user['User']['id'])) {
								echo $this->Form->submit(__('Save', true), array('id' => 'post-labor', 'class' => 'post title-buttons')); 
							} ?>
						</div>
						<?php endif; ?>											  
					</div>
				</fieldset>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="center">
				<h4>Work Performed</h4>	
				<?php echo $this->element('communication/message_view', array('messages' => $comments_labor, 'style' => 'comment', 'result' => null, 'selected_filter_val' => 'labor', 'section_types' => false, 'default_type' => 'labor', 'allow_filter' => false, 'displayTouchBase' => false, 'disable_prev_messages_update' => true)); ?>	
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>	
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order', array('order' => $order, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>