<?php
$title = "Revenue Summary Report";
if($display_details) {
	$title = 'Revenue Detail Report';
}
$total = '$' . number_format($data['Data']['Total'], 2); 
unset($data['Data']['Total']); 
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
			<?php echo $this->Web->dt($target_report['end_date'], 'short_4'); ?>&nbsp;
		</div>	
		<div class="top_left"><?php echo date('m/d/Y'); ?></div>
		<div class="top_right screen_only title-buttons"><?php echo $this->Html->link('Print', array('controller' => 'reports', 'action' => 'print_pdf')); ?></div>
	</div>
	<div id="report_stats_container" class="abstract page_element">
		<div class="report_table_container">
			<table>
				<tr><td class="rowrap">Total Revenue: <?php echo $total; ?></td></tr>
			</table>
		</div>
	</div>
	<div id="" class="abstract page_element">
	<?php if(!empty($data['Data'])) : ?>
	<?php 	foreach($data['Data'] as $key=>$revenue) : ?>
		<div class="revenue_report_table_container">
			<table>
				<tr>
					<th class="nowrap" colspan="6"><?php echo $revenue['name']; ?></th>
				</tr>
				<?php foreach($revenue['data'] as $re) : 
						$address = $this->Web->address($re['RevenueByOrder'], false, ', ', false, false, false); ?>
				<tr>
					<td class="nowrap"><?php echo date('m/d/Y', strtotime($re['RevenueByOrder']['date_invoiced'])); ?></td>
					<td class="nowrap"><?php echo $re['RevenueByOrder']['customer_name']; ?></td>
					<td class="nowrap">
						<?php echo $re['RevenueByOrder']['order_name']; ?>
						<?php if($display_details && !empty($re['RevenueByOrder']['order_type'])) : ?>
						<br />
						<span class="light small">Job Type:&nbsp;<?php echo $re['RevenueByOrder']['order_type']; ?></span>
						<?php endif; ?>
					</td>
					<td class="nowrap"><?php echo $address; ?></td>
					<td class="nowrap">
						<?php 
						$amount_display = '';
						if(!empty($re[0]['amount_paid']) && $re[0]['amount_paid'] > 0) {
							$amount_display = '$' . number_format($re[0]['amount_paid'], 2);
						}
						if(!empty($re[0]['amount_outstanding']) && $re[0]['amount_outstanding'] > 0) {
							if(!empty($amount_display)) {
								$amount_display = $amount_display . '<br />';
							}
							$amount_display = $amount_display . '$' . number_format($re[0]['amount_outstanding'], 2) . '&nbsp;&nbsp;<span class="light">(outstanding)</span>';
						}
						echo $amount_display;
						?>
					</td>
				</tr>
				<?php endforeach;?>
				<tr>
					<td colspan="3">&nbsp;</td>
					<td class="right"><b>Total:</b>&nbsp;&nbsp;</td>
					<td class="nowrap"><?php echo '$' . number_format($revenue['total'], 2); ?></td>
				</tr>
		</div>
		<?php 	endforeach;?>
	<?php else : ?>
	<span class="no_data"><?php echo __('No data exists for the parameters selected.'); ?></span>
	<?php endif; ?>
	</div>
</div>