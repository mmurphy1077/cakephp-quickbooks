<?php 
$this->Html->script('creationsite/job.contacts', false);
#echo $this->element('js'.DS.'jquery', array('ui' => 'buttons'));

// If no contacts are associated... leave open foro entry, else, close.
$add_mode_class = '';
if(!empty($current_contacts)) {
	$add_mode_class = 'hide';
}
?>
<div class="clear">
	<?php if($permissions['can_update'] == 1) : ?>
	<?php 		echo $this->Html->link('add a contact', array('#'), array('id' => 'add_status', 'class' => 'toggle_display_button right')); ?>
	<?php endif; ?>
		<div id="add_status_toggle_display" class="contact-edit-form clear <?php echo $add_mode_class; ?>">
			<?php 
			$target = $model.'Contact';
			#echo $this->Form->create($target, array('class' => 'standard', 'novalidate' => true, 'url' => array('action' => 'edit'), 'id' => $target.'Form')); ?>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<?php echo $this->Form->hidden('Contact.id'); ?>
					<?php echo $this->Form->hidden('Contact.contact_id'); ?>
					<?php echo $this->Form->hidden('Contact.model', array('value' => $model)); ?>
					<?php echo $this->Form->hidden('Contact.foreign_key', array('value' => $foreign_key)); ?>
					<?php echo $this->Form->input('Contact.contact_name', array('class' => 'required contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
					<?php echo $this->Form->input('Contact.contact_title', array('class' => 'contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
					<?php echo $this->Form->input('Contact.contact_phone', array('class' => 'contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
					<?php echo $this->Form->input('Contact.contact_email', array('class' => 'contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
					<?php echo $this->Form->input('Contact.contact_type_id', array('options' => $contactTypes, 'empty' => 'Select', 'class' => 'contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
					<div class="buttonset white-bckgrd larger_label">
						<?php 
						$primary = 0;
						echo $this->Form->radio('Contact.primary', $__yesNo, array('value' => $primary, 'legend' => __('Primary'))); ?><br />
					</div>			
					<div class="input checkbox input larger_label_space">
						<label id="LabelAddToCustomer"  class="standard_checkbox" for="OrderAddToCustomer">Add to Customer Profile</label>
						<?php echo $this->Form->input('Contact.add_to_customer', array('type'=>'checkbox', 'label' => false, 'div' => false)); ?>
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
					<div class="label"><?php echo __('Associated Contacts'); ?></div>
					<div id="autocomplete_container_contact" class="clear autocomplete_container grey jscrollpane_mini">
						<table id="contact_search_table" class="search_table"><?php 
							if(empty($associtated_contacts)) {
								echo '<tr><td>No associated contacts.</td></tr>';
							} else {
								foreach($associtated_contacts as $data) {
									echo $this->element('table_row_order_contact', array('data' => $data['Contact']));
								}
							} ?>
						</table>
					</div>
				</div>
				<div class="col-1of2 clear">
					<?php echo $this->element('ajax-loader', array('id' => 'ajax-loader-contact')); ?>
					<?php echo $this->element('ajax-message', array('id' => 'ajax-message-error-contact', 'type' => 'fail'));?>
					&nbsp;
				</div>
				<?php if(!empty($customer_id)) : ?>
				<div class="col-1of2"><?php echo $this->Form->submit(__('Save'), array('id' => 'save_contact_data', 'class' => 'right', 'escape' => false)); ?></div>
				<?php endif; ?>
			</div>	
			<br />	
			<?php #echo $this->Form->end(); ?>
		</div>
		<table id="<?php echo $model; ?>-contact" class="contact-table clear standard nohover">
			<tr>
				<th>&nbsp;</th>
				<th>Name</th>
				<th>Title</th>
				<th>Phone</th>
				<th>Email</th>
				<th>Type</th>
				<th>Primary</th>
				<th>&nbsp;</th>
			</tr>
		<?php if(!empty($current_contacts)) : ?>
			<?php foreach($current_contacts as $contact) : ?>
			<tr id="contact-row-<?php echo $contact['id']; ?>" class="contact-row">
				<td>&nbsp;</td>
				<td><?php echo $contact['contact_name']; ?></td>
				<td><?php echo $contact['contact_title']; ?></td>
				<td><?php echo $contact['contact_phone']; ?></td>
				<td><?php echo $contact['contact_email']; ?></td>
				<td>
				<?php if(!empty($contact['contact_type_id'])) {
						echo $contactTypes[$contact['contact_type_id']]; 
				}?>&nbsp;
				</td>
				<td><?php echo $__yesNo[$contact['primary']]; ?></td>
				<td class="actions">
					<div id="<?php echo $contact['id']; ?>_contact_data_bank" class="contact_data_bank hide">
						<?php echo $this->Form->hidden('id', array('id' => 'id', 'value' => $contact['id'])); ?>
						<?php echo $this->Form->hidden('primary', array('id' => 'primary', 'value' => $contact['primary'])); ?>
						<?php echo $this->Form->hidden('contact_id_db', array('id' => 'contact_id', 'value' => $contact['contact_id'])); ?>		
						<?php echo $this->Form->hidden('foreign_key', array('id' => 'foreign_key', 'value' => $contact['foreign_key'])); ?>
						<?php echo $this->Form->hidden('contact_name', array('id' => 'contact_name', 'value' => $contact['contact_name'])); ?>
						<?php echo $this->Form->hidden('contact_title', array('id' => 'contact_title', 'value' => $contact['contact_title'])); ?>
						<?php echo $this->Form->hidden('contact_phone', array('id' => 'contact_phone', 'value' => $contact['contact_phone'])); ?>
						<?php echo $this->Form->hidden('contact_email', array('id' => 'contact_email', 'value' => $contact['contact_email'])); ?>
						<?php echo $this->Form->hidden('contact_type_id', array('id' => 'contact_type_id', 'value' => $contact['contact_type_id'])); ?>
					</div>
					<?php
					if($permissions['enable_delete'] || ($contact['requested_by_id'] == $__user['User']['id'])) {
						echo $this->Html->link(__('Delete'), array('controller' => strtolower($model) . '_contacts', 'action' => 'delete', $contact['id']), array(), __('delete_confirm')); 
					} else {
						echo $this->Html->link(__('Delete'), '#', array('class' => 'inactive'));	
					}
					if($permissions['can_update'] == 1) {
						echo $this->Html->link(__('Edit'), array('#'), array('id' => $contact['id'], 'class' => 'edit_contact row-click')); 
					} 
					echo $this->element('ajax-message', array('id' => 'ajax-message-success-'.$contact['id'], 'type' => 'success'));
					?>
				</td>
			</tr>
		<?php 	endforeach; ?>
		<?php endif;?>
		</table>
</div>