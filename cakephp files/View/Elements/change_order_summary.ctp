<?php 
if($permissions['enable_save']) { 
	echo $this->Html->link('add Change Order', array('#'), array('id' => 'add_co', 'class' => 'toggle_display_button right', 'escape'=>false)); 
} ?>
<table class="standard order">
	<tr class="action">
		<th colspan="4">&nbsp;<?php echo 'current ' . __(Inflector::pluralize(Configure::read('Nomenclature.ChangeOrder'))); ?></th>
	</tr>
	<?php if (empty($change_orders)): ?>
		<td colspan="4"><div class="clear flush-left"><?php echo $this->element('info', array('content' => array('no_items',))); ?></div></td>
	<?php else: ?>
		<?php foreach ($change_orders as $i => $change_order): ?>
			<tr>
				<td>
					<div class="small"><b><?php echo $this->Number->currency($change_order['ChangeOrderRequest']['price']); ?></b></div>
				</td>
				<td>
					<div class="">
						<?php echo nl2br($this->Web->excerpt($change_order['ChangeOrderRequest']['description'], 70)); ?>
					</div>
				</td>
				<td class="action">
					<?php if ($permissions['can_delete_cor'] == 1): ?>
						<?php echo $this->Html->link($this->Html->image('icon-close.png', array('class' => 'icon-close order-line-item')), array('controller' => 'change_order_requests', 'action' => 'delete', $change_order['ChangeOrderRequest']['id'], $change_order['ChangeOrderRequest']['order_id']), array('class' => 'right', 'escape' => false), __('delete_confirm')); ?>
					<?php endif; ?>
					<?php if ($permissions['can_update_cor'] == 1): ?>
						<?php echo $this->Html->link('edit&nbsp;&nbsp;', array('controller' => 'change_order_requests', 'action' => 'add', $change_order['ChangeOrderRequest']['order_id'], $change_order['ChangeOrderRequest']['id']), array('class' => 'edit-po right', 'id' => 'edit-po-'.$change_order['ChangeOrderRequest']['id'], 'escape' => false)); ?>&nbsp;
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
</table>