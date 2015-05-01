<?php 
if(!isset($selected_filter_val)) {
	$selected_filter_val = null;
}
if(!empty($messages)) {
	foreach($messages as $data) {
		echo $this->element('communication/comment_thread_element', array('data' => $data, 'user' => $__user, 'selected_filter_val' => $selected_filter_val));
	}
} else {
	echo 'No Comments';
} ?>
<div id="prev-message-pad" class="left">&nbsp;</div>