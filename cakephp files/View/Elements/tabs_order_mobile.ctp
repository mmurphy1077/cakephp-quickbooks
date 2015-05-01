<?php
if(!isset($type)) {
	$type == 'tab';
}
$steps = array(
	'customer_info' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'job_info' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'schedule' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'track' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'invoice' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'history' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'docs' => array(
		'class' => 'normal',
		'params' => null,
	),
	'messages' => array(
		'class' => 'normal',
		'params' => null,
	),
);

/* PARAMETERS */
$steps['customer_info']['params'] = array($order['Order']['id']);
$steps['job_info']['params'] = array($order['Order']['id']);
$steps['schedule']['params'] = array($order['Order']['id']);
$steps['track']['params'] = array($order['Order']['id']);
$steps['invoice']['params'] = array($order['Order']['id']);
$steps['history']['params'] = array($order['Order']['id']);
$steps['docs']['params'] = array($order['Order']['id']);
$steps['messages']['params'] = array($order['Order']['id']);

/* INITIALIZE */
$steps['customer_info']['class'] = 'normal';
$steps['job_info']['class'] = 'normal';
$steps['schedule']['class'] = 'normal';
$steps['track']['class'] = 'normal';
$steps['invoice']['class'] = 'normal';
$steps['history']['class'] = 'normal';
$steps['docs']['class'] = 'normal';
$steps['messages']['class'] = 'normal';

$location = $this->name.METHOD_SEPARATOR.$this->action;
switch ($location) {
	case 'Orders::customer_info':
		$steps['customer_info']['class'] = 'active';
		break;
	case 'OrderLineItems::add':
		$steps['job_info']['class'] = 'active';
		break;
	case 'Orders::schedules':
	case 'Orders::add_schedule':
	case 'Orders::edit_schedule':
		$steps['schedule']['class'] = 'active';
		break;
	case 'Orders::track_mobile_index':
	case 'OrderTimes::add':
	case 'OrderTimes::edit':
	case 'OrderMaterials::add':
	case 'OrderMaterials::edit':
		$steps['track']['class'] = 'active';
		break;
	case 'Orders::materials':
	case 'Orders::labor_hours':
	case 'OrderTimes::index':
	case 'OrderMaterials::index':
	case 'Invoices::index_order':
	case 'Invoices::edit':
	case 'Invoices::view':
		$steps['invoice']['class'] = 'active';
		break;
	case 'Orders::docs':
		$steps['docs']['class'] = 'active';
		break;
	case 'Orders::messages':
	case 'Messages::view':
	case 'Messages::add':
		$steps['messages']['class'] = 'active';
		break;
	case 'Orders::previous_orders_at_location_items' :
		$steps['history']['class'] = 'active';
		break;
}

if($type == 'tab') : ?>
<div id="order-tab-mobile" class="order tab">
	<div class="hidden-md hidden-lg">
		<div class="block <?php echo $steps['customer_info']['class']; ?>">
			<?php
			$link = '#';
			echo $this->Html->link('', $link, array('id' => 'mobile-menu-toggle', 'class' => 'text'));
			?>
		</div>
	</div>
	<div class="visible-md-block visible-lg-block">
		<div class="block <?php echo $steps['customer_info']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.customer_info'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['customer_info']['params']);
			if ($steps['customer_info']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('1<br />Customer'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="block <?php echo $steps['job_info']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.items'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['job_info']['params']);
			if ($steps['job_info']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('2<br />' . Configure::read('Nomenclature.Order') . ' Info'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="block <?php echo $steps['schedule']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.schedules'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
			if ($steps['schedule']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('3<br />Schedule'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="block <?php echo $steps['track']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.production'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
			if ($steps['track']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('4<br />Track'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="block <?php echo $steps['invoice']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.invoices'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
			if ($steps['invoice']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('5<br />Invoice'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<!-- 
		<div class="block <?php echo $steps['invoice']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.production'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
			if ($steps['invoice']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('6<br />Collect'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		 -->
		<div class="block <?php echo $steps['history']['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.history'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['history']['params']);
			if ($steps['history']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('6<br />Past ' . Configure::read('Nomenclature.Order') . ' History'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
	</div>	
	
	
	<div id="button-comment" class="button-blue slider-activate right"></div>
	<div id="button-message" class="button-blue block <?php echo $steps['messages']['class']; ?> right">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.messages'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['messages']['params']);
		if ($steps['messages']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link('', $link, array('class' => 'text'));
		?>
	</div>
	<?php if($permissions['enable_file_upload'] == 1) : ?>
		<div id="button-upload" class="button-blue block <?php echo $steps['docs']['class']; ?> right">
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
			echo $this->Html->link('', $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
	<?php endif; ?>
</div>
<?php else : ?>
<ul id="order-tab-mobile" class="order list">
	<li>
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.customer_info'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['customer_info']['params']);
		if ($steps['customer_info']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('1....Customer Info'), $link, array('class' => 'text'));
		?>
	</li>
	<li>
	<?php
	list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.items'));
	$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['job_info']['params']);
	if ($steps['job_info']['class'] == 'disabled') {
		$link = '#';
	}
	echo $this->Html->link(__('2....Job Info'), $link, array('class' => 'text'));
	?>
	</li>
	<li>
	<?php
	list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.schedules'));
	$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
	if ($steps['invoice']['class'] == 'disabled') {
		$link = '#';
	}
	echo $this->Html->link(__('3....Schedule'), $link, array('class' => 'text'));
	?>
	</li>
	<li>
	<?php
	list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.production'));
	$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
	if ($steps['invoice']['class'] == 'disabled') {
		$link = '#';
	}
	echo $this->Html->link(__('4....Track'), $link, array('class' => 'text'));
	?>
	</li>
	<li>
	<?php
	list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.invoices'));
	$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
	if ($steps['invoice']['class'] == 'disabled') {
		$link = '#';
	}
	echo $this->Html->link(__('5....Invoice'), $link, array('class' => 'text'));
	?>
	</li>
	<!--  
	<li>
	<?php
	list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.production'));
	$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['invoice']['params']);
	if ($steps['invoice']['class'] == 'disabled') {
		$link = '#';
	}
	echo $this->Html->link(__('6....Collect'), $link, array('class' => 'text'));
	?>
	</li>
	 -->
	<li>
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Order.history'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['history']['params']);
		if ($steps['history']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('6....Past ' . Configure::read('Nomenclature.Order') . ' History'), $link, array('class' => 'text'));
		?>
	</li>
</ul>
<?php endif; ?>