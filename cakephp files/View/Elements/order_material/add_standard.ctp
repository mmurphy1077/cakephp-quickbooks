<div class="clear">
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="left"><b>Available Actions:</b></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $this->element('tabs_order_production', array('tab' => ORDER_PRODUCTION_MATERIAL, 'order' => $order)); ?>	
			</div>	
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="title-buttons">
					<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
				</div>
			</div>					  
		</div>
	</fieldset>
	<?php echo $this->Form->create('OrderMaterial', array('id' => 'OrderMaterialAddForm', 'class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'order_materials', 'action' => 'add', $order['Order']['id']))); ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
			<fieldset>
				<div class="fieldset-wrapper">
					<h4>
						<?php echo __('Materials'); ?>&nbsp;&nbsp;
						<span class="small alert"><?php echo $permission_explination; ?></span>
					</h4>
					<?php echo $this->element('order_material/order_material_form', array('material_type' => 'stock', 'permissions' => $permissions, 'permission_attr' => $permission_attr)); ?>	
				</div>
			</fieldset>
			<?php # echo $this->Form->end(); ?>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-5 col-lg-6">
			<?php #echo $this->element('order_material/index', array('materials' => $order_materials, 'permissions'=>$permissions)); ?>
			<div class="title-buttons">
				<?php echo $this->Html->link('Top Picks', '#', array('id' => 'top_picks', 'class' => 'tab_display_button button right no-margin')); ?>
				<?php echo $this->Html->link('Catalog', '#', array('id' => 'catalog', 'class' => 'tab_display_button button right no-margin')); ?>
				<?php echo $this->Html->link('Comments', '#', array('id' => 'comments', 'class' => 'tab_display_button button right no-margin')); ?>
			</div>
			<div id="catalog_tab_display" class="tab_display clear hide">
				<h4>Catalog</h4>
				<?php echo $this->element('material/material_main_container', array('results' => $catalog, 'mode' => 'order', 'container' => 'catalog')); ?>
			</div>
			<div id="top_picks_tab_display" class="tab_display clear hide">
				<h4>Top Picks</h4>
				<?php echo $this->element('material/material_main_container', array('results' => $top_picks, 'mode' => 'order', 'container' => 'top-pick')); ?>
			</div>
			<div id="comments_tab_display" class="tab_display clear">
				<h4>Comments</h4>
				<?php echo $this->element('communication/message_view', array('messages' => $comments_materials, 'style' => 'comment', 'result' => null, 'selected_filter_val' => 'material', 'section_types' => false, 'default_type' => 'material', 'allow_filter' => false, 'displayTouchBase' => false, 'disable_prev_messages_update' => true)); ?>	
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order', array('order' => $order, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>