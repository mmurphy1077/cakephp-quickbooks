<?php 
echo $this->Html->script('creationsite/job.outsource', false);

// If no contacts are associated... leave open for entry, else, close.
$add_mode_class = '';
if(!empty($results)) {
	$add_mode_class = 'hide';
}
?>
<div id="outsource_container" class="clear">
	<?php if($permissions['can_update'] == 1) : ?>
	<?php 	echo $this->Html->link('add outsource', array('#'), array('id' => 'add_outsource', 'class' => 'toggle_display_button right')); ?>
	<?php endif; ?>
		<div id="add_outsource_toggle_display" class="contact-edit-form clear <?php echo $add_mode_class; ?>">
			<div class="grid">
				<div class="col-1of1">
					<?php echo $this->Form->hidden('OrderOutsource.id'); ?>
					<?php echo $this->Form->hidden('OrderOutsource.order_id', array('value' => $order['id'])); ?>
					<?php echo $this->Form->input('OrderOutsource.name', array('class' => 'required contact_input')); ?>
					<?php echo $this->Form->input('OrderOutsource.cost', array('class' => 'num_only contact_input', 'label' => 'Cost ($)')); ?>
					<?php echo $this->Form->input('OrderOutsource.description', array('class' => 'mceNoEditor', 'type' => 'textarea', 'label' => 'Description')); ?>
					<!-- 
					<div id="outsource_distribute_container" class="buttonset white-bckgrd">
						<?php 
						#$distribute = 0;
						#echo $this->Form->radio('OrderOutsource.distribute', $__yesNo, array('value' => $distribute, 'legend' => __('Distribute'))); ?>
					</div>
					 -->
				</div>
				<div class="col-1of2">
					<?php echo $this->element('ajax-loader', array('id' => 'ajax-loader-outsource')); ?>
					<?php echo $this->element('ajax-message', array('id' => 'ajax-message-error-outsource', 'type' => 'fail'));?> &nbsp;
				</div>
				<div class="col-1of2"><?php echo $this->Form->submit(__('Save'), array('id' => 'save_outsource', 'class' => 'right', 'escape' => false)); ?></div>
			</div>
			<br />	
			<?php #echo $this->Form->end(); ?>
		</div>
		<table id="order-outsource" class="contact-table clear standard nohover">
			<tr>
				<th>&nbsp;</th>
				<th>Subcontractor</th>
				<th>Cost</th>
				<th>Description</th>
				<th>&nbsp;</th>
			</tr>
		<?php if(!empty($results)) : ?>
			<?php foreach($results as $result) : ?>
			<tr id="outsource-row-<?php echo $result['id']; ?>">
				<td>&nbsp;</td>
				<td><?php echo $result['name']; ?></td>
				<td><?php echo '$'.number_format($result['cost'], 2, '.', ','); ?></td>
				<td><?php echo $this->Web->excerpt($result['description'], 50); ?></td>
				<td class="actions">
					<div id="<?php echo $result['id']; ?>_outsource_data_bank" class="outsource_data_bank hide">
						<?php echo $this->Form->hidden('OrderOutsource.id', array('id' => 'id', 'value' => $result['id'])); ?>
						<?php echo $this->Form->hidden('OrderOutsource.name', array('id' => 'name', 'value' => $result['name'])); ?>
						<?php echo $this->Form->hidden('OrderOutsource.cost', array('id' => 'cost', 'value' => $result['cost'])); ?>		
						<?php echo $this->Form->hidden('OrderOutsource.description', array('id' => 'description', 'value' => $result['description'])); ?>
						<?php echo $this->Form->hidden('OrderOutsource.distribute', array('id' => 'distribute', 'value' => $result['distribute'])); ?>
						<?php echo $this->Form->hidden('OrderOutsource.order_id', array('id' => 'order_id', 'value' => $order['id'])); ?>
					</div>
					<?php
					if($permissions['enable_delete'] || ($permissions['owner'] == 1)) {
						echo $this->Html->link(__('Delete'), array('controller' => 'order_outsources', 'action' => 'delete', $result['id'], $order['id']), array(), __('delete_confirm')); 
					} else {
						echo $this->Html->link(__('Delete'), '#', array('class' => 'inactive'));	
					}
					if($permissions['can_update'] == 1) {
						echo $this->Html->link(__('Edit'), array('#'), array('id' => $result['id'], 'class' => 'edit_outsource row-click')); 
					} 
					echo $this->element('ajax-message', array('id' => 'ajax-message-success-'.$result['id'], 'type' => 'success')); 
					?>
				</td>
			</tr>
		<?php 	endforeach; ?>
		<?php endif;?>
		</table>
</div>