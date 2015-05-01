<?php
// Permissions
$customerAccess = $__user['Group']['Customer']['_access'];
$accountAccess = $__user['Group']['Account']['_access'];
$contactAccess = $__user['Group']['Contact']['_access'];
$quoterAccess = $__user['Group']['Quote']['_access'];
$orderAccess = $__user['Group']['Order']['_access'];
$scheduleAccess = $__user['Group']['Schedule']['_access'];
$materialAccess = $__user['Group']['Material']['_access'];
$invoiceAccess = $__user['Group']['Invoice']['_access'];
$userAccess = $__user['Group']['User']['_access'];

if(array_key_exists('Customer', $__user['User'])) {
	$customerAccess = $__user['User']['Customer']['_access'];
}
if(array_key_exists('Account', $__user['User'])) {
	$accountAccess = $__user['User']['Account']['_access'];
}
if(array_key_exists('Contact', $__user['User'])) {
	$contactAccess = $__user['User']['Contact']['_access'];
}
if(array_key_exists('Quote', $__user['User'])) {
	$quoterAccess = $__user['User']['Quote']['_access'];
}
if(array_key_exists('Order', $__user['User'])) {
	$orderAccess = $__user['User']['Order']['_access'];
}
if(array_key_exists('Schedule', $__user['User'])) {
	$scheduleAccess = $__user['User']['Schedule']['_access'];
}
if(array_key_exists('Material', $__user['User'])) {
	$materialAccess = $__user['User']['Material']['_access'];
}
if(array_key_exists('Invoice', $__user['User'])) {
	$invoiceAccess = $__user['User']['Invoice']['_access'];
}
if(array_key_exists('User', $__user['User'])) {
	$userAccess = $__user['User']['User']['_access'];
}

if($customerAccess == 1) {
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index'));
}
if($accountAccess == 1) {
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Account'))), array('controller' => 'accounts', 'action' => 'index'));
}
if($contactAccess == 1) {
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Contact'))), array('controller' => 'contacts', 'action' => 'index'));
}
if($quoterAccess == 1) {
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Quote'))), array('controller' => 'quotes', 'action' => 'index'));
}


// ORDERS
if($orderAccess == 1) {	
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Order'))), array('controller' => 'orders', 'action' => 'index'));
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

if($materialAccess == 1) {
	$links[] = $this->Html->link(__(Configure::read('Nomenclature.Catalog')), array('controller' => 'materials', 'action' => 'index'));
}
if($invoiceAccess == 1) {
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Invoice'))), array('controller' => 'invoices', 'action' => 'index'));
}


/* Reports */
$reportAccess = -1;
$reportPermissions = $__user['Group']['Application'];
if(array_key_exists('Application', $__user['User'])) {
	$reportPermissions = $__user['User']['Application'];
}
if(($reportPermissions['_report_metrics'] == 1) || ($reportPermissions['_report_financial'] == 1) || ($reportPermissions['_report_labor'] == 1) || ($reportPermissions['_report_sales'] == 1) || ($reportPermissions['_report_orders'] == 1) || ($reportPermissions['_report_materials'] == 1)) {
	$reportAccess = 1;
}
if($reportAccess == 1) {
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Report'))), array('controller' => 'reports', 'action' => 'index'));
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

$links[] = $this->Html->link(__('Help'), array('controller' => 'help_items', 'action' => 'index'));
?>
<?php if (empty($location) || $location == 'header'): ?>
	<div id="nav">
		<ul>
			<li><?php echo join('</li><li>', $links); ?></li>
		</ul>
	</div>
<?php elseif ($location == 'footer'): ?>
	
<?php endif; ?>