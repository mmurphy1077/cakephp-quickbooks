<?php
$this->Html->script('jquery/jquery.jeditable', false);
?>
<?php if (!empty($data)): ?>
	<?php foreach ($data as $key=>$result): ?>
		<table id="invoice-times-index-table" class="standard order nohover">
			<tr>
				<th colspan="5"><?php echo $result['date']; ?></th>
				<th class=""><?php echo __(''); ?></th>
				<!-- 
				<th>&nbsp;</th>
				<th><?php echo __('Date'); ?></th>
				<th><?php echo __('Hours Entered'); ?></th>
				<th><?php echo __('Time'); ?></th>
				<th class=""><?php echo __(''); ?></th>
				<th><?php echo __('Status'); ?></th>
				<th>&nbsp;</th> -->
				<th class="small align-right"><?php echo $this->Html->link('view details', array('#'), array('id' => $key, 'class' => 'toggle_page_items_button')); ?></th>
			</tr>
			<tr id="<?php echo $key; ?>_toggle_item" class="<?php echo $key; ?>_toggle_item detail-item">
				<th>Employee</th>
				<th>Reg</th>
				<th>OT</th>
				<th>DT</th>
				<th>Billable</th>
				<th>Rate</th>
				<th>Cost</th>
				<!-- <th class="hidden-xs hidden-sm">Comments</th> -->
				<!-- <th class="center"><span class="detail-item">Included</th> -->
			</tr>
			<?php 
			$date_totals['date'] = $result['date'];
			$date_totals['date_short'] = $result['date_short'];
			$date_totals['name'] = '';
			$date_totals['type'] = '';
			$date_totals['time'] = 0;
			$date_totals['cost'] = 0;
			$worker_totals['date'] = $result['date'];
			$worker_totals['date_short'] = $result['date_short'];
			$worker_totals['name'] = '';
			$worker_totals['type'] = '';
			$worker_totals['time'] = 0;
			$worker_totals['cost'] = 0;
			$count = 0;
			$worker_id = null; ?>
			<?php if(!empty($result['OrderTime'])) : ?>
			<?php 	foreach($result['OrderTime'] as $order_time) :
						$count = $count + 1;
						$display_worker = "";
						if($worker_id != $order_time['OrderTime']['worker_id']) {
							$display_worker =  $this->Web->humanName($order_time['Worker']) . ' ' . '<span class="small light">(' . $order_time['Worker']['Group']['name'] . ')</span>';
							
							// Determine if a work itemized daily summary is required
							// This code will be executed when there is more than one worker whom has logged time for the same day.
							// If there is only one worker (or the last worker in the data set), the element will be called at the end of the loop.
							if(!empty($worker_id)) {
								echo $this->element('invoice/invoice_time_worker_daily_summary_total', array('data' => $worker_totals, 'display_mode' => 'itemized', 'display' => $display, 'key' => $key, 'count' => $count, 'worker' => $summary_worker, 'permission_attr' => $permission_attr));
							}
							// Reset
							$worker_totals['time'] = 0;
							$worker_totals['cost'] = 0;
						}
						$date_totals['time'] = $date_totals['time'] + $order_time['OrderTime']['invoice_time_total']; 
						$date_totals['cost'] = $date_totals['cost'] + ($order_time['OrderTime']['invoice_time_total'] * $order_time['OrderTime']['invoice_rate']); 
						$worker_totals['time'] = $worker_totals['time'] + $order_time['OrderTime']['invoice_time_total'];
						$rt = $order_time['OrderTime']['time_reg'] * $order_time['OrderTime']['invoice_rate'];
						$ot = ($order_time['OrderTime']['time_ot'] * $order_time['OrderTime']['invoice_rate']) * 1.5;
						$dt = ($order_time['OrderTime']['time_dt'] * $order_time['OrderTime']['invoice_rate']) * 2;
						$worker_totals['cost'] = $worker_totals['cost'] + ($rt + $dt + $ot);
						$worker_id = $order_time['OrderTime']['worker_id'];
						?>
					<tr class="<?php echo $key; ?>_toggle_item detail-item">
						<td><?php echo $this->Web->humanName($order_time['Worker']); ?></td>
						<td><?php echo number_format($order_time['OrderTime']['invoice_time_reg'], 2); ?></td>
						<td><?php echo number_format($order_time['OrderTime']['invoice_time_ot'], 2); ?></td>
						<td><?php echo number_format($order_time['OrderTime']['invoice_time_dt'], 2); ?></td>
						<td><b><?php echo number_format($order_time['OrderTime']['invoice_time_total'], 2); ?> hours</b></td>
						<td>
							<span>@</span>
							<?php echo number_format($order_time['OrderTime']['invoice_rate'], 2); ?>
							<?php #echo $this->Form->input('rate', array('class' => 'time_input', 'id' => 'rate', 'label' => false, 'div' => false)); ?>
						</td>
						<td><b><?php echo '$' . number_format($rt + $dt + $ot, 2); ?></b></td>
						<!-- <td class="hidden-xs hidden-sm">Comments go here from the technician...</td> -->
						<!-- 
						<td class="center">
						<?php 
						/*
						$checked = false;
						if($order_time['OrderTime']['invoiced']) {
							$checked = 'checked';
						}
						echo $this->Form->input('OrderTime.invoiced', array($checked, 'type'=>'checkbox', 'label'=> false, 'value' => $order_time['OrderTime']['invoiced'], 'name' => 'data[OrderTime]['. $order_time['OrderTime']['id'] . '][invoiced]')); */?>
						</td>
						 -->
					</tr>
					<?php 
					if(!empty($order_time['Message'])) : 
						foreach($order_time['Message'] as $message) : ?>
					<tr class="<?php echo $key; ?>_toggle_item detail-item">
						<td colspan="1"><span class="small">posted: <?php echo $this->Web->dt($message['created'], 'short_4'); ?></span></td>
						<td colspan="6"><?php echo $message['content']; ?></td>
					</tr>
					<?php endforeach;
					endif; ?>
					<?php 		
						$date_totals['name'] = $this->Web->humanName($order_time['Worker']);
						$date_totals['type'] = $order_time['Worker']['Group']['name'];
						$worker_totals['name'] = $this->Web->humanName($order_time['Worker']);
						$worker_totals['type'] = $order_time['Worker']['Group']['name'];
						$summary_worker = $this->Web->humanName($order_time['Worker']) . ' ' . '<span class="small light">(' . $order_time['Worker']['Group']['name'] . ')</span>';
					endforeach;
				endif; 
				$count = $count + 1;
				echo $this->element('invoice/invoice_time_worker_daily_summary_total', array('data' => $worker_totals, 'display_mode' => 'itemized', 'display' => $display, 'key' => $key, 'count' => $count, 'worker' => $summary_worker, 'permission_attr' => $permission_attr));
				$date_totals['name'] = null;
				$date_totals['type'] = null;
				echo $this->element('invoice/invoice_time_daily_summary_total', array('data' => $date_totals, 'display_mode' => 'summary', 'display' => $display, 'key' => $key, 'count' => $count, 'date' => $result['date'], 'permission_attr' => $permission_attr)); ?>
	</table>
	<br /><br />
	<?php endforeach; ?>
<?php else: ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array('no_items',))); ?>
	</div>
<?php endif; ?>