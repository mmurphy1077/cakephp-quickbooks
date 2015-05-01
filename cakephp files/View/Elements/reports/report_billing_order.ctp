<?php
$title = Configure::read('Nomenclature.Order') . ' Billing Summary Report';
if($display_details) {
	$title = Configure::read('Nomenclature.Order') . ' Billing Detail Report';
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
	<?php if(!empty($data['Data'])) :  ?>
	<?php 	foreach($data['Data'] as $key=>$order) : ?>
		<div class="productivity_report_table_container">
			<table>
					<tr>
						<th>Job Name</th>
						<th colspan="3">Address</th>
						<th class="align-right">Score (payment)&nbsp;&nbsp;</th>
						<th><?php echo $order[0]['score_payment']; ?></th>
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
					<?php if($display_details && !empty($order['Invoices'])) : ?>
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
					<?php  
					endif; ?>
			</table>
		</div>
		<?php 	endforeach;?>
	<?php else : ?>
	<span class="no_data"><?php echo __('No data exists for the parameters selected.'); ?></span>
	<?php endif; ?>
	</div>
</div>