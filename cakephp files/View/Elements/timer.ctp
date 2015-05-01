<?php 
$active = '';
if(!empty($data['Timer'])) {
	$active = 'active';
}
?>
<div id="timer-container" class="left timer-container <?php echo $active; ?>">
<?php 
$image = $this->Html->image('timer/timer00.png');
echo $this->Html->link($image, array('#'), array('id' => 'timer', 'class' => 'toggle_display_button right', 'escape'=>false)); 
?>
</div>