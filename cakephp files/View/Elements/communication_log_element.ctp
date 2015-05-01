<?php if(empty($enable_delete)) { $enable_delete = false; } ?>
<div id="call_element_<?php echo $data['id']; ?>" class="call_element">
	<div class="title">
		<?php echo ':: ' . date('m/d/y', strtotime($data['date_communication'])) . ' - ' . $this->Web->humanName($data['User'], 'first_initial'); ?>
		<?php 
		if($enable_delete) {
			echo $this->Html->link($this->Html->image('chosen-sprite.png'), '#', array('id' => $data['id'], 'class' => 'delete_call_record', 'escape' => false)); 
		}	?>
	</div>
	<div class="description"><?php echo nl2br($data['comment']); ?></div>
</div>