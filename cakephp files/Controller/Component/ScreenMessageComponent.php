<?php
/**
 * Allows for easier setting of SessionComponent flash messages to the view
 * Created to utilize Admintasia's four different screen message types:
 * 		- success
 * 		- error
 * 		- inf
 * 		- notice
 * 
 * @see /app/webroot/css/admin/ui/ui.messages.css * 
 * @author Kevin DeCapite <www.decapite.net>
 * @for Creationsite <www.creationsite.com>
 * @created 2010-03-31
 * @modified 2010-03-31
 */
class ScreenMessageComponent extends Component {
	
	/**
	 * Contains a reference to the current controller
	 * 
	 * @var AppController object
	 */
	var $controller;
	
	/**
	 * Set default configuration settings here
	 * Overridden during component initialization if passed in
	 * 
	 * @var array
	 */
	var $__defaultSettings = array(
		'layout' => 'default',
		'params' => array(),
	);
	
	/**
	 * Stores component configuration settings keyed according
	 * 		to the controller name property using it
	 * 
	 * @var array
	 */
	var $settings = array();	
	
	/**
	 * Called before Controller::beforeFilter()
	 * 
	 * @param $controller AppController object reference
	 * @param $settings array runtime configuration settings
	 * 		merged with ScreenMessage::__defaultSettings
	 * @return void
	 */
	function initialize(Controller $controller, $settings = array()) {
		$this->controller =& $controller;
		$this->settings[$controller->name] = Set::merge($this->__defaultSettings, (array)$settings);
	}
	
	function __setSessionComponentMessage($message, $key = 'flash') {
		$this->controller->Session->setFlash(
			$message,
			$this->settings[$this->controller->name]['layout'],
			$this->settings[$this->controller->name]['params'],
			$key
		);
	}
	
	function success($message = null) {
		$this->__setSessionComponentMessage($message, 'success');
	}
	
	function error($message = null) {
		$this->__setSessionComponentMessage($message, 'error');
	}
	
	function info($message = null) {
		$this->__setSessionComponentMessage($message, 'info');
	}
	
	function inf($message = null) {
		$this->info($message);
	}
	
	function information($message = null) {
		$this->info($message);
	}
	
	function notice($message = null) {
		$this->__setSessionComponentMessage($message, 'notice');
	}
    
}
?>
