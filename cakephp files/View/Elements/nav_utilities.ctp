<?php $this->Html->script('creationsite/dropdown', array('inline' => false)); ?>
<div id="utilities">
	<?php if($__browser_view_mode['browser_view_mode'] == 'standard') : ?>
		<ul class="left">
			<?php 
			$class = ' class="active"';
			#if ($permissions_nav['application_settings'] == 1) { $class = ''; } ?>
			<li<?php echo $class; ?>><?php echo $this->Html->link($this->Html->image('gear-white.png', array('id' => 'utility-gear')), array('controller' => 'business360s', 'action' => 'settings'), array('id' => 'application_settings', 'escape' => false)); ?></li>	
			<?php foreach ($__companies as $__company):
				if ($this->Session->read('Companies.Current.Company.id') == $__company['Company']['id']) {
					$class = ' class="active"';
				} else {
					$class = null;
				}
				?>
				<!-- <li<?php echo $class; ?>><?php echo $this->Html->link($__company['Company']['code'], array('controller' => 'companies', 'action' => 'change', $__company['Company']['id'])); ?></li> -->
			<?php endforeach; ?>
			<li id="quick-actions">
				<a href="#"><?php echo __('Quick Actions'); ?> &rarr;</a>
			    <ul>
					<li><?php echo $this->Html->link(__('Add '.Configure::read('Nomenclature.Contact')), array('controller' => 'contacts', 'action' => 'add_lead')); ?></li>
					<li><?php echo $this->Html->link(__('Add '.Configure::read('Nomenclature.Customer')), array('controller' => 'customers', 'action' => 'add')); ?></li>
					<li><?php echo $this->Html->link(__('Add '.Configure::read('Nomenclature.Quote')), array('controller' => 'quotes', 'action' => 'add')); ?></li>
					<li><?php echo $this->Html->link(__('Add '.Configure::read('Nomenclature.Order')), array('controller' => 'orders', 'action' => 'add')); ?></li>
					<li><?php echo $this->Html->link(__('Upload File'), array('controller' => 'users', 'action' => 'dashboard')); ?></li>
			    </ul>
			</li>
		</ul>
	<?php elseif ($__browser_view_mode['can_toggle_browser_view_mode']) : 
		// Application is in field mode. ?>
		<ul>
			<li<?php echo $class; ?>><?php echo $this->Html->link($this->Html->image('gear-white.png', array('id' => 'utility-gear')), array('controller' => 'business360s', 'action' => 'toggle_view'), array('id' => 'application_settings', 'escape' => false)); ?></li>
		</ul>
	<?php endif; ?>
	<ul class="right">
		<?php if (!empty($__user)): ?>
			<?php if ($__user['User']['group_id'] == GROUP_ADMINISTRATORS_ID): ?>
				<li><?php echo $this->Html->link(__(Inflector::pluralize(Configure::read('Nomenclature.WorkHour'))), array('controller' => 'work_hours', 'action' => 'index')); ?></li>
			<?php endif; ?>
			<li><?php echo $this->Html->link(__('My Account'), array('controller' => 'users', 'action' => 'view_owner')); ?></li>
			<?php if (!empty($__user['ProfileImage']['name'])): ?>
				<li class="image"><?php echo $this->Html->link($this->Image->get('ProfileImage', $__user['ProfileImage'], Configure::read('Images.ProfileImage.tiny')), array('controller' => 'users', 'action' => 'view', $__user['User']['id']), array('escape' => false)); ?></li>
			<?php endif; ?>
			<li class="user"><?php echo $__user['User']['name_first']; ?> <?php echo $__user['User']['name_last']; ?></li>
			<li class="logout"><?php echo $this->Html->link(__('Logout'), array('controller' => 'users', 'action' => 'logout')); ?></li>
		<?php else: ?>
			<li class="user"><?php echo __('You are not logged in.'); ?></li>
			<li class="logout"><?php echo $this->Html->link(__('Login'), array('controller' => 'users', 'action' => 'login')); ?></li>
		<?php endif; ?>
	</ul>
</div>