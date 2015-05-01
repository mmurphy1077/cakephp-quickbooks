<ul class="stats" id="taxonomy_<?php echo $parent_id; ?>">
	<?php foreach ($taxonomies as $taxonomy): ?>
		<li>
			<span class="label">
				<?php #echo $this->Html->link($this->Html->image('arrow-up.gif'), array('controller' => 'calculation_taxonomies', 'action' => 'move', 'up', $taxonomy['CalculationTaxonomy']['id']), array('class' => 'cluetip', 'title' => __('Move '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy').' up one.'), 'escape' => false)); ?>
				<?php #echo $this->Html->link($this->Html->image('arrow-down.gif'), array('controller' => 'calculation_taxonomies', 'action' => 'move', 'down', $taxonomy['CalculationTaxonomy']['id']), array('class' => 'cluetip', 'title' => __('Move '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy').' down one.'), 'escape' => false)); ?>
				<?php echo $this->Html->link($this->Html->image('arrow-up.gif'), '#', array('id' => $taxonomy['CalculationTaxonomy']['id'], 'class' => 'cluetip menu_item_move_up', 'title' => __('Move '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy').' up one.'), 'escape' => false)); ?>
				<?php echo $this->Html->link($this->Html->image('arrow-down.gif'), '#', array('id' => $taxonomy['CalculationTaxonomy']['id'], 'class' => 'cluetip menu_item_move_down', 'title' => __('Move '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy').' down one.'), 'escape' => false)); ?>
				
				<?php echo $this->Html->link($this->Html->image('icon-add-16.png'), array('controller' => 'calculation_taxonomies', 'action' => 'add', $taxonomy['CalculationTaxonomy']['id']), array('class' => 'cluetip', 'title' => __('Add to '.$taxonomy['CalculationTaxonomy']['name'].' '.Configure::read('Nomenclature.CalculationTaxonomy')), 'escape' => false)); ?>
				<?php echo $this->Html->link(__($taxonomy['CalculationTaxonomy']['name']), array('controller' => 'calculation_taxonomies', 'action' => 'edit', $taxonomy['CalculationTaxonomy']['id'])); ?>
			</span>
			<?php if (!empty($requireChild)): ?>
				<span class="value req"><?php echo __('Required'); ?></span>
			<?php else: ?>
				<span class="value opt"><?php echo __('Optional'); ?></span>
			<?php endif; ?>
			<?php if (!empty($taxonomy['children'])): ?>
				<?php echo $this->element('calculation_taxonomies', array('taxonomies' => $taxonomy['children'], 'requireChild' => $taxonomy['CalculationTaxonomy']['require_child'], 'parent_id' => $taxonomy['CalculationTaxonomy']['id'])); ?>
			<?php endif; ?>
		</li>
	<?php endforeach; ?>
</ul>