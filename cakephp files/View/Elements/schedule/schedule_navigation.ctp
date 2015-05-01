<?php
$scheduleViews = array(
	'Day' => 'normal',
	'Week' => 'normal',
	'Month' => 'normal',
	'Map' => 'normal',
);
$message_class = 'message';
if (!isset($show_map_option)) {
	$show_map_option = false;
}
if(!$show_map_option) {
	unset($scheduleViews['Map']);
}
if (!isset($show_month_option)) {
	$show_month_option = false;
}
if(!$show_month_option) {
	unset($scheduleViews['Month']);
}
$links = array();
foreach ($scheduleViews as $scheduleView => $class) {
	if ($type == strtolower($scheduleView)) {
		$class = 'active';
	}
	$order_id = null;
	if (!empty($order)) {
		// This variable should probably be $order
		$order_id = $order['Order']['id'];
	}
	switch ($scheduleView) {
		case 'Day':
			$links[] = $this->Html->link(__($scheduleView), array('controller' => $this->params['controller'], 'action' => 'index_day', 'order_id' => $order_id, 'date_selected' => $date), array('class' => $class));
			break;

		case 'Map' :
			$links[] = $this->Html->link(__($scheduleView), array('controller' => $this->params['controller'], 'action' => 'index_map', 'order_id' => $order_id, 'date_selected' => $date), array('class' => $class));
			break;
			
		case 'Month':
			$links[] = $this->Html->link(__($scheduleView), array('controller' => $this->params['controller'], 'action' => 'index_month', 'order_id' => $order_id, 'date_selected' => $date), array('class' => $class));
			break;
				
		case 'Week' :
			$links[] = $this->Html->link(__($scheduleView), array('controller' => $this->params['controller'], 'action' => 'index_week', 'order_id' => $order_id, 'date_selected' => $date), array('class' => $class));
			break;
	}
}
?>
<div class="schedule-nav">
	<span class="bold"><?php echo __('View By'); ?>:&nbsp;</span>
	<span class="light"><?php echo join(' | ', $links); ?></span>
</div>