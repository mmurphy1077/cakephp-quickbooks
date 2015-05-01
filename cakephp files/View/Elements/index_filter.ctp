<?php 
switch($model) {
	case 'Contact' :
		$statuses = $activeStatuses;
		break;
	case 'Order' :
		$statuses = $statuses_order;
		break;
	case 'Quote' :
		$statuses = $statuses_quote;
		break;
	case 'Invoice' :
		$statuses = $statuses_invoice;
		break;
}

echo $this->Form->create('FilterIndex', array('url' => array('controller' => $this->params['controller'], 'action' => 'index_filter'), 'id' => 'FilterIndexForm')); ?>
	<div id="index-filter">
		<?php if($model == 'Invoice') : ?>
		<div id="filter-block-container-invoice" class="filter-block-container">
			<h1><b>Orders</b></h1>
			<div class="assigned-to-filter-container clear">
				<?php 
				$checked = false;
				if(isset($filter_selected_ready_to_invoice) && $filter_selected_ready_to_invoice == 'yes') {
					$checked = true;
				} 
				echo $this->Form->checkbox('Orders.ready_to_invoice', array('value' => 1, 'checked' => $checked, 'label' => false, 'id' => 'filter-invoice-ready-to-invoice', 'hiddenField' => false)); ?>
				<div class="inline">Orders Ready for Invoice</div>
			</div>
			<br />
			<b>OR...</b>
		</div>
		<?php endif; ?>
		
		
		<?php if(!empty($statuses)) : ?>
		<div id="filter-block-container-status" class="filter-block-container col-xs-6 col-sm-4 col-md-12 col-lg-12">
			<h1><b>Status</b></h1>
			<?php foreach($statuses as $key=>$data) : ?>
			<div class="status-filter-container clear">
				<?php 
				$checked = false;
				if(!empty($selected_status) && array_key_exists($key, $selected_status)) {
					$checked = true;
				} 
				echo $this->Form->checkbox('Status.'.$key, array('value' => $key, 'checked' => $checked, 'class' => 'filter-status', 'label' => $data, 'hiddenField' => false)); ?>
				<div class="inline"><?php echo $data; ?></div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	
		
		<?php if(!empty($__assigned_tos)) : ?>
		<div id="filter-block-container-assign" class="filter-block-container col-xs-6 col-sm-4 col-md-12 col-lg-12">
			<h1><span class="light small">and... </span><b>Assigned To</b></h1>
			<?php foreach($__assigned_tos as $key=>$data) : ?>
			<div class="assigned-to-filter-container clear">
				<?php 
				$checked = false;
				if(!empty($selected_assigned_to) && array_key_exists($key, $selected_assigned_to)) {
					$checked = true;
				} 
				echo $this->Form->checkbox('AssignedTo.'.$key, array('value' => $key, 'checked' => $checked, 'label' => $data, 'hiddenField' => false)); ?>
				<div class="inline"><?php echo $data; ?></div>
			</div>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
		
		<div id="filter-block-container-alerts" class="filter-block-container col-xs-12 col-sm-4 col-md-12 col-lg-12">
			<h1><b>Alerts</b></h1>
			<div class="assigned-to-filter-container clear">
				<?php 
				$checked = false;
				if(isset($display_alerts) && $display_alerts == 1) {
					$checked = true;
				} 
				echo $this->Form->checkbox('Alerts.display', array('value' => 1, 'checked' => $checked, 'label' => false, 'hiddenField' => false)); ?>
				<div class="inline">Display Alerts</div>
			</div>
		</div>
	</div>
	<div id="" class="clear">
		<?php echo $this->Form->submit(__('Go', true), array('id' => 'submit-filter')); ?>
	</div>
<?php echo $this->Form->end(); ?>