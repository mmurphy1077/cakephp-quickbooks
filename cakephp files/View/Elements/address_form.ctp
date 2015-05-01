<?php
$__countries = array(
		'USA' => 'USA',
		'Canada' => 'Canada',
);
echo $this->Html->script('creationsite/zip', false);
if(!isset($model)) {
	$model = 'Address';
}
if(!isset($expanded)) {
	$expanded = false;
}
if(!isset($alias)) {
	$alias = 'Address';
}
if(!isset($read_only_attribute)) {
	$read_only_attribute = '';
}
if(!isset($disable_country)) {
	$disable_country = false;
}
$country = 'USA';
if(array_key_exists($alias, $this->data) && array_key_exists('country', $this->data[$alias]) && !empty($this->data[$alias]['country'])) {
	$country = $this->data[$alias]['country'];
}
if(!isset($required)) {
	$required = false;
}
$required_class = '';
if ($required) {
	$required_class = 'required'; 
} ?>
<?php if($expanded) : ?>
<div class="row">
	<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8">
<?php endif; ?>
<?php 
if(!isset($dispaly_name_tag)) {
	$dispaly_name_tag = true;
}
if(!isset($dispaly_address_type)) {
	$dispaly_address_type = true;
}

if($dispaly_address_type) {
	if (!empty($addressTypeId)) {
		echo $this->Form->hidden($alias.'.address_type_id', array('value' => $addressTypeId));
	} else {
		echo $this->Form->input($alias.'.address_type_id', array('div' => 'input select medium'));
	}
}
if (!empty($this->request->data[$alias]['model'])) {
	echo $this->Form->hidden($alias.'.model');
} else {
	echo $this->Form->hidden($alias.'.model', array('value' => $model));
}
if(!isset($foreign_key)) {
	$foreign_key = null;
}
if (!empty($this->request->data[$alias]['foreign_key'])) {
	echo $this->Form->hidden($alias.'.foreign_key');
} else {
	echo $this->Form->hidden($alias.'.foreign_key', array('value' => $foreign_key));
} ?>
<?php
echo $this->Form->hidden($alias.'.id');
if($dispaly_name_tag) {
	echo $this->Form->input($alias.'.name', array($read_only_attribute, 'label' => __('Name/Tag', true)));
}
echo $this->Form->input($alias.'.line1', array('label' => __('Address', true), 'class' => $required_class));
echo $this->Form->input($alias.'.line2', array($read_only_attribute, 'label' => '&nbsp;'));
?>
<div class="input text medium">
	<?php echo $this->Form->input($alias.'.city', array($read_only_attribute, 'div' => false, 'class' => 'city ' . $required_class)); ?>
	<div id="ajax-loader-city" class="ajax-loader">
		<?php echo $this->Html->image('loader-large.gif'); ?>
	</div>
</div>
<div class="input select short">
	<?php 
	$primaryAddress = $this->Session->read('Application.address.primary');
	$state_val = $primaryAddress['Address']['st_prov'];
	if(!empty($this->data[$alias]['st_prov'])) {
		$state_val = $this->data[$alias]['st_prov'];
	}
	?>
	<?php #echo $this->Form->input($alias.'.st_prov', array($read_only_attribute, 'div' => false, 'options' => $__states, 'label' => __('State/Province/Region', true), 'class' => 'state', 'value' => $state_val)); ?>
	<?php echo $this->Form->input($alias.'.st_prov', array($read_only_attribute, 'div' => false, 'label' => __('State', true), 'class' => 'state', 'value' => $state_val)); ?>
	<div id="ajax-loader-state" class="ajax-loader">
		<?php echo $this->Html->image('loader-large.gif'); ?>
	</div>
</div>
<div class="input text short">
	<?php #echo $this->Form->input($alias.'.zip_post', array($read_only_attribute, 'div' => false, 'class' => 'zipcode', 'id' => 'zipcode-'.$alias, 'label' => __('Zip Code', true), 'error' => array('zip' => __('zipcode_us', true)), 'after' => '&nbsp;&nbsp;'.$this->Html->link('&laquo; Look Up', '#', array('id' => 'zipcode-lookup-'.$alias, 'class' => 'zipcode-lookup', 'escape' => false)))); ?>
	<?php echo $this->Form->input($alias.'.zip_post', array($read_only_attribute, 'div' => false, 'class' => 'zipcode', 'label' => __('Zip Code', true), 'error' => array('zip' => __('zipcode_us', true)), 'after' => '&nbsp;&nbsp;'.$this->Html->link('&laquo; Look Up', '#', array('id' => 'zipcode-lookup-'.$alias, 'class' => 'zipcode-lookup', 'escape' => false)))); ?>
	<div id="ajax-loader-zip-<?php echo $alias; ?>" class="ajax-loader">
		<?php echo $this->Html->image('loader-large.gif'); ?>
	</div>
</div>
<?php if(!$disable_country) : ?>
	<?php echo $this->Form->input($alias.'Country', array('label' => __('Country'), 'options' => $__countries, 'id' => $alias.'Country', 'value'=>$country, 'name' => 'data['.$alias.'][country]', 'div' => 'input select medium')); ?>
<?php endif; ?>
<?php 
if(!isset($disable_primary_option)) {
	$disable_primary_option = false;
} 
if(!$disable_primary_option) :
?>
<div class="input checkbox standard">
	<label class="standard_checkbox" for="QuoteAddToCustomer">Primary</label>
	<?php echo $this->Form->input($alias.'.primary', array($read_only_attribute, 'type'=>'checkbox', 'label' => false, 'div' => false)); ?>
</div>
<?php endif; ?>

<?php if($expanded) : ?>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4">
		<div class="label no-pad"><?php echo  __('Location Notes', true); ?></div>
		<?php echo $this->Form->input($alias.'.notes', array($read_only_attribute, 'label' =>false, 'class' => 'main-message full')); ?>
	</div>
</div>
<?php endif; ?>