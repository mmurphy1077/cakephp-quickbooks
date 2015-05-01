<?php 
#echo $this->element('js'.DS.'schedule', array('scheduled_jobs' => $scheduled_jobs, 'type' => 'day', 'order_id' => $order_id));
if(!isset($style)) {
	$style = 'standard';
}
?>
<div id="schedule_container">
	<div id="y-axis-container" class="col-1of6">
		<?php 	
		switch($yaxis_view_type) { 
			case 'employee':
				$yaxis = $employees;
				echo $this->element('schedule/schedule_build_yaxis', array('yaxis' => $yaxis, 'type' => 'employee', 'style' => $style));  	
				break;
			case 'job-type':
				$yaxis = $job_types;
				echo $this->element('schedule/schedule_build_yaxis', array('yaxis' => $yaxis, 'type' => 'job-type', 'style' => $style));
				break;
			case 'order':
				$yaxis = $orders;
				echo $this->element('schedule/schedule_build_yaxis', array('yaxis' => $yaxis, 'type' => 'order', 'style' => $style));
				break;
		} ?>
	</div>
	<div id="time_line" class="col-5of6">
		<?php 	
		switch($display_period_type) { 
			case 'day':
				echo $this->element('schedule/schedule_day_view', array('yaxis' => $yaxis, 'date_selected' => $date_markers['date_selected'], 'style' => $style)); 
				break;
			case 'week':
				echo $this->element('schedule/schedule_week_view', array('yaxis' => $yaxis, 'date_markers' => $date_markers, 'style' => $style));
				break;
			case 'month':
				echo $this->element('schedule/schedule_month_view', array('yaxis' => $yaxis, 'date_markers' => $date_markers, 'style' => $style));
				break;
	
		} ?>
	</div>
	<div id="stagging" class="hide"></div>
</div>
<div id="schedule_container_data_bank" class="hide">
	<div id="yaxis-employee" class="hide"><?php #echo $this->element('schedule/schedule_build_yaxis', array('yaxis' => $employees, 'type' => 'employee')); ?></div>
	<div id="yaxis-job-type" class="hide"><?php #echo $this->element('schedule/schedule_build_yaxis', array('yaxis' => $job_types, 'type' => 'job-type')); ?></div>
	<div id="yaxis-order" class="hide"><?php #echo $this->element('schedule/schedule_build_yaxis', array('yaxis' => $orders, 'type' => 'order')); ?></div>
	<div id="time_line_day" class="hide"><?php #echo $this->element('schedule/schedule_day_view', array('yaxis' => $yaxis, 'date_selected' => $date_markers['date_selected'])); ?></div>
	<div id="time_line_week" class="hide"><?php #echo $this->element('schedule/schedule_week_view', array('yaxis' => $yaxis, 'date_markers' => $date_markers)); ?></div>
	<div id="time_line_month" class="hide"><?php #echo $this->element('schedule/schedule_month_view', array('yaxis' => $yaxis, 'date_markers' => $date_markers)); ?></div>
</div>