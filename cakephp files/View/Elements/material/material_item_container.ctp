<?php if(!isset($mode)) {
	$mode = 'material';
}
?>
<div id="<?php echo $item['Material']['id']; ?>" class="<?php echo $mode; ?> material-item-container  button">
	<div class="grid">
		<div class="col-4of5">
			<div class="item-desc-block">
			<?php echo $item['Material']['name']; ?>
			<?php if(!empty($item['Material']['description'])) : ?>
				<span class="light small">
				&nbsp;&nbsp;-&nbsp;&nbsp;
				<?php echo $item['Material']['description']; ?>
				</span> 
			<?php endif; ?>
			</div>
		</div>
		<div class="col-1of5">
			<?php $per_uom = '';
			if(array_key_exists('Uom', $item) && !empty($item['Uom'])) {
				$per_uom = 'per ' . $item['Uom']['name'];
			} ?>
			<div class="inline" id="<?php echo $item['Material']['id']; ?>-price-per-unit"><?php echo $this->Number->currency($item['Material']['price_per_unit']); ?></div>&nbsp;<?php echo $per_uom; ?><br />
			<div class="inline small light" id="<?php echo $item['Material']['id']; ?>-price-per-unit-actual">actual cost: <?php echo $this->Number->currency($item['Material']['price_per_unit_actual']); ?></div>
		</div>
		<div id="data-bank-<?php echo $item['Material']['id']; ?>" class="data-bank hide">
			<?php echo $this->Form->hidden('Material.id', array('disabled' => 'disabled', 'value' => $item['Material']['id'])); ?>
			<?php echo $this->Form->hidden('Material.is_category', array('disabled' => 'disabled', 'value' => $item['Material']['is_category'])); ?>
			<?php echo $this->Form->hidden('Material.material_type_id', array('disabled' => 'disabled', 'value' => $item['Material']['material_type_id'])); ?>
			<?php echo $this->Form->hidden('Material.name', array('disabled' => 'disabled', 'value' => $item['Material']['name'])); ?>
			<?php echo $this->Form->hidden('Material.description', array('disabled' => 'disabled', 'value' => $item['Material']['description'])); ?>
			<?php echo $this->Form->hidden('Material.price_per_unit', array('disabled' => 'disabled', 'value' => number_format($item['Material']['price_per_unit'], 2, '.', ''))); ?>
			<?php echo $this->Form->hidden('Material.price_per_unit_actual', array('disabled' => 'disabled', 'value' => number_format($item['Material']['price_per_unit_actual'], 2, '.', ''))); ?>
			<?php echo $this->Form->hidden('Material.uom_id', array('disabled' => 'disabled', 'value' => $item['Material']['uom_id'])); ?>
			<?php echo $this->Form->hidden('Material.favorite', array('disabled' => 'disabled', 'value' => $item['Material']['favorite'])); ?>
			<?php echo $this->Form->hidden('Material.parent_id', array('disabled' => 'disabled', 'value' => $item['Material']['parent_id'])); ?>
		</div>
	</div>
</div>