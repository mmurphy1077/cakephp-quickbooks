<?php
$this->extend('/Templates/wide');
if (!empty($this->request->data['Customer']['id'])) {
	$pageTitle = 'Customer Details &mdash; ' . $this->request->data['Customer']['name'] ;
} else {
	$pageTitle = 'Customer Details &mdash; Add';
}
$this->assign('pageTitle', $pageTitle);
#echo $this->element('js'.DS.'jquery', array('ui' => 'buttons'));

$permissions = $this->Permission->getPermissions($__permissions);
$permission_attr = 'readonly disabled';
if($permissions['enable_save'] && $permissions['read_only']==-1) {
	$permission_attr = null;
}
?>
<div class="clear">
	<?php echo $this->Form->create('Customer', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
		<fieldset>
			<div class="fieldset-wrapper available_action_container">
				<b>Available Actions:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;View and edit this customer's primary account information.
				<div class="title-buttons">
					<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
					<?php 
					if($permissions['enable_save']) {
						echo $this->Form->submit(__('Save', true), array('class' => 'title-buttons')); 
					} ?>
				</div>												  
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4><?php echo __('Account Information'); ?></h4>
				<?php /**
				<div class="buttonset"><?php echo $this->Form->radio('company_id', Set::combine($__companies, '/Company/id', '/Company/name'), array('legend' => __('Company'))); ?></div>
				<br />
				*/ ?>
				<?php
				echo $this->Form->input('name', array($permission_attr, 'class' => 'required', 'error' => array('length' => __('text_255', true))));
				echo $this->Form->input('account_rep_id', array($permission_attr, 'empty' => true, 'div' => 'input select medium'));
				echo $this->Form->input('customer_source_id', array($permission_attr, 'empty' => true, 'div' => 'input select medium'));
				echo $this->Form->input('customer_type_id', array($permission_attr, 'empty' => true, 'div' => 'input select medium'));
				echo $this->Form->input('payment_term_id', array($permission_attr, 'empty' => true, 'div' => 'input select medium'));
				echo $this->Form->input('website', array($permission_attr, 'before' => 'http:// '));
				?>
				<div class="buttonset"><?php echo $this->Form->radio('status', $__statuses, array($permission_attr, 'legend' => __('Status'))); ?></div>
			</div>
		</fieldset>
		<?php if (empty($this->request->data['Customer']['id'])): ?>
			<fieldset>
				<div class="fieldset-wrapper">
					<h4><?php echo __('Primary Address'); ?></h4>
					<?php echo $this->element('info', array('content' => array(
						'Enter the primary address of this business or residence and select the label to identify it. You will be able to add additional addresses once this new Customer has been saved.',
					))); ?>
					<?php echo $this->element('address_form', array('model' => 'Customer', 'foreign_key' => null, 'disable_primary_option' => true)); ?>
				</div>
			</fieldset>
		<?php endif; ?>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4><?php echo __('Phone Numbers'); ?></h4>
				<div class="grid">
					<div class="col-1of3">
						<div class="buttonset">
							<?php echo $this->Form->input('phone_1_number', array($permission_attr, 'error' => array('phone' => __('phone_us')), 'label' => __('Phone 1'))); ?>
							<fieldset>
								<legend>&nbsp;</legend>
								<?php foreach($__phoneLabels as $key=>$data) : 
								$value = null;
								if(array_key_exists('phone_1_label', $this->data['Customer'])) {
									$value = $this->data['Customer']['phone_1_label'];
								} ?>
								<input type="radio" <?php echo $permission_attr; ?> id="CustomerPhone1Label<?php echo $key; ?>" name="data[Customer][phone_1_label]" value="<?php echo $key; ?>" <?php if($key == $value) : ?>checked="checked"<?php endif; ?>><label for="CustomerPhone1Label<?php echo $key; ?>"><?php echo $data; ?></label>
								<?php endforeach; ?>
							</fieldset><br />
							<?php echo $this->Form->error('phone_1_label', __('select_one')); ?>
						</div>	
					</div>
					<div class="col-1of3">
						<div class="buttonset">
							<?php echo $this->Form->input('phone_2_number', array($permission_attr, 'error' => array('phone' => __('phone_us')), 'label' => __('Phone 2'))); ?>
							<fieldset>
								<legend>&nbsp;</legend>
								<?php foreach($__phoneLabels as $key=>$data) : 
								$value = null;
								if(array_key_exists('phone_2_label', $this->data['Customer'])) {
									$value = $this->data['Customer']['phone_2_label'];
								} ?>
								<input type="radio" <?php echo $permission_attr; ?> id="CustomerPhone2Label<?php echo $key; ?>" name="data[Customer][phone_2_label]" value="<?php echo $key; ?>" <?php if($key == $value) : ?>checked="checked"<?php endif; ?>><label for="CustomerPhone2Label<?php echo $key; ?>"><?php echo $data; ?></label>
								<?php endforeach; ?>
							</fieldset><br />
							<?php echo $this->Form->error('phone_2_label', __('select_one')); ?>
						</div>						
					</div>
					<div class="col-1of3">
						<div class="buttonset">
							<?php echo $this->Form->input('phone_3_number', array($permission_attr, 'error' => array('phone' => __('phone_us')), 'label' => __('Phone 3'))); ?>
							<fieldset>
								<legend>&nbsp;</legend>
								<?php foreach($__phoneLabels as $key=>$data) : 
								$value = null;
								if(array_key_exists('phone_3_label', $this->data['Customer'])) {
									$value = $this->data['Customer']['phone_3_label'];
								} ?>
								<input type="radio" <?php echo $permission_attr; ?> id="CustomerPhone3Label<?php echo $key; ?>" name="data[Customer][phone_3_label]" value="<?php echo $key; ?>" <?php if($key == $value) : ?>checked="checked"<?php endif; ?>><label for="CustomerPhone3Label<?php echo $key; ?>"><?php echo $data; ?></label>
								<?php endforeach; ?>
							</fieldset><br />
							<?php echo $this->Form->error('phone_3_label', __('select_one')); ?>
						</div>							
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4><?php echo __('Public Notes'); ?></h4>
				<div class="flush-left">
					<?php echo $this->element('info', array('content' => array(
						'These notes are available for all Users within the application to view.',
					))); ?>
				</div>
				<?php echo $this->Form->input('notes', array($permission_attr, 'label' => false)); ?>
			</div>
		</fieldset>
		<?php if(($__user['User']['group_id'] == GROUP_EXECUTIVES_ID) || ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID)) : ?>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4><?php echo __('Private Notes'); ?></h4>
				<div class="flush-left">
					<?php echo $this->element('info', array('content' => array(
						'These notes are only viewable by Administrators within the application.',
					))); ?>
				</div>
				<?php echo $this->Form->input('notes_internal', array($permission_attr, 'label' => false)); ?>
			</div>
		</fieldset>
		<?php endif; ?>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4><?php echo __('Invoice Instructions'); ?></h4>
				<?php echo $this->Form->input('notes_invoice', array($permission_attr, 'label' => false)); ?>
			</div>
		</fieldset>
	<?php #echo $this->Form->submit(__('Save', true)); ?>
	<?php echo $this->Form->hidden('id'); ?>
	<?php echo $this->Form->end(); ?>
</div>
<?php $this->start('buttons'); ?>
<?php $this->end(); ?>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('header_tabs', array('account' => $this->data)); ?>
	<?php #echo $this->element('tabs_customer', array('customer' => $this->data)); ?>
<?php $this->end(); ?>
