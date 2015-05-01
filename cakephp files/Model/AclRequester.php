<?php
class AclRequester extends AppModel {
	
	public $name = 'AclRequester';
	public $useTable = 'aros';
	public $order = 'lft ASC';
	public $actsAs = array(
		'Tree',
		'AclSystem' => array(
			'type' => 'Aro',
		),
	);
	public $hasMany = array(
		'AclPermission' => array(
			'dependent' => true,
		),
	);
	public $contain = array(
		'default' => array(
			'AclPermission' => array(
				'AclRequester',
				'AclControlled',
			),
		),
	);

}
?>