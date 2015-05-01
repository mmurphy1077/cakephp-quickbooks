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
?>
<div class="grid">
	<div class="col-1of4">
		<div id="dashboard_content_container_right_border">
			<div id="dashboard_content_container_right_curve">
				<div class="widget left bg">
					<h3><?php echo __('Statistics stats'); ?></h3>
					<?php echo $this->Form->create(); ?>
					<?php #echo $this->Form->input('view_by', array('type' => 'radio', 'options' => $optionsStats)); ?>
					<div class="grid flat">
						<div class="col-2of5">
							<?php echo $this->Form->input('Start', array('div' => 'input text short', 'value' => '3/1/2013')); ?>
						</div>
						<div class="col-2of5">
							<?php echo $this->Form->input('End', array('div' => 'input text short', 'value' => '3/15/2013')); ?>
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
							<span class="value">$245,642.00</span>
						</li>
						<li>
							<span class="label"><?php echo __('Revenues (Invoiced)'); ?></span>
							<span class="value">$185,980</span>
						</li>
						<li class="div">
							<span class="label"><?php echo __('Expenses (Labor)'); ?></span>
							<span class="value">$65,432.00</span>
						</li>
						<li>
							<span class="label"><?php echo __('Expenses (Materials)'); ?></span>
							<span class="value">$22,334.00</span>
						</li>
						<li>
							<span class="label"><?php echo __('Expenses (Equipment Rentals)'); ?></span>
							<span class="value">$17,325.00</span>
						</li>
						<li>
							<span class="label"><b><?php echo __('Total Expenses'); ?></b></span>
							<span class="value">$105,091.00</span>
						</li>
						<li class="div">
							<span class="label"><?php echo __('Net Profit'); ?></span>
							<span class="value">$80,889.00</span>
						</li>
						<li>
							<span class="label"><?php echo __('Profit Margin'); ?></span>
							<span class="value">43.49%</span>
						</li>
						<li>
							<span class="label"><?php echo __('Target Profit Margin'); ?></span>
							<span class="value">45.00%</span>
						</li>
						<li class="div">
							<span class="label"><?php echo __('Lead-To-Order Ratio'); ?></span>
							<span class="value">75.89%</span>
						</li>
						<li>
							<span class="label"><?php echo __('Hours Logged'); ?></span>
							<span class="value">324</span>
						</li>
					</ul>
					<br /><br />
					<h3 class="left"><?php echo __('Job Site Map'); ?></h3>
					<iframe class="map dashboard" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?f=q&amp;source=s_q&amp;hl=en&amp;geocode=&amp;q=Portland,+OR&amp;aq=0&amp;oq=portland&amp;sll=33.788026,-118.15611&amp;sspn=0.303025,0.676346&amp;ie=UTF8&amp;hq=&amp;hnear=Portland,+Multnomah,+Oregon&amp;ll=45.523452,-122.676207&amp;spn=0.015966,0.042272&amp;t=m&amp;z=14&amp;output=embed"></iframe>
					<?php echo $this->Html->link(__('Enlarge'), '#', array('class' => 'button medium blue right')); ?>
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
<!-- 
<div id="so-130301" class="so-cluetip">
	<ul class="stats activity">
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('said'); ?> &mdash; &quot;This place is in bad shape. I'm going to be here a while.&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('completed job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('logged'); ?> 2.5 <?php echo __('hours on job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:26am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('checked into the job site for SO'); ?> 130327</span>
			<span class="value">Tuesday, March 26 7:56am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('checked out the following equipment'); ?> &mdash; Drill (2), Cable (1000ft), Basic Toolbox (1)</span>
			<span class="value">Tuesday, March 26 7:03am</span>
		</li>
		<li>
			<span class="label"><b>A Cohen</b> <?php echo __('scheduled technician'); ?> C Kabob <?php echo __('to SO'); ?> 130327</span>
			<span class="value">Monday, March 25 2:43pm</span>
		</li>
	</ul>
</div>

<div id="so-130210" class="so-cluetip">
	<ul class="stats activity">
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('said'); ?> &mdash; &quot;This place is in bad shape. I'm going to be here a while.&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('completed job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('logged'); ?> 2.5 <?php echo __('hours on job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:26am</span>
		</li>
	</ul>
</div>

<div id="so-130203" class="so-cluetip">
	<ul class="stats activity">
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('completed job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('logged'); ?> 2.5 <?php echo __('hours on job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:26am</span>
		</li>
	</ul>
</div>

<div id="so-130201" class="so-cluetip">
	<ul class="stats activity">
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('said'); ?> &mdash; &quot;This place is in bad shape. I'm going to be here a while.&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('completed job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('checked into the job site for SO'); ?> 130327</span>
			<span class="value">Tuesday, March 26 7:56am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('checked out the following equipment'); ?> &mdash; Drill (2), Cable (1000ft), Basic Toolbox (1)</span>
			<span class="value">Tuesday, March 26 7:03am</span>
		</li>
		<li>
			<span class="label"><b>A Cohen</b> <?php echo __('scheduled technician'); ?> C Kabob <?php echo __('to SO'); ?> 130327</span>
			<span class="value">Monday, March 25 2:43pm</span>
		</li>
	</ul>
</div>

<div id="so-130176" class="so-cluetip">
	<ul class="stats activity">
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('said'); ?> &mdash; &quot;This place is in bad shape. I'm going to be here a while.&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('completed job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('logged'); ?> 2.5 <?php echo __('hours on job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:26am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('checked into the job site for SO'); ?> 130327</span>
			<span class="value">Tuesday, March 26 7:56am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('checked out the following equipment'); ?> &mdash; Drill (2), Cable (1000ft), Basic Toolbox (1)</span>
			<span class="value">Tuesday, March 26 7:03am</span>
		</li>
		<li>
			<span class="label"><b>A Cohen</b> <?php echo __('scheduled technician'); ?> C Kabob <?php echo __('to SO'); ?> 130327</span>
			<span class="value">Monday, March 25 2:43pm</span>
		</li>
	</ul>
</div>

<div id="so-130153" class="so-cluetip">
	<ul class="stats activity">
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('said'); ?> &mdash; &quot;This place is in bad shape. I'm going to be here a while.&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('completed job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:28am</span>
		</li>
		<li>
			<span class="label"><b>C Kabob</b> <?php echo __('logged'); ?> 2.5 <?php echo __('hours on job item'); ?> 001 &mdash; &quot;Diagnose power outage&quot;</span>
			<span class="value">Tuesday, March 26 10:26am</span>
		</li>
	</ul>
</div>
 -->