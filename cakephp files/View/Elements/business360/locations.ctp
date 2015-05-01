<?php 
echo $this->Html->script('creationsite/address', array('inline' => false));
#echo $this->element('js'.DS.'tinymce');

$add_mode_class = '';
if(!empty($display_mode) && $display_mode == 'collapsed') {
	$add_mode_class = 'hide';
}
$first_location = false;
if(empty($locations)) {
	$add_mode_class = '';
	$first_location = true;
} ?>
<h3 class="left"><?php echo __('Application Settings'); ?> (<?php echo $title; ?>)</h3>
<div id="" class="clear">
	<?php echo $this->Html->link('add location', array('#'), array('id' => 'add_location', 'class' => 'toggle_display_button right')); ?>
	<div id="add_address_toggle_display" class="clear  <?php echo $add_mode_class; ?>">
	<?php echo $this->Form->create('Location', array('class' => 'standard', 'novalidate' => true, 'type' => 'file', 'url' => '/'.$this->params->url)); ?>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4 id="location-form-label"><?php echo __('Location Information'); ?></h4>
				<?php echo $this->Form->hidden('Location.id'); ?>
				<?php echo $this->Form->input('Location.name'); ?>
				<?php echo $this->element('address_form', array('model' => 'location', 'expanded' => false, 'dispaly_name_tag' => false, 'dispaly_address_type' => false, 'disable_primary_option' => true)); ?>
				<br />
				<div class="input standard">
					<label class="standard_checkbox" for="LocationPrimary">Use as Primary</label>
					<?php echo $this->Form->input('Location.primary', array('type'=>'checkbox', 'label' => false, 'div' => false)); ?>
				</div>
				<div id="location-primary-container">
					<?php echo $this->Form->input('Location.phone'); ?>
					<?php echo $this->Form->input('Location.email'); ?>
				</div>
				<div class="input standard">
					<label class="standard_checkbox" for="LocationBilling">Use as Billing</label>
					<?php echo $this->Form->input('Location.billing', array('type'=>'checkbox', 'label' => false, 'div' => false)); ?>
				</div>
				<div id="location-billing-container">
					<?php echo $this->Form->input('Location.phone_billing', array('label' => 'Billing Phone')); ?>
					<?php echo $this->Form->input('Location.email_billing', array('label' => 'Billing Email')); ?>
				</div>
				<div class="input standard">
					<label class="standard_checkbox" for="LocationPrimary">&nbsp;</label>
					<span class="light small">* If Billing Phone and Email fields are left blank, the Account Reps phone and email will appear on Customer's Invoices.</span>
				</div>
				<br/>
				<?php echo $this->Form->submit(__('Save'), array('class' => 'red right', 'escape' => false)); ?>		
			</div>
		</fieldset>
	<?php echo $this->Form->end(); ?>
	</div>
	
	<table class="standard hover clear">
		<tr>
			<th>&nbsp;</th>
			<th>Name</th>
			<th>Address</th>	
			<th>Primary</th>
			<th>Billing</th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach($locations as $data ) : ?>
		<tr id="row-<?php echo $data['Address']['id']; ?>">
			<td>&nbsp;</td>
			<td><?php echo $data['Location']['name']; ?></td>
			<td><?php echo $this->Web->address($data['Address'], false, '<br />', false); ?></td>
			<td><?php 
				if(!empty($data['Location']['primary'])) {
					echo '&#10003;';
				} ?>
			</td>
			<td><?php 
				if(!empty($data['Location']['billing'])) {
					echo '&#10003;';
				} ?>
			</td>
			<td class="actions">
				<div id="<?php echo $data['Address']['id']; ?>_address_data_bank" class="address_data_bank hide">
					<?php echo $this->Form->hidden('location_id', array('value' => $data['Location']['id'])); ?>
					<?php echo $this->Form->hidden('location_name', array('value' => $data['Location']['name'])); ?>
					<?php echo $this->Form->hidden('location_primary', array('value' => $data['Location']['primary'])); ?>
					<?php echo $this->Form->hidden('location_email', array('value' => $data['Location']['email'])); ?>
					<?php echo $this->Form->hidden('location_phone', array('value' => $data['Location']['phone'])); ?>
					<?php echo $this->Form->hidden('location_billing', array('value' => $data['Location']['billing'])); ?>
					<?php echo $this->Form->hidden('location_email_billing', array('value' => $data['Location']['email_billing'])); ?>
					<?php echo $this->Form->hidden('location_phone_billing', array('value' => $data['Location']['phone_billing'])); ?>
					<?php echo $this->Form->hidden('id', array('value' => $data['Address']['id'])); ?>
					<?php echo $this->Form->hidden('name', array('value' => $data['Address']['name'])); ?>		
					<?php echo $this->Form->hidden('address_type_id', array('value' => $data['Address']['address_type_id'])); ?>
					<?php echo $this->Form->hidden('model', array('value' => $data['Address']['model'])); ?>
					<?php echo $this->Form->hidden('foreign_key', array('value' => $data['Address']['foreign_key'])); ?>
					<?php echo $this->Form->hidden('line1', array('value' => $data['Address']['line1'])); ?>
					<?php echo $this->Form->hidden('line2', array('value' => $data['Address']['line2'])); ?>
					<?php echo $this->Form->hidden('city', array('value' => $data['Address']['city'])); ?>
					<?php echo $this->Form->hidden('st_prov', array('value' => $data['Address']['st_prov'])); ?>
					<?php echo $this->Form->hidden('zip_post', array('value' => $data['Address']['zip_post'])); ?>
					<?php echo $this->Form->hidden('country', array('value' => $data['Address']['country'])); ?>
					<?php echo $this->Form->hidden('notes', array('value' => $data['Address']['notes'])); ?>
				</div>
				<?php
				echo $this->Html->link(__('Delete'), array('action' => 'delete_location', $data['Location']['id']), array('id' => $data['Location']['id'], 'class' => ''), __('delete_confirm'));
				echo $this->Html->link(__('Edit'), array('#'), array('id' => $data['Address']['id'], 'class' => 'edit_location row-click')); 
				?>
			</td>
		</tr>
		<?php endforeach; ?>
	</table>
</div>
<div class="title-buttons">
	<?php #echo $this->Form->submit(__('Save', true), array('class' => 'red')); ?>
</div>					  
<?php echo $this->Form->end(); ?>