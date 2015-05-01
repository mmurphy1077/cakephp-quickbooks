<table id="schedule-header" class="time">
	<tr class="schedule-top-row">
		<th colspan="4">1am</th>
		<th colspan="4">2am</th>
		<th colspan="4">3am</th>
		<th colspan="4">4am</th>
		<th colspan="4">5am</th>
		<th colspan="4">6am</th>
		<th colspan="4">7am</th>
		<th colspan="4">8am</th>
		<th colspan="4">9am</th>
		<th colspan="4">10am</th>
		<th colspan="4">11am</th>
		<th colspan="4">12pm</th>
		<th colspan="4">1pm</th>
		<th colspan="4">2pm</th>
		<th colspan="4">3pm</th>
		<th colspan="4">4pm</th>
		<th colspan="4">5pm</th>
		<th colspan="4">6pm</th>
		<th colspan="4">7pm</th>
		<th colspan="4">8pm</th>
		<th colspan="4">9pm</th>
		<th colspan="4">10pm</th>
		<th colspan="4">11pm</th>
		<th colspan="4">12am</th>
	</tr>
	<tr>
		<?php for ($i=1; $i<=24; $i++) : ?>
		<th class="hour_marker" colspan="4">&nbsp;</th>
		<?php endfor; ?>
	</tr>
	<tr>
		<?php for ($i=1; $i<=24; $i++) : ?>
		<th class="minute_marker" id="header_<?php echo $i.'00'; ?>">&nbsp;</th>
		<th class="minute_marker" id="header_<?php echo $i.'15'; ?>">&nbsp;</th>
		<th class="minute_marker" id="header_<?php echo $i.'30'; ?>">&nbsp;</th>
		<th class="minute_marker" id="header_<?php echo $i.'45'; ?>">&nbsp;</th>
		<?php endfor; ?>
	</tr>
	<tr><th class="spacer" colspan="96">&nbsp;</th></tr>
</table>
<?php 
if(!empty($yaxis)) : 
	foreach($yaxis as $yaxis_element) : ?>
	<table id="time-<?php echo $yaxis_element['id']; ?>" class="time <?php echo $display_period_type; ?>_schedule time-y-axis">
		<tr id="time-row-<?php echo $yaxis_element['id']; ?>-1" class="time-row time-row-<?php echo $yaxis_element['id']; ?>">
			<?php for ($i=1; $i<=24; $i++) : ?>
			<td id="<?php echo 'time-y-axis-id-'. $yaxis_element['id'] .'-'. date('Ymd', strtotime($date_selected)) .'-'.$i.'00'; ?>" class="time-y-axis-record"><div>&nbsp;</div></td>
			<td id="<?php echo 'time-y-axis-id-'. $yaxis_element['id'] .'-'. date('Ymd', strtotime($date_selected)) .'-'.$i.'15'; ?>" class="time-y-axis-record"><div>&nbsp;</div></td>
			<td id="<?php echo 'time-y-axis-id-'. $yaxis_element['id'] .'-'. date('Ymd', strtotime($date_selected)) .'-'.$i.'30'; ?>" class="time-y-axis-record"><div>&nbsp;</div></td>
			<td id="<?php echo 'time-y-axis-id-'. $yaxis_element['id'] .'-'. date('Ymd', strtotime($date_selected)) .'-'.$i.'45'; ?>" class="time-y-axis-record"><div>&nbsp;</div></td>
			<?php endfor; ?>
		</tr>
	</table>
<?php 
	endforeach;
endif;	?>