<?php
class MultiModelSearchBehavior extends ModelBehavior {
	
	var $name = 'MultiModelSearch';
	var $searchGroups = null;
	var $__defaultSettings = array(
		'searchGroupsPropertyName' => 'searchGroups',
	);
	
	/**
	 * Sets up configuration for the model
	 * @see cake/libs/model/ModelBehavior#setup($model, $config)
	 */
	function setup(Model $model, $config = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->__defaultSettings;
		}
		$this->settings[$model->alias] = Set::merge($this->settings[$model->alias], (array)$config);
		if (property_exists($model, $this->settings[$model->alias]['searchGroupsPropertyName'])) {
			$this->searchGroups = $model->searchGroups;
		}
	}
	
	function getSearchGroups($model, $for = 'publicSelect') {
		$searchGroups = array();
		if (is_array($model->searchGroups)) {
			switch ($for) {
				case 'publicSelect':
					foreach ($model->searchGroups as $searchGroup => $options) {
						$searchGroups[$searchGroup] = $options['name'];
					}
					break;
				case 'search':
					foreach ($model->searchGroups as $searchGroup) {
						$searchGroups[] = $searchGroup;
					}
					break;
			}
		}
		return $searchGroups;
	}
	
	function search($model, $data, $limit = 10) {
		$model->data = $data;
		// Build a list of search groups based on postback data
		$searchGroups = array();
		if (array_key_exists('searchGroups', $data[$model->alias]) && is_array($data[$model->alias]['searchGroups'])) {
			foreach ($data[$model->alias]['searchGroups'] as $searchGroup => $checked) {
				$searchGroups[] = $model->searchGroups[$searchGroup];
			}
		} else {
			$searchGroups = $model->getSearchGroups('search');
		}
		// Loop through search group data for model instantiation and querying
		foreach ($searchGroups as $iSearchGroup => $searchGroup) {
			// Execute a search against each group independently, index by $searchGroup['name']
			if (!empty($searchGroup['models']) && is_array($searchGroup['models'])) {
				foreach ($searchGroup['models'] as $searchModel => $options) {
					if (!array_key_exists('limit', $options)) {
						$options['limit'] = $limit;
					}
					if (array_key_exists('keyword', $data[$model->alias]) && !empty($data[$model->alias]['keyword'])) {
						$options['keyword'] = $data[$model->alias]['keyword'];
					}
					if (array_key_exists('status', $data[$model->alias])) {
						$options['status'] = $data[$model->alias]['status'];
					}
					$searchGroups[$iSearchGroup]['models'][$searchModel] = Set::merge($options, $this->__searchModel($model, $searchModel, $options));
				}
			}
		}
		return $searchGroups;
	}

	function __searchModel($model, $searchModel, $options) {
		
		$results = array();
		$conditions = $this->buildSearchConditions($model, $searchModel, $options);
		$Model = ClassRegistry::init($searchModel);
#		$Model->contain();
		$Model->contain($options['contain']);
		$results['count'] = $Model->find('count', array('conditions' => $conditions));
		
		$results['results'] = $Model->find('all', array(
			'conditions' => $conditions,
			'contain' => $options['contain'], 
			'limit' => $options['limit'],
			'order' => $options['order'],
		));
		return $results;
	}
	
	function __searchCallbacks($model, $type, $options) {
		switch ($type) {
			case 'moduleIdsByMatchingProducts':
				// Get Product::id values matching search conditions that are also Integrated Products
				// Get Module::id values matching found Product::ids
				$Product =& ClassRegistry::init('Product');
				if (!empty($options['keyword'])) {
					$conditions = $this->__buildFieldConditions($model, 'Product', array('name', 'description'), $options['keyword']);
				}
				$conditions['AND']['Product.availability'] = array(PRODUCT_AVAILABILITY_BOTH_ID, PRODUCT_AVAILABILITY_DISTRIBUTED_ID);
				if (!empty($options['status'])) {
					$conditions['AND']['Product.status'] = $options['status'];
				}
				$productIds = $Product->find('list', array(
					'fields' => array('id', 'id'),
					'conditions' => $conditions,
				));
				if (!empty($productIds)) {
					$moduleIds = $Product->ModulesProduct->find('list', array(
						'fields' => array('module_id', 'module_id'),
						'conditions' => array('product_id' => $productIds),
					));
					return array(
						'OR' => array(
							'Module.id' => $moduleIds,
						),
					);
				}
				return null;
				break;
			case 'productManufacturerIds':
				if (array_key_exists('Product', $model->data) && array_key_exists('product_manufacturer_id', $model->data['Product']) && !empty($model->data['Product']['product_manufacturer_id'])) {
					return array(
						'AND' => array(
							'Product.product_manufacturer_id' => $model->data['Product']['product_manufacturer_id'],
						),
					);
				}
				break;
			case 'productIdsByCategory':
				if (array_key_exists('Product', $model->data) && array_key_exists('category_id', $model->data['Product']) && !empty($model->data['Product']['category_id'])) {
					return array(
						'AND' => array(
							'Product.id' => $model->CategoriesProduct->find('list', array('fields' => array('product_id', 'product_id'), 'conditions' => array('category_id' => $model->data['Product']['category_id']))),
						),
					);
				}
				break;
			case 'productIdsByMissionType':
				if (array_key_exists('Product', $model->data) && array_key_exists('mission_type_id', $model->data['Product']) && !empty($model->data['Product']['mission_type_id'])) {
					return array(
						'AND' => array(
							'Product.id' => $model->MissionTypesProduct->find('list', array('fields' => array('product_id', 'product_id'), 'conditions' => array('mission_type_id' => $model->data['Product']['mission_type_id']))),
						),
					);
				}
				break;
		}
	}
	
	function buildSearchConditions($model, $searchModel, $options) {
		$conditions = array();
		if (!empty($options['fields']) && !empty($options['keyword'])) {
			$conditions = $this->__buildFieldConditions($model, $searchModel, $options['fields'], $options['keyword']);
		}
		if (!empty($options['status'])) {
			$conditions['AND'][$searchModel.'.status'] = $options['status_value'];
		}
		if (!empty($options['conditions'])) {
			if (!array_key_exists('AND', $conditions)) {
				$conditions['AND'] = array();
			}
			$conditions['AND'] = Set::merge($conditions['AND'], $options['conditions']);
		}
		if (!empty($options['callbacks'])) {
			$callbacks = $options['callbacks'];
			if (!is_array($callbacks)) {
				$callbacks = array($callbacks);
			}
			foreach ($callbacks as $callback) {
				$conditions = Set::merge($conditions, $this->__searchCallbacks($model, $callback, $options));
			}
		}
		return $conditions;
	}
	
	function __buildFieldConditions($model, $searchModel, $fields, $keyword) {
		$conditions = array();
		if (!is_array($fields)) {
			$fields = array($fields);
		}
		foreach ($fields as $field) {
			if(strpos($field, '.')) {
				#$conditions['OR'][$field.' LIKE'] = '%'.mysqli_real_escape_string($keyword).'%';
				$conditions['OR'][$field.' LIKE'] = '%'.$keyword.'%';
			} else {
				#$conditions['OR'][$searchModel.'.'.$field.' LIKE'] = '%'.mysqli_real_escape_string($keyword).'%';
				$conditions['OR'][$searchModel.'.'.$field.' LIKE'] = '%'.$keyword.'%';
			}
		}
		return $conditions;
	}
	
}
?>