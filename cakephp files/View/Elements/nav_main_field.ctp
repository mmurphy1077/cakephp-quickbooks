<?php
// Permissions
$orderAccess = $__user['Group']['Order']['_access'];
$scheduleAccess = $__user['Group']['Schedule']['_access'];
$userAccess = $__user['Group']['User']['_access'];	

if(array_key_exists('Order', $__user['User'])) {
	$orderAccess = $__user['User']['Order']['_access'];
}
if(array_key_exists('Schedule', $__user['User'])) {
	$scheduleAccess = $__user['User']['Schedule']['_access'];
}
if(array_key_exists('User', $__user['User'])) {
	$userAccess = $__user['User']['User']['_access'];
}
// SCHEDULES
if($scheduleAccess == 1) {
	// Determine if the user is able to manage schedules... or only see their schedules they have been assigned too.
	if($__user['User']['Schedule']['_view_assigned_only'] == 1) {
		$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Schedule'))), array('controller' => 'schedules', 'action' => 'index_assigned', $__user['User']['id']));
	} else if(($__user['User']['Schedule']['_create'] == 1) || ($__user['User']['Schedule']['_update'] == 1)) {
		$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Schedule'))), array('controller' => 'schedules', 'action' => 'index'));
	}
}
// ORDERS
if($orderAccess == 1) {	
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index'));
}
// MESSAGES
$user_unread_message_count = '';
if(!empty($__user_unread_message_count)) {
	$user_unread_message_count = '&nbsp;&nbsp;&nbsp;<div class="unread-message-alert">' . $__user_unread_message_count . '</div>';
}
$links[] = $this->Html->link(__('Messages') . $user_unread_message_count, array('controller' => 'users', 'action' => 'dashboard', 'messages'), array('escape' => false));
// USERS
if($userAccess == 1) {	
	$links[] = $this->Html->link(__('Users'), array('controller' => 'users', 'action' => 'index'));
}
?>
<?php if (empty($location) || $location == 'header'): ?>
	<div id="nav">
		<ul>
			<li><?php echo join('</li><li>', $links); ?></li>
		</ul>
	</div>
<?php elseif ($location == 'footer'): ?>
	
<?php endif; ?>