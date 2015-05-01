<?php
App::uses('AppModel', 'Model');
/**
 * Contact Model
 * 
 * @property User $AccountRep
 * @property Address $Address
 * @property Enumeration $ContactType
 * @property User $Creator
 * @property Customer $Customer
 * @property Enumeration $CustomerSource
 * @property Enumeration $CustomerType
 * @property Enumeration $AccountSource
 * @property Enumeration $AccountType
 */
class Contact extends AppModel {
	public $name = 'Contact';
	public $statuses = array(
		CONTACT_ACTIVE_STATUS_NO_CONTACT=>'No Contact',
		CONTACT_ACTIVE_STATUS_CONTACT_MADE=>'Made Contact',
		CONTACT_ACTIVE_STATUS_MEETING_SCHEDULED=>'Meeting Scheduled',
		CONTACT_ACTIVE_STATUS_QUOTE_REQUESTED=>'Quote Requested',
		CONTACT_ACTIVE_STATUS_PENDING=> 'Pending',
		CONTACT_STATUS_INACTIVE=>'Inactive'
	);
	public $active_statuses = array(
		CONTACT_ACTIVE_STATUS_NO_CONTACT=>'No Contact',
		CONTACT_ACTIVE_STATUS_CONTACT_MADE=>'Made Contact',
		CONTACT_ACTIVE_STATUS_MEETING_SCHEDULED=>'Meeting Scheduled',
		CONTACT_ACTIVE_STATUS_QUOTE_REQUESTED=>'Quote Requested',
		CONTACT_ACTIVE_STATUS_PENDING=> 'Pending',
		CONTACT_STATUS_INACTIVE=>'Inactive'
	);
	public $contact_sources = array(
		0=>'Customer Referral',
		1=>'Word-of-Mouth',
		2=>'Internet',
		3=>'Advertisement',
		4=>'Internal',
		5=>'Call-In',
		6=>'Previous Customer',
		7=>'Walk In',
		8=>'PDX EX',
		9=>'Cold Call',
		10=>'Other',
	);
	public $search_options = array(
		'account_rep' => 'Account Rep',
		'address' => 'Address',
		'company' => 'Company',
		'email' => 'Email',
		'name' => 'Name',
		'phone' => 'Phone',
	);
	public $comment_types = array(
		'general' => 'General',
		'call_inbound' => 'In-bound call',
		'call_outbound' => 'Out-bound call',
	);
	public $filter_types = array(
		'general' => 'General',
		'calls' => 'Calls',
	);
	
	public $order = 'Contact.created DESC';
	public $belongsTo = array(
		'AccountRep' => array(
			'className' => 'User',
			'foreignKey' => 'account_rep_id'
		),
		'Address' => array(
			'className' => 'Address',
			'foreignKey' => 'address_id'
		),
		'ContactType' => array(
			'className' => 'Enumeration',
		),
		'Creator' => array(
			'className' => 'User',
		),
		'Customer' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Contact.model' => 'Customer'),
		),
		'Account' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Contact.model' => 'Account'),
		),
		'CustomerSource' => array(
			'className' => 'Enumeration',
		),
		'CustomerType' => array(
			'className' => 'Enumeration',
		),
		'AccountSource' => array(
			'className' => 'Enumeration',
		),
		'AccountType' => array(
			'className' => 'Enumeration',
		),
	);
	public $hasOne = array(
		'Reminder' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('Reminder.model' => 'Contact'),
			'dependent' => true,
		),
		'LastCall' => array(
			'className' => 'Message',
			'foreignKey' => 'foreign_key',
			'conditions' => array('LastCall.model' => 'Contact', 'LastCall.type' => array('call_outbound', 'call_inbound')),
			'order' => array('LastCall.created DESC'),
			'dependent' => true,
		),
	);
	public $hasMany = array(
		/*
		'CommunicationLog' => array(
			'foreignKey' => 'foreign_key',
			'conditions' => array('CommunicationLog.model' => 'Contact'),
			'dependent' => true,
		),
		*/
	);
	public $validate = array(
		'name_first' => array(			
			'length' => array(
				'allowEmpty' => true,
				'rule' => array('between', 1, 45),
			),
		),
		'name_last' => array(			
			'length' => array(
				'allowEmpty' => true,
				'rule' => array('between', 1, 45)
			),
		),
		'email' => array(
			'email' => array(
				'allowEmpty' => true,
				'rule' => 'email',			
			),
		),
		'title' => array(			
			'length' => array(
				'allowEmpty' => true,
				'rule' => array('between', 1, 45),
			),
		),
		'company_name' => array(			
			'length' => array(
				'allowEmpty' => true,
				'rule' => array('between', 1, 255)
			),
		),
		'customer_source_other' => array(			
			'length' => array(
				'allowEmpty' => true,
				'rule' => array('between', 1, 100)
			),
		),
		'phone_1_number' => array(
			'phone' => array(
				'allowEmpty' => true,
				'rule' => array('maxLength', 25),
				#'rule' => array('phone', null, 'us'),
			),
		),
		'phone_2_number' => array(
			'phone' => array(
				'allowEmpty' => true,
				'rule' => array('maxLength', 25),
				#'rule' => array('phone', null, 'us'),
			),
		),
		'phone_3_number' => array(
			'phone' => array(
				'allowEmpty' => true,
				'rule' => array('maxLength', 25),
				#'rule' => array('phone', null, 'us'),
			),
		),
	);
	public $contain = array(
		'index' => array(
			'AccountRep',
			'Address',
			'CustomerSource',
			'CustomerType',
			'AccountSource',
			'AccountType',
			//'CommunicationLog' => array('order' => array('CommunicationLog.date_communication DESC', 'CommunicationLog.created DESC'), 'User'),
			'Reminder',
			//'LastCall',
		),
		'default' => array(
			'AccountRep',
			'Address' => array(
				'AddressType',
			),
			'Creator',
			'Customer',
			'Account',
			'CustomerSource',
			'CustomerType',
			'AccountSource',
			'AccountType',
			//'CommunicationLog' => array('order' => array('CommunicationLog.date_communication DESC', 'CommunicationLog.created DESC'), 'User'),
			'Reminder',
		),
	);
	public $displayField = 'name_forward';
	public $virtualFields = array(
		'name_forward' => 'CONCAT(Contact.name_first, " ", Contact.name_last)',
		'name_reverse' => 'CONCAT(Contact.name_last, ", ", Contact.name_first)',
	);
	public $alerts = array(
		'alert_call_back_required' => array(
			'type' => 'alert',
			'alert_type' => 'Contact',
			'title'=>'Callback required',
			'redirect'=>array('controller'=>'contacts', 'action'=>'edit_lead'),
		),
	);
	
	public function beforeSave($options = array()) {
		parent::beforeSave();
	
		/*
		 * Check the Address arrays to determine if there is valid data in them
		 * If only 'Required' vales exist... no valid data.
		 */
		if(is_array($this->data)) {
			if (array_key_exists('Address', $this->data) && empty($this->data['Address']['id']) && !empty($this->data['Address']['line1'])) {
				if($this->data['Address']['line1'] == 'Required') {
					$this->data['Address']['line1'] = '';
					$this->data['Address']['city'] = '';
				}
			}
		}
	
		return true;
	}
	
	public function afterSave($created) {
		if ($created) {
			$this->data[$this->alias]['id'] = $this->getLastInsertID();
		}
		
		$saveData = false;
		if (empty($this->data[$this->alias]['address_id'])) {
			if(array_key_exists('Address', $this->data) && !empty($this->data['Address']['line1'])) {
				$saveData = true;
				
			}
		} else {
			// Address id is present... obtain the model of the address record.  If the model is set to 'Contact', the 
			// address belongs to the current user, and the address can be updated.
			$this->Address->id = $this->data[$this->alias]['address_id'];
			$compare_model = $this->Address->field('model');
			if($compare_model == 'Contact') {
				$saveData = true;
				$this->data['Address']['id'] = $this->data[$this->alias]['address_id'];
			}
		}
		
		if($saveData) {
			// Validation has already taken place, save new Address record
			$this->data['Address']['model'] = $this->alias;
			$this->data['Address']['foreign_key'] = $this->data[$this->alias]['id'];
			$this->Address->save($this->data, false);
			
			if (empty($this->data[$this->alias]['address_id'])) {
				// Grab the newly created id and sve it to the ['Contact']['address_id']
				$address_id = $this->Address->getLastInsertID();
				$this->id = $this->data[$this->alias]['id'];
				$this->saveField('address_id', $address_id, array('validate' => false, 'callbacks' => false));
			}
		}
	}
	
	public function afterFind($results, $primary = false) { 
		if ($primary && !empty($results[0][$this->alias])) {
			foreach ($results as $i => $result) {
				if (array_key_exists('notes_internal', $result[$this->alias]) && !empty($result[$this->alias]['notes_internal'])) {
					$string = str_replace('\\r\\n', '<br>', $result[$this->alias]['notes_internal']);
					#$results[$i][$this->alias]['notes_internal'] = $string;
					}
				if (array_key_exists('notes', $result[$this->alias]) && !empty($result[$this->alias]['notes'])) {
					$string = str_replace('\\r\\n', '<br>', $result[$this->alias]['notes']);
					#$results[$i][$this->alias]['notes'] = nl2br($string, false);
				}
			}
		}
		return $results;
	}
	
	public function beforeValidate($options = array()) {
		parent::beforeValidate();
		
		// Setup validation for Address data
		if (empty($this->data[$this->alias]['id']) && !empty($this->data['Address']['line1'])) {
			// Only validate Address model if User entered began an Address
			$this->Address->set($this->data);
			if (!$this->Address->validates()) {
				return false;
			}
		}
		// At a minimum, the user must insert the city when entering a lead.
		if(!empty($this->data[$this->alias]['add_lead'])) {
			$this->Address->validator()->remove('line1');
			$this->Address->validator()->remove('line2');
			$this->Address->validator()->remove('zip_post');
			$this->Address->set($this->data);
			if (!$this->Address->validates()) {
				$this->invalidate('Address.city');
			}
		}
		
		$phone_number_exists = false;
		foreach ($this->data[$this->alias] as $f => $v) {
			if (substr($f, 0, 6) == 'phone_') {
				// Let's determine if any phone numbers have been entered (i.e. optional validation)
				$fieldParts = explode('_', $f);
				if ($fieldParts[2] == 'label') {
					// Check for existence of a phone number value
					if (!empty($this->data[$this->alias]['phone_'.$fieldParts[1].'_number'])) {
						$phone_number_exists = true;
						// Set validation rule for label field if so
						$this->validate['phone_'.$fieldParts[1].'_label'] = array('rule' => 'notEmpty');
					}
				}
			}
		}
		
		// Require at least one phone number to be entered
		#if(empty($phone_number_exists)) {
		#	$this->validator()->getField('phone_1_number')->setRule('phone', array(
		#	    'required' => true,
		#		'allowEmpty' => false,
		#		'message' => 'At least one phone number is required.'
		#	));
		#}
		return true;
	}
	
	public function saveEmptyRecord() {
		$this->data['Contact']['name_first'] = '';
		$this->data['Contact']['name_last'] = '';
	
		// Save without validating
		if($this->save($this->data, false)) {
			$this->data[$this->alias]['id'] = $this->getLastInsertID();
		}
		return $this->data;
	}
	
	public function getNamesByCustomerId($customerId) {
		return $this->find('list', array(
			'conditions' => array($this->alias.'.model' => 'Customer'),
			'conditions' => array($this->alias.'.foreign_key' => $customerId),
			'order' => $this->alias.'.name_last ASC',
		));
	}
	
	public function getPhonesByCustomerId($customerId, $fields = array('id', 'phone_1_number')) {
		return $this->find('list', array(
			'conditions' => array($this->alias.'.model' => 'Customer'),
			'conditions' => array($this->alias.'.foreign_key' => $customerId),
			'fields' => $fields,
		));
	}
	
	public function getPhoneForContact($contactId) {
		$fields = array('Contact.phone_1_number', 'Contact.phone_2_number', 'Contact.phone_3_number');
		$contact = $this->find('first', array('fields' => $fields, 'conditions' => array('Contact.id' => $contactId), 'recursive' => -1));
		if(!empty($contact)) {
			// construct array;
			if(!empty($contact['Contact']['phone_1_number'])) {
				return $contact['Contact']['phone_1_number'];
			}
			if(!empty($contact['Contact']['phone_2_number'])) {
				return $contact['Contact']['phone_2_number'];
			}
			if(!empty($contact['Contact']['phone_3_number'])) {
				return $contact['Contact']['phone_3_number'];
			}
		}
	}
	
	public function getPhonesForQuoteCombo($customerId) {
		$fields = array('phone_1_number', 'phone_1_number');
		$contact_list = $this->find('list', array(
			'conditions' => array($this->alias.'.model' => 'Customer'),
			'conditions' => array($this->alias.'.foreign_key' => $customerId),
			'fields' => $fields,
		));
		
		// Obtain all the phone numbers associated with the $customerId.
		$fields = array('Customer.phone_1_label', 'Customer.phone_1_number', 'Customer.phone_2_label', 'Customer.phone_2_number', 'Customer.phone_3_label', 'Customer.phone_3_number');
		$customer = $this->Customer->find('first', array('fields' => $fields, 'conditions' => array('Customer.id' => $customerId), 'recursive' => -1));
		$customer_phone_numbers = array();
		if(!empty($customer)) {
			// construct array;
			if(!empty($customer['Customer']['phone_1_number'])) {
				$customer_phone_numbers[$customer['Customer']['phone_1_number']] = $customer['Customer']['phone_1_number'] . ' - Customer ' . $customer['Customer']['phone_1_label'];
			}
			if(!empty($customer['Customer']['phone_2_number'])) {
				$customer_phone_numbers[$customer['Customer']['phone_2_number']] = $customer['Customer']['phone_2_number'] . ' - Customer ' . $customer['Customer']['phone_2_label'];
			}
			if(!empty($customer['Customer']['phone_3_number'])) {
				$customer_phone_numbers[$customer['Customer']['phone_3_number']] = $customer['Customer']['phone_3_number'] . ' - Customer ' . $customer['Customer']['phone_3_label'];
			}
		}
		
		if(!empty($customer_phone_numbers)) {
			foreach($customer_phone_numbers as $key => $data){
				if(array_key_exists($key, $contact_list)) {
					unset($contact_list[$key]);
				}
				$contact_list[$key] = $data;
			}
		}
		
		$contact_list = array_merge($contact_list, $customer_phone_numbers);
		return $contact_list;
	}
	
	public function getRecent($limit = 10) {
		$this->contain($this->contain['index']);
		return $this->find('all', array('conditions' => array($this->alias.'.status' => STATUS_ACTIVE), 'order' => $this->alias.'.created DESC', 'limit'=>$limit));
	}
	
	public function updateAccountRep($id, $account_rep_id) {
		$conditions = array('Contact.id'=>$id);
		$this->id = $id;
		if($this->saveField('account_rep_id', $account_rep_id)) {
			// Return the Account Rep name.
			$result = $this->AccountRep->find('first', array('conditions'=>array('AccountRep.id'=>$account_rep_id)));
			if(!empty($result)) {
				return $result['AccountRep']['name_last'].', '.$result['AccountRep']['name_first'];
			} else {
				return null;
			}
		} else {
			return null;
		}
	}
	
	public function getUserAssignedLeads($account_rep_id, $limit = 10) {
		$conditions = array('Contact.account_rep_id'=>$account_rep_id);
		return $this->find('all', array('conditions' => $conditions, 'order' => $this->alias.'.created DESC', 'limit'=>$limit));
	}
	
	public function getAllLeadIds() {
		$conditions = array('Contact.status >' => CONTACT_STATUS_PROTECTED);
		$fields = array('Contact.id', 'Contact.id');
		return $this->find('list', array('fields' => $fields, 'conditions' => $conditions));
	}
	
	public function enteredLeadsCount($date_start = null, $date_end = null) {
		$conditions[$this->alias.'.status'] = STATUS_ACTIVE;
		if(!empty($date_start) && !empty($date_end)) {
			$conditions[$this->alias.'.created >='] = $date_start;
			$conditions[$this->alias.'.created <='] = $date_end;
		}
			
		return count($this->find('all', array('conditions' => $conditions)));
	}
	
	public function calledLeadsCount($date_start, $date_end) {
		$conditions[$this->alias.'.status'] = STATUS_ACTIVE;
		$conditions[$this->alias.'.created >='] = $date_start;
		$conditions[$this->alias.'.created <='] = $date_end;
			
		return count($this->find('all', array('conditions' => $conditions)));
	}
	
	public function leadsToOrder($date_start, $date_end) {
		$leads = $this->enteredLeadsCount();
		
		// Obtain a list of all the active Leads that have been that have been migrated to Customers.
		// Then match the array of customers with Orders placed within the date range.
		$results = $this->find('list', array('fields' => array('foreign_key', 'foreign_key'), 'conditions' => array('Contact.model' => 'Customer', 'Contact.status' => STATUS_ACTIVE)));
		$conditions['Order.customer_id'] = $results;
		$conditions['Order.status'] = STATUS_ACTIVE;
		$conditions['Order.created >='] = $date_start;
		$conditions['Order.created <='] = $date_end;
		$orders = count($this->Customer->Order->find('all', array('conditions' => $conditions)));
		
		if(empty($leads)) {
			$ratio = 0;
		} else {
			$ratio = ($orders/$leads)*100;
		}
		return $ratio;
	}
	
	public function getListByUser($userId) {
		return $this->find('list', array(
			'fields' => array('id', 'name_forward'),
			'conditions' => array(
				$this->alias.'.status' => STATUS_ACTIVE,
				$this->alias.'.account_rep_id' => $userId, 
			),
			'order' => $this->alias.'.name_last ASC',
		));
	}
	
	public function constructIndexSearchConditions($search = null, $field = null) {
		if(empty($search)) {
			return null;
		}
		$search = str_replace("'", "\'", $search);
		$searchCriteria = "Contact.name_first LIKE '%" . $search . "%' OR Contact.name_last LIKE '%" . $search . "%'";
		if(!empty($field)) {
			switch($field) {
				case 'phone':
					$searchCriteria = "Contact.phone_1_number LIKE '%" . $search . "%' OR Contact.phone_2_number LIKE '%" . $search . "%' OR Contact.phone_3_number LIKE '%" . $search . "%'";
					break;
				case 'email':
					$searchCriteria = "Contact.email LIKE '%" . $search . "%'";
					break;
				case 'address':
					$searchCriteria = "Address.line1 LIKE '%" . $search . "%' OR Address.line2 LIKE '%" . $search . "%' OR Address.city LIKE '%" . $search . "%' OR Address.st_prov LIKE '%" . $search . "%' OR Address.zip_post LIKE '%" . $search . "%'";
					break;
				case 'company' :
					$searchCriteria = "Contact.company_name LIKE '%" . $search . "%'";
					break;
				case 'account_rep':
					$searchCriteria = "AccountRep.name_first LIKE '%" . $search . "%' OR AccountRep.name_last LIKE '%" . $search . "%'";
					break;
				case 'name' :
				default :
					$searchCriteria = "Contact.name_first LIKE '%" . $search . "%' OR Contact.name_last LIKE '%" . $search . "%'";
			}
		}
		
		/*
		 * Generate a SQL statement to bring back a list of contact id's that satisfy the search criteria.
		 */
		$sql = "SELECT Contact.id FROM
			((contacts AS Contact LEFT OUTER JOIN users AS AccountRep ON Contact.account_rep_id = AccountRep.id) LEFT OUTER JOIN users AS Creator ON Contact.creator_id = Creator.id) LEFT OUTER JOIN addresses AS Address ON Contact.address_id = Address.id 
			WHERE " . $searchCriteria;
			/*
			Contact.email LIKE '%" . $search . "%' OR
			Contact.name_first LIKE '%" . $search . "%' OR
			Contact.name_last LIKE '%" . $search . "%' OR
			Contact.company_name LIKE '%" . $search . "%' OR
			Contact.phone_1_number LIKE '%" . $search . "%' OR
			Contact.phone_2_number LIKE '%" . $search . "%' OR
			Contact.phone_3_number LIKE '%" . $search . "%' OR
			Contact.notes LIKE '%" . $search . "%' OR
			Contact.notes_internal LIKE '%" . $search . "%' OR
			AccountRep.name_first LIKE '%" . $search . "%' OR
			AccountRep.name_last LIKE '%" . $search . "%' OR
			Creator.name_first LIKE '%" . $search . "%' OR
			Creator.name_last LIKE '%" . $search . "%' OR
			Address.line1 LIKE '%" . $search . "%' OR
			Address.line2 LIKE '%" . $search . "%' OR
			Address.city LIKE '%" . $search . "%' OR
			Address.st_prov LIKE '%" . $search . "%' OR
			Address.zip_post LIKE '%" . $search . "%' OR
			Address.country LIKE '%" . $search . "%'";
			*/
		$result = $this->query($sql);
		$result_list = array();
		if(!empty($result)) {
			foreach($result as $key => $data) {
				$result_list[$data['Contact']['id']] = $data['Contact']['id'];
			}
		}
		return $result_list;
	}
	
	public function clearPrimaryForModel($foreign_key, $model) {
		$sql = 'UPDATE contacts SET contacts.primary = 0 WHERE contacts.model = "' . $model . '" AND contacts.foreign_key = ' . $foreign_key;
		$this->query($sql);
		return true;
	}
	
	public function searchLeadsLike($search) {
		$search = str_replace("'", "%", $search);
		$sql = "SELECT Contact.id, Contact.name_first, Contact.name_last, Contact.company_name, Contact.email, Contact.phone_1_number, Contact.phone_2_number, Contact.phone_3_number, Address.line1, Address.line2, Address.city, Address.st_prov, Address.zip_post  FROM
				contacts AS Contact LEFT OUTER JOIN addresses AS Address ON Contact.id = Address.foreign_key AND Address.model = 'Contact'
				WHERE Contact.status = 1 AND (
				Contact.name_first LIKE '%" . $search . "%' OR
				Contact.name_last LIKE '%" . $search . "%' OR
				Contact.company_name LIKE '%" . $search . "%')";
	
		return $results = $this->query($sql);
	}
	
	public function getContactsForCustomer($foreign_key, $model = 'Customer', $require_email = false) {
		$conditions['Contact.model'] = $model;
		$conditions['Contact.foreign_key'] = $foreign_key;
		$conditions['Contact.status'] = array(1, -1);
		//$conditions['Contact.status'] = '-1'; 
		$order = array('Contact.primary DESC', 'Contact.name_first ASC', 'Contact.name_last ASC');
		$dataSet = $this->find('all', array('conditions' => $conditions, 'order' => $order, 'recursive' => -1));
		
		/*
		 * Compare email addresses... only keep the 
		 */
		$results = array();
		if(!empty($dataSet)) {
			foreach($dataSet as $key=>$data) {
				$match = false;
				if(empty($results)) {
					$match = false;
				} else {
					// Compare email and Name Forward
					foreach($results as $result) {
						if((trim($result['Contact']['name_forward']) == trim($data['Contact']['name_forward'])) && (trim($result['Contact']['email']) == trim($data['Contact']['email']))) {
							$match = true;
						}
					}
				}
				
				// If an email is required, check...
				if($require_email && empty($data['Contact']['email'])) {
					$match = true;
				}
		
				if(!$match) {
					$results[$key] = $data;
				}
			}
		}
		
		return $results;
	}
	
	public function getContactIdsForCustomer($customer_id) {
		$conditions['Contact.model'] = 'Customer';
		$conditions['Contact.foreign_key'] = $customer_id;
		$results = $this->find('list', array('conditions' => $conditions, 'fields' => array('id', 'id')));
		return $results;
	}
	
	public function getContactIdsForAccount($account_id) {
		$conditions['Contact.model'] = 'Account';
		$conditions['Contact.foreign_key'] = $account_id;
		$results = $this->find('list', array('conditions' => $conditions, 'fields' => array('id', 'id')));
		return $results;
	}
	
	public function getAddresses($id, $menuList = true) {
		$this->Address->contain();
		$contactAddressIds = $this->Address->find('list', array(
			'fields' => array('id', 'id'),
			'conditions' => array(
					'Address.model' => 'Contact',
					'Address.foreign_key' => $id,
					'Address.status' => 1,
					'not' => array('Address.line1' => null, 'Address.line1' => ''),
			),
			'order' => array('Address.primary DESC'),
		));
		if(empty($contactAddressIds)) {
			return null;
		}
		
		if($menuList) {
			return $this->Address->menuList(array('Address.id' => $contactAddressIds), null);
		} else {
			return $this->Address->find('all', array('conditions' => array('Address.id' => $contactAddressIds), 'order' => array('Address.model DESC', 'Address.primary DESC', 'AddressType.name ASC')));
		}
	}
	
	public function genrateLeadStatusStats($conditions = null) {
		$conditions_sql = '';
		if(!empty($conditions)) {
			$conditions_sql = ' AND ' . $conditions;
		}
		$sql = 'SELECT
		SUM(no_contact) as no_contact,
		SUM(made_contact) as made_contact, 
		SUM(meeting_scheduled) as meeting_scheduled,
		SUM(unassigned) as unassigned,
		SUM(assigned) as assigned 
		FROM (
			SELECT 
			CASE WHEN (Base.active_status = 1) THEN 1 ELSE 0 END AS no_contact,
			CASE WHEN (Base.active_status = 2) THEN 1 ELSE 0 END AS made_contact, 
			CASE WHEN (Base.active_status = 3) THEN 1 ELSE 0 END AS meeting_scheduled, 
			CASE WHEN (Base.account_rep_id IS Null) THEN 1 ELSE 0 END AS unassigned,
			CASE WHEN (Base.account_rep_id IS NOT NULL) THEN 1 ELSE 0 END AS assigned 
			FROM (
				SELECT * FROM contacts AS Lead WHERE Lead.foreign_key IS NULL AND Lead.model = "Customer" AND Lead.status > 0 ' . $conditions_sql . 
		'	) AS Base
		) AS Calc';
		
		return $this->query($sql);
	}
	
	public function createReport($target_report, $sql_conditions, $sql_order) {
		$conditions = '';
		$order = '';
		if(!empty($sql_conditions['date'])) {
			$conditions = " AND " . $sql_conditions['date'];
		}
		if(!empty($sql_conditions['filter'])) {
			$conditions = $conditions . " AND " . $sql_conditions['filter'];
		}
		if(!empty($sql_order)) {
			$order = " ORDER BY " . $sql_order;
			$order = $order . " , Lead.created DESC";
		}

		switch ($target_report['name']) {
			case 'leads-summary' :
			case 'leads-detail' :
				$sql = "SELECT Lead.company_name, Lead.foreign_key, Lead.name_first, Lead.name_last, Lead.created, Lead.creator_id, Lead.contacted, Lead.date_last_contact_made, Lead.account_rep_id, Lead.active_status, Lead.status, AccountRep.name_first AS account_rep_name_first, AccountRep.name_last AS account_rep_name_last, Creator.name_first AS creator_name_first, Creator.name_last AS creator_name_last, Lead.notes_internal, Job.customer_id As order_customer_id 
						FROM ((contacts AS Lead INNER JOIN users AS Creator ON Lead.creator_id = Creator.id) LEFT OUTER JOIN users AS AccountRep ON Lead.account_rep_id = AccountRep.id) LEFT OUTER JOIN (SELECT DISTINCT customer_id FROM orders) Job ON Lead.foreign_key = Job.customer_id AND Lead.model = 'Customer' 
						WHERE (Lead.status = -1 OR Lead.status > 0) AND Lead.model = 'Customer' AND Lead.from_lead = 1 "; 
				break;
		}
		$sql = $sql . $conditions . $order;
		$sql_stats = "SELECT SUM(lead_became_quoted_customer) AS lead_became_quoted_customer,
			SUM(lead_no_contact) AS lead_no_contact,
			SUM(lead_contact_made) AS lead_contact_made,
			SUM(lead_meeting_scheduled) AS lead_meeting_scheduled,
			SUM(lead_has_order_associated) AS lead_has_order_associated
			FROM (
				SELECT
				CASE WHEN Base.status = -1 THEN 1 ELSE 0 END AS lead_became_quoted_customer,
				CASE WHEN (Base.status = 1 && Base.active_status = 1) THEN 1 ELSE 0 END AS lead_no_contact,
				CASE WHEN (Base.status = 1 && Base.active_status = 2) THEN 1 ELSE 0 END AS lead_contact_made,
				CASE WHEN (Base.status = 1 && Base.active_status = 3) THEN 1 ELSE 0 END AS lead_meeting_scheduled,
				CASE WHEN (Base.order_customer_id IS NOT NULL) THEN 1 ELSE 0 END AS lead_has_order_associated
				FROM (" . $sql . ") AS Base ) AS Stats";
		$results['data'] = $this->query($sql);
		$results['stats'] = $this->query($sql_stats);
		// Other data needed by the report
		$results['status'] = $this->active_statuses;
		return $results;
	}
	
	public function createSnapShot() {
		$sql = "SELECT 	
			SUM(lead_inactive) AS lead_inactive,
			SUM(lead_active) AS lead_active,
			SUM(lead_became_quoted_customer) AS lead_became_quoted_customer,
			SUM(lead_no_contact) AS lead_no_contact,
			SUM(lead_contact_made) AS lead_contact_made,
			SUM(lead_meeting_scheduled) AS lead_meeting_scheduled,
			SUM(lead_has_order_associated) AS lead_has_order_associated, 
			SUM(lead_assigned) AS lead_assigned,
			SUM(lead_unassigned) AS lead_unassigned,
			SUM(active_no_activity_30_days) AS active_no_activity_30_days,
			SUM(lead_to_quote_conversion) AS lead_to_quote_conversion_days,
			SUM(lead_has_a_quote) AS lead_has_a_quote 
			FROM (
				SELECT
				CASE WHEN Base.status = 0 THEN 1 ELSE 0 END AS lead_inactive,
				CASE WHEN Base.status = 1 THEN 1 ELSE 0 END AS lead_active,
				CASE WHEN Base.status = -1 THEN 1 ELSE 0 END AS lead_became_quoted_customer,
				CASE WHEN (Base.status = 1 && Base.active_status = 1) THEN 1 ELSE 0 END AS lead_no_contact,
				CASE WHEN (Base.status = 1 && Base.active_status = 2) THEN 1 ELSE 0 END AS lead_contact_made,
				CASE WHEN (Base.status = 1 && Base.active_status = 3) THEN 1 ELSE 0 END AS lead_meeting_scheduled,
				CASE WHEN (Base.order_customer_id IS NOT NULL) THEN 1 ELSE 0 END AS lead_has_order_associated,
				CASE WHEN ((Base.status = 1) && (Base.account_rep_id IS NOT NULL)) THEN 1 ELSE 0 END AS lead_assigned, 
				CASE WHEN ((Base.status = 1) && (Base.account_rep_id IS NULL)) THEN 1 ELSE 0 END AS lead_unassigned, 
				CASE WHEN ((Base.status = 1) && (Base.last_activity < DATE_SUB(NOW(), INTERVAL 30 DAY))) THEN 1 ELSE 0 END AS active_no_activity_30_days,
				CASE WHEN (Base.quote_created IS NOT NULL) THEN (DATEDIFF(Base.quote_created, Base.created) + 1) ELSE 0 END AS lead_to_quote_conversion, 
				CASE WHEN (Base.quote_customer_id IS NOT NULL) THEN 1 ELSE 0 END AS lead_has_a_quote   
				FROM (
					SELECT Lead.company_name, Lead.foreign_key, Lead.name_first, Lead.name_last, Lead.created, Lead.contacted, Lead.date_last_contact_made, Lead.account_rep_id, Lead.active_status, Lead.status, AccountRep.name_first AS account_rep_name_first, AccountRep.name_last AS account_rep_name_last, Lead.notes_internal, Job.customer_id As order_customer_id, Job.created As order_created, Quote.customer_id As quote_customer_id, Quote.created As quote_created, ActionLog.last_activity  
					FROM ((((contacts AS Lead LEFT OUTER JOIN users AS AccountRep ON Lead.account_rep_id = AccountRep.id) 
					LEFT OUTER JOIN (SELECT customer_id, created FROM quotes ORDER BY created ASC) Quote ON Lead.foreign_key = Quote.customer_id AND Lead.model = 'Customer')
					LEFT OUTER JOIN (SELECT customer_id, created FROM orders ORDER BY created ASC) Job ON Lead.foreign_key = Job.customer_id AND Lead.model = 'Customer') 
					LEFT OUTER JOIN (SELECT foreign_key, model, created as last_activity FROM action_logs WHERE model = 'Contact' ORDER BY created DESC) ActionLog ON Lead.id = ActionLog.foreign_key)
					LEFT OUTER JOIN (SELECT foreign_key, model, created as last_communication FROM communication_logs WHERE model = 'Contact' ORDER BY created DESC) CommunicationLog ON Lead.id = CommunicationLog.foreign_key
						WHERE (Lead.status > -10) AND Lead.from_lead = 1  AND Lead.model = 'Customer' GROUP BY Lead.id
				) AS Base 
			) AS Stats";
		return $this->query($sql);
	}
	
	public function updateCustomerContactFromQuote($quote_data) {
		/*
		 * This is where a new contact can be added to customer, or updated.
		 * If a Quote::contact_id esists... a record is being updated.  Obtain the contact record from the database
		 * If a Quote::contact_id does not exist... a new record is being added.
		 */
		$data = null;
		if(!empty($quote_data['contact_id'])) {
			// updating
			$result = $this->find('first', array('conditions' => array('Contact.id' => $quote_data['contact_id'])));
			if(!empty($result)) {
				// Do not update the phone label... don't know it. Unless the record pulled is empty
				// For now... Lets not update the name.  Worried about seperating the first and last name
				$data['Contact'] = $result['Contact'];
				if(empty($result['Contact']['phone_1_label'])) {
					$data['Contact']['phone_1_label'] = 'Work';
				}
				$data['Contact']['phone_1_number'] = $quote_data['contact_phone'];
				$data['Contact']['title'] = $quote_data['contact_title'];
				$data['Contact']['email'] = $quote_data['contact_email'];
			}
		} else {
			// adding
			$data['Contact']['id'] = null;
			$data['Contact']['foreign_key'] = $quote_data['customer_id'];
			$data['Contact']['model'] = 'Customer';
			$data['Contact']['from_lead'] = 0;
			$data['Contact']['status'] = -1;
			$data['Contact']['phone_1_label'] = 'Work';
			$data['Contact']['phone_1_number'] = $quote_data['contact_phone'];
			$data['Contact']['title'] = $quote_data['contact_title'];
			$data['Contact']['email'] = $quote_data['contact_email'];
			$data['Contact']['name_first'] = '';
			$data['Contact']['name_last'] = $quote_data['contact_name'];
		}
		if(!empty($data)) {
			$this->Customer->Contact->save($data);
			if(empty($data['Contact']['id'])) {
				$data['Contact']['id'] = $this->Customer->Contact->getLastInsertID();
			}
		}
		
		return $data['Contact']['id'];
	}
	
	public function searchContactEmails($search) {
		$search = str_replace("'", "%", $search);
	
		// First.. Search for Customers containing the search phrase.
		$sql = "SELECT Customer.id, Customer.name FROM customers AS Customer WHERE Customer.status > 0 AND Customer.name LIKE '%" . $search . "%'";
		$customers = $this->query($sql);
		if(!empty($customers)) {
			foreach($customers as $key=>$customer) {
				$sql = "SELECT Contact.id, Contact.model, Contact.foreign_key, CONCAT_WS(' ', Contact.name_first, Contact.name_last) As contact_name, Contact.email, Contact.status FROM contacts AS Contact WHERE Contact.email IS NOT NULL AND CHAR_LENGTH(Contact.email) > 0 AND Contact.foreign_key = " . $customer['Customer']['id'] . " And Contact.model = 'Customer' ORDER BY contact_name ASC";
				$customer_emails = $this->query($sql);
				if(!empty($customer_emails)) {
					$customers[$key]['Customer']['Contact'] = $customer_emails;
				} else {
					unset($customers[$key]);
				}
			}
		}
	
		// Second.. Search for Leads with the customer_name containing the search.
		$sql = "SELECT Contact.id, Contact.company_name, CONCAT_WS(' ', Contact.name_first, Contact.name_last) As contact_name, Contact.email FROM contacts AS Contact WHERE Contact.email IS NOT NULL AND CHAR_LENGTH(Contact.email) > 0 AND Contact.foreign_key IS NULL AND Contact.company_name IS NOT NULL AND Contact.company_name LIKE '%" . $search . "%'";
		$company_leads = $this->query($sql);
	
		// Third.. Search for matches on the email addresses themselves
		$sql = "SELECT Contact.id, Contact.company_name, CONCAT_WS(' ', Contact.name_first, Contact.name_last) As contact_name, Contact.email FROM contacts AS Contact WHERE Contact.email IS NOT NULL AND CHAR_LENGTH(Contact.email) > 0 AND Contact.foreign_key IS NULL AND Contact.company_name IS NOT NULL AND Contact.email LIKE '%" . $search . "%'";
		$email_addresses = $this->query($sql);
	
		$data['Customer'] = $customers;
		$data['Leads'] = $company_leads;
		$data['emails'] = $email_addresses;
	
		return $data;
	}
	
	public function buildAlerts($ids, $target_date=null) {
		/*
		 * Check for the following
		 * At this time... the only alert to show for a Contact would be one with a 
		 * Remember record passed the due date.
		 */
	
		if(empty($ids)) {
			return null;
		}
		$conditions_status = '(Contact.status = ' . CONTACT_STATUS_ACTIVE . ')';
		if(is_array($ids)) {
			$conditions_id = '(Contact.id = ' . implode(' OR Contact.id = ', $ids) . ')';
		} else {
			$conditions_id = '(Contact.id = ' . $ids . ')';
		}
	
		if(empty($target_date)) {
			$target_date = date('Y-m-d 23:59:59');
		}
		
		$alerts = $this->alerts;
		foreach($alerts as $key=>$alert) {
			$alerts[$key]['data'] = array();
		}
	
		$sql = "
		SELECT *
		FROM (
			SELECT Contact.id, Contact.name_first, Contact.name_last, Contact.company_name, Reminder.date_reminder,
			CASE WHEN (Reminder.date_reminder IS NOT NULL And Reminder.date_reminder <= '" . $target_date . "') THEN 1 ELSE 0 END AS alert_call_back_required
			FROM contacts As Contact LEFT OUTER JOIN reminders As Reminder ON Reminder.model = 'Contact' And Reminder.foreign_key = Contact.id
			WHERE " . $conditions_id . "
		) AS Base
		WHERE Base.alert_call_back_required > 0";
		
		$results = $this->query($sql);
		if(!empty($results)) {
			foreach($results as $result) {
				// Callback Required
				if(!empty($result['Base']['alert_call_back_required'])) {
					$alerts['alert_call_back_required']['data'][]['Contact'] = $result['Base'];
				}
			}
		}
	
		if(!empty($alerts)) {
			foreach($alerts as $key=>$data) {
				if(empty($data['data'])) {
					unset($alerts[$key]);
				}
			}
		}
		return($alerts);
	}
}
?>