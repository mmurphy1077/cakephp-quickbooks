<?php
echo $this->Html->script('creationsite/quote', array('inline' => false));

$this->extend('/Templates/wide');
$pageTitle = '';
if(!empty($this->request->data['User'])) {
	$pageTitle = 'User: ' . $this->request->data['User']['name_first'] . ' ' . $this->request->data['User']['name_last'];
}
$this->assign('pageTitle', $pageTitle);

/*
 * PERMISSIONS
 */
$permissions = $this->Permission->getPermissions($__permissions);
if(empty($owner)) {
	$owner = false;
}
/* END PERMISSIONS  */
?>
<div class="clear">
<?php if(!empty($permissions['enable_file_upload'])) : ?>
	<?php 
	$options = array(
			'controller' => 'users',
			'model' => 'User',
			'foreign_key' => $this->data['User']['id'],
			'category' => '',
			'uploaded_files' => $file_uploads,
	);
	echo $this->element('file_upload', array('options' => $options, 'permissions' => $permissions)); ?>
<?php endif; ?>
</div>
<?php $this->start('buttons'); ?>
	<?php echo $this->element('nav_context'); ?>
<?php $this->end(); ?>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('header_tabs'); ?>
	<?php echo $this->element('tabs_user', array('user' => $this->data, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>