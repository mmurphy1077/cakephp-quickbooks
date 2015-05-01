<?php
/**
 * Application model for Cake.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Model', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {
	
	public $actsAs = array(
		'Containable',
		'Copyable' => array(
			'recursive' => true,
		),
	);
	public $statuses = array(1 => 'Active', 0 => 'Inactive');
	public $yesNo = array(0 => 'No', 1 => 'Yes');
	public $phoneLabels = array(
		'Cell' => 'Cell',
		'Fax' => 'Fax',
		'Home' => 'Home',
		'Office' => 'Office',
	);
	public $contain = array(
		'default' => array(),
		'index' => array(),
	);
	
	public function beforeSave($options = array()) {
		if(!empty($this->data[$this->name])) {
			foreach ($this->data[$this->name] as $n => $v) {
				// Format all date_ fields for MySQL datetime column type
				// Assumes validation has already taken place on the field
				if (!empty($v) && substr($n, 0, 5) == 'date_') {
					$v = trim($v);
					$this->data[$this->name][$n] = date('Y-m-d H:i:s', strtotime($v));
				}
	
				// Remove and Required Values
				if ($v === 'Required') {
					$this->data[$this->name][$n] = null;
				}
			}
		}
		return true;
	}
	
	public function beforeValidate($options = array()) {
		if(!empty($this->data[$this->alias])) {
			foreach ($this->data[$this->alias] as $n => $v) {
				// Remove any Required Values.
				if ($v == 'Required') {
					$this->data[$this->alias][$n] = null;
				}
			}
		}
		return true;
	}
	
	public function getList($status = null, $order_field=null) {
		$conditions = array();
		if ($status !== null) {
			$conditions = array($this->alias.'.status' => $status);
		}
		
		$order=null;
		if(!empty($order_field)) {
			$order = array($this->alias.'.'.$order_field.' DESC');
		}
		if(empty($order)) {
			return $this->find('list', array('conditions' => $conditions));
		} else {
			return $this->find('list', array('conditions' => $conditions, 'order'=>$order));
		}
	}
	
	public function getById($id, $containKey = null) {
		if ($containKey !== null && $containKey !== false) {
			$this->contain($this->contain[$containKey]);
		} elseif ($containKey === false) {
			$this->contain();
		}
		return $this->findById($id);
	}
	
	/**
	 * Generate a random password
	 * 
	 * @param passwordLength string number of characters for the password (default is 8)
	 * @param $callback string a method to run after the password is generated
	 * 		it should return a boolean value to indicate whether or not the new password
	 * 		should be returned, or false instead
	 * @return string
	 */
	public function randomPassword($passwordLength = null, $callback = null, $nonAlpha = false) {
		$defaultLength = 8;
		// Allowable characters for password
		// (Lowercase letter 'l', uppercase letter 'O', and integers '0' and '1' are excluded)	
		$salt = "abcdefghijkmnopqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789";
		if ($nonAlpha) {
			$salt .= '!@#$%^&*()-_=+;:[]{}<>,./?\|';
		}
		if (empty($passwordLength)) {
			$passwordLength = $defaultLength;
		}
		$password = '';		
		for ($i = 0; $i < $passwordLength; $i++) {
			$password .= substr($salt, rand() % strlen($salt), 1);
		}
		$callbackResponse = true;
		if ($callback !== null) {
			// Execute optional callback method (i.e. for validating system generated password, etc.)
			if (method_exists($this, $callback)) {
				$callbackResponse = $this->{$callback}($password);
			}
		}
		if ($callbackResponse) {
			return $password;
		}
		return false;
	}
	
	/**
	 * Custom validation method: isUploadedFile()
	 * Determines if a file was uploaded via HTTP post and checks for errors
	 * Uses PHP's built-in is_uploaded_file() function
	 * 
	 * @see http://book.cakephp.org/view/548/Validating-Uploads
	 * 
	 * @param params array holds posted back form data
	 * @param options array('allowEmpty' => bool) will skip validation if true
	 * @return bool true if the file was uploaded without errors, false otherwise
	 */
	function isUploadedFile($params = array(), $options = array()) {
		if (empty($options['allowEmpty'])) {
			$val = array_shift($params);
			if ((isset($val['error']) && $val['error'] == 0) || (!empty( $val['tmp_name']) && $val['tmp_name'] != 'none')) {
				return is_uploaded_file($val['tmp_name']);
			}
			return false;
		}
		return true;
	}
	
	/**
	 * Custom validation method: checkCaptcha() 
	 * @param $field array holds the postback field => value data
	 * @param $options array expects keys named 'field1' & 'field2':
	 * 		- field1 => __captcha (the user submitted field name)
	 * 		- field2 => captcha (the field name holding the value from the session)
	 * @return bool true if field1 == field2, false otherwise
	 */
	function checkCaptcha($field, $options = array()) {
		extract($options);
		if ($this->data[$this->name][$field1] == $this->data[$this->name][$field2]) {
			return true;
		}
		return false;
	}
	
	/**
	 * Custom validation method: checkFilesize()
	 * Determine if a file is greater or less than a specified filesize
	 * 
	 * @param params array holds posted back form data
	 * @param options array('max_size' => 'filesizeInBytes')
	 * @return bool true if the file is less than or equal to max_size, false otherwise
	 */	
	function checkFilesize($params = array(), $options = array()) {
		$val = array_shift($params);
		if ($val['size'] > $options['max_size']) {
			return false;
		}
		return true;
	}
	
	/**
	 * Custom validation method: checkMimeType()
	 * Determine if a file is greater or less than a specified filesize
	 * 
	 * @param params array holds posted back form data
	 * @param options array('allowed_mime_types' => array('mime_type1', 'mime_type2', 'etc.'))
	 * @return bool true if the file's mime type matches one of the allowed_mime_types, false otherwise
	 */
	function checkMimeType($params = array(), $options = array()) {
		if ($this->isUploadedFile($params)) {
			// Only validate mime type if a file was uploaded at all
			$val = array_shift($params);
			foreach ($options['allowed_mime_types'] as $extensions) {
				if ((!is_array($extensions) && $extensions == '*') ||
					(is_array($extensions) && in_array($val['type'], $extensions))) {
					return true;
				}
			}
			return false;
		}
		return true;
	}
	
	/**
	 * Custom validation method: checkExtension()
	 * Validate the extension of a file
	 * Useful when the mime type can't be exactly determined (i.e. generic application/octet-stream)
	 * 
	 * @param params array holds posted back form data 
	 * @param options array('allowed_mime_types' => array('mime_type1', 'mime_type2', 'etc.'))
	 * @return bool true if the file's mime type matches one of the allowed_mime_types, false otherwise
	 */	
	function checkExtension($params = array(), $options = array()) {
		$val = array_shift($params);
		if (!empty($val['name'])) {
			$pathinfo = pathinfo($val['name'], PATHINFO_EXTENSION);
			if (in_array($pathinfo, $options)) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * Custom validation method: habtmSelected()
	 * Determines if a HABTM model contains any selected data
	 * 
	 * @param params array('habtmModel' => 'ModelName')
	 * @return bool true if one or more items are selected, false otherwise
	 */
	function habtmSelected($value, $params = array()) {
		if (empty($this->data[$params['habtmModel']][$params['habtmModel']])) {
			return false;
		}
		return true;
	}	
	
	/**
	 * Custom validation method: isValidDate()
	 * Determines if the format and numerical range of a date is valid or not
	 * Uses strtotime return value
	 * Test for return type false for PHP >= 5.1.0 and -1 for lower versions
	 * 
	 * @param params array holds posted back form data
	 * @param options array('return' => 'time')
	 * @return bool true if the date is valid, false otherwise
	 * 		If $options['return'] = 'time', returns strtotime() value rather than bool
	 */
	function isValidDate($params = array(), $options = array()) {
		$time = strtotime(array_shift($params));
		if ($time !== false && $time != -1) {
			if (!empty($options['return']) && $options['return'] == 'time') {
				return $time;
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Custom validation method: isUniqueException()
	 * Determines if a field is unique in the model (excludes current record's value)
	 * Disables exclusion of current record's id if not editing (i.e. creating)
	 * Used to ensure usernames/emails are not duplicated but still allow editing 
	 * 
	 * @param params array('field' => 'theFieldName', 'exception_id' => 'idFieldName')
	 * @return bool true if the posted data in field1 equals field2, false otherwise
	 */
	function isUniqueException($value, $params = array()) {
		$conditions = array($params['field'] => $this->data[$this->name][$params['field']]);
		if (!empty($this->data[$this->name][$params['exception_id']])) {
			$conditions[$params['exception_id'].' <>'] = $this->data[$this->name][$params['exception_id']];
		}
		$data = $this->field($params['field'], $conditions);
		if (!empty($data)) {
			return false;
		}		
		return true;
	}
	
	/**
	 * Custom validation method: matchField()
	 * Compares two fields and determines if they are equal
	 * 
	 * @param params array('field1' => 'value', 'field2' => 'value')
	 * @return bool true if the posted data in field1 equals field2, false otherwise
	 */
	function matchField($value, $params = array()) {
		if ($this->data[$this->name][$params['field1']] != $this->data[$this->name][$params['field2']]) {
			return false;
		}
		return true;
	}
	
	/**
	 * Custom validation method: betweenIntRange()
	 * Determines if a value is an integer within the specified range (including min and max!)
	 * 
	 * @param value array the value to check as array(field => value)
	 * @param params array('min' => 0, 'max' => 255) [default]
	 * @return bool true if the value of $value is an integer between min and max
	 */
	function betweenIntRange($value, $params = array()) {		
		$min = 0;
		$max = 255;
		extract($params);
		if (is_array($value)) {
			$value = array_shift($value);
		}
		if (preg_match('/^[0-9]+$/', $value)) {
			if ($value >= $min && $value <= $max) {
				return true;
			}
		}		
		return false;
	}
	
	/**
	 * Custom validation method: restrictToList()
	 * Ensures that the text-based value matches a selection from the list
	 * 
	 * @param array $params the postback form data
	 * @param string $model the model to validate against (set in the validation rule)
	 * @param string $textField the name of the form field (set in the validation rule)
	 * @param string $list the name of the Model::method() to use for validating the
	 * 		text-based value against the list (set in the validation rule but defined
	 * 		in the model)
	 * 		If null (default), Model::field('name') is used as the verification routine
	 */
	public function restrictToList($params, $model, $textField = null, $list = null) {
		$value = current($params);
		if (empty($value) && (!empty($textField) && !empty($this->data[$this->alias][$textField]))) {
			/**
			 * This case fails because no value was selected from the predefined list,
			 * 		but the $textField value was posted back.
			 * This means the user tried to manually enter their own value, which
			 * 		is disallowed.
			 */
			return false;
		}
		if (!empty($value)) {
			if ($list === null) {
				// Check against Model::name field
				if ($name = $this->{$model}->field('name', array('id' => $value))) {
					return true;
				}
			} else {
				// Check against custom Model method
				if ($name = $this->{$model}->{$list}($value)) {
					return true;
				}
			}
			return false;
		}
		/**
		 * Allow the postback form field value to be empty
		 * If it should be required, use rule => notEmpty in addition to this one
		 */
		return true;
	}
	
	/**
	 * Shell hack to get the mime type of a file for PHP installs
	 * 		without the mime_content_type() function available
	 * 
	 * @param $file string full system path to file
	 */
	function getMimeType($file) {
		if (!function_exists('mime_content_type')) {
			$f = escapeshellarg($file);
			if ($mimeType = trim( `file -b --mime $f` )) {
				// The --mime parameter will return more information than necessary
				// i.e. "text/plain; charset=us-ascii" vs. "text/plain"
				$mimeParts = explode('; ', $mimeType);
				return $mimeParts[0];
			}
		}
		return mime_content_type($file);
	}
	
	/**
	 * 
	 * Takes two time elements and returns the difference (in minutes)
	 * @param Time $time1
	 * @param Time $time2
	 */
	function calculateTimeDiff($time1, $time2) {
		$time1 = new DateTime($time1);
		$time2 = new DateTime($time2);
		$interval = $time1->diff($time2);
		$h = intval($interval->format('%h')*60);
		$m = intval($interval->format('%i'));
		return $h + $m;
	}
}