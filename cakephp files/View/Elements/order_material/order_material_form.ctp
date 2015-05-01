<?php
echo $this->Html->script('creationsite/order.material.log', false);
if(!isset($permission_attr)) {
	$permission_attr = array(); 
}
if($permissions['material_read_only'] == 1) {
	$permission_attr = array('readonly' => 'readonly', 'disabled' => 'disabled');
} ?>
<?php echo $this->Form->hidden('OrderMaterial.id'); ?>
<?php echo $this->Form->hidden('OrderMaterial.order_id'); ?>
<?php echo $this->Form->hidden('OrderMaterial.status'); ?>
<?php echo $this->Form->hidden('Order.name', array('value'=>$order['Order']['name'])); ?>
<?php echo $this->Form->hidden('Order.customer_name', array('value'=>$order['Order']['customer_name'])); ?>
<!-- 
<fieldset>
	<div class="fieldset-wrapper"> -->
		<?php 
		if(isset($material_type)) : 
			echo $this->Form->hidden('OrderMaterial.type', array('value' => $material_type)); ?>
		<?php else : ?>
			<div class="clear">
				<label><?php echo __('Material'); ?></label>
				<div class="buttonset"><?php echo $this->Form->radio('OrderMaterial.type', $material_types, array_merge($permission_attr, array('div' => array('class' => 'input larger_label_space'), 'legend' => false))); ?></div><br />&nbsp;
			</div>
		<?php endif; ?>
		
		<div class="clear">
			<label><?php echo __('Status'); ?></label>
			<?php 
			$value = $this->data['OrderMaterial']['status'];
			if(array_key_exists($value, $statuses)) {
				echo $statuses[$value] . '<br />&nbsp;';
			} ?>
		</div>
		<div class="clear">
			<label><?php echo __('Date'); ?></label>
			<div class="inline left">
				<div class="time_select_container">
					<?php if($__browser_view_mode['view_device'] == 'computer') : ?>
						<?php echo $this->Form->input('OrderMaterial.date_session', array_merge($permission_attr, array('type' => 'text', 'label' => false, 'class' => 'datepicker', 'value' => date('m/d/Y', strtotime($this->data['OrderMaterial']['date_session'])), 'div' => array('class' => 'input')))); ?>				
					<?php else : 
						$readonly = '';
						$disabled = '';
						if(array_key_exists('readonly', $permission_attr)) {
							$readonly = 'readonly="' . $permission_attr['readonly'] . '"';
						}
						if(array_key_exists('disabled', $permission_attr)) {
							$disabled = 'disabled="' . $permission_attr['disabled'] . '"';
						}
						// Mobile display devices will use the devices date picker. ?>
						<div class="input">
							<input id="OrderMaterialDateSession" <?php echo $readonly; ?> <?php echo $disabled; ?> class="" type="date" value="<?php echo $this->data['OrderMaterial']['date_session']; ?>" name="data[OrderMaterial][date_session]">
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div id="material-detail-container-stock" class="material-detail-container left">
			<div class="clear">
				<div class="">
					<?php 
					$height = '';
					if(isset($material_type) && ($material_type == 'purchase')) {
						$height = 'height-med';
					}
					echo $this->Form->input('OrderMaterial.description', array_merge($permission_attr, array('label' => 'Description', 'class' => $height))); ?>
				</div>
			</div>
			<?php 
			if(isset($material_type) && ($material_type == 'purchase')) : ?>
				<div class="clear">
					<div class="">
						<?php echo $this->Form->hidden('OrderMaterialItem.0.id'); ?>
						<?php echo $this->Form->input('OrderMaterialItem.0.price_per_unit_actual', array_merge($permission_attr, array('label' => 'Cost $', 'class' => 'num_only cost_input'))); ?>
					</div>
					<div id="toggle_calculator_toggle_display" class="clear" style="display: none;">
						<div id="markup-calculator-container">
							<?php echo $this->Form->input('markup_type', array('id' => 'markup_type', 'class' => 'checkbox checkbox-radio-style inline', 'type' => 'select', 'multiple' => 'checkbox', 'options' => array('margin' => 'Margin&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;', 'markup' => 'Markup'), 'label' => false, 'div' => array('class' => 'markup-type-checkbox-container'), 'escape' => false)); ?>
							<?php echo $this->Form->input('markup_perc', array('id'=>'markup_perc', 'class' => 'qty num_only pad', 'label' => false, 'div' => false, 'after' => '&nbsp;%')); ?>
							&nbsp;
							<?php echo $this->Html->link('Calc', '#', array('id' => 'calculate_markup')); ?>
						</div>
					</div>
					<div class="">
						<?php 
						$calc_link = '&nbsp;&nbsp;' . $this->Html->link('use calculator', '#', array('id' => 'toggle_calculator', 'class' => 'toggle_display_button'));
						echo $this->Form->input('OrderMaterialItem.0.price_per_unit', array_merge($permission_attr, array('label' => 'Adjusted $', 'class' => 'num_only cost_input', 'after' => $calc_link))); ?>
					</div>
				</div>
			<?php else : ?>
			<div id="order_material_items_container">	
				<label><?php echo __('Materials'); ?></label>
				<table id="material_select_container" class="standard tight nohover clear">
					<th>Name</th>
					<th>Qty</th>
					<th class="center">Actual / Adjusted</th>
					<th class="center">UOM</th>
					<th>&nbsp;</th>
					<?php 
					$i = 0;
					if(array_key_exists('OrderMaterialItem', $this->data) && !empty($this->data['OrderMaterialItem'])) :
						foreach($this->data['OrderMaterialItem'] as $data) :
							echo $this->element('material/order_material_table_row', array('index' => $i, 'data' =>$data, 'permission_attr' => $permission_attr));
							$i = $i + 1;
						endforeach; 
					endif; ?>
					<?php 
					// At least ass one blank row.
					if($i <= 4) {
						for($i; $i <= 4; $i++) {
							echo $this->element('material/order_material_table_row', array('index' => $i, 'data' =>null, 'permission_attr' => $permission_attr));
						}
					} else {
						echo $this->element('material/order_material_table_row', array('index' => $i, 'data' =>null, 'permission_attr' => $permission_attr));
					} ?>
				</table>
				<?php 
				if(empty($permission_attr)) {
					echo $this->Html->link('add more', array('#'), array('id'=>'add_more_materials', 'class'=>'add_more clear left')); 
				} ?>
			</div>
			<?php endif; ?>
			<?php if(empty($permission_attr)) : ?>
			<div class="clear">
				<label><?php echo __('Submit'); ?></label>
				<div class="">
					<?php echo $this->Form->input('submit', array($permission_attr, 'type'=>'checkbox', 'label' => false, 'div' => false)); ?>
					<?php echo $this->Form->hidden('OrderMaterial.approve'); ?>
				</div>
			</div>
			<?php endif; ?>
			<br /><br />
		</div>
<!-- 	</div>
</fieldset> -->
			
<?php if($permissions['material_approve'] == 1) : ?>
<?php 	if($__browser_view_mode['view_device'] == 'computer') : ?>
			<?php echo $this->element('order_material/order_material_form_admin_block', array('permission_attr' => $permission_attr)); ?>
<?php 	else : ?>
			<?php echo $this->Html->link('view admin section', '#', array('id' => 'admin', 'class' => 'right toggle_display_button'))?>
			<div id="admin_toggle_display" class="clear">
				<?php echo $this->element('order_material/order_material_form_admin_block', array('permission_attr' => $permission_attr)); ?>	
			</div>
<?php 	endif; ?>
<?php endif; ?>

<div class="clear">
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php if(empty($permission_attr)) : ?>
					<div class="title-buttons">
						<?php 
						if(($permissions['material_delete'] == 1) || ($this->data['OrderMaterial']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderMaterial']['creator_id'] == $__user['User']['id'])) {
							echo $this->Html->link(__('Delete', true), array('controller' => 'order_materials', 'action' => 'delete', $this->data['OrderMaterial']['id']), array('class' => 'title-buttons'), __('delete_confirm')); 
						} 
						if(($permissions['material_approve'] == 1) || ($permissions['material_create'] == 1 && $this->data['OrderMaterial']['status'] < ORDER_TIME_STATUS_APPROVE && $this->data['OrderMaterial']['creator_id'] == $__user['User']['id'])) {
							echo $this->Form->submit(__('Save', true), array('id' => 'post-material', 'class' => 'post title-buttons')); 
						} ?>
					</div>
				<?php endif; ?>											  
			</div>
		</div>
	</fieldset>
</div>
<br />