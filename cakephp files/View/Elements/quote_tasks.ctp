<?php
$this->extend('/Templates/wide');
echo $this->element('quote_title', array('quote' => $quote));

echo $this->Html->script('creationsite/quote', array('inline' => false));
echo $this->Html->script('creationsite/quote.tasks', false);
#echo $this->element('js'.DS.'jquery', array('ui' => 'buttons'));
echo $this->element('js'.DS.'jquery', array('ui' => 'datepicker'));
echo $this->element('js'.DS.'quote.datepicker.init');
echo $this->element('js'.DS.'quotes');
$this->Html->script('jquery/jquery.jeditable', false);
echo $this->element('js'.DS.'editable.order.status.init', array('model' => 'quote', 'approvalStatuses' => $statuses_approval, 'taskStatuses' => $statuses_task, 'modelStatuses' => $statuses_quote));

$add_mode_class = '';
if(!empty($display_mode) && $display_mode == 'collapsed') {
	$add_mode_class = 'hide';
}

$permissions = $this->Permission->getPermissions($__permissions);
$permission_attr = 'readonly disabled';
$enable_save = false;
if((($permissions['can_create'] == 1 || $permissions['can_update'] == 1) && $quote['Quote']['project_manager_id'] == $__user['User']['id']) || $permissions['view_all_quotes'] == 1) {
	$permission_attr = null;
	$enable_save = true;
}

$statusQuotes = $statuses_quote;
unset($statusQuotes[QUOTE_STATUS_UNSAVED]);
//unset($statusQuotes[QUOTE_STATUS_SOLD]);
?>
<div class="clear">
	<?php echo $this->Form->create('QuoteTask', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
	<fieldset>
		<div class="fieldset-wrapper available_action_container">
			<div class="left"><b>Available Actions:</b></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manage the quote's task.
			<div class="title-buttons">
				<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
			</div>													  
		</div>
	</fieldset>
	<?php if($enable_save) : ?>
	<?php 		echo $this->Html->link('add a task', array('#'), array('id' => 'add_status', 'class' => 'toggle_display_button right')); ?>
	<?php endif; ?>
	<div id="add_status_toggle_display" class="clear <?php echo $add_mode_class; ?>">
		<?php echo $this->Form->create('QuoteTask', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4><?php echo __('Add Quote Item to be tracked'); ?></h4>
				<div class="grid">
					<div class="col-1of2">
						<?php echo $this->Form->input('item', array('div' => array('class' => 'input'), 'label' => __('Item'))); ?>
						<div class="buttonset">
							<?php echo $this->Form->radio('task_type', $__yesNo, array('legend' => 'Approval Request')); ?><br />
						</div>
						<?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => 'Description')); ?>
					</div>
					<div class="col-1of2">
						<?php echo $this->Form->input('requested_by_id', array('label' => __('Requested By'), 'options' => $employeeList)); ?>
						<?php echo $this->Form->input('assigned_to_id', array('label' => __('Assigned To'), 'empty' => 'Select', 'options' => $employeeList)); ?>
						<?php echo $this->Form->input('date_created', array('label' => __('Date Created'), 'type' => 'text')); ?>
						<?php echo $this->Form->input('date_request', array('label' => __('Request Due'), 'type' => 'text')); ?>
					</div>
				</div>
				<?php echo $this->Form->submit(__('Save'), array('class' => 'right red', 'escape' => false)); ?>		
			</div>
		</fieldset>
		<?php echo $this->Form->hidden('sid'); ?>
		<?php echo $this->Form->hidden('quote_id'); ?>
		<?php echo $this->Form->hidden('Quote.id', array('value' => $quote['Quote']['id'])); ?>
		<?php echo $this->Form->hidden('Quote.sid', array('value' => $quote['Quote']['sid'])); ?>
		<?php echo $this->Form->hidden('id'); ?>
		<?php echo $this->Form->end(); ?>
	</div>
	<table class="standard hover">
		<tr>
			<th>&nbsp;</th>
			<th>Item</th>
			<th>Date Created</th>
			<th>Description</th>
			<th>Request By</th>
			<th>Assigned To</th>
			<th>Due Req.</th>
			<th>Status</th>
			<th>&nbsp;</th>
		</tr>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo Configure::read('Nomenclature.Quote'); ?> Status</td>
			<td><?php echo $this->Web->dt($quote['Quote']['created'], 'short_4'); ?></td>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<td>
				<?php if(!empty($quote['Quote']['project_manager_id'])) : ?>
				<?php 	echo $this->Web->humanName($quote['ProjectManager'], 'first_initial'); ?>
				<?php else : ?>
				<?php 	echo __('Unassigned'); ?>
				<?php endif; ?>
			</td>
			<td><?php echo $this->Web->dt($quote['Quote']['date_request'], 'short_4'); ?></td>
			<td class="nowrap inline-edit">	
				<?php if($permissions['quote_owner'] == 1) : ?>
				<?php $id = 'Quote-' . $quote['Quote']['id'] . '-status-' . $quote['Quote']['id'] . '-quote'; ?>
				<div class="edit_status_quote" id="<?php echo $id; ?>">
					<?php echo $statusQuotes[$quote['Quote']['status']]; ?>
				</div>
				<?php else: ?>
					<?php echo $statusQuotes[$quote['Quote']['status']]; ?>
				<?php endif; ?>
			</td>
			<td class="actions no-delete">&nbsp;</td>
		</tr>
		<?php if(!empty($quote_tasks)) : 
				foreach($quote_tasks as $quote_task) : ?>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo $quote_task['item']; ?></td>
			<td><?php echo $this->Web->dt($quote_task['date_created'], 'short_4'); ?></td>
			<td><?php echo $quote_task['description']; ?></td>
			<td>&nbsp;</td>
			<td>
			<?php if(!empty($quote_task['assigned_to_id']['id'])) : ?>
			<?php 	echo $this->Web->humanName($quote_task['assigned_to_id'], 'first_initial'); ?>
			<?php else : ?>
			<?php 	echo __('Unassigned'); ?>
			<?php endif; ?>
			&nbsp;
			</td>
			<td><?php echo $this->Web->dt($quote_task['date_request'], 'short_4'); ?></td>
			<?php 
			$class = '';
			$id = '';
			$td_class = 'inline-edit-inactive';
			if($permissions['can_approve'] == 1 || (!empty($quote_task['assigned_to_id']) && $__user['User']['id'] == $quote_task['assigned_to_id']['id'])) :
				$td_class = 'inline-edit'; 
				if($quote_task['task_type'] == QUOTE_TASK_TYPE_APPROVAL) {
					$class = 'edit_approval_status_quote';
					$field = 'date_request_approved';
					$status_type = 'approval';
				} else {
					$class = 'edit_task_status_quote';
					$field = 'status';
					$status_type = 'task';
				}
				$id = $quote_task['model'] . '-' . $quote_task['id'] . '-' . $field . '-' . $quote_task['quote_id'] . '-' . $status_type;
				?>
				<td class="nowrap <?php echo $td_class; ?>">
					<div class="<?php echo $class; ?>" id="<?php echo $id; ?>">
						<?php if($quote_task['task_type'] == QUOTE_TASK_TYPE_APPROVAL) : ?>
						<?php 	echo $statuses_approval[$quote_task['status']]; ?>
						<?php else : ?>
						<?php 	echo $statuses_task[$quote_task['status']]; ?>
						<?php endif; ?>
					</div>
				</td>
			<?php else : ?>
				<td class="nowrap">
					<?php if($quote_task['task_type'] == QUOTE_TASK_TYPE_APPROVAL) : ?>
					<?php 	echo $statuses_approval[$quote_task['status']]; ?>
					<?php else : ?>
					<?php 	echo $statuses_task[$quote_task['status']]; ?>
					<?php endif; ?>
				</td>
			<?php endif; ?>
			<td class="actions no-delete">&nbsp;<?php 
				#if($permissions['can_update']) {
				#	echo $this->Html->link(__('Edit'), $quote_task['redirect'], array('class' => 'row-click')); 
				#} ?>
			</td>
		</tr>
		<?php 	endforeach;
		endif; ?>
		<?php if(!empty($current_tasks)) : 
				foreach($current_tasks as $key=>$data) : ?>
		<tr>
			<td>&nbsp;</td>
			<td><?php echo $data['QuoteTask']['item']; ?></td>
			<td><?php echo $this->Web->dt($data['QuoteTask']['date_created'], 'short_4'); ?></td>
			<td><?php echo nl2br($data['QuoteTask']['description']); ?></td>
			<td><?php echo $this->Web->humanName($data['RequestedById'], 'first_initial'); ?></td>
			<td><?php echo $this->Web->humanName($data['AssignedToId'], 'first_initial'); ?></td>
			<td><?php echo $this->Web->dt($data['QuoteTask']['date_request'], 'short_4'); ?></td>		
			<?php 
			$class = '';
			$id = '';
			$td_class = 'inline-edit-inactive';
			// Enable the in-line editing if the user has approval permission... or the user is assigned to the task.
			if($data['Quote']['project_manager_id'] == $__user['User']['id'] || ($__user['User']['id'] == $data['QuoteTask']['assigned_to_id'])) {
				$td_class = 'inline-edit';
				if($data['QuoteTask']['task_type'] == QUOTE_TASK_TYPE_APPROVAL) {
					$class = 'edit_approval_status_quote';
					$field = 'status';
					$status_type = 'approval';
				} else {
					$class = 'edit_task_status_quote';
					$field = 'status';
					$status_type = 'task';
				} 
				$id = 'QuoteTask-' . $data['QuoteTask']['id'] . '-' . $field . '-' . $data['QuoteTask']['quote_id'] . '-' . $status_type;
			} 
			?>
			<td class="nowrap <?php echo $td_class; ?>">
				<div class="<?php echo $class; ?>" id="<?php echo $id ?>">
					<?php if($data['QuoteTask']['task_type'] == QUOTE_TASK_TYPE_APPROVAL) : ?>
					<?php 	echo $statuses_approval[$data['QuoteTask']['status']]; ?>
					<?php else : ?>
					<?php 	echo $statuses_task[$data['QuoteTask']['status']]; ?>
					<?php endif; ?>
				</div>
			</td>
			<td class="actions">
				<div id="<?php echo $data['QuoteTask']['id']; ?>_task_data_bank" class="task_data_bank hide">
					<?php echo $this->Form->hidden('id', array('value' => $data['QuoteTask']['id'])); ?>
					<?php echo $this->Form->hidden('task_type', array('value' => $data['QuoteTask']['task_type'])); ?>
					<?php echo $this->Form->hidden('item', array('value' => $data['QuoteTask']['item'])); ?>		
					<?php echo $this->Form->hidden('description', array('value' => $data['QuoteTask']['description'])); ?>
					<?php echo $this->Form->hidden('requested_by_id', array('value' => $data['QuoteTask']['requested_by_id'])); ?>
					<?php echo $this->Form->hidden('assigned_to_id', array('value' => $data['QuoteTask']['assigned_to_id'])); ?>
					<?php echo $this->Form->hidden('date_created', array('value' => date('m/d/Y', strtotime($data['QuoteTask']['date_created'])))); ?>
					<?php echo $this->Form->hidden('date_request', array('value' => date('m/d/Y', strtotime($data['QuoteTask']['date_request'])))); ?>
					<?php echo $this->Form->hidden('status', array('value' => $data['QuoteTask']['status'])); ?>
				</div>
				<?php
				if($permissions['enable_delete'] || ($data['QuoteTask']['requested_by_id'] == $__user['User']['id'])) {
					echo $this->Html->link(__('Delete'), array('controller' => 'quote_tasks', 'action' => 'delete', $data['QuoteTask']['id']), array(), __('delete_confirm')); 
				} else {
					echo $this->Html->link(__('Delete'), '#', array('class' => 'inactive'));	
				}
				if($permissions['can_update'] == 1) {
					echo $this->Html->link(__('Edit'), array('#'), array('id' => $data['QuoteTask']['id'], 'class' => 'edit_task row-click')); 
				} ?>
			</td>
		</tr>
	<?php 		endforeach;
			endif;	?>
	</table>
</div>
<?php $this->start('sliderTitle'); ?>
<?php echo __('Comments'); ?>
<?php $this->end(); ?>
<?php $this->start('slider'); ?>
	<?php echo $this->element('communication/message_view', array('messages' => $comments, 'style' => 'comment', 'display' => 'slide', 'section_types' => $comment_types, 'allow_filter' => true)); ?>	
<?php $this->end(); ?>

<?php $this->start('statusAssigment'); ?>
	<?php echo $this->element('status_assignment', array('model' => 'Quote', 'foreign_key' => $quote['Quote']['id'], 'permissions' => $permissions, 'data' => $quote)); ?>	
<?php $this->end(); ?>

<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_quote', array('quote' => $quote)); ?>
<?php $this->end(); ?>