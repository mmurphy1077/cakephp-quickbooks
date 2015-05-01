<?php 
switch($display) {
	case 'day':
		$prevDate = $date_markers['previous_day'];
		$nextDate = $date_markers['next_day'];
		break;
	case 'week':
		$prevDate = $date_markers['beginning_of_previous_week'];
		$nextDate = $date_markers['beginning_of_preceeding_week'];
		break;
	case 'month':
		$prevDate = $date_markers['beginning_of_previous_month'];
		$nextDate = $date_markers['beginning_of_preceeding_month'];
		break;
}
$order_id = null;
if (!empty($order)) {
	// This variable should probably be $order
	$order_id = $order['Order']['id'];
} 
if(!isset($class)) {
	$class = '';
}
?>
<?php echo $this->Html->link($this->Html->image('button-arrow-left.png'), array('controller' => $this->params['controller'], 'action' => 'index_'.$display, 'order_id' => $order_id, 'date_selected' => $prevDate), array('class' => 'left button_arrow button_arrow_left', 'escape' => false)); ?>
<h3 id="schedule-date-display" class="<?php echo $class; ?> left"><?php echo $this->Time->format('l F j, Y', $date_markers['date_selected']); ?></h3>
<?php echo $this->Html->link($this->Html->image('button-arrow-right.png'), array('controller' => $this->params['controller'], 'action' => 'index_'.$display, 'order_id' => $order_id, 'date_selected' => $nextDate), array('class' => 'left button_arrow button_arrow_right', 'escape' => false)); ?>
<div id="datepicker-calendar-container">
	<?php echo $this->Form->input('day', array('class' => $display.'picker', 'label' => false, 'div' => false)); ?>
</div>