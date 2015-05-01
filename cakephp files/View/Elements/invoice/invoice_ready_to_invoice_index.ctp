<?php $this->start('tableHeaders'); ?>
	<th>&nbsp;</th>
	<th><?php echo __(Configure::read('Nomenclature.Order')); ?></th>
	<th><?php echo  __('Customer'); ?></th>
	<th><?php echo __('Status'); ?></th>
	<th colspan="2"><?php echo __('Unbilled Stats:'); ?>&nbsp;&nbsp;<?php echo __('Labor'); ?></th>
	<th><?php echo __('Materials'); ?></th>
	<th><?php echo __('Total'); ?></th>
	<th>&nbsp;</th>
<?php $this->end(); ?>
<?php foreach ($results as $result): 
		$labor_cost = 0;
		$labor_hours = 0;
		$material_cost = 0;
		$total = 0;
		if(!empty($result['OrderTime'])) {
			foreach($result['OrderTime'] as $time) {
				$t = $time['time_total'];
				$r = $time['rate'];
				$labor_hours = $labor_hours + $t;
				$labor_cost = $labor_cost + ($t * $r);
				$total = $total + ($t * $r);
			}
		}
		if(!empty($result['OrderMaterial'])) {
			foreach($result['OrderMaterial'] as $om) {
				if(!empty($om['OrderMaterialItem'])) {
					foreach($om['OrderMaterialItem'] as $omi) {
						$material_cost = $material_cost + ($omi['qty'] * $omi['price_per_unit']);
						$total = $total + ($omi['qty'] * $omi['price_per_unit']);
					}
				}
			}
		}
?>
	<tr>
		<td>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($result['Order']['name'], array('controller' => 'invoices', 'action' => 'index_order', $result['Order']['id'])); ?>
			<?php if(array_key_exists('Address', $result)) : ?>
				<br />
				<span class="short light"><?php echo $result['Address']['line1']; ?></span>
			<?php endif; ?>
		</td>
		<td><?php echo $result['Order']['customer_name']; ?></td>
		<td><?php echo $order_statuses[$result['Order']['status']]; ?></td>
		<td>&nbsp;</td>
		<td class="center"><?php echo '$' . number_format($labor_cost, 2); ?>&nbsp;&nbsp;&nbsp;<span class="light">(<?php echo number_format($labor_hours, 2); ?> hrs)</span></td>
		<td><?php echo '$' . number_format($material_cost, 2); ?></td>
		<td><b><?php echo '$' . number_format($total ,2); ?></b></td>
		<td class="actions"><?php echo $this->Html->link(__('Create Invoice'), array('controller' => 'invoices', 'action' => 'add', $result['Order']['id'])); ?></td>
	</tr>
<?php endforeach; ?>