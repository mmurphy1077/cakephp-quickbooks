<?php
echo $this->Form->hidden('CalculationSystemRequirement.'.$calculation_requirement_id.'.calculation_requirement_type_id', array('value' => CALCULATION_REQUIREMENT_TYPE_IMPERIAL_LINEAR_ID));
echo $this->Form->hidden('CalculationSystemRequirement.'.$calculation_requirement_id.'.id');
?>
<div class="grid">
	<div class="col-1of2">
		<p class="bold"><?php echo __($label.' Minimum'); ?></p>
		<div class="clear">&nbsp;</div>
	</div>
	<div class="col-1of2">
		<p class="bold"><?php echo __($label.' Maximum'); ?></p>
		<div class="clear">&nbsp;</div>
	</div>
</div>
<div class="grid">
	<div class="col-1of2">
		<div class="grid flush-left">
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.feet_min', array('label' => false, 'div' => 'input text medium', 'after' => __(' Feet'))); ?></div>
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.inches_min', array('label' => false, 'div' => 'input text medium', 'after' => __(' Inches'))); ?></div>
		</div>		
	</div>
	<div class="col-1of2">
		<div class="grid flush-left">
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.feet_max', array('label' => false, 'div' => 'input text medium', 'after' => __(' Feet'))); ?></div>
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.inches_max', array('label' => false, 'div' => 'input text medium', 'after' => __(' Inches'))); ?></div>
		</div>		
	</div>
</div>