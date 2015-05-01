<?php
$this->extend('/Templates/two_column');
$subTitle = null;
if (!empty($result['UserProfile']['title'])) {
	$subTitle = ' &mdash; '.$result['UserProfile']['title'];
}
$this->assign('pageTitle', 'User Account: ' . $result['User']['name_first'].' '.$result['User']['name_last'].$subTitle);
$permissions = $this->Permission->getPermissions($__permissions);
?>
<?php $this->start('buttonsRight'); ?>
	<?php if ($permissions['admin'] == 1): ?>
		<?php echo $this->Html->link(__('Cancel'), array('controller' => 'users', 'action' => 'index')); ?>
	<?php endif; ?>
	<?php if ($permissions['admin'] == 1 || $permissions['owner'] == 1): ?>
		<?php echo $this->Html->link(__('Edit'), array('controller' => 'users', 'action' => 'edit', $result['User']['id'])); ?>
	<?php endif; ?>
<?php $this->end(); ?>
<?php $this->start('leftside'); ?>
	<div class="widget clear center">
		<table class="data1">
			<?php if (!empty($result['UserProfile']['phone_1_number'])): ?>
				<tr>
					<th><?php echo __($result['UserProfile']['phone_1_label']); ?></th>
					<td><?php echo $result['UserProfile']['phone_1_number']; ?></td>
				</tr>
			<?php endif; ?>
			<?php if (!empty($result['UserProfile']['phone_2_number'])): ?>
				<tr>
					<th><?php echo __($result['UserProfile']['phone_2_label']); ?></th>
					<td><?php echo $result['UserProfile']['phone_2_number']; ?></td>
				</tr>
			<?php endif; ?>
			<?php if (!empty($result['UserProfile']['phone_3_number'])): ?>
				<tr>
					<th><?php echo __($result['UserProfile']['phone_3_label']); ?></th>
					<td><?php echo $result['UserProfile']['phone_3_number']; ?></td>
				</tr>
			<?php endif; ?>
			<tr>
				<th><?php echo __('Group'); ?></th>
				<td><?php echo $this->Html->link(__($result['Group']['name']), array('controller' => 'users', 'action' => 'index', $result['Group']['id'])); ?></td>
			</tr>
			<tr>
				<th><?php echo __('Username'); ?></th>
				<td><?php echo $result['User']['username']; ?></td>
			</tr>
			<tr>
				<th><?php echo __('Email'); ?></th>
				<td><a href="mailto:<?php echo $result['User']['email']; ?>"><?php echo $result['User']['email']; ?></a></td>
			</tr>
			<tr>
				<th><?php echo __('Created'); ?></th>
				<td><?php echo $this->Time->niceShort($result['User']['created'], $result['UserProfile']['timezone']); ?></td>
			</tr>
			<tr>
				<th><?php echo __('Last Visited'); ?></th>
				<td><?php echo $this->Time->niceShort($result['UserProfile']['visited'], $result['UserProfile']['timezone']); ?></td>
			</tr>
			<tr>
				<th><?php echo __('Timezone'); ?></th>
				<td><?php echo $result['UserProfile']['timezone']; ?></td>
			</tr>
			<tr>
				<th><?php echo __('Status'); ?></th>
				<td><?php echo $userStatuses[$result['User']['status']]; ?></td>
			</tr>
		</table>
	</div>
<?php $this->end(); ?>
<?php $this->start('rightside'); ?>
	<div id="profile-image-view">
	<?php if (!empty($result['ProfileImage']['name'])): ?>
		<?php echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/ProfileImage/' . $result['ProfileImage']['name'], array('class' => 'user_profile'))?>
		<?php #echo $this->element('zoom_image', array('model' => 'ProfileImage', 'imageName' => $result['ProfileImage'], 'size' => array('profile', 'large'), 'options' => array('class' => 'profile'))); ?>
	<?php else: ?>
		<?php echo $this->Image->get('ProfileImage', PROFILE_IMAGE_DEFAULT, Configure::read('Images.ProfileImage.profile'), array('class' => 'profile')); ?>
	<?php endif; ?>
	</div>
<?php $this->end(); ?>