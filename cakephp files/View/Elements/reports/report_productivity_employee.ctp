<?php
$title = "Employee Productivity Summary Report";
if($display_details) {
	$title = 'Employee Productivity Detail Report';
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
	<?php 	foreach($data['Data'] as $key=>$user) : ?>
		<div class="productivity_report_table_container">
			<table>
				<tr>
					<th class="nowrap" colspan="3"><?php echo $user['name']; ?></th>
					<th class="align-right nowrap" colspan="3">&nbsp;</th>
				</tr>
				<?php 
				if(!empty($user['Orders'])) :
					foreach ($user['Orders'] as $order) : ?>
				<tr class="customer-productivity-row">
					<td>&nbsp;</td>
					<?php 
					$address['line1'] = $order[0]['StatsByJob']['line1'];
					$address['line2'] = $order[0]['StatsByJob']['line2'];
					$address['city'] = $order[0]['StatsByJob']['city'];
					$address['st_prov'] = $order[0]['StatsByJob']['st_prov'];
					$address['zip_post'] = $order[0]['StatsByJob']['zip_post'];
					?>
					<td class="nowrap">
						<?php echo $order[0]['StatsByJob']['job_name']; ?><br />
						<span class="light small">(<?php echo $this->Web->address($address, false, ', '); ?>)</span>
					</td>
					<td><?php echo $order[0]['StatsByJob']['customer_name']; ?></td>
					<td>
						Job Profit/Loss: <?php echo $order[0][0]['score_productivity']; ?>%
					</td>
					<td class="nowrap" colspan="2"><?php 
						$total_cost = $order[0]['StatsByJobItem']['total_cost'];
						$user_total = $user['amount_total'] + $user['price'];
						$perc = 0;
						if($total_cost > 0) {
							$perc = ($user_total / $total_cost) * 100;
						} ?>
						<?php echo number_format($perc, 0); ?>% of Job Cost
					</td>
				</tr>
				<?php if($display_details && !empty($order['UserStats'])) : ?>
				<?php 	foreach($order['UserStats'] as $user) : ?>
				<tr class="">
					<td colspan="3">&nbsp;</td>
					<td>
						<?php echo 'Client Paid: $' . number_format($order[0][0]['invoices_amount_paid'], 2); ?><br /> 
						<?php echo 'Job Cost: $' . number_format($order[0]['StatsByJobItem']['total_cost'], 2); ?>
						
					</td>
					<td class="nowrap">
						Labor Submitted: $<?php echo number_format($user['amount_total'], 2); ?>
						<?php if(!empty($user['time_total'])) : ?>
						&nbsp;<span class="small light">(<?php echo number_format($user['time_total'], 2); ?> hours)</span>
						<?php endif; ?>
						<br />
						Materials Submitted: $<?php echo number_format($user['price'], 2); ?>
						
					</td>
					<td>&nbsp;</td>
				</tr>
				<?php 	endforeach; ?>
				<?php endif; ?>
				<?php endforeach; 
				endif; ?>
			</table>
		</div>
		<?php 	endforeach;?>
	<?php else : ?>
	<span class="no_data"><?php echo __('No data exists for the parameters selected.'); ?></span>
	<?php endif; ?>
	</div>
</div>