<?php 
$active['billing-snap-shot']['class'] = null;
$active['billing-customer-summary']['class'] = null;
$active['billing-customer-detail']['class'] = null;
$active['billing-order-summary']['class'] = null;
$active['billing-order-detail']['class'] = null;

if(!empty($active_report)) {
	$active[$active_report]['class'] = 'active';
}
?>
<?php 
// The following require finacial permissions.
if($__user['Group']['Application']['_report_metrics'] == 1) : ?> 
<ul>
	<li class="<?php echo $active['billing-snap-shot']['class']; ?>"><?php echo $this->Html->link('Billing Snapshot Report', array('controller' => 'reports', 'action' => 'generate_report', 'billing-snap-shot'), array('id' => 'billing-snap-shot')); ?></li>
</ul>
<h1>By Customer</h1>
<ul>
	<li class="report <?php echo $active['billing-customer-summary']['class']; ?>"><?php echo $this->Html->link('Customer Billing Report Summary', array('controller' => 'reports', 'action' => 'generate_report', 'billing-customer-summary'), array('id' => 'billing-customer-summary')); ?></li>
	<li class="report <?php echo $active['billing-customer-detail']['class']; ?>"><?php echo $this->Html->link('Customer Billing Report Details', array('controller' => 'reports', 'action' => 'generate_report', 'billing-customer-detail'), array('id' => 'billing-customer-detail')); ?></li>
</ul>

<h1>By <?php echo __(Configure::read('Nomenclature.Order')); ?></h1>
<ul>
	<li class="report <?php echo $active['billing-order-summary']['class']; ?>"><?php echo $this->Html->link(__(Configure::read('Nomenclature.Order')) . ' Billing Report Summary', array('controller' => 'reports', 'action' => 'generate_report', 'billing-order-summary'), array('id' => 'billing-order-summary')); ?></li>
	<li class="report <?php echo $active['billing-order-detail']['class']; ?>"><?php echo $this->Html->link(__(Configure::read('Nomenclature.Order')) . ' Billing Report Details', array('controller' => 'reports', 'action' => 'generate_report', 'billing-order-detail'), array('id' => 'billing-order-detail')); ?></li>
</ul>
<?php endif; ?>