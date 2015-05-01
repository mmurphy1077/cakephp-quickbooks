<?php 
// If no contacts are associated... leave open for entry, else, close.
$add_mode_class = '';
if(!empty($po_items)) {
	$add_mode_class = 'hide';
}
?>
<div id="outsource_container" class="clear">
	<?php if($permissions['can_update'] == 1) : ?>
	<?php 	echo $this->Html->link('add line item', array('#'), array('id' => 'add_po_item', 'class' => 'toggle_display_button right')); ?>
	<?php endif; ?>
		<div id="add_po_item_toggle_display" class="contact-edit-form clear <?php echo $add_mode_class; ?>">
			<div class="grid">
				<div class="col-1of1">
					<?php echo $this->Form->hidden('purchase_order_item_index', array('id' => 'purchase_order_item_index')); ?>
					<?php echo $this->Form->hidden('new_purchase_order_item_index', array('value' => 1000, 'id' => 'new_purchase_order_item_index')); ?>
					<?php echo $this->Form->hidden('purchase_order_item_id', array('id' => 'purchase_order_item_id', 'value' => '')); ?>
					<?php #echo $this->Form->hidden('purchase_order_item_purchase_order_id', array('id' => 'purchase_order_item_purchase_order_id')); ?>
					<?php #echo $this->Form->hidden('purchase_order_item_order_id', array('id' => 'purchase_order_item_order_id', 'value' => '')); ?>
					<?php echo $this->Form->input('purchase_order_item_qty', array('id' => 'purchase_order_item_qty', 'value' => 1, 'class' => 'num_only contact_input', 'label' => 'Qty', 'div' => array('class' => 'input text short'))); ?>
					<?php echo $this->Form->input('purchase_order_item_description', array('id' => 'purchase_order_item_description', 'class' => 'mceNoEditor', 'type' => 'textarea', 'label' => 'Description')); ?>
					<?php echo $this->Form->input('purchase_order_item_price_unit', array('id' => 'purchase_order_item_price_unit', 'class' => 'num_only contact_input', 'label' => 'Unit Cost', 'div' => array('class' => 'input text short'))); ?>
					<div class="buttonset white-bckgrd">
						<?php 
						$included = 0;
						echo $this->Form->radio('purchase_order_item_included', $__yesNo, array('id' => 'purchase_order_item_included', 'value' => $included, 'legend' => __('Included'))); ?>
					</div>
				</div>
				<div class="col-1of2">
					<?php echo $this->element('ajax-loader', array('id' => 'ajax-loader-outsource')); ?>
					<?php echo $this->element('ajax-message', array('id' => 'ajax-message-error-outsource', 'type' => 'fail'));?> &nbsp;
				</div>
				<div class="col-1of2"><?php echo $this->Form->submit(__('Update'), array('id' => 'save_purchase_order_item', 'class' => 'right', 'escape' => false)); ?></div>
			</div>
			<br />	
		</div>
		<table id="purchase-order-items" class="contact-table clear standard nohover">
			<tr>
				<th>&nbsp;</th>
				<th>Qty</th>
				<th>Description</th>
				<th>Unit Cost</th>
				<th>Item Total</th>
				<th>&nbsp;</th>
			</tr>
		<?php if(!empty($po_items)) : ?>
			<?php foreach($po_items as $key=>$po_item) : ?>
			<tr id="row-<?php echo $key; ?>" class="purchase-order-row">
				<td>&nbsp;</td>
				<td><?php echo $po_item['qty']; ?></td>
				<td><?php echo nl2br($po_item['description']); ?></td>
				<td><?php 
				if(empty($po_item['price_unit']) || $po_item['price_unit'] == 0) {
					echo 0;
				} else {
					echo '$'.number_format($po_item['price_unit'], 2, '.', ''); 
				}	?>
				</td>
				<td><?php 
				if(empty($po_item['total']) || $po_item['total'] == 0) {
					echo 0;
				} else {
					echo '$'.number_format($po_item['total'], 2, '.', ''); 
				}	?>
				</td>
				<td class="actions">
					<div id="purchase-order-item-bank-<?php echo $key; ?>" class="purchase-order-item-bank hide">
						<?php echo $this->Form->hidden('index', array('id' => 'PurchaseOrderItemIndex', 'value' => $key, 'name' => 'data[PurchaseOrderItem][' . $key . '][index]')); ?>
						<?php echo $this->Form->hidden('id', array('id' => 'PurchaseOrderItemId', 'value' => $po_item['id'], 'name' => 'data[PurchaseOrderItem][' . $key . '][id]')); ?>
						<?php echo $this->Form->hidden('qty', array('id' => 'PurchaseOrderItemQty', 'value' => $po_item['qty'], 'name' => 'data[PurchaseOrderItem][' . $key . '][qty]')); ?>
						<?php echo $this->Form->hidden('price_unit', array('id' => 'PurchaseOrderItemPriceUnit', 'value' => $po_item['price_unit'], 'name' => 'data[PurchaseOrderItem][' . $key . '][price_unit]')); ?>
						<?php echo $this->Form->hidden('total', array('id' => 'PurchaseOrderItemTotal', 'value' => $po_item['total'], 'name' => 'data[PurchaseOrderItem][' . $key . '][total]')); ?>		
						<?php echo $this->Form->hidden('description', array('id' => 'PurchaseOrderItemDescription', 'value' => $po_item['description'], 'name' => 'data[PurchaseOrderItem][' . $key . '][description]')); ?>
						<?php echo $this->Form->hidden('included', array('id' => 'PurchaseOrderItemIncluded', 'value' => $po_item['included'], 'name' => 'data[PurchaseOrderItem][' . $key . '][included]')); ?>
					</div>
					<?php
					if($permissions['enable_delete'] || ($permissions['owner'] == 1)) {
						echo $this->Html->link(__('Delete'), array('controller' => 'orders', 'action' => 'delete_purchase_order_item', $po_item['id'], $order_id), array('id' => 'delete_po_item_' . $po_item['id'], 'class' => 'delete_po_item')); 
					} else {
						echo $this->Html->link(__('Delete'), '#', array('class' => 'inactive'));	
					}
					if($permissions['can_update'] == 1) {
						echo $this->Html->link(__('Edit'), '#', array('id' => 'edit_purchase_order_item_' . $key, 'class' => 'edit_purchase_order_item row-click')); 
					} 
					echo $this->element('ajax-message', array('id' => 'ajax-message-success-'.$po_item['id'], 'type' => 'success')); 
					?>		
				</td>
			</tr>
		<?php 	endforeach; ?>
		<?php endif;?>
		</table>
</div>