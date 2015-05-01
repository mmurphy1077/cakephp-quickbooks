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
		<div class="customer_report_table_container">
			<table>
				<tr>
					<th colspan="6" class="nowrap"><b>Overdue</b></th>
				</tr>
				<?php if(!empty($data['Data']['overdue'])) : ?>
				<?php 	foreach($data['Data']['overdue'] as $callback) : ?>
							<tr>
								<td class="nowrap" colspan="2"><?php echo $callback[0]['what']; ?></td>
								<td class="nowrap" colspan="2">Contact: <b><?php echo $callback[0]['contact']; ?></b></td>
								<td class="nowrap" colspan="2">Account Rep: <?php echo $callback[0]['account_rep']; ?></td>
							</tr>
							<tr>
								<td class="nowrap" colspan="2"><?php echo $callback[0]['company']; ?></td>
								<td class="nowrap" colspan="2">Phone: <b><?php echo $callback[0]['phone']; ?></b></td>
							</tr>
				<?php 	endforeach; ?>
				<?php endif; ?>
				<tr><td colspan="6">&nbsp;</td></tr>
				<tr><td colspan="6">&nbsp;</td></tr>
				<tr>
					<th colspan="6" class="nowrap"><b>Today</b></th>
				</tr>
				<?php if(!empty($data['Data']['today'])) : ?>
				<?php 	foreach($data['Data']['today'] as $callback) : ?>
							<tr>
								<td class="nowrap" colspan="2"><?php echo $callback[0]['what']; ?></td>
								<td class="nowrap" colspan="2">Contact: <b><?php echo $callback[0]['contact']; ?></b></td>
								<td class="nowrap" colspan="2">Account Rep: <?php echo $callback[0]['account_rep']; ?></td>
							</tr>
							<tr>
								<td class="nowrap" colspan="2"><?php echo $callback[0]['company']; ?></td>
								<td class="nowrap" colspan="2">Phone: <b><?php echo $callback[0]['phone']; ?></b></td>
							</tr>
				<?php 	endforeach; ?>
				<?php endif; ?>
				<tr><td colspan="6">&nbsp;</td></tr>
				<tr><td colspan="6">&nbsp;</td></tr>
				<tr>
					<th colspan="6" class="nowrap"><b>Tomorrow</b></th>
				</tr>
				<?php if(!empty($data['Data']['tomorrow'])) : ?>
				<?php 	foreach($data['Data']['tomorrow'] as $callback) : ?>
							<tr>
								<td class="nowrap" colspan="2"><?php echo $callback[0]['what']; ?></td>
								<td class="nowrap" colspan="2">Contact: <b><?php echo $callback[0]['contact']; ?></b></td>
								<td class="nowrap" colspan="2">Account Rep: <?php echo $callback[0]['account_rep']; ?></td>
							</tr>
							<tr>
								<td class="nowrap" colspan="2"><?php echo $callback[0]['company']; ?></td>
								<td class="nowrap" colspan="2">Phone: <b><?php echo $callback[0]['phone']; ?></b></td>
							</tr>
				<?php 	endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	
	</div>
</div>