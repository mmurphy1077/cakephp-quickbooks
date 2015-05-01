<?php 
$id = null;
$material_id = null;
$description = null;
$name = null;
$qty = null;
$price_per_unit_actual = null;
$price_per_unit = null;
$uom_id = null;
if(!empty($data)) {
	$id = $data['id'];
	$material_id = $data['material_id'];
	$description = $data['description'];
	$name = $data['name'];
	$qty = number_format($data['qty'], 2);
	$price_per_unit_actual = number_format($data['price_per_unit_actual'], 2);
	$price_per_unit = number_format($data['price_per_unit'], 2);
	$uom_id = $data['uom_id'];
}
?>
<tr class="material_select_item" id="material_select_item_<?php echo $index; ?>">
	<td>
		<?php echo $this->Form->hidden('OrderMaterial.material_item_id', array_merge($permission_attr, array('value' => $id, 'id'=>'order_material_item_id_'.$index, 'class'=>'id', 'name' => 'data[OrderMaterialItem]['.$index.'][id]'))); ?>
		<?php echo $this->Form->hidden('OrderMaterial.material_item_material_id', array_merge($permission_attr, array('value' => $material_id, 'id'=>'order_material_item_material_id_'.$index, 'class'=>'material_id', 'name' => 'data[OrderMaterialItem]['.$index.'][material_id]'))); ?>
		<?php echo $this->Form->hidden('OrderMaterial.material_item_description', array_merge($permission_attr, array('value' => $description, 'id'=>'order_material_item_description_'.$index, 'class'=>'material_desc', 'name' => 'data[OrderMaterialItem]['.$index.'][description]'))); ?>
		<?php echo $this->Form->input('OrderMaterial.material_item_name', array_merge($permission_attr, array('value' => $name, 'id'=>'order_material_item_name_'.$index, 'class'=>'material_name', 'name' => 'data[OrderMaterialItem]['.$index.'][name]', 'label' => false, 'div' => false))); ?>
	</td>
	<td>
		<?php echo $this->Form->input('OrderMaterial.qty', array_merge($permission_attr, array('value' => $qty, 'id'=>'order_material_item_qty_'.$index, 'class' => 'material_qty num_only', 'name' => 'data[OrderMaterialItem]['.$index.'][qty]', 'label' => false, 'div' => false))); ?>
	</td>
	<td>
		<?php echo $this->Form->input('OrderMaterial.price_per_unit_actual', array_merge($permission_attr, array('value' => $price_per_unit_actual, 'id'=>'order_material_item_price_per_unit_actual_'.$index, 'class' => 'price_per_unit_actual num_only', 'name' => 'data[OrderMaterialItem]['.$index.'][price_per_unit_actual]', 'label' => false, 'div' => false, 'after' => '&nbsp;&nbsp;/&nbsp;'))); ?>
		<?php echo $this->Form->input('OrderMaterial.price_per_unit', array_merge($permission_attr, array('value' => $price_per_unit, 'id'=>'order_material_item_price_per_unit_'.$index, 'class' => 'price_per_unit num_only', 'name' => 'data[OrderMaterialItem]['.$index.'][price_per_unit]', 'label' => false, 'div' => false))); ?>
	</td>
	<td>
		<?php echo $this->Form->input('uom_id', array_merge($permission_attr, array('value' => $uom_id, 'id'=>'order_material_item_uom_id_'.$index, 'options' => $uoms, 'empty' => 'Select', 'label' => false, 'class' => 'MaterialUomId uom', 'name' => 'data[OrderMaterialItem]['.$index.'][uom_id]', 'div' => false, 'before' => 'per&nbsp;'))); ?>
	</td>
	<td>
		<?php 
		if(empty($permission_attr)) {
			echo $this->Html->link('&#x2715;', '#', array('class' => 'material-line-item-delete delete-button', 'id' => 'material-line-item-delete-' . $index, 'escape' => false));
		} ?>
	</td>
	<!-- <div class="units inline" id="material_<?php echo $index; ?>_units"></div> -->
</tr>