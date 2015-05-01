<div class="widget">
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
<div class="grid clear">
	<div class="col-1of2">
		<div>
			<div class="widget center">
				<?php if ($this->fetch('buttonsLeft')): ?>
					<div class="title-buttons">
						<?php echo $this->fetch('buttonsLeft'); ?>
					</div>
				<?php endif; ?>
				<?php echo $this->fetch('leftside'); ?>
			</div>
		</div>
	</div>
	<div class="col-1of2">
		<div>
			<?php if ($this->fetch('buttonsRight')): ?>
				<div class="title-buttons">
					<?php echo $this->fetch('buttonsRight'); ?>
				</div>
			<?php endif; ?>
			<div class="widget center">
				
				<?php if ($this->fetch('pageTitleRight')): ?>
					<h3 class="left"><?php echo __($this->fetch('pageTitleRight')); ?></h3>
				<?php endif; ?>
				<?php echo $this->fetch('rightside'); ?>
			</div>
		</div>
	</div>
</div>

<?php if ($this->fetch('statusAssigment')): ?>
<?php 	echo $this->fetch('statusAssigment'); ?>
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