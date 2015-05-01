<?php
if (empty($label)) {
	$label = 'Upload Image';
}
$callingModel = $model;
if ($model == 'Image' && !empty($this->data['Image'])) {
	// Images are never stored in the Image folder as they're always associated to an implementing model
	$callingModel = $this->data[$model]['model'];
}
?>
<fieldset class="image-upload">
	<?php if (!empty($this->data[$model]['name']) && !empty($this->data[$model]['id'])): ?>
		<?php echo $this->element('zoom_image', array('model' => $callingModel, 'imageName' => $this->data[$model], 'size' => array('medium', 'large'))); ?>
		<?php echo $this->Form->hidden($model.'.id', array('value' => $this->data[$model]['id'])); ?>
	<?php else: ?>
		<?php echo $this->Form->input($model.'.name', array(
			'type' => 'file',
			'label' => __($label, true),
			'error' => false,
		)); ?>
		<?php if (!empty($uploadPath)): ?>
			<?php echo $this->Form->hidden($model.'.__uploadPath', array('value' => $uploadPath)); ?>
		<?php endif; ?>
		<div class="error-left">
			<?php echo $this->Form->error($model.'.name', __('file_type', true)); ?>
			<?php echo $this->Form->error($model.'.name', __('uploaded', true)); ?>
			<?php echo $this->Form->error($model.'.name', __('max_size', true)); ?>
		</div>
	<?php endif; ?>
</fieldset>