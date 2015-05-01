<div id="order-invoice-stats" class="timelog_header">
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<div class="label inline">Sort Hours By</div>
			<?php echo $this->Form->input('Invoice.sort_by', array('options' => $order_by_options, 'value' => $this->data['Invoice']['display_time_by'], 'label' => false)); ?><br />
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
			<div class="label inline">Display</div>
			<?php echo $this->Form->input('labor_section_type', array('label' => false, 'options' => array('itemized' => 'Itemized', 'summary' => 'Summary'), 'div' => 'input select', 'class' => 'type_block')); ?>
		</div>
	</div>
</div>
<!-- 
<span class="left"><b>Time Log</b></span>
<span class="right"><b><?php echo number_format($order_invoice_stats['new_hours_logged'], 2); ?></b> NEW HRS LOGGED | <b><?php echo number_format($order_invoice_stats['hours_billed'], 2); ?></b> HRS PREVIOUSLY BILLED | <b><?php echo number_format($order_invoice_stats['hours_estimated'], 2); ?></b> HRS TOTAL ESTIMATE</span>
 -->
 <div id="invoice-summary-tables" class="clear left">
	<?php 
	if(!empty($this->data['OrderTime'])) :
		if($this->data['Invoice']['display_time_by'] == 'date') {
			echo $this->element('invoice/invoice_time_index_by_date', array('data' => $this->data['OrderTime'], 'permissions' => $permissions, 'display' => $this->data['Invoice']['labor_section_type'], 'permission_attr' => $permission_attr));
		} else {
			// worker
			echo $this->element('invoice/invoice_time_index_by_worker', array('data' => $this->data['OrderTime'], 'permissions' => $permissions, 'display' => $this->data['Invoice']['labor_section_type'], 'permission_attr' => $permission_attr));
		} 
	endif; ?>
</div>