<?php
$this->extend('/Templates/wide');
if(empty($mode)) {
	$mode = 'edit';
}
if ($mode == 'edit') {
	$pageTitle = 'User: ' . $this->data['User']['name_first'] . ' ' . $this->data['User']['name_last'];
	$password_label = 'New Password';
} else {
	$pageTitle = 'Add User';
	$password_label = 'Password';
}
$this->assign('pageTitle', $pageTitle);
echo $this->element('js'.DS.'jquery', array('ui' => 'buttons'));
echo $this->Html->script('creationsite/group', false);

/*
 * PERMISSIONS
 */
$permissions = $this->Permission->getPermissions($__permissions);
$permission_attr = 'readonly disabled';
if($permissions['read_only'] == -1) {
	$permission_attr = null;
} 
if(empty($owner)) {
	$owner = false;
}
/* END PERMISSIONS  */
?>
<?php $this->start('buttons'); ?>
	<?php #echo $this->element('nav_context'); ?>
<?php $this->end(); ?>
<div class="">
	<?php echo $this->Form->create('User', array('class' => 'standard', 'novalidate' => true, 'type' => 'file', 'url' => '/'.$this->params->url)); ?>
		<?php #if($owner) : ?>
		<?php #	echo $this->element('user/edit_profile', array('password_label' => $password_label));?>
		<?php #else : ?>
			<fieldset>
				<div class="row fieldset-wrapper available_action_container">
					<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
						<b>Available Actions:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manage User account information.
					</div>	
					<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
						<div class="title-buttons">
							<div class="visible-xs-block"><br /></div>
							<?php echo $this->Form->submit(__('Save', true), array('class' => 'red')); ?>
						</div>
					</div>					  
				</div>
			</fieldset>
			<fieldset>
				<div class="fieldset-wrapper">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h4><?php echo __('Account Information'); ?></h4>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<?php echo $this->Form->input('name_first', array('class' => 'required', 'error' => array('length' => __('text_45', true)))); ?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<?php  echo $this->Form->input('name_last', array('class' => 'required', 'error' => array('length' => __('text_45', true)))); ?>
						</div>
						<br /><br />
					<?php if ($permissions['admin'] == 1): ?>
						<?php if ($this->action == 'add'): ?>
							<?php 
							/*
							echo $this->element('info', array('content' => array(
								'If the Status field is set to "Active", an email notification will be sent to the user immediately after saving.',
							))); 
							*/?>
						<?php endif; ?>
						<div class="clear col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<?php echo $this->Form->input('status', array('options' => $__statuses, 'label' => __('Status', true))); ?>
							<p><?php echo $this->Form->error('status', __('Please select a status for this user.', true)); ?></p>	
						</div>
						
						<div class="clear col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<?php echo $this->Form->input('group_id', array('options' => $__groups, 'label' => __('Group', true))); ?>
							<p><?php echo $this->Form->error('group_id', __('Please select a group for this user.', true)); ?></p>
						</div>
						
						<?php /* ?>
						<div id="group-buttons-container" class="buttonset">
							<?php # echo $this->Form->radio('group_id', $__groups, array('legend' => __('Group', true))); ?>
							<fieldset>
								<legend><?php echo __('Group', true); ?></legend>
								<?php foreach($__groups as $key=>$data) : 
								$value = null;
								if(array_key_exists('group_id', $this->data['User'])) {
									$value = $this->data['User']['group_id'];
								} ?>
								<input type="radio" id="UserGroupId<?php echo $key; ?>" name="data[User][group_id]" value="<?php echo $key; ?>" <?php if($key == $value) : ?>checked="checked"<?php endif; ?>><label for="UserGroupId<?php echo $key; ?>"><?php echo $data; ?></label>
								<?php endforeach; ?>
							</fieldset>
						</div>
						<?php */ ?>
						<div class="clear col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<div class="business360_multi_checkboxes input select">
								<label class="left"><?php echo __('Job Types'); ?></label>
								<?php if(!empty($jobTypesGroupArray)) : ?>
								<?php 	foreach($jobTypesGroupArray as $key=>$jobTypesGroup) : ?>
								<?php 		$job_type_status = 'hide'; 
											if($key == $this->data['User']['group_id']) {
												$job_type_status = '';
											}			
								?>
								<div id="checkbox_container_<?php echo $key; ?>" class="checkbox_container <?php echo $job_type_status;?>">
									<?php 
									$selected = null;
									if(!empty($this->data['JobType'])) {
										$selected_types = set::extract('/id', $this->data['JobType']);
										$selected_types = array_flip($selected_types);
									} else {
										$selected_types = $jobTypesGroup;
									}
									foreach($jobTypesGroup as $key2=>$jobType) { 
										$selected = false;
										if(array_key_exists($key2, $selected_types)) {
											$selected = true;
										}
										echo $this->Form->input('', array('type'=>'checkbox', 'label' => $jobType, 'id' => 'JobTypesUserJobTypeId'.$key2, 'name' => 'data[Group][' . $key . '][JobTypesUser][job_type_id][' . $key2 . ']', 'checked' => $selected, 'value' => 1, 'div' => array('class' => 'checkbox'))); 
									} ?> 
								</div>
								<?php 	endforeach;?>
								<?php endif; ?>
							</div>
							<br /><br />
						</div>
						<div class="clear col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<?php 
							if(!isset($rates) || empty($rates)) {
								$rates = null;
							}
							echo $this->Form->input('rate_id', array('options' => $rates, 'label' => 'Billable Rate ($/hr)')); ?>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
							<?php echo $this->Form->input('expense_rate', array('class' => 'num_only', 'label' => 'Expense Rate ($/hr)')); ?>
						</div>
						<br />
					<?php endif; ?>		
					
					
					<div class="clear col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<br />		
						<?php echo $this->Form->input('email', array('class' => 'required', 'error' => array('unique' => __('unique', true)))); ?>
					</div>
					<?php #echo $this->Form->input('username', array('class' => 'required', 'error' => array('length' => __('text_45', true)))); ?>
					<div class="flush-left clear col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<?php 
						echo $this->element('info', array('class' => 'bootstrap', 'content' => array(
							'Leave both password fields blank to have a password auto-generated. Otherwise, enter the same password twice to confirm your entry.',
							'Leave both password fields blank to keep the existing password.  Otherwise, enter the same password twice to confirm the new one.',
							'<b>Passwords must meet the following conditions:</b>',
							'Be at least six characters in length',
							'Contain characters from three of the following four categories: A-Z, a-z, 0-9, non-alphanumeric/punctuation characters',
							'Must not contain the user\'s email account or parts of the user\'s full name that exceed two consecutive characters',
						))); ?>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<?php echo $this->Form->input('password1', array('type' => 'password', 'value' => null, 'label' => $password_label, 'error' => array('password1' => __('password1', true)))); ?>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
						<?php echo $this->Form->input('password2', array('type' => 'password', 'value' => null, 'label' => 'Confirm', 'error' => array('password2' => __('password2', true)))); ?>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<div class="fieldset-wrapper">
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<h4><?php echo __('Profile Information'); ?></h4>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div class="">
								<?php echo $this->Form->input('UserProfile.phone_1_number', array('error' => array('phone' => __('phone_us')), 'label' => __('Phone 1'), 'div' => array('class' => 'input text larger_label_space'))); ?>
								<?php echo $this->Form->input('UserProfile.phone_1_label', array('options' => $__phoneLabels, 'label' => '&nbsp;', 'div' => array('class' => 'input select larger_label_space'))); ?><br />
								<?php #echo $this->Form->error('UserProfile.phone_1_label', __('select_one')); ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div class="">
								<?php echo $this->Form->input('UserProfile.phone_2_number', array('error' => array('phone' => __('phone_us')), 'label' => __('Phone 2'), 'div' => array('class' => 'input text larger_label_space'))); ?>
								<?php echo $this->Form->input('UserProfile.phone_2_label', array('options' => $__phoneLabels, 'label' => '&nbsp;', 'div' => array('class' => 'input select larger_label_space'))); ?><br />
								<?php #echo $this->Form->error('UserProfile.phone_2_label', __('select_one')); ?>
							</div>							
						</div>
						<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
							<div class="">
								<?php echo $this->Form->input('UserProfile.phone_3_number', array('error' => array('phone' => __('phone_us')), 'label' => __('Phone 3'), 'div' => array('class' => 'input text larger_label_space'))); ?>
								<?php echo $this->Form->input('UserProfile.phone_3_label', array('options' => $__phoneLabels, 'label' => '&nbsp;', 'div' => array('class' => 'input select larger_label_space'))); ?><br />
								<?php #echo $this->Form->error('UserProfile.phone_3_label', __('select_one')); ?>
							</div>							
						</div>
						
					
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<br />
							<?php echo $this->Form->input('UserProfile.timezone', array('options' => Set::combine(DateTimeZone::listIdentifiers(), '/', '/'), 'div' => array('class' => 'input select larger_label_space'))); ?>
							<br /><br />
						</div>
						<div class="clear col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<h5><?php echo __('Profile Image'); ?>&nbsp;&nbsp;<div class="light small inline">(100px x 100px)</div></h5>
							<?php if (array_key_exists('ProfileImage', $this->request->data) && (!empty($this->request->data['ProfileImage']['bytes']))): ?>
								<span class="label">
									<?php $image = $this->request->data['ProfileImage'];?>
									<?php echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/ProfileImage/' . $image['name'], array('class' => 'user_profile dim100x100'))?>
								</span>
								<?php echo $this->Html->link(__('Delete'), array('controller' => 'users', 'action' => 'delete_image', $image['id'], $image['foreign_key']), array('escape' => false, 'class' => 'image-btn'), __('delete_confirm')); ?>
							<?php else : ?>
								<?php 
								if ($permissions['admin'] == 1 || $permissions['owner'] == 1) {
									echo $this->Form->input('ProfileImage.name', array(
										'type' => 'file',
										'label' => false,
										'error' => false,
									)); 
								} ?>
							<?php endif; ?>
						
							<?php echo $this->Form->error('ProfileImage.name', __('file_type', true)); ?>
							<?php echo $this->Form->error('ProfileImage.name', __('uploaded', true)); ?>
							<?php echo $this->Form->error('ProfileImage.name', __('max_size', true)); ?>
						</div>
						<?php if ($permissions['admin'] == 1): ?>
						<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4">
							<h5><?php echo __('Signature Image'); ?>&nbsp;&nbsp;<div class="light small inline">(100px x 310px)</div></h5>
							<?php if (array_key_exists('SignatureImage', $this->request->data) && (!empty($this->request->data['SignatureImage']['bytes']))): ?>
								<span class="label">
									<?php $image = $this->request->data['SignatureImage'];?>
									<?php echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/SignatureImage/' . $image['name'], array('class' => 'user_signature'))?>
								</span>
								<?php echo $this->Html->link(__('Delete'), array('controller' => 'users', 'action' => 'delete_image', $image['id'], $image['foreign_key']), array('escape' => false, 'class' => 'image-btn'), __('delete_confirm')); ?>
							<?php else : ?>
								<?php 
								echo $this->Form->input('SignatureImage.name', array(
									'type' => 'file',
									'label' => false,
									'error' => false,
								)); 
								?>
							<?php endif; ?>
						
							<?php echo $this->Form->error('SignatureImage.name', __('file_type', true)); ?>
							<?php echo $this->Form->error('SignatureImage.name', __('uploaded', true)); ?>
							<?php echo $this->Form->error('SignatureImage.name', __('max_size', true)); ?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<div class="row fieldset-wrapper available_action_container">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<div class="title-buttons">
							<?php 
							if($permissions['read_only'] == -1) {
								echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
							} ?>
						</div>	
					</div>											  
				</div>
			</fieldset>
		<?php #endif; ?>
		<?php echo $this->Form->hidden('id'); ?>
		<?php echo $this->Form->hidden('UserProfile.id'); ?>
	<?php echo $this->Form->end(); ?>
</div>
<?php $this->start('metaHeader'); ?>
	<?php
	if(!$owner && ($permissions['admin'] == 1)) {
		echo $this->element('header_tabs');
		echo $this->element('tabs_user', array('user' => $this->data, 'owner' => $owner, 'permissions' => $permissions)); 
	} ?>
<?php $this->end(); ?>