<?php 
$permission_attr = 'readonly disabled';
$enable_save = false;
if((($permissions['can_create'] || $permissions['can_update']) && (array_key_exists('project_manager_id', $this->data['Order']) && $this->data['Order']['project_manager_id'] == $__user['User']['id'])) || $permissions['view_all_orders']) {
	$permission_attr = null;
	$enable_save = true;
} ?>
<div class="clear">
	<?php echo $this->Form->create('Order', array('class' => 'standard', 'novalidate' => true, 'url' => '/'.$this->params->url)); ?>
		<fieldset>
			<div class="row fieldset-wrapper available_action_container">
				<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
					<b>Available Actions:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Select / Add Customer, Contact and Billing Information.	
				</div>	
				<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
					<div class="title-buttons">
						<div class="visible-xs-block"><br /></div>
						<?php echo $this->element('nav_context', array('permission' => $permissions)); ?>
						<?php 
						if($enable_save) {
							echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
						} ?>
					</div>
				</div>					  
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4>
					<span class="number-dot">1</span>&nbsp;
					<?php echo __('Enter the ' . __(Configure::read('Nomenclature.Order')) . '\'s name.'); ?>
					<?php #echo $this->Form->error('customer_name', __('Please make a selection from one of the options below.'), array('class' => 'error-message flush')); ?>
				</h4>
				<div class="grid">
					<div class="col-1of1">
						<div class="label inline">Name</div>
						<?php echo $this->Form->input('name', array('class' => 'required', 'label' => false)); ?>
					</div>
				</div>		
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4>
					<span class="number-dot">2</span>&nbsp;
					<?php echo __('Enter the Customer\'s name. Select an existing Customer/Lead.'); ?>
					<?php #echo $this->Form->error('customer_name', __('Please make a selection from one of the options below.'), array('class' => 'error-message flush')); ?>
				</h4>
				<div class="grid">
					<div class="col-1of3">
						<div class="label inline"><?php echo __(Configure::read('Nomenclature.Customer')); ?></div>
						<?php #echo $this->Form->input('contact_id', array('label' => false, 'empty' => true)); ?>
						<?php echo $this->Form->input('customer_name', array('class' => 'required', 'label' => false, 'id' => 'customer_name')); ?>		
					</div>
					<div class="col-1of3">
						<div class="label"><?php echo __('Current ' . Configure::read('Nomenclature.Customer')); ?></div>
						<?php #echo $this->Form->input('customer_id', array('label' => false, 'empty' => true)); ?>	
						<div id="autocomplete_container_customer" class="clear autocomplete_container white jscrollpane_mini">
								<table id="customer_search_table" class="search_table"><?php 
								if(empty($current_customers)) {
									echo '<tr><td>No matching customers.</td></tr>';
								} else {
									$customer_id = null;
									foreach($current_customers as $customer) {  
										if(!empty($customer) && $customer['Customer']['id'] != $customer_id) { 
											// This array is structed to bring back many address... The element is only looking for a one-to-one relationship
											// Same with Contacts.
											if((array_key_exists('Address', $customer)) && (array_key_exists(0, $customer['Address']))) {
												$customer['Address'] = $customer['Address'][0];
											}
											if((array_key_exists('Contact', $customer)) && (array_key_exists(0, $customer['Contact']))) {
												$customer['Contact'] = $customer['Contact'][0];
											}
											echo $this->element('table_row_order_customer_search', array('customer' => $customer));
											$customer_id = $customer['Customer']['id'];
										}
									}
								}?>
								</table>
						</div>
					</div>
					<div class="col-1of3">
						<div class="label"><?php echo __('Current ' . Configure::read('Nomenclature.Contact')); ?></div>
						<?php #echo $this->Form->input('customer_name', array('label' => false, 'error' => false)); ?>
						<div  id="autocomplete_container_lead" class="clear autocomplete_container white jscrollpane_mini">
							<table id="lead_search_table" class="search_table"><?php 
								if(empty($current_contacts)) {
									echo '<tr><td>No matching contacts.</td></tr>';
								} ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4>
					<span class="number-dot">3</span>&nbsp;
					<?php echo __('Select / Add Project Contact'); ?>
				</h4>
				<?php 
				$current_contacts = null;
				if(array_key_exists('OrderContact', $this->data)) {
					$current_contacts = $this->data['OrderContact'];
				} ?>
				<?php 
				$customer_id = null;
				if(array_key_exists('customer_id', $this->data['Order'])) {
					$customer_id = $this->data['Order']['customer_id'];
				}
				echo $this->element('multiple_contact_form', array('current_contacts' => $current_contacts, 'model' => 'Order',  'foreign_key' => $this->data['Order']['id'], 'customer_id' => $customer_id, 'permissions' => $permissions, 'contactTypes' => $contactTypes)); ?>
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4>
					<span class="number-dot">4</span>&nbsp;
					<?php echo __('Select / Add Billing Address'); ?>
				</h4>
				<div class="grid">
					<div id="address_billing_container" class="col-1of2">
						<?php echo $this->element('address_form', array('addressTypeId' => ADDRESS_TYPE_ID_BILLING, 'alias' => 'BillingAddress', 'disable_primary_option' => 'true', 'read_only_attribute' => $permission_attr)); ?>
					</div>
					<div class="col-1of2">
						<div class="label"><?php echo __('Associated Addresses'); ?></div>
						<div id="autocomplete_container_billing" class="clear autocomplete_container white jscrollpane_mini">
							<table id="billing_search_table" class="search_table"><?php 
								if(empty($associated_addresses)) {
									echo '<tr><td>No associated contacts.</td></tr>';
								} else {
									foreach($associated_addresses as $data) {
										echo $this->element('table_row_order_address', array('address' => $data['Address'], 'address_type' => $data['AddressType']['name']));
									}
								} ?>
							</table>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper">
				<h4>
					<span class="number-dot">5</span>&nbsp;
					<?php echo __('Select / Add Jobsite Address'); ?>
				</h4>
				<div class="grid">
					<div id="address_jobsite_container" class="col-1of2">
						<div class="input checkbox standard">
							<label id="LabelBillingSameAsJobsite" class="standard_checkbox" for="OrderJobsiteSameAsBilling">Same As Recipient</label>
							<?php echo $this->Form->input('jobsite_same_as_billing', array('type'=>'checkbox', 'label' => false, 'div' => false, 'id' => 'jobsite_same_as_billing')); ?>
						</div>
						<?php echo $this->element('address_form', array('addressTypeId' => ADDRESS_TYPE_ID_JOBSITE, 'disable_primary_option' => 'true', 'read_only_attribute' => $permission_attr)); ?>
					</div>
					<div class="col-1of2">
						<div class="label"><?php echo __('Associated Addresses'); ?></div>
						<div id="autocomplete_container_jobsite" class="clear autocomplete_container white jscrollpane_mini">
								<table id="jobsite_search_table" class="search_table"><?php 
								if(empty($associated_addresses)) {
									echo '<tr><td>No associated contacts.</td></tr>';
								} else {
									foreach($associated_addresses as $data) {
										echo $this->element('table_row_order_address', array('address' => $data['Address'], 'address_type' => $data['AddressType']['name']));
									}
								} ?>
								</table>
						</div>
					</div>
				</div>
			</div>
		</fieldset>
		<fieldset>
			<div class="fieldset-wrapper available_action_container">
				&nbsp;
				<div class="title-buttons">
					<?php 
					if($enable_save) {
						echo $this->Form->submit(__('Save', true), array('class' => 'red')); 
					} ?>
				</div>												  
			</div>
		</fieldset>
	<?php #echo $this->Form->submit(__('Continue &rarr;'), array('escape' => false)); ?>
	<?php echo $this->Form->hidden('id'); ?>
	<?php echo $this->Form->hidden('sid'); ?>
	<?php echo $this->Form->hidden('customer_id'); ?>
	<?php echo $this->Form->hidden('order_customer_mode'); ?>
	<?php $value = null;
	if(array_key_exists('customer_id', $this->data['Order'])) {
		$this->data['Order']['customer_id'];
	}
	?>
	<?php echo $this->Form->hidden('order_customer_id', array('value' => $value)); ?>
	<?php #echo $this->Form->hidden('customer_id'); ?>
	<?php echo $this->Form->hidden('__validation', array('value' => 'customer_info')); ?>
	<?php echo $this->Form->end(); ?>
</div>

<?php $this->start('metaHeader'); ?>
	<?php echo $this->element('tabs_order', array('order' => $this->data, 'permissions' => $permissions)); ?>
<?php $this->end(); ?>