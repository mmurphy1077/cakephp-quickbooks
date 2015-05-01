<?php 
echo $this->Html->script('creationsite/schedule', array('inline' => false));
echo $this->element('js'.DS.'jquery', array('ui' => 'datepicker'));
echo $this->Html->css(Configure::read('Settings.theme_path').'schedule');
?>
<div id="schedule_container_orders">
	<?php echo $this->element('schedule/schedule_single_order_display'); ?>
</div>
<div class="hidden-sm hidden-md hidden-lg"></div>
<div class="hidden-xs">
	<div class="widget center">
		<?php 
		#echo $this->Form->hidden('order_id', array('value' => $order[''], 'id' => 'order_id'));
		echo $this->Form->hidden('device_display', array('value' => 'mobile', 'id' => 'device_display')); 
		echo $this->Form->hidden('display', array('value' => $display_period_type, 'id' => 'display')); 
		echo $this->Form->hidden('yaxis', array('value' => $yaxis_view_type, 'id' => 'yaxis_view_type'));
		echo $this->Form->hidden('date_selected', array('value' => $date_markers['date_selected'], 'id' => 'date_selected')); 
		echo $this->Form->hidden('table_start_day', array('value' => $date_markers['table_start_day'], 'id' => 'table_start_day'));
		echo $this->Form->hidden('table_end_day', array('value' => $date_markers['table_end_day'], 'id' => 'table_end_day'));
		echo $this->Form->hidden('workday_start', array('value' => SCHEDULE_WORKDAY_START, 'id' => 'workday_start'));
		echo $this->Form->hidden('workday_end', array('value' => SCHEDULE_WORKDAY_END, 'id' => 'workday_end'));
		echo $this->Form->hidden('include_weekends', array('value' => 0, 'id' => 'include_weekends')); 	
		
		if(!isset($selected_order)) {
			$selected_order = null;
		}
		if(!isset($selected_schedule)) {
			$selected_schedule = null;
		}
		echo $this->Form->hidden('selected_order', array('value' => $selected_order, 'id' => 'selected_order'));
		echo $this->Form->hidden('selected_schedule', array('value' => $selected_schedule, 'id' => 'selected_schedule'));
		?>
		<div class="right">
			<?php if(!isset($order)) { $order = null; }
			echo $this->element('schedule/schedule_navigation', array('type' => $display_period_type, 'location' => 'schedules', 'show_map_option' => false, 'show_month_option' => true, 'order' => $order, 'date' => $date_markers['date_selected'])); ?>
		</div>
		<?php echo $this->element('schedule/date_controller_day', array('date_markers' => $date_markers, 'display' => $display_period_type)); ?>
		<div id="ajax-container"></div>
	</div>
	<div id="schedule-table-container" class="clear left mobile">
		<div id="schedule-loader"><?php echo $this->Html->image('loader-large.gif', array('id' => 'order-loader-image')); ?></div>
		<table id="schedule_container" class="nohover">
			<tr>
				<td id="schedule_container_graph">
					<div id="schedule_container_graph">
						<?php 
						#echo $this->Html->image('icon-arrow-right.png', array('id' => 'toggle-schedule-columns', 'class' => 'right'));  
						$order_id = null; 
						echo $this->element('schedule/schedule_table', array('employees' => $employees, 'order_id' => $order_id, 'date_selected' => $date_markers['date_selected'], 'style' => 'mobile'));
						?>
					</div>
					<div id="schedule-table-sched-summary-container" class="hide">
						<div id="schedule-summary-loader"><?php echo $this->Html->image('loader-large.gif', array('id' => 'schedule-summary-image')); ?></div>
					</div>
				</td>
			</tr>
		</table>
	</div>
</div>