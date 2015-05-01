<?php
$this->Html->script('jquery/jquery.jeditable', false);

echo $this->element('js'.DS.'editable', array(
	'editClass' => 'edit-status',
	'postback' => $this->Html->url(array('controller' => 'order_times', 'action' => 'edit_field')),
	'values' => $statuses_invoice,
	'output' => $statuses_invoice,
	'submitOnChange' => 1,
));
 ?>
<?php if (!empty($invoices)): ?>
	<table id="order_times-index_table" class="standard order nohover">
		<tr>
			<th class="hidden-xs hidden-sm">&nbsp;</th>
			<th><?php echo __('Created'); ?></th>
			<?php if($__browser_view_mode['view_device'] == 'computer') : ?>
				<th><?php echo __('Alerts'); ?></th>
				<th><?php echo __('Approved On'); ?></th>
			<?php endif; ?>
			<th><?php echo __('Status'); ?></th>
			<!-- 
			<th class="hidden-xs hidden-sm"><?php echo __('Labor (Hrs)'); ?></th>
			<th class="hidden-xs hidden-sm"><?php echo __('Labor ($)'); ?></th>
			<th class="hidden-xs hidden-sm"><?php echo __('Materials'); ?></th>
			 -->
			<?php /* if($__browser_view_mode['view_device'] == 'computer') : ?>
				<th class=""><?php echo __('% of Job Site'); ?></th>
			<?php endif; */?>
			<th><?php echo __('Total'); ?></th>
			<th class="actions">&nbsp;</th>
		</tr>
	<?php foreach ($invoices as $result): 
			/*
			$ratio['total_perc'] = 0;
			$ratio['total_folder'] = 0;
			if($result['Invoice']['total_est'] > 0) {
				$ratio['total'] = number_format(($result['Invoice']['total'] / $result['Invoice']['total_est'])*100, 2);
			
				$perc = 0;
				$folder = 'grey';
				if($ratio['total'] > 0) {
					$perc = intval(round($ratio['total'],-1));
				}
				if($perc >= 100 && $ratio['total'] < 100) {
					$perc = 90;
				}
				if($perc >= 100) {
					$perc = 100;
					$folder = 'green';
				}
				$ratio['total_perc'] = $perc;
				$ratio['total_folder'] = $folder;
			} */?>
			<tr id="invoice-<?php echo $result['Invoice']['id']; ?>">
				<td class="hidden-xs hidden-sm">&nbsp;</td>
				<td>
					<?php echo $this->Web->dt($result['Invoice']['created'], 'short_4') . ',&nbsp;&nbsp;' . $this->Web->dt($result['Invoice']['created'], null, '12hr');; ?><br />
					<span class="small light">By: <?php echo $this->Web->humanName($result['Creator'], 'first_initial'); ?></span>
				</td>
				<?php if($__browser_view_mode['view_device'] == 'computer') : ?>
					<td>
						<?php 
						if(array_key_exists('Alerts', $result) && !empty($result['Alerts'])) : 
							$clueTip = '<b>Alert: </b><br />';
							foreach($result['Alerts'] as $alert) { 
								$clueTip = $clueTip . ':: ' . $alert['title'] . '</br>';
							} ?>
							<div class="alert-dot-container cs-cluetip" data-content="<?php echo $clueTip; ?>">&nbsp;</div>
						<?php 
						else :
							echo '&nbsp;';			
						endif; ?>
					</td>
					<td>
						<?php if($result['Invoice']['status'] >= INVOICE_STATUS_APPROVED) : ?>
							<?php echo $this->Web->dt($result['Invoice']['date_approved'], 'text_short'); ?><br />
							<span class="small light">By: <?php echo $this->Web->humanName($result['ApprovedBy'], 'first_initial'); ?></span>
						<?php else : ?>
							&nbsp;
						<?php endif; ?>
					</td>
				<?php endif; ?>
				<td><?php echo $statuses_invoice[$result['Invoice']['status']]; ?></td>
				<?php /* if($__browser_view_mode['view_device'] == 'computer') : ?>
					<td class="pie-invoice-stats">
					 	<?php
					 	if(array_key_exists('total', $ratio)) {
					 		echo $ratio['total']. ' %'; 
					 		echo $this->Html->image('pie/'.$ratio['total_folder'].'/'.$ratio['total_perc'].'.png', array('class' => 'pie-invoice-stats'));
						} ?>
					</td>
				<?php endif; */?>
				<td>
					<?php echo '$'.number_format($result['Invoice']['total'],2); ?>
				</td>
				<td class="actions"><?php echo $this->Html->link(__('Details'), array('controller' => 'invoices', 'action' => 'edit', $result['Invoice']['id'], $result['Invoice']['order_id']), array('class' => 'row-click')); ?></td>
			</tr>
	<?php endforeach; ?>
	</table>
<?php else: ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array('no_items',))); ?>
	</div>
<?php endif; ?>