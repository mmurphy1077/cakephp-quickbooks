<?php
App::uses('AppController', 'Controller');
/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {
	public $uses = array('User', 'ApplicationSetting');
	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow('index', 'edit', 'add', 'view_pdf', 'view_pdf_system_docs', 'view_work_order_pdf');
	}
	
	public function beforeRender() {
		parent::beforeRender();
		#$this->set('rates', $this->User->Group->generateRateList());
		$this->set('userStatuses', $this->User->userStatuses);
		#$this->set('jobTypes', $this->User->JobType->getList());
		#$this->set('jobTypesGroupArray', $this->User->Group->buildJobTypesByGroup());
		$this->set('searchTypes', $this->User->search_options);
	}
	
	private function __logout() {
		$this->Cookie->delete('User');
		$this->Session->delete('User');
		$this->Session->delete('AppReferrer');
		$this->ScreenMessage->info(__('logout_true'));
		$this->Auth->logout();
		$this->redirect($this->Auth->logoutRedirect);		
	}
	
	public function add() {
		if (!empty($this->request->data)) {
			/*
			if(!array_key_exists('group_id', $this->request->data)) {
				$this->request->data['User']['group_id'] = null;
			}
			*/	
			if (empty($this->request->data['User']['password1'])) {
				// Auto-generate password if one is not set
				do {
					// Generate a password until it meets Active Directory's requirements
					$this->request->data['User']['password'] = $this->User->randomPassword(10, 'validateAdPassword');
				}
				while (!$this->request->data['User']['password']);
				$this->request->data['User']['password1'] = $this->request->data['User']['password'];
				$this->request->data['User']['password2'] = $this->request->data['User']['password'];
			} else {
				// Otherwise use the manually entered password
				$this->request->data['User']['password'] = $this->request->data['User']['password1'];
			}				
			if ($this->User->save($this->request->data)) {
				// Send email notification to new user if set and if status is active
				if ($this->request->data['User']['status'] == USER_STATUS_ACTIVE) {
					$this->Notification->send('user_add', array(
						#'userId' => $this->User->getLastInsertID(),
						'userId' => $this->request->data['User']['id'],
						'data' => $this->request->data,
					));
				}
				
				/*
				 * Action log start
				*/
				$this->__action_log($this->request->data, 'add');
				/* END Action Log */
				
				$this->ScreenMessage->success(__('The new account was created successfully and an email notification was sent.'));
				#$this->redirect(array('action' => 'index'));
				$this->redirect(array('action' => 'edit', $this->request->data['User']['id']));
			} else {
				unset($this->request->data['User']['password1']);
				unset($this->request->data['User']['password2']);
				$this->ScreenMessage->error(__('save_false'));
			}
		} else {
			/*
			 * Save Initial record to obtain an id,
			 * Set the status to USER_STATUS_UNSAVED.
			 */
			$data['User']['status'] = USER_STATUS_UNSAVED;
			$this->User->save($data, false);
			$this->request->data['User']['id'] = $this->User->getLastInsertID();
			$this->request->data['User']['status'] = USER_STATUS_ACTIVE;
			$this->request->data['User']['group_id'] = DEFAULT_GROUP_ID;
			$this->request->data['User']['username'] = null;
			$this->request->data['UserProfile']['id'] = null;
			$this->request->data['UserProfile']['timezone'] = $this->Session->read('Application.settings.ApplicationSetting.timezone');
			$this->set('mode', 'add');
		}
		$this->set('owner', false);
		$this->render('edit');
	}
	
	private function __edit($id, $owner = false) {
		if ($user = $this->User->getById($id)) {
			if (!empty($this->request->data)) {
				if ($owner) {
					// Perform some additional checks if editing own record
					if (isset($this->request->data['User']['status'])) {
						// Disallow status change of user by owner					
						unset($this->request->data['User']['status']);
					}
					// Disallow group change by owner
					$this->request->data['User']['group_id'] = $this->user['User']['group_id'];
					// Disallow usage of postback id value
					$this->request->data['User']['id'] = $id;
					$redirect = array('action' => 'edit', $user['User']['id']);
				} else {
					$redirect = array('action' => 'edit', $id);
				}
				$this->User->set($this->request->data);
				if ($this->User->validates()) {
					$this->User->save($this->request->data, false);
						
					/* Action log start */
					$this->__action_log($this->request->data, 'edit');
					/* END Action Log */
					
					$this->ScreenMessage->success(__('save_true'));
					$this->redirect($redirect);
				} else {
					$this->ScreenMessage->error(__('save_false'));
					if (empty($this->request->data['ProfileImage']['name']['name'])) {
						// Form validation failed, but no image was uploaded, keep existsing for view
						unset($this->request->data['ProfileImage']);
					}
				}
			}		
		} else {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect($this->Auth->loginRedirect);				
		}
		$this->request->data = Set::merge($user, $this->request->data);
		$this->set('owner', $owner);
		#$this->set('rates', $this->User->Rate->getRates());
		$this->render('edit');
	}
	
	public function dashboard($page = null, $mode = null) {
		if(empty($this->user)) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));
		}
		
		/*
		 * PERMISSIONS
		 * Verify that the user has the ability to see the dashboard.
		 * 
		 * At this time... a user in 'field' mode can not see the dashboard and must be redirected to the appropriate place
		 */
		if($this->Session->read('Application.browser_view_mode') == 'field' && $page != 'messages' && $page != 'view-message') {
			$this->ScreenMessage->error(__('no_permission_access'));
			$this->redirect(array('controller' => 'orders', 'action' => 'index'));
		}
		
		if(!empty($this->request->data)) {
			$date_markers['start'] = date('Y-m-d', strtotime($this->request->data['User']['Start']));
			$date_markers['end'] = date('Y-m-d', strtotime($this->request->data['User']['End']));
		} else {
			$date_markers = $this->Session->read('Dashboard.dates');
			if(empty($stats_date_range)) {
				$date_markers['start'] = date('Y-m-1');
				$date_markers['end'] = date('Y-m-d');
			}
		}
		$this->Session->write('Dashboard.dates', $date_markers);
		
		$this->loadModel('Schedule');
		$quotes_due_today = null;
		$orders_display = null;
		$messages = null;
		$schedules = null;
		switch ($page) {
			case 'activiy' :
				/*
				 * Alerts
				 */
				#		if(!empty($quotes)) {
				#			foreach($quotes as $key=>$result) {
				#				#$quotes[$key]['Alerts'] = $this->User->Quote->buildAlerts($result['Quote']['id']);
				#				$quotes[$key]['Versions'] = null;
				#				if(!empty($result['Quote']['parent_id'])) {
				#					$quotes[$key]['Versions'] = $this->User->Quote->getRelatedQuotes($result['Quote']['id'], true);
				#				}
				#			}
				#		}
				
				break;
				
			case 'messages' :
				/* Get the messges for the user 
				 * Access the Communication Componenet to get these messages.
				 * Does the user want to see messages sent or recived
				 */
				if(!isset($mode)) {
					$mode = $this->Session->read('Dashboard.messages.mode');
					if(empty($mode)) {
						$mode = 'inbox';
					}
				}
				switch ($mode) {
					case 'send' :
						$message['Message']['id'] = null;
						$message['Message']['parent_id'] = null;
						$message['Message']['from'] = $this->user['User']['email'];
						$message['Message']['to'] = null;
						$message['Message']['created'] = date('Y-m-d');
						$message['Message']['time_deviation'] = null;
						$message['Message']['sender_id'] = $this->user['User']['id'];
						$message['Message']['model'] = null;
						$message['Message']['foreign_key'] = null;
						$this->data = $message;
						break;
					default:
						$messages = $this->__get_messages($mode);
						$this->set('messages', $messages);
				}
				
				$this->Session->write('Dashboard.messages.mode', $mode);
				$this->set('mode', $mode);
				$this->set('employees', $this->User->getList('fnln'));
				$this->set('employee_emails', $this->User->getList('email'));
				$this->set('redirect', 'users');
				break;
			
			case 'view-message' :
				$this->data = $this->__get_message($mode);
				$this->set('mode', 'message-view');
				$this->set('redirect', 'users');
				break;
				
			case 'default' :
			default :
				/*
				 * Orders
				 */
				if(!empty($order_ids)) {
					$orders_display = $this->User->Order->buildOrdersForDashboard($order_ids);
				}
		}

		$this->set('stats', $this->__dashboardStats());
		$this->set('page',$page);
		$this->set('date_markers',$date_markers);
		$this->set('quotes_due_today', $quotes_due_today);
		$this->set('orders_display', $orders_display);
		$this->set('schedules', $schedules);
		$this->set('approvalStatuses', $this->User->Quote->QuoteTask->approval_statuses);
		$this->set('taskStatuses', $this->User->Quote->QuoteTask->task_statuses);
		$this->set('unreadMessages', $this->Message->getUserUnreadMessages($this->user));
		
		//if($this->Session->read('Application.browser_view_mode') == 'standard') {
		if($this->Session->read('Application.view_device') == 'computer') {
			$this->render('dashboard');
		} else {
			$this->render('dashboard_field');
			//$this->redirect(array('controller' => 'orders', 'action' => 'index'));
		}
	}
	
	private function __dashboardStats() {
		$stats = null;
		$range = $this->Session->read('Dashboard.dates');
		if(empty($range)) {
			$range['start'] = date('Y-m-1');
			$range['end'] = date('Y-m-d');
		}
		$stats['labor'] = $this->OrderTime->calculateApprovedLaborHourStatsForDateRange($range['start'], $range['end']);
		$stats['material'] = $this->OrderMaterial->calculateApprovedMaterialStatsForDateRange($range['start'], $range['end']);
		$stats['invoice'] = $this->OrderTime->Invoice->invoiceSnapShot($range);
		$stats['active_quotes'] = $this->OrderTime->Order->Quote->getActiveQuoteStats($range);
		return $stats;
	}
	
	public function dashboard_day() {
		// This method will handle retrieveing a date from the named parameters
		$params = $this->params['named'];
		$date_selected = date('Y-m-j');
		if(!empty($params)) {
			if(!empty($params['date_selected'])) {
				$date_selected = $params['date_selected'];
			}
		}
		$this->Session->write('Dashboard.day_start', $date_selected);
		$this->redirect(array('action' => 'dashboard'));
	}
	
	public function index($groupId = null) {
		if ($this->request->is('post') || $this->request->is('put')) {
			// Remove the search criteria
			$this->Session->delete('Users.search');
			$this->Session->delete('Users.searchCriteria');
			if(!empty($this->request->data['SearchIndex']['keyword'])) {
				// Add the search criteria to the session
				$this->Session->write('Users.search', $this->request->data['SearchIndex']['keyword']);
				$this->Session->write('Users.searchCriteria', $this->request->data['SearchIndex']['criteria']);
			}
		}
		
		/*
		 * Permissions!!!
		 * Does the user have access... Can they see the index page, or just edit own ability.
		 */
		$permissions = null;
		if(array_key_exists('User', $this->user['User'])) {
			$permissions = $this->user['User']['User'];
		} else {
			$permissions = $this->user['Group']['User'];
		}
		if($permissions['_access'] != 1) {
			// No access... boot the mofo out.
			$this->redirect(array('controller'=>'users', 'action'=>'dashboard'));
		}
		if($permissions['_create'] == 1 || $permissions['_update'] == 1) {
			// view all... Do nothing right now
		} elseif ($permissions['_update_as_owner'] == 1) {
			// Redirect the user to the edit owner page.
			$this->redirect(array('controller'=>'users', 'action'=>'view', $this->user['User']['id']));
		} else {
			// Bboot the mofo out.
			$this->redirect(array('controller'=>'users', 'action'=>'dashboard'));
		}			
				
		$conditions = array('User.status' => USER_STATUS_ACTIVE);
		if (!empty($groupId)) {
			$conditions = array('User.group_id' => $groupId);
		}
		if (!empty($this->params['named']) && array_key_exists('status', $this->params['named'])) {
			$conditions['User.status'] = $this->params['named']['status'];
			$this->set('currentStatus', $this->params['named']['status']);
		}
		// Check if the session contains any search criteria.
		$search = $this->Session->read('Users.search');
		$searchCriteria = $this->Session->read('Users.searchCriteria');
		if(empty($searchCriteria)) {
			$searchCriteria = 'name';
		}
		$search_conditions = null;
		if(!empty($search)) {
			$search_conditions = $this->User->constructIndexSearchConditions($search, $searchCriteria);
			$conditions = array_merge($conditions, array('User.id' => false));
			if(!empty($search_conditions)) {
				$conditions = array_merge($conditions, array('User.id' => $search_conditions));
			}
		}
		$conditions['Group.public'] = 1;
		$this->paginate = array(
			'limit' => Configure::read('Paginate.list.limit'),
			'contain' => $this->User->contain($this->User->contain['default']),
			'order' => $this->User->order,
			'conditions' => $conditions,
		);
		$results = $this->paginate('User');
		$this->set('results', $results);
		$this->set('search_keyword', $search);
		$this->set('search_criteria', $searchCriteria);
		$this->set('currentGroupId', $groupId);
	}
	
	public function view($id = null) {
		if ($user = $this->User->getById($id)) {
			$this->set('result', $user);
		} else {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('action' => 'index'));				
		}
	}
	
	public function view_owner() {
		if (empty($this->user)) {
			$this->ScreenMessage->notice(__('You are not logged in.'));
			$this->redirect(array('controller' => 'users', 'action' => 'login'));
		}
		$this->view($this->user['User']['id']);
		$this->render('view');
	}
	
	public function login() {
		if ($this->request->is('post')) {
			$this->request->data['User']['email'] = $this->request->data['User']['emailAddress'];
			if ($this->Auth->login()) {
				$this->User->updateProfile($this->Auth->user('id'));
				$user = $this->User->getById($this->Auth->user('id'), 'default');
				$user = $this->User->buildPermissions($user);
				if(array_key_exists('location_id', $this->request->data['User']) && !empty($this->request->data['User']['location_id'])) {
					$user['User']['location_id'] = $this->request->data['User']['location_id'][0];
				}
				$this->Session->write('User', $user);
				$this->Session->write('Application.settings', $this->ApplicationSetting->find('first'));
				$this->loadModel('Location');
				
				$this->__determineUserView($user);
				if (!empty($this->request->data['User']['__rememberMe'])) {
					// Set cookie in client (i.e. browser) if user wants to be remembered
					$this->Cookie->write('User.remembered', $user);
				}
				if ($this->Session->check('AppReferrer')) {
					// Redirect to page requested prior to login
					$referrer = $this->Session->read('AppReferrer');
					$this->Session->delete('AppReferrer');
					$this->redirect('/'.$referrer);
				} else {
					if($this->Session->read('Application.browser_view_mode') == 'field' || $this->Session->read('Application.view_device') == 'mobile' || $this->Session->read('Application.view_device') == 'tablet') {
						$this->redirect(array('controller' => 'schedules', 'action' => 'index_assigned', $user['User']['id']));
					}
				
					// Redirect to default home/dashboard page
					$this->redirect($this->Auth->redirectUrl());
				}
			} else {
				$this->ScreenMessage->error(__('Incorrect email address or password.'));
			}
		} elseif (!empty($this->user)) {
			$this->redirect($this->Auth->loginRedirect);
		}
	}
	
	public function logout() {
		$this->__logout();
	}
	
	public function reset_password() {
		$this->ScreenMessage->info(__('Due to security reasons, we cannot show your password. We can only reset it. Please enter your email address to have your password reset and emailed to your inbox.'));
		if (!empty($this->request->data['User']['emailAddress'])) {
			$this->User->contain();
			$user = $this->User->findByEmail($this->request->data['User']['emailAddress']);
			if (!empty($user)) {
				// Setting User::id value so User::validateADPassword can be aware
				$this->User->id = $user['User']['id'];
				do {
					// Generate a password until it meets Active Directory's requirements
					$password_plain = $this->User->randomPassword(10, 'validateAdPassword');
				}
				while (!$password_plain);
				$user['User']['password_plain'] = $password_plain;
				$user['User']['password'] = $password_plain;
				// Send plain text password to user
				$this->Notification->send('user_reset_password', array(
					'userId' => $user['User']['id'],
					'data' => $user,
				));
				$this->User->save($user, false);
				$this->ScreenMessage->success(__('user_password_reset_true'));
				$this->redirect(array('action' => 'login'));
			} else {
				$this->ScreenMessage->notice(__('not_found'));
			}
		}
	}
	
	public function edit_owner() {
		$this->__edit($this->user['User']['id'], true);
	}
	
	public function edit($id = null) {
		$isOwner = false;
		if ($id == $this->user['User']['id']) {
			$isOwner = true;
		}
		$this->__edit($id, $isOwner);
	}
	
	public function delete($id = null) {
		if (!$result = $this->User->getById($id)) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect($this->Auth->loginRedirect);
		}
		if ($this->User->delete($id)) {
			$this->ScreenMessage->success(__('delete_true'));
		} else {
			$this->ScreenMessage->error(__('delete_false'));
		}
		$this->redirect(array('controller' => 'users', 'action' => 'index'));
	}
	
	public function delete_profile_image($profile_image_id = null, $user_id = null) {
		if (!$profileImage = $this->User->ProfileImage->getById($profile_image_id)) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect($this->Auth->loginRedirect);
		}
		if ($this->user['User']['id'] == $profileImage['ProfileImage']['foreign_key'] || $this->user['User']['group_id'] == GROUP_ADMINISTRATORS_ID) {
			// Only perform delete if user has permissions (owner or admin)
			if ($this->User->ProfileImage->delete($profile_image_id)) {
				$this->ScreenMessage->success(__('delete_true'));
			} else {
				$this->ScreenMessage->error(__('delete_false'));
			}
		} else {
			$this->ScreenMessage->notice(__('not_authorized'));
		}
		if (empty($user_id)) {
			$user_id = $profileImage['ProfileImage']['foreign_key'];
		}
		$this->redirect(array('action' => 'edit', $user_id));
	}
	
	public function clear_search() {
		// Clear Appropriate Session values
		$this->Session->delete('Users.search');
		$this->Session->delete('Users.status');
		$this->redirect(array('action' => 'index'));
	}
	
	public function activity_log ($id = null) {
		if (!$data = $this->User->getById($id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		
		$owner = false;
		if ($id == $this->user['User']['id']) {
			$owner = true;
		}
		
		$this->data = $data;
		$this->loadModel('ActionLog');
		$this->set('results', $this->ActionLog->getLogsbyUser($id));
		$this->set('owner', $owner);
		
		if ($this->request->is('post') || $this->request->is('put')) {
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.docs'));
			$this->redirect(array('controller' => 'users', 'action' => 'activity_log', $this->data['User']['id']));
		}
	}
	public function docs ($id = null) {
		if (!$data = $this->User->getById($id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		
		$owner = false;
		if ($id == $this->user['User']['id']) {
			$owner = true;
		}
		
		$this->data = $data;
		$this->loadModel('Document');
		$this->set('file_uploads', $this->Document->getDocumentsbyModel('User', null, $id));
		$this->set('owner', $owner);
	
		if ($this->request->is('post') || $this->request->is('put')) {
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.docs'));
			$this->redirect(array('controller' => 'users', 'action' => 'docs', $this->data['User']['id']));
		}
	}
	
	private function __action_log($data, $action) {
		$subject = $data['User']['name_first'] . ' ' . $data['User']['name_last'];
		$params['model'] = 'User';
		$params['foreign_key'] = $data['User']['id'];
		$params['redirect']['controller'] = '';
		$params['redirect']['action'] = '';
		$params['redirect']['params'] = '';
		switch ($action) {
			case 'add' :
				$params['subject'] = 'Create User';
				$params['action'] = 'created a user account for <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'users';
				$params['redirect']['action'] = 'edit';
				$params['redirect']['params'] = serialize(array($data['User']['id']));
				break;
					
			case 'edit' :
				$params['subject'] = 'Update User';
				$params['action'] = 'updated the user account record for <b>' . $subject . '</b>';
				$params['redirect']['controller'] = 'users';
				$params['redirect']['action'] = 'edit';
				$params['redirect']['params'] = serialize(array($data['User']['id']));
				break;
					
			case 'delete' :
				$params['subject'] = 'Delete User';
				$params['action'] = 'deleted <b>' . $subject . '</b> as a user.';
				break;
		}
	
		$this->ActionLog->add($params, $this->user);
	}
	
	public function edit_permissions($user_id) {
		if (!$user = $this->User->getById($user_id, 'default')) {
			$this->ScreenMessage->notice(__('not_found'));
			$this->redirect(array('controller' => 'users', 'action' => 'index'));
		}
		/*
		 * Before Proceeding... verify that the user has permission to edit permissions
		 */
		if(array_key_exists('User', $this->user['User'])) {
			if($this->user['User']['User']['_permissions'] != 1) {
				$this->redirect(array('controller' => 'users', 'action' => 'index'));
			}
		} else {
			if($this->user['Group']['User']['_permissions'] != 1) {
				$this->redirect(array('controller' => 'users', 'action' => 'index'));
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
					if(!empty($this->request->data['User'])) {
						foreach($this->request->data['User'] as $key=>$data) {
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
		
		// Get a list of all the control items.
		$current_permissions = $this->User->buildPermissions($user);
		$control_objects = $this->User->getControlledObjects();
		$this->set('aro_id', $this->User->getUsersAroId($user_id));
		$this->set('user', $user);
		$this->set('current_permissions', $current_permissions);
		$this->set('control_objects', $control_objects);
	}
	
	public function delete_image($image_id, $user_id) {
		$sql = "DELETE FROM images WHERE images.id = " . $image_id;
		$this->User->query($sql);
		$this->ScreenMessage->success(__('delete_true'));
		
		$this->redirect(array('action' => 'edit', $user_id));
	}
	
	private function __get_messages($type = 'inbox') {
		$results = null;
		switch ($type) {
			case 'sent':
				$condition = array(
				'Message.sender_id' => $this->user['User']['id'],
				'Message.sender_delete' => 0,
				'NOT' => array('Message.recipient_id' => null),
				);
				break;
			case 'inbox':
			default:
				// Get messages sent
				$condition = array (
					'Message.recipient_delete' => 0,
					'OR' => array(
						'Message.recipient_id' => $this->user['User']['id'],
						array(
							'Message.cc' => 1,
							'Message.sender_id' => $this->user['User']['id'],
						),
					),
				);
				break;
		}
		
		$this->paginate = array(
			'limit' => Configure::read('Paginate.list.limit'),
			//'contain' => $this->Message->contain['default'],
			'order' => array('Message.read' => 'ASC', 'Message.created' => 'DESC'),
			'conditions' => $condition,
			'fields' => array('Message.process_id'),
			'group' => array('Message.process_id'),
		);
		$results = $this->paginate('Message');
		if(!empty($results)) {
			foreach($results as $key=>$result) {
				$results[$key] = $this->Message->getMessageForProcessId($result['Message']['process_id'], $this->user['User']['id']);
			}
		}

		return $results;
	}
	
	private function __determineUserView($user) {
		switch($user['User']['group_id']) {
			case GROUP_EMPLOYEES_ID :
			case GROUP_APPRENTICE_ID :
				$this->Session->write('Application.browser_view_mode', 'field');
				$this->Session->write('Application.can_toggle_browser_view_mode', false);
				break;
			
			default :
				$this->Session->write('Application.browser_view_mode', 'standard');
				$this->Session->write('Application.can_toggle_browser_view_mode', true);
		}
		
		App::import('Vendor', 'MobileDetect', array('file' =>'mobile_detect'.DS.'Mobile_Detect.php'));
		$detect = new Mobile_Detect();
		$device = 'computer';
		if ($detect->isTablet()) {
			$device = 'tablet';
		}
		if ($detect->isMobile() && !$detect->isTablet()) {
			$device = 'mobile';
		}
		
// Temp
#$device = 'mobile';
		$this->Session->write('Application.view_device', $device);
	}
	
	public function ajax_retrieve_users_pay_rate() {
		$params = $this->params['named'];
		$rate = 0;
		$exp_rate = 0;
		$error = null;
		$success = true;
		$data = array();
		// Validate that an ids has been recieved.
		if (empty($params['id'])) {
			$error = __('not_found');
			$success = false;
			
		} else {
			if (!$user = $this->User->getById($params['id'], 'default')) {
				$error = __('not_found');
				$success = false;
			} else {
				if(!empty($user['User']['rate_id'])) {
					$rate_id = $user['User']['rate_id'];
					$rate = number_format($user['Rate']['rate'], 2);
					$exp_rate = number_format($user['User']['expense_rate'], 2);
				} else if (!empty($user['Group']['rate_id'])) {
					$rate_id = number_format($user['Group']['rate_id'], 2);
					$rate = number_format($user['Rate']['rate'], 2);
					$exp_rate = 0;
				} else {
					$rate = HOURLY_RATE;
					$exp_rate = HOURLY_EXPENSE_RATE;
				}
			}
		}
		$data['rate_id'] = $rate_id;
		$data['rate'] = $rate;
		$data['exp_rate'] = $exp_rate;
		
		$this->set('data',$data);
		$this->set('success', $success);
		$this->set('error', $error);
		$this->render('ajax_retrieve_users_pay_rate', 'ajax');
	}
}