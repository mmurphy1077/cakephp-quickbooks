<?php 
/**
 * MATERIAL PURCHASE
 */ ?>
<!-- <h3>Purchases</h3> -->
<?php 
if(!empty($this->data['PurchaseSummary']['not-associated'])) : ?>
	<table id="step3_vanstock_material_summary" class="material_summary nohover standard step3_table">
		<tr>
			<th>&nbsp;</th>
			<th>Act. Cost</th>
			<th>Suggested</th>
			<th>&nbsp;</th>
		</tr>
	<?php 	
	foreach($this->data['PurchaseSummary']['not-associated'] as $material) :  ?>
			<?php if(!empty($material['OrderMaterialItem'])) : ?>
			<?php 	$uom_display = '';
					$qty = number_format($material['OrderMaterialItem']['qty'], 2); 
					$price_per_unit = $material['OrderMaterialItem']['price_per_unit']; 
					$total = ($material['OrderMaterialItem']['qty'] * $material['OrderMaterialItem']['price_per_unit']); 
					if(!empty($material['OrderMaterialItem']['uom_id'])) {
						$uom = $material['Uom']['name'];
						$uom_display = '/' . $uom;
						if($uom == 'each') { 
							$uom_display = $uom;
						}
					} ?>
						
					<tr>
						<td><?php echo $material['OrderMaterial']['description']; ?></td>
						<td><?php echo '$'.number_format($material['OrderMaterialItem']['price_per_unit_actual'], 2); ?> <?php echo $uom_display; ?></td>
						<td><?php echo '$'.number_format($material['OrderMaterialItem']['price_per_unit'], 2); ?> <?php echo $uom_display; ?></td>
						<td id="data-bank-cell" class="align-right">
							<?php if(empty($permission_attr)) : ?>
							<?php echo $this->Html->link('include', array('#'), array('id' => 'material-item-' . $material['OrderMaterialItem']['id'], 'class' => 'invoice-purchase-item-include')); ?>
							<div id="data-bank-material-item-<?php echo $material['OrderMaterialItem']['id']?>" class="hide data-bank">
								<?php echo $this->Form->hidden('DataBank.description', array('id' => 'data-bank-item-description', 'class' => 'data-bank-item', 'value' => $material['OrderMaterial']['description'])); ?>
								<?php echo $this->Form->hidden('DataBank.price_per_unit', array('id' => 'data-bank-item-price', 'class' => 'data-bank-item', 'value' => number_format($material['OrderMaterialItem']['price_per_unit'], 2, '.', ''))); ?>
								<?php echo $this->Form->hidden('DataBank.qty', array('id' => 'data-bank-item-qty', 'class' => 'data-bank-item', 'value' => number_format($material['OrderMaterialItem']['qty'], 2))); ?>
								<?php echo $this->Form->hidden('DataBank.name', array('id' => 'data-bank-item-name', 'class' => 'data-bank-item', 'value' => $material['OrderMaterialItem']['name'])); ?>
								<?php echo $this->Form->hidden('DataBank.date', array('id' => 'data-bank-item-date', 'class' => 'data-bank-item', 'value' => $this->Web->dt($material['OrderMaterial']['date_session'], 'text_short'))); ?>
								<?php echo $this->Form->hidden('DataBank.date_short', array('id' => 'data-bank-item-date-short', 'class' => 'data-bank-item', 'value' => date('m/d', strtotime($material['OrderMaterial']['date_session'])))); ?>
								<?php echo $this->Form->hidden('DataBank.total', array('id' => 'data-bank-item-total', 'class' => 'data-bank-item', 'value' => number_format($material['OrderMaterialItem']['qty'] * $material['OrderMaterialItem']['price_per_unit'], 2, '.', ''))); ?>
								<?php echo $this->Form->hidden('DataBank.uom', array('id' => 'data-bank-item-uom', 'class' => 'data-bank-item', 'value' => $uom)); ?>
							</div>
							<?php endif; ?>
						</td>
					</tr>
					
				<tr class="summary-row">
					<td colspan="2"><?php echo 'Total'; ?></td>
					<td><?php echo '$'.number_format($total, 2); ?></td>
					<td id="data-bank-cell" class="align-right">
						<?php if(empty($permission_attr)) : ?>
						<?php echo $this->Html->link('include', array('#'), array('id' => 'material-item-summary-' . $material['OrderMaterialItem']['material_id'], 'class' => 'invoice-purchase-summary-include')); ?>
						<div id="data-bank-material-item-summary-<?php echo $material['OrderMaterialItem']['material_id']?>" class="hide data-bank">
							<?php echo $this->Form->hidden('DataBank.description', array('id' => 'data-bank-item-description', 'class' => 'data-bank-item', 'value' => 'Total Purchases')); ?>
							<?php echo $this->Form->hidden('DataBank.total', array('id' => 'data-bank-item-total', 'class' => 'data-bank-item', 'value' => number_format($total, 2, '.', ''))); ?>
						</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endif; ?>
		
	<?php 
	endforeach; ?>
	</table>
	<br /><br />
<?php 
else : ?>
<b>No un-billed Purchase Items at this time.</b>
<?php endif; ?>