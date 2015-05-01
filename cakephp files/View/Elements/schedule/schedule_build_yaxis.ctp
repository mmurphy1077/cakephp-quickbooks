<div id="y-axis-label">
<?php 
if($this->params['controller'] == 'schedules') {
	$options = array(
		'employee' => Configure::read('Nomenclature.Employee'),
		#'job-type' => 'Job Type',
		'order' => Configure::read('Nomenclature.Order'),
	); 
} else {
	$options = array(
			'employee' => Configure::read('Nomenclature.Employee'),
	);
}
if($style == 'mobile' || count($options) == 1) {
	echo '<div class="yaxis-spacer">' . $this->Form->hidden('yaxis_type', array('value' => $type)) . '</div>';
} else {
	echo $this->Form->input('yaxis_type', array('options' => $options, 'label' => false, 'value' => $type));
} ?>
</div>
<?php   
if(!empty($yaxis)) :
	foreach($yaxis as $yaxis_element) : ?>
		<table id="y-axis-<?php echo $yaxis_element['id']; ?>" class="y-axis-table <?php echo $yaxis_element['type']; ?>-table">
			<tr><td id="y-axis-td-<?php echo $yaxis_element['id']; ?>" class="y-axis-td"><span class="label"><?php echo $yaxis_element['name']; ?></span></td></tr>
		</table>
<?php endforeach;
endif;	
?>