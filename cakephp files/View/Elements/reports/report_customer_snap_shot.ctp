<?php
#$total_leads = $data['Lead'][0][0]['lead_inactive'] + $data['Lead'][0][0]['lead_active'] + $data['Lead'][0][0]['lead_became_quoted_customer'];
#$total_quotes = $data['Quote'][0][0]['quote_is_inactive'] + $data['Quote'][0][0]['active_quote'] + $data['Quote'][0][0]['quote_sold'];
$title = "Sales Snapshot Report";
?>
<div id="application_report_container">
	<div id="report_header_container" class="abstract page_element group">
		<div class="company_header"><?php echo $applicationSettings['ApplicationSetting']['company_name']; ?></div>
		<div class="title"><?php echo  __($title); ?></div>
		<div class="top_left"><?php echo date('m/d/Y'); ?></div>
		<div class="top_right screen_only title-buttons"><?php echo $this->Html->link('Print', array('controller' => 'reports', 'action' => 'print_pdf')); ?></div>
	</div>
	<div id="" class="abstract page_element">
		<div class="customer_report_table_container">
			<table>
				<tr>
					<th class="nowrap">Customer Name</th>
					<th class="center nowrap">#Jobs<br />(Total)</th>
					<th class="center nowrap"># Jobs<br />(12mos)</th>
					<th class="nowrap">QTO Ratio (#)</th>
					<th class="nowrap">QTO Ratio ($)</th>
					<th class="nowrap">Avg. Profit</th>
					<th class="nowrap">Avg Pay</th>
					<th class="nowrap">Score</th>
				</tr>
				<?php if(!empty($data)) : ?>
				<?php 	foreach($data['Data'] as $key=>$customer) : 
							// Some active customers may not have quotes... check first
							$quote_count = 0;
							$quote_amount = 0;
							if(array_key_exists('quote_count', $customer)) {
								$quote_count = $customer['quote_count'];
							}
							if(array_key_exists('quote_amount', $customer)) {
								$quote_amount = $customer['quote_amount'];
							} 
							
							// Some Customers may not have Invoices
							$avg_days = '-';
							if(!empty($customer['InvoiceStats'])) {
								$avg_days = $customer['InvoiceStats']['days_it_took_to_pay']/$customer['InvoiceStats']['num_of_invoices'] . ' days';
							}
							?>
						<tr>
							<td class="nowrap "><?php echo $customer['customer_name']; ?></td>
							<td class="nowrap center"><?php echo $customer['order_count']; ?></td>
							<td class="nowrap center"><?php echo $customer['order_count_past_12']; ?></td>
							<td class="nowrap"><?php echo $this->Web->displayQuoteToOrderRatio('count', $quote_count, $customer['order_count'], $customer['orders_generated_without_quote']); ?></td>
							<td class="nowrap"><?php echo $this->Web->displayQuoteToOrderRatio('amount', $quote_amount, $customer['order_amount'], $customer['orders_total_generated_without_quote']); ?></td>
							<td class="nowrap"><?php echo 0; ?></td>
							<td class="nowrap"><?php echo $avg_days; ?></td>
							<td class="nowrap center"><?php echo $this->Web->displayQuoteToOrderRatio('score', $quote_amount, $customer['order_amount'], $customer['orders_total_generated_without_quote']); ?></td>
						</tr>
				<?php 	endforeach;?>
				<?php else : ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>