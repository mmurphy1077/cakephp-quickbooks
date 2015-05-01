<?php 
$quoteStatuses = $__statusQuotes;
unset($quoteStatuses[QUOTE_STATUS_SOLD]);
?>
<div class="widget center">
	<?php echo $this->Html->link(__('View More'), array('controller' => 'quotes', 'action' => 'index'), array('class' => 'button medium blue right')); ?>
	<h3 class="left"><?php echo __('Quotes'); ?></h3>
	<?php if(empty($quotes)) : ?>
	<div class="clear">
		<?php echo $this->element('info', array('content' => array(
			'no_items',
		))); ?>
	</div>
	<?php else : ?>
	<table class="standard">
		<tr>
			<th>&nbsp;</th>
			<th><?php echo __('Created'); ?></th>
			<th><?php echo __('Alerts'); ?></th>
			<th><?php echo __('Job Name'); ?></th>
			<th><?php echo __('Address / Jobsite'); ?></th>
			<th><?php echo __('Status'); ?></th>
			<!-- <th><?php #echo $this->Paginator->sort('Quote.created', __('Req Date')); ?></th> -->
			<!-- <th>&nbsp;</th> -->
			<th>&nbsp;</th>
		</tr>
		<?php
		foreach ($quotes as $quote) : 
			/**
			 * Permissions
			 * Permissions for the dashboard cross more than one module.  Because of this we need to look at the $__permissions variable
			 * To update status the user needs _update privledges or _view_all_quotes privledges
			 */
			$can_update_status = false;
			if(($__permissions['Quote']['_update'] == 1 && $__user['User']['id'] == $quote['Quote']['project_manager_id']) || ($__permissions['Quote']['_view_all_quotes'] == 1)) {
				$can_update_status = true;
			}
		?>
		<?php 	echo $this->element('index_line_quote', array('result' => $quote, 'can_update_status' => $can_update_status, 'quoteStatuses' => $quoteStatuses)); ?>
		<?php endforeach; ?>
	</table>
	<?php endif; ?>
</div>