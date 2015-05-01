<?php 
$id = null;
$name = null;
$rate = null;
$sort = null;
if(!empty($data)) {
	$id = $data['id'];
	$name = $data['name'];
	$rate = $data['rate'];
	$sort = $data['sort'];
} ?>
<tr class="rate-item" id="rate_item_<?php echo $index; ?>">
	<td class="drag tight"><?php echo $this->Html->image('icon-drag.png'); ?></td>
	<td class="rate-name">
		<?php echo $this->Form->hidden('Rate.id', array('value' => $id, 'id'=>'rate_id_'.$index, 'class'=>'id', 'name' => 'data[Rate]['.$index.'][id]')); ?>
		<?php echo $this->Form->input('Rate.name', array('value' => $name, 'id'=>'rate_name_'.$index, 'class'=>'rate_name', 'name' => 'data[Rate]['.$index.'][name]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="rate-rate number-container">
		<?php echo $this->Form->input('Rate.rate', array('value' => $rate, 'id'=>'rate_rate_'.$index, 'class' => 'num_only rate_rate', 'name' => 'data[Rate]['.$index.'][rate]', 'label' => false, 'div' => false)); ?>
	</td>
	<td class="rate-delete tight">
		<?php 
		$link = '#';
		if(!empty($id)) {
			$link = array('controller' => 'business360s', 'action' => 'deleteRate', $id);
		}
		echo $this->Html->link('&#x2715;', $link, array('class' => 'rate-item-delete delete-button grey', 'id' => 'rate-item-delete-' . $index, 'escape' => false), __('delete_confirm')); ?>
	</td>
</tr>