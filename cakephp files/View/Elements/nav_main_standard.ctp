<?php
// Permissions
$customerAccess = $__user['Group']['Customer']['_access'];

if(array_key_exists('Customer', $__user['User'])) {
	$customerAccess = $__user['User']['Customer']['_access'];
}
if($customerAccess == 1) {
	$links[] = $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.Customer'))), array('controller' => 'customers', 'action' => 'index'));
}
?>
<?php if (empty($location) || $location == 'header'): ?>
	<div id="nav">
		<ul>
			<li><?php echo join('</li><li>', $links); ?></li>
		</ul>
	</div>
<?php elseif ($location == 'footer'): ?>
	<ul>
		<li>&rarr; <?php echo join('</li><li>&rarr; ', $links); ?></li>
	</ul>
<?php endif; ?>