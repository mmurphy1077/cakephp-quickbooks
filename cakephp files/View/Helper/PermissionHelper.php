<?php
/**
 * @author Creationsite
 * @copyright 2008
 * @modified 2010
 * 
 * Contains logic and functionality for use in the public & admin pages
 */
class PermissionHelper extends AppHelper {
	// Helper function to obtain the permission levels for a page.
	function getPermissions($user_permissions, $user=null, $data=null) { 
		/*
		 * Application Level Permissions
		 */
		$permissions['report_metrics'] = $user_permissions['Application']['_report_metrics'];
		$permissions['report_financial'] = $user_permissions['Application']['_report_financial'];
		$permissions['report_labor'] = $user_permissions['Application']['_report_labor'];
		$permissions['report_sales'] = $user_permissions['Application']['_report_sales'];
		$permissions['report_orders'] = $user_permissions['Application']['_report_orders'];
		$permissions['financials_company'] = $user_permissions['Application']['_financials_company'];
		$permissions['financials_project'] = $user_permissions['Application']['_financials_project'];
		$permissions['application_settings'] = $user_permissions['Application']['_application_settings'];
		switch ($this->params['controller']) {
			/**
			 * AccountsController
			 */
			case 'accounts':
				switch ($this->params['action']) {
					case 'index':
						$permissions['can_create'] = $user_permissions['Account']['_create'];
						break;
					case 'activity_log' :
						$permissions['read_only'] = $user_permissions['Account']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Account']['_create'];
						$permissions['enable_delete'] = $user_permissions['Account']['_delete'];
					case 'add':
					case 'add_address':
						$permissions['read_only'] = $user_permissions['Account']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Account']['_create'];
						$permissions['enable_file_upload'] = $user_permissions['Account']['_upload_files'];
						break;
					case 'addresses' :
					case 'contacts' :
					case 'edit' :
						$permissions['read_only'] = $user_permissions['Account']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Account']['_update'];
						$permissions['enable_delete'] = $user_permissions['Account']['_delete'];
						$permissions['enable_file_upload'] = $user_permissions['Account']['_upload_files'];
						$permissions['enable_add_quote'] = $user_permissions['Account']['_create'];
						break;
					case 'docs' :
						$permissions['enable_file_upload'] = $user_permissions['Account']['_upload_files'];
						break;
					case 'view' :
						$permissions['enable_edit'] = $user_permissions['Account']['_update'];
						$permissions['enable_delete'] = $user_permissions['Account']['_delete'];
						$permissions['enable_add'] = $user_permissions['Account']['_create'];
						break;
					case 'poes' :
						$permissions['can_create_po'] = $user_permissions['PurchaseOrder']['_create'];
						break;
					case 'orders' :
						break;
					case 'invoices' :
						$permissions['can_create_invoice'] = 0;
						break;
				}
				break;
				
			/**
			 * ChangeOrderRequestsController
			 */
			case 'change_order_requests' :
				switch ($this->params['action']) {
					case 'add':
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Order']['_create'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						$permissions['can_delete'] = $user_permissions['Order']['_delete'];
						$permissions['can_approve'] = $user_permissions['Order']['_approve'];
						$permissions['view_all_orders'] = $user_permissions['Order']['_view_all'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['can_update_status'] = -1;
						$permissions['owner'] = -1;
						if(($data['Order']['_update_all'] == 1) || ($user_permissions['Order']['_update'] == 1)) {
							$permissions['can_update'] = 1;
						}
						if(($user['User']['id'] == $data['Order']['project_manager_id']) || ($user_permissions['Order']['_update_all'] == 1)) {
							$permissions['can_update_status'] = 1;
							$permissions['owner'] = 1;
						}
						$permissions['can_access_cor'] = $user_permissions['ChangeOrderRequest']['_access'];
						$permissions['cor_read_only'] = $user_permissions['ChangeOrderRequest']['_read_only'];
						$permissions['can_create_cor'] = $user_permissions['ChangeOrderRequest']['_create'];
						$permissions['can_update_cor'] = $user_permissions['ChangeOrderRequest']['_update'];
						$permissions['can_delete_cor'] = $user_permissions['ChangeOrderRequest']['_delete'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						break;
				}
				break;

			/**
			 * ContactsController
			 */
			case 'contacts': 
				switch ($this->params['action']) {
					case 'index':
						$permissions['can_create'] = $user_permissions['Contact']['_create'];
						break;
					case 'add_lead':
					case 'edit_lead' :
					default :
						$permissions['read_only'] = $user_permissions['Contact']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Contact']['_update'];
						$permissions['enable_delete'] = $user_permissions['Contact']['_delete'];
						$permissions['enable_file_upload'] = $user_permissions['Contact']['_upload_files'];
						$permissions['enable_convert_to_customer'] = $user_permissions['Customer']['_create'];
						$permissions['enable_add_quote'] = $user_permissions['Quote']['_create'];
						break;
				}
				break;
				
			/**
			 * CustomersController
			 */
			case 'customers': 
				switch ($this->params['action']) {
					case 'index':
						$permissions['can_create'] = $user_permissions['Customer']['_create'];
						break;
					case 'activity_log' :
						$permissions['read_only'] = $user_permissions['Customer']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Customer']['_create'];
						$permissions['enable_delete'] = $user_permissions['Customer']['_delete'];
						$permissions['can_convert_to_order'] = 0;
					case 'add':
					case 'add_address':
						$permissions['read_only'] = $user_permissions['Customer']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Customer']['_create'];
						$permissions['enable_file_upload'] = $user_permissions['Customer']['_upload_files'];
						break;
					case 'addresses' : 
					case 'contacts' :
					case 'edit' :
						$permissions['read_only'] = $user_permissions['Customer']['_read_only'];
						$permissions['enable_save'] = $user_permissions['Customer']['_update'];
						$permissions['enable_delete'] = $user_permissions['Customer']['_delete'];
						$permissions['enable_file_upload'] = $user_permissions['Customer']['_upload_files'];
						$permissions['enable_add_quote'] = $user_permissions['Customer']['_create'];
						$permissions['enable_address_delete'] = $user_permissions['Customer']['_update'];
						break;
					case 'docs' :
						$permissions['enable_file_upload'] = $user_permissions['Customer']['_upload_files'];
						break;
					case 'view' : 
						$permissions['enable_edit'] = $user_permissions['Customer']['_update'];
						$permissions['enable_delete'] = $user_permissions['Customer']['_delete'];
						$permissions['enable_add'] = $user_permissions['Customer']['_create'];
						break;
					case 'quotes' :
						$permissions['can_create_quote'] = $user_permissions['Quote']['_create'];
						break;
					case 'orders' :
						$permissions['can_create_order'] = $user_permissions['Order']['_create'];
						break;
					case 'invoices' :
						$permissions['can_create_invoice'] = 0;
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
					case 'index_order':
					case 'step1':
					case 'step2':
					case 'step3':
					case 'view':
						/* Tab permissions - START */
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						/* Tab permissions - END */
						
						$permissions['can_create_invoice'] = $user_permissions['Invoice']['_create'];
						$permissions['can_approve_invoice'] = $user_permissions['Invoice']['_approve'];
						$permissions['read_only_invoice'] = $user_permissions['Invoice']['_read_only'];
						$permissions['can_delete'] = $user_permissions['Invoice']['_delete'];						
					break;
				}
				break;
							
			/**
			 * MessagesController
			 */
			case 'messages':
				switch ($this->params['action']) {
					case 'add':
					case 'view':
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						$permissions['can_approve'] = $user_permissions['Order']['_approve'];
						$permissions['view_all_orders'] = $user_permissions['Order']['_view_all'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['can_update_status'] = -1;
						$permissions['owner'] = -1;
						$permissions['enable_save'] = -1;
						if(($user_permissions['Order']['_create'] == 1) || ($user_permissions['Order']['_update'] == 1)) {
							$permissions['can_update'] = 1;
						}
						$permissions['track_time_only'] = -1;
						if(($user_permissions['Order']['_track_time'] == 1) && ($user_permissions['Order']['_create'] == -1)) {
							// User can mark time against their jobs
							$permissions['track_time_only'] = 1;
						}
						if(!empty($data) && (array_key_exists('Order', $data)) && (($user['User']['id'] == $data['Order']['project_manager_id']) || ($user_permissions['Order']['_update'] == 1))) {
							$permissions['can_update_status'] = 1;
							$permissions['owner'] = 1;
						}
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						if($user_permissions['PurchaseOrder']['_access'] == 1) {
							$permissions['pos_read_only'] = $user_permissions['PurchaseOrder']['_read_only'];
							$permissions['can_create_pos'] = $user_permissions['PurchaseOrder']['_create'];
							$permissions['can_update_pos'] = $user_permissions['PurchaseOrder']['_update'];
							$permissions['can_delete_pos'] = $user_permissions['PurchaseOrder']['_delete'];
							$permissions['can_approve_pos'] = $user_permissions['PurchaseOrder']['_approve'];
						} else {
							$permissions['pos_read_only'] = 1;
							$permissions['can_create_pos'] = -1;
							$permissions['can_update_pos'] = -1;
							$permissions['can_delete_pos'] = -1;
							$permissions['can_approve_pos'] = -1;
						}
						$permissions['enable_stats'] = -1;
						break;
				}
				break;
				
			/**
			 * OrdersController
			 */
			case 'orders':
			case 'order_line_items':
			case 'order_requirements':
			case 'order_tasks' :
				switch ($this->params['action']) {
					case 'add' :
					case 'activity_log' :
					case 'customer_info' :
					case 'docs' :
					case 'edit' :
					case 'index' :
					case 'job_info':
					case 'messages' :
					case 'print_docs' :
					case 'purchasing' :
					case 'view' :
					case 'view_order_items' :
					case 'previous_orders_at_location_items' :
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						$permissions['can_approve'] = $user_permissions['Order']['_approve'];
						$permissions['view_all_orders'] = $user_permissions['Order']['_view_all'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['can_update_status'] = -1;
						$permissions['owner'] = -1;
						$permissions['enable_save'] = -1;
						if(($user_permissions['Order']['_create'] == 1) || ($user_permissions['Order']['_update'] == 1)) {
							$permissions['can_update'] = 1;
							$permissions['enable_save'] = 1;
						}
						$permissions['track_time_only'] = -1;
						if(($user_permissions['Order']['_track_time'] == 1) && ($user_permissions['Order']['_create'] == -1)) {
							// User can mark time against their jobs
							$permissions['track_time_only'] = 1;
						}
						if(($user['User']['id'] == $data['Order']['project_manager_id']) || ($user_permissions['Order']['_update'] == 1)) {
							$permissions['can_update_status'] = 1;
							$permissions['owner'] = 1;
						}
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						if($user_permissions['PurchaseOrder']['_access'] == 1) {
							$permissions['pos_read_only'] = $user_permissions['PurchaseOrder']['_read_only'];
							$permissions['can_create_pos'] = $user_permissions['PurchaseOrder']['_create'];
							$permissions['can_update_pos'] = $user_permissions['PurchaseOrder']['_update'];
							$permissions['can_delete_pos'] = $user_permissions['PurchaseOrder']['_delete'];
							$permissions['can_approve_pos'] = $user_permissions['PurchaseOrder']['_approve'];
						} else {
							$permissions['pos_read_only'] = 1;
							$permissions['can_create_pos'] = -1;
							$permissions['can_update_pos'] = -1;
							$permissions['can_delete_pos'] = -1;
							$permissions['can_approve_pos'] = -1;
						}
						break;
					case 'schedules' :
					case 'add_schedule' :
					case 'edit_schedule' :
						/* Tab permissions - START */
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						/* Tab permissions - END */
			
						$permissions['schedule_access'] = $user_permissions['Schedule']['_access'];
						$permissions['schedule_create'] = $user_permissions['Schedule']['_create'];
						$permissions['schedule_delete'] = $user_permissions['Schedule']['_delete'];
						$permissions['schedule_update'] = $user_permissions['Schedule']['_update'];
						$permissions['schedule_read_only'] = $user_permissions['Schedule']['_read_only'];
						$permissions['view_all'] = $user_permissions['Schedule']['_view_all'];
						$permissions['view_assigned_only'] = $user_permissions['Schedule']['_view_assigned_only'];	
						break;
					case 'materials' :
						/* Tab permissions - START */
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						/* Tab permissions - END */
						
						
						break;
					case 'labor_hours' :
						/* Tab permissions - START */
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						/* Tab permissions - END */
						
						$permissions['labor_access'] = $user_permissions['OrderTime']['_access'];
						$permissions['labor_create'] = $user_permissions['OrderTime']['_create'];
						$permissions['labor_update'] = $user_permissions['OrderTime']['_update'];
						$permissions['labor_delete'] = $user_permissions['OrderTime']['_delete'];
						$permissions['labor_read_only'] = $user_permissions['OrderTime']['_read_only'];
						$permissions['labor_approve'] = $user_permissions['OrderTime']['_approve'];
						$permissions['labor_view_all'] = $user_permissions['OrderTime']['_view_all'];
						$permissions['labor_view_own'] = $user_permissions['OrderTime']['_view_own'];
						break;
						
					case 'track_mobile_index' :
						/* Tab permissions - START */
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						/* Tab permissions - END */
						
						/* Labor */
						$permissions['labor_access'] = $user_permissions['OrderTime']['_access'];
						$permissions['labor_create'] = $user_permissions['OrderTime']['_create'];
						$permissions['labor_update'] = $user_permissions['OrderTime']['_update'];
						$permissions['labor_delete'] = $user_permissions['OrderTime']['_delete'];
						$permissions['labor_read_only'] = $user_permissions['OrderTime']['_read_only'];
						$permissions['labor_approve'] = $user_permissions['OrderTime']['_approve'];
						$permissions['labor_view_all'] = $user_permissions['OrderTime']['_view_all'];
						$permissions['labor_view_own'] = $user_permissions['OrderTime']['_view_own'];
						
						/* Material */
						$permissions['material_access'] = $user_permissions['OrderTime']['_access'];
						$permissions['material_create'] = $user_permissions['OrderTime']['_create'];
						$permissions['material_update'] = $user_permissions['OrderTime']['_update'];
						$permissions['material_delete'] = $user_permissions['OrderTime']['_delete'];
						$permissions['material_read_only'] = $user_permissions['OrderTime']['_read_only'];
						$permissions['material_approve'] = $user_permissions['OrderTime']['_approve'];
						$permissions['material_view_all'] = $user_permissions['OrderTime']['_view_all'];
						$permissions['material_view_own'] = $user_permissions['OrderTime']['_view_own'];
						break;
				}
				break;
				
			case 'order_contacts':
				switch ($this->params['action']) {
					case 'ajax_edit' :
						$permissions['owner'] = -1;
						if(($user['User']['id'] == $data['Order']['project_manager_id']) || ($user_permissions['Order']['_update_all'] == 1)) {
							$permissions['owner'] = 1;
						}
							
						break;
				}
				break;
			
			case 'order_expenses' :
			case 'order_materials' :
				switch ($this->params['action']) {
					default :
						/* Tab permissions - START */
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						/* Tab permissions - END */
			
						$permissions['material_access'] = $user_permissions['OrderTime']['_access'];
						$permissions['material_create'] = $user_permissions['OrderTime']['_create'];
						$permissions['material_update'] = $user_permissions['OrderTime']['_update'];
						$permissions['material_delete'] = $user_permissions['OrderTime']['_delete'];
						$permissions['material_read_only'] = $user_permissions['OrderTime']['_read_only'];
						$permissions['material_approve'] = $user_permissions['OrderTime']['_approve'];
						$permissions['material_view_all'] = $user_permissions['OrderTime']['_view_all'];
						$permissions['material_view_own'] = $user_permissions['OrderTime']['_view_own'];
						break;
				}
				break;
					
			case 'order_outsources' :
				switch ($this->params['action']) {
					case 'ajax_edit' :
						$permissions['owner'] = -1;
						if(($user['User']['id'] == $data['Order']['project_manager_id']) || ($user_permissions['Order']['_update_all'] == 1)) {
							$permissions['owner'] = 1;
						}
						break;
				}
				break;
			
			case 'order_times' :
				switch ($this->params['action']) {
					case 'add' :
					case 'edit' :
					case 'index' :
					case 'ajax_toggle_table_order_type' :
					default :
						/* Tab permissions - START */
						$permissions['can_create'] = $user_permissions['Order']['_create'];
						$permissions['read_only'] = $user_permissions['Order']['_read_only'];
						$permissions['can_update'] = $user_permissions['Order']['_update'];
						$permissions['can_access_pos'] = $user_permissions['PurchaseOrder']['_access'];
						$permissions['enable_file_upload'] = $user_permissions['Order']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['Order']['_delete'];
						/* Tab permissions - END */
						
						$permissions['labor_access'] = $user_permissions['OrderTime']['_access'];
						$permissions['labor_create'] = $user_permissions['OrderTime']['_create'];
						$permissions['labor_update'] = $user_permissions['OrderTime']['_update'];
						$permissions['labor_delete'] = $user_permissions['OrderTime']['_delete'];
						$permissions['labor_read_only'] = $user_permissions['OrderTime']['_read_only'];
						$permissions['labor_approve'] = $user_permissions['OrderTime']['_approve'];
						$permissions['labor_view_all'] = $user_permissions['OrderTime']['_view_all'];
						$permissions['labor_view_own'] = $user_permissions['OrderTime']['_view_own'];
						break;
				}
				break;
					
			/**
			 * QuotesController
			 */
			case 'quotes':
			case 'quote_line_items' :
			case 'quote_tasks' :
				switch ($this->params['action']) {
					case 'customer_info' :
					case 'add':
					case 'add_step1':
					case 'edit' : 
					case 'job_info':
					case 'estimates':
					case 'add_quote_line_item':
					case 'edit_quote_line_item':
					case 'activity_log' :
					case 'review' :
					case 'index':
					case 'quote_tasks':
					case 'docs' :
					case 'messages' :
					case 'view' :
						$permissions['read_only'] = $user_permissions['Quote']['_read_only'];
						$permissions['can_create'] = $user_permissions['Quote']['_create'];
						$permissions['can_update'] = $user_permissions['Quote']['_update'];
						$permissions['can_approve'] = $user_permissions['Quote']['_approve'];
						$permissions['can_delete'] = $user_permissions['Quote']['_delete'];
						$permissions['enable_delete'] = $user_permissions['Quote']['_delete'];
						$permissions['can_convert_to_order'] = $user_permissions['Quote']['_convert_to_order'];
						$permissions['enable_file_upload'] = $user_permissions['Quote']['_upload_files'];
						$permissions['view_all_quotes'] = $user_permissions['Quote']['_view_all_quotes'];
						$permissions['enable_file_upload'] = $user_permissions['Quote']['_upload_files'];
						$permissions['can_update_status'] = -1;
						$permissions['quote_owner'] = -1;
						$permissions['enable_save'] = -1;
						if(($user_permissions['Quote']['_create'] == 1) || ($user_permissions['Quote']['_update'] == 1)) {
							$permissions['can_update'] = 1;
							$permissions['enable_save'] = 1;
						}
						if(($user['User']['id'] == $data['Quote']['project_manager_id']) || ($user_permissions['Quote']['_view_all_quotes'] == 1)) {
							$permissions['can_update_status'] = 1;
							$permissions['quote_owner'] = 1;
						}
						break;

					case 'print_docs':
						$permissions['read_only'] = $user_permissions['Quote']['_read_only'];
						$permissions['can_convert_to_order'] = $user_permissions['Quote']['_create'];
						$permissions['enable_file_upload'] = $user_permissions['Quote']['_upload_files'];
						break;
					case 'view_submitted':
						break;
				}
				break;
			case 'quote_contacts':
				switch ($this->params['action']) {
					case 'ajax_edit' :
						$permissions['owner'] = -1;
						if(($user['User']['id'] == $data['Quote']['project_manager_id']) || ($user_permissions['Quote']['_update_all'] == 1)) {
							$permissions['owner'] = 1;
						}
							
						break;
				}
				break;
			
			/**
			 * QuoteTasksController
			 */
			case 'quote_tasks' :
				switch ($this->params['action']) {
					case 'quote_tasks':
					case 'index':
						$permissions['read_only'] = $user_permissions['QuoteTask']['_read_only'];
						$permissions['enable_save'] = $user_permissions['QuoteTask']['_create'];
						$permissions['enable_update'] = $user_permissions['QuoteTask']['_update'];
						$permissions['enable_delete'] = $user_permissions['QuoteTask']['_delete'];
						$permissions['can_approve'] = $user_permissions['QuoteTask']['_approve'];
						$permissions['can_convert_to_order'] = $user_permissions['Quote']['_create'];
						break;
				}
				break;
				
			/**
			 * SchedulesController
			 */
			case 'schedules' :
				switch ($this->params['action']) {
					default : 
						$permissions['read_only'] = $user_permissions['Schedule']['_read_only'];
						$permissions['all_save'] = $user_permissions['Schedule']['_create'];
						$permissions['all_update'] = $user_permissions['Schedule']['_update'];
						$permissions['can_delete'] = $user_permissions['Schedule']['_delete'];
						#$permissions['can_add_production'] = $user_permissions['Schedule']['_edit_production_schedule'];
				}
				break;
			
			/**
			 * UsersController
			 */
			case 'users': 
				switch ($this->params['action']) {
					case 'activity_log' :
					case 'docs' :
					case 'add':
					case 'edit' :
					case 'edit_owner' :
					case 'edit_permissions' :
					case 'view' :
					case 'view_owner' :
						// Page allows a user to Save and Delete.
						$permissions['read_only'] = $user_permissions['User']['_read_only'];
						//$permissions['enable_save'] = $user_permissions['User']['_create'];
						$permissions['enable_permissions'] = $user_permissions['User']['_permissions'];
						$permissions['enable_file_upload'] = $user_permissions['User']['_upload_files'];
						$permissions['enable_delete'] = $user_permissions['User']['_delete'];
						// Determine if the user has admin or owner ability.		
						$permissions['admin'] = -1;
						$permissions['owner'] = -1;
						if(($user_permissions['User']['_create'] == 1) || ($user_permissions['User']['_update'] == 1)) {
							$permissions['admin'] = 1;
						} else if ($user_permissions['User']['_update_as_owner'] == 1) {
							$permissions['owner'] = 1;
						}
						break;
					case 'index':
						$permissions['can_create'] = $user_permissions['User']['_create'];
						break;
					case 'dashboard' :
						// Dashboard 
						$permissions['enable_order'] = -1;
						$permissions['enable_quote'] = -1;
						$permissions['enable_contact'] = -1;
						$permissions['enable_customer'] = -1;
						$permissions['enable_user'] = -1;
						$permissions['enable_stats'] = -1;
						if(array_key_exists('_create', $user_permissions['Order'])) {
							$permissions['enable_order'] = $user_permissions['Order']['_create'];
						}
						if(array_key_exists('_create', $user_permissions['Quote'])) {
							$permissions['enable_quote'] = $user_permissions['Quote']['_create'];
						}
						if(array_key_exists('_create', $user_permissions['Contact'])) {
							$permissions['enable_contact'] = $user_permissions['Contact']['_create'];
						}
						if(array_key_exists('_create', $user_permissions['Customer'])) {
							$permissions['enable_customer'] = $user_permissions['Customer']['_create'];
						}
						if(array_key_exists('_create', $user_permissions['User'])) {
							$permissions['enable_user'] = $user_permissions['User']['_create'];
						}
						if(array_key_exists('_report_financial', $user_permissions['Application'])) {
							$permissions['enable_stats'] = $user_permissions['Application']['_report_financial'];
						}
						break;
				}
				break;
		}
		return $permissions;
	}
}
?>