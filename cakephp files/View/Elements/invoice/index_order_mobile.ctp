<div id="mobile-menu-container" class="hidden-md hidden-lg hidden">
<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'list')); ?>
</div>
<div id="mobile-content-container">
	<?php echo $this->Form->create('Order', array('class' => 'standard display-mobile', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
	<?php echo $this->Form->hidden('order_id', array('id' => 'order_id', 'value' => $order['Order']['id'])); ?>
	<?php echo $this->element('invoice/invoice_tracking_nav', array('selected' => null, 'order' => $order, 'permissions' => $permissions, 'mode' => 'mobile')); ?>
	<?php echo $this->element('invoice/stats_order', array('order' => $order, 'stats' => $stats)); ?>
	<div class="row">
		<div class="col-xs-12">
			<div id="order-invoice-table-container" class="">
				<?php echo $this->element('invoice/order_index', array('invoices'=>$invoices)); ?>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'tab')); ?>
<?php $this->end(); ?>