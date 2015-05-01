<?php
$steps = array(
	'print' => array(
		'class' => 'normal',
		'params' => null,
	),
	'customer_info' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'job_info' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'line_item' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'docs' => array(
		'class' => 'normal',
		'params' => null,
	),
	'comm' => array(
		'class' => 'normal',
		'params' => null,
	),
	'tasks' => array(
		'class' => 'normal',
		'params' => null,
	),
	'review' => array(
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
if($quote['Quote']['status'] >= QUOTE_STATUS_INACTIVE) {
	// Quote in database.
	$steps['print']['params'] = array($quote['Quote']['id']);
	$steps['customer_info']['params'] = array($quote['Quote']['id']);
	$steps['job_info']['params'] = array($quote['Quote']['id']);
	$steps['line_item']['params'] = array($quote['Quote']['id']);
	$steps['review']['params'] = array($quote['Quote']['id']);
	$steps['docs']['params'] = array($quote['Quote']['id']);
	$steps['comm']['params'] = array($quote['Quote']['id']);
	$steps['tasks']['params'] = array($quote['Quote']['id']);
	$steps['activity_log']['params'] = array($quote['Quote']['id']);
	$steps['messages']['params'] = array($quote['Quote']['id']);
}
$location = $this->name.METHOD_SEPARATOR.$this->action;
switch ($location) {
	case 'Quotes::print_docs':
		$steps['print']['class'] = 'active';
		$steps['customer_info']['class'] = 'normal';
		$steps['job_info']['class'] = 'normal';
		$steps['line_item']['class'] = 'normal';
		$steps['review']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case Configure::read('Quoting.customer_info'):
		$status_class = 'disabled';
		if($quote['Quote']['status'] >= QUOTE_STATUS_INACTIVE) {
			$status_class = 'normal';
		}
		$steps['print']['class'] = $status_class;
		$steps['customer_info']['class'] = 'active';
		$steps['job_info']['class'] = $status_class;
		$steps['line_item']['class'] = 'normal';
		$steps['review']['class'] = $status_class;
		$steps['docs']['class'] = $status_class;
		$steps['comm']['class'] = $status_class;
		$steps['tasks']['class'] = $status_class;
		$steps['messages']['class'] = $status_class;
		$steps['activity_log']['class'] = $status_class;
		break;
	case Configure::read('Quoting.job_info'):
		$steps['print']['class'] = 'normal';
		$steps['customer_info']['class'] = 'normal';
		$steps['job_info']['class'] = 'active';
		$steps['line_item']['class'] = 'normal';
		$steps['review']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case Configure::read('Quoting.line_item'):
		$steps['print']['class'] = 'normal';
		$steps['customer_info']['class'] = 'normal';
		$steps['job_info']['class'] = 'normal';
		$steps['line_item']['class'] = 'active';
		$steps['review']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case Configure::read('Quoting.review'):
	case Configure::read('Quoting.view'):
		$steps['print']['class'] = 'normal';
		$steps['customer_info']['class'] = 'normal';
		$steps['job_info']['class'] = 'normal';
		$steps['line_item']['class'] = 'normal';
		$steps['review']['class'] = 'active';
		$steps['docs']['class'] = 'normal';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case 'Quotes::docs':
		$steps['print']['class'] = 'normal';
		$steps['customer_info']['class'] = 'normal';
		$steps['job_info']['class'] = 'normal';
		$steps['line_item']['class'] = 'normal';
		$steps['review']['class'] = 'normal';
		$steps['docs']['class'] = 'active';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case Configure::read('Quoting.tasks'):
		$steps['print']['class'] = 'normal';
		$steps['customer_info']['class'] = 'normal';
		$steps['job_info']['class'] = 'normal';
		$steps['line_item']['class'] = 'normal';
		$steps['review']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'active';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'normal';
		break;
	case Configure::read('Quoting.activity_log'):
		$steps['print']['class'] = 'normal';
		$steps['customer_info']['class'] = 'normal';
		$steps['line_item']['class'] = 'normal';
		$steps['job_info']['class'] = 'normal';
		$steps['review']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'normal';
		$steps['messages']['class'] = 'normal';
		$steps['activity_log']['class'] = 'active';
		break;
	case 'Quotes::messages':
	case 'Messages::view':
	case 'Messages::add':
		$steps['print']['class'] = 'normal';
		$steps['customer_info']['class'] = 'normal';
		$steps['line_item']['class'] = 'normal';
		$steps['job_info']['class'] = 'normal';
		$steps['review']['class'] = 'normal';
		$steps['docs']['class'] = 'normal';
		$steps['comm']['class'] = 'normal';
		$steps['tasks']['class'] = 'normal';
		$steps['messages']['class'] = 'active';
		$steps['activity_log']['class'] = 'normal';
		break;
}
?>
<div class="quote tab">
	<div id="button-comment" class="slider-activate icon-print-active left">
	</div>
	<?php 
	/*
	if($steps['print']['class'] == 'active') {
		echo $this->Html->link($this->Html->image('icon-print-active.png', array('id' => 'icon-print')), array('controller' => 'quotes', 'action' => 'print_docs', $quote['Quote']['id']), array('class' => 'icon-print-active left', 'escape' => false)); 
	} else {
		echo $this->Html->link($this->Html->image('icon-print.png', array('id' => 'icon-print')), array('controller' => 'quotes', 'action' => 'print_docs', $quote['Quote']['id']), array('class' => 'icon-print left', 'escape' => false));
	} 
	*/ ?>
	<div class="first block <?php echo $steps['customer_info']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.customer_info'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['customer_info']['params']);
		if ($steps['customer_info']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Customer Info'), $link, array('class' => 'text'));
		?>
	</div>
	<div class="block <?php echo $steps['job_info']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.job_info'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['job_info']['params']);
		if ($steps['job_info']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Job Info'), $link, array('class' => 'text'));
		?>
	</div>
	<div class="block <?php echo $steps['line_item']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.line_item'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['line_item']['params']);
		if ($steps['line_item']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.QuoteLineItem'))), $link, array('class' => 'text'));
		?>
	</div>
	<div class="block <?php echo $steps['tasks']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.tasks'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['tasks']['params']);
		if ($steps['tasks']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Requirements'), $link, array('class' => 'text'));
		?>
	</div>
	<div class="block <?php echo $steps['review']['class']; ?>">
		<?php
		#list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.review'));
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.view'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['review']['params']);
		if ($steps['review']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Review/Adjust'), $link, array('class' => 'text'));
		?>
	</div>
	<div class="block <?php echo $steps['docs']['class']; ?>">
		<?php
		$items = '';
		if(!empty($doc_count)) {
			$items = '<span class="doc-count-container">' . $doc_count . '</span>';
		}
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.docs'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => 'docs'), $steps['docs']['params']);
		if ($steps['docs']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Docs & Files'). $items , $link, array('class' => 'text', 'escape' => false));
		?>
	</div>
	<!-- 
	<div class="block <?php echo $steps['comm']['class']; ?>">
		<?php
		/*
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.comm'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => 'communication'), $steps['comm']['params']);
		if ($steps['requirements']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Communication'), $link, array('class' => 'text'));
		*/
		?>
	</div>
	 -->
	<div class="block <?php echo $steps['messages']['class']; ?>">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.messages'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['messages']['params']);
		if ($steps['messages']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Messages'), $link, array('class' => 'text'));
		?>
	</div>
	<div class="block <?php echo $steps['activity_log']['class']; ?> last">
		<?php
		list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.activity_log'));
		$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps['activity_log']['params']);
		if ($steps['activity_log']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Activity Log'), $link, array('class' => 'text'));
		?>
	</div>
</div>