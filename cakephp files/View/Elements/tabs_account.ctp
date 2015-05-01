<?php
$steps = array(
	'snap_shot' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'general_info' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'address' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'contact' => array(
		'class' => 'normal',
		'params' => null,
	),
	'docs' => array(
			'class' => 'normal',
			'params' => null,
	),
	'pos' => array(
			'class' => 'normal',
			'params' => null,
	),
	'orders' => array(
			'class' => 'normal',
			'params' => null,
	),
	'invoice' => array(
			'class' => 'normal',
			'params' => null,
	),
	'messages' => array(
			'class' => 'normal',
			'params' => null,
	),
	'activity_log' => array(
			'class' => 'normal',
			'params' => null,
	),
);
if (!empty($account)) {
	// Quote in database.
	$steps['snap_shot']['params'] = array($account['Account']['id']);
	$steps['general_info']['params'] = array($account['Account']['id']);
	$steps['address']['params'] = array($account['Account']['id']);
	$steps['contact']['params'] = array($account['Account']['id']);
	$steps['docs']['params'] = array($account['Account']['id']);
	$steps['pos']['params'] = array($account['Account']['id']);
	$steps['orders']['params'] = array($account['Account']['id']);
	$steps['invoice']['params'] = array($account['Account']['id']);
	$steps['messages']['params'] = array($account['Account']['id']);
	$steps['activity_log']['params'] = array($account['Account']['id']);
}
$location = $this->name.METHOD_SEPARATOR.$this->action;
switch ($location) {
	case 'Accounts::add':
		$steps['snap_shot']['class'] = 'disabled';
		$steps['general_info']['class'] = 'active';
		$steps['address']['class'] = 'disabled';
		$steps['contact']['class'] = 'disabled';
		$steps['docs']['class'] = 'disabled';
		$steps['pos']['class'] = 'disabled';
		$steps['orders']['class'] = 'disabled';
		$steps['invoice']['class'] = 'disabled';
		$steps['messages']['class'] = 'disabled';
		$steps['activity_log']['class'] = 'disabled';
		break;
	case 'Accounts::view':
		$steps['snap_shot']['class'] = 'active';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::edit':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'active';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::add_address':
	case 'Accounts::addresses':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'active';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::contacts':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'active';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::docs':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'active';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::purchase_orders':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'active';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::orders':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'active';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::invoices':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'active';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::messages':
	case 'Messages::view':
	case 'Messages::add':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'active';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Accounts::activity_log':
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['pos']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'active';
		break;
}
?>
<div class="quote tab">
	<div class="left">
		<div class="block <?php echo $steps['snap_shot']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.snap_shot'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['snap_shot']['params']);
			if ($steps['snap_shot']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Snap Shot'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['general_info']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.general_info'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['general_info']['params']);
			if ($steps['general_info']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('General Info'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['address']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.address'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['address']['params']);
			if ($steps['address']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Addresses'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['contact']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.contact'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['contact']['params']);
			if ($steps['contact']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Contacts'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['docs']['class']; ?>">
			<?php
			$items = '';
			if(!empty($account['Document'])) {
				$items = '<span class="doc-count-container">' . count($account['Document']) . '</span>';
			}
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.docs'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['docs']['params']);
			if ($steps['docs']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Docs & Files'). $items , $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="block <?php echo $steps['pos']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.pos'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['pos']['params']);
			if ($steps['pos']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Purchase Orders'), $link, array('class' => 'text'));
			?>
		</div>
		<!-- 
		<div class="block <?php echo $steps['orders']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.orders'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['orders']['params']);
			if ($steps['orders']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Order'))), $link, array('class' => 'text'));
			?>
		</div>
		 -->
		<!-- 
		<div class="block <?php echo $steps['invoice']['class']; ?> last">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.invoice'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
			if ($steps['invoice']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Invoices'), $link, array('class' => 'text'));
			?>
		</div>
		 -->
		 <div class="block <?php echo $steps['messages']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.messages'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['messages']['params']);
			if ($steps['messages']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Messages'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['activity_log']['class']; ?> last">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Account.activity_log'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['activity_log']['params']);
			if ($steps['activity_log']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Activity Log'), $link, array('class' => 'text'));
			?>
		</div>
	</div>
</div>