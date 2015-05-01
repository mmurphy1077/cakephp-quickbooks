<?php
App::uses('AppModel', 'Model');
/**
 * User Model
 * 
 */
class Group extends AppModel {
	public $name = 'Group';
	public $hasOne = array(
		'Aro' => array(
			'className' => 'Aro',
			'foreignKey' => 'foreign_key',
			'conditions' => array('Aro.model' => 'Group'),
			'dependent' => true,
		),
	);
	public $belongsTo = array(
		#'Rate',
	);
	public $hasMany = array(
		'GroupJobType',
		'User',
	);
	public $hasAndBelongsToMany = array(
		'JobType',
	);
	
	public function getPublicList() {
		return $this->find('list', array('fields' => array('id', 'name'), 'conditions' => array('Group.public' => 1), 'order' => array('Group.id ASC')));
	}
	
	public function getJobTypes($group_id, $dropdown = 0) {
		$conditions = array('GroupsJobType.group_id' => $group_id);
		$fields = array('job_type_id', 'job_type_id');
		$results = $this->GroupsJobType->find('list', array('fields' => $fields, 'conditions' => $conditions));
		if($dropdown) {
			return $this->JobType->find('list', array('fields' => array('id', 'name'), 'conditions' => array('JobType.id' => $results), 'order' => array('JobType.name ASC')));
		} else {
			return $results;
		}
	}
	
	public function buildJobTypesByGroup() {
		// First find a list of groups
		$fields = array('id', 'id');
		$results = $this->find('list', array('fields' => $fields));
		if(!empty($results)) {
			foreach($results as $key=>$data) {
				$results[$key] = $this->getJobTypes($key, 1);
			}
		}
		return $results;
	}
	
	public function generateRateList() {
		/*
		$sql = "SELECT TRUNCATE(groups.rate,2) AS rate, CONCAT_WS(' - $', name, TRUNCATE(groups.rate,2)) as item FROM groups WHERE rate IS NOT NULL";
		$results = $this->query($sql);
		$list = array();
		if(!empty($results)) {
			foreach($results as $key=>$data) {
				$list[$data[0]['rate']] = $data[0]['item'];
			}
		}
		*/
		#$list = $this->Rate->getRates();
		return null;
	}
	
	public function generateRateDataBank() {
		#$list = $this->Rate->generateRateDataBank();
		return null;
	}
	
	public function getGroupRates() {
		$fields = array('id', 'rate');
		return $this->find('list', array('fields' => $fields));
	}
}
?>
