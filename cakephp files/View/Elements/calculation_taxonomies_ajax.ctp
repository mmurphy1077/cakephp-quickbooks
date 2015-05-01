<?php 
$echo_string = '';
#$echo_string = '<ul class="stats" id="'.$parent_id.'">';
	foreach ($taxonomies as $taxonomy) {
		$echo_string = $echo_string.'<li>';
			$echo_string = $echo_string.'<span class="label">';
				$echo_string = $echo_string. $this->Html->link($this->Html->image('arrow-up.gif'), '#', array('id' => $taxonomy['CalculationTaxonomy']['id'], 'class' => 'cluetip menu_item_move_up', 'title' => __('Move '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy').' up one.'), 'escape' => false));
				$echo_string = $echo_string. $this->Html->link($this->Html->image('arrow-down.gif'), '#', array('id' => $taxonomy['CalculationTaxonomy']['id'], 'class' => 'cluetip menu_item_move_down', 'title' => __('Move '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy').' down one.'), 'escape' => false));
				$echo_string = $echo_string. $this->Html->link($this->Html->image('icon-add-16.png'), array('controller' => 'calculation_taxonomies', 'action' => 'add', $taxonomy['CalculationTaxonomy']['id']), array('class' => 'cluetip', 'title' => __('Add to '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy')), 'escape' => false));
				$echo_string = $echo_string. $this->Html->link(__($taxonomy['CalculationTaxonomy']['name']), array('controller' => 'calculation_taxonomies', 'action' => 'edit', $taxonomy['CalculationTaxonomy']['id'])); 
			
				if (!empty($requireChild)) {
					$echo_string = $echo_string.'<span class="value req">' . __('Required') . '</span>';
				} else {
					$echo_string = $echo_string.'<span class="value opt">' . __('Optional') . '</span>';
				}
			
				if (!empty($taxonomy['children'])) {
					$echo_string = $echo_string . $this->element('calculation_taxonomies', array('taxonomies' => $taxonomy['children'], 'requireChild' => $taxonomy['CalculationTaxonomy']['require_child'], 'parent_id' => $taxonomy['CalculationTaxonomy']['id']));
				}
			
			$echo_string = $echo_string.'</span>';
		$echo_string = $echo_string.'</li>';
	}
#$echo_string = $echo_string. '</ul>';
echo $echo_string;
?>