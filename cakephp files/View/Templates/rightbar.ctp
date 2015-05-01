<div class="widget">
	<h3 class="left"><?php echo __($this->fetch('pageTitle')); ?></h3>
	<?php if ($this->fetch('metaHeader')): ?>
		<?php echo $this->fetch('metaHeader'); ?>
	<?php endif; ?>
</div>
<div class="grid clear">
	<div class="col-3of4">
		<div class="widget center">
			<?php if ($this->fetch('buttons')): ?>
				<div class="title-buttons">
					<?php echo $this->fetch('buttons'); ?>
				</div>
			<?php endif; ?>
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
	<div class="col-1of4">
		<div class="widget right">
			<?php echo $this->element('nav_context', array('before' => '&rarr; ', 'wrapper' => 'ul')); ?>
		</div>
		<?php if ($this->fetch('rightbar')): ?>
			<?php echo $this->fetch('rightbar'); ?>
		<?php endif; ?>
	</div>
</div>
