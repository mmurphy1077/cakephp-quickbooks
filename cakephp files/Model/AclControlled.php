<?php
class AclControlled extends AppModel {
	
	public $name = 'AclControlled';
	public $useTable = 'acos';
	public $order = 'AclControlled.lft ASC';
	public $actsAs = array(
		'Tree',
		'AclSystem' => array(
			'type' => 'Aco',
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
	
	public function getPublicId() {
		return $this->field('id', array('AclControlled.alias' => 'Public'));
	}
	
	public function getRestrictedId() {
		return $this->field('id', array('AclControlled.alias' => 'Restricted'));
	}

}
?>