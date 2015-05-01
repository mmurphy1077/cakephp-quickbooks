<?php 
$title = Inflector::pluralize(Configure::read('Nomenclature.Quote'));
$m = 'Quote';
$model_status = $quoteStatuses;
if($model == 'order') {
	$title = Inflector::pluralize(Configure::read('Nomenclature.Order'));
	$m = 'Order';
	$model_status = $orderStatuses;
}
echo $this->element('js'.DS.'editable.order.status.init', array('model' => $model, 'approvalStatuses' => $approvalStatuses, 'taskStatuses' => $taskStatuses, 'modelStatuses' => $model_status));
?>
<div class="widget center">
	<h3 class="left table"><?php echo __($title); ?>&nbsp;&nbsp;<span class="small">(<?php echo __('tasks due by ' . date('m/d/Y', strtotime($date_markers['date_selected']))); ?>)</span></h3>
	<?php echo $this->Html->link('include Completed Tasks', array('#'), array('id' => $model, 'class' => 'dashboard-completed right inline')); ?>
	<?php if(empty($due_today)) : ?>
		<div class="clear">
			<?php echo $this->element('info', array('content' => array('no_items'))); ?>
		</div>
	<?php else : ?>
	<table id="things-due-today" class="standard nohover">
		<tr>
			<th>&nbsp;</th>
			<th><?php echo __('Job Name'); ?></th>
			<th><?php echo __('Project Mngr'); ?></th>
			<th><?php echo __('Task / Item'); ?></th>
			<th><?php echo __('Assigned To'); ?></th>
			<th><?php echo __('Due Date'); ?></th>
			<th><?php echo __('Status'); ?></th>
			<th><?php #echo __('Comments'); ?>&nbsp;</th>
			<th>&nbsp;</th>
		</tr>
		<?php foreach($due_today as $key=>$data) : 
			if(!empty($data['alerts'])) : 
				$total_num_of_items_due = count($data['alerts']);
				$num_of_items_due = count($data['alerts']);
				foreach($data['alerts'] as $alert_key=>$alert) : 
					$name = $data[$m]['name'];
					$project_manager = null;
					if(!empty($data['ProjectManager']['id'])) {
						$project_manager = $this->Web->humanName($data['ProjectManager'],'full');
					}
					$task = $alert['title'];
					$redirect = $alert['redirect'];
					$assigned_to = '&nbsp;'; 
					if(!empty($alert['assign_to'])) {
						$assigned_to = $alert['assign_to'];
					}
							
					$assigned_to_other = 'font_color_grey';
					if(!empty($alert['display_as_owner'])) {
						$assigned_to_other = '';
					}
					$due_date = $alert['due_date'];
					$display_status = $alert['status'];
					
					$border_class = 'no_border';
					#if($num_of_items_due == 1) {
					#	$border_class = '';
					#}
					?>
					<tr class="dashboard-things-due-today-<?php $total_num_of_items_due?>">
						<?php if($num_of_items_due == $total_num_of_items_due) : ?>
						<td class="<?php echo $border_class; ?>" rowspan="<?php echo $total_num_of_items_due; ?>"><?php #echo $this->Html->image('icon-mag.png', array('class' => 'div-cluetip more_info',  'title' => 'SO 130301 &mdash; First Call Heating &amp; Cooling', 'rel' => '#so-130301'))?></td>
						<td class="<?php echo $border_class; ?>" rowspan="<?php echo $total_num_of_items_due; ?>"><?php echo $this->Html->link($name, $alert['redirect_main']); ?></td>
						<td class="<?php echo $border_class; ?>" rowspan="<?php echo $total_num_of_items_due; ?>"><?php echo $project_manager; ?>
						<?php endif; ?>
						<td class="<?echo $assigned_to_other?> <?php echo $border_class; ?>"><?php echo $task; ?></td>
						<td class="<?echo $assigned_to_other?> <?php echo $border_class; ?>"><?php echo $assigned_to; ?></td>
						<td class="<?echo $assigned_to_other?>  small <?php echo $border_class; ?>"><?php echo $this->Web->dt($due_date, 'short_4'); ?></td>
						<td class="inline-edit <?echo $assigned_to_other?> <?php echo $border_class; ?>">
							<?php if(!empty($alert['display_as_owner'])) : ?>
								<?php 
								$class = '';
								switch($alert['status_type']) {
									case 'quote' :
									case 'order' :
										$class = 'edit_status_'.$model;
										break;
									case 'task' :
										$class = 'edit_task_status_'.$model;
										break;
									case 'approval' :
										$class = 'edit_approval_status_'.$model;
										break;
								}
								/*OrderTask-176-status-23-task*/
								/* Obtain the id */
								switch($alert['model']) {
									case 'Quote' :
									case 'Order' :
										$parent_id = $data[$alert['model']]['id'];
										$target_id = $data[$alert['model']]['id'];
										break;
									default :
										$target_id = $data[$alert['model']][0]['id'];
										// Is the parent from Quote or Order?
										$parent_id = $data[$m]['id'];
								}
								$id = $alert['model'] . '-' . $target_id . '-' . $alert['field'] . '-' . $parent_id . '-' . $alert['status_type']; 
								#$td_class = 'inline-edit-inactive';
								#if($permissions['can_approve'] == 1 || ($__user['User']['id'] == $quote_task['assigned_to_id']['id'])) {
								#	$td_class = 'inline-edit'; 
								#	list($controller, $action, $field) = explode('-', $quote_task['id']);
								#	$class = 'edit_' . $controller . '_' . $action . '_status';
								#} ?>
								<!-- <td class="nowrap <?php #echo $td_class; ?>">  -->
									<div class="<?php echo $class; ?>" id="<?php echo $id; ?>">
										<?php echo $display_status; ?>
									</div>
								<!-- </td> -->
							<?php else : ?>
							<?php 		echo $display_status; ?>
							<?php endif; ?>			
						</td>
						 <td class="<?echo $assigned_to_other?> <?php echo $border_class; ?> actions">
						 	<?php 
						 	if(!empty($alert['display_as_owner'])) {
								echo $this->Html->link(__('Go'), $redirect); 
						 	}	?>
						 	&nbsp;
						 </td>
					</tr>
		<?php 		$num_of_items_due = $num_of_items_due - 1;
				endforeach;
			endif;
			if(!empty($data['Completed'])) : 
				foreach($data['Completed'] as $completed_task) : ?>
			<tr class="hide completed-row-<?php echo $model; ?>">
				<td colspan="3" class="no_border font_color_grey">&nbsp;</td>
				<td class="no_border font_color_grey"><?php echo $completed_task['item']; ?></td>
				<td class="no_border font_color_grey"><?php echo $completed_task['assignedTo']; ?></td>
				<td class="no_border font_color_grey small"><?php echo $this->Web->dt($completed_task['due'], 'short_4'); ?></td>
				<td colspan="2" class="no_border font_color_grey">Completed</td>
			</tr>
		<?php endforeach;
			endif; ?>
			<tr class="dashboard-things-due-today-"><td class="slim" colspan="8">&nbsp;</td></tr>
		<?php endforeach;?>	
	</table>
	<?php endif; ?>
</div>