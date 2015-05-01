<div class="grid">
	<div class="col-1of2">
		<table class="data1">
			<tr>
				<th><?php echo __('Created By'); ?></th>
				<td>
					<?php if (!empty($contact['Creator']['id'])): ?>
						<?php echo $contact['Creator']['name_first']; ?>
						<?php echo $contact['Creator']['name_last']; ?>
					<?php endif; ?>
					&nbsp;
				</td>
			</tr>
			<tr>
				<th><?php echo __('Created On'); ?></th>
				<td><?php echo $this->Time->niceShort($contact['Creator']['created']); ?></td>
			</tr>
			<tr>
				<th><?php echo __('Account Rep'); ?></th>
				<td>
					<?php if (!empty($contact['AccountRep']['id'])): ?>
						<?php echo $contact['AccountRep']['name_first']; ?>
						<?php echo $contact['AccountRep']['name_last']; ?>
					<?php endif; ?>
					&nbsp;
				</td>
			</tr>
			<tr>
				<th><?php echo __('Email'); ?></th>
				<td>
					<?php if (!empty($contact['email'])): ?>
						<a href="mailto:<?php echo $contact['email']; ?>"><?php echo $contact['email']; ?></a>
					<?php endif; ?>
					&nbsp;
				</td>
			</tr>
			<tr>
				<th><?php echo __('Title'); ?></th>
				<td>
					<?php if (!empty($contact['title'])): ?>
						<?php echo $contact['title']; ?>
					<?php endif; ?>
					&nbsp;
				</td>
			</tr>
			<tr>
				<th><?php echo __('Type'); ?></th>
				<td>
					<?php if (!empty($contact['ContactType']['name'])): ?>
						<?php echo $contact['ContactType']['name']; ?>
					<?php endif; ?>
					&nbsp;
				</td>
			</tr>
			<tr>
				<th><?php echo __('Customer Source'); ?></th>
				<td>
					<?php if (!empty($contact['CustomerSource']['name'])): ?>
						<?php echo $contact['CustomerSource']['name']; ?>
					<?php endif; ?>
					&nbsp;
				</td>
			</tr>
		</table>
	</div>
	<div class="col-1of2">
		<table class="data1">
			<tr>
				<th><?php echo __('Address'); ?></th>
				<td>
					<?php if (!empty($contact['Address'])): ?>
						<?php echo $this->Web->address($contact['Address']); ?>
					<?php endif; ?>
				</td>
			</tr>
			<?php echo $this->element('phone_numbers_view', array('phoneNumbers' => $contact, 'layout' => 'table')); ?>
			<tr>
				<th><?php echo __('Download vCard'); ?></th>
				<td>
					<?php echo $this->Html->link($this->Html->image('icon-vcard24.png'), array('controller'=>'contacts', 'action'=>'generate_vcard', $contact['id']), array('escape'=>false, 'class'=>'vcard24')); ?>
				</td>
			</tr>
		</table>
	</div>
	<div class="actions">
	<?php if ($__user['Group']['id'] == GROUP_ADMINISTRATORS_ID): ?>
		<?php echo $this->Html->link(__('Edit'), array('controller' => 'contacts', 'action' => 'edit', $contact['id'], 'Customer', $result['Customer']['id']), array('class' => 'button medium blue')); ?>
	<?php endif; ?>
	</div>
</div>