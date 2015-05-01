<?php $this->start('tableHeaders'); ?>
	<th>&nbsp;</th>
	<th><?php echo $this->Paginator->sort('Customer.name', __('Customer')); ?></th>
	<th><?php echo __('SID'); ?></th>
	<th><?php echo __('Job'); ?></th>
	<th><?php echo __('Amount'); ?></th>
	<th><?php echo __('Approved On'); ?></th>
	<th>&nbsp;</th>
<?php $this->end(); ?>
<?php foreach ($results as $result): ?>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo $result['Order']['Customer']['name']; ?></td>
		<td><?php echo $result['Order']['sid']; ?></td>
		<td>
			<?php echo $this->Html->link($result['Order']['name'], array('controller' => 'invoices', 'action' => 'index_order', $result['Order']['id'])); ?>
			<?php if(array_key_exists('Address', $result) && !empty($result['Address'])) : ?>
				<br />
				<span class="small light"><?php echo $result['Address']['line1']; ?></span>
			<?php endif; ?>
		</td>
		<td><?php echo '$' . number_format($result['Invoice']['total'], 2); ?></td>
		<td>
			<?php echo $this->Web->dt($result['Invoice']['date_approved'], 'text_short'); ?>
			<br />
			<span class="small light">By: <?php echo $this->Web->humanName($result['ApprovedBy'], 'first_initial'); ?></span>
		</td>
		<td class="actions"><?php echo $this->Html->link(__('Details'), array('controller' => 'invoices', 'action' => 'view', $result['Invoice']['id'])); ?></td>
	</tr>
<?php endforeach; ?>