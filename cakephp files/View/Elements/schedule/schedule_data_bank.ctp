<div id="data_bank">
	<div id="schedule_sessions">
		<?php if(!empty($scheduled_jobs)) : 
				unset($scheduled_jobs['Summary']);
			 	foreach($scheduled_jobs as $key=>$sessions) : 
					if(!empty($sessions['ScheduleSession'])) : 
						foreach($sessions['ScheduleSession'] as $session) : ?>
						<div id="schedule_<?php echo $session['id']; ?>">
							<div id="date_session" value="<?php echo $session['date_session']; ?>"></div>
							<div id="time_start" value="<?php echo $session['time_start']; ?>"></div>
							<div id="time_end" value="<?php echo $session['time_end']; ?>"></div>
							<div id="time_start_display" value="<?php echo $session['time_start_display']; ?>"></div>
							<div id="time_end_display" value="<?php echo $session['time_end_display']; ?>"></div>
							<div id="date_session_display" value="<?php echo $session['date_session_display']; ?>"></div>
							<div id="order_id" value="<?php echo $session['order_id']; ?>"></div>
							<div id="worker_id" value="<?php echo $session['worker_id']; ?>"></div>
							<div id="creator_id" value="<?php echo $session['creator_id']; ?>"></div>
							<div id="modified" value="<?php echo $session['modified']; ?>"></div>
							<div id="sections" value="<?php echo $session['sections']; ?>"></div>
							<div id="time_worked" value="<?php echo $session['time_worked']; ?>"></div>	
							<div id="tasks" value="<?php echo $session['ScheduleSessionsTask']; ?>"></div>	
						</div>
		<?php 			endforeach;
					endif;
				endforeach;	?>
		<?php endif; ?>
	</div>
	<div id="orders">
		<?php if(array_key_exists('Scheduled', $orders) && !empty($orders['Scheduled'])) : ?>
		<?php 	foreach($orders['Scheduled'] as $order) : ?>
					<div id="order_<?php echo $order['Order']['id']; ?>">
						<div id="name" value="<?php echo $order['Order']['name']; ?>"></div>
						<div id="customer_name" value="<?php echo $order['Order']['customer_name']; ?>"></div>
						<div id="contact_name" value="<?php echo $order['Order']['contact_name']; ?>"></div>
						<div id="contact_phone" value="<?php echo $order['Order']['contact_phone']; ?>"></div>
						<div id="description" value="<?php echo $order['Order']['description']; ?>"></div>
						<div id="total_estimated_hours" value="<?php echo $order['Order']['total_estimated_hours']; ?>"></div>
						<div id="total_estimated_minutes" value="<?php echo $order['Order']['total_estimated_minutes']; ?>"></div>
						<div id="total_scheduled_minutes" value="<?php echo $order['Order']['total_scheduled_minutes']; ?>"></div>
						<div id="total_scheduled_hours" value="<?php echo $order['Order']['total_scheduled_hours']; ?>"></div>
						<div id="line1" value="<?php echo $order['Address']['line1']; ?>"></div>
						<div id="line2" value="<?php echo $order['Address']['line2']; ?>"></div>
						<div id="city" value="<?php echo $order['Address']['city']; ?>"></div>
						<div id="st_prov" value="<?php echo $order['Address']['st_prov']; ?>"></div>
						<div id="zip_post" value="<?php echo $order['Address']['zip_post']; ?>"></div>
						<div id="country" value="<?php echo $order['Address']['country']; ?>"></div>
						<div id="order_line_items">
						<?php 
						if(!empty($order['OrderLineItem'])) :
							foreach ($order['OrderLineItem'] as $line_item) :  ?>
							<div id="<?php echo $line_item['id']; ?>" class="order">
								<div id="name" value="<?php echo $line_item['name']; ?>"></div>
								<div id="description" value="<?php echo $line_item['description']; ?>"></div>
							</div>
						<?php endforeach;
						endif?>
						</div>
					</div>
		<?php 	endforeach;	?>
		<?php endif;		?>
		<?php if(array_key_exists('UnScheduled', $orders) && !empty($orders['UnScheduled'])) : ?>
		<?php 	foreach($orders['UnScheduled'] as $order) : ?>
					<div id="order_<?php echo $order['Order']['id']; ?>">
						<div id="name" value="<?php echo $order['Order']['name']; ?>"></div>
						<div id="customer_name" value="<?php echo $order['Order']['customer_name']; ?>"></div>
						<div id="contact_name" value="<?php echo $order['Order']['contact_name']; ?>"></div>
						<div id="contact_phone" value="<?php echo $order['Order']['contact_phone']; ?>"></div>
						<div id="description" value="<?php echo $order['Order']['description']; ?>"></div>
						<div id="total_estimated_hours" value="<?php echo $order['Order']['total_estimated_hours']; ?>"></div>
						<div id="total_estimated_minutes" value="<?php echo $order['Order']['total_estimated_minutes']; ?>"></div>
						<div id="total_scheduled_minutes" value="<?php echo $order['Order']['total_scheduled_minutes']; ?>"></div>
						<div id="total_scheduled_hours" value="<?php echo $order['Order']['total_scheduled_hours']; ?>"></div>
						<div id="line1" value="<?php echo $order['Address']['line1']; ?>"></div>
						<div id="line2" value="<?php echo $order['Address']['line2']; ?>"></div>
						<div id="city" value="<?php echo $order['Address']['city']; ?>"></div>
						<div id="st_prov" value="<?php echo $order['Address']['st_prov']; ?>"></div>
						<div id="zip_post" value="<?php echo $order['Address']['zip_post']; ?>"></div>
						<div id="country" value="<?php echo $order['Address']['country']; ?>"></div>
						<div id="order_line_items">
						<?php 
						if(!empty($order['OrderLineItem'])) :
							foreach ($order['OrderLineItem'] as $line_item) :  ?>
							<div id="<?php echo $line_item['id']; ?>" class="order">
								<div id="name" value="<?php echo $line_item['name']; ?>"></div>
								<div id="description" value="<?php echo $line_item['description']; ?>"></div>
							</div>
						<?php endforeach;
						endif?>
						</div>
					</div>
		<?php 	endforeach;	?>
		<?php endif; ?>
	</div>
	<div id="employees">
		<?php if(!empty($employees)) :
				foreach($employees as $emp_key => $employee) :?>
		<div id="employee_<?php echo $emp_key; ?>"><div id="name" value="<?php echo $employee; ?>"></div></div>
		<?php 	endforeach;
			  endif; ?>
	</div>
</div>