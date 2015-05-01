<?php 
if(!isset($mode)) {
	$mode = 'material';
} 
if(!isset($container)) {
	$container = 'catalog';
}
if(!isset($assemblies)) {
	$assemblies = null;
}
if(!empty($results)) {
	foreach ($results as $result) {
		if(!$result['Material']['is_category']) {
			// Call Item Element
			echo $this->element('material/material_item_container', array('item' => $result, 'mode' => $mode));
		} else {
			//  Call Category element
			echo $this->element('material/material_category_container', array('category' => $result, 'category_item_count' =>$category_item_count, 'mode' => $mode, 'container' => $container));
		}
	}
}
if($container == 'catalog' && !empty($assemblies)) {
	foreach ($assemblies as $assembly) { 
		echo $this->element('material/assembly_container', array('assembly' => $assembly));
	}
} ?>