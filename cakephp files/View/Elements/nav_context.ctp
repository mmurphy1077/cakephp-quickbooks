<?php
$links = array();
$controllerAction = $this->params['controller'].'.'.$this->params['action'];
$alphabet = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
if (empty($class)) {
	$class = 'stats actions';
}
if (empty($wrapper)) {
	$wrapper = null;
}
if (empty($separator)) {
	$separator = null;
}
switch ($this->params['controller']) {
	/**
	 * AclControlledsController
	 */
	case 'acl_controlleds':
		switch ($this->params['action']) {
			case 'add':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => 'acl_controlleds', 'action' => 'index'));
				break;
			case 'edit':
				$links = array(
					$this->Html->link(__('Cancel', true), array('controller' => 'acl_controlleds', 'action' => 'view', $this->request->data['AclControlled']['id'])),
					$this->Html->link(__('Delete', true), array('controller' => 'acl_controlleds', 'action' => 'delete', $this->request->data['AclControlled']['id']), array(), __('delete_confirm')),
				);
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('Add Controlled Object', true), array('controller' => 'acl_controlleds', 'action' => 'add')),
					$this->Html->link(__('List Permissions', true), array('controller' => 'acl_permissions', 'action' => 'index')),
					$this->Html->link(__('List Request Objects', true), array('controller' => 'acl_requesters', 'action' => 'index')),
				);
				break;
			case 'view':
				$links = array(
					$this->Html->link(__('Edit', true), array('controller' => 'acl_controlleds', 'action' => 'edit', $aco['AclControlled']['id'])),
					$this->Html->link(__('Add Controlled Object', true), array('controller' => 'acl_controlleds', 'action' => 'add')),
					$this->Html->link(__('List Permissions', true), array('controller' => 'acl_permissions', 'action' => 'index')),
					$this->Html->link(__('List Request Objects', true), array('controller' => 'acl_requesters', 'action' => 'index')),
					$this->Html->link(__('List Controlled Objects', true), array('controller' => 'acl_controlleds', 'action' => 'index')),
				);
				break;
		}
		break;
	/**
	 * AclPermissionsController
	 */
	case 'acl_permissions':
		switch ($this->params['action']) {
			case 'add':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => 'acl_permissions', 'action' => 'index'));
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('Add New Permission', true), array('controller' => 'acl_permissions', 'action' => 'add')),
					$this->Html->link(__('List Request Objects', true), array('controller' => 'acl_requesters', 'action' => 'index')),
					$this->Html->link(__('List Controlled Objects', true), array('controller' => 'acl_controlleds', 'action' => 'index')),
				);
				break;
		}
		break;
	/**
	 * AclRequestersController
	 */
	case 'acl_requesters':
		switch ($this->params['action']) {
			case 'add':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => 'acl_requesters', 'action' => 'index'));
				break;
			case 'edit':
				$links = array(
					$this->Html->link(__('Cancel', true), array('controller' => 'acl_requesters', 'action' => 'view', $this->request->data['AclRequester']['id'])),
					$this->Html->link(__('Delete', true), array('controller' => 'acl_requesters', 'action' => 'delete', $this->request->data['AclRequester']['id']), array(), __('delete_confirm')),
				);
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('Add Request Object', true), array('controller' => 'acl_requesters', 'action' => 'add')),
					$this->Html->link(__('List Permissions', true), array('controller' => 'acl_permissions', 'action' => 'index')),
					$this->Html->link(__('List Controlled Objects', true), array('controller' => 'acl_controlleds', 'action' => 'index')),
				);
				break;
			case 'view':
				$links = array(
					$this->Html->link(__('Edit', true), array('controller' => 'acl_requesters', 'action' => 'edit', $aro['AclRequester']['id'])),
					$this->Html->link(__('Add Request Object', true), array('controller' => 'acl_requesters', 'action' => 'add')),
					$this->Html->link(__('List Permissions', true), array('controller' => 'acl_permissions', 'action' => 'index')),
					$this->Html->link(__('List Request Objects', true), array('controller' => 'acl_requesters', 'action' => 'index')),
					$this->Html->link(__('List Controlled Objects', true), array('controller' => 'acl_controlleds', 'action' => 'index')),
				);
				break;
		}
		break;
	/**
	 * AccountsController
	 */
	case 'accounts':
		switch ($this->params['action']) {
			case 'add':
			case 'edit':
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('List All '.Inflector::pluralize(Configure::read('Nomenclature.Account'))), array('controller' => 'accounts', 'action' => 'index')),
					$this->Html->link(__('List Inactive '.Inflector::pluralize(Configure::read('Nomenclature.Account'))), array('controller' => 'accounts', 'action' => 'index', 'status' => STATUS_INACTIVE)),
				);
				if ($__user['Group']['id'] == GROUP_ADMINISTRATORS_ID) {
					$links = array_merge($links, array(					
						$this->Html->link(__('Add '.Configure::read('Nomenclature.Contact')), array('controller' => 'contacts', 'action' => 'add_lead')),
						$this->Html->link(__('Add '.Configure::read('Nomenclature.Quote')), array('controller' => 'quotes', 'action' => 'add')),
						$this->Html->link(__('Add '.Configure::read('Nomenclature.Order')), '#'),
					));
				}
				break;
			case 'messages' :
				$links[] = $this->Html->link(__('New Message'), array('controller' => 'messages', 'action' => 'add', 'account', $account['Account']['id']));
				break;
		}
		break;
	/**
	 * AddressesController
	 */
	case 'addresses':
		switch ($this->params['action']) {
			case 'add':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => Inflector::tableize($this->request->data['Address']['model']), 'action' => 'view', $this->request->data['Address']['foreign_key']));				
				break;
			case 'edit':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => Inflector::tableize($this->request->data['Address']['model']), 'action' => 'view', $this->request->data['Address']['foreign_key']));
				if ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID) {
					$links[] = $this->Html->link(__('Delete', true), array('action' => 'delete', $this->request->data['Address']['id']), array(), __('delete_confirm'));
				}				
				break;
		}
		break;
	/**
	 * ContactsController
	 */
	case 'contacts':
		switch ($this->params['action']) {
			case 'add':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => Inflector::tableize($model), 'action' => 'view', $record[$model]['id']));				
				break;
			case 'add_lead':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => 'contacts', 'action' => 'index'));				
				break;
			case 'assign_to_customer':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => 'contacts', 'action' => 'edit_lead', $result['Contact']['id']));
				break;
			case 'edit':
				$links[] = $this->Html->link(__('Cancel', true), array('controller' => 'customers', 'action' => 'view', $record[$model]['id']));
				if ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID) {
					$links[] = $this->Html->link(__('Delete', true), array('action' => 'delete', $this->request->data['Contact']['id'], $model, $record[$model]['id']), array(), __('delete_confirm'));
				}				
				break;
			case 'edit_lead':
				$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Quote'), true), array('action' => 'add_quote', $this->request->data['Contact']['id']));
				if ($permission['enable_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete', true), array('action' => 'delete_lead', $this->request->data['Contact']['id']), array(), __('delete_confirm'));
				}				
				break;
			case 'index':
				$links = array();
				/*
				$links = array(
					$this->Html->link(__('All Active'), array('controller' => 'contacts', 'action' => 'index', 'status' => 'all')),
					$this->Html->link(__('No Contact'), array('controller' => 'contacts', 'action' => 'index', 'status' => CONTACT_ACTIVE_STATUS_NO_CONTACT)),
					$this->Html->link(__('Contact Made'), array('controller' => 'contacts', 'action' => 'index', 'status' => CONTACT_ACTIVE_STATUS_CONTACT_MADE)),
					$this->Html->link(__('Meeting Scheduled'), array('controller' => 'contacts', 'action' => 'index', 'status' => CONTACT_ACTIVE_STATUS_MEETING_SCHEDULED)),
					$this->Html->link(__('Quote Requested'), array('controller' => 'contacts', 'action' => 'index', 'status' => CONTACT_ACTIVE_STATUS_QUOTE_REQUESTED)),
					$this->Html->link(__('Pending'), array('controller' => 'contacts', 'action' => 'index', 'status' => CONTACT_ACTIVE_STATUS_PENDING)),
					$this->Html->link(__('Inactive '.Inflector::pluralize(Configure::read('Nomenclature.Contact'))), array('controller' => 'contacts', 'action' => 'index', 'status' => CONTACT_STATUS_INACTIVE)),
				);
				*/
				break;
			case 'index_sales':
				if ($__user['Group']['id'] == GROUP_ADMINISTRATORS_ID) {
					$links = array(
						$this->Html->link(__('Sales Report'), '#'),
						$this->Html->link(__('Add Lead'), array('controller' => 'contacts', 'action' => 'add_lead')),
						$this->Html->link(__('Add Quote'), array('controller' => 'quotes', 'action' => 'add')),
						$this->Html->link(__('Add Order'), '#'),
					);
				}
				$links[] = $this->Html->link(__('List Customers'), array('controller' => 'customers'));
				break;
		}
		break;
	/**
	 * CustomersController
	 */
	case 'customers':
		switch ($this->params['action']) {
			case 'add':
				#$links[] = $this->Html->link(__('Cancel'), array('controller' => 'customers', 'action' => 'index'));				
				break;
			case 'edit':
				if (!empty($permission['enable_delete'])) {
					$links[] = $this->Html->link(__('Delete', true), array('action' => 'delete', $this->request->data['Customer']['id']), array(), __('delete_confirm'));
				}
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('All '.Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index')),
					$this->Html->link(__('Active '.Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index', 'status' => STATUS_ACTIVE)),
					$this->Html->link(__('Inactive '.Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index', 'status' => STATUS_INACTIVE)),
					$this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Customer')) . ' with Active Jobs'), array('controller' => 'customers', 'action' => 'index', 'status' => STATUS_INACTIVE)),
				);
				break;
			case 'invoices' :
				#if ($permission['can_create_order'] == 1) {
				#	$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Invoice'), true), array('action' => 'add_invoice', $customer['Customer']['id']));
				#}
				break;
			case 'messages' :
				$links[] = $this->Html->link(__('New Message'), array('controller' => 'messages', 'action' => 'add', 'customer', $customer['Customer']['id']));
				break;
			case 'quotes':
				if ($permission['can_create_quote'] == 1) {
					$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Quote'), true), array('action' => 'add_quote', $customer['Customer']['id']));
				}
				break;
			case 'orders':
				if ($permission['can_create_order'] == 1) {
					$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Order'), true), array('action' => 'add_order', $customer['Customer']['id']));
				}
				break;
			
		}
		break;
	/**
	 * InvoicesController
	 */
	case 'invoices':
		switch ($this->params['action']) {
			case 'add':
				break;
			case 'edit':				
				break;
			case 'view':
				if($permission['can_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete'), array('controller' => 'invoices', 'action' => 'delete', $this->data['Invoice']['id']), array(), __('delete_confirm'));
				};
				$links[] = $this->Html->link(__('Print Invoice'), array('controller' => 'invoices', 'action' => 'print_pdf', $this->data['Invoice']['id']));
				$links[] = $this->Html->link(__('Send Invoice'), array('controller' => 'messages', 'action' => 'add', 'order', $this->data['Invoice']['order_id']), array('id' => 'dialog-form-opener'));
				break;
			case 'index':
			case 'index_ready_to_invoice':
				$links = array(
					$this->Html->link(__('Ready to Invoice'), array('controller' => 'invoices', 'action' => 'index_ready_to_invoice')),
					$this->Html->link(__('Submitted for Approval'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_SUBMITTED)),
					$this->Html->link(__('Approved'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_APPROVED)),
					$this->Html->link(__('Billed'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_BILLED)),
					$this->Html->link(__('Void'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_INACTIVE)),
				);
				break;
			case 'index_order':
				if($permission['can_create_invoice'] == 1) {
					$links[] = $this->Html->link(__('Create Invoice'), array('controller' => 'invoices', 'action' => 'add', $order['Order']['id']));
				};
				break;
		}
		break;
		
			
	/**
	 * OrdersController
	 */
	case 'orders':
		switch ($this->params['action']) {
			case 'index':
				/*
				if($permission['view_all_orders'] == 1) {
					$links[] = $this->Html->link(__('All ' . Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index', 'status' => 'all'));
				};
				#if($permission['track_time_only'] == -1 || $permission['view_all_orders'] == 1) {
					$links[] = $this->Html->link(__('My ' . Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index', 'status' => 'my'));
					$links[] = $this->Html->link(__('New ' . Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index', 'status' => 'new'));
					$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Order')) . ' On Hold'), array('controller' => 'orders', 'action' => 'index', 'status' => 'hold'));
					$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Order')) . ' In Process'), array('controller' => 'orders', 'action' => 'index', 'status' => 'in-process'));
					$links[] = $this->Html->link(__('Rough-In Ready'), array('controller' => 'orders', 'action' => 'index', 'status' => 'rough-ready'));
					$links[] = $this->Html->link(__('Closed ' . Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index', 'status' => 'closed'));
					$links[] = $this->Html->link(__('Ready to Bill'), array('controller' => 'orders', 'action' => 'index', 'status' => 'bill'));
					$links[] = $this->Html->link(__('Cancelled ' . Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index', 'status' => 'cancelled'));
					#$links[] = $this->Html->link(__('Show Alerts'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'alerts'));
				#}
				*/
				break;
			case 'job_info':
				break;
			case 'messages' :
			case 'messages_field' :
				$links[] = $this->Html->link(__('New Message'), array('controller' => 'messages', 'action' => 'add', 'order', $order['Order']['id']));
				break;
			case 'labor_hours' :
				/* Does the User have the ability to create labor hours? */
				if($permission['labor_create'] == 1) {
				$links[] = $this->Html->link(__('Add Hours'), array('controller' => 'order_times', 'action' => 'add', $order['Order']['id']));
				}
				break;	
			case 'schedules' :
				/* Does the User have the ability to create labor hours? */
				if($permission['schedule_create'] == 1) {
					$links[] = $this->Html->link(__('Add Schedule'), array('controller' => 'orders', 'action' => 'add_schedule', $order['Order']['id']));
				}
				break;
			case 'add_schedule' :
			case 'edit_schedule' :
				/* Does the User have the ability to create labor hours? */
				$links[] = $this->Html->link(__('Cancel'), array('controller' => 'orders', 'action' => 'schedules', $order['Order']['id']));
				break;
		}
		break;
		
	/**
	 * OrderMaterialsController
	 */
	case 'order_expenses':
	case 'order_materials':
		switch ($this->params['action']) {
			case 'index_purchases' :
				$links[] = $this->Html->link(__('Add'), array('controller' => 'order_materials', 'action' => 'add_purchase', $order['Order']['id']));
				break;
			case 'add' :
			case 'edit' :
				$links[] = $this->Html->link(__('Cancel'), array('controller' => 'order_materials', 'action' => 'index', $order['Order']['id']));
				break;
			case 'add_purchase' :
			case 'edit_purchase' :
				$links[] = $this->Html->link(__('Cancel'), array('controller' => 'order_materials', 'action' => 'index_purchases', $order['Order']['id']));
				break;
			default:
				$links[] = $this->Html->link(__('Add'), array('controller' => 'order_materials', 'action' => 'add', $order['Order']['id']));
				break;
		}
		break;
	
	/**
	 * OrderRequirementsController
	 */
	case 'order_requirements':
		switch ($this->params['action']) {
			case 'edit':
				$links[] = $this->Html->link(__(Configure::read('Nomenclature.Order').' Details'), array('controller' => 'orders', 'action' => 'view', $order_id, 'job-items'));
				$links[] = $this->Html->link(__('List '.Inflector::pluralize(Configure::read('Nomenclature.Quote'))), array('controller' => 'quotes', 'action' => 'index'));
				$links[] = $this->Html->link(__('List '.Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index'));
				break;
		}
		break;
		
	/**
	 * OrderTimesController
	 */
	case 'order_times':
		switch ($this->params['action']) {
			case 'add':
			case 'index':
				$links[] = $this->Html->link(__('add'), array('controller' => 'order_times', 'action' => 'add', $order['Order']['id']));
				break;
			case 'edit':
				$links[] = $this->Html->link(__('cancel'), array('controller' => 'order_times', 'action' => 'index', $order['Order']['id']));
				break;
		}
		break;
		
	/**
	 * QuotesController
	 */
	case 'quotes':
		switch ($this->params['action']) {
			case 'add':
			case 'add_step1':
				if (!empty($this->request->data['Quote']['id'])) {
					$links[] = $this->Html->link(__('Back to '.Configure::read('Nomenclature.Quote')), array('controller' => 'quotes', 'action' => 'view', $this->request->data['Quote']['id']));
				} else {
					$links[] = $this->Html->link(__('Cancel'), array('controller' => 'quotes', 'action' => 'index'), array(), __('Your Quote has not been saved yet. Are you sure you want to cancel?'));
				}
				break;
			case 'add_step2':
				if (!empty($this->request->data['Quote']['id'])) {
					$links[] = $this->Html->link(__('Back to '.Configure::read('Nomenclature.Quote')), array('controller' => 'quotes', 'action' => 'view', $this->request->data['Quote']['id']));
				} else {
					$links = array(
						$this->Html->link(__('Cancel'), array('controller' => 'quotes', 'action' => 'index'), array(), __('Your Quote has not been saved yet. Are you sure you want to cancel?')),
					);
				}
				break;
			case 'edit':
				$links[] = $this->Html->link(__('List Quotes'), array('action' => 'index'));
				$links[] = $this->Html->link(__('Cancel'), array('action' => 'view', $this->request->data['Quote']['id']));
				if ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID) {
					$links[] = $this->Html->link(__('Delete'), array('action' => 'delete', $this->request->data['Quote']['id']), array(), __('delete_confirm'));
				}
				break;
			case 'index':
				/*
				$links[] = $this->Html->link(__('My Quotes'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'my'));
				$links[] = $this->Html->link(__('All Quotes'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'all'));
				$links[] = $this->Html->link(__('Unsubmitted Quotes'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'unsubmitted'));
				$links[] = $this->Html->link(__('Submitted'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'submitted'));
				$links[] = $this->Html->link(__('Sold Quotes'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'sold'));
				$links[] = $this->Html->link(__('Inactive Quotes'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'inactive'));
				#$links[] = $this->Html->link(__('Quotes past 30 Days'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'past30'));
				*/
				break;
			case 'messages' :
				$links[] = $this->Html->link(__('New Message'), array('controller' => 'messages', 'action' => 'add', 'quote', $quote['Quote']['id']));
				break;
			case 'view':
				if (!empty($quote['Quote']['customer_id'])) {
					$customerId = $quote['Quote']['customer_id'];
				} else {
					$customerId = 0;
				}
				$links = array(
					$this->Html->link(__('List '.Inflector::pluralize(Configure::read('Nomenclature.Quote'))), array('controller' => 'quotes', 'action' => 'index')),
					$this->Html->link(__('Print '.Configure::read('Nomenclature.Quote')), array('controller' => 'quotes', 'action' => 'print_pdf', $quote['Quote']['id'])),
					$this->Html->link(__('Email '.Configure::read('Nomenclature.Quote')), array('controller' => 'messages', 'action' => 'add', 'quote', $quote['Quote']['id'])),
					$this->Html->link(__('Convert to '.Configure::read('Nomenclature.Order')), array('controller' => 'quotes', 'action' => 'convert_to_order', $quote['Quote']['id']), array(), sprintf(__('Are you sure you want to convert this %1$s to a %2$s?', Configure::read('Nomenclature.Quote'), Configure::read('Nomenclature.Order')))),
					#$this->Html->link(__('Print Quote with Cover Letter'), array('controller' => 'quotes', 'action' => 'print_pdf', $quote['Quote']['id'], true)),
				);
				break;
			case 'view_submitted':
				$links[] = $this->Html->link(__('Cancel'), array('action' => 'view', $quote['Quote']['id']));
				break;
		}
		break;
	/**
	 * SchedulesController
	 */
	case 'schedules':
		switch ($this->params['action']) {
			case 'index':
				if (!empty($quote)) {
					$links[] = $this->Html->link(__(Configure::read('Nomenclature.Order').' Details'), array('controller' => 'orders', 'action' => 'view', $order_id, 'job-items'));
					$links[] = $this->Html->link(__('List '.Inflector::pluralize(Configure::read('Nomenclature.Quote'))), array('controller' => 'quotes', 'action' => 'index'));
					$links[] = $this->Html->link(__('List '.Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index'));
				} else {
					$links = array(
						$this->Html->link(__('View Daily'), array('controller' => 'schedules', 'action' => 'index', 'day')),
						$this->Html->link(__('View Weekly'), '#'),
						$this->Html->link(__('View Monthly'), '#'),
						$this->Html->link(__('View Daily Map'), array('controller' => 'schedules', 'action' => 'index', 'map')),
					);
				}
				break;
		}
		break;
	/**
	 * UsersController
	 */
	case 'users':
		switch ($this->params['action']) {
			case 'add':
				$links[] = $this->Html->link(__('Cancel', true), array('action' => 'index'));
				break;
			case 'dashboard': 
				
				if($permission['enable_contact'] == 1) {
					$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Contact')), array('controller' => 'contacts', 'action' => 'add_lead'));
				}
				if($permission['enable_customer'] == 1) {
					$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Customer')), array('controller' => 'customers', 'action' => 'add'));
				}
				if($permission['enable_quote'] == 1) {
					$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Quote')), array('controller' => 'quotes', 'action' => 'add'));
				}
				if($permission['enable_order'] == 1) {
					$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'add'));
				}
				if($permission['enable_user'] == 1) {
					$links[] = $this->Html->link(__('Manage Users', true), array('controller' => 'users', 'action' => 'index'));
				}
				break;
			case 'edit':
			case 'edit_owner':
				if (!$owner) {
					$links[] = $this->Html->link(__('Cancel', true), array('action' => 'index'));
				} else {
					$links[] = $this->Html->link(__('Cancel', true), array('action' => 'view', $__user['User']['id']));
				}				
				if ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID && !$owner) {
					$links[] = $this->Html->link(__('Delete', true), array('action' => 'delete', $this->request->data['User']['id']), array(), __('delete_confirm', true));
				}
				break;
			case 'index':
			case 'view':
			case 'view_owner':
				if ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID) {
					$links[] = $this->Html->link(__('Add New User', true), array('action' => 'add'));
				}
				$links[] = $this->Html->link(__('List All Users', true), array('action' => 'index'));
				foreach ($__groups as $groupId => $groupName) {
					$links[] = $this->Html->link(__($groupName, true), array('action' => 'index', $groupId));
				}
				$links[] = $this->Html->link(__('List Inactive Users', true), array('action' => 'index', 'status' => STATUS_INACTIVE));
				break;
		}
		break;
}
if (!empty($links)) {
	foreach ($links as $i => $link) {
		if (empty($before)) {
			$before = null;
		}
		if (empty($after)) {
			$after = null;
		}
		$links[$i] = $before.$link.$after;
	}
	switch ($wrapper) {
		case 'ul':
			?>
			<ul class="<?php echo $class; ?>">
				<?php foreach ($links as $link): ?>
					<li><?php echo $link; ?></li>
				<?php endforeach; ?>
			</ul>
			<?php
			break;
		default:
			echo join($separator, $links);
			break;
	}
}
?>