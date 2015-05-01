<?php
echo $this->Html->css('search');
$m = 'QuoteLineItem';
$m2 = 'Quote';
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
				<td><h1><?php echo $this->Html->link(__($r[$m2]['number'], true), array('controller' => 'quote_jobs', 'action' => 'edit', $r['QuoteLineItem']['quote_id'], $r['QuoteLineItem']['id'])); ?></h1></td>
				<td>
					<?php echo __($r[$m2]['customer_name'], true); ?><br />
					<?php echo __($r[$m2]['description']); ?>
				</td>
				<td>
					<?php echo '<b>Line Item: </b>'.$r[$m]['name']; ?><br />
					<?php echo '<b>Description: </b>'.$r[$m]['description']; ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>
<?php endif; ?>