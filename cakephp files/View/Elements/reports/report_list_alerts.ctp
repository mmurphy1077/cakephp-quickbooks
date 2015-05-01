<?php 
$active['alert-contact-call-back']['class'] = null;
$active['alert-quote-call-back']['class'] = null;
$active['alert-order-call-back']['class'] = null;

if(!empty($active_report)) {
	$active[$active_report]['class'] = 'active';
}
?>
<?php 
// The following require finacial permissions.
#if($__user['Group']['Application']['_report_metrics'] == 1) : ?> 

<h1>For <?php echo __(Configure::read('Nomenclature.Contact')); ?></h1>
<ul>
	<li class="report <?php echo $active['alert-contact-call-back']['class']; ?>"><?php echo $this->Html->link('Callback required', array('controller' => 'reports', 'action' => 'generate_report', 'alert-contact-call-back'), array('id' => 'alert-contact-call-back')); ?></li>
</ul>

<h1>For <?php echo __(Configure::read('Nomenclature.Quote')); ?></h1>
<ul>
	<li class="report <?php echo $active['alert-quote-call-back']['class']; ?>"><?php echo $this->Html->link('Callback required', array('controller' => 'reports', 'action' => 'generate_report', 'alert-quote-call-back'), array('id' => 'alert-quote-call-back')); ?></li>
</ul>

<h1>For <?php echo __(Configure::read('Nomenclature.Order')); ?></h1>
<ul>
	<li class="report <?php echo $active['alert-order-call-back']['class']; ?>"><?php echo $this->Html->link('Callback required', array('controller' => 'reports', 'action' => 'generate_report', 'alert-order-call-back'), array('id' => 'alert-order-call-back')); ?></li>
</ul>
<?php #endif; ?>