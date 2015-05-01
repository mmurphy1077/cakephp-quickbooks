<?php echo $this->Html->script('creationsite/table-click', false); ?>
<div class="grid">
	<div class="col-3of4">
		<div class="grid grid-2col">
			<div>
				<div class="widget center">
					<?php if ($this->fetch('buttonsLeft')): ?>
						<div class="title-buttons">
							<?php echo $this->fetch('buttonsLeft'); ?>
						</div>
					<?php endif; ?>
					<h3 class="left"><?php echo __($this->fetch('pageTitleLeft')); ?></h3>
					<?php echo $this->fetch('leftside'); ?>
				</div>
			</div>
			<div>
				<div class="widget center">
					<?php if ($this->fetch('buttonsRight')): ?>
						<div class="title-buttons">
							<?php echo $this->fetch('buttonsRight'); ?>
						</div>
					<?php endif; ?>
					<h3 class="left"><?php echo __($this->fetch('pageTitleRight')); ?></h3>
					<?php echo $this->fetch('rightside'); ?>
				</div>
			</div>
		</div>
	</div>
	<div class="col-1of4">
		<div class="widget right">
			<?php echo $this->element('nav_context', array('before' => '&rarr; ', 'wrapper' => 'ul')); ?>
		</div>
		<?php if ($this->fetch('pageStats')): ?>
			<div class="widget right">
				<?php echo $this->fetch('pageStats'); ?>
			</div>
		<?php endif; ?>
	</div>
</div>