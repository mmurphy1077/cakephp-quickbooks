<?php
$this->Html->script('jquery/jquery.jeditable', false);
?>
<?php if (!empty($data)): ?>
	<?php foreach ($data as $key=>$result):  ?>
		<table id="invoice-times-index-table" class="standard order nohover">
			<tr>
				<th colspan="5"><?php echo $this->Web->humanName($result['Worker']) . ' ' . '<span class="small light">(' . $result['Group']['name'] . ')</span>'; ?></th>
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
			<tr class="<?php echo $key; ?>_toggle_item detail-item">
				<th>Date</th>
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
			$date_totals['time'] = 0;
			$date_totals['cost'] = 0;
			$date_totals['name'] = $this->Web->humanName($result['Worker']);
			$date_totals['type'] = $result['Group']['name'];
			$worker_totals['time'] = 0;
			$worker_totals['cost'] = 0;
			$worker_totals['name'] = $this->Web->humanName($result['Worker']);
			$worker_totals['type'] = $result['Group']['name'];
			$count = 0;
			$date_session = null; 
			if(!empty($result['OrderTime'])) : 
				foreach($result['OrderTime'] as $order_time) : 
					$count = $count + 1;
					$display_date = "";
					if($date_session != $order_time['OrderTime']['date_session']) {
						$display_date =  $this->Web->dt($order_time['OrderTime']['date_session'], 'short_4');
							
						// Determine if a itemized daily summary is required
						// This code will be executed when there is more than one work day a worker has logged time for the same day.
						// If there is only one work day (or the last work day in the data set), the element will be called at the end of the loop.
						if(!empty($date_session)) {
							echo $this->element('invoice/invoice_time_daily_summary_total', array('data' => $date_totals, 'display_mode' => 'itemized', 'display' => $display, 'key' => $key, 'count' => $count, 'date' => $this->Web->dt($order_time['OrderTime']['date_session'], 'text_short'), 'permission_attr' => $permission_attr));
						}
						// Reset
						$date_totals['time'] = 0;
						$date_totals['cost'] = 0;
					}
					$rt = $order_time['OrderTime']['time_reg'] * $order_time['OrderTime']['invoice_rate'];
					$ot = ($order_time['OrderTime']['time_ot'] * $order_time['OrderTime']['invoice_rate']) * 1.5;
					$dt = ($order_time['OrderTime']['time_dt'] * $order_time['OrderTime']['invoice_rate']) * 2;
					
					$date_totals['time'] = $date_totals['time'] + $order_time['OrderTime']['invoice_time_total'];
					$date_totals['cost'] = $date_totals['cost'] + ($rt + $dt + $ot);
					$worker_totals['time'] = $worker_totals['time'] + $order_time['OrderTime']['invoice_time_total'];
					$worker_totals['cost'] = $worker_totals['cost'] + ($rt + $dt + $ot);
					$date_session = $order_time['OrderTime']['date_session'];
					?>
					<tr class="<?php echo $key; ?>_toggle_item detail-item">
						<td><?php echo $display_date; ?></td>
						<td><?php echo number_format($order_time['OrderTime']['invoice_time_reg'], 2); ?></td>
						<td><?php echo number_format($order_time['OrderTime']['invoice_time_ot'], 2); ?></td>
						<td><?php echo number_format($order_time['OrderTime']['invoice_time_dt'], 2); ?></td>
						<td><b><?php echo number_format($order_time['OrderTime']['invoice_time_total'], 2); ?> hours</b></td>
						<td>
							<span>@</span>
							<?php echo number_format($order_time['OrderTime']['invoice_rate'], 2); ?>
							<?php #echo $this->Form->input('rate', array('class' => 'time_input', 'id' => 'rate', 'label' => false, 'div' => false)); ?>
						</td>
						<td><b><?php echo '$' . number_format(($rt + $dt + $ot), 2); ?></b></td>
						<!-- <td class="hidden-xs hidden-sm">Comments go here from the technician...</td> -->
						<!--<td class="center">-->
						<?php 
						$checked = false;
						if($order_time['OrderTime']['invoiced']) {
							$checked = 'checked';
						}
						#echo $this->Form->input('OrderTime.invoiced', array($checked, 'type'=>'checkbox', 'label'=> false, 'value' => $order_time['OrderTime']['invoiced'], 'name' => 'data[OrderTime]['. $order_time['OrderTime']['id'] . '][invoiced]')); ?>
						<!--</td>-->
					</tr>
					<?php 
					if(!empty($order_time['Message'])) : 
						foreach($order_time['Message'] as $message) : ?>
					<tr class="<?php echo $key; ?>_toggle_item detail-item">
						<td colspan="1"><span class="small">posted: <?php echo $this->Web->dt($message['created'], 'short_4'); ?></span></td>
						<td colspan="6"><?php echo $message['content']; ?></td>
					</tr>
					<?php 
						endforeach;
					endif; ?>
				<?php 		
						$date_totals['date'] = $this->Web->dt($order_time['OrderTime']['date_session'], 'short_4');
						$date_totals['date_short'] = date('m/d', strtotime($order_time['OrderTime']['date_session']));
						$worker_totals['date'] = $this->Web->dt($order_time['OrderTime']['date_session'], 'short_4');
						$worker_totals['date_short'] = date('m/d', strtotime($order_time['OrderTime']['date_session']));
					endforeach;
				endif; 
				$count = $count + 1;
				echo $this->element('invoice/invoice_time_daily_summary_total', array('data' => $date_totals, 'display_mode' => 'itemized', 'display' => $display, 'key' => $key, 'count' => $count, 'date' => $this->Web->dt($order_time['OrderTime']['date_session'], 'text_short'), 'permission_attr' => $permission_attr)); 
				$worker_totals['date'] = null;
				echo $this->element('invoice/invoice_time_worker_daily_summary_total', array('data' => $worker_totals, 'display_mode' => 'summary', 'display' => $display, 'key' => $key, 'count' => $count, 'worker' => $this->Web->humanName($result['Worker']) . ' <span class="small light">(' . $result['Group']['name'] . ')</span>', 'permission_attr' => $permission_attr)); ?>
	</table>
	<br /><br />
	<?php endforeach; ?>
<?php else: ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array('no_items',))); ?>
	</div>
<?php endif; ?>