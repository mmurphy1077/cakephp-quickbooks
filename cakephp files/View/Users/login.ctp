<?php
$this->set('title_for_layout', __('Login'));
$values = array(
	'email' => __('Email Address'),
	'password' => __('Password'),
); ?>
<script>
	$(document).ready(function() {
		$('#UserPassword').hide();
		$('#UserEmailAddress').bind('blur', function() {
			$(this).val(function(i, val) {
				if (val == '') {
					// Restore form label tip if nothing was entered
					return '<?php echo $values['email']; ?>';
				} else {
					return val;
				}
			});
		});
		$('#UserEmailAddress').bind('focus', function() {
			$(this).val(function(i, val) {
				if (val == '<?php echo $values['email']; ?>') {
					// Only clear form input if nothing has been entered yet
					return null;
				} else {
					return val;
				}
			});
		});
		$('#UserPassword').bind('blur', function() {
			$(this).val(function(i, val) {
				if (val == '') {
					// Restore form label tip if nothing was entered
					$(this).hide();
					$('#UserPasswordText').show();
				} else {
					return val;
				}
			});
		});
		$('#UserPasswordText').bind('focus', function() {
			$(this).hide();
			$('#UserPassword').show();
			$('#UserPassword').focus();
		});
		<?php
		/**
		 * Uncomment to test values of password fields
		 * 
		$('#UserLoginForm').bind('submit', function() {
			alert($('#UserPasswordText').val() + ' ' + $('#UserPassword').val());
		});
		*/
		?>

		
	    $('input[type=checkbox]').on('click', function (evt) {
	        $("input:checkbox").not($(this)).removeAttr("checked");
	        $(this).attr("checked", true);
	    });

	    // Select first checkbox
	    $('div.location-chackbox input[type="checkbox"]').first().attr('checked', true);
	});
</script>
<?php $this->layout = 'splash'; ?>
<div id="login">
	<?php echo $this->Form->create('User', array('class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'users', 'action' => 'login'))); ?>
	<?php echo $this->Form->input('emailAddress', array('label' => false, 'value' => $values['email'])); ?>
	<?php echo $this->Form->input('passwordText', array('type' => 'text', 'label' => false, 'value' => $values['password'])); ?>
	<?php echo $this->Form->input('password', array('label' => false)); ?>
	<?php #echo $this->Form->input('__rememberMe', array('type' => 'checkbox', 'label' => __('Remember Me'))); ?>
	<?php 
	if(isset($__locations) && !empty($__locations)) :
		$count = count($__locations); 
		if($count == 1) :
			// Insert hidden value
			echo $this->Form->hidden('loaction_id', array('value' => current(array_keys($__locations))));
		else : ?>
		<div class="clear">
		Select your Location
		<?php echo $this->Form->input('location_id', array('id' => 'location_id', 'class' => 'location-chackbox', 'type' => 'select', 'multiple' => 'checkbox', 'options' => $__locations, 'label' => false)); ?>
		</div>
	<?php endif;  
	endif; ?>
	
	<div class="clear">
		<br />
		<?php echo $this->Form->submit(__('Login')); ?>
	</div>
	<?php echo $this->Form->end(); ?>
	<p>&rarr; <?php echo $this->Html->link(__('I forgot my password.'), array('controller' => 'users', 'action' => 'reset_password')); ?></p>
</div>