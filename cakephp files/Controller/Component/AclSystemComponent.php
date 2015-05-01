<?php
/**
 * Created: 2009-06-04
 * Author: Kevin DeCapite <www.decapite.net>
 * 
 * Use in tandem with AclSystemBehavior
 * Check user permissions and access rights
 */
class AclSystemComponent extends Component {
	
	/**
	 * Stores reference to current controller
	 * 
	 * @var Controller
	 */
	public $Controller;
	/**
	 * Stores reference to AclPermission model
	 * Required to perform lookups between Aros and Acos
	 * 
	 * @var Model
	 */
	public $AclPermission;
	/**
	 * Stores current Aro node path as indexed array
	 * 
	 * @var array
	 */
	public $aroNodes = array();
	/**
	 * Stores current Aco node path as indexed array
	 * 
	 * @var array
	 */
	public $acoNodes = array();
	/**
	 * Stores most specific permission set for Aro & Aco nodes
	 * 
	 * @var array
	 */
	public $permission = array();
	/**
	 * Holds current Aro type
	 * If no user logged in, defaults to $this->aroGuest (i.e. 'Guest')
	 * Otherwise holds the user's group alias from the Aro tree
	 * May also hold the user's email if found as node in Aro tree
	 * 
	 * @var string
	 */
	public $aroType;
	/**
	 * Guest is the default Aro alias assigned to non-logged in users
	 * 
	 * @var string
	 */
	public $aroGuest = ACL_ARO_GUEST;
	/**
	 * Holds current Aco type
	 * AppController::beforeFilter() sets this property to Public or Admin by default
	 * Use each individual controller's beforeFilter() to override
	 * 
	 * @var string
	 */
	public $acoType;
	/**
	 * Public is the default Aco alias allowed to all users including guests
	 * 
	 * @var string
	 */
	public $acoPublic = ACL_ACO_TYPE_PUBLIC;
	/**
	 * Restricted is an Aco alias containing record-level nodes
	 * 
	 * @var string
	 */
	public $acoRestricted = ACL_ACO_TYPE_RESTRICTED;
	/**
	 * Sets default Aco alias for admin interface
	 * 
	 * @var string
	 */
	public $acoAdmin = ACL_ACO_TYPE_ADMIN;
	/**
	 * Blackhole is an Aco alias that is denied to all users
	 * Used on requests for record ids that don't exist to indicate "page not found"
	 * 
	 * @var string
	 */
	public $acoBlackhole = ACL_ACO_TYPE_BLACKHOLE;
	/**
	 * Rudimentary debug output if true
	 * @var bool
	 */
	public $debug = false;
		
	/**
	 * Initialize component properties
	 */
	public function initialize(Controller $controller) {
		$this->aroType = $this->aroGuest;
		$this->acoType = $this->acoPublic;
		$this->Controller = $controller;
		$this->AclPermission = ClassRegistry::init('AclPermission');
	}
	
	public function beforeRender(Controller $controller) {
		if ($this->debug) {
			debug($this->aroNodes);
			debug($this->acoNodes);
			debug($this->permission);
		}
	}
	
	/**
	 * Check acl to determine if current 'user' can access the requested item
	 * Relies on aro_id and aco_id match in aros_acos table, otherwise will deny access
	 * Called by $this->isAuthorized()
	 * 
	 * Steps:
	 * - Get instance of AclPermission model so we can lookup access rights
	 * - Determine aro of current user:
	 * 		a. Set aro_id as Guest if no user logged in
	 * 		b. Lookup email address as aro alias for user-specific permissions
	 * 		c. Default to user's group if no specific setting found
	 * - Determine aco of requested object/action:
	 * 		a. Lookup ControllerName::action as alias
	 * 		b. If not found, default to $this->acoType
	 * 
	 * @return bool true if aro can access aco, otherwise false
	 */	
	public function isAuthorized($user = array()) {
		$this->aroNodes = $this->_getAro($this->Controller, $this->AclPermission, $user);
		$this->acoNodes = $this->_getAco($this->Controller, $this->AclPermission, $user);
		// Lookup each node point in permissions table until we find a match of aro_id to aco_id
		$action_is_pdf = strpos($this->Controller->action, 'pdf');
		$action_is_quickbooks = strpos($this->Controller->action, 'quickbooks');
		if(empty($user) && ($action_is_pdf === false) && ($action_is_quickbooks === false)) {
			$this->Controller->Auth->logout();
		} else {
			foreach ($this->aroNodes as $aro) {
				foreach ($this->acoNodes as $aco) {
					$this->permission = $this->AclPermission->find('first', array('conditions' => array('aro_id' => $aro['AclRequester']['id'], 'aco_id' => $aco['AclControlled']['id'])));
					// Return true once an acceptable permission is found
					if (!empty($this->permission)) {
						if ($this->permission['AclPermission']['_access'] == '1') {
							return $this->__allow($this->Controller->action);
						} else {
							return $this->__deny($this->Controller->action);
						}
					}
				}
			}
		}
		return false;
	}
	
	private function __allow($action) {
		$this->Controller->Auth->allow($action);
		return true;
	}
	
	private function __deny($action) {
		$this->Controller->Auth->deny($action);
		$this->Controller->ScreenMessage->error(__('not_authorized', true));
		$this->Controller->redirect(array('controller' => 'pages', 'action' => 'display', 'not_authorized'));
		return false;
	}

	protected function _getAro(&$controller, &$acl, $user) {
		$aros = array();
		// Set aro_id according to logged in user, group or guest
		if (!empty($user)) {
			if(!array_key_exists('User', $user)) {
				$user['User'] = $user;
			}
			// Lookup user's email in aros table to check for specific permissions
			$aros = $acl->AclRequester->node($user['User']['email']);
			// Otherwise default to user's group Aro permissions
			if (empty($aros)) {
				$aros = $acl->AclRequester->node(array('model' => 'Group', 'foreign_key' => $user['User']['group_id']));
			}
		} else {
			$aros = $acl->AclRequester->node($this->aroGuest);
		}
		return $aros;
	}
	
	protected function _getAco(&$controller, &$acl, $user) {
		$acos = array();
		// If a specific record is requested, determine if it is an Aco
		if (!empty($controller->{$controller->modelClass}->id)) {
			$record_id = $controller->{$controller->modelClass}->id;
			$acos = $acl->AclControlled->node(array('model' => $controller->modelClass, 'foreign_key' => $record_id));
		}
		// Determine if current controller/action pair is also an Aco
		if (empty($acos)) {
			$acos = $acl->AclControlled->node($controller->name.'::'.$controller->action);			
		}
		// If not, use current controller's acoType or $this->acoType
		if (empty($acos)) {
			if (!empty($controller->acoType)) {
				$this->acoType = $controller->acoType;
			}
			$acos = $acl->AclControlled->node($this->acoType);
		}
		return $acos;
	}
}
?>
