<?php
$row_count = 1; 
foreach($data as $element) : ?>
<div id="field-dschedule-table-container" class="">
	<?php if($row_count > 1) : ?>
		<?php $link = array('controller' => 'schedules', 'action' => 'index_day', 'date_selected' => $element['date']); ?>
		<h3 class="table-top"><?php echo $this->Html->link(date('l, F jS, Y', strtotime($element['date'])), $link, array('class' => 'schedule-time-display-link')); ?></h3>
	<?php endif;?>
<?php if(empty($element['Schedule'])) : ?>
		<div class="clear">
			<?php echo $this->element('info', array('content' => array(
				'no_items',
			))); ?>
		</div>
<?php else: 
		$total_duration = 0; ?>
		<table id="" class="standard nohover">
			<tr>
				<th>&nbsp;</th>
				<th>Job</th>
				<th>Time</th>
				<th>Assigned</th>
				<th class="center">Msg. (unread)</th>
				<th>&nbsp;</th>
			</tr>
		<?php foreach ($element['Schedule'] as $schedule) : 
				$total_duration = $total_duration + $schedule['day_duration']; ?>
			<tr>
				<td>&nbsp;</td>
				<td><?php echo $schedule['Job']['name']; ?> <br/>
					<?php if(!empty($schedule['Job']['customer_name'])) : ?>
					<span class="small light">(<?php echo $schedule['Job']['customer_name']; ?>)</span>
					<?php endif; ?>
				</td>
				<td><?php echo date('g:i a', strtotime($schedule['time_start'])); ?> - <?php echo date('g:i a', strtotime($schedule['time_end'])); ?></td>
				<td>
					<?php if(!empty($schedule['User'])) : ?>
					<ul>
					<?php	foreach($schedule['User'] as $sched_user) : ?>
						<li>
							<?php echo $sched_user['User']['name_first'] . ' ' . $sched_user['User']['name_last']; ?>
							<span class="light small right"><?php echo $sched_user['UserGroup']['name']; ?></span>
						</li>
					<?php 	endforeach; ?>
					</ul>
					<?php endif; ?>
				</td>
				<td class="center"><?php echo $schedule['msg_count_unread']; ?></td>
				<td id="field-view-button-container" class="nowrap"> 
					<?php echo $this->Html->link($this->Html->image('icon-message.png'), array('controller' => 'orders', 'action' => 'messages_field', $schedule['order_id']), array('class'=>'field-action-button field-action-button-message', 'escape' => false))?>
					<?php echo $this->Html->link($this->Html->image('icon-upload.png'), array('controller' => 'orders', 'action' => 'docs', $schedule['order_id']), array('class'=>'field-action-button field-action-button-upload', 'escape' => false))?>
					<?php echo $this->Html->link($this->Html->image('icon-materials.png'), array('#'), array('class'=>'field-action-button field-action-button-materials', 'escape' => false))?>
					<?php echo $this->Html->link($this->Html->image('icon-time.png'), array('controller' => 'order_times', 'action' => 'index', $schedule['order_id']), array('class'=>'field-action-button field-action-button-time', 'escape' => false))?>
					<?php echo $this->Html->link($this->Html->image('icon-view.png'), array('controller' => 'orders', 'action' => 'view_' . $__browser_view_mode['browser_view_mode'], $schedule['order_id']), array('class'=>'field-action-button field-action-button-view', 'escape' => false))?>
				</td>
			</tr>
		<?php endforeach; ?>
			<tr class="schedule-daily-summary-row">
				<td>&nbsp;</td>
				<td>Daily Summary: </td>
				<td><?php echo $total_duration; ?> hours</td>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>
		<br /><br />
<?php endif; ?>
</div>
<?php 
	$row_count = $row_count + 1;
endforeach; ?>