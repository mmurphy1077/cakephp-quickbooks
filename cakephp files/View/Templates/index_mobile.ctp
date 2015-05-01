<?php echo $this->Html->script('creationsite/table-click', false); ?>
<?php 
$paginate = true;
if(!empty($exclude_paginator)) {
	$paginate = false;
}
$permissions = $this->Permission->getPermissions($__permissions); ?>
<?php if(isset($searchTypes)) : ?>
<div id="search-criteria-cluetip-container" class="hide">
	<div>Select search criteria</div>
	<?php if(isset($searchTypes)) : ?>
	<?php foreach($searchTypes as $key=>$searchType) : ?>
	<?php echo $this->Html->link($searchType, '#', array('class' => 'search-criteria-option', 'id' => 'search-criteria-option-' . $key)); ?>
	<?php endforeach; ?>
	<?php endif; ?>
	<?php echo $this->Html->image('icon-close.png', array('id' => 'search-criteria-cluetip-container-close'))?>
</div>
<?php endif; ?>
<div class="widget center">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-md-push-9 col-lg-push-9">

			<div id="index-search-container" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 widget right">
				<div id="index-filter-types">Search this section by:  &nbsp;&nbsp;&nbsp;<?php echo $this->Html->link($searchTypes[$search_criteria], '#', array('id' => 'search-criteria-select')); ?></div>
				<?php echo $this->Form->create('SearchIndex', array('url' => '/'.$this->params->url, 'id' => 'SearchIndexIndexForm')); ?>
					<div id="keyword_index-search">
						<?php echo $this->Form->input('keyword', array('label' => false, 'value' => __($search_keyword))); ?>
						<?php echo $this->Form->submit('icon-search.png', array('alt' => 'search')); ?>
						<?php echo $this->Form->hidden('criteria', array('value' => $search_criteria)); ?>
						<div class="right"><?php echo $this->Html->link('clear search', array('action' => 'clear_search'), array('id' => 'clear_all_filters')); ?></div>
					</div>
				<?php echo $this->Form->end(); ?>
			</div>
			
			<div class="widget right">
				<?php 
				switch($this->params['controller']) {
					case 'orders' :
						echo $this->element('index_filter', array('model' => 'Order', 'selected_assigned_to' => $filter_selected_assigned_to, 'selected_status' => $filter_selected_status));
						break;
					
					case 'quotes' :
						echo $this->element('index_filter', array('model' => 'Quote', 'selected_assigned_to' => $filter_selected_assigned_to, 'selected_status' => $filter_selected_status));
						break;
						
					case 'invoices' :
						echo $this->element('index_filter', array('model' => 'Invoice', 'selected_assigned_to' => $filter_selected_assigned_to, 'selected_status' => $filter_selected_status));
						break;
						
					default : 
						echo $this->element('nav_context', array('wrapper' => 'ul', 'permission' => $permissions));
				} ?>
			</div>
			<?php if ($this->fetch('pageStats')): ?>
				<div class="widget right">
					<?php echo $this->fetch('pageStats'); ?>
				</div>
			<?php endif; ?>
		</div>
		
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-md-pull-3 col-lg-pull-3">
			<?php echo $this->Html->image('border-blocker.jpg', array('id' => 'border-blocker'));?>
			<div class="widget center">
				<?php if ($this->fetch('buttons')): ?>
					<div class="title-buttons">
						<?php echo $this->fetch('buttons'); ?>
					</div>
				<?php endif; ?>
				<h3 class="left"><?php echo __($this->fetch('pageTitle')); ?></h3>
				<?php if ($this->fetch('alerts')): ?>
					<div class="widget right">
						<?php echo $this->fetch('alerts'); ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($results)): ?>
					<?php if($paginate) { echo $this->element('paginator', array('class' => 'paginator top')); }?>
					<table class="standard hover">
						<tr>
							<?php echo $this->fetch('tableHeaders'); ?>
						</tr>
						<?php echo $this->fetch('content'); ?>
					</table>
					<?php if($paginate) { echo $this->element('paginator', array('class' => 'paginator bottom')); }?>
				<?php else: ?>
					<div class="clear">
						<?php echo $this->element('info', array('content' => array(
							'no_items',
						))); ?>
					</div>
				<?php endif; ?>
				<?php if ($this->fetch('modal')): ?>
				<?php 	echo $this->fetch('modal'); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>