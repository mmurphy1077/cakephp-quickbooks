<?php 
$active['revenue-snap-shot']['class'] = null;
$active['revenue-summary']['class'] = null;
$active['revenue-detail']['class'] = null;

if(!empty($active_report)) {
	$active[$active_report]['class'] = 'active';
}
?>
<?php 
// The following require finacial permissions.
if($__user['Group']['Application']['_report_metrics'] == 1) : ?> 
<ul>
	<li class="<?php echo $active['revenue-snap-shot']['class']; ?>"><?php echo $this->Html->link('Revenue Snapshot Report', array('controller' => 'reports', 'action' => 'generate_report', 'revenue-snap-shot'), array('id' => 'revenue-snap-shot')); ?></li>
</ul>

<h1>Revenue</h1>
<ul>
	<li class="report <?php echo $active['revenue-summary']['class']; ?>"><?php echo $this->Html->link('Revenue Summary', array('controller' => 'reports', 'action' => 'generate_report', 'revenue-summary'), array('id' => 'revenue-summary')); ?></li>
	<li class="report <?php echo $active['revenue-detail']['class']; ?>"><?php echo $this->Html->link('Revenue Detail', array('controller' => 'reports', 'action' => 'generate_report', 'revenue-detail'), array('id' => 'revenue-detail')); ?></li>
</ul>
<?php endif; ?>