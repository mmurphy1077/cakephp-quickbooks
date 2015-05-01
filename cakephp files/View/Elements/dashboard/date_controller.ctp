<?php 
$prevDate = $date_markers['previous_day'];
$nextDate = $date_markers['next_day'];

echo $this->Html->link($this->Html->image('button-arrow-left.png'), array('controller' => 'users', 'action' => 'dashboard_day', 'date_selected' => $prevDate), array('class' => 'left button_arrow button_arrow_left', 'escape' => false)); ?>
<h3 id="dashboard-date-display" class="left"><?php echo $this->Time->format('l F j, Y', $date_markers['date_selected']); ?></h3>
<?php echo $this->Html->link($this->Html->image('button-arrow-right.png'), array('controller' => 'users', 'action' => 'dashboard_day', 'date_selected' => $nextDate), array('class' => 'left button_arrow button_arrow_right', 'escape' => false)); ?>
<div id="datepicker-calendar-container">
	<?php echo $this->Form->input('day', array('class' => 'dashboardpicker', 'label' => false, 'div' => false)); ?>
</div>