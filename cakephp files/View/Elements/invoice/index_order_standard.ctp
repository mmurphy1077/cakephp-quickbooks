<div class="clear">
	<?php echo $this->Form->create('Order', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
	<?php echo $this->Form->hidden('order_id', array('id' => 'order_id', 'value' => $order['Order']['id'])); ?>
	<fieldset>
		<div class="row fieldset-wrapper available_action_container <?php echo 'view-'.$__browser_view_mode['browser_view_mode']; ?>">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
				<div class="left"><b>Available Actions:</b></div>
				<?php echo $this->element('tabs_order_production', array('tab' => ORDER_PRODUCTION_INVOICE, 'order' => $order)); ?>	
			</div>	
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
				<div class="visible-md-block"><br /></div>
				<div class="title-buttons">
					<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
				</div>
			</div>					  
		</div>
	</fieldset>
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
	<?php echo $this->element('tabs_order', array('order' => $order, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>