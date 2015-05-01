<?php 
$display_css = 'hide';
if($display == $display_mode) {
	$display_css = '';
}
?>
<tr id="<?php echo $key; ?>-summary-row" class="<?php echo $display_mode; ?> summary-row worker-daily-total <?php echo $display_css; ?>">
	<td colspan="5" class="">
		Total for <?php echo $worker; ?>&nbsp;&nbsp;&nbsp;<b><?php echo number_format($data['time'], 2); ?>&nbsp;hours</b>
	</td>
	<td class="align-right">
		<b>$ <?php echo number_format($data['cost'], 2); ?></b>
	</td>
	<td id="data-bank-cell" class="align-right">
		<?php if(empty($permission_attr)) : ?>
		<?php echo $this->Html->link('include', array('#'), array('id' => 'summary-'.$count, 'class' => 'invoice-include')); ?>
		<div id="data-bank-summary-<?php echo $count?>" class="hide data-bank">
			<?php echo $this->Form->hidden('description', array('id' => 'data-bank-item-description', 'class' => 'data-bank-item', 'value' => 'Total for ' . $worker)); ?>
			<?php echo $this->Form->hidden('hours', array('id' => 'data-bank-item-hours', 'class' => 'data-bank-item', 'value' => number_format($data['time'], 2, '.', ''))); ?>
			<?php echo $this->Form->hidden('price', array('id' => 'data-bank-item-price', 'class' => 'data-bank-item', 'value' => number_format($data['cost'], 2, '.', ''))); ?>
			<?php echo $this->Form->hidden('rate', array('id' => 'data-bank-item-rate', 'class' => 'data-bank-item', 'value' => number_format($data['cost']/$data['time'], 2, '.', ''))); ?>
			<?php echo $this->Form->hidden('date', array('id' => 'data-bank-item-date', 'class' => 'data-bank-item', 'value' => $data['date'])); ?>
			<?php echo $this->Form->hidden('date_short', array('id' => 'data-bank-item-date-short', 'class' => 'data-bank-item', 'value' => $data['date_short'])); ?>
			<?php echo $this->Form->hidden('name', array('id' => 'data-bank-item-name', 'class' => 'data-bank-item', 'value' => $data['name'])); ?>
			<?php echo $this->Form->hidden('type', array('id' => 'data-bank-item-type', 'class' => 'data-bank-item', 'value' => $data['type'])); ?>
			<?php echo $this->Form->hidden('display_mode', array('id' => 'data-bank-item-display_mode', 'class' => 'data-bank-item', 'value' => $display_mode)); ?>
		</div>
		<?php endif; ?>
	</td>
</tr>