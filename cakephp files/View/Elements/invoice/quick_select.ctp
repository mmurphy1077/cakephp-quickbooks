<?php 
switch($type) {
	case 'labor' :
		$options = $quick_select['Options']['Labor'];
		break;
	case 'material' :
		$options = $quick_select['Options']['Material'];
		break;
	default :
		$options = $quick_select['Options']['All'];
}
?>
<div id="combo-box-container-<?php echo $target; ?>" class="combo-box-container left">
	<div class="label inline">Quick Select</div>
	<?php echo $this->Form->input($target . '-combobox', array('id' => $target . '-combobox', 'class' => $target . '-combobox', 'options' => $options, 'empty' => '', 'label' => false, 'div' => array('id' => 'combo-select', 'class' => 'input select combo-select'))); ?>
	<div id="<?php echo $target; ?>-data-bank" class="data-bank hide">
		<?php if(!empty($quick_select['Bank'])) : ?>
		<?php 	foreach($quick_select['Bank'] as $bank_item) : ?>
		<div id="<?php echo $bank_item['slug']; ?>" class="data-bank-item">
			<?php echo $this->Form->hidden('description', array('value' => $bank_item['description'], 'id' => 'description')); ?>
			<?php echo $this->Form->hidden('unit_item_cost', array('value' => $bank_item['unit_item_cost'], 'id' => 'unit_item_cost')); ?>
			<?php echo $this->Form->hidden('unit', array('value' => $bank_item['unit'], 'id' => 'unit')); ?>
		</div>
		<?php 	endforeach; ?>
		<?php endif; ?>
	</div>
</div>