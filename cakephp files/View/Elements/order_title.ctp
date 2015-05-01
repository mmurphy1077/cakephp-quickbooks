<?php 
$pageTitle = '';
$address = null;
if($__browser_view_mode['browser_view_mode'] == 'standard') {
	#$pageTitle = Configure::read('Nomenclature.Order').' &mdash;&nbsp;';
	if(!empty($order['Address'])) {
		$address = $this->Web->address($order['Address'], false, ', ', false);
	}
}
if(!array_key_exists('name', $order['Order']) || empty($order['Order']['name']) || ($order['Order']['name'] == 'Required')) {
	$pageTitle =  $pageTitle . '<span class="red">Name Required</span>';
} else {
	$pageTitle = $pageTitle . $order['Order']['name'];
}
$pageTitleSub = '';
if(array_key_exists('sid', $order['Order']) || empty($order['Order']['sid'])) {
	$pageTitleSub = '(sid #: ' . $order['Order']['sid'] . ')';
}
$pageTitleAddress = '';
if(array_key_exists('Address', $order) && !empty($order['Address']['line1'])) {
	$pageTitleAddress = $this->Web->address($order['Address'], false, ', ', false);
}
if(array_key_exists('Customer', $order) && !empty($order['Customer']['name'])) {
	if(!empty($pageTitleAddress)) {
		$pageTitleAddress =  ' | ' . $pageTitleAddress;
	}
	if($__browser_view_mode['browser_view_mode'] == 'standard') {
		$pageTitleAddress =  $this->Html->link($order['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $order['Customer']['id'])) .  $pageTitleAddress;
	} else {
		$pageTitleAddress =  $order['Customer']['name'] .  $pageTitleAddress;
	}
}
$this->assign('pageTitle', $pageTitle);
$this->assign('pageTitleSub', $pageTitleSub);
$this->assign('pageTitleAddress', $pageTitleAddress);
?>