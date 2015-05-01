<?php
/**
 * @author Creationsite
 * @copyright 2008
 * @modified 2010
 * 
 */
#App::uses('Helper', 'View', 'Html', 'Time', 'Number', 'Form');
App::uses('Helper', 'View');
class FileUploadHelper extends AppHelper {
	var $helpers = array('Html', 'Time', 'Number', 'Form', 'Web');
	function constructFileContainer($document, $permissions) {
		$html = '';
		$type_class = '';
		if (strpos(strtolower($document['Document']['mime_type']), 'zip') !== false) {
			$type_class = 'doc-type-zip';
		} else if (strpos(strtolower($document['Document']['mime_type']), 'word') !== false) {
			$type_class = 'doc-type-word';
		} else if ((strpos(strtolower($document['Document']['mime_type']), 'pdf') !== false) || (strpos(strtolower($document['Document']['mime_type']), 'octet-stream') !== false)) {
			$type_class = 'doc-type-pdf';
		} else if (strpos(strtolower($document['Document']['mime_type']), 'excel') !== false) {
			$type_class = 'doc-type-excel';
		} else if (strpos(strtolower($document['Document']['mime_type']), 'powerpoint') !== false) {
			$type_class = 'doc-type-pp';
		} else if (strpos(strtolower($document['Document']['mime_type']), 'spreadsheet') !== false) {
			$type_class = 'doc-type-ss';
		} else if (strpos(strtolower($document['Document']['mime_type']), 'text') !== false) {
			$type_class = 'doc-type-text';
		} else if (strpos(strtolower($document['Document']['mime_type']), 'photoshop') !== false) {
			$type_class = 'doc-type-ps';
		}  
		
		$html = $html . '<div class="file-container grid" id="doc-id-' . $document['Document']['id'] . '">' . 
					  		'<div class="col-1of2">' . 
					        	'<div class="doc-info-container doc-type ' . $type_class . '">' .
									'<div class="name">' . 
						 				$this->Html->link($document['Document']['title'], array('controller' => 'documents', 'action' => 'download', $document['Document']['id']), array('id' => 'doc_name_' . $document['Document']['id'])) . 
									'</div>' . 
									'<div class="details">'; 
									if(array_key_exists('version', $document['Document']) && ($document['Document']['version'] != 1)) { 
										$html = $html . '<span class="version">(version ' . $document['Document']['version'] . ')</span> &bull; '; 
									}
										$html = $html . 'by ' . $this->Web->humanName($document['Creator'], 'first_initial') . ' &bull; ' . $this->Web->dt($document['Document']['created'], 'text_short') . ' &bull; ' . $this->Web->formatFileSize($document['Document']['bytes']);
										
										// Call Version Container element. 
										if(array_key_exists('version', $document['Document']) && ($document['Document']['version'] != 1)) { 
		$html = $html . 					$this->constructVersionContainer($document); //$this->element('document_version_container', array('parent_doc' => $document));
										} 
		$html = $html . '			</div>' .
								'</div>' .
							'</div>' . 
							'<div class="col-1of2">' . 
								'<div class="grid">' .
									'<div class="col-1of2">' . 
										'<div>&nbsp;' . 
											'<div class="progress progress-striped active" aria-valuenow="40" aria-valuemax="100" aria-valuemin="0" role="progressbar">' . 
												'<div class="progress-bar progress-bar-success" style="width:30%;"></div>' .
											'</div>' .
										'</div>' .
									'</div>' .
									'<div class="col-1of2 doc-action-container">' .
										'<div class="file-actions page-buttons right">' . 
											$this->Html->link('Delete', '#', array('id' => 'delete_file_' . $document['Document']['id'], 'class' => 'delete_file')); 
											if($permissions['enable_file_upload']) {
												$html = $html . $this->Html->link('New Version', '#', array('id' => 'new_version_'. $document['Document']['id'], 'class' => 'new_version')); 
											}
		$html = $html . 					$this->Html->link('Download', array('controller' => 'documents', 'action' => 'download', $document['Document']['id'])) . 
										'</div>' . 
										'<div class="file-loading-actions page-buttons">' .
											$this->Html->link('Cancel', array('#'), array('id' => '', 'class' => 'cancel bg-red')) . 
										'</div>' . 
									'</div>' . 
								'</div>' . 
							'</div>' . 
						'</div>';
		return $html;
	}
	
	function constructVersionContainer($parent_doc) {
		$html = '';
		$prev_version = $parent_doc['Document']['version'] - 1;
		$document = $parent_doc['Parent'][0];
		if($prev_version > 1) {
			$document['Document']['version'] = $prev_version;
		}
		$html = $html . '<div class="details version">' . 
							'<span class="version">(version ' . $prev_version . ')</span> &bull;' .  			
							$this->Html->link($document['Document']['title'], array('controller' => 'documents', 'action' => 'download', $document['Document']['id']), array('div'=>false)) . '&bull;' .  
							'by ' . $this->Web->humanName($document['Creator'], 'first_initial') . '  &bull; ' .  
							$this->Web->dt($document['Document']['created'], 'text_short') . '  &bull; ' .  
							$this->Web->formatFileSize($document['Document']['bytes']) . 
						'</div>';
		 // Call Version Container element.
		if(array_key_exists('version', $document['Document']) && ($document['Document']['version'] != 1)) {
			$html = $html . $this->constructVersionContainer($document);
		} 
		
		return $html;
	}
}
?>