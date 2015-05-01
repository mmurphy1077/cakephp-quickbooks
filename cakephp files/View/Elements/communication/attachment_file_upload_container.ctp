<h2>Uploaded Docs & Files</h2>
<div id="attachment-docs-container" class="clear grid">
	<?php if(!empty($docs)) : ?>
	<?php 	foreach($docs as $type=>$docs_by_type) : ?>
	<?php 		if(!empty($docs_by_type)) : ?>
	<?php 			foreach($docs_by_type as $type=>$doc) : ?>
	<div class="col-1of1 clear">
		<?php 
		$type_class = '';
		if (strpos(strtolower($doc['Document']['mime_type']), 'zip') !== false) {
			$type_class = 'doc-type-zip';
		} else if (strpos(strtolower($doc['Document']['mime_type']), 'word') !== false) {
			$type_class = 'doc-type-word';
		} else if ((strpos(strtolower($doc['Document']['mime_type']), 'pdf') !== false) || (strpos(strtolower($doc['Document']['mime_type']), 'octet-stream') !== false)) {
			$type_class = 'doc-type-pdf';
		} else if (strpos(strtolower($doc['Document']['mime_type']), 'excel') !== false) {
			$type_class = 'doc-type-excel';
		} else if (strpos(strtolower($doc['Document']['mime_type']), 'powerpoint') !== false) {
			$type_class = 'doc-type-pp';
		} else if (strpos(strtolower($doc['Document']['mime_type']), 'spreadsheet') !== false) {
			$type_class = 'doc-type-ss';
		} else if (strpos(strtolower($doc['Document']['mime_type']), 'text') !== false) {
			$type_class = 'doc-type-text';
		} else if (strpos(strtolower($doc['Document']['mime_type']), 'photoshop') !== false) {
			$type_class = 'doc-type-ps';
		}
		$title = $doc['Document']['title'];
		$label= $this->Html->link($title, array('controller' => 'documents', 'action' => 'download', $doc['Document']['id']), array('class' => 'doc-type ' . $type_class));	
		echo $this->Form->input('doc_check', array('type'=>'checkbox', 'label' => $label, 'id' => 'doc_check_' . $doc['Document']['id'], 'class' => 'doc_check', 'name' => 'data[AttachmentDocument][' . $doc['Document']['id'] . '][id]')); ?>	
	</div>
	<?php 			endforeach; ?>
	<?php		endif; ?>
	<?php 	endforeach; ?>
	<?php endif; ?>
</div>