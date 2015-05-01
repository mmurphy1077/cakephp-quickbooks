<tr class="material-assembly-name-container nohover" id="material-assembly-name-container-<?php echo $data['MaterialAssembly']['id']; ?>">
	<td colspan="4"><?php echo $data['MaterialAssembly']['name']; ?></td>
	<td class="tight">
		<?php echo $this->Html->link('&#x2715;', '#', array('class' => 'assembly-delete delete-button grey', 'id' => 'assembly-delete-'.$data['MaterialAssembly']['id'], 'escape' => false)); ?>
	</td>
</tr>
<?php if(!empty($data['Material'])) : ?>
<?php 	foreach($data['Material'] as $material) : ?>
<tr id="material-assembly-item-<?php echo $material['id']; ?>" class="material-assembly-item-container hide material-assembly-item-container-<?php echo $data['MaterialAssembly']['id']; ?>">
	<td class="tight noborder">&nbsp;</td>
	<td class="noborder"><?php echo $material['name']; ?></td>
	<td class="noborder"><?php echo $material['description']; ?></td>
	<td class="noborder">$<?php echo number_format($material['price_per_unit'], 2); ?></td>
	<td class="noborder tight">
		<div id="assembly-id-contianer" class="hide"><?php echo $data['MaterialAssembly']['id']; ?></div>
		<?php echo $this->Html->link('&#x2715;', '#', array('class' => 'assembly-item-delete delete-button grey', 'id' => 'assembly-item-delete-'.$material['id'], 'escape' => false)); ?>
	</td>
</tr>
<?php 	endforeach; ?>
<?php endif; ?>