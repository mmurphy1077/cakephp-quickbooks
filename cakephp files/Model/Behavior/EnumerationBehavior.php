<?php
class EnumerationBehavior extends ModelBehavior {	
	
	public $__defaultSettings = array(
		'useTable' => 'enumerations',
	);
	
	public function setup(&$model, $settings = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->__defaultSettings;
		}
		$this->settings[$model->alias] = Set::merge($this->settings[$model->alias], (array)$settings);
		$model->useTable = $this->settings[$model->alias]['useTable'];
	}
	
	public function beforeFind(&$model, $queryData) {
		$queryData['conditions'] = Set::merge($queryData['conditions'], array($model->alias.'.model' => $model->alias));
		return $queryData;
	}
		
}
?>