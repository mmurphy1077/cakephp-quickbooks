<?php 
$isOwnerClass = 'isNotOwnerMessage';
$isOwner = false;
$from = '';
$content = '';
$created = '';
$td = '';
$id = '';
$type = '';
$attachments = array();
//$attachments_exist = 'hide';
$attachments_exist = '';
if(!empty($data)) {
	$id = $data['Message']['id'];;
	$from = $data['Message']['from'];
	$content = $data['Message']['content'];
	$created = date('F d, Y g:i a', strtotime($data['Message']['created']));
	$type = $data['Message']['type'];
	$td = $data['Message']['time_deviation'];
	$attachments = $data['Attachment'];
	switch($type) {
		case 'call_inbound' :
			$filter_type = 'calls';
			$display_type = 'Inbound Call';
		case 'call_outbound' :
			$filter_type = 'calls';
			$display_type = 'Outbound Call';
			break;	
		default :
			$filter_type = $type;
			$display_type = ucfirst($type);
	}
	if(!empty($data['Attachment'])) {
		$attachments_exist = '';
	}
	if(isset($user) && ($user['User']['id'] == $data['Message']['sender_id']) && ($data['Message']['sender_model'] == 'User')) {
		$isOwnerClass = 'isOwnerMessage';
		$isOwner = true;
	}
} else {
	$isOwnerClass = 'isOwnerMessage';
}
if(!isset($selected_filter_val)) {
	$selected_filter_val = null;
}
$display = '';
if(($selected_filter_val != 'all') && (!empty($selected_filter_val)) && ($selected_filter_val != $type)) {
	$display = 'hidden';
}
?>
<div class="individual-message-container <?php echo $filter_type; ?> <?php echo $display; ?>">
	<div id="comment-<?php echo $id; ?>" class="prev-message <?php echo $isOwnerClass; ?>">
		<div class="message-content-container">
			<div id="posted" class="small light">Posted: <?php echo $created; ?> <?php echo $td; ?></div>
			<div id="post-by" class="small light clear">By: <?php echo $from; ?></div>
			<?php if(!empty($type)) : ?>
			<div id="post-type" class="small light clear">Type: <?php echo $display_type; ?></div>
			<?php endif; ?>
			<br>
			<div id="content">
			<?php echo nl2br($content); ?>
			</div>
		</div>
		<ul id="attachments-inline" class="attachments-inline <?php echo $attachments_exist; ?>">
		<?php if(!empty($attachments)) : ?>
			<?php foreach($attachments as $attachment) : ?>
		  		<li>
					<?php 
					$ext = pathinfo($attachment['Document']['name'], PATHINFO_EXTENSION);
					$title = $attachment['Document']['title'] . '.' . $ext;
					echo $this->Html->link($this->Web->excerpt_file_name($title, 15), array('controller' => 'documents', 'action' => 'download', $attachment['Document']['id']), array('class' => 'attachment-inline')); ?>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		</ul>
		<?php 
		if($isOwner) {
			echo $this->Html->link('delete', '#', array('id' => 'delete-comment-' . $id, 'class' => 'delete-comment-link')); 
		} ?>
		<?php echo $this->element('communication/message_portfolio_pic', array('user' => $data['Sender'])); ?>
	</div>
</div>