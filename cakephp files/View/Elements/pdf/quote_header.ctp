<?php
if(!isset($primaryAddress)) {
	$primaryAddress = $this->Session->read('Application.address.primary');
}
if(!isset($applicationSettings)) {
	$applicationSettings = $this->Session->read('Application.settings');
} ?> 
<div class="header page_element">
	<?php if (array_key_exists('CompanyImage', $applicationSettings) && (!empty($applicationSettings['CompanyImage']['bytes']))) {
		$image = $applicationSettings['CompanyImage'];
		echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/CompanyImage/' . $image['name'], array('id' => 'company-logo-image', 'class' => 'logo')); 
	} else {
		echo $this->Html->image('logo-company.png', array('class' => 'logo')); 
	} ?>
	<div class="meta">
		<?php
		if(!empty($primaryAddress['Location']['phone'])) {
			echo __('Ph: ') .  $primaryAddress['Location']['phone']; 
		} 
		if(!empty($primaryAddress['Location']['email'])) {
			echo __(' | '); 
			echo '<span class="email">' . $primaryAddress['Location']['email'] . '</span>';
		} ?>
		<?php  
		if(!empty($licenses)) : ?>
		|
		<?php foreach($licenses as $license) : ?>
		<?php echo $license?>&nbsp;
		<?php endforeach;
		endif; ?>
	</div>
</div>