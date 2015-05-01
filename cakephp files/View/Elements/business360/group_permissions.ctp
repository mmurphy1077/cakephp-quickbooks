<h3 class="left"><?php echo __('Group Permissions'); ?> (<?php echo $title; ?>)</h3>
<?php echo $this->Form->create('Aco', array('class' => 'standard', 'novalidate' => true, 'type' => 'file', 'url' => '/'.$this->params->url)); ?>
<h4><?php echo __('Business 360'); ?></h4>
	<div class="permissions_container">
		<div class="grid">
			<div class="col-1of3">
			<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_application_settings', $current_permissions['Application']) && $current_permissions['Application']['_application_settings'] == 1) {
					$checked = true;
				} 
				echo $this->Form->input('aco_'.$control_objects['Application']['_application_settings']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_application_settings']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_application_settings']['id'])); ?> 
			</div>
			<div class="col-1of3">
				<?php  /** FINANCIAL PERMISSIONS **/ ?>
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_financials_company', $current_permissions['Application']) && $current_permissions['Application']['_financials_company'] == 1) {
					$checked = true;
				} 
				echo $this->Form->input('aco_'.$control_objects['Application']['_financials_company']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_financials_company']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_financials_company']['id'])); ?> 
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_financials_project', $current_permissions['Application']) && $current_permissions['Application']['_financials_project'] == 1) {
					$checked = true;
				}
				echo $this->Form->input('aco_'.$control_objects['Application']['_financials_project']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_financials_project']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_financials_project']['id'])); ?>
			</div>
			<div class="col-1of3">
				<?php  /** REPORT PERMISSIONS **/ ?>
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_report_metrics', $current_permissions['Application']) && $current_permissions['Application']['_report_metrics'] == 1) {
					$checked = true;
				}
				echo $this->Form->input('aco_'.$control_objects['Application']['_report_metrics']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_report_metrics']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_report_metrics']['id'])); ?> 
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_report_financial', $current_permissions['Application']) && $current_permissions['Application']['_report_financial'] == 1) {
					$checked = true;
				}
				echo $this->Form->input('aco_'.$control_objects['Application']['_report_financial']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_report_financial']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_report_financial']['id'])); ?>
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_report_labor', $current_permissions['Application']) && $current_permissions['Application']['_report_labor'] == 1) {
					$checked = true;
				}
				echo $this->Form->input('aco_'.$control_objects['Application']['_report_labor']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_report_labor']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_report_labor']['id'])); ?>
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_report_sales', $current_permissions['Application']) && $current_permissions['Application']['_report_sales'] == 1) {
					$checked = true;
				}
				echo $this->Form->input('aco_'.$control_objects['Application']['_report_sales']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_report_sales']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_report_sales']['id'])); ?>
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_report_orders', $current_permissions['Application']) && $current_permissions['Application']['_report_orders'] == 1) {
					$checked = true;
				}
				echo $this->Form->input('aco_'.$control_objects['Application']['_report_orders']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_report_orders']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_report_orders']['id'])); ?>
				<?php 
				$checked = false; 
				if(array_key_exists('Application', $current_permissions) && array_key_exists('_report_materials', $current_permissions['Application']) && $current_permissions['Application']['_report_materials'] == 1) {
					$checked = true;
				}
				echo $this->Form->input('aco_'.$control_objects['Application']['_report_materials']['id'], array('type' => 'checkbox', 'label' => $control_objects['Application']['_report_materials']['label'], 'checked' => $checked, 'id' => $control_objects['Application']['_report_materials']['id'])); ?>
			</div>
		</div>
	</div>
	<h4><?php echo __('Accounts'); ?>
		<?php if(array_key_exists('Account', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['Account']['_access']['id'];
			if(array_key_exists('Account', $current_permissions) && array_key_exists('_access', $current_permissions['Account']) && $current_permissions['Account']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Account']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Account', 'control_objects' => $control_objects['Account'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Change Orders'); ?>
		<?php if(array_key_exists('ChangeOrderRequest', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['ChangeOrderRequest']['_access']['id'];
			if(array_key_exists('ChangeOrderRequest', $current_permissions) && array_key_exists('_access', $current_permissions['ChangeOrderRequest']) && $current_permissions['ChangeOrderRequest']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['ChangeOrderRequest']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'ChangeOrderRequest', 'control_objects' => $control_objects['ChangeOrderRequest'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Customers'); ?>
		<?php if(array_key_exists('Customer', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['Customer']['_access']['id'];
			if(array_key_exists('Customer', $current_permissions) && array_key_exists('_access', $current_permissions['Customer']) && $current_permissions['Customer']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Customer']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Customer', 'control_objects' => $control_objects['Customer'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Invoices'); ?>
		<?php if(array_key_exists('Invoice', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['Invoice']['_access']['id'];
			if(array_key_exists('Invoice', $current_permissions) && array_key_exists('_access', $current_permissions['Invoice']) && $current_permissions['Invoice']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Invoice']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Invoice', 'control_objects' => $control_objects['Invoice'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Jobs'); ?>
		<?php if(array_key_exists('Order', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['Order']['_access']['id'];
			if(array_key_exists('Order', $current_permissions) && array_key_exists('_access', $current_permissions['Order']) && $current_permissions['Order']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Order']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Order', 'control_objects' => $control_objects['Order'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Job') . ' ' . __('Materials') . ' & ' . __('Expenses'); ?>
		<?php if(array_key_exists('OrderMaterial', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['OrderMaterial']['_access']['id'];
			if(array_key_exists('OrderMaterial', $current_permissions) && array_key_exists('_access', $current_permissions['OrderMaterial']) && $current_permissions['OrderMaterial']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['OrderMaterial']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'OrderMaterial', 'control_objects' => $control_objects['OrderMaterial'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Labor Hours'); ?>
		<?php if(array_key_exists('OrderTime', $control_objects)) :
			$checked = false; 
			$disabled = true;
			$id = $control_objects['OrderTime']['_access']['id'];
			if(array_key_exists('OrderTime', $current_permissions) && array_key_exists('_access', $current_permissions['OrderTime']) && $current_permissions['OrderTime']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['OrderTime']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'OrderTime', 'control_objects' => $control_objects['OrderTime'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Leads'); ?>
		<?php if(array_key_exists('Contact', $control_objects)) :
			$checked = false; 
			$disabled = true;
			$id = $control_objects['Contact']['_access']['id'];
			if(array_key_exists('Contact', $current_permissions) && array_key_exists('_access', $current_permissions['Contact']) && $current_permissions['Contact']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Contact']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Contact', 'control_objects' => $control_objects['Contact'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Materials'); ?>
		<?php if(array_key_exists('Material', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['Material']['_access']['id'];
			if(array_key_exists('Material', $current_permissions) && array_key_exists('_access', $current_permissions['Material']) && $current_permissions['Material']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}	
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Material']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Material', 'control_objects' => $control_objects['Material'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Purchase Orders'); ?>
		<?php if(array_key_exists('PurchaseOrder', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['PurchaseOrder']['_access']['id'];
			if(array_key_exists('PurchaseOrder', $current_permissions) && array_key_exists('_access', $current_permissions['PurchaseOrder']) && $current_permissions['PurchaseOrder']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['PurchaseOrder']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'PurchaseOrder', 'control_objects' => $control_objects['PurchaseOrder'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Quotes'); ?>
		<?php if(array_key_exists('Quote', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['Quote']['_access']['id'];
			if(array_key_exists('Quote', $current_permissions) && array_key_exists('_access', $current_permissions['Quote']) && $current_permissions['Quote']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Quote']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Quote', 'control_objects' => $control_objects['Quote'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Schedules'); ?>
		<?php if(array_key_exists('Schedule', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['Schedule']['_access']['id'];
			if(array_key_exists('Schedule', $current_permissions) && $current_permissions['Schedule']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['Schedule']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'Schedule', 'control_objects' => $control_objects['Schedule'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	<h4><?php echo __('Users'); ?>
		<?php if(array_key_exists('User', $control_objects)) : 
			$checked = false;
			$disabled = true;
			$id = $control_objects['User']['_access']['id'];
			if(array_key_exists('User', $current_permissions) && array_key_exists('_access', $current_permissions['User']) && $current_permissions['User']['_access'] == 1) {
				$checked = true;
				$disabled = false;
			}
			echo $this->Form->input('aco_'.$id, array('type' => 'checkbox', 'div' => false, 'label' => false, 'checked' => $checked, 'id' => $id, 'class' => 'access'));
		endif; ?>
	</h4>
	<div class="permissions_container" id="permissions_container_<?php echo $control_objects['User']['_access']['id']; ?>">
		<?php echo $this->element('permissions_container', array('model' => 'User', 'control_objects' => $control_objects['User'], 'current_permissions' => $current_permissions, 'disabled' => $disabled)); ?>
	</div>
	
	<div class="title-buttons">
		<?php echo $this->Form->submit(__('Save', true), array('class' => 'red')); ?>
	</div>					  

<?php echo $this->Form->hidden('Aro.aro_id', array('value' => $aro_id));?>
<?php echo $this->Form->end(); ?>
	