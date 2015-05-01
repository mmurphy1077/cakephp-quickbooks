<?php 
 /* -- Style Sheets --> */
echo $this->Html->css('communication_log');
 
/* -- .js files --> */
echo $this->element('js'.DS.'communication_log');
?>   
<fieldset>
	<div class="fieldset-wrapper">
		<h4 id="phone_log_icon">
			<?php echo __('Communication Log'); ?>
			<div id="file-upload-button" class="title-buttons right"><?php echo $this->Html->link('Add', array('#'), array('id' => 'add_call', 'class' => 'toggle_display_button')); ?></div>
		</h4>
		<div id="add_call_toggle_display" class="toggle_display_element">
			<?php echo $this->Form->create('CommunicationLog', array('class' => 'ajax_form standard', 'novalidate' => true, 'action' => 'ajax_add')); ?>
				<?php echo $this->Form->input('date_communication', array('div' => array('class' => 'input short text'), 'type' => 'text', 'label' => 'Date', 'class' => 'datepicker', 'readonly' => 'true', 'value' => date('m/d/Y'))); ?>
				<?php 
				$comm_types = array('email' => 'Email', 'phone' => 'Phone');
				?>
				<div class="buttonset">
					<?php echo $this->Form->radio('communication_type', $comm_types, array('value' => $options['communication_type'], 'legend' => __('Type'))); ?>
				</div><br />
				<?php echo $this->Form->input('quick-comm', array('id' => 'quick_comm_select', 'empty' => 'Select', 'options' => $quick_comm_options, 'label' => 'Quick Text')); ?>
				<?php echo $this->Form->input('comment', array('type' => 'textarea', 'label' => 'Description')); ?>
				<div id="error-message-CommunicationLogComment" class="error-message clear"></div>
				
				<?php
				if(!empty($options['created_by_options'])) {
					echo $this->Form->input('created_by', array('value' => $options['created_by'], 'options' => $options['created_by_options'], 'label' => 'Created By', 'empty' => 'Select'));
				} else {
					echo $this->Form->hidden('created_by', array('value' => $options['created_by']));
				}
				?>
				<?php echo $this->Form->hidden('foreign_key', array('value' => $options['foreign_key'])); ?>
				<?php echo $this->Form->hidden('model', array('value' => $options['model'])); ?>
				<?php echo $this->Form->hidden('enable_delete', array('value' => 1)); ?>
				<?php #echo $this->Form->hidden('communication_type', array('value' => $options['communication_type'])); ?>
				<br />
				<?php echo $this->Form->submit(__('Save'), array('class' => 'red right', 'escape' => false)); ?>
				<div id="ajax_loader_log" class="ajax_loader"><?php echo $this->Html->image('loader-large.gif'); ?></div>
				<div id="error-ajax-message-log" class="error-ajax-message"></div>
				<div id="ajax-message-log" class="ajax-message"></div>
			<?php echo $this->Form->end(); ?>
		</div>
		<div id="call_container" class="call_container">
			<?php 
			if(!empty($existing_logs)) {
				foreach($existing_logs as $key=>$data) { 
					echo $this->element('communication_log_element', array('data' => $data, 'enable_delete' => true));
				}
			}
			?>
		</div>
	</div>
</fieldset>