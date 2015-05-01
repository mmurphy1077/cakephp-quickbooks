<?php 
if(!isset($class)) {
	$class='';
} ?>
<?php if ($this->Session->check('Message.success')): ?>
	<div class="response-msg success <?php echo $class; ?>">
		<?php echo $this->Session->flash('success'); ?>	
		<div id="close-response-msg" class="close-response-msg"></div>
	</div>	
<?php endif; ?>		
<?php if ($this->Session->check('Message.error')): ?>
	<div class="response-msg error <?php echo $class; ?>">
		<?php echo $this->Session->flash('error'); ?>
		<div id="close-response-msg" class="close-response-msg"></div>	
	</div>	
<?php endif; ?>
<?php if ($this->Session->check('Message.info')): ?>
	<div class="response-msg info <?php echo $class; ?>">
		<?php echo $this->Session->flash('info'); ?>
		<div id="close-response-msg" class="close-response-msg"></div>
	</div>	
<?php endif; ?>
<?php if ($this->Session->check('Message.notice')): ?>
	<div class="response-msg notice <?php echo $class; ?>">
		<?php echo $this->Session->flash('notice'); ?>
		<div id="close-response-msg" class="close-response-msg"></div>	
	</div>	
<?php endif; ?>
<?php if ($this->Session->check('Message.auth')): ?>
	<div class="response-msg notice <?php echo $class; ?>">
		<?php echo $this->Session->flash('auth'); ?>
		<div id="close-response-msg" class="close-response-msg"></div>
	</div>	
<?php endif; ?>
<?php if ($this->Session->check('Message.flash')): ?>
	<div class="response-msg error <?php echo $class; ?>">
		<?php echo $this->Session->flash('flash'); ?>
		<div id="close-response-msg" class="close-response-msg"></div>	
	</div>	
<?php endif; ?>