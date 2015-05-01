<?php
$this->extend('/Templates/index');
$pageTitle = Inflector::pluralize(Configure::read('Nomenclature.Customer'));
if (isset($currentStatus)) {
	$pageTitle .= ' &mdash; '.$__statuses[$currentStatus];
} elseif (isset($currentLetter)) {
	$pageTitle .= ' &mdash; '.$currentLetter;
}
$this->assign('pageTitle', $pageTitle);

$permissions = $this->Permission->getPermissions($__permissions);
?>
<?php $this->start('tableHeaders'); ?>
	<th>&nbsp;</th>
	<th><?php echo $this->Paginator->sort('Customer.name', __('Name')); ?></th>
	<th><?php echo __('Alerts'); ?></th>
	<th><?php echo __('Primary Address'); ?></th>
	<th><?php echo __('Phone'); ?></th>
	<th><?php echo __('Created'); ?></th>
	<th><?php echo __('Last Contact'); ?></th>
	<th>&nbsp;</th>
<?php $this->end(); ?>
<?php foreach ($results as $result): ?>
	<tr>
		<td>&nbsp;</td>
		<td><?php echo $result['Customer']['name']; ?></td>
		<td>&nbsp;</td>
		<td>
			<?php if (!empty($result['Address'])): ?>
				<?php echo $this->Web->address($result['Address'][0], false); ?>
			<?php endif; ?>
		</td>
		<td><?php echo $result['Customer']['phone_1_number']; ?></td>
		<td><?php echo $this->Web->dt($result['Customer']['created'], 'short_4'); ?></td>
		<td>&nbsp;</td>
		<td class="actions"><?php echo $this->Html->link(__('Details'), array('controller' => 'customers', 'action' => 'edit', $result['Customer']['id']), array('class' => 'row-click')); ?></td>
	</tr>
<?php endforeach; ?>
<?php $this->start('buttons'); ?>
	<?php if (!empty($permissions['can_create'])): ?>
		<?php echo $this->Html->link(__('Add Customer'), array('controller' => 'customers', 'action' => 'add')); ?>
	<?php endif; ?>
<?php $this->end(); ?>