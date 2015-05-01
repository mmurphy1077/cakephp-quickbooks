<?php $permissions = $this->Permission->getPermissions($__permissions); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta name="viewport" content="width=600, initial-scale=1">
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?> ::
			<?php echo Configure::read('Public.title') ?> ::
			<?php echo $this->Session->read('Application.settings.ApplicationSetting.company_name') ?>
		</title>
		<script type="text/javascript">var myBaseUrl = '<?php echo $this->Html->url('/'); ?>';</script>
		<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('grid/reset.min');
		echo $this->Html->css('grid/grid.min');
		echo $this->Html->css('bootstrap/bootstrap.min');
		echo $this->Html->css('fonts');
		echo $this->Html->css('jquery/ui/blue1/jquery-ui-1.8.11.custom');
		echo $this->Html->css('base');
		echo $this->Html->css('app/base');
		echo $this->Html->css('forms');
		echo $this->Html->css('app/forms');
		echo $this->Html->css('active_timers');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		
		echo $this->Html->script('https://maps.google.com/maps/api/js?sensor=true');
		echo $this->Html->script('jquery/jquery-1.11.1.min');
		echo $this->Html->script('jquery/jquery.form.min', array('inline' => false));
#		echo $this->Html->script('jquery/ui/jquery-ui.min');
		echo $this->Html->script('jquery/ui/jquery.ui.core');
		echo $this->Html->script('jquery/ui/jquery.ui.widget');
		echo $this->Html->script('creationsite/business360');
		echo $this->Html->script('creationsite/search');
		echo $this->Html->script('creationsite/dropdown');
		echo $this->Html->script('creationsite/date.format');
		echo $this->element('js'.DS.'jquery', array('ui' => 'buttons'));
		echo $this->element('js'.DS.'jquery', array('ui' => 'dialog'));
		echo $this->Html->script('creationsite/active.timer');
		echo $this->fetch('script');
		?>
	</head>
	<body>
		<?php echo $this->element('nav_utilities'); ?>
		<div id="wrapper" class="<?php #echo $__browser_view_mode['browser_view_mode']; ?>">
			<div class="grid" id="header">
				<div class="col-1of4 title">
					<?php echo $this->Html->image('logo-360.png'); ?>
					<h1>
						<span class="title1"><?php echo Configure::read('Public.title1'); ?></span>
						<span class="title2"><?php echo Configure::read('Public.title2'); ?></span>
						<span class="subtitle"><?php echo $this->Session->read('Application.settings.ApplicationSetting.company_name'); ?></span>
					</h1>
				</div>
				<div class="logo-header" id="logo-header"></div>
				<div class="right location <?php echo $__browser_view_mode['browser_view_mode']; ?>">
					<h2 class="<?php echo $__browser_view_mode['browser_view_mode']; ?>"><?php echo __($title_for_layout); ?></h2>
					<div id="advanced-search-container" class="<?php echo $__browser_view_mode['browser_view_mode']; ?>">
						<?php echo $this->Form->create('Search', array('url' => array('controller' => 'searches', 'action' => 'search'))); ?>
						<?php echo $this->Form->submit(__('Go')); ?>
						<div id="keyword_search">
							<?php $search = $this->Session->read('Searches.data.Search.keyword'); ?>
							<?php echo $this->Form->input('keyword', array('label' => false, 'value' => __($search))); ?>
							<?php if(empty($search)) : ?>
								<div id="background" class><?php echo __('Keyword Search'); ?></div>
							<?php endif; ?> 
						</div>
						<?php echo $this->Form->end(); ?>
					</div>
				</div>
			</div>
			<?php echo $this->element('nav_main_' . $__browser_view_mode['browser_view_mode'], array('location' => 'header')); ?>
			<div id="body">
				<div id="page-loader"><?php echo $this->Html->image('loader-large.gif', array('id' => 'order-loader-image')); ?></div>
				<?php echo $this->element('screen_messages'); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
			<div id="footer" class="<?php echo $__browser_view_mode['browser_view_mode']; ?>">
				<div class="actions">
					<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
				</div>
				<div class="utility">
					<div class="credits">
						&copy; <?php echo date('Y').' '.$this->Session->read('Application.settings.ApplicationSetting.company_name').' '.__('All rights reserved.'); ?><br />
						<span class="cs">&rarr; <a href="http://www.creationsite.com/" target="_blank">Creationsite, Inc.</a></span>
					</div>
					<?php #echo $this->element('nav_main_' . $__browser_view_mode['browser_view_mode'], array('location' => 'footer')); ?>
				</div>
			</div>
		</div>
		<?php 
		/**
		 * Comments
		 */
		?>
		<div id="comment-container-fixed">
			<div id="button-comment-fixed" class="slider-activate icon-print-active left">&nbsp;</div>
			<div class="content slider-activate"><b>Comments</b></div>
			<div id="top-return" class="right"><div class="rotate90">&#8677;</div> Top</div>
		</div>
		<?php 
		/**
		 * TIMERS 
		 */
		#echo $this->element('timers_active', array('data' => $__active_timers));
		?>
		<?php 
		/**
		 * Cluetip for Schedule Status
		 */
		?>
		<div id="cs-cluetip-container" class="cs-cluetip-container hide">
			<div id="cs-cluetip-content" class="cs-cluetip-content"></div>
			<?php echo $this->Html->image('icon-close.png', array('id' => 'cs-cluetip-action-close')); ?>
		</div>
		<div id="schedule-status-cluetip-container" class="schedule-status-cluetip-container hide">
			<?php echo $this->Form->hidden('schedule_status_sched_id', array('id' => 'schedule_status_sched_id')); ?>
			<?php echo $this->Form->hidden('schedule_status_order_id', array('id' => 'schedule_status_order_id')); ?>
			<?php echo $this->Form->hidden('schedule_status_status', array('id' => 'schedule_status_status')); ?>
			<div id="status-container">
				<?php if(isset($schedule_statuses)) : ?>
				<?php foreach($schedule_statuses as $key=>$schedule_status) : ?>
				<?php echo $this->Form->input('schedule_status-' . $key, array('type' => 'checkbox', 'label' => $schedule_status, 'class' => 'schedule_status_checkbox schedule_status_checkbox_' . $key, 'id' => 'schedule_status_checkbox_' . $key, 'div' => array('class' => 'schedule-checkbox'))); ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div id="action-container">
				<?php echo $this->Html->link('delete schedule', '#', array('class' => 'left delete_schedule_from_table'))?><br />
				<?php echo $this->Html->link('open Job <span class="small light">(in new window)</span>', '#', array('class' => 'left jump_to_order_from_table', 'escape' => false))?>
			</div>
			<div id="message-container"></div>
		</div>
		<div id="order-schedule-status-cluetip-container" class="order-schedule-status-cluetip-container hide">
			<?php echo $this->Form->hidden('schedule_order_status_order_id', array('id' => 'schedule_order_status_order_id')); ?>
			<?php echo $this->Form->hidden('schedule_order_status_model', array('id' => 'schedule_order_status_model')); ?>
			<?php echo $this->Form->hidden('schedule_order_status_foreign_key', array('id' => 'schedule_order_status_foreign_key')); ?>
			<?php echo $this->Form->hidden('schedule_order_status', array('id' => 'schedule_order_status')); ?>
			<div id="status-container">
				<?php if(isset($schedule_statuses)) : ?>
				<?php foreach($schedule_statuses as $key=>$schedule_status) : ?>
				<?php echo $this->Form->input('order_schedule_status-' . $key, array('type' => 'checkbox', 'label' => $schedule_status, 'class' => 'order_schedule_status_checkbox order_schedule_status_checkbox_' . $key, 'id' => 'order_schedule_status_checkbox_' . $key, 'div' => array('class' => 'schedule-checkbox'))); ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>
			<div id="action-container">
				<?php echo $this->Html->link('delete all schedules', '#', array('class' => 'left delete_schedules_for_order'))?><br />
				<?php echo $this->Html->link('open Job <span class="small light">(in new window)</span>', '#', array('class' => 'left jump_to_order_from_order', 'escape' => false))?>
			</div>
			<div id="message-container"></div>
		</div>
	</body>
</html>