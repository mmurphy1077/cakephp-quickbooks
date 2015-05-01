<?php 
echo $this->Html->script('creationsite/activity.log', array('inline' => false)); 
if(!empty($header)) : 
	foreach($header as $key=>$data) : ?>
		<div class="category-header">
			<div class="name"><b><?php echo $data; ?></b></div>
			<div class="action">
				<div id="materials_button_<?php echo $key; ?>" class="materials_button collapse button"></div>
			</div>
		</div>
		<div id="category_table_container_<?php echo $key; ?>" class="category_table_container">
			<?php
			$results =  $result[$key];
			if(empty($results)) : ?>
			<?php echo $this->element('info', array('content' => array(__('no_items')))); ?>
			<?php else : ?>
			<table id="category_table_<?php echo $key; ?>" class="standard material category_table">
				<?php $date = ''; ?>
				<?php foreach($results as $log_result) : 
						if($date != $this->Web->dt($log_result['ActionLog']['created'], 'text_short')) {
							$display_date = $this->Web->dt($log_result['ActionLog']['created'], 'text_short') . ' ' . date('(l)', strtotime($log_result['ActionLog']['created']));
							$date = $this->Web->dt($log_result['ActionLog']['created'], 'text_short');
						} else {
							$display_date = null;
						}
				?>
				<tr>
					<td class="nowrap"><?php echo $display_date; ?></td>
					<td class="nowrap"><?php echo $this->Web->dt($log_result['ActionLog']['created'], null, '12hr_zero'); ?></td>
					<td><?php echo $log_result['ActionLog']['user_name'] . ' ' . $log_result['ActionLog']['action']; ?></td>
				</tr>
				<?php endforeach; ?>
			</table>
			<?php endif; ?>
		</div>
<?php 
	endforeach;
endif; ?>