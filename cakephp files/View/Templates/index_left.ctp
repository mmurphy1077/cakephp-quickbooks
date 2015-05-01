<?php echo $this->Html->script('creationsite/table-click', false); ?>
<?php 
$paginate = true;
if(!empty($exclude_paginator)) {
	$paginate = false;
}
?>
<div class="grid">
	<div class="col-1of5 dotted-border">
		<?php echo $this->Html->image('border-blocker.jpg', array('id' => 'border-blocker'));?>
		<div id="menu_container" class="widget center">
			<h3 class="left"><?php echo __($this->fetch('pageTitle')); ?></h3>
			<?php echo $this->fetch('buttons'); ?>
		</div>
	</div>
	<div class="col-4of5">
		<div class="widget">
			<?php echo $this->fetch('content'); ?>
		</div>
	</div>
</div>