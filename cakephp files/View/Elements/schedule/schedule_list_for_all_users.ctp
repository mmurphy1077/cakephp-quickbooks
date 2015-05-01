<?php
$row_count = 1; 
foreach($data as $element) : ?>
<div id="field-schedule-table-container" class="">
	<?php if($row_count > 1) : ?>
		<?php $link = array('controller' => 'schedules', 'action' => 'index_day', 'date_selected' => $element['date']); ?>
		<h3 class="table-top"><?php echo $this->Html->link(date('l, F jS, Y', strtotime($element['date'])), $link, array('class' => 'schedule-time-display-link')); ?></h3>
	<?php endif;?>
<?php if(!empty($element['User'])) : 
		$total_duration = 0;  ?>
		<table id="field-schedule-table" class="standard nohover">
			<tr>
				<th colspan="5">&nbsp;</th>
			</tr>
		<?php 
		foreach ($element['User'] as $user) :
			$user_duration = 0;
			if(empty($user['Schedule'])) : ?>
				<tr>
					<td class="bottom-border" colspan="2"><?php echo $user['name']; ?></td>
					<td class="bottom-border"><?php echo $user_duration; ?> hrs</td>
					<td class="bottom-border" colspan="2">&nbsp;</td>
				</tr>
			<?php 
			else : 
				$first_user_record = true;
				foreach ($user['Schedule'] as $schedule) : 
					$user_duration = $user_duration + $schedule['day_duration'];
					$total_duration = $total_duration + $schedule['day_duration']; ?>
					<tr>
						<td>
							<?php if($first_user_record) {
								echo $user['name'];
							} ?>&nbsp;
						</td>
						<td><?php echo date('g:i a', strtotime($schedule['time_start'])); ?> - <?php echo date('g:i a', strtotime($schedule['time_end'])); ?></td>		
						<td><?php echo $schedule['day_duration']; ?> hrs</td>
						<td><?php echo $this->Web->excerpt($schedule['Job']['name'], 30); ?> <br/>
							<?php if(!empty($schedule['Job']['customer_name'])) : ?>
							<span class="small light">(<?php echo $schedule['Job']['customer_name']; ?>)</span>
							<?php endif; ?>
						</td>
						<td class="actions">
							<?php echo $this->Html->link(__('Details'), array('controller' => 'orders', 'action' => 'edit_schedule', $schedule['id'])); ?>
						</td>
					</tr>
		<?php 
					$first_user_record = false;
				endforeach; ?>
				<tr class="">
					<td class="bottom-border" colspan="3">&nbsp;</td>
					<td class="bottom-border align-right" colspan="2"><b>Total: <?php echo $user_duration; ?> hrs</b></td>
				</tr>
		<?php 
			endif;
		endforeach; ?>
			<tr class="schedule-daily-summary-row">
				<td colspan="2"><b>Daily Summary: <?php echo $total_duration; ?> hrs</b></td>
				<td colspan="3">&nbsp;</td>
			</tr>
		</table>
		<br /><br />
<?php endif; ?>
</div>
<?php 
	$row_count = $row_count + 1;
endforeach; ?>