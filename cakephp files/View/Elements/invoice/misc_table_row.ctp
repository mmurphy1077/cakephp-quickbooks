<?php 
$id = null;
$description = null;
$hours = null;
$cost = null;
if(!empty($data)) {
	$id = $data['id'];
	$description = $data['description'];
	$cost = $data['cost'];
	$sort = $data['sort'];	
}
?>
<tr class="invoice-misc-item" id="invoice-misc_item_<?php echo $index; ?>">
	<td class="invoice-description">
		<?php echo $this->Form->hidden('InvoiceMiscItem.id', array($permission_attr, 'value' => $id, 'id'=>'invoice_misc_item_id_'.$index, 'class'=>'id', 'name' => 'data[InvoiceMiscItem]['.$index.'][id]')); ?>
		<?php echo $this->Form->input('InvoiceMiscItem.description', array($permission_attr, 'value' => $description, 'id'=>'invoice_misc_item_description_'.$index, 'class'=>'invoice_misc_item_description', 'name' => 'data[InvoiceMiscItem]['.$index.'][description]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-cost">
		<?php echo $this->Form->input('InvoiceMiscItem.cost', array($permission_attr, 'value' => $cost, 'id'=>'invoice_misc_item_cost_'.$index, 'class' => 'invoice_misc_item_cost', 'name' => 'data[InvoiceMiscItem]['.$index.'][cost]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="invoice-delete">
		<?php 
		if(empty($permission_attr)) {
			echo $this->Html->link('&#x2715;', '#', array('class' => 'invoice-misc-item-delete delete-button grey', 'id' => 'invoice-misc-item-delete-' . $index, 'escape' => false));
			#echo $this->Html->link($this->Html->image('icon-close.png'), '#', array('class' => 'invoice-misc-item-delete', 'id' => 'invoice-misc-item-delete-' . $index, 'escape' => false)); 
		} ?>
	</td>
</tr>