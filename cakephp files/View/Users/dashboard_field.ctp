<?php
echo $this->element('js/jquery', array('ui' => 'cluetip'));
echo $this->element('js'.DS.'jquery', array('ui' => 'datepicker'));
$this->Html->script('jquery/jquery.jeditable', false);
$this->Html->script('creationsite/dashboard', false);

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
	<div class="col-1of1 white">
		<div id="dashboard_content_container_left_border">
			<div id="dashboard_content_container_left_curve" class="white">
				<div class="grid clear">
					<?php 
					switch($page) :
						case 'view-message' :
						case 'messages' : ?>
						<?php $this->set('title_for_layout','Messages'); ?>
						<div class="col-1of1">
							<div class="widget center">
								<h3 class="inline left">&nbsp;<!-- Messages --></h3>
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
								<?php echo $this->element('dashboard/dashboard_today_summary', array('due_today' => $quotes_due_today, 'model' => 'quote', 'date_markers' => $date_markers)); ?>
								<?php echo $this->element('dashboard/dashboard_today_summary', array('due_today' => $orders_due_today, 'model' => 'order', 'date_markers' => $date_markers)); ?>
							</div>
						</div>
						<div class="col-1of3">
							<div class="widget right">
								<?php echo $this->Html->link(__('Show More'), '#', array('class' => 'button medium blue right')); ?>
								<h3 class="left"><?php echo __('Unread Messages'); ?></h3>
								<ul class="stats activity">
									<li>
										<span class="label"><b>J Marty</b> logged 3 hours on SO 130301</span>
										<span class="value">Tuesday, March 26 5:08pm</span>
									</li>
									<li>
										<span class="label"><b>J Squires</b> said: &quot;Why haven't we started the First Call job yet?&quot;</span>
										<span class="value">Tuesday, March 26 12:17pm</span>
									</li>
									<li>
										<span class="label"><b>L Palo</b> posted a new document &quot;Portland City Requirements.pdf&quot; to SO 130176</span>
										<span class="value">Monday, March 25 8:43am</span>
									</li>
								</ul>
							</div>
							<div class="widget right">
								<?php echo $this->Html->link(__('Show More'), array('controller' => 'pages', 'action' => 'display', 'schedule'), array('class' => 'button medium blue right')); ?>
								<h3 class="left"><?php echo __('Schedule'); ?></h3>
								<h4><?php echo __('Today'); ?></h4>
								<ul class="stats activity">
									<li>
										<span class="label">
											<p>
												&rarr; <?php echo __('SO'); ?> <b><?php echo $this->Html->link('130301', '#'); ?></b>
												<?php echo __('is assigned to'); ?> <b>K Smith</b><br />
												<b>9:00am &mdash; 12:00pm</b>
											</p>
											13565 SW Tualatin Sherwood Rd, #100<br />
											Sherwood, OR 97140
										</span>
									</li>
									<li>
										<span class="label">
											<p>
												&rarr; <?php echo __('SO'); ?> <b><?php echo $this->Html->link('130210', '#'); ?></b>
												<?php echo __('is assigned to'); ?> <b>K Smith</b><br />
												<b>8:30am &mdash; 1:00pm</b>
											</p>
											20736 Carmen Loop #140<br />
											Bend, OR 97702
										</span>
									</li>
									<li>
										<span class="label">
											<p>
												&rarr; <?php echo __('SO'); ?> <b><?php echo $this->Html->link('130203', '#'); ?></b>
												<?php echo __('is assigned to'); ?> <b>B Johnson</b><br />
												<b>9:00am &mdash; 12:30pm</b>
											</p>
											16169 SE 106th Ave.<br />
											Clackamas, OR 97015
										</span>
									</li>
								</ul>
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