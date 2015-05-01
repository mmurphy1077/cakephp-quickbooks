<?php 
$selected_labor="";
$selected_material="";
$selected_purchase="";
if(isset($selected)) {
	switch ($selected) {
		case 'labor':
			$selected_labor="selected";
			break;
		case 'material':
			$selected_material="selected";
			break;
		case 'purchase':
			$selected_purchase="selected";
			break;
	}
}
?>
<fieldset>
	<div class="row fieldset-wrapper available_action_container">
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<b>4 Track:</b>&nbsp;&nbsp;Enter your labors, materials and expenses and select Save.
		</div>	
		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
			<div class="title-buttons hidden-xs">
				<?php
				echo $this->Html->link(__('Purchases', true), array('controller' => 'order_materials', 'action' => 'add_purchase', $order_id), array('class' => 'title-buttons ' . $selected_purchase));
				echo $this->Html->link(__('Materials', true), array('controller' => 'order_materials', 'action' => 'add', $order_id), array('class' => 'title-buttons ' . $selected_material));
				echo $this->Html->link(__('Labor', true), array('controller' => 'order_times', 'action' => 'add', $order_id), array('class' => 'title-buttons ' . $selected_labor));
				?>
			</div>
			<div class="title-buttons visible-xs-block">
				<br />
				<?php
				echo $this->Html->link(__('Labor', true), array('controller' => 'order_times', 'action' => 'add', $order_id), array('class' => 'title-buttons left ' . $selected_labor));
				echo $this->Html->link(__('Materials', true), array('controller' => 'order_materials', 'action' => 'add', $order_id), array('class' => 'title-buttons left ' . $selected_material));
				echo $this->Html->link(__('Purchases', true), array('controller' => 'order_materials', 'action' => 'add_purchase', $order_id), array('class' => 'title-buttons left ' . $selected_purchase));
				?>
			</div>
		</div>					  
	</div>
</fieldset>