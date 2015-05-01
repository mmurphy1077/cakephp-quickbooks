<?php 
$isOwnerClass = 'isNotOwnerMessage';
$from = '';
$content = '';
$created = '';
$td = '';
$id = '';
$attachments = array();
//$attachments_exist = 'hide';
$attachments_exist = '';
if(!empty($data)) {
	$id = $data['Message']['id'];;
	$from = $data['Message']['from'];
	$content = $data['Message']['content'];
	$created = date('F d, Y h:i a', strtotime($data['Message']['created']));
	$td = $data['Message']['time_deviation'];
	$attachments = $data['Attachment'];
	if(!empty($data['Attachment'])) {
		$attachments_exist = '';
	}
	if(isset($user) && ($user['User']['id'] == $data['Message']['sender_id']) && ($data['Message']['sender_model'] == 'User')) {
		$isOwnerClass = 'isOwnerMessage';
	}
} else {
	$isOwnerClass = 'isOwnerMessage';
}
?>
<div>
	<div class="prev-message <?php echo $isOwnerClass; ?>">
		<div class="message-content-container">
			<div id="posted" class="small light">Posted: <?php echo $created; ?> <?php echo $td; ?></div>
			<div id="post-by" class="small light clear">By: <?php echo $from; ?></div>
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
		<?php echo $this->Html->link('view', '#', array('id' => 'view-message-' . $id, 'class' => 'view-message-link')); ?>
		<?php echo $this->element('communication/message_portfolio_pic', array('user' => $data['Sender'])); ?>
	</div>
</div>
<?php 
if(!empty($data) && !empty($data['Parent'])) {
	echo $this->element('communication/message_thread_element', array('data' => $data['Parent'], 'user' => $user));
} ?>