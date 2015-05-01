<?php
$steps = array(
	'print' => array(
		'class' => 'normal',
		'params' => null,
	),
	'snap_shot' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'customer_info' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'job_info' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'requirements' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'purchasing' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'production' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'docs' => array(
		'class' => 'normal',
		'params' => null,
	),
	'tasks' => array(
		'class' => 'normal',
		'params' => null,
	),
	'activity_log' => array(
		'class' => 'normal',
		'params' => null,
	),
	'messages' => array(
		'class' => 'normal',
		'params' => null,
	),
);
if($order['Order']['status'] >= ORDER_STATUS_CANCELLED) {
	// Order in database.
	$steps['print']['params'] = array($order['Order']['id']);
	$steps['snap_shot']['params'] = array($order['Order']['id']);
	$steps['customer_info']['params'] = array($order['Order']['id']);
	$steps['job_info']['params'] = array($order['Order']['id']);
	$steps['requirements']['params'] = array($order['Order']['id']);
	$steps['purchasing']['params'] = array($order['Order']['id']);
	$steps['production']['params'] = array($order['Order']['id']);
	$steps['docs']['params'] = array($order['Order']['id']);
	$steps['tasks']['params'] = array($order['Order']['id']);
	$steps['activity_log']['params'] = array($order['Order']['id']);
	$steps['messages']['params'] = array($order['Order']['id']);
}
$steps['purchasing']['class'] = 'disabled';
if($permissions['can_access_pos'] == 1) {
	$steps['purchasing']['class'] = 'normal';
} 

$steps['print']['class'] = 'normal';
$steps['snap_shot']['class'] = 'normal';
$steps['customer_info']['class'] = 'normal';
$steps['job_info']['class'] = 'normal';
$steps['requirements']['class'] = 'normal';
$steps['purchasing']['class'] = 'normal';
$steps['production']['class'] = 'normal';
$steps['docs']['class'] = 'normal';
$steps['tasks']['class'] = 'normal';
$steps['messages']['class'] = 'normal';
$steps['activity_log']['class'] = 'normal';
$location = $this->name.METHOD_SEPARATOR.$this->action;
switch ($location) {
	case 'Orders::print_docs':
		$steps['print']['class'] = 'active';
		$steps['purchasing']['class'] = $steps['purchasing']['class'];
		break;
	case 'Orders::view':
		$steps['snap_shot']['class'] = 'active';
		$steps['purchasing']['class'] = $steps['purchasing']['class'];
		break;
	case 'Orders::customer_info':
		$status_class = 'disabled';
		if($order['Order']['status'] >= ORDER_STATUS_CANCELLED) {
			$status_class = 'normal';
		}
		$steps['customer_info']['class'] = 'active';
		break;
	case 'Orders::job_info':
	case 'Orders::view_order_items':
	case 'OrderLineItems::add':
	case 'ChangeOrderRequests::add':
	case 'Orders::schedules':
	case 'Orders::add_schedule':
	case 'Orders::edit_schedule':
	case 'OrderRequirements::edit':
	case 'Orders::purchasing':
	case 'OrderTasks::index':
		$steps['job_info']['class'] = 'active';
		$steps['purchasing']['class'] = $steps['purchasing']['class'];
		break;
	case 'Orders::production':
	case 'Orders::materials':
	case 'Orders::labor_hours':
	case 'OrderTimes::add':
	case 'OrderTimes::edit':
	case 'OrderTimes::index':
	case 'OrderMaterials::index':
	case 'OrderMaterials::index_purchases':
	case 'OrderMaterials::edit_purchase':
	case 'OrderMaterials::edit':
	case 'OrderMaterials::add':
	case 'Invoices::index_order':
	case 'Invoices::edit':
	case 'Invoices::view':
		$steps['purchasing']['class'] = $steps['purchasing']['class'];
		$steps['production']['class'] = 'active';
		break;
	case 'Orders::docs':
		$steps['purchasing']['class'] = $steps['purchasing']['class'];
		$steps['docs']['class'] = 'active';
		break;
	case 'Orders::activity_log':
		$steps['purchasing']['class'] = $steps['purchasing']['class'];
		$steps['activity_log']['class'] = 'active';
		break;
	case 'Orders::messages':
	case 'Messages::view':
		$steps['purchasing']['class'] = $steps['purchasing']['class'];
		$steps['messages']['class'] = 'active';
		break;
	case 'Messages::add':
		$steps['production']['class'] = 'normal';
		$steps['messages']['class'] = 'active';
		
		// Is the message being added from the messages module (default)...
		// Or from the Invoice (Billing) tab
		if(array_key_exists('subject', $this->data['Message']) && $this->data['Message']['subject']) {
			// Kinda of a hack... check if "Invoice" is in the subject.
			if(!(strpos(strtolower($this->data['Message']['subject']), 'invoice') === false)) {
				$steps['messages']['class'] = 'normal';
				$steps['production']['class'] = 'active';
			}
		}
		break;
}
?>
<div class="order tab">
	<div id="button-comment" class="slider-activate icon-print-active left">
	</div>
	<div class="first block <?php echo $steps['snap_shot']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.snap_shot'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['snap_shot']['params']);
		if ($steps['snap_shot']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Snapshot'), $link, array('class' => 'text'));
		?>
	</div>
	<?php if(($permissions['can_update'] == 1) || ($permissions['read_only'] == 1)) : ?>
	<div class="block <?php echo $steps['customer_info']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.customer_info'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['customer_info']['params']);
		if ($steps['customer_info']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Customer Info'), $link, array('class' => 'text'));
		?>
	</div>
	<?php endif; ?>
	<?php if(($permissions['can_update'] == 1) || ($permissions['read_only'] == 1)) : ?>
	<div class="block <?php echo $steps['job_info']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.job_info'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['job_info']['params']);
		if ($steps['job_info']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Job Info'), $link, array('class' => 'text'));
		?>
	</div>
	<?php endif; ?>
	<?php 
	#debug($permissions);
	#if(($permissions['can_update'] == 1) || ($permissions['read_only'] == 1) || ($permissions['labor_create'] == 1)) : ?>
	<div class="block <?php echo $steps['production']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.production'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['production']['params']);
		if ($steps['production']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Billing'), $link, array('class' => 'text'));
		?>
	</div>
	<?php #endif; ?>
	<?php if($permissions['enable_file_upload'] == 1) : ?>
	<div class="block <?php echo $steps['docs']['class']; ?>">
		<?php
		$items = '';
		if(!empty($doc_count)) {
			$items = '<span class="doc-count-container">' . $doc_count . '</span>';
		}
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.docs'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => 'docs'), $steps['docs']['params']);
		if ($steps['docs']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Docs & Files'). $items , $link, array('class' => 'text', 'escape' => false));
		?>
	</div>
	<?php endif; ?>
	<div class="block <?php echo $steps['messages']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.messages'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['messages']['params']);
		if ($steps['messages']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Messages'), $link, array('class' => 'text'));
		?>
	</div>
	<div class="block <?php echo $steps['activity_log']['class']; ?> last">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.activity_log'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['activity_log']['params']);
		if ($steps['activity_log']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Activity Log'), $link, array('class' => 'text'));
		?>
	</div>
</div>