<?php
class CompanyEntityBehavior extends ModelBehavior {	
	
	public $__defaultSettings = array(
		'union' => 'CompaniesEntity',
		'model' => null,
	);
	
	public function setup(Model $Model, $settings = array()) {
		if (!isset($this->settings[$Model->alias])) {
			$this->settings[$Model->alias] = $this->__defaultSettings;
		}
		$this->settings[$Model->alias] = Set::merge($this->settings[$Model->alias], (array)$settings);
		if (empty($this->settings[$Model->alias]['model'])) {
			$this->settings[$Model->alias]['model'] = $Model->alias;
		}
		$this->__associate($Model);
	}
	
	private function __associate(Model $Model) {
		// Attach CompaniesEntity to implementation Model
		$Model->bindModel(array(
			'hasMany' => array(
				$this->settings[$Model->alias]['union'] => array(
					'foreignKey' => 'foreign_key',
					'conditions' => array($this->settings[$Model->alias]['union'].'.model' => $this->settings[$Model->alias]['model']),
				),
			),
		));
		// Attach implementation Model back to CompaniesEntity
		$Model->{$this->settings[$Model->alias]['union']}->bindModel(array(
			'belongsTo' => array(
				'Company',
			),
		));
	}
	
	public function beforeFind(Model $Model, $query) {
		if (empty($query['conditions']) || (array_key_exists($Model->alias.'.id', $query['conditions']) && !is_array($query['conditions'][$Model->alias.'.id']))) {
			// Do not perform filter if accessing a direct record (only filter on list pages)
			// !!! THIS SHOULD BE CHANGED SO CROSS-COMPANY ENTITY VIEWING DOESN'T HAPPEN !!!
			return $query;
		}
		$ids = $Model->{$this->settings[$Model->alias]['union']}->find('list', array(
			'fields' => array('id', 'foreign_key'),
			'conditions' => array(
				'company_id' => Configure::read('Company.Company.id'),
				'model' => $this->settings[$Model->alias]['model'],
			),
		));
		$query['conditions'] = Set::merge($query['conditions'], array($Model->alias.'.id' => $ids));
		return $query;
	}
	
	/**
	 * Register an association in the CompanyEntity model for newly
	 * 		created entities that are to be assigned to a Company
	 * Binds the CompanyEntity association and performs the save
	 * 
	 * @param Model $Model reference to the implementation model
	 * @param bool $created true if this is a new record
	 */
	public function afterSave(Model $Model, $created) {
		if ($created) {
			$id = $Model->getLastInsertID();
			$data = array(
				$this->settings[$Model->alias]['union'] => array(
					'company_id' => Configure::read('Company.Company.id'),
					'model' => $this->settings[$Model->alias]['model'],
					'foreign_key' => $id,
				),
			);
			$Model->{$this->settings[$Model->alias]['union']}->create();
			$Model->{$this->settings[$Model->alias]['union']}->save($data);
		}
	}
	
	public function afterDelete(Model $Model) {
		$Model->{$this->settings[$Model->alias]['union']}->deleteAll(array(
			'company_id' => Configure::read('Company.Company.id'),
			'model' => $this->settings[$Model->alias]['model'],
			'foreign_key' => $Model->id,
		));
	}
		
}
?>