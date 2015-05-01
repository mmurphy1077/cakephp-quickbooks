<?php
$this->Html->script('jquery/jquery.jeditable', false);
echo $this->element('js'.DS.'editable', array(
	'editClass' => 'inline-edit-sort',
	'postback' => $this->Html->url(array('controller' => 'order_line_items', 'action' => 'edit_field')),
	'submitOnChange' => 1,
	//'submit' => 'OK',
	'type' => 'numeric',
));
if(!isset($allowEdit)) {
	$allowEdit = true;
}
if(!isset($itemsOnly)) {
	$itemsOnly = false;
}
if(!isset($displaySort)) {
	$displaySort = true;
}
?>
<table class="data1 quote">
	<?php if(!$itemsOnly) : ?>
	<tr>
		<th><?php echo __('Order Number'); ?></th>
		<td><?php echo $order['Order']['number']; ?></td>
	</tr>
	<tr>
		<th><?php echo __('Created'); ?></th>
		<td><?php echo $this->Time->niceShort($order['Order']['created']); ?></td>
	</tr>
	<tr>
		<th><?php echo __('Customer'); ?></th>
		<td><b><?php echo $order['Order']['customer_name']; ?></b></td>
	</tr>
	<tr>
		<th><?php echo __('Contact'); ?></th>
		<td>
			<b><?php echo $order['Order']['contact_name']; ?></b>
			&mdash;
			<?php echo $order['Order']['contact_phone']; ?>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Account Rep'); ?></th>
		<td>
			<?php if (!empty($order['AccountRep']['id'])): ?>
				<?php echo $this->Web->humanName($order['AccountRep']); ?>
			<?php endif; ?>
			&nbsp;
		</td>
	</tr>
	<tr>
		<th><?php echo __('Service Address'); ?></th>
		<td>
			<?php if (!empty($order['AddressExisting']['id'])): ?>
				<?php echo $this->Web->address($order['AddressExisting']); ?>
			<?php elseif (!empty($order['Address'])): ?>
				<?php echo $this->Web->address($order['Address']); ?>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Description'); ?></th>
		<td><?php echo $order['Order']['description']; ?></td>
	</tr>
	<?php endif; ?>
	<?php if (!empty($order['OrderLineItem'])): ?>
		<?php if(!$itemsOnly) : ?>
		<tr class="action">
			<th>&nbsp;<?php echo __(Inflector::pluralize(Configure::read('Nomenclature.OrderJob'))); ?></th>
			<td><?php #echo $this->Number->currency(array_sum(Set::extract('/price_subtotal', $order['OrderLineItem']))); ?></td>
		</tr>
		<?php endif; ?>
		<?php foreach ($order['OrderLineItem'] as $i => $orderLineItem):
			if (!empty($orderLineItem['OrderJob'])) {
				list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.step3_edit'));
				$editLink = array('controller' => Inflector::tableize($controller), 'action' => $action, $orderLineItem['order_id'], $orderLineItem['id']);
			} else {
				$editLink = array('controller' => 'order_line_items', 'action' => 'add', $orderLineItem['order_id'], $orderLineItem['id']);
			}
			$colspan = 2;
			if($allowEdit) {
				$colspan = 1;
			} ?>
			<tr>
				<?php 
				if($allowEdit && $displaySort) : ?>
					<th class="inline-edit">
						<span class="left"><b>sort order:</b></span>
						<div id="<?php echo $orderLineItem['id']; ?>" class="inline-edit-sort left inline">
						<?php echo $orderLineItem['sort']; ?>
						</div>
						<?php #echo __($quoteLineItem['QuoteLineItemType']['name']); ?>
					</th>
				<?php endif; ?>
				<td colspan="<?php echo $colspan; ?>" class="order-summary-table-item">
					<?php if (!empty($orderLineItem['OrderJob'])): ?>
						<a href="#" class="toggle actions right" id="<?php echo $i; ?>">Details</a>
					<?php endif; ?>
					<div class="right small">
						<div class="grid" style="width: 200px;">
							<div class="col-1of2 light">
								<?php echo __('Labor'); ?><br />
								<?php echo __('Materials'); ?><br />
								<?php echo __('Other'); ?>
							</div>
							<div class="col-1of2">
								<?php echo $this->Number->currency($orderLineItem['labor_cost_dollars']); ?>
								(<?php echo number_format(($orderLineItem['labor_cost_hours'] * $orderLineItem['labor_qty']), 2).__(' hrs'); ?>)<br />
								<?php echo $this->Number->currency($orderLineItem['materials_cost_dollars']); ?><br />
								<?php echo $this->Number->currency($orderLineItem['equipment_cost_dollars']); ?>
							</div>
						</div>
					</div>
					<?php if ($allowEdit): ?>
						<b><?php echo $this->Html->link($orderLineItem['name'], $editLink); ?></b>
					<?php else: ?>
						<b><?php echo $orderLineItem['name']; ?></b>
					<?php endif; ?>
					<div class="small">
						<b>
							<?php echo $this->Number->currency($orderLineItem['total']); ?>
							<?php #echo $this->Number->currency($orderLineItem['price_subtotal']); ?>
						</b>
						(<?php echo $orderLineItem['qty']; ?> @ <?php echo $this->Number->currency($orderLineItem['price_unit']); ?> <?php echo __('ea'); ?>)
					</div>
					<?php if (!empty($orderLineItem['description'])): ?>
						<div class="light">
							<?php echo nl2br($orderLineItem['description']); ?><br /><br />
						</div>
					<?php endif; ?>
					<?php if (!empty($orderLineItem['OrderJob'])): ?>
						<div id="order-job-summary-<?php echo $i; ?>" class="hidden">
							<?php echo $this->element('order_job_summary', array('orderJob' => $orderLineItem)); ?>
						</div>
					<?php endif; ?>
					<?php if ($allowEdit): ?>
						<?php echo $this->Html->link('&#x2715;', array('controller' => 'order_line_items', 'action' => 'delete', $orderLineItem['id']), array('class' => 'delete-button grey', 'escape' => false), __('delete_confirm')); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
	<?php #	if($showErrors) : ?>
	<!-- 
		<tr class="action">
			<th>&nbsp;<?php echo __(Inflector::pluralize(Configure::read('Nomenclature.OrderLineItem'))); ?></th>
			<td><?php #echo $this->Number->currency(array_sum(Set::extract('/price_subtotal', $order['OrderLineItem']))); ?></td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td>
				<?php echo $this->Html->link('Add Item to Order.', array('controller' => 'order_line_items', 'action' => 'add', $order['Order']['id'])); ?>
				<div class="error_msg">&#8592; Missing Tasks</div>
			</td>
		</tr>
	 -->
	<?php #	endif; ?>
	<?php endif; ?>
</table>