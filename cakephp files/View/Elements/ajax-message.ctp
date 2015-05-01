<?php 
if(empty($type)) {
	$type = 'success';
}

if(empty($id)) {
	$id = 'ajax-message_success';
}

if(empty($msg)) {
	$msg = '';
}
?>
<div id="<?php echo $id; ?>" class="ajax_message_<?php echo $type; ?> hide">
	<?php echo $msg; ?>&nbsp;
</div>