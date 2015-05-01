<div class="clear">
	<?php echo $this->Form->create('OrderLineItem', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
				<div class="left"><b>Available Actions:</b></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $this->element('tabs_order_job', array('tab' => ORDER_ORDER_ITEMS, 'order' => $order, 'permissions' => $permissions)); ?>		
			</div>	
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<div class="title-buttons">
					<div class="visible-xs-block"><br /></div>
					<?php 
					if($permissions['enable_save'] == 1) {
						echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
					} ?>
				</div>
			</div>					  
		</div>
	</fieldset>
	<div class="grid">
		<div class="col-1of2">
			<div class="widget center">
				<?php echo $this->Form->create('OrderLineItem', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
					<fieldset>
						<div class="fieldset-wrapper">
							<h4><?php echo __(Configure::read('Nomenclature.Order').' Item'); ?></h4>				
							<?php
							echo $this->Form->input('name', array('error' => array('length' => __('text_100'))));
							?>
							<!-- <div class="buttonset"><?php echo $this->Form->radio('order_line_item_type_id', $orderLineItemTypes, array('legend' => __('Type', true))); ?></div> -->
						</div>
					</fieldset>
					<fieldset>
						<div class="fieldset-wrapper">
							<h4>
								<?php echo __('Scope of Work'); ?>						
								<span class="small light">&mdash; <?php echo __('Type a description.'); ?></span>
							</h4>
							<?php echo $this->Form->input('description', array('type' => 'textarea', 'div' => 'input textarea required large flush', 'label' => false)); ?>
						</div>
					</fieldset>
					<fieldset>
						<div class="fieldset-wrapper">
							<h4><?php echo __('Estimate'); ?></h4>
							<?php echo $this->element('estimate_line_items', array('model' => 'Order')); ?>
						</div>
					</fieldset>
					<fieldset>
						<div class="fieldset-wrapper">
							<h4><?php echo __('Cost'); ?></h4>	
							<div class="grid long-label clear">
								<div class="col-1of2 clear"><label>Unit $</label></div>
								<div class="col-1of2">
									<?php echo $this->Form->input('price_unit', array('label' => false, 'div' => 'input text medium currency', 'before' => __('$') . '&nbsp;', 'class' => 'num_only_allow_neg', 'after' => __('&nbsp;&nbsp;&nbsp;<div id="CostEstimateSummary" class="bold small inline light"></div>'), 'error' => array('num_pos' => __('num_positive'), 'size' => __('num_positive')))); ?>
								</div>
							</div>
							<div class="grid long-label clear">
								<div class="col-1of2 clear"><label>Qty</label></div>
								<div class="col-1of2">
									<?php echo $this->Form->input('qty', array('div' => 'input text short', 'class' => 'num_only', 'label' => false, 'before' => '&nbsp;&nbsp;&nbsp;', 'error' => array('int_pos' => __('int_pos'), 'size' => __('num_pos')))); ?>
								</div>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<div class="fieldset-wrapper available_action_container">
							&nbsp;
							<div class="title-buttons">
								<?php 
								if($permissions['enable_save'] == 1) {
									echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
								} ?>
							</div>					  
						</div>
					</fieldset>
				<?php echo $this->Form->hidden('labor', array('id' => 'line_item_labor', 'value' => Configure::read('Pricing.QuoteLineItem.labor'))); ?>
				<?php echo $this->Form->hidden('id'); ?>
				<?php echo $this->Form->hidden('order_id'); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div class="col-1of2">
			<div>
				<div class="widget center">
					<?php echo $this->element('order/order_summary', array('order' => $order, 'allowEdit' => true)); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order', array('order' => $order, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>