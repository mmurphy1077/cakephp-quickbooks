<?php
/**
 * Created: 2009-06-03
 * Author: Kevin DeCapite <www.decapite.net>
 * Source: CakePHP core AclBehavior
 */
class AclSystemBehavior extends ModelBehavior {	
	
	/**
	 * Map requester and controlled types to their respective shortcuts
	 * 
	 * @var string
	 */
	public $_requester = 'Aro';
	public $_controlled = 'Aco';
	/**
	 * Use to determine whether a model's record should
	 * belong to the Public or Restricted parent nodes
	 * 
	 * @var string
	 */
	public $_restricted = 'restricted';
	public $_defaultSettings = array(
		'Acl' => 'AclPermission',
		'type' => 'Aro',
		'related' => array(
			'Aro' => 'Aco',
			'Aco' => 'Aro',
		),
		'model' => array(
			'Aro' => 'AclControlled',
			'Aco' => 'AclRequester',
		),
	);
	
	/**
	 * Sets up configuration for the model
	 * @see cake/libs/model/ModelBehavior#setup($model, $config)
	 */
	public function setup(Model $model, $settings = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->_defaultSettings;
		}
		$this->settings[$model->alias] = Set::merge($this->settings[$model->alias], (array)$settings);
	}
	
	public function beforeValidate(Model $model) {
		$settings = $this->settings[$model->alias];
		// Make sure at least one Aro/Aco is selected if this record is restricted
		if (!empty($model->data[$model->alias][$this->_restricted])) {
			if (!is_array($model->data[$model->alias][$settings['related'][$settings['type']]]) || count($model->data[$model->alias][$settings['related'][$settings['type']]]) < 1) {
				$model->invalidate($settings['related'][$settings['type']]);
				return false;
			}
		}		
		return true;
	}
	
	/**
	 * Retrieves the Aro/Aco node for this model
	 *
	 * @param mixed $ref criteria for locating a specific node
	 * @return array result set of node path from children to parents
	 * @access public
	 */
	public function node(Model $model, $ref = null) {
		$node = array();
		// Get the id of the requested node		
		if (empty($ref)) {
			$ref = array('model' => $model->name, 'foreign_key' => $model->id);
		} elseif (!is_array($ref)) {
			$ref = array('alias' => $ref);
		}
		$id = $model->field('id', array($ref));
		// Get the full node path and reverse it
		if ($id) {
			$node = $model->getPath($id);
			if (!empty($node)) {
				$node = array_reverse($node);
			}
		}
		return $node;
	}
	
	/**
	 * Used to allow assignment of groups access to specific records
	 * While always allowing Admins
	 * Wrapper for $this->getFromRelated()
	 * Modifies result set to exclude Admins 
	 * 
	 * @param $exclude array list of group aliases to remove from result set
	 * @return array
	 */
	public function getRestrictionGroups(Model $model, $exclude = array('Administrator')) {
		$results = array();
		$restrictionGroups = $this->getFromRelated($model, array('model' => 'Group'));
		if (!empty($exclude)) {
			if (!is_array($exclude)) {
				$exclude = array($exclude);
			}
			foreach ($exclude as $group) {
				if ($i = array_search($group, $restrictionGroups)) {
					unset($restrictionGroups[$i]);
				}
			}
		}
		return $restrictionGroups;
	}

	/**
	 * Queries the associated model for a result set
	 * If current model is Aro, will query Aco via join table and vice-versa
	 * 
	 * @param $conditions array
	 * @param $type string default = "list"
	 * @param $fields array
	 * @return array
	 */
	public function getFromRelated(Model $model, $conditions = array(), $type = 'list', $fields = array('id', 'alias')) {
		$settings = $this->settings[$model->alias];
		return $model->{$settings['Acl']}->{$settings['model'][$settings['type']]}->find($type, array('conditions' => $conditions, 'fields' => $fields, 'order' => 'alias ASC'));
	}
	
	/**
	 * Queries join table for associated values
	 * 
	 * @param $conditions array
	 * @param $fields array
	 * @param $type string default = "list"
	 * @return array
	 */
	public function getRelated(Model $model, $conditions = array(), $fields = array('id', '_access'), $type = 'list') {
		$settings = $this->settings[$model->alias];
		return $model->{$settings['Acl']}->find($type, array('fields' => $fields, 'conditions' => $conditions));		
	}
}

?>
