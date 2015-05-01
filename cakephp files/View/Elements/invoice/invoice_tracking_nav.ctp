<?php 
$selected_edit="";
$selected_format="";
$selected_email="";
if(isset($selected)) {
	switch ($selected) {
		case 'edit':
			$selected_edit="selected";
			break;
		case 'format':
			$selected_format="selected";
			break;
		case 'email':
			$selected_email="selected";
			break;
	}
} ?>
<fieldset>
	<div class="row fieldset-wrapper available_action_container">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php 
			$link_steps2 = 'step2_mobile';
			$link_steps3 = 'step3_mobile';
			if($mode == 'computer') : 
				$link_steps2 = 'step2';
				$link_steps3 = 'step3';	?>
				<div class="left"><b>Available Actions:</b></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $this->element('tabs_order_production', array('tab' => ORDER_PRODUCTION_INVOICE, 'order' => $order)); ?>
			<?php else : ?>
				<b>5 Invoice:</b>&nbsp;&nbsp;Prepare your invoice and submit for payment.
			<?php endif; ?>
		</div>	
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<?php if(isset($selected)) : ?>
				<div class="title-buttons hidden-xs hidden-sm">
					<?php
					echo $this->Html->link(__('Email', true), array('controller' => 'invoices', 'action' => 'email', $invoice_id, $order['Order']['id']), array('class' => 'title-buttons ' . $selected_email));
					echo $this->Html->link(__('Pdf', true), array('controller' => 'invoices', 'action' => 'print_pdf', $invoice_id), array('class' => 'title-buttons '));
					echo $this->Html->link(__('Review/Format', true), array('controller' => 'invoices', 'action' => 'edit', $invoice_id, $order['Order']['id'], $link_steps3), array('class' => 'title-buttons ' . $selected_format));
					echo $this->Html->link(__('Edit', true), array('controller' => 'invoices', 'action' => 'edit', $invoice_id, $order['Order']['id'], $link_steps2), array('class' => 'title-buttons ' . $selected_edit));
					?>
				</div>
				<div class="title-buttons visible-xs-block visible-sm-block">
					<br />
					<?php
					echo $this->Html->link(__('Edit', true), array('controller' => 'invoices', 'action' => 'edit', $invoice_id, $order['Order']['id'], $link_steps2), array('class' => 'title-buttons left ' . $selected_edit));
					echo $this->Html->link(__('Review/Format', true), array('controller' => 'invoices', 'action' => 'edit', $invoice_id, $order['Order']['id'], $link_steps3), array('class' => 'title-buttons left ' . $selected_format));
					echo $this->Html->link(__('Pdf', true), array('controller' => 'invoices', 'action' => 'print_pdf', $invoice_id), array('class' => 'title-buttons left'));
					echo $this->Html->link(__('Email', true), array('controller' => 'invoices', 'action' => 'email', $invoice_id, $order['Order']['id']), array('class' => 'title-buttons left ' . $selected_email));
					?>
				</div>
			<?php else : ?>
				<div class="title-buttons hidden-xs">
					<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
				</div>
				<div class="title-buttons visible-xs-block">
					<br />
					<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
				</div>
			<?php endif; ?>
		</div>					  
	</div>
</fieldset>