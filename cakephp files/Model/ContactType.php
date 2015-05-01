<?php
App::uses('AppModel', 'Model');
class ContactType extends AppModel {

	public $name = 'ContactType';
	public $actsAs = array('Enumeration');
	
}
?>