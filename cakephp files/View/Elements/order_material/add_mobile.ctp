<div id="mobile-menu-container" class="hidden-md hidden-lg hidden">
<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'list')); ?>
</div>
<div id="mobile-content-container">
	<?php echo $this->Form->create('OrderMaterial', array('id' => 'OrderMaterialAddForm', 'class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'order_materials', 'action' => 'add', $order['Order']['id']))); ?>
	<?php echo $this->element('order_tracking_nav', array('selected' => 'material', 'order_id' => $this->data['OrderMaterial']['order_id'])); ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
			<h4>
				<?php echo __('Materials'); ?>&nbsp;&nbsp;
				<span class="small alert"><?php echo $permission_explination; ?></span>
			</h4>
			<?php echo $this->element('order_material/order_material_form', array('material_type' => 'stock', 'permissions' => $permissions, 'permission_attr' => $permission_attr)); ?>	
		</div>
		<div class="col-xs-12 col-sm-12 col-md-5 col-lg-6">
			<?php #echo $this->element('order_material/index', array('materials' => $order_materials, 'permissions'=>$permissions)); ?>
			<div id="material-catalog-menu" class="title-buttons">
				<?php echo $this->Html->link('Top Picks', '#', array('id' => 'top_picks', 'class' => 'tab_display_button button right no-margin')); ?>
				<?php echo $this->Html->link('Catalog', '#', array('id' => 'catalog', 'class' => 'tab_display_button button right no-margin selected')); ?>
				</div>
			<div id="catalog_tab_display" class="tab_display clear">
				<h4>Catalog</h4>
				<?php echo $this->element('material/material_main_container', array('results' => $catalog, 'mode' => 'order', 'container' => 'catalog')); ?>
			</div>
			<div id="top_picks_tab_display" class="tab_display clear hide">
				<h4>Top Picks</h4>
				<?php echo $this->element('material/material_main_container', array('results' => $top_picks, 'mode' => 'order', 'container' => 'top-pick')); ?>
			</div>
		</div>
	</div>
	<?php echo $this->Form->end(); ?>	
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'tab')); ?>
<?php $this->end(); ?>