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
	'messages' => array(
			'class' => 'normal',
			'params' => null,
	),
	'quotes' => array(
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
	'activity_log' => array(
			'class' => 'normal',
			'params' => null,
	),
);
$location = $this->name.METHOD_SEPARATOR.$this->action;
$c = $this->Session->read('Customers.Quote');
if(!empty($customer)) {
	$c = $customer;
}
if (!empty($c)) {
	// Quote in database.
	$steps['snap_shot']['params'] = array($c['Customer']['id']);
	$steps['general_info']['params'] = array($c['Customer']['id']);
	$steps['address']['params'] = array($c['Customer']['id']);
	$steps['contact']['params'] = array($c['Customer']['id']);
	$steps['docs']['params'] = array($c['Customer']['id']);
	$steps['messages']['params'] = array($c['Customer']['id']);
	$steps['quotes']['params'] = array($c['Customer']['id']);
	$steps['orders']['params'] = array($c['Customer']['id']);
	$steps['invoice']['params'] = array($c['Customer']['id']);
	$steps['activity_log']['params'] = array($c['Customer']['id']);
}
switch ($location) {
	case 'Customers::add':
		$steps['print']['class'] = 'disabled';
		$steps['snap_shot']['class'] = 'disabled';
		$steps['general_info']['class'] = 'active';
		$steps['address']['class'] = 'disabled';
		$steps['contact']['class'] = 'disabled';
		$steps['docs']['class'] = 'disabled';
		$steps['messages']['class'] = 'disabled';
		$steps['quotes']['class'] = 'disabled';
		$steps['orders']['class'] = 'disabled';
		$steps['invoice']['class'] = 'disabled';
		$steps['activity_log']['class'] = 'disabled';
		break;
	case 'Customers::print_docs':
		$steps['print']['class'] = 'active';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::view':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'active';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::edit':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'active';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::add_address':
	case 'Customers::addresses':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'active';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::contacts':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'active';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::docs':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'active';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::messages':
	case 'Messages::view':
	case 'Messages::add':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'active';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::quotes':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'active';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::orders':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'active';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::invoices':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'active';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Customers::activity_log':
		$steps['print']['class'] = 'normal';
		$steps['snap_shot']['class'] = 'normal';
		$steps['general_info']['class'] = 'normal';
		$steps['address']['class'] = 'normal';
		$steps['contact']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['quotes']['class'] = 'normal';
		$steps['orders']['class'] = 'normal';
		$steps['invoice']['class'] = 'normal';
		$steps['activity_log']['class'] = 'active';
		break;
}
?>
<div class="quote tab">
	<div class="left">
		<?php 
		if($steps['print']['class'] == 'active') {
			#echo $this->Html->link($this->Html->image('icon-print-active.png', array('id' => 'icon-print')), array('controller' => 'Customers', 'action' => 'print_docs'), array('escape' => false)); 
		} else {
			#echo $this->Html->link($this->Html->image('icon-print.png', array('id' => 'icon-print')), array('controller' => 'customers', 'action' => 'print_docs'), array('escape' => false));
		} ?>
		<div class="block <?php echo $steps['snap_shot']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.snap_shot'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['snap_shot']['params']);
			if ($steps['snap_shot']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Snap Shot'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['general_info']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.general_info'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['general_info']['params']);
			if ($steps['general_info']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('General Info'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['address']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.address'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['address']['params']);
			if ($steps['address']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Addresses'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['contact']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.contact'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['contact']['params']);
			if ($steps['contact']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Contacts'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['quotes']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.quotes'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['quotes']['params']);
			if ($steps['quotes']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Quotes'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['orders']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.orders'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['orders']['params']);
			if ($steps['orders']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Order'))), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['invoice']['class']; ?> last">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.invoice'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
			if ($steps['invoice']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Invoices'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['docs']['class']; ?>">
			<?php
			$items = '';
			if(!empty($c['Document'])) {
				$items = '<span class="doc-count-container">' . count($c['Document']) . '</span>';
			}
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.docs'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['docs']['params']);
			if ($steps['docs']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Docs & Files') . $items, $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="block <?php echo $steps['messages']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.messages'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['docs']['params']);
			if ($steps['messages']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Messages'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['activity_log']['class']; ?> last">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Customer.activity_log'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['activity_log']['params']);
			if ($steps['activity_log']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Activity Log'), $link, array('class' => 'text'));
			?>
		</div>
	</div>
</div>