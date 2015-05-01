<?php 
$response = '<tr id="' .  $customer['Customer']['id'] .'" class="customer_search_table_row search_table_row">';
$response = $response . '	<td>';
$response = $response . '		<div class="customer">';
$response = $response . 			'<span class="semi-bold">' . $customer['Customer']['name'] . '</span>';
$response = $response . 			'<span class="right">' . $this->Web->phone($customer['Customer']) . '</span>';
$response = $response . 			$this->Form->hidden('', array('id' => 'customer_name_' . $customer['Customer']['id'] , 'value' => $customer['Customer']['name']));
$response = $response . '		</div>';
$response = $response . '		<div class="address">';
#$response = $response . 			$this->Web->address($customer['Address'], false, '<br />', false, false, false);
if(!empty($customer['Address']) && !empty($customer['Address']['line1'])) {
$response = $response . 			$this->Web->address($customer['Address'], false, ', ', false, false, false);
#$response = $response . 			$customer['Address']['line1'] . ', ' . $customer['Address']['city'] . ' ' . $customer['Address']['st_prov'] . ', ' . $customer['Address']['zip_post'];
$response = $response . '		</div>';
}
if(!empty($customer['Contact'])) {
$response = $response . '		<div class="contact">';
$response = $response . 			'Contact: ' . $this->Web->humanName($customer['Contact'], 'full');
$response = $response . 			'<span class="right">' . $this->Web->phone($customer['Contact']) . '</span>';
$response = $response . '		</div>';
}
$response = $response . '	</td>';
$response = $response . '</tr>';

echo $response;
?>