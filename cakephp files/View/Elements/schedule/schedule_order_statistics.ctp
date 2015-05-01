<?php //// Only pertains to pages for a selected Order ////?>
<?php 
if(!isset($style)) {
	$style = 'standard';
} ?>
<div class="row">
	<div id="job_status_container" class="col-xs-8 col-sm-8 col-md-8 col-lg-8">
		<?php 
		$time_scheduled = 0;
		$perc = 0;
		if($order['Order']['total_estimated_minutes'] > 0) {
			$perc = intval(round(($order['Order']['total_scheduled_minutes']/$order['Order']['total_estimated_minutes'])*100,-1));
		} else {
			if($order['Order']['total_scheduled_minutes'] > 0) {
				$perc = 100;
			}
		}
		if($perc > 100) {
			$perc = 100;
		}
		$est = 0;
		if(!empty($order['Order']['total_estimated_hours'])) {
			$est = $order['Order']['total_estimated_hours'];
		} ?>
		<?php echo $this->Html->image('pie/'.$perc.'.png', array('class' => 'status_pie', 'id' => 'status_pie')); ?>
		<?php if($style == 'standard') : ?>
			<?php echo __('You have scheduled <b><span id="scheduled_hours">'.number_format(($order['Order']['total_scheduled_minutes']/60), 2).'</span></b> of an <br />estimated <b>'.$est.'</b> hours.'); ?>
		<?php else : ?>
			<?php echo 'Scheduled hours: <b><span id="scheduled_hours">'.number_format(($order['Order']['total_scheduled_minutes']/60), 2).'</span></b><br />Estimated hours: <b>'.$est.'</b>'; ?>
		<?php endif; ?>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
		<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
	</div>
</div>