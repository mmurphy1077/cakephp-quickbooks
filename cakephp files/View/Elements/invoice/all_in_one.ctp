<?php 
$permission_attr = null;
if($this->data['Invoice']['status'] >= INVOICE_STATUS_APPROVED) {
	$permission_attr = 'readonly disabled';
}
$order_by_options = array('date' => 'Date Recorded', 'worker' => 'Worker');
if(!isset($mode)) {
	$mode = 'computer';
}
?>
<div class="row">
	<div id="invoice-labor-container-mobile" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<?php if($mode == 'mobile') : ?>
		<div id="window-toggle-container-mobile" class="">
			Display:&nbsp;&nbsp;
			<?php echo $this->Html->link('Tracked Items', '#', array('id'=>'view-track-items')); ?>&nbsp;&nbsp;|&nbsp;&nbsp;
			<?php echo $this->Html->link('Order Items', '#', array('id'=>'view-order-items')); ?>
			<?php 
			$value = 'off';
			if(array_key_exists('tracked_item_status', $this->data['Invoice'])) {
				$value = $this->data['Invoice']['tracked_item_status'];
			}
			echo $this->Form->hidden('tracked_item_status', array('id'=>'tracked_item_status', 'value'=>$value)); ?>
			<?php 
			$value = 'off';
			if(array_key_exists('order_item_status', $this->data['Invoice'])) {
				$value = $this->data['Invoice']['order_item_status'];
			}
			echo $this->Form->hidden('order_item_status', array('id'=>'order_item_status', 'value'=>$value)); ?>
		</div>
		<?php else :
			// Computer... force the display of the order items and tracked items. 
			echo $this->Form->hidden('tracked_item_status', array('id'=>'tracked_item_status', 'value'=>'on'));
			echo $this->Form->hidden('order_item_status', array('id'=>'order_item_status', 'value'=>'on'));	
		endif; ?>
		<div id="invoice_info_container" class="">
			<?php if(!empty($quick_select['Options']['All'])) {
				echo $this->element('invoice/quick_select', array('quick_select' => $quick_select, 'type' => 'all', 'target' => 'labor'));
			} ?>	
			<div id="invoice-labor-table" class="clear left">
				<table id="material_select_container" class="standard tight nohover clear sortable">
					<tr class="nodrag">
						<th>&nbsp;</th>
						<th>Qty</th>
						<th>Description</th>
						<th>Unit $</th>
						<th class="center">Amount</th>
						<th>&nbsp;</th>
					</tr>
					<?php 
					$i = 0;
					if(array_key_exists('InvoiceMaterialItem', $this->data) && !empty($this->data['InvoiceMaterialItem'])) :
						foreach($this->data['InvoiceMaterialItem'] as $data) : 
							echo $this->element('invoice/material_table_row', array('index' => $i, 'data' =>$data, 'type' => 'stock', 'permission_attr' => $permission_attr));
							$i = $i + 1;
						endforeach; 
					endif; 

					// At least add one blank row.
					if($i <= 4) {
						for($i; $i <= 4; $i++) {
							echo $this->element('invoice/material_table_row', array('index' => $i, 'data' => null, 'type' => null, 'permission_attr' => $permission_attr));
						}
					} else {
						echo $this->element('invoice/material_table_row', array('index' => $i, 'data' =>null, 'type' => null, 'permission_attr' => $permission_attr));
					} ?>
				</table>
				<?php 
				if(empty($permission_attr)) {
					echo $this->Html->link('add more', array('#'), array('id'=>'add_more_material_items', 'class'=>'add_more clear left')); 
				} ?>
			</div>
			<table class="invoice-summary standard tight nohover clear">
				<tr>
					<td class="invoice-description-summary">
						<div class="label inline">Notes - Will appear under the Invoice Items (optional)</div>
						<?php echo $this->Form->input('Invoice.vanstock_used', array($permission_attr, 'label' => false, 'type' => 'textarea', 'class' => 'type_block full')); ?>
					</td>
					<td>
						<div class="label inline">Total</div>
						<?php 
						$value = number_format($this->data['Invoice']['total'], 2, '.', '');
						echo $this->Form->input('Invoice.total', array($permission_attr, 'value' => $value, 'label' => false, 'before' => '$ ')); ?>
					</td>
				</tr>	
				<tr>
					<td colspan="2">
						<div class="label inline">Work Performed - will appear on invoice</div>
						<?php echo $this->Form->input('Invoice.work_performed', array('label' => false, 'type' => 'textarea', 'class' => 'type_block full height-med')); ?>
					</td>
				</tr>	
			</table>
			<br />
			<div class="title-buttons">
				<?php 
				$value = 'step2';
				if($mode == 'mobile') {
					$value = 'step2_mobile';
				}
				echo $this->Form->hidden('Invoice.page', array('value' => $value));
				if(($permissions['can_delete'] == 1) && !empty($this->data['Invoice']['id'])) {
					echo $this->Html->link(__('Delete'), array('action' => 'delete', $this->data['Invoice']['id']), array(), __('delete_confirm'));
				}
				if($permissions['can_create_invoice'] == 1) {
					echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
				} ?>
			</div>
		</div>
		<br /><br /><br />
	</div>
	<div id="tracking-container" class="hidden-xs hidden-sm hidden-md hidden-lg col-xs-12 col-sm-12 col-md-pull-6 col-md-6 col-lg-6">
		<ul id="form-tabs-block-1" class="form-tabs form-tabs-block">
			<li id="form-tabs-element-labor-container" class="label form-tabs-element inline left active">Labor</li>
			<li id="form-tabs-element-material-container" class="label form-tabs-element inline left">Materials</li>
			<li id="form-tabs-element-purchase-container" class="label form-tabs-element inline left">Purchases</li>
			<li id="form-tabs-element-scope-container" class="label form-tabs-element inline left hidden-md hidden-lg">Scope of Work</li>
			<li id="form-tabs-element-items-container" class="label form-tabs-element inline left hidden-md hidden-lg">Job Items</li>
			<!-- <li id="form-tabs-element-invoice-notes-container" class="label form-tabs-element inline left">Invoice Notes</li> -->
		</ul>
		<div id="labor-container" class="form-tabs-container form-tabs-block-1 border clear">
			<?php echo $this->element('invoice/summary_labor_block', array('order_by_options' => $order_by_options, 'permissions' => $permissions, 'permission_attr' => $permission_attr));  ?>
		</div>
		<div id="material-container" class="form-tabs-container form-tabs-block-1 border clear hide">
			<?php echo $this->element('invoice/summary_material_block', array('permissions' => $permissions, 'permission_attr' => $permission_attr));  ?>
		</div>
		<div id="purchase-container" class="form-tabs-container form-tabs-block-1 border clear hide">
			<?php echo $this->element('invoice/summary_purchase_block', array('permissions' => $permissions, 'permission_attr' => $permission_attr));  ?>
		</div>
		<div id="scope-container" class="form-tabs-container form-tabs-block-1 border clear hide">
			<?php echo $order['Order']['description']; ?>
		</div>
		<div id="items-container" class="form-tabs-container form-tabs-block-1 border clear hide">
			<?php echo $this->element('order/order_summary', array('order' => $order, 'allowEdit' => false, 'itemsOnly' => true)); ?>
		</div>
		<!--  
		<div id="invoice-notes-container" class="form-tabs-container border clear hide">
			<?php #echo $order['Customer']['notes_invoice']; ?>
		</div> -->
	</div>
	<div id="order-container" class="hidden-xs hidden-sm hidden-md hidden-lg col-md-12 col-lg-12">
		<ul id="form-tabs-block-2" class="form-tabs form-tabs-block">
			<li id="form-tabs-element-scope-container-2" class="label form-tabs-element inline left active">Scope of Work</li>
			<li id="form-tabs-element-items-container-2" class="label form-tabs-element inline left">Job Items</li>
			<!-- <li id="form-tabs-element-invoice-notes-container" class="label form-tabs-element inline left">Invoice Notes</li> -->
		</ul>
		<div id="scope-container-2" class="form-tabs-container form-tabs-block-2 border clear">
			<?php echo $order['Order']['description']; ?>
		</div>
		<div id="items-container-2" class="form-tabs-container form-tabs-block-2 border clear hide">
			<?php echo $this->element('order/order_summary', array('order' => $order, 'allowEdit' => false, 'itemsOnly' => true)); ?>
		</div>
	</div>
</div>