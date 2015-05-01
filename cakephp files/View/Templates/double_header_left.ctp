<?php echo $this->Html->script('creationsite/table-click', false); ?>
<div class="widget center">
	<?php #echo $this->Html->script('creationsite/table-click', false); ?>
	<?php if ($this->fetch('buttons')): ?>
		<div class="title-buttons">
			<?php echo $this->fetch('buttons'); ?>
		</div>
	<?php endif; ?>
	<div class="title-container">
		<h3 class="left"><?php echo __($this->fetch('pageTitle')); ?></h3>
		<div class="page_sub_title left">&nbsp;<?php echo __($this->fetch('pageTitleSub')); ?></div>
		<?php if ($this->fetch('pageTitleAddress')): ?>
			<div class="left clear"><?php echo $this->fetch('pageTitleAddress'); ?></div>
		<?php endif; ?>
	</div>
	<?php if ($this->fetch('metaHeader')): ?>
		<?php echo $this->fetch('metaHeader'); ?>
	<?php endif; ?>
</div>
<div class="modal-container"><?php echo $this->fetch('modal'); ?></div>
<div class="grid clear">
	<div class="col-1of3">
		<div class="widget center">
			<?php if ($this->fetch('buttons1')): ?>
				<div class="title-buttons">
					<?php echo $this->fetch('buttons1'); ?>
				</div>
			<?php endif; ?>
			<?php if ($this->fetch('pageTitle1')): ?>
			<h3 class="left"><?php echo __($this->fetch('pageTitle1')); ?></h3>
			<?php endif; ?>
			<?php echo $this->fetch('leftbar'); ?>
		</div>
	</div>
	<div class="col-2of3">
		<div class="widget center">			
			<?php if ($this->fetch('buttons2')): ?>
				<div class="title-buttons">
					<?php echo $this->fetch('buttons2'); ?>
				</div>
			<?php endif; ?>
			<?php if ($this->fetch('pageTitle2')): ?>
			<h3 class="left"><?php echo __($this->fetch('pageTitle2')); ?></h3>
			<?php endif; ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</div>

<?php if ($this->fetch('slider')): ?>
<div class="slider">
	<div class="widget">
		<div id="slider-header"><?php echo $this->fetch('sliderTitle'); ?></div>
		<div id="slider-content">
			<?php echo $this->fetch('slider'); ?>
		</div>
		<div id="slider-footer"></div>
	</div>
	<div id="slider-close-button" class="slider-close">&times;</div>
</div>
<?php endif; ?>

<?php if ($this->fetch('statusAssigment')): ?>
<?php 	echo $this->fetch('statusAssigment'); ?>
<?php endif; ?>