<?php echo $this->Html->script('creationsite/table-click', false); ?>
<div class="widget container-fluid">
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
<div class="grid">
	<div class="col-3of5">
		<div class="widget center">
			<?php echo $this->fetch('content'); ?>
			<?php if ($this->fetch('modal')): ?>
			<?php 	echo $this->fetch('modal'); ?>
			<?php endif; ?>
		</div>
	</div>
	<div class="col-2of5">
		<div class="widget center">
			<?php echo $this->element('nav_context', array('before' => '&rarr; ', 'wrapper' => 'ul')); ?>
			<?php 
			if ($this->fetch('content_form')) {
				echo $this->fetch('content_form'); 
			} ?>
		</div>
	</div>
</div>