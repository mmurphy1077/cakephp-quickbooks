<div class="grid">
	<div class="col-1of4">
		<?php echo $this->fetch('leftbar'); ?>
	</div>
	<div class="col-3of4">
		<div class="grid">
			<div class="col-2of3">
				<?php echo $this->fetch('content'); ?>
			</div>
			<div class="col-1of3">
				<div class="widget right">
					<?php echo $this->element('nav_context', array('before' => '&rarr; ', 'wrapper' => 'ul')); ?>
				</div>
				<?php echo $this->fetch('rightbar'); ?>
			</div>
		</div>
	</div>
</div>

<div id="log-call">
	<div class="widget center widget_container" id="widget_container_">
		<?php echo $this->Form->create(null, array('class' => 'standard wide', 'id' => 'recordCallForm')); ?>
		<?php echo $this->Form->input('Contact.date', array('value' => date('m/d/Y'), 'class' => 'new_date', 'id' => 'datepicker_cluetip')); ?>
		<?php echo $this->Form->input('Contact.comment', array('type' => 'textarea', 'id' => 'new_call_record', 'class' => 'new_call_record')); ?>
		<?php echo $this->Form->hidden('Contact.id'); ?>
		<div class="submit tabbed">
		<?php echo $this->Form->submit(__('Save'), array('div' => false, 'id' => 'save_call_record')); ?>
		<?php echo $this->Html->image('loader-grey.gif', array('class'=>'ajax_loader')); ?>
		<div class="ajax_message_success">Call log was successfully saved.</div>
		<div class="ajax_message_fail">Failed to save the call log.</div>
		</div>
		<div id="notes_public"></div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>