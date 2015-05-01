<?php
$steps = array(
	1 => array(
		'class' => 'normal',
		'params' => array(),
	),
	2 => array(
		'class' => 'normal',
		'params' => array(),
	),
	3 => array(
		'class' => 'normal',
		'params' => array(),
	),
	4 => array(
		'class' => 'normal',
		'params' => array(),
	),
	5 => array(
		'class' => 'normal',
		'params' => array(),
	),
	'view' => array(
		'class' => 'normal',
		'params' => array(),
	),
);
// Determine which step the status of order is in.
if(true) {
	// The order has completed its lifecycle.  All the steps are enabled.
	// Set the focus to step 1
	$steps[1]['params'] = array($order['Order']['id']);
	$steps[2]['params'] = array($order['Order']['id']);
	$steps[3]['params'] = array($order['Order']['id']);
	$steps[4]['params'] = array($order['Order']['id']);
	$steps[5]['params'] = array('week', $order['Order']['id']);
	$steps['view']['params'] = array($order['Order']['id']);
	
	$steps[1]['class'] = 'normal';
	$steps[2]['class'] = 'normal';
	$steps[3]['class'] = 'normal';
	$steps[4]['class'] = 'normal';
	$steps[5]['class'] = 'normal';
	$steps['view']['class'] = 'normal';

	if(!empty($location)) {
		$steps[$location]['class'] = 'active';
	} else {
		$steps[1]['class'] = 'active';	
	}
	
} else if(!empty($order['OrderRequirement'])) {
	// The order has OrderRequirements but not Scheduled yet.  Set the steps 1 - 4 are enabled
	// Set the focus to Schedule (step 5)
	$steps[1]['params'] = array($order['Order']['id']);
	$steps[2]['params'] = array($order['Order']['id']);
	$steps[3]['params'] = array($order['Order']['id']);
	$steps[4]['params'] = array($order['Order']['id']);
	$steps[5]['params'] = array('week', $order['Order']['id']);
	$steps['view']['params'] = array($order['Order']['id']);
	
	$steps[1]['class'] = 'normal';
	$steps[2]['class'] = 'normal';
	$steps[3]['class'] = 'normal';
	$steps[4]['class'] = 'normal';
	$steps[5]['class'] = 'normal';
	$steps['view']['class'] = 'normal';
	
	if(!empty($location)) {
		$steps[$location]['class'] = 'active';
	} else {
		$steps[5]['class'] = 'active';	
	}
	
} else if(!empty($order['OrderLineItem'])) {
	// Line Items have been entered.  Enable steps 1 - 3.
	// Set the focus to Order Requirements (step 4)
	// Dispable step 5 and 'view'
	$steps[1]['params'] = array($order['Order']['id']);
	$steps[2]['params'] = array($order['Order']['id']);
	$steps[3]['params'] = array($order['Order']['id']);
	$steps[4]['params'] = array($order['Order']['id']);
	
	$steps[1]['class'] = 'normal';
	$steps[2]['class'] = 'normal';
	$steps[3]['class'] = 'normal';
	$steps[4]['class'] = 'normal';
	$steps[5]['class'] = 'disabled';
	$steps['view']['class'] = 'disabled';
	
	if(!empty($location)) {
		$steps[$location]['class'] = 'active';
	} else {
		$steps[4]['class'] = 'active';	
	}
	
} else if(!empty($order['Order']['address_id'])) {
	// A Service Address for the Order has been entered.  The Order is at the Line Item phase (step 3)
	// Enable step 1 - 2.
	// Set the focus at step 3.
	// Disable steps 4 - 'view'
	$steps[1]['params'] = array($order['Order']['id']);
	$steps[2]['params'] = array($order['Order']['id']);
	$steps[3]['params'] = array($order['Order']['id']);
	
	$steps[1]['class'] = 'normal';
	$steps[2]['class'] = 'normal';
	$steps[3]['class'] = 'normal';
	$steps[4]['class'] = 'disabled';
	$steps[5]['class'] = 'disabled';
	$steps['view']['class'] = 'disabled';

	if(!empty($location)) {
		$steps[$location]['class'] = 'active';
	} else {
		$steps[3]['class'] = 'active';	
	}
	
} else if(!empty($order['Order']['customer_name']) && !empty($order['Order']['name'])) {
	// A customer has been selected (step 1).  The order is at the Service Address phase (step 2)
	// Enable step 1.
	// Set the focus at step 2
	// Disable steps 3 - 'view'
	$steps[1]['params'] = array($order['Order']['id']);
	$steps[2]['params'] = array($order['Order']['id']);
	
	$steps[1]['class'] = 'normal';
	$steps[2]['class'] = 'normal';
	$steps[3]['class'] = 'disabled';
	$steps[4]['class'] = 'disabled';
	$steps[5]['class'] = 'disabled';
	$steps['view']['class'] = 'disabled';
	
	if(!empty($location)) {
		$steps[$location]['class'] = 'active';
	} else {
		$steps[2]['class'] = 'active';	
	}
	
} else {
	// Set the focus at step 1
	// Disable steps 2 - 'view'
	$steps[1]['params'] = array($order['Order']['id']);
	
	$steps[1]['class'] = 'active';
	$steps[2]['class'] = 'disabled';
	$steps[3]['class'] = 'disabled';
	$steps[4]['class'] = 'disabled';
	$steps[5]['class'] = 'disabled';
	$steps['view']['class'] = 'disabled';
	
	if(!empty($location)) {
		$steps[$location]['class'] = 'active';
	} else {
		$steps[1]['class'] = 'active';	
	}
}
if(!empty($order)) :
?>
<div class="quote wizard">
	<div class="grid">
		<div class="block <?php echo $steps[1]['class']; ?> first">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Orders.step1'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[1]['params']);
			if ($steps[1]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Customer'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps[2]['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Orders.step2'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[2]['params']);
			if ($steps[2]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Service Address'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps[3]['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Orders.step3'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[3]['params']);
			if ($steps[3]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.OrderLineItem'))), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps[4]['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Orders.step4'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[2]['params']);
			if ($steps[4]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.OrderRequirement'))), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps[5]['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Orders.step5'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[3]['params']);
			if ($steps[5]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__(Configure::read('Nomenclature.Schedule').' Work'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps['view']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Orders.view'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[4]['params']);
			if ($steps['view']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('View'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="last">&nbsp;</div>
	</div>
</div>
<?php else: ?>
<?php endif; ?>