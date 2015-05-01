<?php
$this->extend('/Templates/index');
$pageTitle = 'Users';
if (!empty($currentGroupId)) {
	$pageTitle .= ' &mdash; '.$__groups[$currentGroupId];
}
if (isset($currentStatus)) {
	$pageTitle .= ' &mdash; '.$__statuses[$currentStatus];
}
$this->assign('pageTitle', $pageTitle);
?>
<?php $this->start('tableHeaders'); ?>
	<th>&nbsp;</th>
	<th><?php echo __('Name'); ?></th>
	<th><?php echo __('Last Login'); ?></th>
	<th><?php echo __('Username'); ?></th>
	<th>&nbsp;</th>
<?php $this->end(); ?>
<?php foreach ($results as $result): ?>
	<tr>
		<td class="index-profile-pic">
			<?php if (!empty($result['ProfileImage']['name'])): ?>
				<?php echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/ProfileImage/' . $result['ProfileImage']['name'], array('class' => 'profile'))?>
				<?php #echo $this->element('zoom_image', array('model' => 'ProfileImage', 'imageName' => $result['ProfileImage'], 'size' => array('tiny', 'large'), 'options' => array('class' => 'profile'))); ?>
			<?php else: ?>
				<?php echo $this->Image->get('ProfileImage', PROFILE_IMAGE_DEFAULT, Configure::read('Images.ProfileImage.tiny'), array('class' => 'profile')); ?>
			<?php endif; ?>
		</td>
		<td><?php echo $result['User']['name_last']; ?>, <?php echo $result['User']['name_first']; ?></td>
		<td>
			<?php if (!empty($result['UserProfile']['visited'])): ?>
				<?php echo $this->Time->niceShort($result['UserProfile']['visited'], $result['UserProfile']['timezone']); ?>
			<?php else: ?>
				<?php echo __('n/a'); ?>
			<?php endif; ?>
		</td>
		<td><?php echo $result['User']['username']; ?></td>
		<td class="actions"><?php echo $this->Html->link(__('Details'), array('controller' => 'users', 'action' => 'view', $result['User']['id'])); ?></td>
	</tr>
<?php endforeach; ?>
<?php $this->start('buttons'); ?>
	<?php if ($__user['User']['User']['_create'] == 1): ?>
		<?php echo $this->Html->link(__('Add User'), array('controller' => 'users', 'action' => 'add')); ?>
	<?php endif; ?>
<?php $this->end(); ?>