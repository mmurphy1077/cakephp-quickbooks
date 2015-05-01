<?php 
$pageTitle = '';
if ($mode == 'add') {
	$pageTitle = 'Add '.Configure::read('Nomenclature.Contact');
} else {
	$pageTitle = Configure::read('Nomenclature.Contact');
	if (!empty($this->request->data['Contact']['name_forward'])) {
		$pageTitle = Configure::read('Nomenclature.Contact') . ' &mdash; '. $data['Contact']['name_forward'];
	}
}
$this->assign('pageTitle', $pageTitle); ?>