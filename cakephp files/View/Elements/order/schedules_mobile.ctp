<div id="mobile-menu-container" class="hidden-md hidden-lg hidden">
<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'list')); ?>
</div>
<div id="mobile-content-container">
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-sm-12 col-sm-6 col-md-6 col-lg-6">
				<b>3 Schedule:</b> Select workers, dates and tasks using arrows to the right. Select Save. Note: Scheduling is NOT required to track labor and materials.
				<div class="visible-xs-block"><br /></div>
			</div>	
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="title-buttons right">
					<?php echo $this->element('schedule/schedule_order_statistics', array('order' => $order, 'permissions' => $permissions, 'style' => 'mobile')); ?>
				</div>
			</div>					  
		</div>
	</fieldset>
	<?php 
	if(!empty($schedules)) {	
		echo $this->element('order/schedule_index', array('schedules' => $schedules, 'permissions' => $permissions, 'style' => 'mobile'));
	} else {
		 
	} ?>
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'tab')); ?>
<?php $this->end(); ?>