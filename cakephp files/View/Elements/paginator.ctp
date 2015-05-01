<?php
if (empty($options) || !is_array($options)) {
	$options = array();
}
if (empty($class)) {
	$class = null;
}
if (!empty($alphaPagination)) {
	$letters = str_split('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
}
?>
<div class="<?php echo $class; ?>">
	<?php if (!empty($alphaPagination)): ?>
		<div class="paginator-alpha">
			<div class="letters">
				<?php foreach ($letters as $letter):
					$url = array_merge(array('controller' => $this->params['controller'], 'action' => $this->action, $letter), $this->params['named']);
					?>
					<span><?php echo $this->Html->link($letter, $url); ?></span>
				<?php endforeach; ?>
			</div>
		</div>
	<?php endif; ?>
	<div class="paginator-info">
		<?php $this->Paginator->options($options); ?>
		<?php echo $this->Paginator->counter(array('format' => __('Viewing').' <b>%start%</b>-<b>%end%</b> '.__('of').' '.' <b>%count%</b> '.__('result(s).'))); ?>
	</div>
	<div class="paginator-controls">
		<?php if ($this->Paginator->hasPrev()): ?>
			<span class="previous"><?php echo $this->Paginator->prev(__('&larr; Prev'), array('escape' => false)); ?></span>
		<?php endif; ?>
		<span class="numbers"><?php echo $this->Paginator->numbers(array('separator' => false)); ?></span>
		<?php if ($this->Paginator->hasNext()): ?>
			<span class="next"><?php echo $this->Paginator->next(__('Next &rarr;'), array('escape' => false)); ?></span>
		<?php endif; ?>
	</div>			
</div>