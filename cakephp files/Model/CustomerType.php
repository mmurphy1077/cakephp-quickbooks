<?php
App::uses('AppModel', 'Model');
class CustomerType extends AppModel {

	public $name = 'CustomerType';
	public $actsAs = array('Enumeration');
	
}
?>