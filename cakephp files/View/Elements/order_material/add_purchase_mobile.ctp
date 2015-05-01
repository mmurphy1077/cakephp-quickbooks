<div id="mobile-menu-container" class="hidden-md hidden-lg hidden">
<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'list')); ?>
</div>
<div id="mobile-content-container">
	<?php echo $this->Form->create('OrderMaterial', array('id' => 'OrderMaterialAddForm', 'class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'order_materials', 'action' => 'add_purchase', $order['Order']['id']))); ?>
	<?php echo $this->element('order_tracking_nav', array('selected' => 'purchase', 'order_id' => $this->data['OrderMaterial']['order_id'])); ?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-7 col-lg-6">
			<h4>
				<?php echo __('Purchases'); ?>&nbsp;&nbsp;
				<span class="small alert"><?php echo $permission_explination; ?></span>
			</h4>
			<?php echo $this->element('order_material/order_material_form', array('material_type' => 'purchase', 'permissions' => $permissions, 'permission_attr' => $permission_attr)); ?>	
		</div>
	</div>
	<?php echo $this->Form->end(); ?>	
</div>
<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'tab')); ?>
<?php $this->end(); ?>