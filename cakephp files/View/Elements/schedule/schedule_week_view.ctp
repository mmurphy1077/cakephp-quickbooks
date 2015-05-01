<?php
$order_id = null;
$weekdays = array(
	'Monday',
	'Tuesday',
	'Wednesday',
	'Thursday',
	'Friday',
	'Saturday',
	'Sunday',
);
$headerStartTime_hour = SCHEDULE_WORKDAY_START/100;
$headerStopTime_hour = SCHEDULE_WORKDAY_END/100;
$header_colspan = $headerStopTime_hour - $headerStartTime_hour;
?>
<table id="weekly_schedule" class="time <?php echo $display_period_type; ?>_schedule">
	<tr class="schedule-top-row">
		<?php
		$first_day = true;
		$date = $date_markers['beginning_of_week']; 
		foreach ($weekdays as $i => $weekday):
			if(!$first_day) {
				$date = date('Y-m-d', strtotime($date. ' + 1 day'));
			}
			$css = null;
			if ($date_markers['date_selected'] == $date) {
				$css = ' class="active"';
			}
			?>
			<th<?php echo $css; ?> colspan="<?php echo $header_colspan; ?>"><?php echo $this->Html->link(__($weekday).' '.date('n/j', strtotime($date)), array('controller' => 'schedules', 'action' => 'index_day', 'date_selected' => $date, 'order_id' => $order_id)); ?></th>	
		<?php 
			$first_day = false;
		endforeach; ?>
	</tr>
	<tr><th class="spacer padded" colspan="96">&nbsp;</th></tr>
</table>
<?php 
// must have the day start times and the day end times
// must have the first day and the last
if(!empty($yaxis)) : 
	foreach($yaxis as $yaxis_element) : ?>
	<table id="time-<?php echo $yaxis_element['id']; ?>" class="time <?php echo $display_period_type; ?>_schedule time-y-axis">
		<tr id="time-row-<?php echo $yaxis_element['id']; ?>-1" class="time-row time-row-<?php echo $yaxis_element['id']; ?>">
			<?php 
			for($i = 1; $i <=7; $i++): 
				$currentDate = date('Ymd', strtotime($date_markers['beginning_of_week']. ' + ' . ($i - 1) . ' days'));
				$weekend = '';
				if(date('N', strtotime($date_markers['beginning_of_week']. ' + ' . ($i - 1) . ' days')) >= 6) {
					$weekend = 'weekend';
				}
				$hour = SCHEDULE_WORKDAY_START/100; 
				$include_day_border = 'day_seperator';
				while($hour < (SCHEDULE_WORKDAY_END/100)) : ?>
					<td id="<?php echo 'time-y-axis-id-'. $yaxis_element['id'] .'-'. $currentDate .'-'.$hour; ?>" class="time-y-axis-record <?php echo $include_day_border; ?> <?php echo $weekend; ?>"><div>&nbsp;</div></td>
			<?php 
					$include_day_border = '';
					$hour = $hour + 1;
				endwhile;
			endfor; ?>
		</tr>
	</table>
<?php 
	endforeach;
endif;	?>