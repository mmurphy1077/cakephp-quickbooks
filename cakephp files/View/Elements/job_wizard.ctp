<?php
if(empty($errors)) {
	$errors = null;
}
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
$steps[1]['params'] = array($q['Quote']['id']);
$steps[2]['params'] = array($q['Quote']['id']);
$steps[3]['params'] = array($q['Quote']['id']);
$steps[4]['params'] = array($q['Quote']['id']);

if($errors) {
	$steps[1]['class'] = 'normal';
	$steps[2]['class'] = 'active';
	$steps[3]['class'] = 'active';
	$steps[4]['class'] = 'active';
} elseif (!empty($q)) {
	switch ($location) {
		case Configure::read('Jobs.step1'):
			$steps[1]['class'] = 'normal';
			$steps[2]['class'] = 'active';
			$steps[3]['class'] = 'active';
			$steps[4]['class'] = 'active';
			break;
		case Configure::read('Jobs.step2'):
			$steps[1]['class'] = 'active';
			$steps[2]['class'] = 'normal';
			$steps[3]['class'] = 'active';
			$steps[4]['class'] = 'active';
			break;
		case Configure::read('Jobs.step3'):
			$steps[1]['class'] = 'active';
			$steps[2]['class'] = 'active';
			$steps[3]['class'] = 'normal';
			$steps[4]['class'] = 'active';
			break;
		case Configure::read('Jobs.view'):
			$steps[1]['class'] = 'active';
			$steps[2]['class'] = 'active';
			$steps[3]['class'] = 'active';
			$steps[4]['class'] = 'normal';
			break;
	}
}
?>
<div id="job_wizard" class="quote wizard">
	<div class="grid">
		<div class="col-1of4 step <?php echo $steps[1]['class']; ?> first">
			<?php
			list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Jobs.step1'));
			$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[1]['params']);
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Confirm<br />Details'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="col-1of4 step <?php echo $steps[2]['class']; ?>">
			<?php
			if(!$errors) {
				list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Jobs.step2'));
				$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[2]['params']);
				echo $this->Html->link(null, $link, array('class' => 'dot'));
				echo $this->Html->link(__('Select Order<br />Requirements'), $link, array('class' => 'text', 'escape' => false));
			} else {
				echo '<div class="dot"></div>';
				echo 'Select Order<br />Requirements';
			}
			?>
		</div>
		<div class="col-1of4 step <?php echo $steps[3]['class']; ?>">
			<?php
			if(!$errors) {
				list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Jobs.step3'));
				$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[3]['params']);
				echo $this->Html->link(null, $link, array('class' => 'dot'));
				echo $this->Html->link(__('Schedule<br />Work'), $link, array('class' => 'text', 'escape' => false));
			} else {
				echo '<div class="dot"></div>';
				echo 'Schedule<br />Tasks';
			}
			?>
		</div>
		<div class="col-1of4 step <?php echo $steps[4]['class']; ?> last">
			<?php
			if(!$errors) {
				list($controller, $action) = explode(METHOD_SEPARATOR, Configure::read('Jobs.view'));
				$link = Set::merge(array('controller' => Inflector::tableize($controller), 'action' => $action), $steps[4]['params']);
				echo $this->Html->link(null, $link, array('class' => 'dot'));
				echo $this->Html->link(__('Complete'), $link, array('class' => 'text'));
			} else {
				echo '<div class="dot"></div>';
				echo 'Complete';
			}
			?>
		</div>
	</div>
</div>