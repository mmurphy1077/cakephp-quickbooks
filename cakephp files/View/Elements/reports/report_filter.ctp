<?php echo $this->Form->create('Report', array('class' => 'standard', 'id' => 'form-report', 'novalidate' => true, 'url' => array('controller' => 'reports', 'action' => 'generate_report'))); ?>
<div class="grid">
	<div class="col-1of4">
	<?php echo $this->Form->input('quick_dates', array('options' => $report_quick_date, 'label' => 'Quick Dates')); ?>
	</div>
	<div class="col-1of4">
	<div class="label">Date Range</div>
	<?php echo $this->Form->input('date_start', array('id' => 'datepicker_from', 'type' => 'text', 'label' => false, 'class' => 'static short inline', 'div' => false, 'after' => ' - to - ')); ?>
	<?php echo $this->Form->input('date_end', array('id' => 'datepicker_to', 'type' => 'text', 'label' => false, 'class' => 'static short inline', 'div' => false)); ?>
	</div>
	<div class="col-1of4">
	<?php echo $this->Form->input('display_by', array('options' => $display_by_options, 'label' => 'Display By')); ?>
	</div>
	<div class="col-1of4">
	<?php echo $this->Form->input('show_only', array('options' => '', 'label' => 'Show Only')); ?>
	</div>
</div>
<?php echo $this->Form->hidden('current_report'); ?>
<?php echo $this->Form->end(); ?>