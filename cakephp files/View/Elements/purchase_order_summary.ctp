<?php 
if($permissions['enable_save']) { 
	echo $this->Html->link('add PO', array('#'), array('id' => 'add_po', 'class' => 'toggle_display_button right', 'escape'=>false)); 
} ?>
<table class="standard order">
	<tr class="action">
		<th colspan="4">&nbsp;<?php echo 'current ' . __(Inflector::pluralize(Configure::read('Nomenclature.PurchaseOrder'))); ?></th>
	</tr>
	<?php if (empty($order_pos)): ?>
		<td colspan="4"><div class="clear flush-left"><?php echo $this->element('info', array('content' => array('no_items',))); ?></div></td>
	<?php else: ?>
		<?php foreach ($order_pos as $i => $order_po): ?>
			<tr>
				<td>
					<div class="small"><b><?php echo $this->Number->currency($order_po['PurchaseOrder']['total']); ?></b></div>
				</td>
				<td>
					<b><?php echo $order_po['PurchaseOrder']['vendor_name']; ?></b>&nbsp;&nbsp;<div class="small inline">(PO #: <?php echo $order_po['PurchaseOrder']['po_number']; ?>)</div><br />
					<div class="light">
						<?php echo nl2br($this->Web->excerpt($order_po['PurchaseOrder']['description'], 70)); ?>
					</div>
				</td>
				<td class="action">
					<?php if ($permissions['can_delete_pos'] == 1): ?>
						<?php echo $this->Html->link($this->Html->image('icon-close.png', array('class' => 'icon-close order-line-item')), array('controller' => 'orders', 'action' => 'delete_purchase_order', $order_po['PurchaseOrder']['id'], $order_po['PurchaseOrder']['order_id']), array('class' => 'right', 'escape' => false), __('delete_confirm')); ?>
					<?php endif; ?>
					<?php if ($permissions['can_create_pos'] == 1): ?>
						<?php echo $this->Html->link('edit&nbsp;&nbsp;', array('controller' => 'orders', 'action' => 'purchasing', $order_po['PurchaseOrder']['order_id'], $order_po['PurchaseOrder']['id']), array('class' => 'edit-po right', 'id' => 'edit-po-'.$order_po['PurchaseOrder']['id'], 'escape' => false)); ?>&nbsp;
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>
</table>