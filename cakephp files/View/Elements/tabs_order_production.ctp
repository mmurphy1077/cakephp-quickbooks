<?php 
$invoice_on = '';
$material_on = '';
$purchases_on = '';
$labor_on = '';
switch ($tab) {
	case ORDER_PRODUCTION_MATERIAL :
		$material_on = 'current';
		break;
	case ORDER_PRODUCTION_INVOICE :
		$invoice_on = 'current';
		break;
	case ORDER_PRODUCTION_PURCHASE :
		$purchases_on = 'current';
		break;
	default:
		// ORDER_PRODUCTION_LABOR
		$labor_on = 'current';
}
?>
<ul id="available_actions_list">
	<li class="action <?php echo $labor_on?>"><?php echo $this->Html->link('Labor Hours', array('controller' => 'order_times', 'action' => 'index', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $material_on?>"><?php echo $this->Html->link('Materials', array('controller' => 'order_materials', 'action' => 'index', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $purchases_on?>"><?php echo $this->Html->link('Purchases', array('controller' => 'order_materials', 'action' => 'index_purchases', $order['Order']['id'])); ?></li>
	<li class="divider">|</li>
	<li class="action <?php echo $invoice_on?>"><?php echo $this->Html->link('Invoices', array('controller' => 'invoices', 'action' => 'index_order', $order['Order']['id'])); ?></li>
</ul>