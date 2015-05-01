<?php
App::uses('AppController', 'Controller');
/**
 * Customers Controller
 *
 * @property Customer $Customer
 */
class CustomersController extends AppController {
	public $uses = array('Customer', 'ActionLog');
	public $acoType = ACL_ACO_TYPE_APPLICATION;
	
	public function beforeRender() {
		parent::beforeRender();
		$this->set('title_for_layout', __(Inflector::pluralize(Configure::read('Nomenclature.Customer'))));
		$this->set('searchTypes', $this->Customer->search_options);
	}
	
	public function add_quote($id = null) {
		if (!$result = $this->Customer->getById($id, false)) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$postback = array(
			'Quote' => array(
				'id' => null,
				'account_rep_id' => $result['Customer']['account_rep_id'],
				'project_manager_id' => $this->user['User']['id'],
				'customer_id' => $id,
				'quote_customer_id' => $id,
				'quote_customer_mode' => 'customer',
				'customer_name' => $result['Customer']['name'],
				'status' => QUOTE_STATUS_UNSUBMITTED,
			),
		);
		// Update the Account Rep Id
		if(empty($postback['Quote']['account_rep_id'])) {
			$postback['Quote']['account_rep_id'] = $this->user['User']['id'];
		}
		$this->Session->write('Quotes.postback', $postback);
		$this->redirect(array('controller' => 'quotes', 'action' => 'add'));
	}
	
	public function add_order($id = null) {
		if (!$result = $this->Customer->getById($id, false)) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$postback = array(
			'Order' => array(
				'id' => null,
				'account_rep_id' => $this->user['User']['id'],
				'customer_id' => $id,
				'order_customer_id' => $id,
				'order_customer_mode' => 'customer',
				'customer_name' => $result['Customer']['name'],
				'status' => ORDER_STATUS_NEW,
				'number' => null,
			),
		);
		$this->Session->write('Orders.postback', $postback);
		$this->redirect(array('controller' => 'orders', 'action' => 'add'));
	}
	
	public function add() {
		if ($this->request->is('post')) {
			$this->Customer->create();
			$this->request->data['Customer']['creator_id'] = $this->user['User']['id'];
			
			// Updated to Validate both the Customer and Address model before saving.
			$this->Customer->set($this->request->data);
			$valid=$this->Customer->validates($this->request->data);
			// Setup validation for Address data
			if (empty($this->request->data['Customer']['id'])) {
				#$this->Customer->Address->set($this->request->data);
				#$valid=$this->Customer->Address->validates($this->request->data) && $valid;
			}
			if($valid) {
				if ($this->Customer->save($this->request->data)) {
					$this->ScreenMessage->success(__('save_true'));
					
					/********************/
					/* Action log start */
					if(empty($this->request->data['Customer']['id'])) {
						$this->request->data['Customer']['id'] = $this->Customer->getLastInsertID();
					}
		
					$this->redirect(array('action' => 'edit', $this->Customer->getLastInsertID()));
				} else {
					$this->ScreenMessage->error(__('save_false'));
				}
			} else {
				$this->ScreenMessage->error(__('save_false'));
			}
		} else {
			$this->request->data['Customer']['id'] = null;
			$this->request->data['Customer']['company_id'] = Configure::read('Company.Company.id');
			$this->request->data['Customer']['status'] = STATUS_ACTIVE;
			$this->request->data['Address']['address_type_id'] = ADDRESS_TYPE_ID_MAIN;
			$primaryAddress = $this->Session->read('Application.address.primary');
			$this->request->data['Address']['st_prov'] = $primaryAddress['Address']['st_prov'];
		}
		$this->set('accountReps', null);
		$this->render('edit');
	}
	
	public function edit($id = null) {
		if (!$result = $this->Customer->getById($id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));				
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Customer->save($this->request->data)) {
				
				/********************/
				/* Action log start */
				#$this->__action_log('edit', $this->request->data);
				/* END Action Log   */
				/********************/
				
				$this->ScreenMessage->success(__('save_true'));
				$this->redirect(array('action' => 'edit', $id));
			} else {
				$this->ScreenMessage->error(__('save_false'));
			}
		}
		$this->request->data = Set::merge($result, $this->request->data);
		$this->set('accountReps', null);
	}
	
	public function index($letter = null) {
		$this->Customer->Behaviors->disable('CompanyEntity');
		if ($this->request->is('post') || $this->request->is('put')) {
			// Reset Pagination to first page
			$this->request->params['named']['page'] = 1;
				
			// Remove the search criteria
			$this->Session->delete('Customer.search');
			$this->Session->delete('Customer.searchCriteria');
			if(!empty($this->request->data['SearchIndex']['keyword'])) {
				// Add the search criteria to the session
				$this->Session->write('Customer.search', $this->request->data['SearchIndex']['keyword']);
				$this->Session->write('Customer.searchCriteria', $this->request->data['SearchIndex']['criteria']);
			}
		}
		
		$conditions = array();
		if (!empty($this->params['named']) && array_key_exists('status', $this->params['named'])) {
			$conditions['Customer.status'] = $this->params['named']['status'];
			$this->set('currentStatus', $this->params['named']['status']);
		}
		$limit = Configure::read('Paginate.list.limit');
		if (!empty($letter)) {
			$conditions['Customer.name LIKE'] = $letter.'%';
			$this->set('currentLetter', $letter);
			$limit = Configure::read('Paginate.long.limit');
		}
		// Check if the session contains any search criteria.
		$search = $this->Session->read('Customer.search');
		$searchCriteria = $this->Session->read('Customer.searchCriteria');
		if(empty($searchCriteria)) {
			$searchCriteria = 'name';
		}
		$search_conditions = null;
		if(!empty($search)) {
			$search_conditions = $this->Customer->constructIndexSearchConditions($search, $searchCriteria);
			$conditions = array_merge($conditions, array('Customer.id' => false));
			if(!empty($search_conditions)) {
				$conditions = array_merge($conditions, array('Customer.id' => $search_conditions));
			}
		}
		$this->paginate = array(
			'limit' => $limit,
			'conditions' => $conditions,
			'contain' => $this->Customer->contain($this->Customer->contain['index']),
			'order' => 'Customer.name asc',
		);
		$this->set('search_keyword', $search);
		$this->set('search_criteria', $searchCriteria);
		$this->set('results', $this->paginate('Customer'));
		$this->set('alphaPagination', true);
	}
	
	private function __format_note_entry($data=null) {
		$view = new View($this);
        $time = $view->loadHelper('Time');
        $current_time = $time->format('Y-m-d h:ia', time(), false, $this->user['UserProfile']['timezone']);
         
        $view = new View($this);
        $name = $view->loadHelper('Web');
		$uesr_name = $name->humanName($this->user['User'], 'first_initial'); // substr($this->user['User']['name_first'], 0, 1).'. '.$this->user['User']['name_last'];
		
		// Update public notes.
		$this->Customer->id = $data['Customer']['id'];
		$db_notes = $this->Customer->field('notes');
		return $current_time . ' :: ' . $uesr_name . ' :: ' . $data['Customer']['notes_new'] . "\r\n" . $db_notes;
	}
	
	public function view($id = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			if(!empty($this->request->data['Customer']['id']) && !empty($this->request->data['Customer']['notes_new'])) {
				// Timestamp notes.
				if(!empty($this->request->data['Customer']['notes_new'])) {
					$this->request->data['Customer']['notes'] = $this->__format_note_entry($this->data);
				}
				
				if ($this->Customer->save($this->request->data)) {
					$this->request->data['Customer']['notes_new'] = null;
					$this->ScreenMessage->success(__('save_true'));
					#$this->redirect(array('action' => 'view', $id));
				} else {
					$this->ScreenMessage->error(__('save_false'));
				}
			}
		}
		if ($result = $this->Customer->getById($id, 'default')) {
			$this->set('stats', $this->Customer->getCustomerStats($id));
			$this->set('active_quotes', $this->Customer->getAssociatedQuotes($id, 'active'));
			$this->set('active_orders', $this->Customer->getAssociatedOrders($id, 'active'));
			$this->set('invoices', $this->Customer->Order->Invoice->getInvoicesForCustomer($id, 'active'));
			$this->set('customer', $result);
		} else {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));				
		}
	}
	
	public function generate_vcard($id = null) {
		if ($result = $this->Customer->getById($id, 'default')) {
			$this->set('result', $result);
		} else {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));				
		}
		
		// Execute the Vcard.build() function.
		if(!empty($result)) {
			$card_data = $this->__generate_vcard($result);
			$this->helpers = array('Vcard'=>array('card_data'=>$card_data));
			
		}
	}
	
	public function __generate_vcard($result) {
		$card_data['display_name'] = $result['Customer']['name'];
		$card_data['company'] = $result['Customer']['name'];
		$card_data['url'] = $result['Customer']['website'];
	    $card_data['note'] = $result['Customer']['notes'];
	    $card_data['cell_tel'] = null;
	    $card_data['home_tel'] = null;
      	$card_data['office_tel'] = null;
      	$card_data['fax_tel'] = null;
      	if(!empty($result['Customer']['phone_1_number'])) {
      		switch ($result['Customer']['phone_1_label']) {
      			case 'Cell' :
      				$card_data['cell_tel'] = $result['Customer']['phone_1_number'];
      				break;
      			case 'Home' :
      				$card_data['home_tel'] = $result['Customer']['phone_1_number'];
      				break;
      			case 'Work' :
      				$card_data['office_tel'] = $result['Customer']['phone_1_number'];
      				break;
      			case 'Fax' :
      				$card_data['fax_tel'] = $result['Customer']['phone_1_number'];
      				break;
      		}
      	}
		if(!empty($result['Customer']['phone_2_number'])) {
      		switch ($result['Customer']['phone_2_label']) {
      			case 'Cell' :
      				$card_data['cell_tel'] = $result['Customer']['phone_2_number'];
      				break;
      			case 'Home' :
      				$card_data['home_tel'] = $result['Customer']['phone_2_number'];
      				break;
      			case 'Work' :
      				$card_data['office_tel'] = $result['Customer']['phone_2_number'];
      				break;
      			case 'Fax' :
      				$card_data['fax_tel'] = $result['Customer']['phone_2_number'];
      				break;
      		}
      	}
		if(!empty($result['Customer']['phone_3_number'])) {
      		switch ($result['Customer']['phone_3_label']) {
      			case 'Cell' :
      				$card_data['cell_tel'] = $result['Customer']['phone_3_number'];
      				break;
      			case 'Home' :
      				$card_data['home_tel'] = $result['Customer']['phone_3_number'];
      				break;
      			case 'Work' :
      				$card_data['office_tel'] = $result['Customer']['phone_3_number'];
      				break;
      			case 'Fax' :
      				$card_data['fax_tel'] = $result['Customer']['phone_3_number'];
      				break;
      		}
      	}
 	      	
	    $card_data['first_name'] = null;
      	$card_data['last_name'] = null;
      	$card_data['title'] = null;
     	$card_data['email1'] = null;	      	
	    $card_data['work_extended_address'] = null;
		$card_data['work_address'] = null;
		$card_data['work_city'] = null;
		$card_data['work_state'] = null;
		$card_data['work_postal_code'] = null;
      	if(!empty($result['Address'])) {
      		$address = $result['Address'][0]['line1'];
      		if(!empty($result['Address'][0]['line2'])) {
      			$address = $address . ', ' . $result['Address'][0]['line2'];
      		}
		    $card_data['work_address'] = $address;
		    $card_data['work_city'] = $result['Address'][0]['city'];
		    $card_data['work_state'] = $result['Address'][0]['st_prov'];
		    $card_data['work_postal_code'] = $result['Address'][0]['zip_post'];
      	}
      	
      	return $card_data;
	}

	public function delete($id = null) {
		if (!$result = $this->Customer->getById($id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect($this->Auth->loginRedirect);
		}
		$this->Customer->id = $id;
		if ($this->Customer->saveField('status', 0)) {
			/* Action log start */
			//$this->__action_log('delete', $result);
			/* END Action Log   */
			
			$this->ScreenMessage->success(__('delete_true'));
		} else {
			$this->ScreenMessage->error(__('delete_false'));
		}
		$this->redirect(array('action' => 'index'));
	}
	
	public function delete_contact($customer_id, $contact_id) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect($this->Auth->loginRedirect);
		}
		if (!$contact = $this->Customer->Contact->getById($contact_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect($this->Auth->loginRedirect);
		}
		
		/* Do not delete the contact.... but disable them */
		$this->Customer->Contact->id = $contact_id;
		$this->Customer->Contact->saveField('status', 0, array('validate' => false, 'callbacks' => false));
		
		// Update the status field all addresses with the model = 'Contact', and foreign_key = $customer_id
		$sql = "UPDATE addresses SET status = 0 WHERE model = 'Contact' AND foreign_key = " . $contact_id;
		$this->Customer->query($sql);
		
		/* Action log start */
		#$this->__action_log('delete_contact', $customer, $contact);
		
		$this->ScreenMessage->success(__('delete_true'));
		$this->redirect(array('controller' => 'customers', 'action' => 'contacts', $customer_id));
	}
	
	public function addresses($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('display_mode', 'collapsed');
		$this->set('customer', $customer);
		$this->set('customer_addresses', $this->Customer->getAssociatedAddresses($customer_id));
	}
	
	public function messages($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('customer', $customer);
		$this->set('mode', 'message-all');
		$this->set('employees', $this->Customer->AccountRep->getList('fnln'));
		$this->set('employee_emails', $this->Customer->AccountRep->getList('email'));
		$this->set('messages', $this->Communication->getMessages('Customer', $customer_id));
		$this->set('redirect', 'customer,'.$customer_id);
	}
	
	public function add_address($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('customer', $customer);
		$this->set('customer_addresses', $this->Customer->getAssociatedAddresses($customer_id));
		
		if ($this->request->is('post') || $this->request->is('put')) {
			/*
			 * Validate
			 */
			$this->Customer->Address->set($this->request->data);
			if ($this->Customer->Address->validates()) {
				/*
				 * Determine if the address is being being saved as the primary.  If so, clear out all other primary values...Only one per customer.
				 */
				if(!empty($this->request->data['Address']['primary'])) {
					$this->Customer->Address->clearPrimaryForModel($customer_id, 'Customer');
				}
				
				$this->request->data['Address']['created_by_id'] = $this->user['User']['id'];
				if ($this->Customer->Address->save($this->request->data)) {
					/* Action log start */
					$action = 'edit_address';
					if(empty($this->request->data['Address']['id'])) {
						$this->request->data['Address']['id'] = $this->Customer->Address->getLastInsertID();
						$action = 'add_address';
					}
					$data = array_merge($customer, $this->request->data);
					#$this->__action_log($action, $data);
					/* END Action Log   */
					
					$this->ScreenMessage->success(__('save_true'));
					$this->redirect(array('controller' => Inflector::tableize($this->request->data['Address']['model']), 'action' => 'addresses', $this->request->data['Address']['foreign_key']));
				} else {
					$this->ScreenMessage->error(__('save_false'));
				}
			} else {
				$this->ScreenMessage->error(__('save_false'));
			}
		} else {
			$this->request->data['Address']['address_type_id'] = ADDRESS_TYPE_ID_MAIN;
			$primaryAddress = $this->Session->read('Application.address.primary');
			$this->request->data['Address']['st_prov'] = $primaryAddress['Address']['st_prov'];
		}
		$this->render('addresses');
	}
	
	public function contacts($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('display_mode', 'collapsed');
		$this->set('customer', $customer);
		$this->set('customer_contacts', $this->Customer->getAssociatedContacts($customer_id));
		$this->set('customer_addresses', $this->Customer->getAssociatedAddresses($customer_id));
	}
	
	public function add_contact($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		
		$this->set('customer', $customer);
		$this->set('customer_contacts', $this->Customer->getAssociatedContacts($customer_id));
	
		if ($this->request->is('post') || $this->request->is('put')) {
			/*
			 * Validate
			 */
			$this->Customer->Contact->set($this->request->data);
			if ($this->Customer->Contact->validates()) {
				/*
				 * Determine if the contact is being saved as the primary.  If so, clear out all other primary values...Only one per customer.
				 */
				if(!empty($this->request->data['Contact']['primary'])) {
					$this->Customer->Contact->clearPrimaryForModel($customer_id, 'Customer');
				}
				$this->request->data['Contact']['creator_id'] = $this->user['User']['id'];
				$this->request->data['Contact']['status'] = -1;
				if ($this->Customer->Contact->save($this->request->data)) {
					/* Action log start */
					$action = 'edit_contact';
					if(empty($this->request->data['Contact']['id'])) {
						$this->request->data['Contact']['id'] = $this->Customer->Contact->getLastInsertID();
						$action = 'add_contact';
					}
					#$this->__action_log($action, $customer, $this->request->data);
					/* END Action Log   */
					
					$this->ScreenMessage->success(__('save_true'));
					$this->redirect(array('controller' => 'customers', 'action' => 'contacts', $customer_id));
				} else {
					$this->ScreenMessage->error(__('save_false'));
				}
			} else {
				$this->ScreenMessage->error(__('save_false'));
			}
		} else {
			$this->request->data['Address']['address_type_id'] = ADDRESS_TYPE_ID_MAIN;
			$primaryAddress = $this->Session->read('Application.address.primary');
			$this->request->data['Address']['st_prov'] = $primaryAddress['Address']['st_prov'];
		}
		$this->render('contacts');
	}
	
	public function quotes($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('customer', $customer);
		$this->set('customer_quotes', $this->Customer->getAssociatedQuotes($customer_id));
		$this->set('quote_statuses', $this->Customer->Quote->statuses);
		
		if ($this->request->is('post') || $this->request->is('put')) {
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.quotes'));
			$this->redirect(array('controller' => 'customers', 'action' => 'quotes', $customer_id));
		}
	}
	
	public function orders($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('customer', $customer);
		$this->set('customer_orders', $this->Customer->getAssociatedOrders($customer_id));
	
		if ($this->request->is('post') || $this->request->is('put')) {
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.orders'));
			$this->redirect(array('controller' => 'customers', 'action' => 'orders', $customer_id));
		}
	}
	
	public function docs($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('customer', $customer);
		$this->loadModel('Document');
		$this->set('file_uploads', $this->Document->getDocumentsbyModel('Customer', null, $customer_id));
		
		if ($this->request->is('post') || $this->request->is('put')) {
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.orders'));
			$this->redirect(array('controller' => 'customers', 'action' => 'docs', $customer_id));
		}
	}
	
	public function invoices($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('customer', $customer);
		
		// Obtain all the Invoices for the Customer
		$this->set('invoices', $this->Customer->Order->Invoice->getInvoicesForCustomer($customer_id));
		
		// Obtain all orders for the customer with available Income
		$this->set('unbilled', $this->Customer->Order->Invoice->calcUnbilledForCustomer($customer_id));
		
		$this->set('invoice_statuses', $this->Customer->Order->Invoice->statuses);
		if ($this->request->is('post') || $this->request->is('put')) {
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.invoices'));
			$this->redirect(array('controller' => 'customers', 'action' => 'invoices', $customer_id));
		}
	}
	
	public function activity_log($customer_id = null) {
		if (!$customer = $this->Customer->getById($customer_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		$this->set('customer', $customer);
		$this->set('logs', $this->ActionLog->getLogsforCustomer($customer_id));
	
		if ($this->request->is('post') || $this->request->is('put')) {
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.activity_log'));
			$this->redirect(array('controller' => 'customers', 'action' => 'activity_log'));
		}
	}
	
	public function clear_search() {
		// Clear Appropriate Session values
		$this->Session->delete('Customer.search');
		$this->Session->delete('Customer.status');
		$this->Session->delete('Customer.searchCriteria');
		$this->redirect(array('action' => 'index'));
	}
	
	public function import_existing_customers() {
		$this->loadModel('ExistingCustomer');
		$results = $this->ExistingCustomer->find('all');
		
		foreach($results as $result) {
			$data['Customer']['id'] = null;
			$data['Customer']['legacy_id'] = $result['ExistingCustomer']['current_ids'];
			$data['Customer']['name'] = $result['ExistingCustomer']['name'];
			$data['Customer']['phone_1_number'] = $result['ExistingCustomer']['phone_1_number'];
			if(!empty($result['ExistingCustomer']['phone_1_ext'])) {
				$data['Customer']['phone_1_number'] = $data['Customer']['phone_1_number'] . ' ' . $result['ExistingCustomer']['phone_1_ext'];
			}
			$data['Customer']['phone_1_label'] = 'Work';
			$data['Customer']['phone_2_label'] = 'Fax';
			$data['Customer']['phone_2_number'] = $result['ExistingCustomer']['phone_2_number'];
			
			$this->Customer->create();
			if($this->Customer->save($data, false)) {
				$data['Customer']['id'] = $this->Customer->getLastInsertID();
				
				
				// Construct Address.
				$address['Address']['id'] = null;
				$address['Address']['address_type_id'] = 20;
				$address['Address']['line1'] = $result['ExistingCustomer']['address'];
				if(!empty($result['ExistingCustomer']['address3'])) {
					$address['Address']['line2'] = $result['ExistingCustomer']['address2'] . ', ' . $result['ExistingCustomer']['address3'];
				} else {
					$address['Address']['line2'] = $result['ExistingCustomer']['address2'];
				}
				$address['Address']['city'] = $result['ExistingCustomer']['city'];
				$address['Address']['st_prov'] = $result['ExistingCustomer']['state'];
				$address['Address']['zip_post'] = $result['ExistingCustomer']['zip'];
				$address['Address']['primary'] = 1;
				$address['Address']['model'] = 'Customer';
				$address['Address']['foreign_key'] = $data['Customer']['id'];
				$address['Address']['created_by_id'] = 1;
				$this->Customer->Address->create();		
				$this->Customer->Address->save($address, false);
				
				
				// Construct Contact.
				if(!empty($result['ExistingCustomer']['contact_name'])) {
					$contact['Contact']['foreign_key'] = $data['Customer']['id'];
					$contact['Contact']['model'] = 'Customer';
					$contact['Contact']['creator_id'] = 1;
					$contact['Contact']['status'] = -1;
					$contact['Contact']['email'] = $result['ExistingCustomer']['email'];
					$names = explode(' ', $result['ExistingCustomer']['contact_name']);
					$contact['Contact']['name_first'] =  $names[0];
					if(array_key_exists(1, $names)) {
						$contact['Contact']['name_last'] = $names[1];
					}
					
					$data['Contact']['phone_1_number'] = $result['ExistingCustomer']['phone_1_number'];
					if(!empty($result['ExistingCustomer']['phone_1_ext'])) {
						$data['Contact']['phone_1_number'] = $data['Contact']['phone_1_number'] . ' ' . $result['ExistingCustomer']['phone_1_ext'];
					}
					$data['Contact']['phone_1_label'] = 'Work';
					
					$this->Customer->Contact->create();
					$this->Customer->Contact->save($contact, false);
				}
			}
		}
		
		debug('done');
		die;
	}
	
	private function __action_log($action, $data, $data1=null) {
		$subject = $data['Customer']['name'];
		$params['model'] = 'Customer';
		$params['foreign_key'] = $data['Customer']['id'];
		$params['redirect']['controller'] = '';
		$params['redirect']['action'] = '';
		$params['redirect']['params'] = '';
		switch ($action) {
			case 'add' :
				$params['subject'] = 'Create Customer';
				$params['action'] = 'created a customer record for <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'customers';
				$params['redirect']['action'] = 'edit';
				$params['redirect']['params'] = serialize(array($data['Customer']['id']));
				break;
					
			case 'edit' :
				$params['subject'] = 'Update Customer';
				$params['action'] = 'updated the customer record for <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'customers';
				$params['redirect']['action'] = 'edit';
				$params['redirect']['params'] = serialize(array($data['Customer']['id']));
				break;
					
			case 'delete' :
				$params['subject'] = 'Delete Lead';
				$params['action'] = 'deleted ' . $subject . ' as a customer.';
				break;
				
			case 'add_contact' :
				$params['subject'] = 'Create Customer Contact';
				$params['action'] = 'created <b>' . $data1['Contact']['name_first'] . ' ' . $data1['Contact']['name_last'] . '</b> as a contact for customer <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'customers';
				$params['redirect']['action'] = 'contacts';
				$params['redirect']['params'] = serialize(array($data['Customer']['id']));
				break;
				
			case 'edit_contact' :
				$params['subject'] = 'Update Customer Contact';
				$params['action'] = 'updated contact record <b>' . $data1['Contact']['name_first'] . ' ' . $data1['Contact']['name_last']. '</b> for the customer <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'customers';
				$params['redirect']['action'] = 'contacts';
				$params['redirect']['params'] = serialize(array($data['Customer']['id']));
				break;
				
			case 'delete_contact' :
				$params['subject'] = 'Delete Customer Contact';
				$params['action'] = 'deleted contact record <b>' . $data1['Contact']['name_first'] . ' ' . $data1['Contact']['name_last']. '</b> for the customer <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'customers';
				$params['redirect']['action'] = 'contacts';
				$params['redirect']['params'] = serialize(array($data['Customer']['id']));
				break;
			
			case 'add_address' :
				// Get Address type.
				$this->Customer->Address->AddressType->id = $data['Address']['address_type_id'];
				$address_type = $this->Customer->Address->AddressType->field('name');
				$params['subject'] = 'Create Customer Address';
				$params['action'] = 'created <b>' . $data['Address']['name'] . '</b> as a '. $address_type .' address for customer <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'customers';
				$params['redirect']['action'] = 'addresses';
				$params['redirect']['params'] = serialize(array($data['Customer']['id']));
				break;
			
			case 'edit_address' :
				$params['subject'] = 'Update Customer Address';
				$params['action'] = 'updated address <b>' . $data['Address']['name'] . '</b> for the customer <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'customers';
				$params['redirect']['action'] = 'addresses';
				$params['redirect']['params'] = serialize(array($data['Customer']['id']));
				break;
		}
	
		$this->ActionLog->add($params, $this->user);
	}
	
	public function ajax_search_customers() {
		$params = $this->params['named'];
		
		// Validate that all $contact_id, comment, and date have been recieved.
		if (empty($params['search_value'])) {
			return null;
		}
		$this->set('results', $this->Customer->searchCustomerLike($params['search_value']));
		$this->set('success', 1);
		$this->set('error', null);
		$this->render('ajax_search_customers', 'ajax');
	}
	
	public function ajax_get_customer_addresses() {
		$results = null;
		$params = $this->params['named'];
	
		// Validate that all $contact_id, comment, and date have been recieved.
		if (!empty($params['customer_id'])) {
			$results = $this->Customer->getAddresses($params['customer_id'], true, false);
		}
		$this->set('success', 1);
		$this->set('results', $results);
		$this->set('error', '');
		$this->render('ajax_get_customer_addresses', 'ajax');
	}
}