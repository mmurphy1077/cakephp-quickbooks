<?php if (!empty($quoteJob['QuoteJob']['QuoteJobImage']['name'])): ?>
	<?php echo $this->element('zoom_image', array('model' => 'QuoteJobImage', 'imageName' => $quoteJob['QuoteJob']['QuoteJobImage'], 'size' => array('small', 'large'), 'options' => array('class' => 'quote-job-summary'))); ?>
<?php endif; ?>
<?php if (!empty($quoteJob['QuoteJob']['QuoteJobRequirement'])): ?>
	<br />
	<div class="bold"><?php echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.QuoteJobRequirement'))), array('controller' => 'quote_job_requirements', 'action' => 'edit', $quoteJob['QuoteJob']['id'])); ?></div>
	<ul class="stats">
		<?php foreach ($quoteJob['QuoteJob']['QuoteJobRequirement'] as $quoteJobRequirement): ?>
			<li class="small">
				<span class="label"><?php echo __($quoteJobRequirement['CalculationSystemRequirement']['CalculationRequirement']['name']); ?></span>
				<span class="value">
					<?php echo $this->Web->convertTo($quoteJobRequirement['CalculationSystemRequirement']['CalculationRequirement']['calculation_requirement_type_id'], $quoteJobRequirement['uom']); ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php if (!empty($quoteJob['QuoteJob']['QuoteJobLineItem'])): ?>
	<br />
	<div class="bold"><?php echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.QuoteJobLineItem'))), array('controller' => 'quote_job_line_items', 'action' => 'edit', $quoteJob['QuoteJob']['id'])); ?></div>
	<ul class="stats">
		<?php foreach ($quoteJob['QuoteJob']['QuoteJobLineItem'] as $quoteJobLineItem): ?>
			<li class="small">
				<span class="label">
					<b><?php echo __($quoteJobLineItem['CalculationLineItem']['name']); ?></b>
					<?php echo METHOD_SEPARATOR; ?>
					<?php if (!empty($quoteJobLineItem['CalculationTaxonomy'])): ?>
						<?php echo __($quoteJobLineItem['CalculationTaxonomy']['name']); ?>
					<?php elseif (!empty($quoteJobLineItem['include'])): ?>
						<?php echo __('Yes'); ?>
					<?php else: ?>
						<?php echo __('No'); ?>
					<?php endif; ?>
					<br />
					<span class="light"><?php echo __('Material'); ?></span> <?php echo $this->Number->currency($quoteJobLineItem['price_material_subtotal']); ?>
					<span class="light"><?php echo __('Labor'); ?></span> <?php echo $this->Number->currency($quoteJobLineItem['price_labor_subtotal']); ?>
				</span>
				<span class="value"><?php echo __('Qty ').$quoteJobLineItem['qty']; ?></span>
				<br />
				<span class="label clear light">
					<?php echo $quoteJobLineItem['notes']; ?>
				</span>
			</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
	<br />
	<div class="bold"><?php echo __('Pricing'); ?></div>
	<ul class="stats">
		<li class="small">
			<span class="label"><?php echo __('Processing Fee'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_processing_fee']); ?></span>
		</li>
		<li class="small">
			<span class="label"><?php echo __('Overhead'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_overhead']); ?></span>
		</li>
		<li class="small">
			<span class="label"><?php echo __('Labor Profit Margin'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_profit_margin_labor']); ?></span>
		</li>
		<li class="small">
			<span class="label"><?php echo __('Materials Profit Margin'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_profit_margin_materials']); ?></span>
		</li>
		<li class="small">
			<span class="label"><?php echo __('Commission'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_commission']); ?></span>
		</li>
		<li class="small">
			<span class="label"><?php echo __('Labor Subtotal'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_labor_subtotal']); ?></span>
		</li>
		<li class="small">
			<span class="label"><?php echo __('Materials Subtotal'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_materials_subtotal']); ?></span>
		</li>
		<li class="small">
			<span class="label"><?php echo __(Configure::read('Nomenclature.QuoteJob').' Total (ea)'); ?></span>
			<span class="value"><?php echo $this->Number->currency($quoteJob['QuoteJob']['price_total']); ?></span>
		</li>
	</ul>