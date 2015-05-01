<div class="widget">
	<?php 
	$title = $this->fetch('pageTitle');
	if(($__browser_view_mode['browser_view_mode'] == 'standard') && (!empty($title))) : ?>
		<div class="title-container">
			<h3 class="left"><?php echo __($title); ?>&nbsp;&nbsp;</h3>
			<div class="page_sub_title left">&nbsp;<?php echo __($this->fetch('pageTitleSub')); ?></div>
			<?php if ($this->fetch('pageTitleAddress')): ?>
				<div class="left clear"><?php echo $this->fetch('pageTitleAddress'); ?></div>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<?php if ($this->fetch('metaHeader')): ?>
		<?php echo $this->fetch('metaHeader'); ?>
	<?php endif; ?>
</div>
<?php 
// If in field mode... drop a line.
$title = $this->fetch('pageTitle');
if($__browser_view_mode['browser_view_mode'] == 'field' && !empty($title)) : ?>
<div id="field-view-pageTitle" class="widget container-fluid">
	<div class="grid">
		<div class="col-1of1 border-bottom">
			<h3 class="left"><?php echo __($title); ?>&nbsp;&nbsp;</h3>
			<div class="page_sub_title left">&nbsp;<?php echo __($this->fetch('pageTitleSub')); ?></div>
			<?php if ($this->fetch('pageTitleAddress')): ?>
				<div class="left clear"><?php echo $this->fetch('pageTitleAddress'); ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>
<div class="widget center">
	<?php if ($this->fetch('buttons')): ?>
		<div class="title-buttons">
			<?php echo $this->fetch('buttons'); ?>
		</div>
	<?php endif; ?>
	<?php echo $this->fetch('content'); ?>
</div>

<?php if($__browser_view_mode['view_device'] == 'computer') : ?>
<?php 	if ($this->fetch('statusAssigment')): ?>
<?php 		echo $this->fetch('statusAssigment'); ?>
<?php 	endif; ?>
<?php endif; ?>

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