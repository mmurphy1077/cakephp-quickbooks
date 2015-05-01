<ul id="form-tabs-block-order-items" class="form-tabs form-tabs-block">
	<li id="form-tabs-element-scope-container" class="label form-tabs-element inline left active">Scope of Work</li>
	<li id="form-tabs-element-items-container" class="label form-tabs-element inline left">Job Items</li>
	<li id="form-tabs-element-invoice-notes-container" class="label form-tabs-element inline left">Invoice Notes</li>
</ul>
<div id="scope-container" class="form-tabs-container form-tabs-block-order-items border clear">
	<?php echo $order['Order']['description']; ?>
</div>
<div id="items-container" class="form-tabs-container form-tabs-block-order-items border clear hide">
	<?php echo $this->element('order/order_summary', array('order' => $order, 'allowEdit' => false, 'itemsOnly' => true)); ?>
</div>
<div id="invoice-notes-container" class="form-tabs-container form-tabs-block-order-items border clear hide">
	<?php echo $order['Customer']['notes_invoice']; ?>
</div>