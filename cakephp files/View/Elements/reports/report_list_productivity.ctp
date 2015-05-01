<?php 
$active['customer-snap-shot']['class'] = null;
$active['customer-productivity-summary']['class'] = null;
$active['customer-productivity-detail']['class'] = null;
$active['order-productivity-summary']['class'] = null;
$active['order-productivity-detail']['class'] = null;
$active['employee-productivity-summary']['class'] = null;
$active['employee-productivity-detail']['class'] = null;

if(!empty($active_report)) {
	$active[$active_report]['class'] = 'active';
}
?>
<?php 
// The following require finacial permissions.
if($__user['Group']['Application']['_report_metrics'] == 1) : ?> 
<!-- 
<ul>
	<li class="<?php echo $active['customer-snap-shot']['class']; ?>"><?php echo $this->Html->link('Customer Snapshot Report', array('controller' => 'reports', 'action' => 'generate_report', 'customer-snap-shot'), array('id' => 'customer-snap-shot')); ?></li>
</ul>
 -->
<h1>Customer Productivity (Profit/Loss)</h1>
<ul>
	<li class="report <?php echo $active['customer-productivity-summary']['class']; ?>"><?php echo $this->Html->link('Customer Productivity Summary', array('controller' => 'reports', 'action' => 'generate_report', 'customer-productivity-summary'), array('id' => 'customer-productivity-summary')); ?></li>
	<li class="report <?php echo $active['customer-productivity-detail']['class']; ?>"><?php echo $this->Html->link('Customer Productivity Detail', array('controller' => 'reports', 'action' => 'generate_report', 'customer-productivity-detail'), array('id' => 'customer-productivity-detail')); ?></li>
</ul>

<h1><?php echo __(Configure::read('Nomenclature.Order')); ?> Productivity (Profit/Loss)</h1>
<ul>
	<li class="report <?php echo $active['order-productivity-summary']['class']; ?>"><?php echo $this->Html->link(__(Configure::read('Nomenclature.Order')) . ' Productivity Summary', array('controller' => 'reports', 'action' => 'generate_report', 'order-productivity-summary'), array('id' => 'order-productivity-summary')); ?></li>
	<li class="report <?php echo $active['order-productivity-detail']['class']; ?>"><?php echo $this->Html->link(__(Configure::read('Nomenclature.Order')) . ' Productivity Detail', array('controller' => 'reports', 'action' => 'generate_report', 'order-productivity-detail'), array('id' => 'order-productivity-detail')); ?></li>
</ul>

<h1>Employee Productivity</h1>
<ul>
	<li class="report <?php echo $active['employee-productivity-summary']['class']; ?>"><?php echo $this->Html->link('Employee Productivity Summary', array('controller' => 'reports', 'action' => 'generate_report', 'employee-productivity-summary'), array('id' => 'employee-productivity-summary')); ?></li>
	<li class="report <?php echo $active['employee-productivity-detail']['class']; ?>"><?php echo $this->Html->link('Employee Productivity Detail', array('controller' => 'reports', 'action' => 'generate_report', 'employee-productivity-detail'), array('id' => 'employee-productivity-detail')); ?></li>
</ul>
<?php endif; ?>