<label>Labor</label>
<?php echo $this->element('labor_hour_items', array('model' => 'Order')); ?>
<div class="col-xs-9 col-sm-6 col-md-9 col-lg-6 clear">
	<?php echo $this->Form->input('materials_cost_dollars', array('div' => 'input larger_label_space', 'class' => 'num_only_allow_neg cost_input', 'label' => 'Material', 'before' => __('$') . '&nbsp;', 'error' => array('int_pos' => __('int_pos'), 'size' => __('num_pos')))); ?>
</div>			
<div class="col-xs-9 col-sm-6 col-md-9 col-lg-6 clear">
	<div class="label"></div>
	<?php echo $this->Form->input('equipment_cost_dollars', array('div' => 'input larger_label_space', 'class' => 'num_only_allow_neg cost_input', 'label' => 'Other', 'before' => __('$') . '&nbsp;', 'error' => array('int_pos' => __('int_pos'), 'size' => __('num_pos')))); ?>
</div>