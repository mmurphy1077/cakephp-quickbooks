<?php
echo $this->Form->hidden('CalculationSystemRequirement.'.$calculation_requirement_id.'.calculation_requirement_type_id', array('value' => CALCULATION_REQUIREMENT_TYPE_HOURLY_ID));
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
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.hours_min', array('label' => false, 'div' => 'input text medium', 'after' => __(' Hours'))); ?></div>
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.minutes_min', array('label' => false, 'div' => 'input text medium', 'after' => __(' Minutes'))); ?></div>
		</div>		
	</div>
	<div class="col-1of2">
		<div class="grid flush-left">
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.hours_max', array('label' => false, 'div' => 'input text medium', 'after' => __(' Hours'))); ?></div>
			<div class="col-1of3"><?php echo $this->Form->input('CalculationSystemRequirement.'.$calculation_requirement_id.'.minutes_max', array('label' => false, 'div' => 'input text medium', 'after' => __(' Minutes'))); ?></div>
		</div>		
	</div>
</div>