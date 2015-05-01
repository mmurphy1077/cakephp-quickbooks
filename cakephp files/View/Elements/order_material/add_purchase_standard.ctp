<div id="mobile-menu-container" class="hidden-md hidden-lg hidden">
<?php echo $this->element('tabs_order_mobile', array('order' => $order, 'permissions' => $permissions, 'type' => 'list')); ?>
</div>
<div id="mobile-content-container">
	<?php echo $this->Form->create('OrderMaterial', array('id' => 'OrderMaterialAddForm', 'class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'order_materials', 'action' => 'add_purchase', $order['Order']['id']))); ?>
	<fieldset>
		<div class="row fieldset-wrapper available_action_container">
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="left"><b>Available Actions:</b></div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<?php echo $this->element('tabs_order_production', array('tab' => ORDER_PRODUCTION_PURCHASE, 'order' => $order)); ?>
			</div>	
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="title-buttons">
					<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
				</div>
			</div>					  
		</div>
	</fieldset>
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
	<?php echo $this->element('tabs_order', array('order' => $order, 'permissions' => $permissions, 'type' => 'tab')); ?>
<?php $this->end(); ?>