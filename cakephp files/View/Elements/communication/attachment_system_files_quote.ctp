<?php ?>
<h2>Quote documents</h2>
<div id="attachment-docs-container" class="clear grid">
	<div class="col-1of1 clear">
		<?php 
		$img_class = 'icon-file-pdf';
		$label= $this->Html->link(Configure::read('Nomenclature.Proposal').'_SID#'.$quote['Quote']['sid'].'_' . date('Y-m-d') . '.pdf', array('controller' => 'quotes', 'action' => 'print_pdf', $quote['Quote']['id']), array('div' => false, 'class' => $img_class)); 
		echo $this->Form->input('AttachmentSystemDocs.attach_quote', array('label' => $label, 'type' => 'checkbox')); 
		#echo $this->Form->input('attach_cover_sheet', array('label' => false, 'div' => false, 'checked' => false)) .  __('Include').' '.__('Cover Sheet'); ?>
	</div>
</div>