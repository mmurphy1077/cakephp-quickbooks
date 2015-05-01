<?php
switch ($layout) {
	case 'table':
	default:
		?>
		<?php if (!empty($phoneNumbers['phone_1_number'])): ?>
			<tr>
				<th><?php echo __($phoneNumbers['phone_1_label']); ?> <?php echo __('Phone'); ?></th>
				<td><?php echo $phoneNumbers['phone_1_number']; ?></td>
			</tr>
		<?php endif; ?>
		<?php if (!empty($phoneNumbers['phone_2_number'])): ?>
			<tr>
				<th><?php echo __($phoneNumbers['phone_2_label']); ?> <?php echo __('Phone'); ?></th>
				<td><?php echo $phoneNumbers['phone_2_number']; ?></td>
			</tr>
		<?php endif; ?>
		<?php if (!empty($phoneNumbers['phone_3_number'])): ?>
			<tr>
				<th><?php echo __($phoneNumbers['phone_3_label']); ?> <?php echo __('Phone'); ?></th>
				<td><?php echo $phoneNumbers['phone_3_number']; ?></td>
			</tr>
		<?php endif; ?>
		<?php
		break;
}
?>