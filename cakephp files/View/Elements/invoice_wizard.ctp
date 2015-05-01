<?php
$steps = array(
	1 => array(
		'class' => 'normal',
		'params' => array(),
	),
	2 => array(
		'class' => 'normal',
		'params' => array(),
	),
	3 => array(
		'class' => 'normal',
		'params' => array(),
	),
	4 => array(
		'class' => 'normal',
		'params' => array(),
	),
);
switch ($step) {
	case 'step1':
		$steps[1]['class'] = 'normal';
		$steps[2]['class'] = 'active';
		$steps[3]['class'] = 'active';
		$steps[4]['class'] = 'active';
		break;
	case 'step2':
		$steps[1]['class'] = 'active';
		$steps[2]['class'] = 'normal';
		$steps[3]['class'] = 'active';
		$steps[4]['class'] = 'active';
		break;
	case 'step3':
		$steps[1]['class'] = 'active';
		$steps[2]['class'] = 'active';
		$steps[3]['class'] = 'normal';
		$steps[4]['class'] = 'active';
		break;
	case 'step4':
		$steps[1]['class'] = 'active';
		$steps[2]['class'] = 'active';
		$steps[3]['class'] = 'active';
		$steps[4]['class'] = 'normal';
		break;
}
?>
<div class="quote wizard">
	<div class="grid">
		<div class="col-1of4 step <?php echo $steps[1]['class']; ?> first">
			<?php
			$link = array('controller' => 'invoices', 'action' => 'edit', $invoice_id, $order_id, 'step1');
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Customer Details'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="col-1of4 step <?php echo $steps[2]['class']; ?>">
			<?php
			$link = array('controller' => 'invoices', 'action' => 'edit', $invoice_id, $order_id, 'step2');
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Invoice Labor'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="col-1of4 step <?php echo $steps[3]['class']; ?>">
			<?php
			$link = array('controller' => 'invoices', 'action' => 'edit', $invoice_id, $order_id, 'step3');
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Invoice Materials'), $link, array('class' => 'text', 'escape' => false));
			?>
		</div>
		<div class="col-1of4 step <?php echo $steps[4]['class']; ?> last">
			<?php
			$link = array('controller' => 'invoices', 'action' => 'view', $invoice_id);
			echo $this->Html->link(null, $link, array('class' => 'dot'));
			echo $this->Html->link(__('Review'), $link, array('class' => 'text'));
			?>
		</div>
	</div>
</div>