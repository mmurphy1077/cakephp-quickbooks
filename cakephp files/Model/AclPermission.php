<?php
class AclPermission extends AppModel {
	
	public $name = 'AclPermission';
	public $useTable = 'aros_acos';
	public $belongsTo = array(
		'AclRequester' => array(
			'foreignKey' => 'aro_id',
		),
		'AclControlled' => array(
			'foreignKey' => 'aco_id',
		),
	);
	
	public function beforeValidate($options = array()) {
		// Lookup aro_id aco_id combination and invalidate save if it already exists
		$this->contain = array();
		$acl = $this->find('first', array('conditions' => array(
			'aro_id' => $this->data['AclPermission']['aro_id'],
			'aco_id' => $this->data['AclPermission']['aco_id']
		)));
		if (empty($acl)) {
			return true;
		} else {
			$this->invalidate('_access');
			return false;	
		}		
	}
	
	public function beforeSave($options = array()) {
		if (empty($this->data[$this->alias]['_access'])) {
			$this->data[$this->alias]['_access'] = ACL_PERMISSION_ACCESS_DENIED;
		} else {
			$this->data[$this->alias]['_access'] = ACL_PERMISSION_ACCESS_ALLOWED;
		}
		return true;
	}

}
?>
