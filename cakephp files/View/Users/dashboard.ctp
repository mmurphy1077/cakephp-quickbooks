<?php
$this->set('title_for_layout','Dashboard'); 
echo $this->element('js/jquery', array('ui' => 'cluetip'));
echo $this->element('js'.DS.'jquery', array('ui' => 'datepicker'));
$this->Html->script('jquery/jquery.jeditable', false);
$this->Html->script('creationsite/dashboard', false);
echo $this->element('js'.DS.'maps');

$optionsStats = array(
	0 => 'Today',
	1 => 'Month-To-Date',
	2 => 'Current Quarter',
	3 => 'Year-To-Date',
);

if(!isset($page)) {
	$page = 'default';
}
$permissions = $this->Permission->getPermissions($__permissions);
?>
<div class="grid">
	<div class="col-1of4">
		<div id="dashboard_content_container_right_border">
			<div id="dashboard_content_container_right_curve">
				<div id="dashboard-stats" class="widget bg">
					<?php if($permissions['enable_stats'] == 1) : ?>
						<h3><?php echo __('Statistics stats'); ?></h3>
						<?php echo $this->Form->create(); ?>
						<?php #echo $this->Form->input('view_by', array('type' => 'radio', 'options' => $optionsStats)); ?>
						<div class="grid flat">
							<div class="col-2of5">
								<?php echo $this->Form->input('Start', array('div' => 'input text short date', 'id' => 'datepicker_from', 'value' => date('m/d/Y', strtotime($date_markers['start'])))); ?>
							</div>
							<div class="col-2of5">
								<?php echo $this->Form->input('End', array('div' => 'input text short date', 'id' => 'datepicker_to', 'value' => date('m/d/Y', strtotime($date_markers['end'])))); ?>
							</div>
							<div class="col-1of5">
								<?php echo $this->Form->submit(__('Go'), array('id' => 'statistics_go')); ?>
							</div>
						</div>		
						<br /><br />
						<?php echo $this->Form->end(); ?>
						<ul class="stats">
							<li>
								<span class="label"><?php echo __('Active Estimates'); ?></span>
								<span class="value">$<?php echo number_format($stats['active_quotes']['total'], 2); ?></span>
							</li>
							<li>
								<span class="label"><?php echo __('Revenues (Invoiced)'); ?></span>
								<span class="value">$<?php echo number_format($stats['invoice']['invoiced_amount_in_range'], 2); ?></span>
							</li>
							<li class="div">
								<span class="label"><?php echo __('Expenses (Labor)'); ?></span>
								<span class="value">$<?php echo number_format($stats['labor']['expense_total_amount'], 2); ?></span>
							</li>
							<li>
								<span class="label"><?php echo __('Expenses (Materials)'); ?></span>
								<span class="value">$<?php echo number_format($stats['material']['material_amount_actual'], 2); ?></span>
							</li>
							<li>
								<span class="label"><b><?php echo __('Total Expenses'); ?></b></span>
								<span class="value">$<?php echo number_format(($stats['labor']['expense_total_amount'] + $stats['material']['material_amount_actual']), 2); ?></span>
							</li>
							<li class="div">
								<span class="label"><?php echo __('Net Profit'); ?></span>
								<span class="value">$<?php echo number_format($stats['invoice']['invoiced_amount_in_range'] - ($stats['labor']['expense_total_amount'] + $stats['material']['material_amount_actual']), 2); ?></span>
							</li>
							<li>
								<span class="label"><?php echo __('Profit Margin'); ?></span>
								<span class="value">
								<?php 
								/*
								$net_profit = $stats['invoice']['invoiced_amount_in_range'] - ($stats['labor']['expense_total_amount'] + $stats['material']['material_amount_actual']);
								if(!empty($stats['invoice']['invoiced_amount_in_range']) && $stats['invoice']['invoiced_amount_in_range'] > 0) {
									echo number_format(($net_profit/$stats['invoice']['invoiced_amount_in_range'])*100, 0) . '%';
								} else {
									echo '-';
								} 
								
								Markup Percentage = (Sales Price Ð Unit Cost)/Unit Cost = ($133.33 Ð $100)/$100 = 33.3%
								*/
								$sale_price = $stats['invoice']['invoiced_amount_in_range'];
								$unit_cost = ($stats['labor']['expense_total_amount'] + $stats['material']['material_amount_actual']);
								if(!empty($unit_cost) && $unit_cost > 0) {
									echo number_format((($sale_price-$unit_cost)/$unit_cost)*100, 2) . '%';
								} else {
									echo '-';
								}
								?>
								</span>
							</li>
				 			<?php 
				 			$data = $this->Session->read('Application.settings');
				 			if(array_key_exists('margin', $data['ApplicationSetting']) && !empty($data['ApplicationSetting']['margin']) && $data['ApplicationSetting']['margin'] > 0) : ?>
							<li>
								<span class="label"><?php echo __('Target Profit Margin'); ?></span>
								<span class="value"><?php echo number_format($data['ApplicationSetting']['margin'], 2); ?>%</span>
							</li>
							 <?php endif; ?>
							<!-- 
							<li class="div">
								<span class="label"><?php echo __('Lead-To-Order Ratio'); ?></span>
								<span class="value">??</span>
							</li>
							 -->
							<li class="div">
								<span class="label"><?php echo __('Labor Hours Approved'); ?></span>
								<span class="value"><?php echo number_format($stats['labor']['total_time'], 2); ?></span>
							</li>
						</ul>
						<br /><br />
					<?php endif; ?>
					<h3 class="left"><?php echo __('Job Site Map'); ?></h3>
					<?php #echo $this->Html->link(__('Enlarge'), '#', array('class' => 'button medium blue right')); ?>
					<?php 
					$primary = $this->Session->read('Application.address.primary');
					$address = null;
					if(!empty($primary)) {
						$address = $primary['Address']['line1'].', '.$primary['Address']['line2'].', '.$primary['Address']['city'].', '.$primary['Address']['st_prov'];
					}
					echo $this->GoogleMap->map(array(
						'id' => 'map_canvas',
						'width' => '100%',
						'height' => '632px',
						'zoom' => 10,
						'type' => 'ROADMAP',
						'localize' => false,
						'address' => $address,
						'windowText' => $this->Session->read('Application.settings.ApplicationSetting.company_name'),
						//'markerIcon' => $this->GoogleMap->getMarker('home', null),
						/*
						 * 'id' => 'map_canvas',
						    'width' => '800px',
						    'height' => '800px',
						    'style' => '',
						    'zoom' => 7,
						    'type' => 'HYBRID',
						    'custom' => null,
						    'localize' => true,
						    'latitude' => 40.69847032728747,
						    'longitude' => -1.9514422416687,
						    'address' => '1 Infinite Loop, Cupertino',
						    'marker' => true,
						    'markerTitle' => 'This is my position',
						    'markerIcon' => 'http://google-maps-icons.googlecode.com/files/home.png',
						    'markerShadow' => 'http://google-maps-icons.googlecode.com/files/shadow.png',
						    'infoWindow' => true,
						    'windowText' => 'My Position'
						 */
					)); ?>
					<?php 
					$count = 0;
					if(!empty($schedules)) {
						foreach($schedules as $order) { 
							if(!empty($order['Order']['Address'])) {
								$count = $count + 1;
								$options = array(
										'windowText' =>
										'<div class="google_marker_content">' .
										'<p>' .
										'<b>'.__('Company: ').'</b>'.str_replace ("'", "\'", $order['Order']['customer_name']).'<br />'.
										'<b>'.__('Contact: ').'</b>'.str_replace ("'", "\'", $order['Order']['contact_name']).'<br />'.
										'</p>' .
										'<p>' .
										#str_replace ("'", "\'", $this->Web->humanName($worker['Worker'], 'first_initial')).'<br />'.
										#date('g:i a' , strtotime($order['Order']['time_start_display'])). __(' to ') . date('g:i a' , strtotime($order['Order']['time_end_display'])) . '<br />'.
										'<p>' .
										'<p>' .
										$order['Order']['Address']['line1'].'<br />'.
										$order['Order']['Address']['city'].', '.$order['Order']['Address']['st_prov'].' '.$order['Order']['Address']['zip_post'] .
										'<p>' .
										'</div>',
										'markerIcon' => $this->GoogleMap->getMarker('order', $order['Order']),
								);
	
								if(!empty($order['Order']['Address']['lat']) && $order['Order']['Address']['lng']) {
									echo $this->GoogleMap->addMarker('map_canvas', $count, array('latitude' => $order['Order']['Address']['lat'], 'longitude' => $order['Order']['Address']['lng']), $options);
								} else {
									$map_address = $order['Order']['Address']['line1'] . ', ' . $order['Order']['Address']['line2'] . ', ' . $order['Order']['Address']['city'] . ', ' . $order['Order']['Address']['st_prov'];
									echo $this->GoogleMap->addMarker('map_canvas', $count, $map_address, $options);
								}
							}
						}
					}
					?>
					<br /><br />&nbsp;
				</div>
			</div>
		</div>
	</div>
	<div class="col-3of4 white">
		<div id="dashboard_content_container_left_border">
			<div id="dashboard_content_container_left_curve" class="white">
				<?php echo $this->element('header_tabs', array('separator' => '|')); ?>
				<div class="grid clear">
					<?php 
					switch($page) :
						case 'view-message' :
						case 'messages' : ?>
						<div class="col-1of1">
							<div class="widget center">
								<h3 class="inline left">Messages</h3>
								<?php echo $this->element('communication/message_index', array('result' => null)); ?>	
							</div>
						</div>
					<?php 	break;
						case 'activity' : ?>
						<div class="col-1of1">
							<div class="widget center">
								<div class="fieldset-wrapper available_action_container">
									<h3>Activity Log</h3>	
									<?php echo $this->element('activity_log_index', array('result' => null)); ?>									  
								</div>
								<div class="fieldset-wrapper available_action_container">
									<h3>Alerts</h3>	
									<?php echo $this->element('alerts_index', array('result' => null)); ?>									  
								</div>
							</div>
						</div>
					<?php 	break;
						case 'default' : 
						default : 			?>
						<div class="col-2of3">
							<div class="toggle_page_container default" id="toggle_page_container_jobs_tasks">
								<?php echo $this->element('dashboard/dashboard_job_summary', array('orders' => $orders_display)); ?>
							</div>
						</div>
						<div class="col-1of3">
							<div class="widget right">
								<?php #echo $this->Html->link(__('Show More'), '#', array('class' => 'button medium blue right')); ?>
								<h3 class="left"><?php echo __('Unread Messages'); ?></h3>
								<?php if(!empty($unreadMessages)) : ?>
								<ul class="stats activity">
									<?php foreach($unreadMessages as $message) : ?>
									<li>
										<span class="label"><b><?php echo $this->Web->humanName($message['Sender'], 'first_initial')?></b> 
										<?php 
										$attachments = false;
										$link = array('controller' => 'messages', 'action' => 'view', $message['Message']['id'], 'users');
										$intro = 'replied to message'; 
										if(empty($message['Message']['parent_id'])) {
											$intro = 'sent message';
										} 
										if(!empty($message['Message']['attachments']) || !empty($message['Message']['attachments_system_docs'])) {
											$attachments = true;	
										}
										echo $this->Html->link($intro, $link); ?><br /><?php echo $message['Message']['subject']; ?>
										<?php if ($attachments) : ?>
										<br />(attachments included)
										<?php endif; ?>
										</span>
										<span class="value"><?php echo date('l, F j g:ia', strtotime($message['Message']['created'])); ?></span>
									</li>
									<?php endforeach; ?>
								</ul>
								<?php else : ?>
								<div class="clear">
									<?php echo $this->element('info', array('content' => array(
										'no_items',
									))); ?>
								</div>
								<?php endif; ?>
							</div>
							<div class="widget right">
								<?php #echo $this->Html->link(__('Show More'), array('controller' => 'pages', 'action' => 'display', 'schedule'), array('class' => 'button medium blue right')); ?>
								<h3 class="left"><?php echo __('Schedule'); ?></h3>
								<h4><?php echo __('Today'); ?></h4>
								<?php if(!empty($schedules)) : ?>
								<ul class="stats activity">
									<?php foreach($schedules as $order) : ?> 
									<li>
										<span class="label">
											<p>
												<?php echo __('#'); ?> <b><?php echo $this->Html->link($order['Order']['sid'], array('controller' => 'orders', 'action' => 'schedules', $order['Order']['id'])); ?></b>
												 - <?php echo $order['Order']['customer_name'] ?><br />
												
												<?php echo $this->Web->address($order['Order']['Address'], false, ', ', false, false, false); ?>
											</p>
											<?php foreach($order['Schedule'] as $schedule) : ?>
											<p>
												Start <?php echo date('M jS', strtotime($schedule['Schedule']['date_session_start'])); ?> at <?php echo date('g:ia', strtotime($schedule['Schedule']['date_session_start'])); ?> for <b><?php echo $this->Html->link($schedule['Schedule']['duration_in_seconds']/3600 . ' hours', array('controller' => 'orders', 'action' => 'edit_schedule', $schedule['Schedule']['id'])); ?></b><br />
												<?php 
												$string = '';
												foreach($schedule['ScheduleResource'] as $worker)  {
													if(!empty($string)) {
														$string = $string . ', ';
													}
													$string = $string . '<b>' . $this->Web->humanName($worker['User']) . '</b>';
												} ?>
												<?php echo __('is assigned to') . ' ' . $string; ?> 
											</p>
											<?php endforeach;?>
										</span>
									</li>
									<?php endforeach;?>
								</ul>
								<?php else : ?>
								<div class="clear">
									<?php echo $this->element('info', array('content' => array(
										'no_items',
									))); ?>
								</div>
								<?php endif; ?>
							</div>
						</div>
					<?php 
					endswitch;
					?>
				</div>
			</div>
		</div>
	</div>
</div>