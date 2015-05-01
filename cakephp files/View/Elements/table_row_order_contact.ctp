<?php 
$response = '<tr id="' . $data['id'] . '" class="contact_search_table_row search_table_row">' .
			'	<td>' .
			'		<div class="contact">' .
			'			<span class="semi-bold">' . $this->Web->humanName($data, 'full') . '</span><br />';
if(!empty($data['title'])) {
	$response = $response . $data['title'] . '<br />';
}
$response = $response . $this->Web->phone($data) . '<br />';
if(!empty($data['title'])) {
	$response = $response . $data['email'];
}
$response = $response . '		</div>' .
			'		<div id="contact_data_bank_' . $data['id'] . '" class="data_bank">'.
			'			<input id="name" type="hidden" value="' . $this->Web->humanName($data, 'full') . '">' .
			'			<input id="title" type="hidden" value="' . $data['title'] . '">' .
			'			<input id="phone" type="hidden" value="' . $this->Web->phone($data) . '">' .
			'			<input id="email" type="hidden" value="' . $data['email'] . '">' .
			'		</div>';
			'	</td>' .
			'</tr>';
echo $response;
?>