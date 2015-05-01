<?php $this->Html->script('creationsite/job.contacts', false); 
$data['id'] = null;
$data['contact_id'] = null;
$data['primary'] = 1;
$data['add_to_customer'] = 1;
$data['contact_name'] = null;
$data['contact_phone'] = null;
$data['contact_email'] = null;
if(!empty($current_contacts)) {
	$data['id'] = $current_contacts[0]['id'];
	$data['contact_id'] = $current_contacts[0]['contact_id'];
	$data['primary'] = $current_contacts[0]['primary'];
	$data['add_to_customer'] = 0;
	$data['contact_name'] = $current_contacts[0]['contact_name'];
	$data['contact_phone'] = $current_contacts[0]['contact_phone'];
	$data['contact_email'] = $current_contacts[0]['contact_email'];
}
?>
<div class="clear row">
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<?php echo $this->Form->hidden('Contact.id', array('value' => $data['id'])); ?>
		<?php echo $this->Form->hidden('Contact.contact_id', array('value' => $data['contact_id'])); ?>
		<?php echo $this->Form->hidden('Contact.primary', array('value' => $data['primary'])); ?>
		<?php echo $this->Form->hidden('Contact.add_to_customer', array('value' => $data['add_to_customer'])); ?>
		<?php echo $this->Form->hidden('Contact.model', array('value' => $model)); ?>
		<?php echo $this->Form->hidden('Contact.foreign_key', array('value' => $foreign_key)); ?>
		<?php echo $this->Form->hidden('Contact.contact_type_id'); ?>
		<?php echo $this->Form->hidden('Contact.contact_title'); ?>
		<?php echo $this->Form->input('Contact.contact_name', array('value' => $data['contact_name'], 'class' => 'required contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
		<?php echo $this->Form->input('Contact.contact_phone', array('value' => $data['contact_phone'], 'class' => 'contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
		<?php echo $this->Form->input('Contact.contact_email', array('value' => $data['contact_email'], 'class' => 'contact_input', 'div' => array('class' => 'input larger_label_space'))); ?>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
		<div class="label"><?php echo __('Associated Contacts'); ?></div>
		<div id="autocomplete_container_contact" class="clear autocomplete_container white jscrollpane_mini">
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
</div>