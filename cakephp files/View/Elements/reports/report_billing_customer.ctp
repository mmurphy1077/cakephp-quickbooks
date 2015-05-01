<?php
$title = "Customer Billing Summary Report";
if($display_details) {
	$title = 'Customer Billing Detail Report';
}
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
				/*
				 * Stats
				 */
				$overall['total_invoiced'] = 0;
				$overall['amount_paid'] = 0;
				$overall['outstanding_amount'] = 0;
				$overall['num_of_invoices_scored'] = 0;
				if(array_key_exists('InvoiceStats', $customer['Stats']) && !empty($customer['Stats']['InvoiceStats'])) {
					$overall['total_invoiced'] = $customer['Stats']['InvoiceStats']['total_invoiced'];
					$overall['amount_paid'] = $customer['Stats']['InvoiceStats']['invoices_amount_paid'];
					$overall['outstanding_amount'] = $customer['Stats']['InvoiceStats']['outstanding_invoices_amount'];
					$overall['num_of_invoices_scored'] = $customer['Stats']['InvoiceStats']['num_of_invoices_scored'];
				}
				$overall['payment_score'] = $customer['Stats']['Score']['payment'];
				
				$range['total_invoiced'] = $customer['InvoiceStats']['total_invoiced'];
				$range['amount_paid'] = $customer['InvoiceStats']['invoices_amount_paid'];
				$range['outstanding_amount'] = $customer['InvoiceStats']['outstanding_invoices_amount'];
				$range['payment_score'] = $customer['InvoiceStats']['score_payment'];
				$range['num_of_invoices_scored'] = $customer['InvoiceStats']['num_of_invoices_scored'];
				
				/** FORMAT */
				if(!empty($overall['total_invoiced'])) {
					$overall['total_invoiced'] = '$' . number_format($overall['total_invoiced'], 2);
				}
				if(!empty($overall['amount_paid'])) {
					$overall['amount_paid'] = '$' . number_format($overall['amount_paid'], 2);
				}
				if(!empty($overall['outstanding_amount'])) {
					$overall['outstanding_amount'] = '$' . number_format($overall['outstanding_amount'], 2);
				}
					
				if(!empty($range['total_invoiced'])) {
					$range['total_invoiced'] = '$' . number_format($range['total_invoiced'], 2);
				}
				if(!empty($range['amount_paid'])) {
					$range['amount_paid'] = '$' . number_format($range['amount_paid'], 2);
				}
				if(!empty($range['outstanding_amount'])) {
					$range['outstanding_amount'] = '$' . number_format($range['outstanding_amount'], 2);
				}
				?>
		<div class="productivity_report_table_container">
			<table>
				<tr>
					<th class="nowrap" colspan="6"><?php echo $customer['Customer']['name']; ?></th>
				</tr>
				<tr><td colspan="6"><b>Invoice Statistics - Includes invoices that have been approved and invoiced to the customer.  Date range statistics include invoices that were created within the selected date range.</b></td></tr>
				<tr>
					<td colspan="2"><b>Date Range</b></td>
					<td>Payment Score: <?php echo $range['payment_score']; ?></td>
					<td colspan="2"><b>Overall</b></td>
					<td>Payment Score: <?php echo $overall['payment_score']; ?></td>
				</tr>
				<tr>
					<td colspan="3">Total Invoiced: <?php echo $range['total_invoiced']; ?></td>
					<td colspan="3">Total Invoiced: <?php echo $overall['total_invoiced']; ?></td>
				</tr>
				<tr>
					<td>Total Paid: <?php echo $range['amount_paid']; ?></td>
					<td colspan="2">Total Outstanding: <?php echo $range['outstanding_amount']; ?></td>
					<td>Total Paid: <?php echo $overall['amount_paid']; ?></td>
					<td colspan="2">Total Outstanding: <?php echo $overall['outstanding_amount']; ?></td>
				</tr>
				
				<?php
				if($display_details) :  
					if(!empty($customer['Orders'])) : ?>
					<?php foreach ($customer['Orders'] as $order) : ?>
					<tr><td colspan="6">&nbsp;</td></tr>
					<tr>
						<td colspan="4"><b><?php echo __(Inflector::pluralize(Configure::read('Nomenclature.Order'))); ?></b></td>
						<td class="align-right"><b>Score (payment)&nbsp;&nbsp;</b></td>
						<td><b><?php echo $order[0]['score_payment']; ?></b></td>
					</tr>
					<tr class="customer-productivity-row">
						<td><?php echo $order['StatsByJob']['job_name']; ?></td>
						<td colspan="3"><?php echo $this->Web->address($order['StatsByJob'], false); ?></td>
						<td class="align_right">
							Job Cost:&nbsp;&nbsp;<br />
							Total Invoiced:&nbsp;&nbsp;<br />
							Paid:&nbsp;&nbsp;<br />
							Oustanding:&nbsp;&nbsp;
						</td>
						<td class="">
							<b><?php echo '$' . number_format($order['StatsByJobItem']['total_cost'], 2); ?></b><br />
							<b><?php echo '$' . number_format($order[0]['total_invoiced'], 2); ?></b><br />
							<?php echo '$' . number_format($order[0]['invoices_amount_paid'], 2); ?><br />
							<?php echo '$' . number_format($order[0]['outstanding_invoices_amount'], 2); ?>
						</td>
					</tr>
					<?php if(!empty($order['Invoices'])) : ?>
					<tr><td colspan="6"><b><?php echo __(Inflector::pluralize('Invoice')); ?></b></td></tr>
					<?php 	foreach($order['Invoices'] as $invoice) : ?>
					<tr>
						<td>
							Created: <?php echo $this->Web->dt($invoice['Invoice']['created'], 'short_4'); ?>
						</td>
						<td><b><?php echo $invoice_statuses[$invoice['Invoice']['status']]; ?></b></td>
						<td colspan="3"><?php echo $invoice['Invoice']['contact_name']; ?> <span class="light small">Phone: <?php echo $invoice['Invoice']['contact_phone']; ?></span></td>
						<td class="right">Total: <b><?php echo '$' . number_format($invoice['Invoice']['total'], 2); ?></b></td>
					</tr>
					<tr>
						<td>
							<?php 
							// Check if overdue
							$overdue = false;
							if(!empty($invoice['Invoice']['date_due']) && ($invoice['Invoice']['date_due'] < date('Y-m-d')) && ($invoice['Invoice']['status'] == INVOICE_STATUS_BILLED)) {
								$overdue = true;
							}
							?>
							Approved: <?php echo $this->Web->dt($invoice['Invoice']['date_approved'], 'short_4'); ?><br />
							Invoiced: <?php echo $this->Web->dt($invoice['Invoice']['date_invoiced'], 'short_4'); ?><br />
							Due: <?php echo $this->Web->dt($invoice['Invoice']['date_due'], 'short_4'); ?>
							<?php if($overdue) {
								echo ' - <br>OVERDUE</br>';
							} ?>
							<br />
							Paid: <?php echo $this->Web->dt($invoice['Invoice']['date_paid'], 'short_4'); ?><br />
						</td>
						<td colspan="5"><?php echo $this->Web->address($invoice['Address'], false, ', '); ?></td>
					</tr>
					<tr><td colspan="6">&nbsp;</td></tr>
					<?php 	endforeach; ?>
					<?php endif; ?>
					<?php endforeach; 
					endif; 
				endif; ?>
			</table>
		</div>
		<?php 	endforeach;?>
	<?php else : ?>
	<span class="no_data"><?php echo __('No data exists for the parameters selected.'); ?></span>
	<?php endif; ?>
	</div>
</div>