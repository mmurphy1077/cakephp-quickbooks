<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 * 
 * @property Group $Group
 * @propery ProfileImage $ProfileImage
 * @property NotificationPreference $NotificationPreference
 * @propery UserProfile $UserProfile
 */
class User extends AppModel {
	
	public $name = 'User';
	public $userStatuses = array(
		-1 => 'Protected',
		0 => 'Inactive',
		1 => 'Active',
		2 => 'Unverified',
	);
	public $order = 'name_last ASC, name_first ASC';
	public $searchFields = array(
		'name_first' => 'First Name',
		'name_last' => 'Last Name',
		'username' => 'Username',
		'email' => 'Email',
		'title' => 'Title',
		'phone1' => 'Phone',
	);
	public $search_options = array(
		'group' => 'Group',
		'name' => 'Name',
		'username' => 'User Name',
	);
	public $displayField = 'name_last';
	public $contain = array(
		'default' => array(
			'Group',
		),
		'simple' => array(
			'Group',
		),
	);
	public $belongsTo = array(
		'Group',
	);
	public $hasOne = array(
		'UserProfile' => array(
			'dependent' => true,
		),
		'Aro' => array(
			'className' => 'Aro',
			'foreignKey' => 'foreign_key',
			'conditions' => array('Aro.model' => 'User'),
			'dependent' => true,
		),
	);
	public $hasMany = array(
	);	
	public $hasAndBelongsToMany = array(
	);
	public $validate =	array(
		'group_id' => array(
			'rule' => 'notEmpty',
		),
		'name_first' => array(			
			'length' => array(
				'rule' => array('between', 2, 45),
			),
		),
		'name_last' => array(			
			'length' => array(
				'rule' => array('between', 2, 45)
			),
		),
		'phone1' => array(			
			'length' => array(
				'rule' => array('maxLength', 25),
				#'rule' => array('phone', null, 'us'),
			),
		),
		'email' => array(
			'email' => array(
				'allowEmpty' => false,
				'rule' => 'email',			
			),
			'unique' => array(
				'rule' => 'isUniqueException', 'field' => 'email', 'exception_id' => 'id',
			),
		),
		/*
		'username' => array(			
			'length' => array(
				'rule' => array('between', 2, 45)
			),
			'unique' => array(
				'rule' => 'isUniqueException', 'field' => 'username', 'exception_id' => 'id',
			),
		),
		*/
		'password1' => array(			
			'password1' => array(
				'allowEmpty' => true,
				'rule' => array('validatePassword'),
			),
		),
		'password2' => array(
			'password2' => array(
				'rule' => 'matchField', 'field1' => 'password1', 'field2' => 'password2',
			),
		),
	);
	
	function afterFind($results, $primary = false) {
		if (!empty($results) && !empty($results[0]) && is_array($results[0])) {
			foreach ($results as $i => $result) {
				// Clear some values in case a User is deleted
				if (empty($result[$this->alias]['id'])) {
					$results[$i][$this->alias]['id'] = null;
				}
				/*
				if (empty($result[$this->alias]['name_first'])) {
					$results[$i][$this->alias]['name_first'] = 'Unknown';
				}
				if (empty($result[$this->alias]['name_last'])) {
					$results[$i][$this->alias]['name_last'] = 'User';
				}
				*/
			}
		}
		return $results;
	}
	
	function afterLogin($id, $contain = 'default') {
		$this->contain($this->contain[$contain]);
		$user =  parent::getById($id);
		
		return $user;
	}
	
	function cleanUpDB($user_id = null) {
		return true;
	}
	
	function buildPermissions($user) {
		// Access the AroS tabel to determinre the id values for the Group and User Request Objects
		$user_id = null;
		$group_id = null;
		$sql = "SELECT * FROM aros as Aro WHERE Aro.parent_id IS NULL AND Aro.model = 'User'";
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			$user_id = $results[0]['Aro']['id'];
		}
		
		$sql = "SELECT * FROM aros as Aro WHERE Aro.parent_id IS NULL AND Aro.model = 'Group'";
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			$group_id = $results[0]['Aro']['id'];
		}
		
		// There should always be a group.
		$test = array();
		
		/*
		 * Application specific permissions
		 */
		$sql = "SELECT * FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = 3 AND Aro.parent_id = " . $group_id . " AND Aro.model = 'Group' AND Aro.foreign_key = " . $user['Group']['id'];
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			$test = array('Application'=>array());
			foreach($results as $result) {
				$test['Application'][$result['Aco']['alias']] =  $result['ArosAco']['_access'];
			}
			$user['Group'] += $test;
		}
		/*
		 * Module Permissions
		 */
		$sql = "SELECT * FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = 1 AND Aro.parent_id = " . $group_id . " AND Aro.model = 'Group' AND Aro.foreign_key = " . $user['Group']['id'];
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			foreach($results as $result) {
				if(!empty($result['Aco']['model'])) {
					// Create the 'access' element
					$test = array($result['Aco']['model']=>array());
					$test[$result['Aco']['model']]['_access'] =  $result['ArosAco']['_access'];
					
					// Find all other controlled permissions
					$sql = "SELECT Aco.*, ArosAco._access FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = " . $result['Aco']['id'] . " AND Aro.parent_id = " . $group_id . " AND Aro.model = 'Group' AND Aro.foreign_key = " . $user['Group']['id'];
					$child = $this->Aro->query($sql);
					if(!empty($child)) {
						foreach($child as $data) {
							$test[$result['Aco']['model']][$data['Aco']['alias']] = $data['ArosAco']['_access'];
						}
					}
					$user['Group'] += $test;
				}
			}
		}
		
		$test = array();
		// If $user_id is not empty... Check
		if(!empty($user_id)) {
			/*
			 * Application specific permissions
			 */
			$sql = "SELECT * FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = 3 AND Aro.parent_id = " . $user_id . " AND Aro.model = 'User' AND Aro.foreign_key = " . $user['User']['id'];
			$results = $this->Aro->query($sql);
			if(!empty($results)) {
				$test = array('Application'=>array());
				foreach($results as $result) {
					$test['Application'][$result['Aco']['alias']] =  $result['ArosAco']['_access'];
				}
				$user['User'] += $test;
			}		

			/*
			 * Module Permissions
			 */
			$sql = "SELECT * FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = 1 AND Aro.parent_id = " . $user_id . " AND Aro.model = 'User' AND Aro.foreign_key = " . $user['User']['id'];
			$results = $this->Aro->query($sql);
			
			// Obtain any User specific permissions.
			if(!empty($results)) {
				foreach($results as $result) {
					if(!empty($result['Aco']['model'])) {
						// Create the 'access' element
						$test = array($result['Aco']['model']=>array());
						$test[$result['Aco']['model']]['_access'] =  $result['ArosAco']['_access'];
							
						$sql = "SELECT Aco.*, ArosAco._access FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = " . $result['Aco']['id'] . " AND Aro.parent_id = " . $user_id . " AND Aro.model = 'User' AND Aro.foreign_key = " . $user['User']['id'];
						$child = $this->Aro->query($sql);
						if(!empty($child)) {
							foreach($child as $data) { 
								$test[$result['Aco']['model']][$data['Aco']['alias']] = $data['ArosAco']['_access'];
							}
						}
						$user['User'] += $test;
					}
				}
			}
		}
		
		// Get Job Types
		$user['JobType'] = $this->__buildUserJobType($user);
		return $user;
	}
	
	private function __buildUserJobType($user) {
		return null;
	}
	
	function buildGroupPermissions($group_id = null) {
		// Access the AroS table to determine the id values for the Group and User Request Objects
		$sql = "SELECT * FROM aros as Aro WHERE Aro.parent_id IS NULL AND Aro.model = 'Group'";
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			$group_parent_id = $results[0]['Aro']['id'];
		}
	
		// There should always be a group.
		$test = array();
		$user = array();
		/*
		 * Application specific permissions
		 */
		$sql = "SELECT * FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = 3 AND Aro.parent_id = " . $group_parent_id . " AND Aro.model = 'Group' AND Aro.id = " . $group_id;
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			$test = array('Application'=>array());
			foreach($results as $result) {
				$test['Application'][$result['Aco']['alias']] =  $result['ArosAco']['_access'];
			}
			$user += $test;
		}
		/*
		 * Module Permissions
		 */
		$sql = "SELECT * FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = 1 AND Aro.parent_id = " . $group_parent_id . " AND Aro.model = 'Group' AND Aro.id = " . $group_id;
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			foreach($results as $result) {
				if(!empty($result['Aco']['model'])) {
					// Create the 'access' element
					$test = array($result['Aco']['model']=>array());
					$test[$result['Aco']['model']]['_access'] =  $result['ArosAco']['_access'];
						
					// Find all other controlled permissions
					$sql = "SELECT Aco.*, ArosAco._access FROM (aros AS Aro LEFT OUTER JOIN aros_acos AS ArosAco ON Aro.id = ArosAco.aro_id) LEFT OUTER JOIN acos AS Aco ON ArosAco.aco_id = Aco.id WHERE Aco.parent_id = " . $result['Aco']['id'] . " AND Aro.parent_id = " . $group_parent_id . " AND Aro.model = 'Group' AND Aro.id = " . $group_id;
					$child = $this->Aro->query($sql);
					if(!empty($child)) {
						foreach($child as $data) {
							$test[$result['Aco']['model']][$data['Aco']['alias']] = $data['ArosAco']['_access'];
						}
					}
					$user += $test;
				}
			}
		}
		return $user;
	}
	
	function buildGroupFromAro() {
		// Access the AroS table to determine the id values for the Group and User Request Objects
		$group_id = null;
		$data = null;
		$sql = "SELECT * FROM aros as Aro WHERE Aro.parent_id IS NULL AND Aro.model = 'Group'";
		$results = $this->Aro->query($sql);
		if(!empty($results)) {
			$group_id = $results[0]['Aro']['id'];
			$sql = "SELECT * FROM aros as Aro WHERE Aro.parent_id = " . $group_id . " AND Aro.model = 'Group' AND Aro.alias <> 'Developer'";
			$data = $this->Aro->query($sql);
		}
		return $data;
	}
	
	function afterSave($created) {
		if ($created) {
			$this->data[$this->alias]['id'] = $this->getLastInsertID();
		}
		// Clear all JobTypesUser for the user.
		
		if (array_key_exists('UserProfile', $this->data) && !empty($this->data['UserProfile'])) {
			// Save associated UserProfile record if values exist in postback
			$this->data['UserProfile']['user_id'] = $this->data[$this->alias]['id'];
			$this->UserProfile->save($this->data, false);
		}
		/**
		 * Save corresponding Image data if anything was posted back
		 */
		$appImages = Configure::read('Images');
		foreach ($appImages as $img => $configs) {
			// Loop through all Configure::Images indices and determine if any exist in postback
			if (array_key_exists($img, $this->data) && !empty($this->data[$img]['name']['name'])) {
				// Engage Image save operation
				$name = $this->data[$img]['name'];
				$this->data[$img] = Set::merge($this->data[$img], $name);
				$this->data[$img] = Set::merge($this->data[$img], array(
					'model' => $img,
					'foreign_key' => $this->data[$this->alias]['id'],
					'creator_id' => null,
					'title' => $this->data[$img]['name'],
				));
				$this->{$img}->save($this->data, false);
			}
		}
		
		/*
		 * Permissions
		 * Check the Aros table for a record where the foreign_key value => $this->data[$this->alias]['id'] and model => 'User'
		 */
		$conditions['Aro.model'] = 'User';
		$conditions['Aro.foreign_key'] = $this->data[$this->alias]['id'];
		$result = $this->Aro->find('first', array('conditions' => $conditions));
		if(empty($result) && (array_key_exists('group_id', $this->data[$this->alias]) && !empty($this->data[$this->alias]['group_id']))) {
			// Obtain the group aro_id
			$conditions['Aro.model'] = 'Group';
			$conditions['Aro.foreign_key'] = $this->data[$this->alias]['group_id'];
			$result = $this->Aro->find('first', array('conditions' => $conditions));
			$group_aros_id = $result['Aro']['id'];
			
			// Create and Aro record for the User.
			// Obtain the parent_id
			$conditions['Aro.parent_id'] = null;
			$conditions['Aro.model'] = 'User';
			$conditions['Aro.foreign_key'] = null;
			$result = $this->Aro->find('first', array('conditions' => $conditions));
			if(!empty($result)) {
				$parent_id = $result['Aro']['id'];
				$data = array();
				$data['Aro']['id'] = null;
				$data['Aro']['model'] = 'User';
				$data['Aro']['foreign_key'] = $this->data[$this->alias]['id'];
				$data['Aro']['parent_id'] = $parent_id;
				$data['Aro']['alias'] = $this->data[$this->alias]['name_first'] . ' ' . $this->data[$this->alias]['name_last'];
				if($this->Aro->save($data)) {
					$aro_id = $this->Aro->getLastInsertID();
					$conditions = array('ArosAco.aro_id' => $group_aros_id);
					App::import('model','ArosAco');
					$aros_acos = new ArosAco();
					$permissions = $aros_acos->find('all', array('conditions' => $conditions, 'recursive' => -1));
					if(!empty($permissions)) {
						foreach ($permissions as $permission) {
							$new_record = $permission;
							$new_record['ArosAco']['id'] = null;
							$new_record['ArosAco']['aro_id'] = $aro_id;
							
							$aros_acos->create();
							$aros_acos->save($new_record);
						}
					}
				}
			}
		}
	}
	
	function beforeSave($options = array()) {
		if (array_key_exists('status', $this->data[$this->alias]) && $this->data[$this->alias]['status'] == USER_STATUS_ACTIVE) {
			// If status is 1 (active), automatically verify
			$this->data[$this->alias]['verification_code'] = time();
		}
		if (!empty($this->data[$this->alias]['password1']) && !empty($this->data[$this->alias]['password2'])) {
			// Set password field to user entered values from postback if applicable
			if ($this->data[$this->alias]['password1'] == $this->data[$this->alias]['password2']) {
				$this->data[$this->alias]['password'] = $this->data[$this->alias]['password1'];
			}
		}
		if (!empty($this->data[$this->alias]['password'])) {
			// Hash the password if it exists (Blowfish)
			$this->data[$this->alias]['password'] = Security::hash($this->data[$this->alias]['password'], 'blowfish');
		}
		return true;
	}
	
	function beforeValidate($options = array()) {
		parent::beforeValidate();
		
		$this->UserProfile->set($this->data);
		if (!$this->UserProfile->validates()) {
			return false;
		}
		return true;
	}
	
	function getActive() {
		$this->contain();
		return $this->find('all', array(
			'conditions' => array($this->alias.'.status' => USER_STATUS_ACTIVE),
		));
	}
	
	function getIdByEmail($email) {
		return $this->field('id', array('email' => $email));
	}
	
	function getIdByUsername($username) {
		return $this->field('id', array('username' => $username));
	}
	
	public function getList($status = null, $order_field = null, $group_type = null, $include_admins = 0) {
		$conditions = array();
		if(!empty($group_type)) {
			$conditions['group_id'] = $group_type;
		}
		$conditions['status'] = STATUS_ACTIVE;
		switch ($status) {
			case 'id':
				return $this->find('list', array('fields' => array('id', 'id'), 'conditions' => $conditions));
				break;
			case 'username':
				return $this->find('list', array('fields' => array('id', 'username'), 'conditions' => $conditions, 'order' => $this->alias.'.username ASC'));
				break;
			case 'fnln':
				$this->contain();
				$results = $this->find('all', array('fields' => array('id', 'name_first', 'name_last'), 'conditions' => $conditions, 'order' => array($this->alias.'.name_first ASC', $this->alias.'.name_last ASC')));
				$list = array();
				if (!empty($results)) {
					foreach ($results as $result) {
						$list[$result[$this->alias]['id']] = $result[$this->alias]['name_first'].' '.$result[$this->alias]['name_last']; 
					}
				}
				return $list;
				break;
			case 'lnfn':
				$this->contain();
				$results = $this->find('all', array('fields' => array('id', 'name_first', 'name_last'), 'conditions' => $conditions, 'order' => array($this->alias.'.name_last ASC', $this->alias.'.name_first ASC')));
				$list = array();
				if (!empty($results)) {
					foreach ($results as $result) {
						$list[$result[$this->alias]['id']] = $result[$this->alias]['name_last'].', '.$result[$this->alias]['name_first']; 
					}
				}
				return $list;
				break;
			case 'email':
				return $this->find('list', array('fields' => array('id', 'email'), 'conditions' => $conditions, 'order' => $this->alias.'.email ASC'));
				break;
			default:
				return parent::getList();
		}
	}
	
	private function __include_in_account_rep_selection() {
		// Include the following groups
		// Production Supervisor(5), System Administrator(3), Executive(2)
		$conditions = array('group_id' => array(5,3,2));
		return $this->find('list', array('fields' => array('id', 'id'), 'conditions' => $conditions));
	}
	
	public function getAccountRepListDEPRICATED($status = null) {
		$conditions['group_id'] = GROUP_PROJECT_MANAGERS_ID;
		$conditions['status'] = STATUS_ACTIVE;
		// Get a list of ID's for the conditions
		$ids = $this->find('list', array('fields' => array('id', 'id'), 'conditions' => $conditions));
		// Include any extra User ids (exceptions)
		$ids = array_merge($ids, $this->__include_in_account_rep_selection());
		$conditions = array('id' => $ids);
		switch ($status) {
			case 'id':
				return $this->find('list', array('fields' => array('id', 'id'), 'conditions' => $conditions));
				break;
			case 'username':
				return $this->find('list', array('fields' => array('id', 'username'), 'conditions' => $conditions, 'order' => $this->alias.'.username ASC'));
				break;
			case 'fnln':
				$this->contain();
				$results = $this->find('all', array('fields' => array('id', 'name_first', 'name_last'), 'conditions' => $conditions, 'order' => array($this->alias.'.name_last ASC', $this->alias.'.name_first ASC')));
				$list = array();
				if (!empty($results)) {
					foreach ($results as $result) {
						$list[$result[$this->alias]['id']] = $result[$this->alias]['name_first'].' '.$result[$this->alias]['name_last'];
					}
				}
				return $list;
				break;
			case 'lnfn':
				$this->contain();
				$results = $this->find('all', array('fields' => array('id', 'name_first', 'name_last'), 'conditions' => $conditions, 'order' => array($this->alias.'.name_last ASC', $this->alias.'.name_first ASC')));
				$list = array();
				if (!empty($results)) {
					foreach ($results as $result) {
						$list[$result[$this->alias]['id']] = $result[$this->alias]['name_last'].', '.$result[$this->alias]['name_first'];
					}
				}
				return $list;
				break;
			default:
				return parent::getList();
		}
	}
	
	public function getAccountRepList($display_format = null, $include_admins = 0) {
		$conditions['JobTypesUser.job_type_id'] = DISTRIBUTION_ACCOUNT_REP_REQUEST;
		$ids = $this->JobTypesUser->find('list', array('fields' => array('user_id', 'user_id'), 'conditions' => $conditions));
		return $this->getEmployeeList($display_format, $ids);
	}
	
	/*
	public function getProjectManagersList($display_format = null, $include_admins = 0) {
		$conditions['JobTypesUser.job_type_id'] = DISTRIBUTION_PROJECT_MANAGER_REQUEST;
		$ids = $this->JobTypesUser->find('list', array('fields' => array('user_id', 'user_id'), 'conditions' => $conditions));
		return $this->getEmployeeList($display_format, $ids);
	}
	*/
	
	public function getProductionTeamMembersList($status = null) {
		return $this->getList($status, null, GROUP_PRODUCTION_TEAM_MEMBERS_ID);
	}
	
	public function getDesignersList($status) {
		$conditions['JobTypesUser.job_type_id'] = DISTRIBUTION_DESIGN_REQUEST;
		$ids = $this->JobTypesUser->find('list', array('fields' => array('user_id', 'user_id'), 'conditions' => $conditions));
		return $this->getEmployeeList($status, $ids);
	}
	
	public function getAssignmentList($type, $display_format) {
		$conditions['JobTypesUser.job_type_id'] = $type;
		$ids = $this->JobTypesUser->find('list', array('fields' => array('user_id', 'user_id'), 'conditions' => $conditions));
		return $this->getEmployeeList($display_format, $ids);
	}
	
	public function getEmployeeList($status = null, $users_id = null) {
		$conditions[$this->alias.'.status >'] = 0;
		$conditions[$this->alias.'.group_id >'] = 1;
		if(!empty($users_id)) {
			$conditions[$this->alias.'.id'] = $users_id;
		}
		switch ($status) {
			case 'username':
				return $this->find('list', array('fields' => array('id', 'username'), 'conditions' => $conditions, 'order' => $this->alias.'.username ASC'));
				break;
			case 'fnln':
				$this->contain();
				$order = array('name_first ASC', 'name_last ASC');
				$results = $this->find('all', array('fields' => array('id', 'name_first', 'name_last'), 'conditions' => $conditions, 'order' => $order));
				$list = array();
				if (!empty($results)) {
					foreach ($results as $result) {
						$list[$result[$this->alias]['id']] = $result[$this->alias]['name_first'].' '.$result[$this->alias]['name_last']; 
					}
				}
				return $list;
				break;
			case 'lnfn':
				$this->contain();
				$results = $this->find('all', array('fields' => array('id', 'name_first', 'name_last'), 'conditions' => $conditions));
				$list = array();
				if (!empty($results)) {
					foreach ($results as $result) {
						$list[$result[$this->alias]['id']] = $result[$this->alias]['name_last'].', '.$result[$this->alias]['name_first']; 
					}
				}
				return $list;
				break;
			case 'group' :
				$this->contain('Group');
				$order = array('group_id ASC', 'name_first ASC', 'name_last ASC');
				$results = $this->find('all', array('conditions' => $conditions, 'order' => $order));
				$list = array();
				if (!empty($results)) {
					foreach ($results as $result) {
						$list[$result[$this->alias]['id']] = $result[$this->alias]['name_first'].' '.$result[$this->alias]['name_last'] . '&nbsp;&nbsp;&nbsp;<span class="light small">(' . $result['Group']['name'] . ')</span>';
					}
				}
				return $list;
			default:
				return parent::getList();
		}
	}
	
	function formatSearchConditions($data) {
		if (empty($data) || !array_key_exists('keyword', $data[$this->alias])) {
			return null;
		}
		$conditions = array();
		$keyword = '%'.mysql_real_escape_string($data[$this->alias]['keyword']).'%';
		if (!empty($data[$this->alias]['searchField'])) {
			$conditions[$this->alias.'.'.$data[$this->alias]['searchField'].' LIKE'] = $keyword;
		} else {
			foreach ($this->searchFields as $field => $label) {
				$conditions['OR'][$this->alias.'.'.$field.' LIKE'] = $keyword;
			}
		}
		return $conditions;
	}
	
	/**
	 * Wrapper for User::validatePasswordAdvanced so we can
	 * 		call this method from a non-postback scenario easily
	 * 
	 * @param string $password the password to validate
	 * @return bool true if validation passes
	 */
	function validatePassword($password) {
		$arg1 = null;
		if (!empty($this->id)) {
			$arg1 = $this->id;
		} elseif (!empty($this->data)) {
			$arg1 = $this->data;
		}
		if (!empty($arg1)) {
			// User::id must be set someplace else (i.e. the controller)
			return $this->__validatePassword($arg1, $password);
		}
		return false;
	}
	
	/**
	 * Validates a password string using the following criteria:
	 * 
	 * I.	Must not contain the user's account name or parts of the user's full name
	 * 			that exceed two consecutive characters
	 * II.	Be at least six characters in length
	 * III.	Contain characters from three of the following four categories:
	 * 			1. English uppercase characters (A through Z)
	 * 			2. English lowercase characters (a through z)
	 * 			3. Base 10 digits (0 through 9)
	 * 			4. Non-alphabetic characters (for example, !, $, #, %)
	 * 
	 * @param int $userId the User::id corresponding to the password in question
	 * @param string $password the password to validate
	 * @return bool true if all rules pass, false otherwise
	 */
	function __validatePassword($arg1, $arg2) {
		if (is_array($arg1) && is_array($arg2)) {
			// Function called from validation procedure
			$userId = $this->data[$this->alias]['id'];
			$username = $this->data[$this->alias]['username'];
			$fullname = $this->data[$this->alias]['name_first'].$this->data[$this->alias]['name_last'];
			$password = $this->data[$this->alias]['password1'];
		} else {
			// Values not passed in via $arg1, must query model
			$userId = $arg1;
			$password = $arg2;
			if (is_array($password)) {
				$password = array_pop($password);
			}
			$username = $this->field('username', array('id' => $userId));
			$fullname = $this->field('CONCAT(name_first, name_last) AS fullname', array('id' => $userId));
		}
		// Get a string representation of username.name_first.name_last
		$concatname = $username.$fullname;
		$password = trim($password);
		if (strlen($password) < 6) {
			// Rule II. validation failed
			return false;
		}
		for ($i = 0; $i < strlen($concatname); $i++) {
			$disallowedPattern = strtolower(substr($concatname, $i, 3));
			if (strlen($disallowedPattern) > 2) {
				// We are allowed to match the $concatname up to 2 characters
				if (preg_match('/'.preg_quote($disallowedPattern).'/', strtolower($password))) {
					// Rule I. validation failed
					return false;
				}
			}
		}
		// Establish regex pattern for matching 3 of the 4 categories from III.
		$patterns = array(
			'[A-Z]+',
			'[a-z]+',
			'[0-9]+',
			'[\.\-_+=\(\)\[\]\{\}!$#%]'
		);
		$c = 0;
		foreach ($patterns as $pattern) {
			if (preg_match('/'.$pattern.'/', $password)) {
				// Track found matches in counter variable
				$c++;
			}
		}
		if ($c < 3) {
			// Rule III. validation failed
			return false;
		}
		// Will only arrive here if all rules passed
		return true;
	}
	
	function getWebmaster() {
		$this->contain();
		return $this->find('first', array('conditions' => array(
			$this->alias.'.group_id' => GROUP_ADMINISTRATORS_ID,
			$this->alias.'.status' => USER_STATUS_PROTECTED,
		)));
	}
	
	function updateProfile($id) {
		$data = array(
			'UserProfile' => array(
				'user_id' => $id,
				'visited' => date('Y-m-d H:i:s'),
				'ip' => $_SERVER['REMOTE_ADDR'],
				//'timezone' => $this->Session->read('Application.settings.ApplicationSetting.timezone'),
			),
		);
		if (!$userProfileId = $this->UserProfile->field('id', array('user_id' => $id))) {
			$this->UserProfile->create();
		} else {
			$data['UserProfile']['id'] = $userProfileId;
		}
		$this->UserProfile->save($data, false);
		return true;
	}
	
	function getUserName($id, $format = 'full') { 
		$result = $this->getById($id);
		if(!empty($result)) {
			switch ($format) {
				case 'first':
					return $result['User']['name_first'];
					break;
				case 'full':
					return $result['User']['name_first'].' '.$result['User']['name_last'];
					break;
				case 'first_initial':
					return substr($result['User']['name_first'], 0, 1).'. '.$result['User']['name_last'];
					break;
				case 'reverse':
					return $result['User']['name_last'].', '.$result['User']['name_first'];
					break;
				default:
					return $result['User']['name_first'].' '.substr($result['User']['name_last'], 0, 1).'.';
					break;
			}
		}
		
		return null;
	}
	
	public function constructIndexSearchConditions($search = null) {
		if(empty($search)) {
			return null;
		}
		$search = str_replace("'", "\'", $search);
		/*
		 * Generate a SQL statement to bring back a list of contact id's that satisfy the search criteria.
		 */
		$sql = "SELECT User.id FROM
			users AS User LEFT OUTER JOIN groups AS Groups ON User.group_id = Groups.id 
			WHERE
			User.email LIKE '%" . $search . "%' OR
			User.name_first LIKE '%" . $search . "%' OR
			User.name_last LIKE '%" . $search . "%' OR
			User.username LIKE '%" . $search . "%' OR
			Groups.name LIKE '%" . $search . "%'";
		$result = $this->query($sql);
		$result_list = array();
		if(!empty($result)) {
			foreach($result as $key => $data) {
				$result_list[$data['User']['id']] = $data['User']['id'];
			}
		}
		return $result_list;
	}
	
	public function getControlledObjects() {
		$data = array();
		$sql = "SELECT * FROM acos AS Aco WHERE Aco.parent_id = 3 ORDER BY Aco.model ASC";
		$results = $this->Aro->Aco->query($sql);
		$data['Application'] = array();
		if(!empty($results)) { 
			foreach($results as $result) {
				$data['Application'][$result['Aco']['alias']]['id'] = $result['Aco']['id'];
				$data['Application'][$result['Aco']['alias']]['label'] = $result['Aco']['label'];
			}
		}
	
		$sql = "SELECT * FROM acos AS Aco WHERE Aco.parent_id = 1 AND LENGTH(Aco.model) > 0 ORDER BY Aco.model ASC";
		$results = $this->Aro->Aco->query($sql);
		if(!empty($results)) {
			foreach($results as $result) {
				$data[$result['Aco']['model']] = array();
				// Create the 'access' element
				$data[$result['Aco']['model']]['_access']['id'] = $result['Aco']['id'];
				$data[$result['Aco']['model']]['_access']['label'] = $result['Aco']['label'];
				// Find all other controlled permissions
				$sql = "SELECT Aco.* FROM acos AS Aco WHERE Aco.parent_id = " . $result['Aco']['id'];
				$childs = $this->Aro->Aco->query($sql);
				if(!empty($childs)) {
					foreach($childs as $child) {
						$data[$result['Aco']['model']][$child['Aco']['alias']]['id'] = $child['Aco']['id'];
						$data[$result['Aco']['model']][$child['Aco']['alias']]['label'] = $child['Aco']['label'];
					}
				}
			}
		}
		return $data;
	}
	
	public function getUsersAroId($user_id) {
		$sql = "SELECT * FROM aros AS Aro WHERE Aro.foreign_key = " . $user_id . " AND Aro.model = 'User'";
		$result = $this->Aro->query($sql);
		return $result[0]['Aro']['id'];
	}
	
	public function getGroupsAroId($user_id) {
		$sql = "SELECT * FROM aros AS Aro WHERE Aro.foreign_key = " . $group_id . " AND Aro.model = 'User'";
		$result = $this->Aro->query($sql);
		return $result[0]['Aro']['id'];
	}
	
	public function getScheduleTypes($user) {
		if($user['User']['Schedule']['_access'] == -1) {
			return null;
		}
		$results = array();
		/*
		if($user['User']['Schedule']['_edit_installation_schedule'] == 1) {
			$results[SCHEDULE_TYPE_INSTALLATION] = 'Installation';
			$results['default'] = SCHEDULE_TYPE_INSTALLATION;
		}
		if($user['User']['Schedule']['_edit_design_schedule'] == 1) {
			$results[SCHEDULE_TYPE_DESIGN] = 'Design';
			$results['default'] = SCHEDULE_TYPE_DESIGN;
		}
		if($user['User']['Schedule']['_edit_production_schedule'] == 1) {
			$results[SCHEDULE_TYPE_PRODUCTION] = 'Production';
			$results['default'] = SCHEDULE_TYPE_PRODUCTION;
		}
		*/
		$results['default'] = SCHEDULE_TYPE_LABOR;
		if(empty($results)) {
			return null;
		} else {
			return $results;
		}
	}
	
	public function getEmployeesForScheduleType($scheduleType = 'labor') {
		$data = array();
		
		// Obtain all the JobTypes for the appropriate area.
		if(empty($scheduleType)) {
			$scheduleType = 'labor';
		}
		$results = $this->JobType->find('list', array('fields' => array('id', 'id'), 'conditions' => array('JobType.type' => $scheduleType)));
		$conditions = "(JobTypesUser.job_type_id = " . implode(' OR JobTypesUser.job_type_id = ',$results) . ")";
		$sql = "SELECT CONCAT_WS(' ', User.name_first, User.name_last) AS name, User.id AS id FROM job_types_users AS JobTypesUser LEFT OUTER JOIN users AS User on JobTypesUser.user_id = User.id WHERE User.status = 1 AND " . $conditions . " ORDER BY User.name_first ASC, User.name_last ASC";
		$users_results = $this->query($sql);
		if(!empty($users_results)) {
			foreach($users_results as $key=>$users_result) {
				$data[$users_result['User']['id']]['id'] = $users_result['User']['id'];
				$data[$users_result['User']['id']]['name'] = $users_result[0]['name'];
				$data[$users_result['User']['id']]['type'] = 'employee';
				$data[$users_result['User']['id']]['Schedule'] = array();
			}
		}
		//return array_values($data);
		return $data;
	}
	
	public function getAssignedToUsers() {
		$data[0] = 'Unassigned';
		$sql = "SELECT CONCAT_WS(' ', User.name_first, User.name_last) AS name, User.id AS id FROM users AS User WHERE User.status = 1 AND User.group_id = 2 ORDER BY User.name_first ASC, User.name_last ASC";
		$users_results = $this->query($sql);
		if(!empty($users_results)) {
			foreach($users_results as $key=>$users_result) {
				$data[$users_result['User']['id']] = $users_result[0]['name'];
			}
		}
		return $data;
	}
	
	public function getEmployeesByJobType($scheduleType) {
		// Obtain all the JobTypes for the appropriate schedule Type.
		$sql = "SELECT JobType.id, JobType.name FROM job_types AS JobType WHERE JobType.type = '" . $scheduleType . "'";
		$results = $this->query($sql);
		if(!empty($results)) {
			foreach($results as $key=>$data) {
				$sql = "SELECT CONCAT_WS(' ', User.name_first, User.name_last) AS name, User.id AS id FROM job_types_users AS JobTypesUser LEFT OUTER JOIN users AS User on JobTypesUser.user_id = User.id WHERE JobTypesUser.job_type_id = " . $data['JobType']['id'] . " ORDER BY User.name_last ASC";
				$users_results = $this->query($sql);
				if(!empty($users_results)) {
					foreach($users_results as $key_user=>$users_result) {
						$results[$key]['User'][$key_user]['id'] = $users_result['User']['id'];
						$results[$key]['User'][$key_user]['name'] = $users_result[0]['name'];
					}
				}
			}
		}
		return $results;
	}
	
	public function getFloaterList($exclude_ids=null) {
		$conditions = null;
		if(!empty($exclude_ids)) {
			$conditions = array('NOT' => array($this->alias.'.id' => $exclude_ids));
		}
		
		// For now allow all Apprentices to float
		$conditions[$this->alias.'.group_id'] = 4;
		$order = array($this->alias.'.name_first ASC');
		$fields = array($this->alias.'.id', $this->alias.'.name_first', $this->alias.'.name_last');
		$results = $this->find('all', array('conditions' => $conditions, 'order' => $order, 'fields' => $fields, 'recursive' => -1));
		$data = null;
		if(empty($results)) {
			return null;
		}
		foreach($results as $result) {
			$data[$result[$this->alias]['id']] = $result[$this->alias]['name_first'] . ' ' . $result[$this->alias]['name_last'];
		}
		return $data;
	}
	
	public function getUsersPayRate($id) {
		$result = $this->getById($id);
		$data = null;
		$data['rate'] = null;
		if(!empty($result['Rate'])) {
			$data['id'] = $result['Rate']['id'];
			$data['rate'] = $result['Rate']['rate'];
		}
		return $data;
	}
}
?>