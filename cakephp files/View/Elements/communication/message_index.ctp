<?php $this->Html->script('creationsite/communication', array('inline' => false)); ?>
<?php
if(!isset($displayRecipient)) {
	$displayRecipient = false;
}
if(!isset($displaySender)) {
	$displaySender = false;
}
if(!isset($style)) {
	// style will either be 'plugin' or 'inline'
	$style = 'plugin';
}
switch($mode) {
	case 'inbox' :
		$displayRecipient = false;
		$displaySender = true;
		$enableDelete = true;
		$style = 'plugin';
		break;
	case 'sent' :
		$displayRecipient = true;
		$displaySender = false;
		$enableDelete = true;
		$style = 'plugin';
		break;
	case 'message-all' :
		$displayRecipient = true;
		$displaySender = true;
		$enableDelete = false;
		$style = 'inline';
		break;
}
	
$class_new = '';
$class_sent = '';
$class_in = '';
switch($mode) {
	case 'send' :
		$class_new = 'active';
		break;
	case 'sent' :
		$class_sent = 'active';
		break;
	case 'inbox' :
		$class_in = 'active';
	default :

} 
?>
<?php if($style == 'plugin') : ?>
<div id="message-view-control" class="right title-buttons">
	<?php echo $this->Html->link(__('New Message', true), array('controller' => 'users', 'action' => 'dashboard', 'messages', 'send'), array('class' => 'trigger-page-loader ' . $class_new)); ?>
	<?php echo $this->Html->link(__('Sent Items', true), array('controller' => 'users', 'action' => 'dashboard', 'messages', 'sent'), array('class' => 'trigger-page-loader ' . $class_sent)); ?>
	<?php echo $this->Html->link(__('Inbox', true), array('controller' => 'users', 'action' => 'dashboard', 'messages', 'inbox'), array('class' => 'trigger-page-loader ' . $class_in)); ?>
</div>
<?php endif; ?>
<div class="clear register">
	<?php 
	switch($mode) : 
		case 'inbox' : 
		case 'sent' : 
		case 'message-all' : ?>
		<?php if (!empty($messages)): ?>
			<?php echo $this->element('paginator', array('class' => 'paginator top')); ?>
			<table class="standard hover">
				<tr>
					<?php if($displaySender) : ?>
					<th><?php echo $this->Paginator->sort('Sender.name_last', 'From'); ?></th>
					<?php endif; ?>
					<?php if($displayRecipient) : ?>
					<th><?php echo $this->Paginator->sort('Recipient.name_last', 'To'); ?></th>
					<?php endif; ?>
					<th><?php echo __('Subject'); ?></th>
					<th><?php echo $this->Paginator->sort('created', 'Date'); ?></th>
					<th>&nbsp;</th>
				</tr>
				<?php $i = 1; ?>
				<?php foreach ($messages as $message): ?>
					<?php
					if ($i % 2 == 0) {
						$class = ' alt';
					} else {
						$class = null;
					}
					$i++;
					#$link = array('action' => 'dashboard', 'view-message', $message['Message']['id']);
					#$jsLink = ' onclick="document.location.href=\''.$this->Html->url($link).'\';"';
					?>
					<tr id="message-row-<?php echo $message['Message']['id']; ?>" class="clickable<?php echo $class; ?>">
						<?php if($displaySender) : ?>
						<td><?php echo $this->Web->excerpt($message['Message']['from'], 30, array('wordBreak' => false)); ?></td>
						<?php endif; ?>
						<?php if($displayRecipient) : ?>
						<td><?php echo $this->Web->excerpt(str_replace(';', '<br />', $message['Message']['to']), 30, array('wordBreak' => false)); ?></td>
						<?php endif; ?>
						<td>
							<?php // Check if has been read or if the message is being 'cc' to the user
							$read = true;
							if($mode == 'sent') {
								if ($message['Message']['read'] == '0') {
									$read = false;
								}
							} else {
								if(($message['Message']['read'] == '0') && ($message['Message']['recipient_id'] == $__user['User']['id'])) {
									$read = false;
								}
							}
							if($read) {
								$subject_string = $message['Message']['subject'] . ' - ' . $message['Message']['content'];
							} else {
								$subject_string = '<b>' . $message['Message']['subject'] . '</b> - ' . $message['Message']['content'];
							}
							echo $this->Web->excerpt($subject_string, 80, array('stripTags' => false)); ?>
						</td>
						<td><?php echo $this->Web->dt($message['Message']['created'], 'short_4', '12hr'); ?></td>
						<td class="actions">
							<?php if($enableDelete) {
								echo $this->Html->link(__('Delete', true), array('action' => 'delete_message', $message['Message']['id']), array('id' => 'delete_message_' . $message['Message']['id'], 'class' => 'delete_message_' . $mode)); 
							}?>
							<?php echo $this->Html->link(__('View', true), '#', array('class' => 'view-message-link', 'id' => 'view-message-'.$message['Message']['id'])); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			<?php echo $this->element('paginator', array('class' => 'paginator bottom')); ?>
		<?php else: ?>
			<?php echo $this->element('info', array('content' => array('no_items'))); ?>
		<?php endif; ?>
	<?php 	break;
		case 'send' :
		case 'message-view' : ?>
		<?php echo $this->element('communication/message_view', array('employees' => $employees, 'redirect' => $redirect)); ?>
		
	<?php endswitch; ?>
	<?php echo $this->Form->hidden('redirect', array('value' => $redirect, 'id' => 'redirect')); ?>
</div>