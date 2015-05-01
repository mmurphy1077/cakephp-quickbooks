<?php $this->start('tableHeaders'); ?>
	<th>&nbsp;</th>
	<th><?php echo $this->Paginator->sort('Invoice.created', __('Created')); ?></th>
	<th><?php echo $this->Paginator->sort('Invoice.customer_name', __('Customer')); ?></th>
	<th><?php echo __('Job'); ?></th>
	<th><?php echo __('Status'); ?></th>
	<th><?php echo __('Amount'); ?></th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
<?php $this->end(); ?>
<?php foreach ($results as $result): 
		$submittedBy = null;
		if(!empty($result['SubmittedBy']['id'])) {
			$submittedBy = '<span class="small light">By: ' . $this->Web->humanName($result['SubmittedBy'], 'first_initial') . '</span>';
		}
		$approvedBy = null;
		if(!empty($result['ApprovedBy']['id'])) {
			$approvedBy = '<span class="small light">By: ' . $this->Web->humanName($result['ApprovedBy'], 'first_initial') . '</span>';
		} ?>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo $this->Web->dt($result['Invoice']['created'], 'short_4'); ?></td>
		<td><?php echo $result['Invoice']['customer_name']; ?></td>
		<td>
			<?php echo $this->Html->link($result['Order']['name'], array('controller' => 'invoices', 'action' => 'index_order', $result['Order']['id'])); ?>
			<?php if(array_key_exists('Address', $result['Order']) && !empty($result['Order']['Address'])) : ?>
				<br />
				<span class="small light"><?php echo $result['Order']['Address']['line1']; ?></span>
			<?php endif; ?>
		</td>
		<td><?php echo $statuses_invoice[$result['Invoice']['status']]; ?></td>
		<td><?php echo '$' . number_format($result['Invoice']['total'], 2); ?></td>
		<td>
			Submitted: <?php echo $this->Web->dt($result['Invoice']['date_submitted'], 'text_short'); ?>&nbsp;<?php echo $submittedBy; ?><br />
			Approved: <?php echo $this->Web->dt($result['Invoice']['date_approved'], 'text_short'); ?>&nbsp;<?php echo $approvedBy; ?><br />
			Billed: 
			<?php if(array_key_exists('date_billed', $result['Invoice'])) {
				echo $this->Web->dt($result['Invoice']['date_billed'], 'text_short'); 
			} ?><br />
			Paid: 
			<?php if(array_key_exists('date_paid', $result['Invoice'])) {
				echo $this->Web->dt($result['Invoice']['date_paid'], 'text_short'); 
			} ?>
		</td>
		<td class="actions"><?php echo $this->Html->link(__('Details'), array('controller' => 'invoices', 'action' => 'view', $result['Invoice']['id'])); ?></td>
	</tr>
<?php endforeach; ?>