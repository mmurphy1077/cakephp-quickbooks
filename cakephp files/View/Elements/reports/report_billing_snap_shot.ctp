<?php
$title = "Billing Snapshot Report";
?>
<div id="application_report_container">
	<div id="report_header_container" class="abstract page_element group">
		<div class="company_header"><?php echo $applicationSettings['ApplicationSetting']['company_name']; ?></div>
		<div class="title"><?php echo  __($title); ?></div>
		<div class="top_left"><?php echo  date('m/d/Y'); ?></div>
		<div class="top_ screen_only title-buttons"><?php echo  $this->Html->link('Print', array('controller' => 'reports', 'action' => 'print_pdf')); ?></div>
	</div>
	<div id="" class="abstract page_element">
		<div class="snapshot_table_container">
			<table>
				<tr>
					<th colspan="2">BILLING STATS (MTD)</th>
				</tr>
				<tr>
					<td>Approved</td>
					<td class=""><?php echo $data['Data']['MTD'][0][0]['approved_count_in_range']; ?> ($<?php echo number_format($data['Data']['MTD'][0][0]['approved_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Invoiced</td>
					<td class=""><?php echo $data['Data']['MTD'][0][0]['invoiced_count_in_range']; ?> ($<?php echo number_format($data['Data']['MTD'][0][0]['invoiced_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Due</td>
					<td class=""><?php echo $data['Data']['MTD'][0][0]['due_count_in_range']; ?> ($<?php echo number_format($data['Data']['MTD'][0][0]['due_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Paid</td>
					<td class=""><?php echo $data['Data']['MTD'][0][0]['paid_count_in_range']; ?> ($<?php echo number_format($data['Data']['MTD'][0][0]['paid_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Outstanding</td>
					<td class=""><?php echo $data['Data']['MTD'][0][0]['outstanding_invoices_count']; ?> ($<?php echo number_format($data['Data']['MTD'][0][0]['outstanding_invoices_amount'], 2); ?>)</td>
				</tr>
			</table>
		</div>
	</div>
	<?php unset($data['Data']['MTD']['Total']);?>
	<div id="" class="abstract page_element">
		<div class="snapshot_table_container">
			<table>
				<tr>
					<th colspan="2">BILLING STATS (YTD)</th>
				</tr>
				<tr>
					<td>Approved</td>
					<td class=""><?php echo $data['Data']['YTD'][0][0]['approved_count_in_range']; ?> ($<?php echo number_format($data['Data']['YTD'][0][0]['approved_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Invoiced</td>
					<td class=""><?php echo $data['Data']['YTD'][0][0]['invoiced_count_in_range']; ?> ($<?php echo number_format($data['Data']['YTD'][0][0]['invoiced_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Due</td>
					<td class=""><?php echo $data['Data']['YTD'][0][0]['due_count_in_range']; ?> ($<?php echo number_format($data['Data']['YTD'][0][0]['due_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Paid</td>
					<td class=""><?php echo $data['Data']['YTD'][0][0]['paid_count_in_range']; ?> ($<?php echo number_format($data['Data']['YTD'][0][0]['paid_amount_in_range'], 2); ?>)</td>
				</tr>
				<tr>
					<td>Outstanding</td>
					<td class=""><?php echo $data['Data']['YTD'][0][0]['outstanding_invoices_count']; ?> ($<?php echo number_format($data['Data']['YTD'][0][0]['outstanding_invoices_amount'], 2); ?>)</td>
				</tr>
			</table>
		</div>
	</div>
</div>