<?php 
$primary_text = '';
if($address['primary']) {
	$primary_text = 'Primary';
}
$response = '<tr id="' . $address['id'] . '" class="address_search_table_row search_table_row">' .
		'	<td>' . $primary_text . '</td>' .
		'	<td>' . $address_type . '</td>' .
		'	<td>' .
		'		<div class="contact">';
		if(!empty($address['name'])) {
			$response = $response . '<span class="semi-bold">' . $address['name'] . '</span><br />';
		}
$response = $response . $this->Web->address($address, false, '<br />', false, false, false) .
		'		</div>' .
		'		<div id="address_data_bank_' . $address['id'] . '" class="data_bank">'.
		'			<input id="name" type="hidden" value="' . $address['name'] . '">' .
		'			<input id="address_type_id" type="hidden" value="' . $address['address_type_id'] . '">' .
		'			<input id="model" type="hidden" value="' . $address['model'] . '">' .
		'			<input id="foreign_key" type="hidden" value="' . $address['foreign_key'] . '">' .
		'			<input id="line1" type="hidden" value="' . $address['line1'] . '">' .
		'			<input id="line2" type="hidden" value="' . $address['line2'] . '">' .
		'			<input id="city" type="hidden" value="' . $address['city'] . '">' .
		'			<input id="st_prov" type="hidden" value="' . $address['st_prov'] . '">' .
		'			<input id="zip_post" type="hidden" value="' . $address['zip_post'] . '">' .
		'			<input id="country" type="hidden" value="' . $address['country'] . '">' .
		'			<input id="primary" type="hidden" value="' . $address['primary'] . '">' .
		'		</div>';
		'	</td>' .
		'</tr>';

echo $response;
?>