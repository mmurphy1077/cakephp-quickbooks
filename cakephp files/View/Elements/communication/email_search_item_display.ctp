<?php 
switch ($type) {
	case 'customer' :
		$response = '<tr id="" class="contact_search_table_row search_table_row">' .
					'	<td>' .
					'		<div class="company-name">' .
					'			<span class="semi-bold">' . $data['Customer']['name'] . '</span><br />' .
					'		</div>' .
					'		<div id="" class="contacts">';
		
					foreach($data['Customer']['Contact'] as $contact) {
		$response = $response . 
					'	<div class="contact-email-container" id="contact-email-container_' . $contact['Contact']['id'] . '">' . 
							$this->Form->input('contact_check', array('type'=>'checkbox', 'label' => false, 'div' => false, 'id' => 'contact_check_' . $contact['Contact']['id'], 'class' => 'contacts_email_check')) .
							$contact[0]['contact_name'] . ' (' . $contact['Contact']['email'] . ')' .
							$this->Form->hidden('contact_email_' . $contact['Contact']['id'], array('id' => 'contact_email_' . $contact['Contact']['id'], 'value' => $contact['Contact']['email'], 'class' => 'contact_email')) . 
					'	</div>';
					}
					
		$response = $response . 
					'		</div>' .
					'	</td>' .
					'</tr>';			
		break;
		
	case 'lead' :
		$response = '<tr id="" class="contact_search_table_row search_table_row">' .
				'	<td>' .
				'		<div class="company-name">' .
				'			<span class="semi-bold">' . $data['Contact']['company_name'] . '</span><br />' .
				'		</div>' .
				'		<div id="" class="contacts">' .
				'			<div class="contact-email-container" id="contact-email-container_' . $data['Contact']['id'] . '">' .
							$this->Form->input('contact_check', array('type'=>'checkbox', 'label' => false, 'div' => false, 'id' => 'contact_check_' . $contact['Contact']['id'], 'class' => 'contacts_email_check')) .
							$data[0]['contact_name'] . ' (' . $data['Contact']['email'] . ')' .
							$this->Form->hidden('contact_email_' . $contact['Contact']['id'], array('id' => 'contact_email_' . $contact['Contact']['id'], 'value' => $contact['Contact']['email'], 'class' => 'contact_email')) .
				'			</div>' .
				'		</div>' .
				'	</td>' .
				'</tr>';
		break;
		
	case 'email' :
		$response = '<tr id="" class="contact_search_table_row search_table_row">' .
				'	<td>' .
				'		<div class="company-name">' .
				'			<span class="semi-bold">' . '&nbsp;</span><br />' .
				'		</div>' .
				'		<div id="" class="contacts">' .
				'			<div class="contact-email-container" id="contact-email-container_' . $data['Contact']['id'] . '">' .
							$this->Form->input('contact_check', array('type'=>'checkbox', 'label' => false, 'div' => false, 'id' => 'contact_check_' . $contact['Contact']['id'], 'class' => 'contacts_email_check')) .
							$data[0]['contact_name'] . ' (' . $data['Contact']['email'] . ')' .
							$this->Form->hidden('contact_email_' . $contact['Contact']['id'], array('id' => 'contact_email_' . $contact['Contact']['id'], 'value' => $contact['Contact']['email'], 'class' => 'contact_email')) .
				'			</div>' .
				'		</div>' .
				'	</td>' .
				'</tr>';
		break;
}
echo $response;
?>