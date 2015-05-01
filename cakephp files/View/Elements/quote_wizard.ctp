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
);
$location = $this->name.METHOD_SEPARATOR.$this->action;
$q = $this->Session->read('Quotes.Quote');
if (empty($q)) {
	switch ($location) {
		case Configure::read('Quoting.step1'):
			$steps[1]['class'] = 'active';
			$steps[2]['class'] = 'disabled';
			$steps[3]['class'] = 'disabled';
			$steps[4]['class'] = 'disabled';
			break;
		case Configure::read('Quoting.step2'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'active';
			$steps[3]['class'] = 'disabled';
			$steps[4]['class'] = 'disabled';
			break;
		case 'QuoteLineItems::add':
		case 'QuoteJobLineItems::edit':
		case 'QuoteJobRequirements::edit':
		case Configure::read('Quoting.step3'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'active';
			$steps[4]['class'] = 'disabled';
			break;
		case Configure::read('Quoting.step3_edit'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'active';
			$steps[4]['class'] = 'disabled';
			break;
		case Configure::read('Quoting.view'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'normal';
			$steps[4]['class'] = 'active';
			break;
	}
} else {
	$steps[1]['params'] = array($q['Quote']['id']);
	$steps[2]['params'] = array($q['Quote']['id']);
	$steps[3]['params'] = array($q['Quote']['id']);
	$steps[4]['params'] = array($q['Quote']['id']);
	switch ($location) {
		case Configure::read('Quoting.step1'):
			$steps[1]['class'] = 'active';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'normal';
			$steps[4]['class'] = 'normal';
			break;
		case Configure::read('Quoting.step2'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'active';
			$steps[3]['class'] = 'normal';
			$steps[4]['class'] = 'normal';
			break;
		case 'QuoteLineItems::add':
		case 'QuoteJobLineItems::edit':
		case 'QuoteJobRequirements::edit':
		case Configure::read('Quoting.step3'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'active';
			$steps[4]['class'] = 'normal';
			break;
		case Configure::read('Quoting.step3_edit'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'active';
			$steps[4]['class'] = 'normal';
			break;
		case Configure::read('Quoting.view'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'normal';
			$steps[4]['class'] = 'active';
			break;
	}
}
?>
<div class="quote wizard">
	<div class="grid">
		<div class="block <?php echo $steps[1]['class']; ?> first">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.step1'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[1]['params']);
			if ($steps[1]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Customer'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps[2]['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.step2'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[2]['params']);
			if ($steps[2]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Job Site Info'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps[3]['class']; ?>">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.step3'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[3]['params']);
			if ($steps[3]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.QuoteJob'))), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $steps[4]['class']; ?> last">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Quoting.view'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[4]['params']);
			if ($steps[4]['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Review'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="last">&nbsp;</div>
	</div>
</div>