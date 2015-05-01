<?php 
if(empty($background)) {
	$background = '';
} else {
	$background = '-'.$background;
}

if(empty($id)) {
	$id = '';
}

if(empty($msg)) {
	$msg = 'updating...';
}
?>
<div id="<?php echo $id; ?>" class="ajax_loader_inline">
	<?php echo $this->Html->image('loader' . $background . '.gif'); ?>
	<div id="msg"><?php echo $msg; ?></div>
</div>