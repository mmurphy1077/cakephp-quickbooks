<?php
$report = array(
	'customer' => array(
		'class' => 'disabled',
	),
	'sales' => array(
		'class' => 'disabled',
	),
	'productivity' => array(
		'class' => 'disabled',
	),
	'revenue' => array(
		'class' => 'disabled',
	),
	'alerts' => array(
		'class' => 'disabled',
	),
	'billing' => array(
		'class' => 'disabled',
	),
);

// Use permissions to determine if the user hass access to the reports.
if($__user['Group']['Application']['_report_financial'] == 1) {
	$report['billing']['class'] = 'normal';
	$report['revenue']['class'] = 'normal';
}
if($__user['Group']['Application']['_report_sales'] == 1) {
	$report['sales']['class'] = 'normal';
}
if($__user['Group']['Application']['_report_metrics'] == 1) {
	$report['productivity']['class'] = 'normal';
	$report['customer']['class'] = 'normal';
	$report['alerts']['class'] = 'normal';
}

// Set the current tab
$report[$current_tab]['class'] = 'active';
$controller = 'reports' ;
$action = 'index';
?>
<div class="quote tab">
	<div class="left">
		<div class="block <?php echo $report['customer']['class']; ?>">
			<?php
			$link = array('controller' => $controller, 'action' => $action, 'customer');
			if ($report['customer']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Customer'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $report['sales']['class']; ?>">
			<?php
			$link = array('controller' => $controller, 'action' => $action, 'sales');
			if ($report['sales']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Sales'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $report['revenue']['class']; ?>">
			<?php
			$link = array('controller' => $controller, 'action' => $action, 'revenue');
			if ($report['revenue']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Revenue'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $report['productivity']['class']; ?>">
			<?php
			$link = array('controller' => $controller, 'action' => $action, 'productivity');
			if ($report['productivity']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Productivity'), $link, array('class' => 'text'));
			?>
		</div> 
		<div class="block <?php echo $report['billing']['class']; ?> last">
			<?php
			$link = array('controller' => $controller, 'action' => $action, 'billing');
			if ($report['billing']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Billing'), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $report['alerts']['class']; ?> last">
			<?php
			$link = array('controller' => $controller, 'action' => $action, 'alerts');
			if ($report['alerts']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Alerts'), $link, array('class' => 'text'));
			?>
		</div>
	</div>
</div>