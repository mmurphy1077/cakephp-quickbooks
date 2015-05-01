<?php 
$active['customer-snap-shot']['class'] = null;
$active['customer-roster-summary']['class'] = null;
$active['customer-roster-detail']['class'] = null;
$active['customer-stats']['class'] = null;
$active['customer-ranking']['class'] = null;

if(!empty($active_report)) {
	$active[$active_report]['class'] = 'active';
}
?>
<?php 
// The following require finacial permissions.
if($__user['Group']['Application']['_report_metrics'] == 1) : ?> 
<ul>
	<li class="<?php echo $active['customer-snap-shot']['class']; ?>"><?php echo $this->Html->link('Customer Snapshot Report', array('controller' => 'reports', 'action' => 'generate_report', 'customer-snap-shot'), array('id' => 'customer-snap-shot')); ?></li>
</ul>

<h1>Customer Roster</h1>
<ul>
	<li class="report <?php echo $active['customer-roster-summary']['class']; ?>"><?php echo $this->Html->link('Customer Roster Summary', array('controller' => 'reports', 'action' => 'generate_report', 'customer-roster-summary'), array('id' => 'customer-roster-summary')); ?></li>
	<li class="report <?php echo $active['customer-roster-detail']['class']; ?>"><?php echo $this->Html->link('Customer Roster Detail', array('controller' => 'reports', 'action' => 'generate_report', 'customer-roster-detail'), array('id' => 'customer-roster-detail')); ?></li>
</ul>

<h1>Customer Stats</h1>
<ul>
	<li class="report <?php echo $active['customer-stats']['class']; ?>"><?php echo $this->Html->link('Stats', array('controller' => 'reports', 'action' => 'generate_report', 'customer-stats'), array('id' => 'customer-stats')); ?></li>
	<li class="report <?php echo $active['customer-ranking']['class']; ?>"><?php echo $this->Html->link('Ranking', array('controller' => 'reports', 'action' => 'generate_report', 'customer-ranking'), array('id' => 'customer-ranking')); ?></li>
</ul>
<?php endif; ?>