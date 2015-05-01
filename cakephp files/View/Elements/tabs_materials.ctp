<?php
$data = array(
	'material' => array(
		'class' => 'disabled',
	),
	'assembly' => array(
		'class' => 'disabled',
	),
);

// Use permissions to determine if the user hass access to the reports.
if($__user['Group']['Application']['_report_financial'] == 1) {
	$data['material']['class'] = 'normal';
	$data['assembly']['class'] = 'normal';
}

// Set the current tab
$data[$tab]['class'] = 'active';
?>
<div class="quote tab">
	<div class="left">
		<div class="block <?php echo $data['material']['class']; ?>">
			<?php
			$link = array('controller' => 'materials', 'action' => 'index');
			if ($data['material']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__(Configure::read('Nomenclature.Catalog')), $link, array('class' => 'text'));
			?>
		</div>
		<div class="block <?php echo $data['assembly']['class']; ?>">
			<?php
			$link = array('controller' => 'material_assemblies', 'action' => 'index');
			if ($data['assembly']['class'] == 'disabled') {
				$link = '#';
			}
			echo $this->Html->link(__('Assembly'), $link, array('class' => 'text'));
			?>
		</div>
	</div>
</div>