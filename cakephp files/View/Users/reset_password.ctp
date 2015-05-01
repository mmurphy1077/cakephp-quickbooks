<?php $this->set('title_for_layout', __('Reset Password'));
$values = array(
	'email' => __('Email Address'),
);
?>
<script>
	$(document).ready(function() {
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
	});
</script>
<?php $this->layout = 'splash'; ?>
<h4><?php __('Enter your email address to have a new password sent to you.'); ?></h4>
<?php echo $this->Form->create('User', array('url' => '/'.$this->params->url, 'novalidate' => true, 'class' => 'standard')); ?>
<?php echo $this->Form->input('emailAddress', array('label' => false, 'value' => $values['email'])); ?>
<?php echo $this->Form->submit(__('Reset My Password', true)); ?>
<?php echo $this->Form->end(); ?>
<p>&rarr; <?php echo $this->Html->link(__('I have my password and want to login.'), array('controller' => 'users', 'action' => 'login')); ?></p>