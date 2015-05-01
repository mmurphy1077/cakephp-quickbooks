<?php
echo $this->Html->css('search');
$m = 'Contact';
$result = $records;
?>
<?php if (empty($result['count'])): ?>
	<p><?php __('no_items'); ?></p>
<?php else: ?>
	<div class="index2 search fieldset-wrapper">
		<table class="search">
		<?php foreach ($result['results'] as $r): ?>
			<tr class="bottom">
				<td><?php echo __($this->Web->dt($r['Contact']['modified'], 'short_4')); ?></td>
				<td><h1><?php echo $this->Html->link(__($r['Contact']['name_reverse'], true), array('controller' => 'contacts', 'action' => 'edit_lead', $r[$m]['id'])); ?></h1></td>
				<td><?php echo __($r['Contact']['company_name']); ?></td>
				<td>
					<?php echo '<b>Address: </b>'.$this->Web->address($r['Address'], false, $separator = ', ', $name = true, $phone = false, $country = false)?><br />
					<?php echo '<b>Account Rep: </b>'.__($r['AccountRep']['name_first'] . ' ' . $r['AccountRep']['name_last']); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>