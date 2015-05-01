<?php
App::uses('AppController', 'Controller');
/**
 * Business360s Controller
 *
 * @property User $User
 */
class Business360sController extends AppController {
	public $name = 'Business360s';
	public $uses = array('User', 'ApplicationSetting', 'ActionLog', 'Enumeration');
	public function beforeRender() {
		parent::beforeRender();
		$this->set('controlled_objects', $this->User->getControlledObjects());
		$this->set('aro_groups', $this->User->buildGroupFromAro());
	}
	
	public function settings($id = null) {
		// Check if the session contains the page the user was on.
		$results = $this->Session->read('Business360.settings');
		if(!empty($results)) {
			$action = $results['action'];
			$id = $results['id'];
			
			$this->redirect(array('controller' => 'business360s', 'action' => $action, $id));
		}
	}
	
	public function edit_group_permissions($group_aro_id) {
		$this->loadModel('ArosAco');
		/*
		 * PERMISSIONS
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['Application']['_application_settings'] != 1) {
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		} else {
			if($this->user['Group']['Application']['_application_settings'] != 1) {
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			/*
			 * Befor saving the permissions, delete all the current ArosAco records for the aro_id of the selected user
			 */
			$aro_id = $this->request->data['Aro']['aro_id'];
			if(!empty($aro_id)) {
				$conditions['ArosAco.aro_id'] = $aro_id;
				if($this->ArosAco->deleteAll($conditions)) {
					// After successful delete, Loop through the post-back data, inderting new records
					if(!empty($this->request->data['Aco'])) {
						foreach($this->request->data['Aco'] as $key=>$data) {
							$value = 1;
							if(empty($data)) {
								$value = -1;
							}
							$data = array();
							$data['ArosAco'] = array();
							$data['ArosAco']['id'] = null;
							$data['ArosAco']['aco_id'] = str_replace('aco_', '', $key);
							$data['ArosAco']['aro_id'] = $aro_id;
							$data['ArosAco']['_access'] = $value;
							$this->ArosAco->create();
							if(!$this->ArosAco->save($data)) {
								debug('error'); die;
							}
						}
					}
				}
				$this->ScreenMessage->success(__('save_true'));
		
				/*
				 * Log Activity
				 */
				
			}
		}
		
		$aro_groups = $this->User->buildGroupFromAro();
		$title = null;
		foreach($aro_groups as $key=>$data) {
			if($data['Aro']['id'] == $group_aro_id) {
				$title = $data['Aro']['alias'];
			}
		}
		
		$this->set('action', 'edit_group_permissions');
		$this->set('current_permissions', $this->User->buildGroupPermissions($group_aro_id));
		$this->set('title', $title);
		$this->set('aro_id', $group_aro_id);
		$this->render('settings');
	}
	
	public function edit_group_job_types($aros_id) {
		/*
		 * JOB TYPES
		 * This section is used to map what job types groups can have.  For example, If a user is part of the Production team, they could have 
		 * Assembly, Paint, and/or etc...
		 * 
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		$this->loadModel('ArosAco');
		$this->loadModel('GroupsJobType');
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['Application']['_application_settings'] != 1) {
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		} else {
			if($this->user['Group']['Application']['_application_settings'] != 1) {
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		}
		
		$this->ArosAco->Aro->id = $aros_id;
		$group_id = $this->ArosAco->Aro->field('foreign_key');
		if ($this->request->is('post') || $this->request->is('put')) {
			/*
			 * Befor saving the permissions, delete all the current ArosAco records for the aro_id of the selected user
			 */
			$group_id = $this->request->data['Group']['id'];
			if(!empty($group_id)) {
				$conditions['GroupsJobType.group_id'] = $group_id;
				if($this->GroupsJobType->deleteAll($conditions)) {
					// After successful delete, Loop through the post-back data, inserting new records
					if(!empty($this->request->data['GroupsJobType'])) {
						foreach($this->request->data['GroupsJobType'] as $key=>$value) {
							if(!empty($value)) {
								$data = array();
								$data['GroupsJobType'] = array();
								$data['GroupsJobType']['id'] = null;
								$data['GroupsJobType']['group_id'] = $group_id;
								$data['GroupsJobType']['job_type_id'] = $value;
								$this->GroupsJobType->create();
								if(!$this->GroupsJobType->save($data)) {
									debug('error'); die;
								}	
							}
						}
					}
				}
				$this->ScreenMessage->success(__('save_true'));
		
				/*
				 * Log Activity
				 */
				
			}
		}
		
		$aro_groups = $this->User->buildGroupFromAro();
		$title = null;
		foreach($aro_groups as $key=>$data) {
			if($data['Aro']['id'] == $aros_id) {
				$title = $data['Aro']['alias'];
			}
		}
		$this->set('action', 'edit_group_job_types');
		$this->set('job_types', $this->User->JobType->getList());
		$this->set('current_job_types', $this->User->Group->getJobTypes($group_id));
		$this->set('title', $title);
		$this->set('group_id', $group_id);
		$this->render('settings');
	}
	
	public function edit_enumeration($model) {
		/*
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		} else {
			if($this->user['Group']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			$model = $this->request->data['EnumerationModel']['model'];
			if(array_key_exists('Enumeration', $this->request->data)) {
				$sort_count = 1;
				$success = true;
				foreach($this->request->data['Enumeration'] as $key=>$data) {
					if(!empty($data['name'])) {
						$data['sort'] = $sort_count;
						$data['status'] = 1;
						$data['model'] = $model;
						$this->Enumeration->create();
						if(!$this->Enumeration->save($data)) {
							$success = false;
						}
						$sort_count = $sort_count + 1;
					}
				}
			
				if($success) {
					$this->ScreenMessage->success(__('save_true'));
				} else {
					$this->ScreenMessage->error(__('save_false'));
				}
			}
		} 
		
		$order = array('Enumeration.sort ASC', 'Enumeration.name ASC');
		$conditions = array('Enumeration.model' => $model, 'Enumeration.status' => 1);
		$results = $this->Enumeration->find('all', array('conditions' => $conditions, 'order' => $order, 'callbacks' => false));
		$this->set('model', $model);
		$this->set('data', $results);
		$this->set('action', 'edit_enumeration');
		$this->render('settings');
	}
	
	public function delete_enumeration($id) {
		$data = $this->Enumeration->find('first', array('conditions' => array('id' => $id), 'callbacks' => false));
		
		// Do not delete but set the status to 0;
		$this->Enumeration->id = $id;
		$model = $data['Enumeration']['model'];
		$this->Enumeration->saveField('status', 0);
	
		// Redirect to edit_rates
		$this->redirect(array('action' => 'edit_enumeration', $model));
	}
	
	public function edit_application_settings($type = null) {
		/*
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		} else {
			if($this->user['Group']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		}
		
		if ($this->request->is('post') || $this->request->is('put')) {
			switch($type) {
				case 'contract_language' :
					$this->ApplicationSetting->id = $this->request->data['ApplicationSetting']['id'];
					$this->ApplicationSetting->saveField($this->request->data['ApplicationSetting']['field'], $this->request->data['ApplicationSetting']['data']);
					break;
					
				case 'default' :
					$data['ApplicationSetting']['id'] = $this->request->data['ApplicationSetting']['id'];
					$data['ApplicationSetting']['company_name'] = $this->request->data['ApplicationSetting']['company_name'];
					$data['ApplicationSetting']['company_url'] = $this->request->data['ApplicationSetting']['company_url'];
					$data['ApplicationSetting']['timezone'] = $this->request->data['ApplicationSetting']['timezone'];
					$data['ApplicationSetting']['margin'] = 0;
					if(array_key_exists('margin', $this->request->data['ApplicationSetting']) && !empty($this->request->data['ApplicationSetting']['margin'])) {
						$data['ApplicationSetting']['margin'] = number_format($this->request->data['ApplicationSetting']['margin'], 2);
					}
					//$data['ApplicationSetting']['apply_margin_to_materials'] = $this->request->data['ApplicationSetting']['apply_margin_to_materials'];
					$data['ApplicationSetting']['default_reminder_lead'] = $this->request->data['ApplicationSetting']['default_reminder_lead'];
					$data['ApplicationSetting']['default_reminder_quote'] = $this->request->data['ApplicationSetting']['default_reminder_quote'];
					$data['ApplicationSetting']['default_reminder_order'] = $this->request->data['ApplicationSetting']['default_reminder_order'];
						
					if(array_key_exists('CompanyImage', $this->request->data)) {
						$data['CompanyImage'] = $this->request->data['CompanyImage'];
					}
					$this->ApplicationSetting->save($data);
					break;
			}
		}
		
		$title = null;
		switch($type) {
			case 'company' :
				$title = 'Company';
				$field = $type;
				$data = $this->ApplicationSetting->find('first');
				break;
				
			case 'contract_language' :
				$title = 'Contract Language';
				$field = $type;
				$data = $this->ApplicationSetting->find('first');
				break;
				
			case 'default' :
				$title = 'Company Info';
				$field = $type;
				$this->loadModel('Reminder');
				$this->set('reminder_options', $this->Reminder->options);
				$data = $this->ApplicationSetting->find('first');
				break;
		}
		
		$this->set('action', 'edit_application_settings');
		$this->set('type', $type);
		$this->set('title', $title);
		$this->set('field', $field);
		$this->set('data', $data);
		$this->render('settings');
	}
	
	public function delete_company_logo($image_id = null) {
		if (!$companyLogo = $this->ApplicationSetting->CompanyImage->getById($image_id)) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'edit_application_settings', 'default'));
		}
		
		if($this->user['User']['Application']['_application_settings'] == 1) {			
			// Only perform delete if user has permissions (owner or admin)
			if ($this->ApplicationSetting->CompanyImage->delete($image_id)) {
				$this->ScreenMessage->success(__('delete_true'));
			} else {
				$this->ScreenMessage->error(__('delete_false'));
			}
		}
		
		$this->redirect(array('action' => 'edit_application_settings', 'default'));
	}
	
	/**
	 * LOCATIONS
	 */
	public function edit_locations() {
		$this->loadModel('Location');
		/*
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		} else {
			if($this->user['Group']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		}
		
		$display_mode = '';
		if ($this->request->is('post') || $this->request->is('put')) {
			//$this->request->data['Location']['primary'] = $this->request->data['Address']['primary'];
			$this->request->data['Address']['name'] = $this->request->data['Location']['name'];
			$this->request->data['Location']['address_id'] = $this->request->data['Address']['id'];
			if(empty($this->request->data['Location']['primary'])) {
				$this->request->data['Location']['email'] = '';
				$this->request->data['Location']['phone'] = '';
			}
			if(empty($this->request->data['Location']['billing'])) {
				$this->request->data['Location']['email_billing'] = '';
				$this->request->data['Location']['phone_billing'] = '';
			}
			if(!$this->Location->save($this->request->data)) {
				$display_mode = 'open';
			} else {
				$display_mode = 'collapsed';
			}
		}
		
		$data = $this->Location->find('all', array('conditions' => array('Location.status' => 1)));
		if(!empty($data) && empty($display_mode)) {
			$display_mode = 'collapsed';
		} else {
			// No current Addresses... initialize first address
			$this->request->data['Location']['primary'] = 1;
			$this->request->data['Location']['billing'] = 1;
		}
		
		$this->set('display_mode', $display_mode);
		$this->set('action', 'edit_locations');
		$this->set('title', 'Manage Locations');
		$this->set('data', $data);
		$this->set('addressTypes', $this->Location->Address->AddressType->getList());
		$this->render('settings');
	}
	
	public function delete_location($id) {
		/*
		 * THis function does not delete but sets the Location/Address records status fields to 0
		 */
		$this->loadModel('Location');
		if (!$location = $this->Location->getById($id)) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'edit_locations'));
		}
		$address_id = $location['Location']['address_id'];
		$location_id = $location['Location']['id'];
		$this->Location->id = $location_id;
		$this->Location->saveField('status', 0, array('validate' => false, 'callbacks' => false));
		$this->Location->Address->id = $location_id;
		$this->Location->Address->saveField('status', 0, array('validate' => false, 'callbacks' => false));
		$this->ScreenMessage->success(__('delete_true'));
		
		$this->redirect(array('action' => 'edit_locations'));
	}
	
	/**
	 * LABOR RATES
	 */
	public function edit_rates() {
		$this->loadModel('Rate');
		/*
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		} else {
			if($this->user['Group']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		}
	
		$success = true;
		if ($this->request->is('post') || $this->request->is('put')) {
			if(array_key_exists('Rate', $this->request->data)) {
				$sort_count = 1;
				foreach($this->request->data['Rate'] as $key=>$rate) {
					if(!empty($rate['name'])) {
						$data = $rate;
						$data['sort'] = $sort_count;
						$data['status'] = 1;
						$this->Rate->create();
						if(!$this->Rate->save($data)) {
							$success = false;
						}	
						$sort_count = $sort_count + 1;
					}
				}
				
				if($success) {
					$this->ScreenMessage->success(__('save_true'));
				} else {
					$this->ScreenMessage->error(__('save_false'));
				}
			}
		}
	
		$this->set('action', 'edit_rates');
		$this->set('title', 'Labor Rates');
		$this->data = $this->Rate->find('all', array('conditions' => array('Rate.status' => 1)));
		$this->render('settings');
	}
	
	public function deleteRate($id) {
		// Do not delete but set the status to 0;
		$this->loadModel('Rate');
		$this->Rate->id = $id;
		$this->Rate->saveField('status', 0);
		
		// Redirect to edit_rates
		$this->redirect(array('action' => 'edit_rates'));
	}
	
	/**
	 * LABOR RATES
	 */
	public function edit_licenses() {
		$this->loadModel('License');
		/*
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		} else {
			if($this->user['Group']['Application']['_application_settings'] != 1) {
				$this->ScreenMessage->error(__('no_edit_permission'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));
			}
		}
	
		$success = true;
		if ($this->request->is('post') || $this->request->is('put')) {
			if(array_key_exists('License', $this->request->data)) {
				$sort_count = 1;
				foreach($this->request->data['License'] as $key=>$license) {
					if(!empty($license['name'])) {
						$data = $license;
						$data['sort'] = $sort_count;
						$data['status'] = 1;
						$this->License->create();
						if(!$this->License->save($data)) {
							$success = false;
						}
						$sort_count = $sort_count + 1;
					}
				}
	
				if($success) {
					$this->ScreenMessage->success(__('save_true'));
				} else {
					$this->ScreenMessage->error(__('save_false'));
				}
			}
		}
	
		$this->set('action', 'edit_licenses');
		$this->set('title', 'Licenses');
		$this->data = $this->License->find('all', array('conditions' => array('License.status' => 1), 'order' => array('License.sort ASC')));
		$this->render('settings');
	}
	
	public function delete_licenses($id) {
		// Do not delete but set the status to 0;
		$this->loadModel('License');
		$this->License->delete($id);
	
		// Redirect to edit_rates
		$this->redirect(array('action' => 'edit_licenses'));
	}
	
	public function convertQuoteToQuoteContacts() {
		App::import('Model','Quote');
		$quote = new Quote();
		$results = $quote->find('all');
		if(!empty($results)) {
			foreach($results as $result) {
				// Construct the conversion array.
				$data['QuoteContact']['id'] = null;
				$data['QuoteContact']['model'] = 'Quote';
				$data['QuoteContact']['foreign_key'] = $result['Quote']['id'];
				$data['QuoteContact']['contact_id'] = $result['Quote']['contact_id'];
				#$data['QuoteContact']['contact_type_id'] = $result['Quote'][''];
				#$data['QuoteContact']['primary'] = $result['Quote'][''];
				$data['QuoteContact']['contact_name'] = $result['Quote']['contact_name'];
				$data['QuoteContact']['contact_phone'] = $result['Quote']['contact_phone'];
				$data['QuoteContact']['contact_title'] = $result['Quote']['contact_title'];
				$data['QuoteContact']['contact_email'] = $result['Quote']['contact_email'];
				$quote->QuoteContact->create();
				$quote->QuoteContact->save($data);
			}
		}
	
		return true;
	}
	
	public function convertLineItems() {
		App::import('Model','OrderLineItem');
		$order_line_item = new OrderLineItem();
		$results = $order_line_item->find('all');
		if(!empty($results)) {
			foreach($results as $result) {
				if(!empty($result['OrderLineItem']['labor_cost_dollars'])) {
					// Construct the conversion array.
					$data['OrderLineItemLaborItem']['id'] = null;
					$data['OrderLineItemLaborItem']['order_id'] = $result['OrderLineItem']['order_id'];
					$data['OrderLineItemLaborItem']['order_line_item_id'] = $result['OrderLineItem']['id'];
					$data['OrderLineItemLaborItem']['rate'] = '100.00';
					$data['OrderLineItemLaborItem']['labor_cost_hours'] = $result['OrderLineItem']['labor_cost_hours'];
					$data['OrderLineItemLaborItem']['labor_qty'] = $result['OrderLineItem']['labor_qty'];
					$data['OrderLineItemLaborItem']['labor_cost_dollars'] = $result['OrderLineItem']['labor_cost_dollars'];
					
					$order_line_item->OrderLineItemLaborItem->create();
					$order_line_item->OrderLineItemLaborItem->save($data);
				}
			}
		}
		
		App::import('Model','QuoteLineItem');
		$quote_line_item = new QuoteLineItem();
		$results = $quote_line_item->find('all');
		if(!empty($results)) {
			foreach($results as $result) {
				if(!empty($result['QuoteLineItem']['labor_cost_dollars'])) {
					// Construct the conversion array.
					$data['QuoteLineItemLaborItem']['id'] = null;
					$data['QuoteLineItemLaborItem']['quote_id'] = $result['QuoteLineItem']['quote_id'];
					$data['QuoteLineItemLaborItem']['quote_line_item_id'] = $result['QuoteLineItem']['id'];
					$data['QuoteLineItemLaborItem']['rate'] = '100.00';
					$data['QuoteLineItemLaborItem']['labor_cost_hours'] = $result['QuoteLineItem']['labor_cost_hours'];
					$data['QuoteLineItemLaborItem']['labor_qty'] = $result['QuoteLineItem']['labor_qty'];
					$data['QuoteLineItemLaborItem']['labor_cost_dollars'] = $result['QuoteLineItem']['labor_cost_dollars'];
						
					$quote_line_item->QuoteLineItemLaborItem->create();
					$quote_line_item->QuoteLineItemLaborItem->save($data);
				}
			}
		}
		return true;
	}
	
	public function importContacts() {
		$sql = "SELECT * FROM contact_import";
		debug($sql); die;
		$results = $this->User->query($sql);
		$data = array();
		foreach($results as $result) {
			$name = $result['contact_import']['supervisor'];
			$first = substr ($name ,0 , strpos($name, ' ')); 
			$last = str_replace($first . " ", '', $name);
			$data[$result['contact_import']['id']]['Contact']['company_name'] = $result['contact_import']['name'];
			$data[$result['contact_import']['id']]['Contact']['name_first'] = $first;
			$data[$result['contact_import']['id']]['Contact']['name_last'] = $last;
			$data[$result['contact_import']['id']]['Contact']['email'] = $result['contact_import']['email'];
			$data[$result['contact_import']['id']]['Contact']['phone_1_number'] = $result['contact_import']['phone'];
			$data[$result['contact_import']['id']]['Contact']['phone_1_label'] = 'Office';
			$data[$result['contact_import']['id']]['Contact']['address_id'] = null;
			$data[$result['contact_import']['id']]['Contact']['Address']['id'] = null;
			$data[$result['contact_import']['id']]['Contact']['Address']['model'] = 'Contact';
			$data[$result['contact_import']['id']]['Contact']['Address']['foreign_key'] = null;
			$data[$result['contact_import']['id']]['Contact']['Address']['line1'] = $result['contact_import']['line1'];
			$data[$result['contact_import']['id']]['Contact']['Address']['city'] = $result['contact_import']['city'];
			$data[$result['contact_import']['id']]['Contact']['Address']['st_prov'] = $result['contact_import']['state'];
			$data[$result['contact_import']['id']]['Contact']['Address']['zip_post'] = $result['contact_import']['zip'];
		}
		
		App::import('Model','Contact');
		$contact = new Contact();
		foreach($data as $key=>$record) {
			$contact->Address->create();
			$contact->Address->save($record['Contact']['Address'], array('validate' => false, 'callbacks' => false));
			$address_id = $contact->Address->getLastInsertID();
			
			$record['Contact']['address_id'] = $address_id;
			$contact->create();
			$contact->save($record['Contact'], array('validate' => false, 'callbacks' => false));
		}
	}
	
	
	
	public function updateContactAddressIds() {
		App::import('Model','Contact');
		$contact = new Contact();
		$results = $contact->Address->find('all', array('conditions' => array('Address.model' => 'Contact')));
		if(!empty($results)) {
			foreach($results as $result) {
				$id = $result['Address']['foreign_key'];
				$address_id = $result['Address']['id'];
				
				$contact->id = $id;
				$contact->saveField('address_id', $address_id, array('validate' => false, 'callbacks' => false));
			}
		}
	}
	
	public function merge_customers($base_customer_id, $target_customer_id) {
		$sql = 'UPDATE orders SET orders.customer_id = ' . $base_customer_id . ' WHERE orders.customer_id = ' . $target_customer_id;
		$this->User->query($sql);
		$sql = 'UPDATE quotes SET quotes.customer_id = ' . $base_customer_id . ' WHERE quotes.customer_id = ' . $target_customer_id;
		$this->User->query($sql);
		$sql = 'UPDATE addresses SET addresses.foreign_key = ' . $base_customer_id . ' WHERE addresses.foreign_key = ' . $target_customer_id . ' AND addresses.model = "Customer"';
		$this->User->query($sql);
		$sql = 'UPDATE contacts SET contacts.foreign_key = ' . $base_customer_id . ' WHERE contacts.foreign_key = ' . $target_customer_id . ' AND contacts.model = "Customer"';
		$this->User->query($sql);
		$sql = 'UPDATE documents SET documents.foreign_key = ' . $base_customer_id . ' WHERE documents.foreign_key = ' . $target_customer_id . ' AND documents.model = "Customer"';
		$this->User->query($sql);
		$sql = 'UPDATE messages SET messages.foreign_key = ' . $base_customer_id . ' WHERE messages.foreign_key = ' . $target_customer_id . ' AND messages.model = "Customer"';
		$this->User->query($sql);
		
		$sql = 'UPDATE customers SET customers.status = 0 WHERE customers.id = ' . $target_customer_id;
		$this->User->query($sql);
		
		$sql = 'DELETE FROM customers WHERE customers.id = ' . $target_customer_id;
		$this->User->query($sql);
		
	}
	
	public function updateInvoice() {
		$this->loadModel('Invoice');
		$results = $this->Invoice->find('all', array('contain' => array('Order' => array('Customer'))));
		if(!empty($results)) { 
			foreach($results as $result) { 
				$this->Invoice->id = $result['Invoice']['id'];
				$this->Invoice->saveField('customer_name', $result['Order']['Customer']['name']);
			}
		}
	}
	
	public function transferCommLog() {
		$this->loadModel('Message');
		$this->loadModel('CommunicationLog');
		$results = $this->CommunicationLog->find('all');
		if(empty($results)) {
			return null;
		}
		
		foreach($results as $result) {
			$data['Message']['id'] = null;
			$data['Message']['model'] = $result['CommunicationLog']['model'];
			$data['Message']['parent_model'] = $result['CommunicationLog']['model'];
			$data['Message']['foreign_key'] = $result['CommunicationLog']['foreign_key'];
			$data['Message']['parent_foreign_key'] = $result['CommunicationLog']['foreign_key'];
			$data['Message']['sender_id'] = $result['CommunicationLog']['created_by'];
			$data['Message']['created'] = $result['CommunicationLog']['date_communication'];
			$data['Message']['content'] = $result['CommunicationLog']['comment'];;
			$data['Message']['sender_model'] = 'User';
			$data['Message']['type'] = 'call_outbound';
			$data['Message']['subject'] = 'comment';
			
			$this->Message->create();
			$this->Message->save($data);
		}
	}
	
	public function importInvoiceLaborToMaterial() {
		$this->loadModel('Invoice');
		$results = $this->Invoice->InvoiceLaborItem->find('all');
		if(!empty($results)) {
			foreach($results as $result) {
				$data['InvoiceMaterialItem'] = $result['InvoiceLaborItem'];
				$data['InvoiceMaterialItem']['type'] = 'labor';
				$data['InvoiceMaterialItem']['id'] = null;
				
				$this->Invoice->InvoiceMaterialItem->create();
				$this->Invoice->InvoiceMaterialItem->save($data);
			}
		}
	}
	
	public function import_customers_qb() {
		$sql = "SELECT * FROM customer_imports";
		$results = $this->User->query($sql);
		$data = array();
		$this->loadModel('CustomerImport');
		$this->loadModel('Customer');
		$this->loadModel('Contact');
		foreach($results as $result) { 
			$customer['account_rep_id'] = 3;
			$customer['customer_type_id'] = 4;
			$customer['payment_term_id'] = 7;
			$customer['name'] = $result['customer_imports']['Company'];
			$customer['phone_1_label'] = null;
			$customer['phone_1_number'] = null;
			if(!empty($result['customer_imports']['Phone'])) {
				$customer['phone_1_label'] = 'Office';
				$customer['phone_1_number'] = $result['customer_imports']['Phone'];
			}
			
			$customer['phone_2_label'] = null;
			$customer['phone_2_number'] = null;
			if(!empty($result['customer_imports']['Fax'])) {
				$customer['phone_2_label'] = 'Fax';
				$customer['phone_2_number'] = $result['customer_imports']['Fax'];
			}
			$customer['status'] = 1;
			
			$conditions = array('Customer.name' => $result['customer_imports']['Company']);
			$test = $this->Customer->find('first', array('conditions' => $conditions));
			$customer_id = null;
			
			if(!empty($test)) {
				$customer_id = $test['Customer']['id'];
			} else {
				$this->Customer->create();
				if($this->Customer->save($customer, array('validate' => false, 'callbacks' => false))) {
					$customer_id = $this->Customer->getLastInsertId();
				}
			}
			
			if(!empty($customer_id)) {
				$this->CustomerImport->id = $result['customer_imports']['id'];
				$this->CustomerImport->saveField('customer_id', $customer_id);
				
				/** Contacts **/
				$name = $result['customer_imports']['Contact'];
				if(!empty($name)) {
					// IS the contact already in there?
					$first = substr ($name ,0 , strpos($name, ' '));
					$last = str_replace($first . " ", '', $name);
					$contact_conditions = array('Contact.name_first' => $first, 'Contact.name_last' => $last);
					//$sql_contact = "SELECT contacts.id FROM contacts WHERE name_first = '" . $first . "' AND name_last = '" . $last . "'";
					$contact_check = $this->Contact->find('first', array('fields' => array('Contact.id'), 'conditions' => $contact_conditions));
					debug($contact_conditions);
					if(empty($contact_check)) {
						$contact['Contact']['id'] = null;
						$contact['Contact']['model'] = 'Customer';
						$contact['Contact']['foreign_key'] = $customer_id;
						$contact['Contact']['name_first'] = $first;
						$contact['Contact']['name_last'] = $last;
						$contact['Contact']['email'] = $result['customer_imports']['Email'];
						$contact['Contact']['company_name'] = $result['customer_imports']['Company'];
				
						$contact['Contact']['phone_1_label'] = null;
						$contact['Contact']['phone_1_number'] = null;
						if(!empty($result['customer_imports']['Phone'])) {
							$contact['Contact']['phone_1_label'] = 'Office';
							$contact['Contact']['phone_1_number'] = $result['customer_imports']['Phone'];
						}
						
						$contact['Contact']['phone_2_label'] = null;
						$contact['Contact']['phone_2_number'] = null;
						if(!empty($result['customer_imports']['Fax'])) {
							$contact['Contact']['phone_2_label'] = 'Fax';
							$contact['Contact']['phone_2_number'] = $result['customer_imports']['Fax'];
						}
						$contact['Contact']['active_status'] = 1;
						$contact['Contact']['status'] = -1;
						$contact['Contact']['primary'] = 1;
						debug($contact);
						$this->Contact->create();
						$this->Contact->save($contact, array('validate' => false, 'callbacks' => false));
					}
				}
				
				// SERVICE ADDRESS
				if(strpos($result['customer_imports']['Ship to 4'], ',')) {
					// Assume this string contains the City State Zip
					$address_name = $result['customer_imports']['Ship to 1'];
				
					if(strpos($result['customer_imports']['Ship to 2'], 'ATTN')) {
						$address2 = $result['customer_imports']['Ship to 2'];
						$address = $result['customer_imports']['Ship to 3'];
					} else {
						$address = $result['customer_imports']['Ship to 2'];
						$address2 = $result['customer_imports']['Ship to 3'];
					}
				
					$cityState = $result['customer_imports']['Ship to 4'];
				} else {
					// Assume it is in ['Ship to 3']
					$address_name = $result['customer_imports']['Ship to 1'];
					$address2 = null;
					$address = $result['customer_imports']['Ship to 2'];
					$cityState = $result['customer_imports']['Ship to 3'];
				}
				
				$firstComma = strpos($cityState, ',');
				$city = substr($cityState, 0, $firstComma);
				$stateZip = str_replace ($city . ', ' , '', $cityState);
				$state = 'OR';
				if(strpos($stateZip, 'WA')) {
					$state = 'WA';
				}
				$zip = str_replace ($state . ' ' , '', $stateZip);
				
				$address_service['Address']['model'] = 'Customer';
				$address_service['Address']['foreign_key'] = $customer_id;
				$address_service['Address']['primary'] = 1;
				$address_service['Address']['address_type_id'] = 1;
				$address_service['Address']['name'] = $address_name;
				$address_service['Address']['line1'] = $address;
				$address_service['Address']['line2'] = $address2;
				$address_service['Address']['city'] = $city;
				$address_service['Address']['st_prov'] = $state;
				$address_service['Address']['zip_post'] = $zip;
				$address_service['Address']['country'] = 'USA';
				
				$conditions = array('Address.line1' => $address_service['Address']['line1']);
				$results_service = $this->Contact->Address->find('first', array('conditions' => $conditions));
				if(empty($results_service)) {
					$this->Contact->Address->create();
					if(strpos($address, 'PO') || strpos($address2, 'PO')) {
						$this->Contact->Address->save($address_service, array('callbacks' => false, 'validate' => false));
					} else {
						$this->Contact->Address->save($address_service, array('callbacks' => false, 'validate' => false));
					}
				}
				
				
				
				// Billing ADDRESS
				if(strpos($result['customer_imports']['Bill to 4'], ',')) {
					// Assume this string contains the City State Zip
					$address_name = $result['customer_imports']['Bill to 1'];
						
					if(strpos($result['customer_imports']['Bill to 2'], 'ATTN')) {
						$address2 = $result['customer_imports']['Bill to 2'];
						$address = $result['customer_imports']['Bill to 3'];
					} else {
						$address = $result['customer_imports']['Bill to 2'];
						$address2 = $result['customer_imports']['Bill to 3'];
					}
						
					$cityState = $result['customer_imports']['Bill to 4'];
				} else {
					// Assume it is in ['Bill to 3']
					$address_name = $result['customer_imports']['Bill to 1'];
					$address2 = null;
					$address = $result['customer_imports']['Bill to 2'];
					$cityState = $result['customer_imports']['Bill to 3'];
				}
				
				
				$firstComma = strpos($cityState, ',');
				$city = substr($cityState, 0, $firstComma);
				$stateZip = str_replace ($city . ', ' , '', $cityState);
				$state = 'OR';
				if(strpos($stateZip, 'WA')) {
					$state = 'WA';
				}
				$zip = str_replace ($state . ' ' , '', $stateZip);
				
				$address_billing['Address']['model'] = 'Customer';
				$address_billing['Address']['foreign_key'] = $customer_id;
				$address_billing['Address']['primary'] = 1;
				$address_billing['Address']['address_type_id'] = 2;
				$address_billing['Address']['name'] = $address_name;
				$address_billing['Address']['line1'] = $address;
				$address_billing['Address']['line2'] = $address2;
				$address_billing['Address']['city'] = $city;
				$address_billing['Address']['st_prov'] = $state;
				$address_billing['Address']['zip_post'] = $zip;
				$address_billing['Address']['country'] = 'USA';
				
				$conditions = array('Address.line1' => $address_billing['Address']['line1']);
				$results_billing = $this->Contact->Address->find('first', array('conditions' => $conditions));
				if(empty($results_billing)) {
					if(strpos($address, 'PO') || strpos($address2, 'PO')) {
						$this->Contact->Address->save($address_billing, array('callbacks' => false, 'validate' => false));
					} else {
						$this->Contact->Address->save($address_billing, array('callbacks' => false, 'validate' => false));
					}
				}
			}
		}
	}
}
?>