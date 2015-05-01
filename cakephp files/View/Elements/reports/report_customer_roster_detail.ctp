<?php
#$total_leads = $data['Lead'][0][0]['lead_inactive'] + $data['Lead'][0][0]['lead_active'] + $data['Lead'][0][0]['lead_became_quoted_customer'];
#$total_quotes = $data['Quote'][0][0]['quote_is_inactive'] + $data['Quote'][0][0]['active_quote'] + $data['Quote'][0][0]['quote_sold'];
$title = "Customer Roster Detail Report";
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
				<tr><td class="nowrap" colspan="2"><?php echo $this->Web->address($address, false, ', ', false, false); ?></td></tr>
				<?php if(!empty($phone)) : ?>
				<tr><td class="nowrap" colspan="2"><?php echo $phone; ?></td></tr>
				<?php endif; ?>
				<tr><td class="nowrap" colspan="2">Primary Contact: <?php echo $contact; ?></td></tr>
				<tr><td class="nowrap" colspan="2">Contact Phone: <?php echo $phone_contact; ?></td></tr>
				<tr><td class="nowrap" colspan="2">Contact Email: <?php echo $email; ?></td></tr>
				<tr><td class="nowrap" colspan="2">Customer Score: <?php echo $customer['Stats']['Score']['score']; ?></td></tr>
			</table>
		</div>
		<?php 	endforeach;?>
	<?php else : ?>
	<span class="no_data"><?php echo __('No data exists for the parameters selected.'); ?></span>
	<?php endif; ?>
	</div>
</div>