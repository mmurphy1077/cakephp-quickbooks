<?php
// QUOTE BY 
// NO ORDERING
$title = "Revenue Snapshot Report";
?>
<div id="application_report_container">
	<div id="report_header_container" class="abstract page_element group">
		<div class="company_header"><?php echo $applicationSettings['ApplicationSetting']['company_name']; ?></div>
		<div class="title"><?php echo  __($title); ?></div>
		<div class="top_left"><?php echo  date('m/d/Y'); ?></div>
		<div class="top_right screen_only title-buttons"><?php echo  $this->Html->link('Print', array('controller' => 'reports', 'action' => 'print_pdf')); ?></div>
	</div>
	<div id="" class="abstract page_element">
		<div class="snapshot_table_container">
			<table>
				<tr>
					<th colspan="2">TOTAL REVENUES</th>
				</tr>
				<tr>
					<td>Total Revenues (MTD)</td>
					<td class="align-right"><?php echo '$'. number_format($data['Data']['MTD']['Total'], 2); ?></td>
				</tr>
				<tr>
					<td>Total Revenues (YTD)</td>
					<td class="align-right"><?php echo '$'. number_format($data['Data']['YTD']['Total'], 2); ?></td>
				</tr>
			</table>
		</div>
	</div>
	<?php unset($data['Data']['MTD']['Total']);?>
	<div id="" class="abstract page_element">
		<div class="snapshot_table_container">
			<table>
				<tr>
					<th colspan="2">REVENUES BY TYPE (MTD)</th>
				</tr>
				<?php if(!empty($data['Data']['MTD'])) : ?>
				<?php 	foreach($data['Data']['MTD'] as $data) : ?>
				<tr>
					<td><?php echo $data['name']; ?></td>
					<td class="align-right"><?php echo '$'. number_format($data['total'], 2); ?></td>
				</tr>
				<?php 	endforeach; ?>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>