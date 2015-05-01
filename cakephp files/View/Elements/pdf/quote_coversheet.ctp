<?php
$this->layout = 'pdf_proof';

/**
 * Account Rep info
 */
$phone = '';
if(!empty($q['AccountRep']['UserProfile']['phone_1_number']) || !empty($q['AccountRep']['UserProfile']['phone_2_number']) || !empty($q['AccountRep']['UserProfile']['phone_3_number'])) {
	if(!empty($q['AccountRep']['UserProfile']['phone_1_number'])) {
		$phone = $q['AccountRep']['UserProfile']['phone_1_number'];
	} elseif (!empty($q['AccountRep']['UserProfile']['phone_2_number'])) {
		$phone = $q['AccountRep']['UserProfile']['phone_2_number'];
	} else {
		$phone = $q['AccountRep']['UserProfile']['phone_3_number'];
	}
}
$primaryAddress = $this->Session->read('Application.address.primary'); ?>
<div class="page coversheet">
<div id="page_header" class="page_header quote">
	<div class="logo_container">
		<?php echo $this->Html->image('logo-ohio-awning-quote.png', array('id' => 'logo')); ?>
		<div id="header_string">
		<?php echo $primaryAddress['Address']['line1'] ?>&nbsp;
		<?php echo $primaryAddress['Address']['line2'] ?>&nbsp;&nbsp;|&nbsp;&nbsp;Ph: 
		<?php echo $primaryAddress['Location']['phone'] ?>&nbsp;&nbsp;|&nbsp;&nbsp;
		<?php echo Configure::read('Public.public_url') ?>	
		</div>
	</div>
</div>
<?php echo $this->Time->format('F j, Y', $q['Quote']['created']); ?>
<br />
<br />
<?php echo $q['Quote']['contact_name']; ?><br />
<?php if (!empty($q['AddressExisting'])): ?>
	<?php echo $this->Web->address($q['AddressExisting'], false, '<br />'); ?>
<?php elseif (!empty($q['Address'])): ?>
	<?php echo $this->Web->address($q['Address'], false, '<br />'); ?>
<?php endif; ?>
<br />	
<br />
<br />
RE: <?php echo $q['Quote']['name']; ?><br />
<?php echo __(Configure::read('Nomenclature.ProjectNumber')); ?>
<?php echo $q['Quote']['number']; ?>
<br />	
<br />
<br />

Dear <?php echo $q['Quote']['contact_name']; ?>:<br /><br />
<p>Ohio Awning and Manufacturing Company is pleased to have the opportunity to bid the project for <LOCATION>.  The scope of the work for the awnings, as we understand it, follows: </p>
			
<p>
Ohio Awning will provide:
			
<?php 
$total_awning_count = 0;
foreach ($q['QuoteLineItem'] as $i => $qLineItem):  
	$total_awning_count = $total_awning_count + $qLineItem['qty'];
	$width = '';
	$height = '';
	$projection = '';	
	foreach ($qLineItem['QuoteJob']['QuoteJobRequirement'] as $qJobRequirement) { 
		if ($qJobRequirement['CalculationSystemRequirement']['CalculationRequirement']['calculation_requirement_type_id'] != CALCULATION_REQUIREMENT_TYPE_HOURLY_ID) {
			switch ($qJobRequirement['CalculationSystemRequirement']['CalculationRequirement']['name']) {
					case 'Width' :
						$width = $this->Web->convertTo($qJobRequirement['CalculationSystemRequirement']['CalculationRequirement']['calculation_requirement_type_id'], $qJobRequirement['uom']);
						break;
					case 'Height' :
						$height = $this->Web->convertTo($qJobRequirement['CalculationSystemRequirement']['CalculationRequirement']['calculation_requirement_type_id'], $qJobRequirement['uom']);
						break;
					case 'Projection' :
						$projection = $this->Web->convertTo($qJobRequirement['CalculationSystemRequirement']['CalculationRequirement']['calculation_requirement_type_id'], $qJobRequirement['uom']);
						break;
			}
		}
	}
				
	/** 
	 * Materials
	 */
	$materials = array(); 
	foreach ($qLineItem['QuoteJob']['QuoteJobLineItem'] as $qJobLineItem) { #debug($qJobLineItem['CalculationLineItem']);
		$materials[$qJobLineItem['CalculationLineItem']['name']]['name'] = null;
		if(array_key_exists('name', $qJobLineItem['CalculationTaxonomy'])) {
			$materials[$qJobLineItem['CalculationLineItem']['name']]['name'] = $qJobLineItem['CalculationTaxonomy']['name'];
		}
		$materials[$qJobLineItem['CalculationLineItem']['name']]['qty'] = null;
		if(array_key_exists('qty', $qJobLineItem['CalculationTaxonomy'])) {
			$materials[$qJobLineItem['CalculationLineItem']['name']]['qty'] = $qJobLineItem['qty'];
		}
		$materials[$qJobLineItem['CalculationLineItem']['name']]['price_subtotal'] = 0;
		if(array_key_exists('price_material_unit', $qJobLineItem['CalculationTaxonomy']) && array_key_exists('price_labor_unit', $qJobLineItem['CalculationTaxonomy'])) {
			$materials[$qJobLineItem['CalculationLineItem']['name']]['price_subtotal'] = $qJobLineItem['price_material_unit'] + $qJobLineItem['price_labor_unit'];
		}	
	}
	$materials_string_frame = '.';
	$materials_string_fabric = '';
	if(array_key_exists('Fabric Type', $materials) && !empty($materials['Fabric Type']['name'])) {
	 	$materials_string_fabric = $materials['Fabric Type']['name'] . ' fabric';
	 	unset($materials['Fabric Type']);
	}
	if(!empty($materials_string_fabric)) {
		$materials_string_frame = ' and ';
	}
	if(array_key_exists('Frame', $materials) && !empty($materials['Frame']['name'])) {
	 	$materials_string_frame = $materials['Frame']['name'] . ' frames.';
	 	unset($materials['Frame']);
	}
	unset($materials['Cover']);
	?>
	<br />
	<div class="cover_letter_item_qty">(<?php echo $qLineItem['qty'];?>)</div>
	<div class="cover_letter_item_detail">
	<?php echo $qLineItem['name']; ?> Awning<?php if($qLineItem['qty'] > 1) { echo 's'; } ?> at 
	<?php echo $width; ?> wide x <?php echo $height; ?> tall with a <?php echo $projection; ?> projection <br />
	The awning will be produced and installed to design Spec. Section ?? ?? ??<br />
	<?php echo $materials_string_fabric; ?><?php echo $materials_string_frame; ?>  
	</div>
<?php endforeach; ?>
	</p>
	<p>
	Total of (<?php echo $total_awning_count; ?>) awnings, fabricated and installed: <span class="right"><?php echo $this->Number->currency($q['Quote']['price_total']); ?></span>
	</p>
	<p>This pricing does not include sales tax, costs for engineering, registration or permit fees, if required.  This cost will be honored for 30 days.</p>
	<p>We thank you for allowing us to quote.  Should you have any questions or wish to place the order, please contact us at <?php echo $primaryAddress['Location']['phone'] ?> or <?php echo $phone; ?></p>
	<div class="signature_block">
		Sincerely, <br/><br/><br/><br/>
		<?php echo $q['AccountRep']['name_first'].' '.$q['AccountRep']['name_last']; ?><br/>
		<b><?php echo __($this->Session->read('Application.settings.ApplicationSetting.company_name')); ?></b>
	</div>
</div>