<?php 
$active['sales-snap-shot']['class'] = null;
$active['leads-summary']['class'] = null;
$active['leads-detail']['class'] = null;
$active['leads-to-quote']['class'] = null;
$active['leads-to-order']['class'] = null;
$active['quotes-summary']['class'] = null;
$active['quotes-detail']['class'] = null;
$active['quotes-to-sold']['class'] = null;
$active['quotes-jobs-customers']['class'] = null;
$active['revenue-summary']['class'] = null;
$active['revenue-details']['class'] = null;
$active['revenue-customer']['class'] = null;

if(!empty($active_report)) {
	$active[$active_report]['class'] = 'active';
}
?>
<ul>
	<li class="<?php echo $active['sales-snap-shot']['class']; ?>"><?php echo $this->Html->link('Sales Snapshot Report', array('controller' => 'reports', 'action' => 'generate_report', 'sales-snap-shot'), array('id' => 'sales-snap-shot')); ?></li>
</ul>
<h1>Leads</h1>
<ul>
	<li class="report <?php echo $active['leads-summary']['class']; ?>"><?php echo $this->Html->link('Leads Summary', array('controller' => 'reports', 'action' => 'generate_report', 'leads-summary'), array('id' => 'leads-summary')); ?></li>
	<li class="report <?php echo $active['leads-detail']['class']; ?>"><?php echo $this->Html->link('Leads Detail', array('controller' => 'reports', 'action' => 'generate_report', 'leads-detail'), array('id' => 'leads-detail')); ?></li>
	<!-- 
	<li class="report <?php echo $active['leads-to-quote']['class']; ?>"><?php echo $this->Html->link('Leads-to-Quotes', array('controller' => 'reports', 'action' => 'generate_report', 'leads-to-quote'), array('id' => 'leads-to-quote')); ?></li>
	<li class="report <?php echo $active['leads-to-order']['class']; ?>"><?php echo $this->Html->link('Leads-to-Orders', array('controller' => 'reports', 'action' => 'generate_report', 'leads-to-order'), array('id' => 'leads-to-order')); ?></li>
	 -->
</ul>
<h1>Quotes</h1>
<ul>
	<li class="report <?php echo $active['quotes-summary']['class']; ?>"><?php echo $this->Html->link('Quotes Summary', array('controller' => 'reports', 'action' => 'generate_report', 'quotes-summary'), array('id' => 'quotes-summary')); ?></li>
	<li class="report <?php echo $active['quotes-detail']['class']; ?>"><?php echo $this->Html->link('Quotes Detail', array('controller' => 'reports', 'action' => 'generate_report', 'quotes-detail'), array('id' => 'quotes-detail')); ?></li>
	<!-- 
	<li class="report <?php echo $active['quotes-to-sold']['class']; ?>"><?php echo $this->Html->link('Quote-to-Sold Ratio', array('controller' => 'reports', 'action' => 'generate_report', 'quotes-to-sold'), array('id' => 'quotes-to-sold')); ?></li>
	<li class="report <?php echo $active['quotes-jobs-customers']['class']; ?>"><?php echo $this->Html->link('Quotes-Jobs-Customers', array('controller' => 'reports', 'action' => 'generate_report', 'quotes-jobs-customers'), array('id' => 'quotes-jobs-customers')); ?></li>
	-->
</ul>
<?php 
// The following require finacial permissions.
if(!empty($__user['Group']['Reports_Financial']['_access'])) :
?>
<!-- 
<h1>Revenues</h1>
<ul>
	<li class="report <?php echo $active['revenue-summary']['class']; ?>"><?php echo $this->Html->link('Revenue Summary', array('controller' => 'reports', 'action' => 'generate_report', 'revenue-summary'), array('id' => 'revenue-summary')); ?></li>
	<li class="report <?php echo $active['revenue-details']['class']; ?>"><?php echo $this->Html->link('Revenue Detail', array('controller' => 'reports', 'action' => 'generate_report', 'revenue-details'), array('id' => 'revenue-details')); ?></li>
	<li class="report <?php echo $active['revenue-customer']['class']; ?>"><?php echo $this->Html->link('Revenue by Customer', array('controller' => 'reports', 'action' => 'generate_report', 'revenue-customer'), array('id' => 'revenue-customer')); ?></li>
</ul>
 -->
<?php endif; ?>