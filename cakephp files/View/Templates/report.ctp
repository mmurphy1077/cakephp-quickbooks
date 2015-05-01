<?php 
echo $this->Html->script('creationsite/table-click', false); 
echo $this->element('js'.DS.'jquery', array('ui' => 'datepicker'));
echo $this->element('js'.DS.'quote.datepicker.init');
echo $this->element('js'.DS.'reports');
?>
<div class="widget center">
	<h3 class="left"><?php echo __($this->fetch('pageTitle')); ?></h3><div class="page_sub_title left">&nbsp;<?php echo __($this->fetch('pageTitleSub')); ?></div>
	<?php if ($this->fetch('metaHeader')): ?>
		<?php echo $this->fetch('metaHeader'); ?>
	<?php endif; ?>
	<?php if ($this->fetch('report_filter')): ?>
		<div id="report_filter_container">
		<?php echo $this->fetch('report_filter'); ?>
		</div>
	<?php endif; ?>
	<div class="grid">
		<div class="col-1of5">
			<div id="reports_container" class="center">
				<?php if ($this->fetch('report_list')): ?>
					<?php echo $this->fetch('report_list'); ?>
				<?php endif; ?>
			</div>
		</div>
		<div class="col-4of5">
			<div id="report_data_container" class="widget right">			
				<?php if ($this->fetch('report_data')): ?>
					<?php echo $this->fetch('report_data'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
