<div id="mobile-menu-container" class="hidden-md hidden-lg hidden">
<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'list')); ?>
</div>
<div id="mobile-content-container">
	<?php echo $this->Form->create('OrderLineItem', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-xs-6 col-sm-9 col-md-9 col-lg-10">
				<b>Available Actions:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Job Information: Enter one or more items for this job.
			</div>	
			<div class="col-xs-6 col-sm-3 col-md-3 col-lg-2">
				<div class="title-buttons">
					<?php 
					if($permissions['enable_save'] == 1) {
						echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
					} ?>
				</div>
			</div>					  
		</div>
	</fieldset>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div class="center">
				<?php echo $this->Form->create('OrderLineItem', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
					<fieldset>
						<div class="fieldset-wrapper">
							<h4><?php echo __(Configure::read('Nomenclature.Order').' Item'); ?></h4>				
							<?php echo $this->Form->input('name', array('class' => 'full', 'error' => array('length' => __('text_100')))); ?>
							<?php 
							$description = '';
							if(array_key_exists('description', $this->data['OrderLineItem']) && !empty($this->data['OrderLineItem']['description'])) {
								$description = $this->data['OrderLineItem']['description'];
							}
							echo $this->Form->input('description', array('class' => 'full medium_text', 'label' => 'Description', 'type' => 'textarea', 'div' => 'input textarea required')); ?>
							<?php echo $this->Form->hidden('qty', array('value' => 1)); ?>
							<?php echo $this->Html->link('toggle estimate calculator', '#', array('id' => 'estimate', 'class' => 'right toggle_display_button'))?>
							<br />
							<div id="estimate_toggle_display">
							<?php echo $this->element('estimate_line_items', array('model' => 'Order')); ?>
							</div>
							<br />
							<div class="col-xs-9 col-sm-6 col-md-9 col-lg-6 clear">
							<?php echo $this->Form->input('price_unit', array('label' => 'Item Cost', 'div' => 'input larger_label_space', 'before' => __('$') . '&nbsp;', 'class' => 'num_only_allow_neg cost_input', 'error' => array('num_pos' => __('num_positive'), 'size' => __('num_positive')))); ?>
							</div>		
						</div>
					</fieldset>
					<fieldset>
						<div class="row fieldset-wrapper available_action_container">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">&nbsp;</div>	
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
								<div class="title-buttons hidden-xs hidden-sm">
									<?php 
									if($permissions['enable_save']) {
										echo $this->Form->submit(__('Save', true), array('class' => '')); 
									} ?>
								</div>
								<div class="title-buttons visible-xs-block visible-sm-block">
									<br />
									<?php 
									if($permissions['enable_save']) {
										echo $this->Form->submit(__('Save', true), array('class' => 'left')); 
									} ?>
								</div>
							</div>					  
						</div>
					</fieldset>
				<?php echo $this->Form->hidden('labor', array('id' => 'line_item_labor', 'value' => Configure::read('Pricing.QuoteLineItem.labor'))); ?>
				<?php echo $this->Form->hidden('id'); ?>
				<?php echo $this->Form->hidden('order_id'); ?>
				<?php echo $this->Form->end(); ?>
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
			<div>
				<div class="center">
					<h4>Current Items</h4>
					<?php 
					if (empty($order['OrderLineItem'])) {

					} else {
						echo $this->element('order/order_summary', array('order' => $order, 'allowEdit' => true, 'itemsOnly' => true, 'displaySort' => false)); 
					} ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'tab')); ?>
<?php $this->end(); ?>