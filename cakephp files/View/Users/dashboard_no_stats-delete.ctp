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
?>
<div class="grid">
	<div class="col-1of1 white">
		<div id="dashboard_content_container_left_border">
			<div id="dashboard_content_container_left_curve" class="white">
			<?php #echo $this->element('header_tabs', array('separator' => '|')); ?>
				<div class="toggle_page_container default" id="toggle_page_container_jobs_tasks">
					<div class="widget center">
						<br />
						<?php echo $this->element('dashboard/date_controller', array('date_markers' => $date_markers)); ?>
					</div>
					<?php echo $this->element('dashboard/dashboard_today_summary', array('due_today' => $quotes_due_today, 'model' => 'quote', 'date_markers' => $date_markers)); ?>
					<?php echo $this->element('dashboard/dashboard_today_summary', array('due_today' => $orders_due_today, 'model' => 'order', 'date_markers' => $date_markers)); ?>
					<?php #echo $this->element('dashboard/dashboard_quote_summary', array('quotes' => $quotes)); ?>
					<?php #echo $this->element('dashboard/dashboard_job_summary', array('due_today' => $quotes_due_today)); ?>
				</div>
				<div class="toggle_page_container" id="toggle_page_container_messages">
					<h4>Page Under Construction</h4>
				</div>
				<div class="toggle_page_container" id="toggle_page_container_activity_alerts">
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