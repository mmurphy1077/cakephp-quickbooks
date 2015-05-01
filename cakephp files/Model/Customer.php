<?php
App::uses('AppModel', 'Model');
/**
 * Customer Model
 * 
 * @property User $AccountRep
 * @property Address $Address
 * @property Contact $Contact
 * @property User $Creator
 * @property Enumeration $CustomerSource
 * @property Enumeration $CustomerType
 * @property Order $Order
 * @property Quote $Quote
 * @property UserFavorite $UserFavorite
 */
class Customer extends AppModel {

	public $name = 'Customer';
	public $actsAs = array('CompanyEntity');
	public $order = array(
		'Customer.name ASC',
	);
	public $search_options = array(
		'account_rep' => 'Account Rep',
		'address' => 'Address',
		'name' => 'Name',
		'contact' => 'Contact',
	);
	public $belongsTo = array(
	
	);
	public $hasMany = array(
	);
	var $hasAndBelongsToMany = array();
	public $validate = array(
		'name' => array(			
			'length' => array(
				'rule' => array('between', 1, 255),
			),
		),
		'phone_1_number' => array(
			'phone' => array(
				'allowEmpty' => true,
				#'rule' => array('phone', null, 'us'),
				'rule' => array('maxLength', 25),
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
		'website' => array(
			'length' => array(
				'allowEmpty' => true,
				'rule' => array('between', 1, 255),
			),
		),
	);
	public $contain = array(
		'default' => array(
			
		),
		'index' => array(
			
		),
		'report' => array(
			
		),
	);
	
	public function afterSave($created) {
		App::import('model','QuickbooksQueue');
		$qb = new QuickbooksQueue();
		if ($created) {
			$this->data[$this->alias]['id'] = $this->getLastInsertID();
			$qb->queueCustomerAdd($this->data[$this->alias]['id']);
		} else {
			$qb->queueCustomerModify($this->data[$this->alias]['id']);
		}
		
		if (array_key_exists('Address', $this->data) && !empty($this->data['Address']['line1'])) {
			// Validation has already taken place, save new Address record
			$this->data['Address']['model'] = $this->alias;
			$this->data['Address']['foreign_key'] = $this->data[$this->alias]['id'];
			if ($created) {
				// When adding... force the address to be the Primary
				$this->data['Address']['primary'] = 1;
			}
			//$this->Address->save($this->data, false);
		}
	}
	
	public function beforeSave($options = array()) {
		if (!empty($this->data[$this->alias]['website'])) {
			// Remove http:// or https:// from URL if it exists
			$this->data[$this->alias]['website'] = preg_replace("(https?://)", "", $this->data[$this->alias]['website']);
		}
		return true;
	}
	
	public function beforeValidate($options = array()) {
		parent::beforeValidate();
		
		// Setup validation for phone number fields and labels
		foreach ($this->data[$this->alias] as $f => $v) {
			if (substr($f, 0, 6) == 'phone_') {
				// Let's determine if any phone numbers have been entered (i.e. optional validation)
				$fieldParts = explode('_', $f);
				if ($fieldParts[2] == 'label') {
					// Check for existence of a phone number value
					if (!empty($this->data[$this->alias]['phone_'.$fieldParts[1].'_number'])) {
						// Set validation rule for label field if so
						$this->validate['phone_'.$fieldParts[1].'_label'] = array('rule' => 'notEmpty');
					}
				}
			}
		}
		return true;
	}
	
	public function getByIds($ids, $containString = 'default') {
		$this->Behaviors->disable('CompanyEntity');
		$conditions = array('Customer.id' => $ids);
		$contain = $this->contain[$containString];
		$results = $this->find('all', array('conditions' => $conditions, 'contain' => $contain));
		return $results;
	}
	
	public function getByName($name) {
		$this->contain();
		return $this->findByName($name);
	}
	
	public function createCustomerFromOrder($name, $creator_id) {
		if(empty($name)) {
			return null;
		}
		$data[$this->alias]['id'] = null;
		$data[$this->alias]['name'] = $name;
		$data[$this->alias]['creator_id'] = $creator_id;
		$data[$this->alias]['account_rep_id'] = $creator_id;
		$this->create();
		if($this->save($data)) {
			return $this->getLastInsertID();
		} else {
			return null;
		}
	}
	
	public function getAddresses($id, $includeContacts = false, $menuList = true) {
		$this->Address->contain();
		$addressIds = $this->Address->find('list', array(
			'fields' => array('id', 'id'),
			'conditions' => array(
				'Address.model' => $this->alias,
				'Address.foreign_key' => $id,
				'Address.status' => 1,
				'not' => array('Address.line1' => null, 'Address.line1' => ''),
			),
		));
		if ($includeContacts) {
			$contactIds = $this->Contact->find('list', array(
				'fields' => array('id', 'id'),
				'conditions' => array('Contact.model' => 'Customer', 'Contact.foreign_key' => $id),
			));
			$this->Address->contain();
			$contactAddressIds = $this->Address->find('list', array(
				'fields' => array('id', 'id'),
				'conditions' => array(
					'Address.model' => 'Contact',
					'Address.foreign_key' => $contactIds,
					'Address.status' => 1,
					'Address.copied_to_customer' => 0,
					'not' => array('Address.line1' => null, 'Address.line1' => ''),
				),
				'order' => array('Address.primary DESC'),
			));
			$addressIds = array_merge($addressIds, $contactAddressIds);
		}
		if($menuList) {
			return $this->Address->menuList(array('Address.id' => $addressIds), null);
		} else {
			$results = $this->Address->find('all', array('conditions' => array('Address.id' => $addressIds), 'order' => array('Address.model DESC', 'Address.primary DESC', 'Address.name ASC', 'Address.line1 ASC')));
			return $this->_getDistinctAddress($results);
		}
	}
	
	private function _getDistinctAddress($dataSet) {  
		$results = array();
		if(!empty($dataSet)) {
			foreach($dataSet as $key=>$data) {
				$match = false;
				if(empty($results)) {
					$match = false;
				} else {
					// Compare Address Types
					foreach($results as $result) { 
						if($result['AddressType']['name'] == $data['AddressType']['name']) {
						// Address Types are equal... continue comparisons.
							if(($result['Address']['name'] == $data['Address']['name']) && ($result['Address']['line1'] == $data['Address']['line1']) && ($result['Address']['line2'] == $data['Address']['line2']) && ($result['Address']['city'] == $data['Address']['city']) && ($result['Address']['st_prov'] == $data['Address']['st_prov']) && ($result['Address']['zip_post'] == $data['Address']['zip_post'])) {
								$match = true;
							}
						}
					}
				}
				
				if(!$match) {
					$results[$key] = $data;
				}
			}
		}
		
		return $results;
	}
	
	public function convertLeadToCustomer($data) {
		// Map Contact data to the Customer model fields
		$customer['Customer']['account_rep_id'] = $data['Contact']['account_rep_id'];
		$customer['Customer']['creator_id'] = $data['Contact']['creator_id'];
		$customer['Customer']['customer_source_id'] = $data['Contact']['customer_source_id'];
		$customer['Customer']['customer_type_id'] = $data['Contact']['customer_type_id'];
		if (!empty($data['Contact']['company_name'])) {
			$customer['Customer']['name'] = $data['Contact']['company_name'];
		} else {
			$customer['Customer']['name'] = $data['Contact']['name_forward'];
		}
		$customer['Customer']['phone_1_label'] = $data['Contact']['phone_1_label'];
		$customer['Customer']['phone_1_number'] = $data['Contact']['phone_1_number'];
		$customer['Customer']['phone_2_label'] = $data['Contact']['phone_2_label'];
		$customer['Customer']['phone_2_number'] = $data['Contact']['phone_2_number'];
		$customer['Customer']['phone_3_label'] = $data['Contact']['phone_3_label'];
		$customer['Customer']['phone_3_number'] = $data['Contact']['phone_3_number'];
		$customer['Customer']['notes'] = $data['Contact']['notes'];
		$customer['Customer']['notes_internal'] = $data['Contact']['notes_internal'];
		
		$copied_contact_address_id = $data['Address']['id'];
		unset($data['Address']['id']);
		$customer['Address'] = $data['Address'];
		$customer['Address']['primary'] = 1;
		
		// Set the copied contact's address 'copied_to_customer' field to '1'
		$this->Address->id = $copied_contact_address_id;
		$this->Address->saveField('copied_to_customer', 1, array('callbacks' => false));
		$this->Address->id = null;
		
		$this->set($customer);
		if ($result = $this->save($customer, false)) {
			$new_customer_id = $this->getLastInsertID();
			
			$this->Contact->id = $data['Contact']['id'];
			$this->Contact->saveField('foreign_key',$new_customer_id, array('callbacks' => false)); 
			$this->Contact->saveField('model','Customer', array('callbacks' => false));
			$this->Contact->saveField('status',CONTACT_STATUS_PROTECTED, array('callbacks' => false));
			return $new_customer_id;
		} else {
			return null;
		}
		
		// Update the Contact data 
	}
	
	public function getEmailAddressesForCustomer($quote_id) {
		$this->Behaviors->disable('CompanyEntity');
		
		// Access the CustomersQuote table to obtain a list of customers associated with the quote.
		$list = $this->Quote->find('list', array('fields' => array('customer_id', 'customer_id'), 'conditions' => array('Quote.id' => $quote_id)));
		if(!empty($list)) {
			$customer_emails = array();
			foreach($list as $key=>$data) {
				if(empty($data)) {
					unset($list[$key]);
				} else {
					$conditions = array('Customer.id' => $data);
					$results = $this->find('all', array('conditions' => $conditions, 'contain' => 'Contact'));
					if(!empty($results)) {
						foreach($results as $result) {
							if(!empty($include_customer_email) && !empty($result['Customer']['email'])) {
								$customer_emails[$key][$result['Customer']['email']] = $result['Customer']['name'] . '   [' . $result['Customer']['email'] . ']';
							}
							if(!empty($result['Contact'])) {
								foreach($result['Contact'] as $contact) {
									if(!empty($contact['email'])) {
										$customer_emails[$key][$contact['email']] = $contact['name_first'] . ' ' . $contact['name_last'] . '   < ' . $contact['email'] . ' >';
									}
								}
							}
						}
					}
				}
			}
			
		}
		return $customer_emails;
	}
	
	public function constructIndexSearchConditions($search = null, $field = null) {
		if(empty($search)) {
			return null;
		}
		$search = str_replace("'", "\'", $search);
		$searchCriteria = "Customer.name LIKE '%" . $search . "%'";
		if(!empty($field)) {
			switch($field) {
				case 'contact':
					$searchCriteria = "Contact.name_first LIKE '%" . $search . "%' OR Contact.name_last LIKE '%" . $search . "%'";
					break;
				case 'address':
					#$searchCriteria = "Address.line1 LIKE '%" . $search . "%' OR Address.line2 LIKE '%" . $search . "%' OR Address.city LIKE '%" . $search . "%' OR Address.st_prov LIKE '%" . $search . "%' OR Address.zip_post LIKE '%" . $search . "%' OR BillingAddress.line1 LIKE '%" . $search . "%' OR BillingAddress.line2 LIKE '%" . $search . "%' OR BillingAddress.city LIKE '%" . $search . "%' OR BillingAddress.st_prov LIKE '%" . $search . "%' OR BillingAddress.zip_post LIKE '%" . $search . "%'";
					$searchCriteria = "Address.line1 LIKE '%" . $search . "%' OR Address.line2 LIKE '%" . $search . "%' OR Address.city LIKE '%" . $search . "%' OR Address.st_prov LIKE '%" . $search . "%' OR Address.zip_post LIKE '%" . $search . "%'";
					break;
				case 'account_rep':
					$searchCriteria = "AccountRep.name_first LIKE '%" . $search . "%' OR AccountRep.name_last LIKE '%" . $search . "%'";
					break;
				case 'name' :
				default :
					$searchCriteria = "Customer.name LIKE '%" . $search . "%'";
			}
		}
		
		/*
		 * Generate a SQL statement to bring back a list of contact id's that satisfy the search criteria.
		 */
		$sql = "SELECT Customer.id FROM
				(((customers AS Customer LEFT OUTER JOIN users AS AccountRep ON Customer.account_rep_id = AccountRep.id) LEFT OUTER JOIN addresses AS Address ON
				Customer.id = Address.foreign_key AND Address.model = 'Customer') LEFT OUTER JOIN contacts AS Contact ON Customer.id = Contact.foreign_key AND Contact.model = 'Customer') LEFT OUTER JOIN
				users AS Creator ON Customer.account_rep_id = Creator.id
				WHERE " . $searchCriteria;
		/*
				Customer.name LIKE '%" . $search . "%' OR
				Customer.phone_1_number LIKE '%" . $search . "%' OR
				Address.line1 LIKE '%" . $search . "%' OR
				Address.line2 LIKE '%" . $search . "%' OR
				Address.city LIKE '%" . $search . "%' OR
				Address.st_prov LIKE '%" . $search . "%'";
		*/
		$result = $this->query($sql);
		$result_list = array();
		if(!empty($result)) {
			foreach($result as $key => $data) {
				$result_list[$data['Customer']['id']] = $data['Customer']['id'];
			}
		}
		return $result_list;
	}
	
	public function getCustomerStats($id) {
		if(is_array($id)) {
			$customer_list = $id;
			$where_job = "(Job.customer_id = " . implode(' OR Job.customer_id = ', array_flip($id)) . ")";
			$where_quote = "(Quote.customer_id = " . implode(' OR Quote.customer_id = ', array_flip($id)) . ")";
		} else {
			$customer_list[$id] = $id;
			$where_job = "Job.customer_id = " . $id;
			$where_quote = "Quote.customer_id = " . $id;
		}
		
		/** INVOICE **/
		$invoice_stats = $this->__getCustomerInvoiceStats(array_flip($customer_list));
		
		// Prep $customer_list
		foreach($customer_list as $key=>$customer_name) {
			$name = $customer_name;
			$customer_list[$key] = array();
			$customer_list[$key]['customer_name'] = $name;
			$customer_list[$key]['InvoiceStats'] = array();
			
			// Combine the the Invoice stats with the customer record.
			if(!empty($invoice_stats) && array_key_exists($key, $invoice_stats)) {
				$customer_list[$key]['InvoiceStats'] = $invoice_stats[$key];
			} 
			
			/*
			 * Last Contact... Obtain a list of all the contacts associated with the Customer.
			 * Use that data to obtain the date of the last know contact.
			 */
			$customer_list[$key]['last_contact'] = 0;
			$customer_list[$key]['total_sales'] = 0;
			$customer_list[$key]['active_jobs'] = 0;
			$customer_list[$key]['order_count'] = 0;
			$customer_list[$key]['order_count_past_12'] = 0;
			$customer_list[$key]['order_amount'] = 0;
			$customer_list[$key]['orders_generated_without_quote'] = 0;
			$customer_list[$key]['orders_total_generated_without_quote'] = 0;
			$customer_list[$key]['active_quotes'] = 0;
			$customer_list[$key]['quote_count'] = 0;
			$customer_list[$key]['quote_amount'] = 0;
		}
	
		/** ORDERS **/
		$sql = "SELECT customer_id, SUM(is_active_job) AS number_of_active_jobs, SUM(active_job_total) AS total_for_active_jobs, SUM(is_valid_job) AS number_of_valid_jobs, SUM(is_valid_job_12) AS number_of_valid_jobs_for_past_year, SUM(valid_job_total) AS total_for_valid_jobs, SUM(non_quote_genereated_orders) AS non_quote_genereated_orders, SUM(non_quote_genereated_order_total) AS non_quote_genereated_order_total   
				FROM (
					SELECT 
						Job.customer_id AS customer_id, 
						CASE WHEN (Job.status >= " . ORDER_STATUS_HOLD . " AND Job.status < " . ORDER_STATUS_COMPLETE . ") THEN 1 ELSE 0 END AS is_active_job,
						CASE WHEN (Job.status >= " . ORDER_STATUS_HOLD . " AND Job.status < " . ORDER_STATUS_COMPLETE . ") THEN Job.price_total ELSE 0 END AS active_job_total,
						CASE WHEN (Job.status >= " . ORDER_STATUS_HOLD . ") THEN 1 ELSE 0 END AS is_valid_job, 
						CASE WHEN (Job.status >= " . ORDER_STATUS_HOLD . " && Job.created >= date_sub(now(), interval 12 month)) THEN 1 ELSE 0 END AS is_valid_job_12,
						CASE WHEN (Job.status >= " . ORDER_STATUS_HOLD . ") THEN Job.price_total ELSE 0 END AS valid_job_total,
						CASE WHEN (Job.quote_id IS NULL) THEN 1 ELSE 0 END AS non_quote_genereated_orders, 
						CASE WHEN (Job.quote_id IS NULL) THEN Job.price_total ELSE 0 END AS non_quote_genereated_order_total       
					FROM (SELECT * FROM orders AS Job WHERE " . $where_job . " AND Job.status >= " . ORDER_STATUS_HOLD . ") AS Job
				) AS Stats GROUP BY customer_id";
		$orders = $this->query($sql);
		if(!empty($orders)) {
			foreach($orders as $order) { 
				$customer_list[$order['Stats']['customer_id']]['total_sales'] = $order[0]['total_for_valid_jobs'];
				$customer_list[$order['Stats']['customer_id']]['active_jobs'] = $order[0]['number_of_active_jobs'];
				$customer_list[$order['Stats']['customer_id']]['order_count'] = $order[0]['number_of_valid_jobs'];
				$customer_list[$order['Stats']['customer_id']]['order_count_past_12'] = $order[0]['number_of_valid_jobs_for_past_year'];
				$customer_list[$order['Stats']['customer_id']]['order_amount'] = $order[0]['total_for_valid_jobs'];
				$customer_list[$order['Stats']['customer_id']]['orders_generated_without_quote'] = $order[0]['non_quote_genereated_orders'];
				$customer_list[$order['Stats']['customer_id']]['orders_total_generated_without_quote'] = $order[0]['non_quote_genereated_order_total'];
			}
		}
		
		/** QUOTES **/
		$sql = "SELECT customer_id, SUM(is_active_quote) AS number_of_active_quotes, SUM(active_quote_total) AS total_for_active_quotes, SUM(is_valid_quote) AS number_of_valid_quotes, SUM(valid_quote_total) AS total_for_valid_quotes 
				FROM (
					SELECT 
						Quote.customer_id AS customer_id, 
						CASE WHEN (Quote.status >= " . QUOTE_STATUS_UNSUBMITTED . " AND Quote.status < " . QUOTE_STATUS_SOLD . ") THEN 1 ELSE 0 END AS is_active_quote,
						CASE WHEN (Quote.status >= " . QUOTE_STATUS_UNSUBMITTED . " AND Quote.status < " . QUOTE_STATUS_SOLD . ") THEN Quote.price_total ELSE 0 END AS active_quote_total,
						CASE WHEN (Quote.status >= " . QUOTE_STATUS_INACTIVE . ") THEN 1 ELSE 0 END AS is_valid_quote, 
						CASE WHEN (Quote.status >= " . QUOTE_STATUS_INACTIVE . ") THEN Quote.price_total ELSE 0 END AS valid_quote_total 
					FROM (SELECT * FROM quotes AS Quote WHERE " . $where_quote . " AND Quote.status >= " . QUOTE_STATUS_INACTIVE . ") AS Quote
				) AS Stats GROUP BY customer_id";
		$quotes = $this->query($sql);
		if(!empty($quotes)) {
			foreach($quotes as $quote) {
				$customer_list[$quote['Stats']['customer_id']]['active_quotes'] = $quote[0]['number_of_active_quotes'];
				$customer_list[$quote['Stats']['customer_id']]['quote_count'] = $quote[0]['number_of_valid_quotes'];
				$customer_list[$quote['Stats']['customer_id']]['quote_amount'] = $quote[0]['total_for_valid_quotes'];
			}
		}
		
		/** QUOTE TO ORDER RATIO **/
		if(!empty($customer_list)) {
			foreach($customer_list as $key=>$data) {
				$customer_list[$key]['Ratio'] = $this->__CalculateRatio($data);
				$customer_list[$key]['Score'] = $this->__calculateScore($customer_list[$key]);
			}
		}
		return $customer_list;
	}
	
	private function __calculateScore($data) {
		$result['sales'] = 0;
		$result['productivity'] = 0;
		$result['payment'] = 0;
		$result['score'] = 0;
		
		if(!empty($data['InvoiceStats'])) {
			$result['productivity'] = $data['InvoiceStats']['score_productivity']/10;
			if($result['productivity'] > 10) {
				$result['productivity'] = 10;
			} else if($data['InvoiceStats']['score_productivity'] < 10) {
				$result['productivity'] = 0;
			}
			$result['payment'] = $data['InvoiceStats']['score_payment'];
		}
		if(!empty($data['Ratio'])) {
			$sales_score = 10;
			if($data['Ratio']['score'] < 10) {
				$sales_score = $data['Ratio']['score'];
			}
			$result['sales'] = $sales_score;
		}
	
		$result['score'] = number_format(($result['productivity'] + $result['payment'] + $result['sales'])/3, 1);
		return $result;
	}
	
	private function __CalculateRatio($data) {
		$result['by_count'] = 0;
		$result['by_amount'] = 0;
		$result['score'] = 0;
		
		/* BY COUNT */
		$count_quote = $data['quote_count'] + $data['orders_generated_without_quote'];
		$count_order = $data['order_count'];
		if(!empty($count_quote)) {
			$ratio = $count_order/$count_quote;
			$result['by_count'] = $this->__ratioDisplay($ratio);
		}	
		
		
		/* BY AMOUNT */
		$amount_quote = $data['quote_amount'] + $data['orders_total_generated_without_quote'];
		$amount_order = $data['order_amount'];
		if(!empty($amount_quote)) {
			$ratio = $amount_order/$amount_quote;
			$result['by_amount'] = $this->__ratioDisplay($ratio);
			$result['score'] = number_format($ratio * 10, 1);
		}
		
		return $result;
	}
	
	private function __ratioDisplay($ratio) {
		$perc = $ratio * 100;
		$remander = $perc % 100;
		if($remander > 0) {
			$ratio = number_format($ratio, 2);
		} else {
			$ratio = number_format($ratio, 0);
		}
		return '1 : ' . $ratio . ' (' . number_format($perc, 0) . '%)';
	}
	
	public function getAssociatedAddresses($id, $status=1) {
		$results = null;
		$conditions['Address.model'] = 'Customer';
		$conditions['Address.foreign_key'] = $id;
		$conditions['Address.status'] = $status;
		$order = array('Address.primary DESC', 'AddressType.name ASC', 'Address.name ASC', 'Address.created ASC');
		$results = $this->Address->find('all', array('conditions' => $conditions, 'order' => $order));
		return $results;
	}
	
	public function getAssociatedContacts($id) {
		$results = null;
		$conditions['Contact.model'] = 'Customer';
		$conditions['Contact.status'] = array(-1, 1);
		$conditions['Contact.foreign_key'] = $id;
		$order = array('Contact.name_first ASC', 'Contact.name_last ASC');
		$contain = array(
			//'CommunicationLog' => array('order' => array('CommunicationLog.date_communication DESC', 'CommunicationLog.created DESC'), 'User'),
			'Address',
		);
		$results = $this->Contact->find('all', array('conditions' => $conditions, 'order' => $order, 'contain' => $contain));
		return $results;
	}
	
	public function getAssociatedQuotes($id, $active = null) {
		$results = null;
		$conditions['Quote.customer_id'] = $id;
		$conditions['Quote.status >'] = QUOTE_STATUS_UNSAVED;
		if(!empty($active)) {
			$conditions['Quote.status'] = $this->Quote->statuses_active;
		}
		$order = array('Quote.created DESC');
		$contain = $this->Quote->contain['index'];
		$results = $this->Quote->find('all', array('conditions' => $conditions, 'order' => $order, 'contain' => $contain));
		return $results;
	}
	
	public function getAssociatedOrders($id, $active = null) {
		$results = null;
		$conditions['Order.customer_id'] = $id;
		$conditions['Order.status >'] = ORDER_STATUS_UNSAVED;
		if(!empty($active)) {
			$conditions['Order.status'] = $this->Order->statuses_active;
		}
		$order = array('Order.created DESC');
		$contain = $this->Order->contain['index'];
		$results = $this->Order->find('all', array('conditions' => $conditions, 'order' => $order, 'contain' => $contain));
		return $results;
	}
	
	public function getAssociatedInvoices($id) {
		$results = null;
		$conditions['Invoice.customer_id'] = $id;
		$order = array('Invoice.created DESC');
		$contain = $this->Invoice->contain['index'];
		$results = $this->Invoice->find('all', array('conditions' => $conditions, 'order' => $order, 'contain' => $contain));
		return $results;
	}
	
	public function searchCustomerLike($search) {
		$search = str_replace("'", "%", $search);				
		$conditions = "Customer.name LIKE '%" . $search . "%'
				Order By Customer.name ASC, Contact.primary DESC, Address.primary DESC, Address.address_type_id DESC"; // OR 
				//Contact.name_first LIKE '%" . $search . "%' OR
				//Contact.name_last LIKE '%" . $search . "%' OR
				//Contact.company_name LIKE '%" . $search . "%'";
		return $this->__customerSQL($conditions);
	}
	
	public function searchCustomerId($id) { 
		$conditions = "Customer.id = " . $id; 
		return $this->__customerSQL($conditions);
	}
	
	private function __customerSQL($conditions) {
		$sql = "SELECT Customer.id, Customer.name, Customer.phone_1_number, Customer.phone_2_number, Customer.phone_3_number, Contact.name_first, Contact.name_last, Contact.company_name, Contact.phone_1_number, Contact.phone_2_number, Contact.phone_3_number, Address.line1, Address.line2, Address.city, Address.st_prov, Address.zip_post FROM
				(((customers AS Customer LEFT OUTER JOIN users AS AccountRep ON Customer.account_rep_id = AccountRep.id) LEFT OUTER JOIN addresses AS Address ON
				Customer.id = Address.foreign_key AND Address.model = 'Customer') LEFT OUTER JOIN contacts AS Contact ON Customer.id = Contact.foreign_key AND Contact.model = 'Customer') LEFT OUTER JOIN
				users AS Creator ON Customer.account_rep_id = Creator.id AND Contact.primary = 1
				WHERE " . $conditions;
	
		return $this->query($sql);
	}
	
	public $order_estimate_sql = "
		SELECT * FROM (
			SELECT LineItem.order_id, SUM(materials_cost_dollars * qty) AS materials_cost_dollars, SUM(equipment_cost_dollars * qty) AS equipment_cost_dollars, SUM(other_cost_dollars * qty) AS other_cost_dollars, SUM(price_unit * qty) AS total_cost
			FROM (
				SELECT * FROM order_line_items AS OrderLineItem) AS LineItem GROUP BY LineItem.order_id
			) AS LineItem 
		LEFT OUTER JOIN (
			SELECT Labor.order_id AS labor_order_id, SUM(Labor.labor_cost_hours * Labor.labor_qty) as order_labor_hours,  SUM(Labor.labor_cost_dollars) as order_labor_dollars  
			FROM (
				SELECT OrderLineItemLaborItem.* FROM order_line_items AS OrderLineItem LEFT OUTER JOIN order_line_item_labor_items AS OrderLineItemLaborItem ON OrderLineItem.id = 	OrderLineItemLaborItem.order_line_item_id) AS Labor GROUP BY Labor.order_id
			) AS Labor 
		ON LineItem.order_id = Labor.labor_order_id";
	
	private function __getCustomerInvoiceStats($customerArray) {
		if(empty($customerArray)) {
			return null;
		}
		$where_job = "(Job.customer_id = " . implode(' OR Job.customer_id = ', $customerArray) . ")";
		$sql = "SELECT Stats.customer_id, Stats.customer_name, 
				SUM(num_of_invoices) As num_of_invoices, 
				SUM(Stats.total_invoiced) As total_invoiced,  
				SUM(Stats.total_contracted_price) As total_contracted_price, 
				SUM(Stats.days_it_took_to_pay) As days_it_took_to_pay, 
				SUM(Stats.days_past_due_date) As days_past_due_date,
				SUM(Stats.invoices_amount_paid) As invoices_amount_paid,
				SUM(Stats.outstanding_invoices_amount) As outstanding_invoices_amount,
				SUM(Stats.outstanding_invoices_count) As outstanding_invoices_count,
				SUM(Stats.materials_cost_dollars) As orders_materials_cost_dollars,
				SUM(Stats.equipment_cost_dollars) As orders_equipment_cost_dollars,
				SUM(Stats.other_cost_dollars) As orders_other_cost_dollars,
				SUM(Stats.total_cost) As orders_total_cost_dollars, 
				SUM(Stats.order_labor_hours) As orders_labor_hours,
				SUM(Stats.order_labor_dollars) As orders_labor_cost_dollars,
				SUM(num_of_invoices_scored) As num_of_invoices_scored,
				TRUNCATE(SUM(score_payment)/SUM(num_of_invoices_scored), 1) As score_payment,
				CASE WHEN (SUM(Stats.total_cost) = 0) THEN 0 ELSE Floor((SUM(Stats.invoices_amount_paid) / SUM(Stats.total_cost))*100) END AS score_productivity  
			FROM (
				SELECT StatsByJob.customer_id,
					StatsByJob.job_id,
					StatsByJob.customer_name, 
					SUM(order_has_invoice) As num_of_invoices,
					SUM(score_payment) As score_payment, 
					SUM(num_of_invoices_scored) As num_of_invoices_scored,
					SUM(StatsByJob.total) As total_invoiced, 
					StatsByJob.price_total As total_contracted_price, 
					SUM(days_it_took_to_pay) As days_it_took_to_pay, 
					SUM(days_past_due_date) As days_past_due_date,
					SUM(invoices_amount_paid) As invoices_amount_paid,
					SUM(outstanding_invoices_amount) As outstanding_invoices_amount,
					SUM(outstanding_invoices_count) As outstanding_invoices_count,
					StatsByJobItem.materials_cost_dollars,
					StatsByJobItem.equipment_cost_dollars,
					StatsByJobItem.other_cost_dollars,
					StatsByJobItem.total_cost, 
					StatsByJobItem.order_labor_hours,
					StatsByJobItem.order_labor_dollars 
				FROM (
					SELECT 
						Invoice.total, 
						Job.price_total, 
						Invoice.date_invoiced, 
						Invoice.date_due, 
						Invoice.date_paid, 
						Job.id As job_id, 
						Job.customer_id, 
						Customer.name AS customer_name, 
						DATEDIFF(Invoice.date_paid, Invoice.date_invoiced) AS days_it_took_to_pay, 
						DATEDIFF(Invoice.date_paid, Invoice.date_due) AS days_past_due_date,
						CASE WHEN (Invoice.id IS NOT NULL) THEN 1 ELSE 0 END AS order_has_invoice,
						CASE WHEN (Invoice.status = " . INVOICE_STATUS_PAID . " OR (Invoice.status = " . INVOICE_STATUS_BILLED . " AND (DATEDIFF(CURDATE(), Invoice.date_due) > 0))) THEN 1 ELSE 0 END As num_of_invoices_scored, 
						CASE WHEN (Invoice.status = " . INVOICE_STATUS_PAID . ") THEN Invoice.total ELSE 0 END AS invoices_amount_paid, 
						CASE WHEN (Invoice.status >= " . INVOICE_STATUS_BILLED . " AND Invoice.status < " . INVOICE_STATUS_PAID . ") THEN Invoice.total ELSE 0 END AS outstanding_invoices_amount,
						CASE WHEN (Invoice.status >= " . INVOICE_STATUS_BILLED . " AND Invoice.status < " . INVOICE_STATUS_PAID . ") THEN 1 ELSE 0 END AS outstanding_invoices_count,
						CASE WHEN (Invoice.status = " . INVOICE_STATUS_PAID . " AND (DATEDIFF(Invoice.date_paid, Invoice.date_due) <= 0)) THEN 10  
							 WHEN (Invoice.status = " . INVOICE_STATUS_PAID . " AND (DATEDIFF(Invoice.date_paid, Invoice.date_due) > 0)) THEN (60 - DATEDIFF(Invoice.date_paid, Invoice.date_due))/6  
							 WHEN (Invoice.status = " . INVOICE_STATUS_BILLED . " AND (DATEDIFF(CURDATE(), Invoice.date_due) > 0)) THEN 0 END AS score_payment  
					FROM (invoices AS Invoice Left Outer Join orders AS Job ON Invoice.order_id = Job.id) LEFT OUTER JOIN customers AS Customer ON Job.customer_id = Customer.id WHERE Invoice.status >= " . INVOICE_STATUS_BILLED . " AND Job.status >= " . ORDER_STATUS_WORK_COMPLETE . " AND " . $where_job . "
				) AS StatsByJob LEFT OUTER JOIN 
					(" . $this->order_estimate_sql . ") As StatsByJobItem 
					ON StatsByJob.job_id = StatsByJobItem.order_id GROUP BY StatsByJob.job_id
			) AS Stats GROUP BY Stats.customer_id"; 
		
		$invoices = $this->query($sql);
		if(empty($invoices)) {
			return null;
		}
		$data = array();
		foreach($invoices as $key=>$invoice) { 
			$data[$invoice['Stats']['customer_id']] = $invoice[0];
		}
		return $data;
	}	
	
	public function buildOrderStats($orderArray, $customer_id = null, $invoice_ids = null) {
		/**
		 * Customer Statistics
		 * Obtain all the Orders that have a status equal to or greater than "work completed".  Link these orders to Invoice, Address, and Customer tables.
		 * Group the orders by customer so each order is summed up.
		 * Calculate fields are include:
		 * days_it_took_to_pay - Difference between the date_paid and the date_invoiced.  
		 * days_past_due_date - Difference between the date_paid and the date_due. (Negative numbers mean invoice was paid before it was due)
		 * num_of_invoices - The number of invoices associated with the order(s) no matter status
		 * num_of_invoices_scored - the number of invoices that have either been paid (status equals INVOICE_STATUS_PAID) or invoices have been billed and the date due is past the current date.
		 * 		This is used when calculating paymant scores.  Thus it does not include Invoices that have been billed but not yet due so as not to penalize a customer for not paying invoices that are not yet due.
		 * invoices_amount_paid - total sum of the invoice amounts on those invoices that are marked as paid.
		 * invoices_paid_count - total number of invoices that are marked as paid.
		 * outstanding_invoices_amount - The invoice total for all invoices that have been billed and not yet paid. 
		 * outstanding_invoices_count - total number of invoices that have been billed and not yet paid.
		 * Score of 10 - Any invoice that has a status of paid and was paid on or before the due date.
		 * Score of 0 - Any invoice that has been billed (and not yet paid) and who's due date has passed.
		 * Score (variable) - If an invoice is paid BUT paid after the due date the following algorithm is used:
		 * 		60 - (number of days paid form when it was due)/6 
		 * 		If paid 30 days late... score is '5'
		 * 		If paid 1 day late... score is 9.(something)
		 * 		As the number of days get closer to 60... score approches 0
		 */
		if(empty($orderArray)) {
			return null;
		}
		$where_job = "(Job.id = " . implode(' OR Job.id = ', $orderArray) . ")";
		if(!empty($customer_id)) {
			$where_job = $where_job . " And Job.customer_id = " . $customer_id;
		}
		$where_invoice = '';
		if(!empty($invoice_ids)) {
			$where_invoice = " AND (Invoice.id = " . implode(' OR Invoice.id = ', $invoice_ids) . ")";
		}
		$sql = "SELECT StatsByJob.customer_id,
					StatsByJob.customer_name, 
					StatsByJob.job_id,
					StatsByJob.job_name,
					StatsByJob.line1,
					StatsByJob.line2,
					StatsByJob.city,
					StatsByJob.st_prov,
					StatsByJob.zip_post,
					SUM(order_has_invoice) As num_of_invoices,
					SUM(score_payment) As score_payment,
					SUM(num_of_invoices_scored) As num_of_invoices_scored,
					SUM(StatsByJob.total) As total_invoiced,
					StatsByJob.price_total As total_contracted_price,
					SUM(days_it_took_to_pay) As days_it_took_to_pay,
					SUM(days_past_due_date) As days_past_due_date,
					SUM(invoices_amount_paid) As invoices_amount_paid,
					SUM(outstanding_invoices_amount) As outstanding_invoices_amount,
					SUM(outstanding_invoices_count) As outstanding_invoices_count,
					StatsByJobItem.materials_cost_dollars,
					StatsByJobItem.equipment_cost_dollars,
					StatsByJobItem.other_cost_dollars,
					StatsByJobItem.total_cost,
					StatsByJobItem.order_labor_hours,
					StatsByJobItem.order_labor_dollars,
					TRUNCATE(SUM(score_payment)/SUM(num_of_invoices_scored), 1) As score_payment,
					CASE WHEN (StatsByJobItem.total_cost = 0) THEN 0 ELSE Floor((SUM(StatsByJob.invoices_amount_paid) / StatsByJobItem.total_cost)*100) END AS score_productivity
				FROM (
					SELECT
						Invoice.total,
						Job.price_total,
						Invoice.date_invoiced,
						Invoice.date_due,
						Invoice.date_paid,
						Job.id As job_id,
						Job.name As job_name, 
						Job.customer_id,
						Customer.name AS customer_name, 
						Address.line1,
						Address.line2,
						Address.city,
						Address.st_prov,
						Address.zip_post,
						DATEDIFF(Invoice.date_paid, Invoice.date_invoiced) AS days_it_took_to_pay,
						DATEDIFF(Invoice.date_paid, Invoice.date_due) AS days_past_due_date, 
						CASE WHEN (Invoice.id IS NOT NULL) THEN 1 ELSE 0 END AS order_has_invoice,
						CASE WHEN (Invoice.status = " . INVOICE_STATUS_PAID . " OR (Invoice.status = " . INVOICE_STATUS_BILLED . " AND (DATEDIFF(CURDATE(), Invoice.date_due) > 0))) THEN 1 ELSE 0 END As num_of_invoices_scored,
						CASE WHEN (Invoice.status = " . INVOICE_STATUS_PAID . ") THEN Invoice.total ELSE 0 END AS invoices_amount_paid,
						CASE WHEN (Invoice.status = " . INVOICE_STATUS_PAID . ") THEN 1 ELSE 0 END AS invoices_paid_count,
						CASE WHEN (Invoice.status >= " . INVOICE_STATUS_BILLED . " AND Invoice.status < " . INVOICE_STATUS_PAID . ") THEN Invoice.total ELSE 0 END AS outstanding_invoices_amount,
						CASE WHEN (Invoice.status >= " . INVOICE_STATUS_BILLED . " AND Invoice.status < " . INVOICE_STATUS_PAID . ") THEN 1 ELSE 0 END AS outstanding_invoices_count,
						CASE WHEN (Invoice.status = " . INVOICE_STATUS_PAID . " AND (DATEDIFF(Invoice.date_paid, Invoice.date_due) <= 0)) THEN 10
							 WHEN (Invoice.status = " . INVOICE_STATUS_PAID . " AND (DATEDIFF(Invoice.date_paid, Invoice.date_due) > 0)) THEN (60 - DATEDIFF(Invoice.date_paid, Invoice.date_due))/6
							 WHEN (Invoice.status = " . INVOICE_STATUS_BILLED . " AND (DATEDIFF(CURDATE(), Invoice.date_due) > 0)) THEN 0 END AS score_payment
					FROM ((orders AS Job Left Outer Join invoices AS Invoice ON Invoice.order_id = Job.id) LEFT OUTER JOIN addresses AS Address ON Job.address_id = Address.id) LEFT OUTER JOIN customers AS Customer ON Job.customer_id = Customer.id WHERE Job.status >= " . ORDER_STATUS_WORK_COMPLETE . " AND " . $where_job . $where_invoice . " ORDER BY Job.name ASC 
				) AS StatsByJob LEFT OUTER JOIN
					(" . $this->order_estimate_sql . ") As StatsByJobItem
					ON StatsByJob.job_id = StatsByJobItem.order_id GROUP BY StatsByJob.job_id";
		
		$results = $this->query($sql);
		return $results;
	}
	
	public function getActiveCustomerList($date_range = null, $field = 'name') {
		// Mainly used for reports
		$fields = array('Order.customer_id', 'Customer.'.$field);
		$contain = array('Customer');
		$order = array('Customer.name ASC');
		$conditions = array('Order.status >= ' => ORDER_STATUS_NEW, 'Order.status < ' => ORDER_STATUS_WORK_COMPLETE, 'NOT' => array('Order.customer_id' => null));
		if(!empty($date_range)) {
			$conditions = array_merge($conditions, $date_range);
		}				
		$list = $this->Order->find('list', array('fields' => $fields, 'conditions' => $conditions, 'contain' => $contain, 'order' => $order));
		unset($list[0]);
		return $list;
	}
	
	public function getCustomerList($date_range = null, $field = 'name') {
		// Mainly used for reports
		$fields = array('Order.customer_id', 'Customer.'.$field);
		$contain = array('Customer');
		$order = array('Customer.name ASC');
		$conditions = array('Order.status >= ' => ORDER_STATUS_NEW, 'Order.status <= ' => ORDER_STATUS_WORK_COMPLETE, 'NOT' => array('Order.customer_id' => null));
		if(!empty($date_range)) {
			$conditions = array_merge($conditions, $date_range);
		}
		$list = $this->Order->find('list', array('fields' => $fields, 'conditions' => $conditions, 'contain' => $contain, 'order' => $order));
		unset($list[0]);
		return $list;
	}
	
	public function getQuickbooksFormated($id) {
		$data['name'] = null;
		$data['company_name'] = null;
		$data['contact'] = null;
		$data['billing_addr1'] = null;
		$data['billing_addr2'] = null;
		$data['billing_city'] = null;
		$data['billing_state'] = null;
		$data['billing_post_code'] = null;
		$data['billing_country'] = null;
		$data['phone'] = null;
		$data['alt_phone'] = null;
		$data['fax'] = null;
		$data['email'] = null;
		
		
		$result = $this->getById($id, 'default');
		if(!empty($result)) {
			// Is the there a contact.
			$contact = null;
			$contactData = null;
			$email = null;
			if(!empty($result['Contact'])) {
				$contactData = $result['Contact'][0];
				$contact = $contactData['name_first'] . ' ' . $contactData['name_last'];
				$email = $contactData['email'];
			}
			
			// Is the there an address.
			$addr1 = null;
			$addr2 = null;
			$city = null;
			$state = null;
			$post = null;
			$country = null;
			if(!empty($result['Address'])) {
				$addr1 = $result['Address'][0]['line1'];
				$addr2 = $result['Address'][0]['line2'];
				$city = $result['Address'][0]['city'];
				$state = $result['Address'][0]['st_prov'];
				$post = $result['Address'][0]['zip_post'];
				$country = $result['Address'][0]['country'];
			}
			
			// Phones
			$phones = $this->__sortPhoneNum($result['Customer'], $contactData);
		
			$data['name'] = $result['Customer']['name'];
			$data['company_name'] = $result['Customer']['name'];
			$data['contact'] = $contact;
			$data['billing_addr1'] = $addr1;
			$data['billing_addr2'] = $addr2;
			$data['billing_city'] = $city;
			$data['billing_state'] = $state;
			$data['billing_post_code'] = $post;
			$data['billing_country'] = $country;
			$data['phone'] = $phones['main'];
			$data['alt_phone'] = $phones['alt'];
			$data['fax'] = $phones['fax'];
			$data['email'] = $email;
		}
		return $data;
	}
	
	private function __sortPhoneNum($customer, $contact=null) {
		$phone['main'] = null;
		$phone['fax'] = null;
		$phone['alt'] = null;
		
		// $customer.
		$phone = $this->__sortThroughPhoneShit($customer['phone_1_label'], $customer['phone_1_number'], $phone);
		$phone = $this->__sortThroughPhoneShit($customer['phone_2_label'], $customer['phone_2_number'], $phone);
		$phone = $this->__sortThroughPhoneShit($customer['phone_3_label'], $customer['phone_3_number'], $phone);
		
		//$contact
		if(!empty($contact) && (empty($phone['main']) || empty($phone['fax']) || empty($phone['alt']))) {
			$phone = $this->__sortThroughPhoneShit($contact['phone_1_label'], $contact['phone_1_number'], $phone);
			$phone = $this->__sortThroughPhoneShit($contact['phone_2_label'], $contact['phone_2_number'], $phone);
			$phone = $this->__sortThroughPhoneShit($contact['phone_3_label'], $contact['phone_3_number'], $phone);
			
		}
		return $phone;
	}
	
	private function __sortThroughPhoneShit($label, $number, $phone) {
		switch ($label) {
			case 'Office' :
				if(empty($phone['main'])) {
					$phone['main'] = $number;
				} else if(empty($phone['alt'])) {
					$phone['alt'] = $number;
				}
				break;
			case 'Cell' :
				if(empty($phone['main'])) {
					$phone['main'] = $number;
				} else if(empty($phone['alt'])) {
					$phone['alt'] = $number;
				}
				break;
			case 'Home' :
				if(empty($phone['main'])) {
					$phone['main'] = $number;
				} else if(empty($phone['alt'])) {
					$phone['alt'] = $number;
				}
				break;
			case 'Fax' :
				if(empty($phone['fax'])) {
					$phone['fax'] = $number;
				}
				break;
		}
		return $phone;
	}
}
?>