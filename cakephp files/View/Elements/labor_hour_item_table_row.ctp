<?php 
switch($model) {
	case 'OrderLineItemLaborItem' :
		$lc_model = 'order';
		break;
	case 'QuoteLineItemLaborItem' :
	default :
		$lc_model = 'quote';
}
if(!isset($rate)) {
	$rate = null;
}
if(!isset($rate_id)) {
	$rate_id = null;
}
$id = null;
$order_id = null;
$order_line_item_id = null;
$labor_cost_hours = null;
$labor_qty = 1;
$labor_cost_dollars = null;
$empty = '';
if(!empty($data)) {
	if(array_key_exists('id', $data)) {
		$id = $data['id'];
	}
	if(array_key_exists($lc_model.'_id', $data)) {
		$order_id = $data[$lc_model.'_id'];
	}
	if(array_key_exists($lc_model.'_line_item_id', $data)) {
		$order_line_item_id = $data[$lc_model.'_line_item_id'];
	}
	if(array_key_exists('labor_cost_hours', $data)) {
		$labor_cost_hours = $data['labor_cost_hours'];
	}
	if(array_key_exists('labor_qty', $data)) {
		$labor_qty = $data['labor_qty'];
	}
	if(array_key_exists('labor_cost_dollars', $data)) {
		$labor_cost_dollars = $data['labor_cost_dollars'];
	}
	if(array_key_exists('rate_id', $data)) {
		$rate_id = $data['rate_id'];
	}
	if(array_key_exists('rate', $data)) {	
		$rate = $data['rate'];
		if($rate != $rates_data_banks[$rate_id]['Rate']['rate']) {
			$empty = $rates_data_banks[$rate_id]['Rate']['name'] . ' - ' . $rate;
			$rate_id = null;
		}
	} else {
		if(array_key_exists('rate_id', $data) && !empty($data['rate_id'])) {
			$rate = $rates_data_banks[$data['rate_id']]['Rate']['rate'];
		}
	}
} ?>
<tr class="labor_estimate_item" id="labor_estimate_item_<?php echo $index; ?>">
	<td>
		<?php echo $this->Form->hidden($model . '.id', array('value' => $id, 'id'=>'labor_hour_item_id_'.$index, 'class'=>'id', 'name' => 'data['.$model.']['.$index.'][id]')); ?>
		<?php echo $this->Form->hidden($model . '.' . $lc_model . '_id', array('value' => $order_id, 'id'=>'labor_hour_item_' . $lc_model . '_id_'.$index, 'class'=> $lc_model . '_id', 'name' => 'data['.$model.']['.$index.'][' . $lc_model . '_id]')); ?>
		<?php echo $this->Form->hidden($model . '.' . $lc_model . '_line_item_id', array('value' => $order_line_item_id, 'id'=>'labor_hour_item_' . $lc_model . '_line_item_id_'.$index, 'class'=>$lc_model . '_line_item_id', 'name' => 'data['.$model.']['.$index.'][' . $lc_model . '_line_item_id]')); ?>
		<?php echo $this->Form->input($model . '.labor_cost_hours', array('value' => $labor_cost_hours, 'id'=>'labor_hour_item_labor_cost_hours_'.$index, 'class' => 'labor_cost_hours num_only', 'name' => 'data['.$model.']['.$index.'][labor_cost_hours]', 'div' => 'input text medium num_only', 'label' => false)); ?>
	</td>
	<td>
		<?php echo $this->Form->input($model . '.rate_id', array('value' => $rate_id, 'id'=>'labor_hour_item_rate_id_'.$index, 'class' => 'rate_id', 'name' => 'data['.$model.']['.$index.'][rate_id]', 'label' => false, 'div' => false, 'options' => $rates, 'empty' => $empty)); ?>
		<?php echo $this->Form->hidden($model . '.rate', array('value' => $rate, 'id'=>'labor_hour_item_rate_'.$index, 'class'=>'rate', 'name' => 'data['.$model.']['.$index.'][rate]')); ?>
	</td>
	<td>
		<?php echo $this->Form->input($model . '.labor_qty', array('value' => $labor_qty, 'id'=>'labor_hour_item_labor_qty_'.$index, 'class' => 'labor_qty num_only', 'name' => 'data['.$model.']['.$index.'][labor_qty]', 'label' => false, 'div' => false)); ?>
	</td>
	<td>	
		<?php echo $this->Form->input($model . '.labor_cost_dollars', array('value' => $labor_cost_dollars, 'id'=>'labor_hour_item_labor_cost_dollars_'.$index, 'class' => 'num_only_allow_neg labor_cost_dollars medium', 'name' => 'data['.$model.']['.$index.'][labor_cost_dollars]', 'label' => false, 'div' => false)); ?>
	</td>
	<td>
		<?php echo $this->Html->link('&#x2715;', '#', array('class' => 'labor-hour-item-delete delete-button', 'id' => 'labor-hour-item-delete-' . $index, 'escape' => false)); ?>
	</td>
</tr>