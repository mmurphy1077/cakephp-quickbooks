<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */

/**
 * @property AuthComponent $Auth
 * @property NotificationComponent $Notification
 * @property ScreenMessageComponent $ScreenMessage
 * @propery SessionComponent $Session
 */
class AppController extends Controller {
	
	public $layout = 'main';
	public $acoType = ACL_ACO_TYPE_MEMBERSHIP;
	public $components = array(
		'AclSystem',
		'Auth',
		'Cookie',
		'Notification',
		'Communication',
		'ScreenMessage',
		'Session',
		'RequestHandler',
	);
	public $helpers = array(
		'Image',
		'Web',
	);
	/**
	 * Same data as found in Configure::read('Environment')
	 * 
	 * @var array
	 */
	public $environment;
	/**
	 * Holds all data for the currently logged in User
	 * 
	 * @var array
	 */
	public $user;
	public $permissions;
	
	public function beforeFilter() {
		/**
		 * Load application's configuration settings
		 */
		Configure::load('config');
		$this->environment = Configure::read('Environment');
		if ($this->environment['debug'] > 1) {
			// Add debug toolbar if applicable
			$this->components['DebugKit.Toolbar'] = null;
		}
		
		/**
		 * Force entire application to run via SSL
		 */
		if($this->environment['platform'] == 'production' && ENABLE_SSL) {
			$this->__setProtocol($this->environment['ssl_port']);
		}
		
		/**
		 * Define AuthComponent settings
		 */
		$this->Auth->loginRedirect = array('controller' => 'customers', 'action' => 'index');
#		$this->Auth->loginRedirect = array('controller' => 'quotes', 'action' => 'index');
#		$this->Auth->logoutRedirect = array('controller' => 'users', 'action' => 'login');
		$this->Auth->authenticate = array(
			'Form',
			'Blowfish' => array(
				'scope' => array(
					'User.status <=' => USER_STATUS_ACTIVE,
				),
			),
			AuthComponent::ALL => array(
				'userModel' => 'User',
				'fields' => array(
					'username' => 'email',
					'password' => 'password',
				),
				'scope' => array(
					'User.status <=' => USER_STATUS_ACTIVE,
				),
			),
		);
		/**
		 * Sets up CookieComponent configuration and re-logs in a User if applicable
		 */
		$this->__restoreUserSession();
		/**
		 * Sets the currently selected Company to the Controller::company property
		 * Also sets this same information into the Configure class (Company key)
		 */
		$this->__setCompanyEnvironment();
		/**
		 * Set current User information to AppController::user property
		 * This value will also be exposed to all views as $__user variable
		 */
		$this->user = Set::merge($this->Auth->user(), $this->Session->read('User'));
		/**
		 * Check permissions for currently logged in user for the requested object
		 */
		$this->AclSystem->isAuthorized($this->user);
		if(!empty($this->user)) {
			$this->permissions = $this->__getPermissions($this->user);
			if(empty($this->permissions['_access'])) {
				$this->ScreenMessage->notice(__('no_permission_access'));
				$this->redirect(array('controller' => 'users', 'action' => 'dashboard'));	
			}
		}
	}
	
	public function beforeRender() {
		// Set commonly used vars to all views
		if ($this->modelClass != 'Page') {
			$this->set('__statuses', $this->{$this->modelClass}->statuses);
			$this->set('__yesNo', $this->{$this->modelClass}->yesNo);
			$this->set('__phoneLabels', $this->{$this->modelClass}->phoneLabels);
		}
		$this->set('__user', $this->user);
		$this->set('__permissions', $this->permissions);
		$Group = ClassRegistry::init('Group');
		$this->set('__groups', $Group->getPublicList());
		#$this->set('__group_rates', $Group->getGroupRates());
		$this->set('__assigned_tos', $Group->User->getAssignedToUsers());
		$this->__setCompanyEnvironment();
		$Message = ClassRegistry::init('Message');
		if(!empty($this->user)) {

		}
		
		$browser_view_mode = $this->__getViewMode();
		$this->set('__browser_view_mode', $browser_view_mode);
		switch($browser_view_mode['view_device']) {
			case 'mobile' :
			case 'tablet' :
				$this->layout = 'mobile';
				break;
		
			default :
				$this->layout = 'main';
		}
	}
	
	private function __setCompanyEnvironment() {
		$Company = ClassRegistry::init('Company');
		$Company->contain();
		$companies = $Company->find('all', array('order' => 'Company.name ASC'));
		$this->set('__companies', $companies);
		if (!$this->Session->check('Companies.Current') && !empty($companies)) {
			$this->Session->write('Companies.Current', $companies[0]);
		}
		Configure::write('Public', Set::merge(Configure::read('Public'), $this->Session->read('Companies.Current.Company')));
		Configure::write('Company', $this->Session->read('Companies.Current'));
	}
	
	private function __restoreUserSession() {
		/**
		 * Configure CookieComponent settings
		 * Used for login "Remember Me" functionality
		 */
		if (Configure::read('Environment.platform') != 'development') {
			// Only set hostname in stage and production environments due to browser issues
			$this->Cookie->domain = Configure::read('Environment.host');
		}
		$this->Cookie->name = Configure::read('Application.cookieName');
		$this->Cookie->key = '51734df1991f7';
		$this->Cookie->time = '30 days';
		/**
		 * Check for saved user session in Cookie
		 * Restore login if it exists
		 */
		if ($this->Cookie->check('User.remembered')) {
			$user = $this->Cookie->read('User.remembered');
			if (!empty($user['User']['id'])) {
				// Login user back into the system
				if ($this->Auth->login($user)) {
					// Update UserProfile and Session to "refresh" this User
					$User = ClassRegistry::init('User');
					$User->updateProfile($this->Auth->user('User.id'));
					$user = $User->getById($this->Auth->user('User.id'), 'default');
					$this->Session->write('User', $user);
				}
			}
		}
	}

	public function __time_to_sec($time) {
	    $hours = substr($time, 0, -6);
	    $minutes = substr($time, -5, 2);
	    $seconds = substr($time, -2);
	
	    return $hours * 3600 + $minutes * 60 + $seconds;
	}
	
	public function copy($id, $redirect) {
		list($model, $action) = explode(".", $redirect);
		$schema = $this->{$model}->schema();
		if ($this->{$model}->copy($id)) {
			if (array_key_exists('name', $schema)) {
				// Update Model::name field to reflect new copy
				$name = $this->{$model}->field('name', array('id' => $id));
				$this->{$model}->id = $this->{$model}->getLastInsertID();
				$this->{$model}->saveField('name', $name.__(' (copy)'));
			}
		}
		$this->ScreenMessage->success(__('You successfully copied the '.Configure::read('Nomenclature.'.$model).'.'));
		$this->redirect(array('controller' => Inflector::tableize($model), 'action' => $action));
	}
	
	/**
	 * Update the sort order of all records in a model
	 * 
	 * @param max int the maximum value allowed for a sort order number
	 * @return void (redirects to URL sent in with posted data)
	 */	
	public function sort($max = 255) {
		$model = $this->modelClass;
		if ($this->request->is('post') || $this->request->is('put')) {
			if (array_key_exists('id', $this->request->data[$model]) && is_array($this->request->data[$model]['id'])) {
				foreach ($this->request->data[$model]['id'] as $recordId => $sort) {
					if (ctype_digit($sort)) {
						// Only update if a positive integer is set
						$this->{$model}->id = $recordId;
						$this->{$model}->saveField('sort', $sort);
					}
				}
				$this->ScreenMessage->success(__('The sort order was updated successfully.'));
				$this->redirect($this->request->data[$model]['__redirect']);
			}
		}
		$this->ScreenMessage->notice(__('There is nothing to re-order.'));
		$this->redirect($this->Auth->loginRedirect);		
	}
	
	public function send_message() {
		$this->Communication->send_message($this->request->data, $this->user);
	}
	
	private function __getTimers($user) {
		$this->loadModel('Timer');
		return $this->Timer->getActiveTimers($user);
	}
	
	private function __getViewMode() {
		$display['can_toggle_browser_view_mode'] = $this->Session->read('Application.can_toggle_browser_view_mode');
		$display['browser_view_mode'] = $this->Session->read('Application.browser_view_mode');
		$display['view_device'] = $this->Session->read('Application.view_device');
		return $display;
	}
	
	public function __getNextSortNum() {
		$model = $this->modelClass;
		$num = 1;
		$conditions = $conditions = array($model.'.status'=>'1');
		$order = array($model.'.sort DESC');
		
		$result = $this->$model->find('first', array('conditions'=>$conditions, 'order'=>$order));
		if(!empty($result[$model])) {
			$num = $result[$model]['sort'] + 1;
		}
		return $num;
	}
	
	private function __getPermissions($user) {
		$permissions = null;
		if($this->params['controller'] == 'users' && $this->params['action'] == 'login') {
			$permissions['_access'] = 1;
		} else {
			// Obtain a list from tha ACL table of all Public models
			$conditions = array('Aco.parent_id' => 1);
			$fields = array('id', 'model');
			$this->loadModel('Aco');
			$results = $this->Aco->find('list', array('conditions' => $conditions, 'fields' => $fields));
			if(!empty($user)) {
				$permissions = $user['User'];
			}
		if(in_array($this->modelClass, $results)) {
				/*
				 * Update... structure so which 'Model' permission is checked can be controlled
				 */
				switch ($this->modelClass) {
					case 'QuoteTask' :
					case 'QuoteLineItem' :
						$model = 'Quote';
						break;
					case 'OrderTask' :
					case 'OrderLineItem' :
						$model = 'Order';
						break;
					default: 
						$model = $this->modelClass;
				}
				if(!empty($user)) {
					if(array_key_exists($model, $user['User'])) {
						$permissions = $user['User'];
					} else {
						$permissions = $user['Group'];
					}
					// Determine if the user has access to the current model.
					$permissions['_access'] = $permissions[$model]['_access'];
				}
			} else {
				$permissions['_access'] = 1;
			}
		}
		return $permissions;
	}
	
	/**
	 * Change the protocol of the current request using specified port number
	 * Used to force an SSL connection or switch back to standard HTTP
	 *
	 * @param $port int the port number of the desired protocol
	 * @return void
	 */
	function __setProtocol($port = 80) {
		#debug($this->environment);
		#debug($_SERVER); die;
		#if($_SERVER["HTTPS"] != "on")
		#{
		#	header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
		#	exit();
		#}
	
	
		$protocol = 'http';
		if ($port == 443) {
			$protocol = 'https';
		}
		if ($_SERVER['SERVER_PORT'] != $port) {
			$this->redirect($protocol.'://'.$this->environment['host'].$this->here);
		}
	}
}