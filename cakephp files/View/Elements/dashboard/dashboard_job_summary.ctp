<div class="widget center">
	<br />
	<?php #echo $this->Html->link(__('View More'), array('controller' => 'orders', 'action' => 'index'), array('class' => 'button medium blue right')); ?>
	<h3 class="left"><?php echo __(Inflector::pluralize(Configure::read('Nomenclature.Order'))); ?></h3>
	<?php  
	if(!empty($orders)) : ?>
	<table class="standard">
		<tr>
			<th>&nbsp;</th>
			<th colspan="2"><?php echo __('Order Name'); ?></th>
			<th><?php echo __('Customer Name'); ?></th>
			<th><?php echo __('Status'); ?></th>
			<th><?php #echo __('Comments'); ?>&nbsp;</th>
		</tr>
		<?php foreach($orders as $order) : 
		$clueTip = number_format($order['Order']['total_hours_logged'],2) . ' hours logged of a total ' . number_format($order['Order']['total_hours_scheduled'],2) . ' scheduled hours.';
		?>
		<tr>
			<td><?php echo $this->Html->link($this->Web->displayPieImage($order['Order']), array('controller' => 'order_times', 'action' => 'add', $order['Order']['order_id']), array('id' => 'cs-cluetip-'.$order['Order']['order_id'], 'class' => 'cs-cluetip', 'data-content' => $clueTip, 'escape' => false)); ?></td>
			<td><?php 
				if(empty($order['Order']['Alerts'])) :
					echo '&nbsp;';		
				else :
					$clueTip = '<b>Alert: </b><br />';
					foreach($order['Order']['Alerts'] as $alert) {
						$clueTip = $clueTip . ':: ' . $alert['title'] . '</br>';
					} ?>
					<div class="alert-dot-container cs-cluetip" data-content="<?php echo $clueTip; ?>">&nbsp;</div>
				<?php 			
				endif; ?>
			</td>
			<td><?php echo $this->Html->link($order['Order']['name'], array('controller' => 'orders', 'action' => 'view', $order['Order']['order_id'])); ?></td>
			<td><?php echo $this->Html->link($order['Order']['customer_name'], array('controller' => 'customers', 'action' => 'view', $order['Order']['customer_id'])); ?></td>
			<td class="small"><?php echo $statuses_order[$order['Order']['status']]?></td>
			<td class="center">
				 &nbsp;
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
	<?php else : ?>
	
	<?php endif; ?>
</div>