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
<tr class="invoice-material-item" id="invoice-material_item_<?php echo $index; ?>">
	<td class="drag"><?php echo $this->Html->image('icon-drag.png'); ?></td>
	<td class="invoice-qty">
		<?php echo $this->Form->input('InvoiceMaterialItem.qty', array($permission_attr, 'value' => $qty, 'id'=>'invoice_material_item_qty_'.$index, 'class' => 'num_only invoice_material_item_qty', 'name' => 'data[InvoiceMaterialItem]['.$index.'][qty]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-description">
		<?php echo $this->Form->hidden('InvoiceMaterialItem.id', array($permission_attr, 'value' => $id, 'id'=>'invoice_material_item_id_'.$index, 'class'=>'id', 'name' => 'data[InvoiceMaterialItem]['.$index.'][id]')); ?>
		<?php echo $this->Form->hidden('InvoiceMaterialItem.type', array('value' => $type, 'id'=>'invoice_material_item_type_'.$index, 'name' => 'data[InvoiceMaterialItem]['.$index.'][type]')); ?>
		<?php echo $this->Form->input('InvoiceMaterialItem.description', array($permission_attr, 'value' => $description, 'id'=>'invoice_material_item_description_'.$index, 'class'=>'invoice_material_item_description', 'name' => 'data[InvoiceMaterialItem]['.$index.'][description]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-unit-cost">
		<?php echo $this->Form->input('InvoiceMaterialItem.unit_cost', array($permission_attr, 'value' => $unit_cost, 'id'=>'invoice_material_item_unit_cost_'.$index, 'class' => 'num_only invoice_material_item_unit_cost', 'name' => 'data[InvoiceMaterialItem]['.$index.'][unit_cost]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-cost">
		<?php echo $this->Form->input('InvoiceMaterialItem.cost', array($permission_attr, 'value' => $cost, 'id'=>'invoice_material_item_cost_'.$index, 'class' => 'invoice_material_item_cost', 'name' => 'data[InvoiceMaterialItem]['.$index.'][cost]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-delete">
		<?php 
		if(empty($permission_attr)) {
			echo $this->Html->link('&#x2715;', '#', array('class' => 'invoice-material-item-delete delete-button grey', 'id' => 'invoice-material-item-delete-' . $index, 'escape' => false));
			#echo $this->Html->link($this->Html->image('icon-close.png'), '#', array('class' => 'invoice-material-item-delete', 'id' => 'invoice-material-item-delete-' . $index, 'escape' => false)); 
		} ?>
	</td>
</tr>