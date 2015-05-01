<div class="material-category-container">
	<div id="assembly-<?php echo $assembly['MaterialAssembly']['id']; ?>" class="assembly-header category-header toggle_display_button button">
		<b><div class="name"><?php echo $assembly['MaterialAssembly']['name']; ?></div></b>
	</div>
	<div id="assembly_button_<?php echo $assembly['MaterialAssembly']['id']; ?>" class="materials_button assembly_button button">add assembly</div>
	<div id="assembly-<?php echo $assembly['MaterialAssembly']['id']; ?>_toggle_display" class="category-child-container hide">
		<div class="category-container-pad left">&nbsp;</div>
		<div id="assembly-container-children-<?php echo $assembly['MaterialAssembly']['id']; ?>" class="category-container-children left">
		<?php if(!empty($assembly['Material'])) :  ?>
		<?php 	foreach($assembly['Material'] as $child) : ?>
		<?php 		$data['Material'] = $child;
					$data['Uom'] = $child['Uom'];
					echo $this->element('material/material_item_container', array('item' => $data)); ?>
		<?php endforeach;?>
		<?php endif; ?>
		</div>
	</div>
</div>