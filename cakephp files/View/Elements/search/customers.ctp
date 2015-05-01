<?php
echo $this->Html->css('search');
$m = 'Customer';
$result = $records;
?>
<?php if (empty($result['count'])): ?>
	<p><?php __('no_items'); ?></p>
<?php else: ?>
	<div class="index2 search fieldset-wrapper">
		<table class="search">
		<?php foreach ($result['results'] as $r): ?>
			<tr class="bottom">
				<td><?php echo __($this->Web->dt($r[$m]['modified'], 'short_4')); ?></td>
				<td><h1><?php echo $this->Html->link(__($r[$m]['name'], true), array('controller' => 'customers', 'action' => 'view', $r[$m]['id'])); ?></h1></td>
				<td><?php echo __($r[$m]['website']); ?></td>
				<td><?php #echo __($r['Company']['name']); ?></td>
				<td><?php if(!empty($r['Address'])) : ?>
					<?php echo '<b>Address: </b>'.$this->Web->address($r['Address'][0], false, $separator = ', ', $name = true, $phone = false, $country = false); ?><br />
					<?php echo '<b>Account Rep: </b>'.__($r['AccountRep']['name_first'] . ' ' . $r['AccountRep']['name_last']); ?>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>