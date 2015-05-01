<?php 
$id = null;
$name = null;
$number = null;
$sort = null;
if(!empty($data)) {
	$id = $data['id'];
	$name = $data['name'];
	$number = $data['number'];
	$sort = $data['sort'];
} ?>
<tr class="license-item" id="license_item_<?php echo $index; ?>">
	<td class="drag tight"><?php echo $this->Html->image('icon-drag.png'); ?></td>
	<td class="license-name">
		<?php echo $this->Form->hidden('License.id', array('value' => $id, 'id'=>'license_id_'.$index, 'class'=>'id', 'name' => 'data[License]['.$index.'][id]')); ?>
		<?php echo $this->Form->input('License.name', array('value' => $name, 'id'=>'license_name_'.$index, 'class'=>'license_name', 'name' => 'data[License]['.$index.'][name]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="license-license">
		<?php echo $this->Form->input('License.number', array('value' => $number, 'id'=>'license_number_'.$index, 'class' => 'license_number', 'name' => 'data[License]['.$index.'][number]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="license-delete tight">
		<?php 
		$link = '#';
		if(!empty($id)) {
			$link = array('controller' => 'business360s', 'action' => 'delete_licenses', $id);
		}
		echo $this->Html->link('&#x2715;', $link, array('class' => 'license-item-delete delete-button grey', 'id' => 'license-item-delete-' . $index, 'escape' => false), __('delete_confirm')); ?>
	</td>
</tr>