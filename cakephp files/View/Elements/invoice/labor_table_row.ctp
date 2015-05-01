<?php 
$id = null;
$description = null;
$cost = null;
$qty = null;
$unit_cost = null;
$type = null;
if(!empty($data)) {
	$id = $data['id'];
	$description = $data['description'];
	$cost = $data['cost'];
	$sort = $data['sort'];
	$qty = $data['qty'];
	$unit_cost = $data['unit_cost'];
	if(array_key_exists('type', $data)) {
		$type = $data['type'];
	}
}
?>
<tr class="invoice-labor-item" id="invoice-labor_item_<?php echo $index; ?>">
	<td class="drag"><?php echo $this->Html->image('icon-drag.png'); ?></td>
	<td class="invoice-qty">
		<?php echo $this->Form->input('InvoiceLaborItem.qty', array($permission_attr, 'value' => $qty, 'id'=>'invoice_labor_item_qty_'.$index, 'class' => 'num_only invoice_labor_item_qty', 'name' => 'data[InvoiceLaborItem]['.$index.'][qty]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-description">
		<?php echo $this->Form->hidden('InvoiceLaborItem.id', array('value' => $id, 'id'=>'invoice_labor_item_id_'.$index, 'class'=>'id', 'name' => 'data[InvoiceLaborItem]['.$index.'][id]')); ?>
		<?php echo $this->Form->hidden('InvoiceLaborItem.type', array('value' => $type, 'id'=>'invoice_labor_item_type_'.$index, 'name' => 'data[InvoiceLaborItem]['.$index.'][type]')); ?>
		<?php echo $this->Form->input('InvoiceLaborItem.description', array($permission_attr, 'value' => $description, 'id'=>'invoice_labor_item_description_'.$index, 'class'=>'invoice_labor_item_description', 'name' => 'data[InvoiceLaborItem]['.$index.'][description]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-unit-cost">
		<?php echo $this->Form->input('InvoiceLaborItem.unit_cost', array($permission_attr, 'value' => $unit_cost, 'id'=>'invoice_labor_item_unit_cost_'.$index, 'class' => 'num_only invoice_labor_item_unit_cost', 'name' => 'data[InvoiceLaborItem]['.$index.'][unit_cost]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-cost">
		<?php echo $this->Form->input('InvoiceLaborItem.cost', array($permission_attr, 'value' => $cost, 'id'=>'invoice_labor_item_cost_'.$index, 'class' => 'invoice_labor_item_cost', 'name' => 'data[InvoiceLaborItem]['.$index.'][cost]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-delete">
		<?php 
		if(empty($permission_attr)) {
			echo $this->Html->link('&#x2715;', '#', array('class' => 'invoice-labor-item-delete delete-button grey', 'id' => 'invoice-labor-item-delete-' . $index, 'escape' => false));
			#echo $this->Html->link($this->Html->image('icon-close.png'), '#', array('class' => 'invoice-labor-item-delete', 'id' => 'invoice-labor-item-delete-' . $index, 'escape' => false)); 
		} ?>
	</td>
</tr>