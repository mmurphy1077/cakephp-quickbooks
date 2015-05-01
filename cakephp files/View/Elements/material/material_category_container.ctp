<?php 
if(!isset($mode)) {
	$mode = 'material';
} 
if(!isset($container)) {
	$container = 'catalog';
} ?>
<div class="material-category-container">
	<?php 
	$item_count = '';
	if(isset($category_item_count) && array_key_exists($category['Material']['id'], $category_item_count) && !empty($category_item_count[$category['Material']['id']]['child_item_count'])) {
		$item_count = '(<div class="inline category-count" id="category-count-' . $category['Material']['id'] . '">' . $category_item_count[$category['Material']['id']]['child_item_count'] . '</div> items)';
	} else {
		$category_item_count = null;
	}
	?>
	<div id="<?php echo $category['Material']['id']; ?>-<?php echo $container; ?>" class="category-header toggle_display_button button">
		<div class="name"><b><?php echo $category['Material']['name']; ?></b>&nbsp;&nbsp;<?php echo $item_count; ?></div>
		<div class="action">
			<!-- <div id="materials_button_<?php #echo $category['Material']['id']; ?>" class="materials_button collapse button"></div>  -->
			<?php #echo $this->Html->link(__('Add '.Configure::read('Nomenclature.Material')), '#material-container', array('class' => 'div-clicktip-material', 'id' => 'parent-id-'. $category['Material']['id'], 'rel' => '#material-container', 'title' => 'Add '.Configure::read('Nomenclature.Material').' to '.$category['Material']['name'])); ?>
			<?php #echo $this->Html->link($this->Html->image('button-arrow-right.png'), '#', array('id' => 'materials_button_close', 'class' => 'materials_button', 'escape' => false))?>
		</div>
		<div id="data-bank-<?php echo $category['Material']['id']; ?>" class="data-bank hide">
			<?php echo $this->Form->hidden('Material.id', array('disabled' => 'disabled', 'value' => $category['Material']['id'])); ?>
			<?php echo $this->Form->hidden('Material.is_category', array('disabled' => 'disabled', 'value' => $category['Material']['is_category'])); ?>
			<?php echo $this->Form->hidden('Material.material_type_id', array('disabled' => 'disabled', 'value' => $category['Material']['material_type_id'])); ?>
			<?php echo $this->Form->hidden('Material.name', array('disabled' => 'disabled', 'value' => $category['Material']['name'])); ?>
			<?php echo $this->Form->hidden('Material.description', array('disabled' => 'disabled', 'value' => $category['Material']['description'])); ?>
			<?php echo $this->Form->hidden('Material.price_per_unit', array('disabled' => 'disabled', 'value' => $category['Material']['price_per_unit'])); ?>
			<?php echo $this->Form->hidden('Material.uom_id', array('disabled' => 'disabled', 'value' => $category['Material']['uom_id'])); ?>
			<?php echo $this->Form->hidden('Material.parent_id', array('disabled' => 'disabled', 'value' => $category['Material']['parent_id'])); ?>
		</div>
	</div>
	<div id="<?php echo $category['Material']['id']; ?>-<?php echo $container; ?>_toggle_display" class="category-child-container hide">
		<div class="category-container-pad left">&nbsp;</div>
		<div class="category-container-children left">
		<?php if(!empty($category['children'])) :  ?>
		<?php 	foreach($category['children'] as $child) : ?>
		<?php 		if(!$child['Material']['is_category']) {
						// Call Item Element
						echo $this->element('material/material_item_container', array('item' => $child, 'mode' => $mode));
					} else { 
						//  Call Category element
						echo $this->element('material/material_category_container', array('category' => $child, 'category_item_count' => $category_item_count, 'mode' => $mode, 'container' => $container)); 
		 			}?>
		<?php endforeach;?>
		<?php endif; ?>
		</div>
	</div>
</div>