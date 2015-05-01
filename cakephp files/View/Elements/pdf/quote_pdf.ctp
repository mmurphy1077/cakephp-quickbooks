<?php
$display_line_item_price = false;
if($quote['Quote']['display_line_item_totals'] == 1) {
	$display_line_item_price = true;
}
$display_total_price = false;
if($quote['Quote']['display_total'] == 1) {
	$display_total_price = true;
}
$date_quote = $quote['Quote']['date_quote'];
if(empty($quote['Quote']['date_quote'])) {
	$date_quote = date('Y-m-d');
}
$contact_name = '';
if(!empty($quote['QuoteContact'])) {
	// Grab first and then see if there are any primary.
	$contact_name = $quote['QuoteContact'][0]['contact_name'];
	if(count($quote['QuoteContact']) > 1 && $quote['QuoteContact'][0]['primary'] == 0) {
		// loop
		foreach($quote['QuoteContact'] as $contact) {
			if($contact['primary'] == 1) {
				$contact_name = $contact['contact_name'];
			}
		}
	}
}
$jobsite = $quote['Address'];
$billing = $quote['BillingAddress'];
?>
<div class="grid page_element">
	<div id="quote_type" class="col-1of1 center"><h2><?php echo $quoteTypes[$quote['Quote']['quote_type_id']]; ?></h2></div>
	<div id="quote_who" class="col-1of2 left">
		<h2><?php echo $quote['Quote']['customer_name']; ?></h2>
		<?php if(!empty($contact_name)) : ?>
		<p><?php echo $contact_name; ?></p>
		<?php endif; ?>
		<p><?php echo $this->Web->address($billing, false, '<br />', false); ?></p>
	</div>
	<div id="quote_where" class="col-1of2">
		<!-- <h2>Quote #<?php echo $quote['Quote']['number']; ?></h2> -->
		<h2>
		<?php echo $this->Web->dt($date_quote, 'F j, Y'); ?></h2>
		<p><?php echo $quote['Quote']['name']; ?></p>
		<?php if(!empty($quote['Quote']['customer_supplied_job_number'])) : ?>
		<p><?php echo Configure::read('Nomenclature.Order') . ' Number: ' . $quote['Quote']['customer_supplied_job_number']; ?></p>
		<?php endif; ?>
		<p><?php echo $this->Web->address($jobsite, false); ?></p>
	</div>
</div>
<div class="page_element">
	<br/>
	<?php echo __('Thank you for the opportunity to provide you with a quote for the following scope of work.'); ?>
	<?php #echo nl2br($quote['Quote']['description']); ?>
</div>
<div class="line-items page_element">
	<table class="data1">
		<?php if (!empty($quote['QuoteLineItem'])): ?>
			<?php foreach ($quote['QuoteLineItem'] as $i => $quoteLineItem): ?>
				<tr class="group">
					<!-- 
					<th>
						<?php echo str_pad($i + 1, 3, 0, STR_PAD_LEFT); ?>
						&mdash;
						<?php echo __($quoteLineItem['QuoteLineItemType']['name']); ?>
					</th>
					 -->
					<td class="group">
						<b><?php echo $quoteLineItem['name']; ?></b>
						<?php if (!empty($quoteLineItem['description'])): ?>
							<div class="group quote_line_item_description tinymce_container">
								<span class="quote_line_item_indent"><?php echo $quoteLineItem['description']; ?></span>
							</div>
						<?php endif; ?>
					</td>
					<?php if($display_line_item_price) : 
					$price = $quoteLineItem['price_unit'];
					if(!empty($quoteLineItem['qty'])) {
						$price = $quoteLineItem['qty'] * $quoteLineItem['price_unit'];
					}
					?>
					<td><b><?php echo $this->Number->currency($price); ?></b></td>
					<?php endif; ?>
				</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</table>
	<?php if($display_total_price) : ?>
	<div class="total">
		<?php echo strtoupper(__('Total Job Estimate')).' = '; ?>
		<?php echo $this->Number->currency($quote['Quote']['price_total']); ?>
	</div>
	<?php endif; ?>
</div>
<div class="footer page_element group">
	<p><?php echo __('If you have any questions or comments, please call me at ' . $this->Web->phone($quote['AccountRep']['UserProfile']). ' or email me at <span class="email">&nbsp;' . $quote['AccountRep']['email'].'</span>.'); ?></p>
	<?php echo __('Thank You,'); ?>
	<table class="standard">
		<tr>
			<td class="footer_address">
				<?php echo $this->Web->humanName($quote['AccountRep'], 'full'); ?><br />
				<?php echo $this->Session->read('Application.settings.ApplicationSetting.company_name'); ?><br />
			</td>
			<td class="signature_container">
				<br />
				_______________________________________________<br />
				<?php echo __('Please sign (Shows Approval)'); ?>
			</td>
		</tr>
		<tr>
			<td class="footer_address">
				<?php echo __('Please remit to:'); ?><br />
				<?php echo $this->Session->read('Application.settings.ApplicationSetting.company_name'); ?><br />
				<?php $primaryAddress = $this->Session->read('Application.address.primary'); ?>
				<?php echo $primaryAddress['Address']['line1']; ?><br />
				<?php echo $primaryAddress['Address']['line2']; ?><br /><br />
			</td>
			<td class="signature_container">
				_______________________________________________<br />
				<?php echo __('Please print Above Name'); ?>
			</td>
		</tr>
	</table>
</div>