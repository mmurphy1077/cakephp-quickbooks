<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<title>
			<?php echo $title_for_layout; ?> ::
			<?php echo Configure::read('Public.title') ?> ::
			<?php echo $this->Session->read('Application.settings.ApplicationSetting.company_name') ?>
		</title>
		<?php
		echo $this->Html->meta('icon');
		echo $this->Html->css('grid/reset.min');
		echo $this->Html->css('grid/grid.min');
		echo $this->Html->css('fonts');
		echo $this->Html->css('base');
		echo $this->Html->css('splash');
		echo $this->Html->css('forms');
		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->Html->script('jquery/jquery-1.7.1.min');
		echo $this->fetch('script');
		?>
	</head>
	<body>
		<div id="wrapper">
			<div class="grid" id="header">
				<div class="title">
					<?php echo $this->Html->image('logo-360.png'); ?>
					<h1>
						<span class="title1"><?php echo Configure::read('Public.title1'); ?></span>
						<span class="title2"><?php echo Configure::read('Public.title2'); ?></span>
						<span class="subtitle"><?php echo $this->Session->read('Application.settings.ApplicationSetting.company_name'); ?></span>
					</h1>
				</div>
			</div>
			<div id="body" class="splash">
				<?php echo $this->element('screen_messages', array('class' => 'static')); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
	</body>
</html>