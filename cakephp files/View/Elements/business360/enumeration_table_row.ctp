<?php 
$id = null;
$name = null;
$sort = null;
if(!empty($data)) {
	$id = $data['id'];
	$name = $data['name'];
	$sort = $data['sort'];
} ?>
<tr class="enumeration-item" id="enumeration_item_<?php echo $index; ?>">
	<td class="drag tight"><?php echo $this->Html->image('icon-drag.png'); ?></td>
	<td class="enumeration-name">
		<?php echo $this->Form->hidden('Enumeration.id', array('value' => $id, 'id'=>'enumeration_id_'.$index, 'class'=>'id', 'name' => 'data[Enumeration]['.$index.'][id]')); ?>
		<?php echo $this->Form->input('Enumeration.name', array('value' => $name, 'id'=>'enumeration_name_'.$index, 'class'=>'full', 'name' => 'data[Enumeration]['.$index.'][name]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="enumeration-delete tight">
		<?php 
		$link = '#';
		if(!empty($id)) {
			$link = array('controller' => 'business360s', 'action' => 'delete_enumeration', $id);
		}
		echo $this->Html->link('&#x2715;', $link, array('class' => 'enumeration-item-delete delete-button grey', 'id' => 'enumeration-item-delete-' . $index, 'escape' => false), __('delete_confirm')); ?>
	</td>
</tr>