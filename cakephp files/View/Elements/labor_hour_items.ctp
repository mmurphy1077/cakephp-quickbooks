<?php #debug($this->data); ?>
<?php 
if(!isset($model)) {
	$model = 'Quote';
}
$model = $model.'LineItemLaborItem';
?>
<table id="labor_hour_items_container" class="tight nohover clear">
	<th>Hours</th>
	<th>Rate</th>
	<th>Qty</th>
	<th>Cost ($)</th>
	<th>&nbsp;</th>
	<?php 
	$i = 0;
	if(array_key_exists($model, $this->data) && !empty($this->data[$model])) :
		foreach($this->data[$model] as $data) :
			echo $this->element('labor_hour_item_table_row', array('index' => $i, 'data' =>$data, 'model' => $model));
			$i = $i + 1;
		endforeach; ?>
		<?php 
		// At least add one blank row.
		echo $this->element('labor_hour_item_table_row', array('index' => $i, 'data' =>null, 'model' => $model)); ?>
	<?php else: 
		if(!empty($rates)) {
			foreach($rates as $key=>$rate) {
				$rate = $key;
				$data['rate_id'] = $key;	
				$data['rate'] = $rates_data_banks[$key]['Rate']['rate'];
				$data['labor_qty'] = 1;
				echo $this->element('labor_hour_item_table_row', array('index' => $i, 'data' =>$data, 'model' => $model, 'rate' => $rate));
				$i = $i + 1;
			}
			// At least add one blank row.
			echo $this->element('labor_hour_item_table_row', array('index' => $i, 'data' =>null, 'model' => $model));
		} ?>
	<?php 
	endif; ?>
</table>
<?php echo $this->Html->link('add more', array('#'), array('id'=>'add_more_labor_items', 'class'=>'add_more clear right')); ?>
<div id="rate-data-bank" class="hide">
	<?php 
	if(!empty($rates_data_banks)) :
		foreach($rates_data_banks as $key => $rates_data_bank) : ?>
		<div id="rate-container-<?php echo $rates_data_bank['Rate']['id']; ?>" class="rate-container">
			<?php echo $this->form->hidden('id', array('id' => 'id', 'value' => $rates_data_bank['Rate']['id'])); ?>
			<?php echo $this->form->hidden('name', array('id' => 'name', 'value' => $rates_data_bank['Rate']['name'])); ?>
			<?php echo $this->form->hidden('rate', array('id' => 'rate', 'value' => $rates_data_bank['Rate']['rate'])); ?>
		</div>
	<?php endforeach;
	endif; ?>
</div>