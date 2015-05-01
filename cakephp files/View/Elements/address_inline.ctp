<?php $primaryAddress = $this->Session->read('Application.address.primary');?>
<div class="input text medium inline"><?php echo $this->Form->input($model . '.city', array('div' => false, 'error' => array('length' => __('text_45', true)), 'label' => false, 'class' => 'required city', 'id' => $city_id, 'error' => false)); ?></div>
<div class="input select short inline"><?php echo $this->Form->input($model . '.st_prov', array('div' => false, 'options' => $__states, 'label' => false, 'class' => 'state', 'value' => $primaryAddress['Address']['st_prov'], 'id' => $state_id, 'error' => false)); ?></div>
<div class="input text short inline">
	<?php echo $this->Form->input($model . '.zip_post', array('div' => false, 'class' => 'required zipcode', 'id' => 'zipcode-' . $zip_id, 'label' => false, 'error' => false, 'after' => '&nbsp;&nbsp;'.$this->Html->link('&laquo; Look Up', '#', array('id' => 'zipcode-lookup-' . $zip_id, 'class' => 'zipcode-lookup', 'escape' => false)))); ?>
	<div id="ajax-loader-zip-<?php echo $zip_id?>" class="ajax-loader">
		<?php echo $this->Html->image('loader-large.gif'); ?>
	</div>
</div>
<?php echo $this->Form->error($model.'.city', __('City is Required'), array('class' => 'error-message flush')); ?>
<?php echo $this->Form->error($model.'.st_prov', __('State is Required'), array('class' => 'error-message flush')); ?>
<?php echo $this->Form->error($model.'.zip_post', __('Zip Code is Required'), array('class' => 'error-message flush')); ?>