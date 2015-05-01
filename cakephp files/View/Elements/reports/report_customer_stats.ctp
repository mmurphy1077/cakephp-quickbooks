<?php
#$total_leads = $data['Lead'][0][0]['lead_inactive'] + $data['Lead'][0][0]['lead_active'] + $data['Lead'][0][0]['lead_became_quoted_customer'];
#$total_quotes = $data['Quote'][0][0]['quote_is_inactive'] + $data['Quote'][0][0]['active_quote'] + $data['Quote'][0][0]['quote_sold'];
$title = "Customer Stats Report";
?>
<div id="application_report_container">
	<div id="report_header_container" class="abstract page_element group">
		<div class="company_header"><?php echo $applicationSettings['ApplicationSetting']['company_name']; ?></div>
		<div class="title"><?php echo  __($title); ?></div>
		<div class="date_range">
			<?php echo $this->Web->dt($target_report['start_date'], 'short_4'); ?> 
			<?php if (!empty($target_report['end_date'])) : ?>
			- 
			<?php endif; ?>
			<?php echo $this->Web->dt($target_report['end_date'], 'short_4'); ?>
		</div>	
		<div class="top_left"><?php echo date('m/d/Y'); ?></div>
		<div class="top_right screen_only title-buttons"><?php echo $this->Html->link('Print', array('controller' => 'reports', 'action' => 'print_pdf')); ?></div>
	</div>
	<div id="" class="abstract page_element">
	<?php if(!empty($data['Data'])) : ?>
	<?php 	foreach($data['Data'] as $key=>$customer) :
				$outstanding = '-';
				if(!empty($customer['Stats']['InvoiceStats'])) {
					$outstanding = '$'.number_format($customer['Stats']['InvoiceStats']['outstanding_invoices_amount'], 2);
				}
				$address = null;
				if(!empty($customer['Address'])) {
					$address = $customer['Address'][0];
				}
				$phone = $this->Web->displayPhoneList($customer['Customer']);
				$contact = '(no primary indicated)';
				$phone_contact = null;
				$email = null;
				if(!empty($customer['Contact'])) {
					$contact = $customer['Contact'][0]['name_first'] . ' ' . $customer['Contact'][0]['name_last'];
					$phone_contact = $this->Web->displayPhoneList($customer['Contact'][0]);
					$email = $customer['Contact'][0]['email'];
				}
				?>
		<div class="customer_report_table_container">
			<table>
				<tr>
					<th class="nowrap"><?php echo $customer['Customer']['name']; ?></th>
					<th class="align-right nowrap">Outstanding Balance: <?php echo $outstanding; ?></th>
				</tr>
				<tr>
					<td class="nowrap">Quotes (#):&nbsp;&nbsp;<?php echo number_format($customer['Stats']['quote_count'], 2); ?></td>
					<td class="nowrap">Score (Sales):&nbsp;&nbsp;<?php echo $customer['Stats']['Score']['sales']; ?></td>
				</tr>
				<tr>
					<td class="nowrap">Quotes ($):&nbsp;&nbsp;<?php echo '$'.number_format($customer['Stats']['quote_amount'], 2); ?></td>
					<td class="nowrap">Score (Productivity):&nbsp;&nbsp;<?php echo $customer['Stats']['Score']['productivity']; ?></td>
				</tr>
				<tr>
					<td class="nowrap">Jobs (#):&nbsp;&nbsp;<?php echo number_format($customer['Stats']['order_count'], 2); ?></td>
					<td class="nowrap">Score (Payment):&nbsp;&nbsp;<?php echo $customer['Stats']['Score']['payment']; ?></td>
				</tr>
				<tr>
					<td class="nowrap">Jobs ($):&nbsp;&nbsp;<?php echo '$'.number_format($customer['Stats']['order_amount'], 2); ?></td>
					<td class="nowrap">Score (Comp):&nbsp;&nbsp;<?php echo $customer['Stats']['Score']['score']; ?></td>
				</tr>
				<tr>
					<td class="nowrap">QTJ Ratio (#):&nbsp;&nbsp;<?php echo $customer['Stats']['Ratio']['by_count']; ?></td>
				</tr>
				<tr>
					<td class="nowrap"> QTJ Ratio ($):&nbsp;&nbsp;<?php echo $customer['Stats']['Ratio']['by_amount']; ?></td>
				</tr>
			</table>
		</div>
		<?php 	endforeach;?>
	<?php else : ?>
	<span class="no_data"><?php echo __('No data exists for the parameters selected.'); ?></span>
	<?php endif; ?>
	</div>
</div>