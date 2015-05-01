<div class="grid">
	<div class="col-1of1">
		<div class="widget header">
			<?php if ($this->fetch('buttons')): ?>
				<div class="title-buttons">
					<?php echo $this->fetch('buttons'); ?>
				</div>
			<?php endif; ?>
			<h3 class="left"><?php echo __($this->fetch('pageTitle')); ?></h3>	
		</div>
	</div>
</div>
<div class="grid">
	<div class="col-1of4">
		<?php echo $this->fetch('leftbar'); ?>
	</div>
	<div class="col-3of4">
		<div class="grid">
			<div class="col-2of3">
				<?php echo $this->fetch('content'); ?>
			</div>
			<div class="col-1of3">
				<div class="widget right">
					<?php echo $this->element('nav_context', array('before' => '&rarr; ', 'wrapper' => 'ul')); ?>
				</div>
				<?php echo $this->fetch('rightbar'); ?>
			</div>
		</div>
	</div>
</div>