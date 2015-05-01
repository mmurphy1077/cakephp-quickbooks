<?php 
echo $this->Html->css('jquery/jquery.time.circles');
echo $this->Html->script('jquery/jquery.time.circles');
echo $this->Html->script('creationsite/time.circles.init');
$active = 'hide';
if(!empty($data)) {
	$active = '';
}
?>
<div id="active-timer-container" class="<?php echo $active; ?>">
	<div id="active-timer-toggle-container">
		<div id="container-open"><?php echo $this->Html->image('icon-arrow-down.png'); ?></div>
		<div id="container-closed" class="hide"><?php echo count($data); ?></div>
	</div>
	<?php
	if (!empty($data)) :
		foreach ($data as $timer) : ?>
		<div id="timer-element-container-<?php echo $timer['Timer']['foreign_key']; ?>" class="timer-element-container clear">
			<div class="timer-shrink-container">
				<div class="left timer-element" data-date="<?php echo date('Y-m-d G:i:s', strtotime($timer['Timer']['time_start'])); ?>"></div>
			</div>
			<div class="grid">
				<div id="info1-container" class="col-1of1">
					<div class="grid">
						<div id="" class="col-1of1">
							<div class="active-timer-item ">
								<div class="title-buttons title-buttons-mini">
									<?php echo $this->Html->link('Log Time', array('controller' => 'order_times', 'action' => 'edit', $timer['OrderTime']['id']), array('class' => 'log-active-timer')); ?>
								</div>
							</div>
							<div class="active-timer-item left">&nbsp;&nbsp;&nbsp;Started at: <?php echo date('g:i a', strtotime($timer['OrderTime']['time_start_work'])); ?></div>
							<div class="active-timer-item left">
								<b>For: </b><?php echo $this->Web->humanName($timer['OrderTime']['Worker'], 'first_initial'); ?> at <?php echo $timer['OrderTime']['Order']['customer_name']; ?></div>
						</div>
					</div>
				</div>
				<?php echo $this->Html->link($this->Html->image('icon-error2.png'), '#', array('class'=>'active-timer-delete right', 'escape'=>false)); ?>
				<div id="timer-data-bank-<?php echo $timer['Timer']['foreign_key']; ?>" class="hide">
					<?php echo $this->Form->hidden('ActiveTimer.order_time_id', array('value'=>$timer['Timer']['foreign_key']));?>
					<?php echo $this->Form->hidden('ActiveTimer.start_time', array('value'=>$timer['OrderTime']['time_start_work']));?>
					<?php echo $this->Form->hidden('ActiveTimer.order_name', array('value'=>$timer['OrderTime']['Order']['name']));?>
					<?php echo $this->Form->hidden('ActiveTimer.worker_name', array('value'=>$timer['OrderTime']['Worker']['name_first'] . ' ' . $timer['OrderTime']['Worker']['name_last']));?>
				</div>
			</div>
		</div>
	<?php 
		endforeach;
	endif
	?>
	<div id="new-timer-element-container" class="timer-element-container clear hide">
		<div class="timer-shrink-container">
			<div id="new-timer-element" class="left timer-element" data-date=""></div>
		</div>
		<div class="grid">
			<div class="col-1of1">
				<div class="grid">
					<div class="col-1of1">
						<div class="active-timer-item ">
							<div class="title-buttons title-buttons-mini">
								<?php echo $this->Html->link('Log Time', array('controller' => 'order_times', 'action' => 'edit'), array('id' => 'active-timer-item-link', 'class' => 'log-active-timer')); ?>
							</div>
						</div>
						<div id="active-timer-item-when" class="active-timer-item left">&nbsp;&nbsp;&nbsp;Started at: </div>
						<!-- <div id="active-timer-item-who" class="active-timer-item left"><b>For: </b></div>	 -->
						<div id="active-timer-item-order-name" class="active-timer-item left"><b><?php echo __('For'); ?>: </b></div>
					</div>
				</div>
				<?php echo $this->Html->link($this->Html->image('icon-error2.png'), '#', array('class'=>'active-timer-delete right', 'escape'=>false)); ?>
			</div>
		</div>
	</div>	
	<?php echo $this->Form->hidden('log-time-confirm-box-id', array('id' => 'log-time-confirm-box-id')); ?>
	<?php echo $this->Form->hidden('log-time-confirm-box-time', array('id' => 'log-time-confirm-box-time')); ?>
	<div id='log-time-confirm-box'>
		<p>This is an animated dialog which is useful for displaying information. The dialog window can be moved, resized and closed with the 'x' icon.</p>
	</div>
</div>