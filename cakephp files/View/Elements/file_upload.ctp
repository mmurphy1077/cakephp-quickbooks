<?php 
 /* -- Style Sheets --> */
echo $this->Html->css('file_upload');
 
/* -- jQuery File Upload Dependencies --> */
echo $this->Html->script('jquery/jquery_file_upload/jquery.iframe-transport', array('inline' => false));
echo $this->Html->script('jquery/jquery_file_upload/jquery.fileupload', array('inline' => false));
echo $this->element('js'.DS.'file_upload_init');

$permissions['enable_file_upload'] = 1;
?>   
<?php 
if (empty($group)) {
	$group = null;
}
?>
<?php echo $this->Form->create('Document', array('id' => 'upload', 'class' => 'standard', 'url' => array('controller' => 'documents', 'action' => 'ajax_upload'), 'enctype' => 'multipart/form-data')); ?>    
<?php 
	echo $this->Form->hidden('controller', array('value' => $options['controller']));
	echo $this->Form->hidden('model', array('value' => $options['model']));
	echo $this->Form->hidden('foreign_key', array('value' => $options['foreign_key']));
	echo $this->Form->hidden('category', array('value' => $options['category']));
	
	$id = 'documents_container';
	if(!empty($options['category'])) {
		$id = 'documents_container_'.$options['category'];
	}
?>      
<div class="">
	<fieldset id="<?php echo $id; ?>" class="top">
		<div class="upload-container row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<h4 id="file_upload_icon">
						<?php echo __('Files / Documents'); ?>
						<?php if($permissions['enable_file_upload']) : ?>
						<div id="file-upload-button" class="title-buttons right"><?php echo $this->Html->link('Upload File', array('#'), array('id' => 'button_upload_file_' . $options['category'], 'class' => 'button_upload_file')); ?></div>
						<?php endif; ?>
					</h4>
					<div class="version_container" id="version_container">
						<div class="version_title" id="version_title"></div>
						<?php echo $this->Form->hidden('parent_id', array('id' => 'version_target_id')); ?>
					</div>
					<div class="file_upload_container" id="file_upload_container_<?php echo $options['category']; ?>">
				            <div id="drop">
				                Drop files here... or click the 'browse' button.<br />
				                <div class="page-buttons">
				                	<?php echo $this->Html->link('Browse', array('#'), array('id' => 'file_browse')); ?>
				                </div>
				                <input id="file_input" type="file" name="upl" multiple />
				            </div>
				
				            <ul>
				                <!-- The file uploads will be shown here -->
				            </ul>
							<?php #echo $this->Form->submit(__('Continue &rarr;'), array('escape' => false)); ?>
					</div>
				</div>
			
		</div>
			<?php if(!empty($options['uploaded_files'])) : ?>
			<div id="uploaded-files-container" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<?php 	foreach($options['uploaded_files'][$options['model']] as $key=>$documents) : 
						$category = $key;
						if($key == 'top_level') {
							$category = '';
						}
						
						if(!empty($options['category']) && $options['category'] != $category) {
							$documents = null;
						} 
						
						if(empty($options['category']) && !empty($category)) : ?>
						<div class="group-container"><?php echo $category; ?></div>
			<?php 		endif;
						if(!empty($documents)) : 
							foreach($documents as $document) : 
								// The following is a call to a helper that builds the individual Document/file container objects.
								// The helper returns the html... This was used in order so the same code could be used when updating files in ajax.
								echo $this->FileUpload->constructFileContainer($document, $permissions);
				 			endforeach; ?>
			<?php 		endif;	?>
			<?php 	endforeach; ?>
			</div>
			<?php endif; ?>
		
	</fieldset>
</div>
<?php echo $this->Form->end(); ?>