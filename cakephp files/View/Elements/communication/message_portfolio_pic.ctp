<?php 
if(isset($options)) {
	extract($options);
} 
if(!isset($pic_only)) {
	$pic_only = false;
}
if(!$pic_only) : 
?>
<div class="message-speech-bubble-outer"><div class="message-speech-bubble-inner"></div></div>
<div class="message-portfolio-pic-container">
<?php 
if(!empty($user) && array_key_exists('ProfileImage', $user) && !empty($user['ProfileImage'])) {
	$image = $user['ProfileImage'];
	echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/ProfileImage/' . $image['name'], array('class' => 'user_profile'));
} else {
	// grab the default.
	echo $this->Image->get('ProfileImage', PROFILE_IMAGE_DEFAULT, Configure::read('Images.ProfileImage.profile'), array('class' => 'profile')); 
} ?>
</div>
<?php else :
	if(!empty($user) && array_key_exists('ProfileImage', $user) && !empty($user['ProfileImage'])) {
		$image = $user['ProfileImage'];
		echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/ProfileImage/' . $image['name'], array('class' => 'user_profile'));
	} else {
		// grab the default.
		echo $this->Image->get('ProfileImage', PROFILE_IMAGE_DEFAULT, Configure::read('Images.ProfileImage.profile'), array('class' => 'profile'));
	}
endif; ?>