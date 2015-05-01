<?php
$title = "Order Productivity Summary Report";
if($display_details) {
	$title = 'Order Productivity Detail Report';
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
	<div class="productivity_report_table_container">
		<table>
	<?php 	foreach($data['Data'] as $key=>$order) : ?>
				<tr class="customer-productivity-row">
					<td>&nbsp;</td>
					<?php 
					$address['line1'] = $order['StatsByJob']['line1'];
					$address['line2'] = $order['StatsByJob']['line2'];
					$address['city'] = $order['StatsByJob']['city'];
					$address['st_prov'] = $order['StatsByJob']['st_prov'];
					$address['zip_post'] = $order['StatsByJob']['zip_post'];
					?>
					<td>
						<?php echo $order['StatsByJob']['job_name']; ?> <br />
						<span class="small light">(<?php echo $this->Web->address($address, false, ', '); ?>)</span>
					</td>
					<td><?php echo $order['StatsByJob']['customer_name']; ?></td>
					<td><?php #debug($order); ?>
						<?php echo 'Client Paid: $' . number_format($order[0]['invoices_amount_paid'], 2); ?><br /> 
						<?php echo 'Job Cost: $' . number_format($order['StatsByJobItem']['total_cost'], 2); ?>
					</td>
					<td class="align-right nowrap" colspan="2">Job Profit/Loss: <?php echo $order[0]['score_productivity']; ?>%</td>
				</tr>
				<?php if($display_details && !empty($order['UserStats'])) : ?>
				<tr class="userStats">
					<td>&nbsp;</td>
					<td colspan="5"><b>Employee Details</b></td>
				</tr>
				<?php 	foreach($order['UserStats'] as $user) : ?>
				<tr class="userStats">
					<td colspan="2">&nbsp;</td>
					<td><?php echo $user['name']; ?></td>
					<td>
						Labor Submitted: $<?php echo number_format($user['amount_total'], 2); ?>
						<?php if(!empty($user['time_total'])) : ?>
						&nbsp;<span class="small light">(<?php echo number_format($user['time_total'], 2); ?> hours)</span>
						<?php endif; ?>
						<br />
						Materials Submitted: $<?php echo number_format($user['price'], 2); ?>
					</td>
					<td class="align-right nowrap">
						<?php 
						$total_cost = $order['StatsByJobItem']['total_cost'];
						$user_total = $user['amount_total'] + $user['price'];
						$perc = 0;
						if($total_cost > 0) {
							$perc = ($user_total / $total_cost) * 100;
						} ?>
						<?php echo number_format($perc, 0); ?>% of Job Cost
					</td>
					<td>&nbsp;</td>
				</tr>
				
				<?php endforeach; 
				endif; ?>
		<?php 	endforeach;?>
		</table>
	</div>
	<?php else : ?>
	<span class="no_data"><?php echo __('No data exists for the parameters selected.'); ?></span>
	<?php endif; ?>
	</div>
</div>