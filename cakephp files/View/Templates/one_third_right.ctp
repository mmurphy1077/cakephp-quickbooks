<?php if ($this->fetch('metaHeader')): ?>
	<?php echo $this->fetch('metaHeader'); ?>
<?php endif; ?>
<?php echo $this->Html->script('creationsite/table-click', false); ?>
<div class="grid">
	<div class="col-2of3">
		<div id="invoice_details_container" class="widget center">
			<h3 class="left"><?php echo __($this->fetch('pageTitle')); ?></h3>
			<?php echo $this->fetch('content'); ?>
			<?php if ($this->fetch('buttons')): ?>
			<div class="title-buttons">
				<?php echo $this->fetch('buttons'); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-1of3">
		<div id="invoice_info_container" class="widget right">		
			<h3><?php echo Configure::read('Nomenclature.Invoice').' Info'; ?></h3>	
			<?php if ($this->fetch('invoice_info')): ?>
				<?php echo $this->fetch('invoice_info'); ?>
			<?php endif; ?>
		</div>
	</div>
</div>