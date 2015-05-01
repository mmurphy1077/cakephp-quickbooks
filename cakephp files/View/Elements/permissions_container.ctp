<?php 
unset($control_objects['_access']);
$delete_id = null;
$delete_label = null;
if(array_key_exists('_delete', $control_objects)) {
	$delete_id = $control_objects['_delete']['id'];
	$delete_label = $control_objects['_delete']['label'];
	unset($control_objects['_delete']);
}
?>
<div class="grid">
	<div class="col-1of3">
		<?php 
		/*
		 * Each modeule will have Create, Update, and Read only option
		 */
		$checked_create = false;
		$checked_update = false;
		$checked_read_only = false;
		if(array_key_exists($model, $current_permissions)) {
			if(array_key_exists('_create', $current_permissions[$model]) && $current_permissions[$model]['_create'] == 1) {
				$checked_create = true;
			}
			
			if(array_key_exists('_update', $current_permissions[$model]) && $current_permissions[$model]['_update'] == 1) {
				$checked_update = true;
			}
			
			if(array_key_exists('_read_only', $current_permissions[$model]) && $current_permissions[$model]['_read_only'] == 1) {
				$checked_read_only = true;
			}
		}
		echo $this->Form->input('aco_'.$control_objects['_create']['id'], array('type' => 'checkbox', 'label' => $control_objects['_create']['label'], 'checked' => $checked_create, 'id' => $control_objects['_create']['id'], 'class' => 'create permission', 'disabled' => $disabled));
		echo $this->Form->input('aco_'.$control_objects['_update']['id'], array('type' => 'checkbox', 'label' => $control_objects['_update']['label'], 'checked' => $checked_update, 'id' => $control_objects['_update']['id'], 'class' => 'update permission', 'disabled' => $disabled));
		echo $this->Form->input('aco_'.$control_objects['_read_only']['id'], array('type' => 'checkbox', 'label' => $control_objects['_read_only']['label'], 'checked' => $checked_read_only, 'id' => $control_objects['_read_only']['id'], 'class' => 'read_only', 'disabled' => $disabled));
		unset($control_objects['_create']);
		unset($control_objects['_update']);
		unset($control_objects['_read_only']);
		?>
	</div>
	<div class="col-1of3">
		<?php 
		if(!empty($control_objects)) :
			foreach($control_objects as $key=>$object) : ?>
			<?php 
			$checked = false;
			if(array_key_exists($model, $current_permissions) && array_key_exists($key, $current_permissions[$model]) && $current_permissions[$model][$key] == 1) {
				$checked = true;
			}
			echo $this->Form->input('aco_'.$object['id'], array('type' => 'checkbox', 'label' => $object['label'], 'checked' => $checked, 'id' => $object['id'], 'class' => 'permission', 'disabled' => $disabled)); ?>
		<?php endforeach;
		else :
			echo '&nbsp;'; 
		endif;	?>
	</div>
	<div class="col-1of3">
		<?php 
		$checked = false;
		if(array_key_exists($model, $current_permissions)) {
			if(array_key_exists('_delete', $current_permissions[$model]) && $current_permissions[$model]['_delete'] == 1) {
				$checked = true;
			}
		}
		echo $this->Form->input('aco_'.$delete_id, array('type' => 'checkbox', 'label' => $delete_label, 'checked' => $checked, 'id' => $delete_id, 'class' => 'delete permission', 'disabled' => $disabled));
		?>
	</div>
</div>