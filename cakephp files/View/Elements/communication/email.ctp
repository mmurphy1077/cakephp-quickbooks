<?php
echo $this->element('js'.DS.'jquery', array('ui' => 'combobox'));
$controller = $this->params->params['controller'];
echo $this->Form->create('Message', array('class' => 'standard', 'type' => 'file', 'novalidate' => true, 'url' => array('controller' => $controller, 'action' => 'send_message'))); ?>
	<fieldset>
		<div class="fieldset-wrapper">
			<div class="grid">
				<div class="col-1of5"><div class="label">From</div></div>
				<div class="col-4of5"><?php echo $this->Form->input('from', array('id' => 'email-from', 'class' => 'email-input', 'label' => false, 'value' => $__user['User']['email'], 'error' => array('length' => __('text_100')))); ?></div>
			</div>	
			<div class="grid">
				<div class="col-1of5"><div class="label">To</div></div>
				<div class="col-4of5">
					<div id="to-container"></div>
					<?php echo $this->Form->input('to', array('id' => 'email-to', 'label' => false)); ?>
				</div>
			</div>			
			<div class="grid">
				<div class="col-1of5">&nbsp;</div>
				<div id="to-container" class="col-4of5">
					<ul id="tabs-to" class="form-tabs">
						<li id="form-tabs-element-employees-container" class="form-tabs-element">Employees</li>
						<li>|</li>
						<li id="form-tabs-element-contacts-container" class="form-tabs-element">Contacts</li>
						<li id="form-tabs-element-contacts-container" class="form-tabs-element"><?php echo $this->Form->input('contact_search', array('id' => 'contact_search', 'label' => false)); ?></li>
					</ul>
					<div id="employees-container" class="form-tabs-container tabs-to hide" style="display: none;">
						<?php echo $this->element('communication/employee_container', array('employees' => $employees, 'employee_emails' => $employee_emails)); ?>
					</div>
					<div id="contacts-container" class="form-tabs-container tabs-to hide" style="display: none;">
						<?php echo $this->element('communication/contacts_container', array('customer' => null, 'leads' => null)); ?>
					</div>
				</div>
			</div>
			
			<div class="grid">
				<div class="col-1of5"><div class="label">Subject</div></div>
				<div id="to-container" class="col-4of5"><?php echo $this->Form->input('subject', array('id' => 'email-subject', 'class' => 'email-input', 'label' => false, 'error' => array('length' => __('text_255')))); ?></div>
			</div>

			<div class="grid">
				<div class="col-1of5">&nbsp;</div>
				<div id="to-container" class="col-4of5"><?php echo $this->Form->input('cc', array('label' => __('Send a copy to my inbox.'), 'checked' => false)); ?></div>
			</div>
			<div class="grid">
				<div class="col-1of5">&nbsp;</div>
				<div class="col-4of5"><?php echo $this->Form->input('content', array('type' => 'textarea', 'div' => 'input textarea tall', 'label' => false)); ?></div>
			</div>
			<div id="email_attachment_container" class="grid">
				<div class="col-1of5">&nbsp;</div>
				<div class="col-3of5">
					<label><?php echo __('Attachments'); ?></label>
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
				<div class="col-1of5">
					<?php echo $this->Form->submit(__('Send'), array('id' => 'message-send', 'class' => 'title-buttons left')); ?>
					<div class="clear align_right"><?php echo $this->Form->input('notify_by_email', array('checked' => 'checked', 'type' => 'checkbox', 'label' => 'Notify by email', 'div' => array('id' => 'notify_by_email'))); ?></div>
				</div>
			</div>
			<?php echo $this->Form->hidden('redirect', array('value' => $redirect)); ?>
			<?php echo $this->Form->hidden('parent_id', array('value' => null)); ?>
			<?php echo $this->Form->hidden('model', array('value' => null)); ?>
			<?php echo $this->Form->hidden('foreign_key', array('value' => null)); ?>
			<br /><br />
		</div>
	</fieldset>
<?php echo $this->Form->end(); ?>
<div id="email-loader-container" class="loader-container"><?php echo $this->Html->image('loader-large.gif'); ?></div>