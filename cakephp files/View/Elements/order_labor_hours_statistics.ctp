<?php //// Only pertains to pages for a selected Order ////?>
<div id="job_status_container">
	<?php 
	$time_scheduled = 0;
	$perc = 0;
	$path = '';
	if($order['Order']['total_estimated_minutes'] > 0) {
		$perc = intval(round(($order['Order']['total_labor_approved']/($order['Order']['total_estimated_minutes']/60))*100,-1));
	} else {
		if($order['Order']['total_labor_approved'] > 0) {
			$perc = 100;
		}
	}
	if($perc > 100) {
		$perc = 100;
		$path = 'red/';
	} else if($perc >= 90) {
		$path = 'red/';
	} else if($perc >= 80) {
		$path = 'orange/';
	}
	?>
	<?php echo $this->Html->image('pie/'.$path.$perc.'.png', array('class' => 'status_pie', 'id' => 'status_pie')); ?>
	<p><?php echo __('You have approved <b><span id="scheduled_hours">'.number_format(($order['Order']['total_labor_approved']), 2).'</span></b> of an estimated <b>'.$order['Order']['total_estimated_hours'].'</b> hours.'); ?></p>
</div>