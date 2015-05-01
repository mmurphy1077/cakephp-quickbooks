<?php 
/**
 * MATERIAL STOCK
 */ ?>
<!-- <h3>Materials</h3> -->
<?php 
if(!empty($this->data['MaterialSummary']['material-associated']) || !empty($this->data['MaterialSummary']['not-associated'])) : ?>
	<?php 	
	if(array_key_exists('material-associated', $this->data['MaterialSummary']) && !empty($this->data['MaterialSummary']['material-associated'])) :
		foreach($this->data['MaterialSummary']['material-associated'] as $material) :
			$uom_material = null; 
			$uom_material_display = null;
			if(!empty($material['Material']['uom_id'])) {
				$uom_material = $material['Uom']['name'];
				$uom_material_display = $uom_material;
				if($uom_material == 'each') {
					$uom_material_display = '';
				}
			} ?>
		<table id="step3_vanstock_material_summary" class="material_summary nohover standard step3_table">
			<tr>
				<th><?php echo $material['Material']['name']; ?></th>
				<th>Qty</th>
				<th>Act. Cost</th>
				<th>Cost</th>
				<th>Suggested</th>
				<th>&nbsp;</th>
			</tr>
			<?php if(!empty($material['OrderMaterialItem'])) : ?>
			<?php 	$qty = 0;
					$price_per_unit = 0; 
					$total = 0;
					foreach($material['OrderMaterialItem'] as $material_item ) :
						$uom = null; 
						$uom_display = '';
						if(!empty($material_item['uom_id'])) {
							$uom = $material_item['Uom']['name'];
							$uom_display = '/' . $uom;
							if($uom == 'each') { 
								$uom_display = $uom;
							}
						}
						$qty = $qty + number_format($material_item['qty'], 2); 
						$price_per_unit = $price_per_unit + $material_item['price_per_unit']; 
						$total = $total + ($material_item['qty'] * $material_item['price_per_unit']); ?>
					<tr>
						<td><?php echo $this->Web->dt($material_item['created'],'short_4'); ?></td>
						<!-- <td><?php echo $material_item['name']; ?></td> -->
						<td><?php echo number_format($material_item['qty'], 2); ?></td>
						<td><?php echo '$'.number_format($material_item['price_per_unit_actual'], 2); ?> <?php echo $uom_display; ?></td>
						<td><?php echo '$'.number_format($material_item['price_per_unit'], 2); ?> <?php echo $uom_display; ?></td>
						<td><?php echo '$'.number_format($material_item['qty'] * $material_item['price_per_unit'], 2); ?></td>
						<td id="data-bank-cell" class="align-right">
							<?php if(empty($permission_attr)) : ?>
							<?php echo $this->Html->link('include', array('#'), array('id' => 'material-item-' . $material_item['id'], 'class' => 'invoice-material-item-include')); ?>
							<div id="data-bank-material-item-<?php echo $material_item['id']?>" class="hide data-bank">
								<?php echo $this->Form->hidden('DataBank.description', array('id' => 'data-bank-item-description', 'class' => 'data-bank-item', 'value' => 'Total ' . $material_item['name'])); ?>
								<?php echo $this->Form->hidden('DataBank.price_per_unit', array('id' => 'data-bank-item-price', 'class' => 'data-bank-item', 'value' => number_format($material_item['price_per_unit'], 2, '.', ''))); ?>
								<?php echo $this->Form->hidden('DataBank.qty', array('id' => 'data-bank-item-qty', 'class' => 'data-bank-item', 'value' => number_format($material_item['qty'], 2, '.', ''))); ?>
								<?php echo $this->Form->hidden('DataBank.name', array('id' => 'data-bank-item-name', 'class' => 'data-bank-item', 'value' => $material_item['name'])); ?>
								<?php echo $this->Form->hidden('DataBank.date', array('id' => 'data-bank-item-date', 'class' => 'data-bank-item', 'value' => $this->Web->dt($material_item['created'], 'text_short'))); ?>
								<?php echo $this->Form->hidden('DataBank.date_short', array('id' => 'data-bank-item-date-short', 'class' => 'data-bank-item', 'value' => date('m/d', strtotime($material_item['created'])))); ?>
								<?php echo $this->Form->hidden('DataBank.total', array('id' => 'data-bank-item-total', 'class' => 'data-bank-item', 'value' => number_format($material_item['qty'] * $material_item['price_per_unit'], 2, '.', ''))); ?>
								<?php echo $this->Form->hidden('DataBank.uom', array('id' => 'data-bank-item-uom', 'class' => 'data-bank-item', 'value' => $uom)); ?>
							</div>
							<?php endif; ?>
						</td>
					</tr>
			<?php 	endforeach; ?>
				<tr class="summary-row">
					<td><?php echo 'Total'; ?></td>
					<td colspan="2"><?php echo number_format($qty, 2); ?> <?php echo $uom_material_display; ?></td>
					<td><?php #echo '$'.number_format($price_per_unit, 2); ?></td>
					<td><?php echo '$'.number_format($total, 2); ?></td>
					<td id="data-bank-cell" class="align-right">
						<?php if(empty($permission_attr)) : ?>
						<?php echo $this->Html->link('include', array('#'), array('id' => 'material-item-summary-' . $material_item['material_id'], 'class' => 'invoice-material-item-summary-include')); ?>
						<div id="data-bank-material-item-summary-<?php echo $material_item['material_id']?>" class="hide data-bank">
							<?php echo $this->Form->hidden('DataBank.description', array('id' => 'data-bank-item-description', 'class' => 'data-bank-item', 'value' => 'Total ' . $material['Material']['name'])); ?>
							<?php echo $this->Form->hidden('DataBank.price_per_unit', array('id' => 'data-bank-item-price', 'class' => 'data-bank-item', 'value' => number_format($price_per_unit, 2, '.', ''))); ?>
							<?php echo $this->Form->hidden('DataBank.qty', array('id' => 'data-bank-item-qty', 'class' => 'data-bank-item', 'value' => $qty)); ?>
							<?php echo $this->Form->hidden('DataBank.name', array('id' => 'data-bank-item-name', 'class' => 'data-bank-item', 'value' => $material['Material']['name'])); ?>
							<?php echo $this->Form->hidden('DataBank.date', array('id' => 'data-bank-item-date', 'class' => 'data-bank-item', 'value' => null)); ?>
							<?php echo $this->Form->hidden('DataBank.date_short', array('id' => 'data-bank-item-date-short', 'class' => 'data-bank-item', 'value' => null)); ?>
							<?php echo $this->Form->hidden('DataBank.total', array('id' => 'data-bank-item-total', 'class' => 'data-bank-item', 'value' => number_format($total, 2, '.', ''))); ?>
							<?php echo $this->Form->hidden('DataBank.uom', array('id' => 'data-bank-item-uom', 'class' => 'data-bank-item', 'value' => $uom_material)); ?>
						</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endif; ?>
		</table>
		<br /><br />
	<?php 	endforeach; 
	endif; ?>
	<?php 
	if(array_key_exists('not-associated', $this->data['MaterialSummary']) && !empty($this->data['MaterialSummary']['not-associated'])) : ?>
	<table id="step3_vanstock_material_summary" class="material_summary  nohover standard step3_table">
		<tr>
			<th colspan="2">&nbsp;</th>
			<th>Qty</th>
			<th>Act. Cost</th>
			<th>Cost</th>
			<th>Suggested</th>
			<th>&nbsp;</th>
		</tr>
	<?php 
	foreach($this->data['MaterialSummary']['not-associated'] as $material) : ?>
		<tr>
			<td><?php echo $this->Web->dt($material['OrderMaterial']['date_session'],'short_4'); ?></td>
			<td><?php echo $material['OrderMaterialItem']['name']; ?></td>
			<td><?php echo number_format($material['OrderMaterialItem']['qty'], 2); ?></td>
			<td><?php echo '$'.number_format($material['OrderMaterialItem']['price_per_unit_actual'], 2); ?></td>
			<td><?php echo '$'.number_format($material['OrderMaterialItem']['price_per_unit'], 2); ?></td>
			<td><?php echo '$'.number_format($material['OrderMaterialItem']['qty'] * $material['OrderMaterialItem']['price_per_unit'], 2); ?></td>
			<td id="data-bank-cell" class="align-right">
				<?php if(empty($permission_attr)) : ?>
				<?php echo $this->Html->link('include', array('#'), array('id' => 'unassoc-material-item-' . $material['OrderMaterialItem']['id'], 'class' => 'invoice-unassoc-material-item-include')); ?>
				<div id="data-bank-unassoc-material-item-<?php echo $material['OrderMaterialItem']['id']; ?>" class="hide data-bank">
					<?php echo $this->Form->hidden('DataBank.description', array('id' => 'data-bank-item-description', 'class' => 'data-bank-item', 'value' => 'Total ' . $material['OrderMaterialItem']['name'])); ?>
					<?php echo $this->Form->hidden('DataBank.price_per_unit', array('id' => 'data-bank-item-price', 'class' => 'data-bank-item', 'value' => number_format($material['OrderMaterialItem']['price_per_unit'], 2, '.', ''))); ?>
					<?php echo $this->Form->hidden('DataBank.qty', array('id' => 'data-bank-item-qty', 'class' => 'data-bank-item', 'value' => $material['OrderMaterialItem']['qty'])); ?>
					<?php echo $this->Form->hidden('DataBank.total', array('id' => 'data-bank-item-total', 'class' => 'data-bank-item', 'value' => number_format($material['OrderMaterialItem']['qty'] * $material['OrderMaterialItem']['price_per_unit'], 2, '.', ''))); ?>
					<?php echo $this->Form->hidden('DataBank.name', array('id' => 'data-bank-item-name', 'class' => 'data-bank-item', 'value' => $material['OrderMaterialItem']['name'])); ?>
					<?php echo $this->Form->hidden('DataBank.date', array('id' => 'data-bank-item-date', 'class' => 'data-bank-item', 'value' => $this->Web->dt($material['OrderMaterial']['date_session'],'text_short'))); ?>
					<?php echo $this->Form->hidden('DataBank.date_short', array('id' => 'data-bank-item-date-short', 'class' => 'data-bank-item', 'value' => date('m/d', strtotime($material['OrderMaterial']['date_session'])))); ?>
					<?php echo $this->Form->hidden('DataBank.uom', array('id' => 'data-bank-item-uom', 'class' => 'data-bank-item', 'value' => $material['Uom']['name'])); ?>
				</div>
				<?php endif; ?>
			</td>
		</tr>
	<?php 	endforeach; ?>
	</table>
	<br />
	<?php endif; 
else: ?>
<b>No un-billed Vanstock Items at this time.</b>
<?php endif; ?>