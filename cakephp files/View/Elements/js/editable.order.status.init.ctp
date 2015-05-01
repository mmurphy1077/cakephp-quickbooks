<?php 
$controller = 'quotes';
if(!isset($model)) {
	$model == 'quote';
}
if($model == 'order') {
	$controller = 'orders';
}
/*
 * This page requires two editable types because the values displayed are different depending on the task type.
 */
echo $this->element('js'.DS.'editable', array(
		'editClass' => 'edit_task_status_'.$model,
		'postback' => $this->Html->url(array('controller' => $controller, 'action' => 'edit_field')),
		'values' => $taskStatuses,
		'output' => $taskStatuses,
		'submitOnChange' => 1,
));
echo $this->element('js'.DS.'editable', array(
		'editClass' => 'edit_approval_status_'.$model,
		'postback' => $this->Html->url(array('controller' => $controller, 'action' => 'edit_field')),
		'values' => $approvalStatuses,
		'output' => $approvalStatuses,
		'submitOnChange' => 1,
));
echo $this->element('js'.DS.'editable', array(
		'editClass' => 'edit_status_'.$model,
		'postback' => $this->Html->url(array('controller' => $controller, 'action' => 'edit_field')),
		'values' => $modelStatuses,
		'output' => $modelStatuses,
		'submitOnChange' => 1,
));
?>