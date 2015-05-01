<h3 class="left"><?php echo __('Group Job Types'); ?> (<?php echo $title; ?>)</h3>
<?php echo $this->Form->create('GroupsJobType', array('class' => 'standard', 'novalidate' => true, 'type' => 'file', 'url' => '/'.$this->params->url)); ?>
<h4><?php echo __('Business 360'); ?></h4>
<div class="permissions_container">
	<?php if(!empty($job_types)) : ?>
	<?php 	foreach($job_types as $key=>$job_type) : ?>
	<?php 		$checked = false; 
				if(array_key_exists($key, $current_job_types)) {
					$checked = true;
				} 
				echo $this->Form->input('job_type_'.$key, array('type' => 'checkbox', 'label' => $job_type, 'checked' => $checked, 'id' => $key, 'value' => $key)); 
	?>		
	<?php 	endforeach; ?>
	<?php endif; ?>
</div>
<div class="title-buttons">
	<?php echo $this->Form->submit(__('Save', true), array('class' => 'red')); ?>
</div>					  
<?php echo $this->Form->hidden('Group.id', array('value' => $group_id));?>
<?php echo $this->Form->end(); ?>