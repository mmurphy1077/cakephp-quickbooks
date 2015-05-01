<?php 
$border_bottom = '';
if($border) {
	$border_bottom = 'border_bottom';
} ?>
<tr class="daily-total <?php echo $border_bottom; ?>">
	<td>&nbsp;</td>
	<td colspan="1">&nbsp;</td>
	<td class="">Daily Total: <b><?php echo number_format($total_daily_time, 2); ?>&nbsp;hours</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	<td class="hidden-xs hidden-sm"><?php echo __(''); ?></td>
	<td colspan="4">&nbsp;</td>
</tr>