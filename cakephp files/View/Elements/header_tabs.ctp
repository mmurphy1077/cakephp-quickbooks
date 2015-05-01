<?php
$links = array();
$controllerAction = $this->params['controller'].'.'.$this->params['action'];
$alphabet = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
if (empty($class)) {
	$class = 'links actions';
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
				$links[] = $this->Html->link(__('Cancel'), array('controller' => 'accounts', 'action' => 'index'));				
				break;
				
			case 'activity_log' :
			case 'add':
			case 'add_address':
			case 'add_contacts':
			case 'addresses':
			case 'contacts':
			case 'docs':
			case 'edit':
			case 'orders':
			case 'quotes':
			case 'view':
				$links[] = $this->Html->link(__('Cancel'), array('controller' => 'accounts', 'action' => 'index'));	
				if ($__user['User']['Account']['_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete', true), array('action' => 'delete', $account['Account']['id']), array(), __('delete_confirm'));
				}				
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('List All '.Inflector::pluralize(Configure::read('Nomenclature.Account'))), array('controller' => 'accounts', 'action' => 'index')),
					$this->Html->link(__('List Inactive '.Inflector::pluralize(Configure::read('Nomenclature.Account'))), array('controller' => 'accounts', 'action' => 'index', 'status' => STATUS_INACTIVE)),
				);
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
			case 'additional_info':
			case 'edit':
			case 'edit_lead':
			case 'docs' :
				$links[] = $this->Html->link(__('Add '.Configure::read('Nomenclature.Quote'), true), array('action' => 'add_quote', $data['Contact']['id']));
				if ($permissions['enable_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete', true), array('action' => 'delete_lead', $data['Contact']['id']), array(), __('delete_confirm'));
				}
				break;
				
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('My '.Inflector::pluralize(Configure::read('Nomenclature.Contact'))), array('controller' => 'contacts', 'action' => 'index')),
					$this->Html->link(__('Active '.Inflector::pluralize(Configure::read('Nomenclature.Contact'))), array('controller' => 'contacts', 'action' => 'index', 'status' => STATUS_ACTIVE)),
					$this->Html->link(__('Inactive '.Inflector::pluralize(Configure::read('Nomenclature.Contact'))), array('controller' => 'contacts', 'action' => 'index', 'status' => STATUS_INACTIVE)),
					$this->Html->link(__('No Contact '.Inflector::pluralize(Configure::read('Nomenclature.Contact'))), array('controller' => 'contacts', 'action' => 'index', 'status' => STATUS_INACTIVE)),
				);
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
			case 'activity_log' :
			case 'add':
			case 'add_address':
			case 'add_contacts':
			case 'addresses':
			case 'contacts':
			case 'docs':
			case 'edit':
			case 'orders':
			case 'quotes':
			case 'view':
				$links[] = $this->Html->link(__('Cancel'), array('controller' => 'customers', 'action' => 'index'));				
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('All '.Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index')),
					$this->Html->link(__('Active '.Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index', 'status' => STATUS_ACTIVE)),
					$this->Html->link(__('Inactive '.Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index', 'status' => STATUS_INACTIVE)),
					$this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Customer')) . ' with Active Jobs'), array('controller' => 'customers', 'action' => 'index', 'status' => STATUS_INACTIVE)),
				);
				break;
		}
		break;
	/**
	 * InvoicesController
	 */
	case 'invoices':
		switch ($this->params['action']) {
			case 'add':
			case 'edit':
			case 'index_order' :
			case 'view':
				if($permissions['can_create'] == 1) {
					$links[] = $this->Html->link(__('Copy ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'copy', $order['Order']['id']));
				}
				if($permissions['enable_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'delete', $order['Order']['id']), array(), __('delete_confirm'));
				}
				break;
			case 'index':
				$links = array(
					$this->Html->link(__('Billed'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_BILLED)),
					$this->Html->link(__('Approved'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_APPROVED)),
					$this->Html->link(__('Awaiting Approval'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_PENDING)),
					$this->Html->link(__('Ready to Invoice'), array('controller' => 'invoices', 'action' => 'index', 'status' => 4)),
					$this->Html->link(__('Void'), array('controller' => 'invoices', 'action' => 'index', 'status' => INVOICE_STATUS_INACTIVE)),
				);
				break;
		}
		break;
	/**
	 * MessagesController
	 */
	case 'messages':
		switch ($this->params['action']) {
			case 'add' :
			case 'view' :
				if($__browser_view_mode['browser_view_mode'] == 'field') {
					$links[] = $this->Html->link('&laquo; back to ' . __(Configure::read('Nomenclature.Order')). ' list', array('controller' => 'orders', 'action' => 'index'), array('class' => 'text-header-tab-field-view', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-message.png'), array('controller' => 'orders', 'action' => 'messages_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-message', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-upload.png'), array('controller' => 'orders', 'action' => 'docs', $order['Order']['id']), array('class'=>'field-action-button field-action-button-upload', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-materials.png'), array('controller' => 'order_materials', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-materials', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-time.png'), array('controller' => 'order_times', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-time', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-view.png'), array('controller' => 'orders', 'action' => 'view_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-view', 'escape' => false));
				}
				break;
		}
		break;
	/**
	 * OrdersController
	 */
	case 'orders':
	case 'order_line_items' :
		switch ($this->params['action']) {
			case 'index':
				$links = array(
					$this->Html->link(__('List All '.Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index')),
					$this->Html->link(__('List Inactive '.Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index', 'status' => STATUS_INACTIVE)),
				);
				break;
			default :
				if($__browser_view_mode['browser_view_mode'] == 'field') {
					$doc_count_display = '';
					if(!empty($doc_count)) {
						$doc_count_display = '<div id="doc-count-field-view">'.$doc_count.'</div>';
					}
					$links[] = $this->Html->link($this->Html->image('icon-message.png'), array('controller' => 'orders', 'action' => 'messages_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-message', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-upload.png'), array('controller' => 'orders', 'action' => 'docs', $order['Order']['id']), array('class'=>'field-action-button field-action-button-upload', 'escape' => false));
					if(!empty($doc_count_display)) {
						$links[] = $doc_count_display;
					}
					$links[] = $this->Html->link($this->Html->image('icon-materials.png'), array('controller' => 'order_materials', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-materials', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-time.png'), array('controller' => 'order_times', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-time', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-view.png'), array('controller' => 'orders', 'action' => 'view_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-view', 'escape' => false));
				} else {
					if($permissions['can_create'] == 1) {
						$links[] = $this->Html->link(__('Copy ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'copy', $order['Order']['id']));
					}
					if($permissions['enable_delete'] == 1) {
						$links[] = $this->Html->link(__('Delete ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'delete', $order['Order']['id']), array(), __('delete_confirm'));
					}
				}
		}
		break;
	/**
	 * OrderRequirementsController
	 * OrderTasksController
	 * ChangeOrderRequestController
	 */
	case 'order_requirements':
	case 'order_tasks':
	case 'change_order_requests':
		switch ($this->params['action']) {
			default:
				if($permissions['can_create'] == 1) {
					$links[] = $this->Html->link(__('Copy ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'copy', $order['Order']['id']));
				}
				if($permissions['enable_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'delete', $order['Order']['id']), array(), __('delete_confirm'));
				}
				break;
		}
		break;
		
	/**
	 * OrderMaterialsController & OrderExpensesController
	 */
	case 'order_expenses':
	case 'order_materials':
		switch ($this->params['action']) {
			case 'add':
			case 'edit':
			case 'index':
			default:
				if($__browser_view_mode['browser_view_mode'] == 'field') {
					$doc_count_display = '';
					if(!empty($doc_count)) {
						$doc_count_display = '<div id="doc-count-field-view">'.$doc_count.'</div>';
					}
					$links[] = $this->Html->link($this->Html->image('icon-message.png'), array('controller' => 'orders', 'action' => 'messages_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-message', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-upload.png'), array('controller' => 'orders', 'action' => 'docs', $order['Order']['id']), array('class'=>'field-action-button field-action-button-upload', 'escape' => false));
					if(!empty($doc_count_display)) {
						$links[] = $doc_count_display;
					}
					$links[] = $this->Html->link($this->Html->image('icon-materials.png'), array('controller' => 'order_materials', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-materials', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-time.png'), array('controller' => 'order_times', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-time', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-view.png'), array('controller' => 'orders', 'action' => 'view_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-view', 'escape' => false));
				} else {
					if($permissions['can_create'] == 1) {
						$links[] = $this->Html->link(__('Copy ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'copy', $order['Order']['id']));
					}
					if($permissions['enable_delete'] == 1) {
						$links[] = $this->Html->link(__('Delete ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'delete', $order['Order']['id']), array(), __('delete_confirm'));
					}
				}
				break;
		}
		break;
		
	/**
	 * OrderTimesController
	 */
	case 'order_times':
		switch ($this->params['action']) {
			case 'add':
			case 'edit':
				#$links[] = $this->Html->link('&laquo; back to ' . __(Configure::read('Nomenclature.OrderTime')). ' list', array('controller' => 'order_times', 'action' => 'index', $order['Order']['id']), array('class' => 'text-header-tab-field-view', 'escape' => false));
			case 'index':
				if($__browser_view_mode['browser_view_mode'] == 'field') {
					$doc_count_display = '';
					if(!empty($doc_count)) {
						$doc_count_display = '<div id="doc-count-field-view">'.$doc_count.'</div>';
					}
					$links[] = $this->Html->link($this->Html->image('icon-message.png'), array('controller' => 'orders', 'action' => 'messages_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-message', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-upload.png'), array('controller' => 'orders', 'action' => 'docs', $order['Order']['id']), array('class'=>'field-action-button field-action-button-upload', 'escape' => false));
					if(!empty($doc_count_display)) {
						$links[] = $doc_count_display;
					}
					$links[] = $this->Html->link($this->Html->image('icon-materials.png'), array('controller' => 'order_materials', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-materials', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-time.png'), array('controller' => 'order_times', 'action' => 'index', $order['Order']['id']), array('class'=>'field-action-button field-action-button-time', 'escape' => false));
					$links[] = $this->Html->link($this->Html->image('icon-view.png'), array('controller' => 'orders', 'action' => 'view_field', $order['Order']['id']), array('class'=>'field-action-button field-action-button-view', 'escape' => false));
				} else {
					if($permissions['can_create'] == 1) {
						$links[] = $this->Html->link(__('Copy ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'copy', $order['Order']['id']));
					}
					if($permissions['enable_delete'] == 1) {
						$links[] = $this->Html->link(__('Delete ' . Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'delete', $order['Order']['id']), array(), __('delete_confirm'));
					}
				}
				break;
		}
		break;
		
	/**
	 * QuotesController
	 */
	case 'quotes':
		switch ($this->params['action']) {
			case 'activity_log' :
			case 'add':
			case 'job_info':
			case 'docs' :
			case 'estimates' :
			case 'messages' :
			case 'docs' :
			case 'review' :
			case 'print_docs' :
			case 'view' :
				if(!empty($permissions) && $permissions['can_create'] == 1) {
					// Allow the user to Copy and Create Revision
					$links[] = $this->Html->link(__('Copy'), array('controller' => 'quotes', 'action' => 'copy', $quote['Quote']['id']));
					$links[] = $this->Html->link(__('Create Revision'), array('controller' => 'quotes', 'action' => 'version', $quote['Quote']['id']));
				}
				if($permissions['enable_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete'), array('action' => 'delete', $quote['Quote']['id']), array(), __('delete_confirm'));
				}
				break;
			case 'customer_info':
				if(!empty($permissions) && $permissions['can_create'] == 1) {
					$links[] = $this->Html->link(__('Copy'), array('controller' => 'quotes', 'action' => 'copy', $this->data['Quote']['id']));
					$links[] = $this->Html->link(__('Create Revision'), array('controller' => 'quotes', 'action' => 'version', $this->data['Quote']['id']));
				}
				if($permissions['enable_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete'), array('action' => 'delete', $this->data['Quote']['id']), array(), __('delete_confirm'));
				}
				break;
			case 'view_submitted':
				$links[] = $this->Html->link(__('Cancel'), array('action' => 'view', $quote['Quote']['id']));
				break;
			case 'edit':
				$links[] = $this->Html->link(__('List Quotes'), array('action' => 'index'));
				$links[] = $this->Html->link(__('Cancel'), array('action' => 'view', $this->request->data['Quote']['id']));
				if ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID) {
					$links[] = $this->Html->link(__('Delete'), array('action' => 'delete', $this->request->data['Quote']['id']), array(), __('delete_confirm'));
				}				
				break;
			case 'index':
				break;
		}
		break;
	/**
	 * Quote Requirements and Tasks
	 */
	case 'quote_line_items' :
	case 'quote_tasks' :
		switch ($this->params['action']) {
			default :
				if(!empty($permissions) && $permissions['can_create'] == 1) {
					// Allow the user to Copy and Create Revision
					$links[] = $this->Html->link(__('Copy'), array('controller' => 'quotes', 'action' => 'copy', $quote['Quote']['id']));
					$links[] = $this->Html->link(__('Create Revision'), array('controller' => 'quotes', 'action' => 'version', $quote['Quote']['id']));
				}
				if($permissions['enable_delete'] == 1) {
					$links[] = $this->Html->link(__('Delete'), array('action' => 'delete', $quote['Quote']['id']), array(), __('delete_confirm'));
				}
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
				$links = array(
					$this->Html->link(__('Jobs & Tasks'), array('controller' => 'users', 'action' => 'dashboard')),
					$this->Html->link(__('Messages'), array('controller' => 'users', 'action' => 'dashboard', 'messages')),
					#$this->Html->link(__('Activity & Alerts'), array('controller' => 'users', 'action' => 'dashboard', 'activity')),
				);
				break;
			case 'activity_log':
			case 'docs':
			case 'edit':
			case 'edit_permissions' :
			case 'edit_owner':
				$links[] = $this->Html->link(__('Cancel', true), array('action' => 'index'));
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
		/**
		 * PagessController
		 */
		case 'pages':
			switch ($this->params['action']) {
				case 'add':
					$links[] = $this->Html->link(__('Cancel', true), array('action' => 'index'));
					break;
				case 'display':
					$links = array(
					$this->Html->link(__('Jobs & Tasks'), array('#'), array('id' => 'jobs_tasks', 'class' => 'toggle_page_link')),
					$this->Html->link(__('Messages'), array('#'), array('id' => 'messages', 'class' => 'toggle_page_link')),
					#$this->Html->link(__('Activity & Alerts'), array('#'), array('id' => 'activity_alerts', 'class' => 'toggle_page_link')),
					);
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
			echo '<div class="header-links">';
			echo join($separator, $links);
			echo '</div>';
			break;
	}
}
?>