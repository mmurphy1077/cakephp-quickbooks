<?php 
if(array_key_exists('name', $quote['Quote']) && !empty($quote['Quote']['name'])) {
	$pageTitle = Configure::read('Nomenclature.Quote').' &mdash;&nbsp;' . $quote['Quote']['name'];
	if(!empty($quote['Quote']['subname'])) {
		$pageTitle = $pageTitle . ' - ' . $quote['Quote']['subname'];
	}
} else {
	if($quote['Quote']['status'] == QUOTE_STATUS_UNSAVED) {
		$pageTitle = 'New ' . Configure::read('Nomenclature.Quote');
	}else if(!array_key_exists('name', $quote['Quote']) || empty($quote['Quote']['name']) || ($quote['Quote']['name'] == 'Required')) {
		$pageTitle = Configure::read('Nomenclature.Quote').' &mdash;&nbsp;' . '<span class="red">Name Required</span>';
	}
}

if(!array_key_exists('sid', $quote['Quote']) || empty($quote['Quote']['sid'])) {
	$pageTitleSub = '';
} else {
	$pageTitleSub = '(sid #: ' . $quote['Quote']['sid'] . ')';
}
$pageTitleAddress = '';
if(array_key_exists('Address', $quote) && !empty($quote['Address']['line1'])) {
	$pageTitleAddress = $this->Web->address($quote['Address'], false, ', ', false);
}
if(array_key_exists('Customer', $quote) && !empty($quote['Customer']['name'])) {
	if(!empty($pageTitleAddress)) {
		$pageTitleAddress =  ' | ' . $pageTitleAddress;
	}
	$pageTitleAddress =  $this->Html->link($quote['Customer']['name'], array('controller' => 'customers', 'action' => 'view', $quote['Customer']['id'])) .  $pageTitleAddress;
}
$this->assign('pageTitle', $pageTitle);
$this->assign('pageTitleSub', $pageTitleSub);
$this->assign('pageTitleAddress', $pageTitleAddress);
?>