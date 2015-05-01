<?php
echo $this->Html->script('creationsite/quote', array('inline' => false));
$this->extend('/Templates/wide');

$pageTitle = '';
if(!empty($this->request->data['User'])) {
	$pageTitle = 'User: ' . $this->request->data['User']['name_first'] . ' ' . $this->request->data['User']['name_last'];
}
$this->assign('pageTitle', $pageTitle);
$permissions = $this->Permission->getPermissions($__permissions);
?>
<div class="clear">
	<h4>Activity Log</h4>
	<?php echo $this->element('activity_log_index', array('result' => $results, 'header' => array('day' => 'Today', 'week' => 'Last Week', 'month' => 'Past 30 Days'))); ?>
</div>
<?php $this->start('buttons'); ?>
	<?php echo $this->element('nav_context'); ?>
<?php $this->end(); ?>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('header_tabs'); ?>
	<?php echo $this->element('tabs_user', array('user' => $this->data, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>