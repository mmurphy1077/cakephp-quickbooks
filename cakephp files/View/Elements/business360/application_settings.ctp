<?php 
echo $this->element('js'.DS.'tinymce');
?>
<h3 class="left"><?php echo __('Application Settings'); ?> (<?php echo $title; ?>)</h3>
<?php echo $this->Form->create('ApplicationSetting', array('class' => 'standard', 'novalidate' => true, 'type' => 'file', 'url' => '/'.$this->params->url)); ?>
<?php echo $this->Form->hidden('ApplicationSetting.id', array('value' => $data['ApplicationSetting']['id'])); ?>
<div class="row">
	<?php 
	switch ($type) : 
		case 'contract_language' : ?>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h4><?php echo __('Business 360'); ?></h4>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<?php echo $this->Form->input('data', array('type' => 'textarea', 'class' => 'tall', 'label' => false, 'value' => $data['ApplicationSetting'][$field])); ?>
				<?php echo $this->Form->hidden('field', array('value' => $field)); ?>
				<?php echo $this->Form->hidden('id', array('value' => $data['ApplicationSetting']['id'])); ?>
			</div>
	<?php 	break; 
		case 'default' : ?>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<br />
				<h4><?php echo __('Business 360'); ?></h4>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
				<?php echo $this->Form->input('company_name', array('value' => $data['ApplicationSetting']['company_name'], 'label' => 'Company Name')); ?>
				<?php echo $this->Form->input('company_url', array('value' => $data['ApplicationSetting']['company_url'], 'label' => 'URL (http://)')); ?>
				<br />
				<?php 
				$value = 'America/Los_Angeles'; 
				if(!empty($data['ApplicationSetting']['timezone'])) {
					$value = $data['ApplicationSetting']['timezone'];
				}
				echo $this->Form->input('timezone', array('value' => $value, 'label' => 'Timezone', 'options' => Set::combine(DateTimeZone::listIdentifiers(), '/', '/'))); ?>
				<br />
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="label"><?php echo __('Company Logo'); ?>&nbsp;&nbsp;<div class="light small inline">(300px x 130px)</div></div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<?php 
						if (array_key_exists('CompanyImage', $data) && (!empty($data['CompanyImage']['bytes']))) {
							$image = $data['CompanyImage'];
							echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/CompanyImage/' . $image['name'], array('id' => 'company-logo-image', 'class' => 'company-logo-image dim300x130')); 
						} else {
							echo $this->Form->input('CompanyImage.name', array('type' => 'file', 'label' => false, 'error' => false,)); 
						} ?>
					</div>
					<?php 
					if (array_key_exists('CompanyImage', $data) && (!empty($data['CompanyImage']['bytes']))) : ?>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<?php echo $this->Html->link(__('Delete'), array('controller' => 'business360s', 'action' => 'delete_company_logo', $image['id'], $image['foreign_key']), array('escape' => false, 'class' => 'clear image-btn'), __('delete_confirm')); ?> 
					</div>
					<?php endif ?>
				
				</div>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
				<div class="col-1of4">
					<?php echo $this->Form->error('CompanyImage.name', __('file_type', true)); ?>
					<?php echo $this->Form->error('CompanyImage.name', __('uploaded', true)); ?>
					<?php echo $this->Form->error('CompanyImage.name', __('max_size', true)); ?>
				</div>
			</div>
			
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<br />
				<h4><?php echo __('Financial Targets'); ?> (optional)</h4>
			</div>
			<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
				<?php echo $this->Form->input('ApplicationSetting.margin', array('label' => 'Target Margin', 'value' => $data['ApplicationSetting']['margin'], 'class' => 'num_only', 'div' => array('class' => 'input number short'), 'after' => '&nbsp;<b>%</b>')); ?>
				<!-- 
				<div class="buttonset_container">
					<label><?php #echo __('Apply Margin To Materials'); ?></label>
					<div class="buttonset"><?php #echo $this->Form->radio('ApplicationSetting.apply_margin_to_materials', $__yesNo, array('div' => array('class' => 'input'), 'value' => $data['ApplicationSetting']['apply_margin_to_materials'], 'legend' => false)); ?></div> 
				</div>
				 -->
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<br /><br />
				<h4><?php echo __('Contact Reminders'); ?> (optional)</h4>
				<span class="notes">Contact Reminders are used in Leads, Quotes, and Orders.  If a default is selected, any time a new record is created for one of these modules, a contact reminder (Callback) date will be automatically created.</span>
				<br /><br />
			</div>
			<div class="col-xs-9 col-sm-6 col-md-6 col-lg-6">
				<?php echo $this->Form->input('default_reminder_lead', array('value' => $data['ApplicationSetting']['default_reminder_lead'], 'label' => 'Leads', 'options' => $reminder_options, 'empty' => 'Select')); ?><br />
				<?php echo $this->Form->input('default_reminder_quote', array('value' => $data['ApplicationSetting']['default_reminder_quote'], 'label' => 'Quotes', 'options' => $reminder_options, 'empty' => 'Select')); ?><br />
				<?php echo $this->Form->input('default_reminder_order', array('value' => $data['ApplicationSetting']['default_reminder_order'], 'label' => 'Orders', 'options' => $reminder_options, 'empty' => 'Select')); ?>
			</div>
	</div>
	<?php 	break; ?>
	<?php endswitch;?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="title-buttons">
			<?php echo $this->Form->submit(__('Save', true), array('class' => 'red')); ?>
		</div>	
	</div>	
	<br /><br /><br /><br /><br /><br /><br /><br />			  
</div>
<?php echo $this->Form->end(); ?>