<div class="clear">
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8">
				<div class="left"><b>Available Actions:</b></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $this->element('tabs_order_job', array('tab' => ORDER_PRODUCTION_SCHEDULE, 'order' => $order)); ?>			
			</div>	
			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<div class="title-buttons">
					<div class="visible-sm-block"><br /></div>
					<?php echo $this->element('schedule/schedule_order_statistics', array('order' => $order, 'permissions' => $permissions)); ?>
				</div>
			</div>					  
		</div>
	</fieldset>
	<?php echo $this->element('schedule/main'); ?>
</div>

<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order', array('order' => $order, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>