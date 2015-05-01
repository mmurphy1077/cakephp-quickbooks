<table class="standard nohover">
	<tr>
		<?php if($style != 'mobile') : ?>
			<th>&nbsp;</th>
		<?php endif; ?>
		<th>When</th>
		<th>Duration</th>
		<th>Assigned To</th>
		<?php if($style != 'mobile') : ?>
			<th>Created By</th>
			<th class="center">Comments</th>
		<?php endif; ?>
		<th>&nbsp;</th>
	</tr>
	<?php if(!empty($schedules)) : 
			foreach($schedules as $schedule) : ?>
	<tr>
		<?php if($style != 'mobile') : ?>
			<td>&nbsp;</td>
		<?php endif; ?>
		<td><?php echo $this->Web->dt($schedule['Schedule']['date_session_start'], 'text_full'); ?>&nbsp;
			<?php if($style == 'mobile') : ?>
			<br />
			<?php endif; ?>
			<?php echo $this->Web->dt($schedule['Schedule']['time_start'], null, '12hr'); ?>
			&nbsp;-&nbsp;
			<?php 
			if($schedule['Schedule']['date_session_start'] != $schedule['Schedule']['date_session_end']) {
				echo $this->Web->dt($schedule['Schedule']['date_session_end'], 'text_full') . '&nbsp;';
			}
			echo $this->Web->dt($schedule['Schedule']['time_end'], null, '12hr'); ?>
		</td>
		<td><?php echo round($schedule['Schedule']['duration_in_seconds']/3600, 2); ?> hrs</td>
		<td>
		<?php 
		if(!empty($schedule['ScheduleResource'])) : 
			foreach($schedule['ScheduleResource'] as $assigned) :?>
			
				<?php echo $this->Web->humanName($assigned['User'], 'first_initial'); ?> <span class="light small">(<?php echo $assigned['User']['Group']['name'];?>)</span><br />
		
		<?php
			endforeach; 
		endif; ?>
		</td>
		<?php if($style != 'mobile') : ?>
			<td><?php echo $this->Web->humanName($schedule['Creator'], 'first_initial'); ?></td>
			<td class="center">
				<div id="comment-log-<?php  echo $schedule['Schedule']['id']; ?>" class="comment_log_container hide">
					<?php 
					$count = count($schedule['Comment']);
					$sched_clue_tip = '';
					if($count > 0) {
						$count = '<b>' . $count . '</b>';
						$sched_clue_tip = 'cluetip-schedule-comment';
					}
					if(!empty($schedule['Comment']))  {
						foreach ($schedule['Comment'] as $comment) { 
							echo $this->element('communication/comment_thread_element', array('data' => $comment, 'user' => $__user));
						}
					} 	?>
				</div>
				<div class="<?php echo $sched_clue_tip; ?> schedule-comment-log" title="Comments" rel="#comment-log-<?php echo $schedule['Schedule']['id']; ?>"><?php echo $count; ?></div>
			</td> 
		<?php endif; ?>
		<td class="actions">
		<?php 
		if($permissions['schedule_delete'] == 1) {
			echo $this->Html->link(__('delete'), array('controller' => 'orders', 'action' => 'delete_schedule', $schedule['Schedule']['id'], $schedule['Schedule']['order_id']), array(), __('delete_confirm'));
		}
		if($permissions['schedule_update'] == 1) {
			echo $this->Html->link(__('edit'), array('controller' => 'orders', 'action' => 'edit_schedule', $schedule['Schedule']['id'])); 
		}
		?>
		</td>
	</tr>
	<?php 	endforeach;
	endif; ?>
</table>