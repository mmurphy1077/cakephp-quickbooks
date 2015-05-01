<h2>Contact List</h2> (select contacts to include)
<div id="contact-container">
	<div class="clear grid">
		<div class="col-1of1">
			<?php #echo $this->Form->input('customer_id', array('label' => false, 'empty' => true)); ?>	
			<div id="autocomplete_container_customer_email" class="clear jscrollpane_mini">
				<?php 
				if(empty($contacts))  : ?>
					<table id="contact_email_search_table" class="nohover"></table>
				<?php 
				else :
					$count = count($contacts) / 3;
					$left = array();
					$middle = array();
					$right = $contacts;
					$i = 0;
					foreach($contacts as $key=>$data) {
						if($i < $count) {
							$left[$key] = $data;
							unset($right[$key]);
							unset($contacts[$key]);
						}
						$i = $i + 1;
					}
					$i = 0;
					foreach($contacts as $key=>$data) {
						if($i < $count) {
							$middle[$key] = $data;
							unset($right[$key]);
							unset($contacts[$key]);
						}
						$i = $i + 1;
					}
					$result['left'] = $left;
					$result['middle'] = $middle;
					$result['right'] = $right;
				endif; ?>
				<div class="col-1of3 clear">
					<?php if(!empty($result['left'])) : ?>
					<?php 	foreach($result['left'] as $key=>$data) : ?>
					<?php 		$isSelected = '';
								foreach($current_selected_contacts as $current_selected_contact) {
									if(($current_selected_contact['reply_to_model'] == 'Contact') &&  ($current_selected_contact['reply_to_id'] == $data['Contact']['id'])) {
										$isSelected = 'checked';
									}
								} ?>
					<?php 		echo $this->Form->input('contact_check', array('checked' => $isSelected, 'type'=>'checkbox', 'label' => $data['Contact']['name_forward'], 'id' => 'contact_check_' . $data['Contact']['id'], 'class' => 'contacts_email_check')); ?>	
					<?php 		echo $this->Form->hidden('contact_email', array('id' => 'contact_email_'.$data['Contact']['id'], 'class' => 'contact_email', 'value' => $data['Contact']['email'])); ?>
					<?php 	endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="col-1of3">
					<?php if(!empty($result['middle'])) : ?>
					<?php 	foreach($result['middle'] as $key=>$data) : ?>
					<?php 		$isSelected = '';
								foreach($current_selected_contacts as $current_selected_contact) {
									if(($current_selected_contact['reply_to_model'] == 'Contact') &&  ($current_selected_contact['reply_to_id'] == $data['Contact']['id'])) {
										$isSelected = 'checked';
									}
								} ?>
					<?php 		echo $this->Form->input('contact_check', array('checked' => $isSelected, 'type'=>'checkbox', 'label' => $data['Contact']['name_forward'], 'id' => 'contact_check_' . $data['Contact']['id'], 'class' => 'contacts_email_check')); ?>
					<?php 		echo $this->Form->hidden('contact_email', array('id' => 'contact_email_'.$data['Contact']['id'], 'class' => 'contact_email', 'value' => $data['Contact']['email'])); ?>
					<?php 	endforeach; ?>
					<?php endif; ?>
				</div>
				<div class="col-1of3">
					<?php if(!empty($result['right'])) : ?>
					<?php 	foreach($result['right'] as $key=>$data) : ?>
					<?php 		$isSelected = '';
								foreach($current_selected_contacts as $current_selected_contact) {
									if(($current_selected_contact['reply_to_model'] == 'Contact') &&  ($current_selected_contact['reply_to_id'] == $data['Contact']['id'])) {
										$isSelected = 'checked';
									}
								} ?>
					<?php 		echo $this->Form->input('contact_check', array('checked' => $isSelected, 'type'=>'checkbox', 'label' => $data['Contact']['name_forward'], 'id' => 'contact_check_' . $data['Contact']['id'], 'class' => 'contacts_email_check')); ?>
					<?php 		echo $this->Form->hidden('contact_email', array('id' => 'contact_email_'.$data['Contact']['id'], 'class' => 'contact_email', 'value' => $data['Contact']['email'])); ?>
					<?php 	endforeach; ?>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</div>