<?php 
$job_info_on = '';
$order_item_on = '';
$change_order_on = '';
$schedule_on = '';
$requirements_on = '';
$purchasing_on = '';
switch ($tab) {
	case ORDER_ORDER_ITEMS :
		$order_item_on = 'current';
		break;
	case ORDER_CHANGE_REQUEST :
		$change_order_on = 'current';
		break;
	case ORDER_PRODUCTION_SCHEDULE :	
		$schedule_on = 'current';
		break;
	case ORDER_PRODUCTION_REQUIREMENT :
		$requirements_on = 'current';
		break;
	case ORDER_PRODUCTION_PURCHASING :
		$purchasing_on = 'current';
		break;
	default:
		// ORDER_JOB_INFO
		$job_info_on = 'current';
}
?>
<ul id="available_actions_list">
	<li class="action <?php echo $job_info_on?>"><?php echo $this->Html->link(__(Configure::read('Nomenclature.Order') . ' Info'), array('controller' => 'orders', 'action' => 'job_info', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $order_item_on?>"><?php echo $this->Html->link(__(Configure::read('Nomenclature.Order') . ' Items'), array('controller' => 'order_line_items', 'action' => 'add', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $schedule_on?>"><?php echo $this->Html->link('Schedule', array('controller' => 'orders', 'action' => 'schedules', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $requirements_on?>"><?php echo $this->Html->link('Requirements', array('controller' => 'order_tasks', 'action' => 'index', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $purchasing_on?>"><?php echo $this->Html->link('Purchasing', array('controller' => 'orders', 'action' => 'purchasing', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $change_order_on?>" id="design"><?php echo $this->Html->link(__(Configure::read('Nomenclature.ChangeOrder')), array('controller' => 'change_order_requests', 'action' => 'add', $order['Order']['id'])); ?></li>
</ul>