<?php echo $this->Html->script('creationsite/call.log', array('inline' => false)); ?>
<div id="log-call">
	<div class="widget center ct-bg widget_container" id="widget_container_">
		<?php echo $this->Form->create(null, array('class' => 'standard wide', 'id' => 'recordCallForm')); ?>
		<?php echo $this->Form->input('date', array('value' => date('m/d/Y'), 'class' => 'new_date datepicker_cluetip', 'id' => 'datepicker_cluetip')); ?>
		<?php $comm_types = array('email' => 'Email', 'phone' => 'Phone'); ?>
		<?php echo $this->Form->input('comment', array('type' => 'textarea', 'id' => 'new_call_record', 'class' => 'new_call_record')); ?>
		<?php echo $this->Form->hidden('Contact.id'); ?>
		<?php echo $this->Form->hidden('Contact.model', array('id' => 'model', 'value' => 'Contact')); ?>
		<div class="submit tabbed">
		<?php echo $this->Form->submit(__('Save'), array('class' => 'red right', 'div' => false, 'id' => 'save_call_record')); ?>
		<?php echo $this->Html->image('loader-grey.gif', array('class'=>'ajax_loader')); ?>
		<div class="ajax_message_success">Call log was successfully saved.</div>
		<div class="ajax_message_fail">Failed to save the call log.</div>
		</div>
		<div id="notes_public"></div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>