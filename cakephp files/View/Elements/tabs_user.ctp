<?php
$steps = array(
	'data' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'permissions' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'docs' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'activity' => array(
		'class' => 'normal',
		'params' => array(),
	),
);
$current_action = $this->params['action'];
switch ($current_action) {
	case 'add':
	case 'edit':
		$steps['data']['class'] = 'active';
		$steps['permissions']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['activity']['class'] = 'normal';
		break;
	case 'edit_permissions':
		$steps['data']['class'] = 'normal';
		$steps['permissions']['class'] = 'active';
		$steps['docs']['class'] = 'normal';
		$steps['activity']['class'] = 'normal';
		break;
	case 'docs':
		$steps['data']['class'] = 'normal';
		$steps['permissions']['class'] = 'normal';
		$steps['docs']['class'] = 'active';
		$steps['activity']['class'] = 'normal';
		break;
	case 'activity_log':
		$steps['data']['class'] = 'normal';
		$steps['permissions']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['activity']['class'] = 'active';
		break;
}

/*
 * Determine if the Permissions tab should be disabled.
 * Disable if User.group_id is null OR
 * The logged in user does not have permission to access permissions
 */
$steps['permissions']['class'] = 'disabled';
$steps['docs']['class'] = 'disabled';
$steps['activity']['class'] = 'disabled';
if(array_key_exists('verification_code', $user['User']) && !empty($user['User']['verification_code'])) {
	if($permissions['enable_file_upload'] == 1) {
		$steps['permissions']['class'] = 'normal';
	}
	if($permissions['enable_file_upload'] == 1) {
		$steps['docs']['class'] = 'normal';
	}
	$steps['activity']['class'] = 'normal';
} ?>
<div class="quote tab">
	<div class="block <?php echo $steps['data']['class']; ?>">
		<?php
		$link = array('controller' => 'users', 'action' => 'edit', $user['User']['id']);
		if ($steps['data']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('User Info'), $link, array('class' => 'text'));
		?>
	</div>
	<?php if($permissions['enable_file_upload'] == 1) : ?>
		<div class="block <?php echo $steps['permissions']['class']; ?>">
			<?php
			$link = array('controller' => 'users', 'action' => 'edit_permissions', $user['User']['id']);
			if ($steps['permissions']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Permissions'), $link, array('class' => 'text'));
			?>
		</div>
	<?php endif; ?>
	<?php if($permissions['enable_file_upload'] == 1) : ?>
		<div class="block <?php echo $steps['docs']['class']; ?>">
			<?php
			$items = '';
			if(!empty($user['Document'])) {
				$items = '<span class="doc-count-container">' . count($user['Document']) . '</span>';
			}
			$link = array('controller' => 'users', 'action' => 'docs', $user['User']['id']);
			if ($steps['docs']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Docs & Files') . $items, $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
	<?php endif; ?>
	<div class="block <?php echo $steps['activity']['class']; ?> last">
		<?php
		$link = array('controller' => 'users', 'action' => 'activity_log', $user['User']['id']);
		if ($steps['activity']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Activity Log'), $link, array('class' => 'text'));
		?>
	</div>
	<div class="last">&nbsp;</div>
</div>