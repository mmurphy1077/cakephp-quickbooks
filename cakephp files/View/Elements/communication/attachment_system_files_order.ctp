<?php 
#debug($order);
?>
<h2>Select a Order document</h2>
<div id="attachment-docs-container" class="clear grid">
	<?php if(!empty($order['Invoice'])) : ?>
	<?php 	foreach($order['Invoice'] as $invoice) : ?>
	<?php 		#if(!empty($invoice['date_approved'])) : ?>
	<div class="col-1of1 clear">
		<?php 
		$img_class = 'icon-file-pdf';
		$checked = false;
		if(!empty($selected_invoices) && is_array($selected_invoices) && array_key_exists($invoice['id'], $selected_invoices)) {
			$checked = true;
		}
		$label= $this->Html->link('Invoice_'. date('Y_m_d', strtotime($invoice['date_invoiced'])) . '.pdf', array('controller' => 'invoices', 'action' => 'print_pdf', $invoice['id']), array('div' => false, 'class' => $img_class)); 
		echo $this->Form->input('AttachmentSystemDocs.attach_invoice', array('label' => $label, 'value' => $invoice['id'], 'checked' => $checked, 'type' => 'checkbox', 'name' => 'data[AttachmentSystemDocs][attach_invoice][' . $invoice['id'] . ']')); 
		?>
	</div>
	<?php 		#endif; ?>
	<?php 	endforeach;?>
	<?php endif; ?>
</div>