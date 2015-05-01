<?php
if (empty($allowEdit)) {
	$allowEdit = false;
}
if(!isset($allowPreviewUpdates)) {
	$allowPreviewUpdates = false;
}
$this->Html->script('jquery/jquery.jeditable', false);
echo $this->element('js'.DS.'editable', array(
	'editClass' => 'inline-edit-sort',
	'postback' => $this->Html->url(array('controller' => 'quote_line_items', 'action' => 'edit_field')),
	'submitOnChange' => 1,
	//'submit' => 'OK',
	'type' => 'numeric',
));
if (empty($allowStatusUpdate)) {
	$allowStatusUpdate = false;
} else {
	// jQuery in placed editing configuration
	#echo $this->element('js'.DS.'editable.quote.status.init', array('editClass' => 'inline-edit-status', 'values' => $statuses));
}
if ($allowPreviewUpdates) {
	echo $this->element('js'.DS.'editable', array(
		'editClass' => 'inline-edit-yes-no',
		'postback' => $this->Html->url(array('controller' => 'quotes', 'action' => 'edit_field', '')),
		'values' => $__yesNo,
		'output' => $__yesNo,
		'submitOnChange' => 1,
	));
}
if (empty($validationErrors)) {
	$validationErrors = null;
	$showErrors = false;
} else {
	$showErrors = true;
}
?>
<script>
	$(document).ready(function(){
		$('.hidden').hide();
		$('.toggle').click(function() {
			var id = $(this).attr('id');
			var text = $(this).text();
			$('#quote-job-summary-' + id).toggle();
			if (text == 'Details') {
				text = 'Hide';
			} else {
				text = 'Details';
			}
			$(this).text(text);
			return false;
		});
	});
</script>
<table class="data1 quote">
	<tr>
		<th><?php echo __('Quote Number'); ?></th>
		<td>
			<?php echo $quote['Quote']['sid']; ?>
		</td>
	</tr>
	<!-- 
	<tr>
		<th><?php echo __('Status'); ?></th>
		<?php if($allowStatusUpdate) : ?>
		<td class="inline-edit">
			<?php $id = 'Quote-'.$quote['Quote']['id'].'-status-'.$quote['Quote']['id'].'-status'; ?>
			<b><div class="inline-edit-status inline-edit link" id="<?php echo $id ?>"><?php echo $statuses_quote[$quote['Quote']['status']]; ?></div></b>
		</td>
		<?php else : ?>
		<td><?php echo $statuses_quote[$quote['Quote']['status']]; ?></td>
		<?php endif; ?>
	</tr>
	 -->
	<tr>
		<th><?php echo __('Created'); ?></th>
		<td><?php echo $this->Time->niceShort($quote['Quote']['created']); ?></td>
	</tr>
	<tr>
		<th><?php echo __(Configure::read('Nomenclature.Order').' Name'); ?></th>
		<td>
			<?php echo $quote['Quote']['name']; ?>
			<?php $this->Web->reportError('name', $validationErrors, 'Name'); ?>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Customer'); ?></th>
		<td>
			<b><?php 
			if(!empty($quote['Quote']['customer_id'])) {
				echo $this->Html->link($quote['Quote']['customer_name'], array('controller' => 'customers', 'action' => 'view', $quote['Quote']['customer_id'])); 
			} else {
				echo $quote['Quote']['customer_name'];
			} ?>
			</b>
			<?php $this->Web->reportError('customer_name', $validationErrors, 'Customer'); ?>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Contact'); ?></th>
		<td>
			<b><?php echo $quote['Quote']['contact_name']; ?></b>
			&mdash;
			<?php echo $quote['Quote']['contact_phone']; ?>
			<?php $this->Web->reportError('contact_name', $validationErrors, 'Contact'); ?>
			<?php $this->Web->reportError('contact_phone', $validationErrors, 'Phone Number'); ?>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Account Rep'); ?></th>
		<td>
			<?php if (!empty($quote['AccountRep']['id'])): ?>
				<?php echo $this->Web->humanName($quote['AccountRep']); ?>
			<?php endif; ?>
			&nbsp;
			<?php $this->Web->reportError('account_rep_id', $validationErrors, 'Account Rep'); ?>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Job Site'); ?></th>
		<td>
			<?php if (!empty($quote['AddressExisting']['id'])): ?>
				<?php echo $this->Web->address($quote['AddressExisting']); ?>
			<?php elseif (!empty($quote['Address'])): ?>
				<?php echo $this->Web->address($quote['Address']); ?>
			<?php elseif ($showErrors) : ?>
			<?php 	$this->Web->reportError('address_id', $validationErrors, 'Address'); ?>
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Description'); ?></th>
		<td>
			<?php echo $quote['Quote']['description']; ?>
		</td>
	</tr>
	<?php if($allowPreviewUpdates) : ?>
	<tr>
		<th><?php echo __('Display Individual Item Costs'); ?></th>
		<td class="inline-edit">
			<?php $id = 'Quote-'.$quote['Quote']['id'].'-display_line_item_totals-'.$quote['Quote']['id'].'-yesno'; ?>
			<div id="<?php echo $id; ?>" class="inline-edit-yes-no inline-edit">
				<?php echo $__yesNo[$quote['Quote']['display_line_item_totals']]; ?>
			</div>
		</td>
	</tr>
	<tr>
		<th><?php echo __('Display Total Cost'); ?></th>
		<td class="inline-edit">
			<?php $id = 'Quote-'.$quote['Quote']['id'].'-display_total-'.$quote['Quote']['id'].'-yesno'; ?>
			<div id="<?php echo $id; ?>" class="inline-edit-yes-no inline-edit">
				<?php echo $__yesNo[$quote['Quote']['display_total']]; ?>
			</div>
		</td>
	</tr>
	<?php endif; ?>
	<?php if (!empty($quote['QuoteLineItem'])): ?>
		<tr class="action">
			<th>&nbsp;<?php echo __(Inflector::pluralize(Configure::read('Nomenclature.QuoteJob'))); ?></th>
			<td><?php #echo $this->Number->currency(array_sum(Set::extract('/price_subtotal', $quote['QuoteLineItem']))); ?></td>
		</tr>
		<?php foreach ($quote['QuoteLineItem'] as $i => $quoteLineItem):
			if (!empty($quoteLineItem['QuoteJob'])) {
				list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.step3_edit'));
				$editLink = array('controller' => Inflector::tableize($controller), 'action' => $action, $quoteLineItem['quote_id'], $quoteLineItem['id']);
			} else {
				$editLink = array('controller' => 'quote_line_items', 'action' => 'add', $quoteLineItem['quote_id'], $quoteLineItem['id']);
			}
			?>
			<tr>
				<th class="inline-edit">
					<span class="left"><b>sort order:</b></span>
					<div id="<?php echo $quoteLineItem['id']; ?>" class="inline-edit-sort left inline">
					<?php echo $quoteLineItem['sort']; ?>
					</div>
					<?php #echo __($quoteLineItem['QuoteLineItemType']['name']); ?>
				</th>
				<td>
					<?php if (!empty($quoteLineItem['QuoteJob'])): ?>
						<a href="#" class="toggle actions right" id="<?php echo $i; ?>">Details</a>
					<?php endif; ?>
					<div class="right small">
						<div class="grid" style="width: 245px;">
							<div class="col-1of2 light">
								<?php echo __('Labor'); ?><br />
								<?php echo __('Materials'); ?><br />
								<?php echo __('Equipment'); ?>
							</div>
							<div class="col-1of2">
								<?php echo $this->Number->currency($quoteLineItem['labor_cost_dollars']); ?>
								(<?php echo number_format(($quoteLineItem['labor_cost_hours'] * $quoteLineItem['labor_qty']), 2).__(' hrs'); ?>)<br />
								<?php echo $this->Number->currency($quoteLineItem['materials_cost_dollars']); ?><br />
								<?php echo $this->Number->currency($quoteLineItem['equipment_cost_dollars']); ?>
							</div>
						</div>
					</div>
					<?php if ($allowEdit): ?>
						<?php echo $this->Html->link($this->Html->image('icon-close.png', array('class' => 'left inline icon-close quote-line-item')), array('controller' => 'quote_line_items', 'action' => 'delete', $quoteLineItem['id']), array('escape' => false), __('delete_confirm')); ?>
					<?php endif; ?>
					<?php if ($allowEdit): ?>
						<b><?php echo $this->Html->link($quoteLineItem['name'], $editLink); ?></b>
					<?php else: ?>
						<b><?php echo $quoteLineItem['name']; ?></b>
					<?php endif; ?>
					<div class="small">
						<b><?php echo $this->Number->currency($quoteLineItem['price_subtotal']); ?></b>
						(<?php echo $quoteLineItem['qty']; ?> @ <?php echo $this->Number->currency($quoteLineItem['price_unit']); ?> <?php echo __('ea'); ?>)
					</div>
					<?php if (!empty($quoteLineItem['description'])): ?>
						<div class="light tinymce_container">
							<?php echo $quoteLineItem['description']; ?>
						</div>
					<?php endif; ?>
					<?php if (!empty($quoteLineItem['QuoteJob'])): ?>
						<div id="quote-job-summary-<?php echo $i; ?>" class="hidden">
							<?php echo $this->element('quote_job_summary', array('quoteJob' => $quoteLineItem)); ?>
						</div>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php else: ?>
	<?php 	if($showErrors) : ?>
		<tr class="action">
			<th>&nbsp;<?php echo __(Inflector::pluralize(Configure::read('Nomenclature.QuoteJob'))); ?></th>
			<td><?php #echo $this->Number->currency(array_sum(Set::extract('/price_subtotal', $quote['QuoteLineItem']))); ?></td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td>
				<?php echo $this->Html->link('Add Item to Quote.', array('controller' => 'quote_line_items', 'action' => 'add', $quote['Quote']['id'])); ?>
				<div class="error_msg">&#8592; Missing Tasks</div>
			</td>
		</tr>
	<?php 	endif; ?>
	<?php endif; ?>
</table>