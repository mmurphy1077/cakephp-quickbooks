<?php $this->Html->script('creationsite/communication', array('inline' => false)); ?>
<?php echo $this->Html->css('communication'); ?>
<?php
// $style will be either 'message', or 'comment'
if(!isset($style)) {
	$style = 'message';
}
// $mode will either be 'send' or 'view';
if(!isset($mode)) {
	$mode = 'send';
} 
if(!isset($display_prev_messages)) {
	$display_prev_messages = true;
}
if(!empty($this->data['Message']['id'])) {
	$mode = 'view';
}
if($mode == 'send') {
	$send_only_class = '';
	$input_status = '';
} else {
	$send_only_class = 'hide';
	$input_status = 'readonly';
}
$isOwnerClass = 'isNotOwnerMessage';
if(($mode == 'view') && ($__user['User']['id'] == $this->data['Message']['sender_id']) && ($this->data['Message']['sender_model'] == 'User')) {
	$isOwnerClass = 'isOwnerMessage';
}
if($mode == 'send') {
	$isOwnerClass = 'isOwnerMessage';
}
if(!isset($section_types)) {
	$section_types = null;
}
if(!isset($default_type)) {
	$default_type = 'all';
}
if(!isset($allow_filter)) {
	$allow_filter = false;
}
if(!isset($filter_types)) {
	$filter_types = null;
}
if(!isset($displayTouchBase)) {
	$displayTouchBase = true;
}
/*
$class_new = '';
$class_sent = '';
$class_in = '';
*/
if(!isset($selected_filter_val)) {
	$selected_filter_val = 'all';
}
?>			
<?php 
switch($style) : 
	case 'message' : ?>
		<?php
		$from = $this->data['Message']['from'];
		$to = $this->data['Message']['to'];
		$doc_types = array(
				'doc' => 'icon-file-doc.png',
				'pdf' => 'icon-file-pdf.png',
				'odt' => 'icon-file-odt.png',
				'xls' => 'icon-file-xls.png',
		);
		$controller = $this->params->params['controller'];
		
		echo $this->Form->create('Message', array('class' => 'standard', 'type' => 'file', 'novalidate' => true, 'url' => array('controller' => $controller, 'action' => 'send_message'))); 
		if(!isset($contacts)) {
			$contacts = null;
		}
		// Construct the "Reply To" data array (IF NOT IN ADD MODE)
		$reply_to_data = array();
		if($__user['User']['id'] == $this->data['Message']['sender_id']) {
			if(!empty($this->data['Recipients'])) {
				foreach($this->data['Recipients'] as $reply_to) {
					$user_data = array();
					$user_data['reply_to_id'] = $reply_to['foreign_key'];
					$user_data['reply_to_model'] = $reply_to['model'];
					$user_data['reply_to_display'] = $reply_to['display'];
					$reply_to_data[] = $user_data;
				}
			}
		} else {
			$user_data = array();
			$user_data['reply_to_id'] = $this->data['Message']['sender_id'];
			$user_data['reply_to_model'] = $this->data['Message']['sender_model'];
			$user_data['reply_to_display'] = $this->data['Message']['from'];
			$reply_to_data[] = $user_data;
		}
		?>
		<fieldset>
			<div id="message-view-container" class="fieldset-wrapper">
				<?php if($mode == 'view') : ?>
				<div id="message-action-container" class="">
					<?php echo $this->Html->link(__('Reply'), '#', array('id' => 'message-action-reply', 'class' => 'button short')); ?>
				</div>
				<?php endif; ?>
				<h4><div id="posted-container"><?php echo $this->Web->dt($this->data['Message']['created'], 'text_full'); ?> <?php echo $this->data['Message']['time_deviation']; ?></div></h4>
				<div class="grid">
					<div class="col-1of5"><div class="label">From</div></div>
					<div class="col-4of5"><?php echo $this->Form->input('from', array($input_status, 'id' => 'email-from', 'class' => 'email-input', 'label' => false, 'value' => $from, 'error' => array('length' => __('text_100')))); ?></div>
				</div>	
				<div class="grid">
					<div class="col-1of5"><div class="label">To</div></div>
					<div class="col-4of5">
						<div id="to-container"></div>
						<?php echo $this->Form->input('to', array($input_status, 'id' => 'email-to', 'label' => false, 'value' => $to)); ?>
					</div>
				</div>	
				<div id="send-to-selection-container" class="grid <?php echo $send_only_class;?>">
					<div class="col-1of5">&nbsp;</div>
					<div id="to-container" class="col-4of5">
						<ul id="tabs-to" class="form-tabs inline">
							<li id="form-tabs-element-employees-container" class="form-tabs-element">Employees</li>
							<li>|</li>
							<li id="form-tabs-element-contacts-container" class="form-tabs-element">Contacts</li>
						</ul>
						<?php 
						// Determine if a list of contacts was provided... if so do not allow lookup.
						if(!isset($contacts) || empty($contacts) && ($redirect == 'users')) : ?>
						<ul class="form-tabs inline">
							<li id="form-tabs-element-contacts-container" class="form-tabs-element left">
								<?php echo $this->Form->input('contact_search', array('id' => 'contact_search', 'label' => false)); ?>
							</li>
						</ul>
						<?php endif; ?>
						<div id="employees-container" class="form-tabs-container tabs-to form-tabs hide clear" style="display: none;">
							<?php echo $this->element('communication/employee_container', array('employees' => $employees, 'employee_emails' => $employee_emails, 'current_selected_users' => $reply_to_data)); ?>
						</div>
						<div id="contacts-container" class="form-tabs-container tabs-to form-tabs hide clear" style="display: none;">
							<?php echo $this->element('communication/contacts_container', array('contacts' => $contacts, 'current_selected_contacts' => $reply_to_data)); ?>
						</div>
					</div>
				</div>
				<div class="grid">
					<div class="col-1of5"><div class="label">Subject</div></div>
					<div id="to-container" class="col-4of5"><?php echo $this->Form->input('subject', array($input_status, 'id' => 'email-subject', 'class' => 'email-input', 'label' => false, 'error' => array('length' => __('text_255')))); ?></div>
				</div>
				<?php if(!empty($this->data['Attachment']) || !empty($this->data['AttachmentSystemDoc'])) : ?>
				<div id="attachments-container" class="grid">
					<div class="col-1of5"><div class="label"><?php echo __('Attachments'); ?></div></div>
					<div id="email_attachment_container" class="col-4of5">
						<ul id="attachments">
						<?php $flat = array(); ?>
						<?php if(!empty($this->data['AttachmentSystemDoc'])) : ?>
							<?php foreach($this->data['AttachmentSystemDoc'] as $systemDoc) : ?>
						  		<li>
									<?php 
									echo $this->Html->link($systemDoc['display'], $systemDoc['link'], array('div' => false, 'class' => 'doc-type doc-type-pdf'));
									$flat[] = $this->Html->link($this->Web->excerpt_file_name($systemDoc['display'], 15), $systemDoc['link'], array('class' => 'attachment-inline')); ?>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
						<?php if(!empty($this->data['Attachment'])) : ?>
						  	<?php foreach($this->data['Attachment'] as $attachment) : ?>
						  		<li>
									<?php 
									$type_class = '';
									if (strpos(strtolower($attachment['Document']['mime_type']), 'zip') !== false) {
										$type_class = 'doc-type-zip';
									} else if (strpos(strtolower($attachment['Document']['mime_type']), 'word') !== false) {
										$type_class = 'doc-type-word';
									} else if ((strpos(strtolower($attachment['Document']['mime_type']), 'pdf') !== false) || (strpos(strtolower($attachment['Document']['mime_type']), 'octet-stream') !== false)) {
										$type_class = 'doc-type-pdf';
									} else if (strpos(strtolower($attachment['Document']['mime_type']), 'excel') !== false) {
										$type_class = 'doc-type-excel';
									} else if (strpos(strtolower($attachment['Document']['mime_type']), 'powerpoint') !== false) {
										$type_class = 'doc-type-pp';
									} else if (strpos(strtolower($attachment['Document']['mime_type']), 'spreadsheet') !== false) {
										$type_class = 'doc-type-ss';
									} else if (strpos(strtolower($attachment['Document']['mime_type']), 'text') !== false) {
										$type_class = 'doc-type-text';
									} else if (strpos(strtolower($attachment['Document']['mime_type']), 'photoshop') !== false) {
										$type_class = 'doc-type-ps';
									}
									
									#$ext = pathinfo($attachment['Document']['name'], PATHINFO_EXTENSION);
									$title = $attachment['Document']['title'];
									echo $this->Html->link($title, array('controller' => 'documents', 'action' => 'download', $attachment['Document']['id']), array('class' => 'doc-type ' . $type_class));  
									$flat[] = $this->Html->link($this->Web->excerpt_file_name($title, 15), array('controller' => 'documents', 'action' => 'download', $attachment['Document']['id']), array('class' => 'attachment-inline')); ?>
								</li>
							<?php endforeach; ?>
						<?php endif; ?>
						</ul>
						<div id="attachments-inline" class="hide">
						<?php foreach($flat as $attachment) : ?>
							<li><?php echo $attachment; ?> </li>
				  		<?php endforeach; ?>
						</div>
					</div>
				</div>
				<br />
				<?php endif; 	?>
				<div class="grid">
					<div class="col-1of5"><div class="label"><?php echo __('Message'); ?></div></div>
					<div class="col-4of5">
						<?php echo $this->Form->input('content', array($input_status, 'type' => 'textarea', 'class' => 'main-message ' . $isOwnerClass, 'div' => 'input textarea', 'label' => false)); ?>
					</div>
				</div>
				<div id="select_attachment_container" class="grid <?php echo $send_only_class;?>">
					<div class="col-1of5">&nbsp;</div>
					<div class="col-3of5">
						<div class="grid">
							<div class="col-1of4"><div class="label"><?php echo __('Attachments'); ?></div></div>
							<div class="col-3of4">
								<?php 
								/* Check the model... If the model is equal to 'Quote' or 'Order' include the appropriate system docs to be included */
								switch ($this->data['Message']['model']) {
									case 'Quote' :
										echo $this->element('communication/attachment_system_files_quote', array('quote' => $quote));
										break;
									case 'Order' :
										if(!isset($selected_invoices)) {
											$selected_invoices = null;
										}
										echo $this->element('communication/attachment_system_files_order', array('order' => $order, 'selected_invoices' => $selected_invoices));
										break;
								}
								?>
								<?php
								if(!empty($file_uploads)) {
									echo $this->element('communication/attachment_file_upload_container', array('docs' => $file_uploads)); 
								} ?>
								<div class="block">
									<?php echo $this->Form->input('AttachmentExternal.name', array(
										'name' => 'data[AttachmentExternal][][name]',
										'type' => 'file',
										'label' => false,
										'error' => false,
										'multiple'
									)); ?>
								</div>
							</div>
						</div>
					</div>
					<div class="col-1of5">
						<?php echo $this->Form->submit(__('Send'), array('id' => 'message-send', 'class' => 'title-buttons left')); ?>
						<div class="clear align_right"><?php echo $this->Form->input('notify_by_email', array('checked' => 'checked', 'type' => 'checkbox', 'label' => 'Notify by email', 'div' => array('id' => 'notify_by_email'))); ?></div>
					</div>
					<div class="col-1of1">&nbsp;</div>
				</div>
				<div class="grid">
					<div class="col-1of5">&nbsp;</div>
					<div id="prev-message-container" class="col-4of5">
						<div id="stage"></div>
						<?php if(!empty($this->data['Parent'])) {
							echo $this->element('communication/message_thread_element', array('data' => $this->data['Parent'], 'user' => $__user));
						} ?>
					</div>
				</div>
				<br />
				<?php echo $this->Form->hidden('id', array('value' => $this->data['Message']['id'])); ?>
				<?php echo $this->Form->hidden('parent_id', array('value' => $this->data['Message']['parent_id'])); ?>
				<?php echo $this->Form->hidden('redirect', array('value' => $redirect, 'id' => 'redirect')); ?>
				<?php echo $this->Form->hidden('model', array('value' => $this->data['Message']['model'])); ?>
				<?php echo $this->Form->hidden('foreign_key', array('value' => $this->data['Message']['foreign_key'])); ?>
				<?php 
				if (isset($watermark)) {
					echo $this->Form->hidden('watermark', array('value' => $watermark));
				}
				?>
				<div id="reply_data_bank" class="hide">
					<?php if(!empty($reply_to_data)) : ?>
						<?php 	foreach($reply_to_data as $reply_to) : ?>
						<div id="reply-to-<?php echo $reply_to['reply_to_id']; ?>" class="reply-to">
							<?php echo $this->Form->hidden('reply_to_id', array('value' => $reply_to['reply_to_id'], 'id' => 'reply_to_id')); ?>
							<?php echo $this->Form->hidden('reply_to_model', array('value' => $reply_to['reply_to_model'], 'id' => 'reply_to_model')); ?>
							<?php echo $this->Form->hidden('reply_to_display', array('value' => $reply_to['reply_to_display'], 'id' => 'reply_to_display')); ?>	
							<?php #echo $this->Form->hidden('reply_to_email', array('value' => $reply_to['email'], 'id' => 'reply_to_email')); ?>	
						</div>	
						<?php 	endforeach; ?>
					<?php endif; ?>
					<?php
					echo $this->Form->hidden('reply_from', array('value' => $__user['User']['name_first'] . ' ' . $__user['User']['name_last'] . ' (' . $__user['User']['email'] . ')', 'id' => 'reply_from')); 			
					echo $this->element('communication/message_portfolio_pic', array('user' => $this->data['Sender'], 'options' => array('pic_only' => true))); ?>		
				</div>
				<br /><br />
			</div>
		</fieldset>
		<?php echo $this->Form->end(); ?>
<?php 	break; 
	case 'comment' : 
		// Comments are part of the containing form.  These records will be saved within the controllers of the parent controller.
		if(!isset($display)) {
			$display = 'slide';
		} 
		if($display != 'inline') {
			echo $this->Form->create('Message', array('id' => 'ajax_comment_form', 'class' => 'standard', 'type' => 'file', 'novalidate' => true, 'url' => array('controller' => 'messages', 'action' => 'ajax_save_comment')));
		} ?>
		<div id="comment-loader"><?php echo $this->Html->image('loader-large.gif', array('id' => 'comment-loader-image')); ?></div>
		<div id="message-view-container" class="">
			<?php if($mode != 'view_message_thread_only') : ?>
			<div class="grid">
				<!-- <div class="col-1of5"><div class="label"><?php echo __('Comment'); ?></div></div>
				<div class="col-4of5"> -->
				<div class="col-1of1">
					<?php  
					if(!empty($section_types)) {
						echo $this->Form->input('Message.type', array('options' => $section_types, 'value' => $default_type, 'class' => 'comment_type', 'id' => 'comment_type', 'label' => false, 'div' => false));
					} else {
						echo $this->Form->hidden('Message.type', array('value' => $default_type));
					} ?>
					<?php echo $this->Form->input('Message.content', array($input_status, 'type' => 'textarea', 'class' => 'main-message mceNoEditor short full ' . $isOwnerClass, 'div' => 'input textarea', 'label' => false)); ?>
					<div class="error-message error-message-comment no_label_space" id="error-message-MessageContent"></div>
				
					<?php #echo $this->Form->hidden('id', array('value' => $this->data['Message']['id'])); ?>
					<?php echo $this->Form->hidden('Message.parent_id', array('value' => $this->data['Message']['parent_id'])); ?>
					<?php echo $this->Form->hidden('Message.model', array('value' => $this->data['Message']['model'])); ?>
					<?php echo $this->Form->hidden('Message.foreign_key', array('value' => $this->data['Message']['foreign_key'])); ?>
					<?php echo $this->Form->hidden('Message.parent_model', array('value' => $this->data['Message']['parent_model'])); ?>
					<?php echo $this->Form->hidden('Message.parent_foreign_key', array('value' => $this->data['Message']['parent_foreign_key'])); ?>
					<?php echo $this->Form->hidden('Message.subject', array('value' => $this->data['Message']['subject'])); ?>
					<?php echo $this->Form->hidden('Message.from', array('value' => $this->data['Message']['from'])); ?>
					<?php echo $this->Form->hidden('Message.to', array('value' => $this->data['Message']['to'])); ?>
				</div>
				<?php if($displayTouchBase) : ?>
				<div id="message-touchbase-container" class="hide col-3of4">
					<?php 
					$default_reminder_interval = null;
					$app_session = $this->Session->read('Application.settings');
					switch($this->data['Message']['model']) {
						case 'Contact' :
							if(array_key_exists('default_reminder_lead', $app_session['ApplicationSetting']) && !empty($app_session['ApplicationSetting']['default_reminder_lead'])) {
								$default_reminder_interval = $app_session['ApplicationSetting']['default_reminder_lead'];
							}
							break;
						case 'Quote' :
							if(array_key_exists('default_reminder_quote', $app_session['ApplicationSetting']) && !empty($app_session['ApplicationSetting']['default_reminder_quote'])) {
								$default_reminder_interval = $app_session['ApplicationSetting']['default_reminder_quote'];
							}
							break;
						case 'Order' :
							if(array_key_exists('default_reminder_order', $app_session['ApplicationSetting']) && !empty($app_session['ApplicationSetting']['default_reminder_order'])) {
								$default_reminder_interval = $app_session['ApplicationSetting']['default_reminder_order'];
							}
							break;
					}
					echo $this->element('communication/callback', array('data' => null, 'default_reminder_interval' => $default_reminder_interval, 'datepicker_id' => 'message')); ?>
					<br /><br /><br />&nbsp;
				</div>
				<?php endif; ?>
				<?php if($display != 'inline') : ?>
				<div class="right col-1of4">
					<?php 
					$id = 'post-comment';
					if($display == 'slide') {
						$id = 'ajax-post-comment';
					}
					echo $this->Form->submit(__('Post'), array('id' => $id, 'class' => 'ajax_form post title-buttons left')); ?>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>
			<?php if($allow_filter && !empty($filter_types)) : ?>	
				<div id="search-criteria-cluetip-container" class="hide">
					<div>Filter Comments By:</div>
					<?php foreach($filter_types as $key=>$searchType) : ?>
					<?php echo $this->Html->link($searchType, '#', array('class' => 'search-criteria-option message-type-option', 'id' => $key)); ?>
					<?php endforeach; ?>
					<?php echo $this->Html->link('All', '#', array('class' => 'search-criteria-option message-type-option', 'id' => 'all')); ?>
					<?php echo $this->Html->image('icon-close.png', array('id' => 'search-criteria-cluetip-container-close'))?>
				</div>
				<div class="grid">
					<div class="col-1of1">&nbsp;</div>
					<div class="col-1of1">
						<?php 
						$label = 'All';  
						if(array_key_exists($default_type, $filter_types)) {
							$label = $filter_types[$default_type];
						}
						?>
						<div id="index-filter-types" class="comment-filter">Show comments of type:  &nbsp;&nbsp;&nbsp;<?php echo $this->Html->link($label, '#', array('id' => 'search-criteria-select', 'class' => 'inline')); ?></div>
						<?php echo $this->Form->hidden('selected_filter', array('id' => 'selected_filter', 'value' => $selected_filter_val)); ?>
					</div>
					<div class="col-1of1">&nbsp;</div>
				</div>
			<?php endif; ?>
			
			<?php 
			if($display_prev_messages) : 
				if(!isset($disable_prev_messages_update)) {
					$disable_prev_messages_update = false;
				}
				$class = 'enabled';
				if ($disable_prev_messages_update) {
					$class = 'disabled';
				}
			?>	
			<div id="prev-message-container" class="<?php echo $class; ?>">
			<?php echo $this->element('communication/prev_messages', array('messages' => $messages, 'selected_filter_val' => $selected_filter_val)); ?>
			</div>
			<?php endif; ?>
		</div>
		<?php 
		if($display != 'inline') {
			echo $this->Form->end();
		} ?>
<?php 	break; 
endswitch;	?>
<div id="message-thread-element-template" class="hide">
	<?php echo $this->element('communication/message_thread_element', array('data' => null, 'user' => null)); ?>
</div>