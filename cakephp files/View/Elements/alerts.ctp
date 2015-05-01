<?php 
$container_class = '';
?>
<div class="alert-container-quotes <?php echo $container_class; ?>" id="alert-container-quotes-index">
<?php 
#echo $this->Form->hidden('model', array('id' => 'model', 'value' => 'quotes'));
if(!empty($alerts)) :
	foreach($alerts as $key=>$data) : 
		if(!empty($data['data'])) : 
			$count = count($data['data']);
			$class = 'collapse';
			$table_class = 'hide';
			if(!empty($expanded_alerts) && in_array('alert_'.$key, $expanded_alerts)) {
				$class = 'expand';
				$table_class = '';
			}
?>
	<div class="category-header">
		<div class="name"><span class="<?php echo $data['type']; ?>"><?php echo ucfirst($data['type']); ?>: </span><b><?php echo __($data['title']); ?></b></div>
		<div class="action">
			<div id="alert_<?php echo $key;?>" class="alerts_button <?php echo $class; ?> button"><?php echo $count; ?></div>
		</div>
	</div>
	<div id="alert_table_container_alert_<?php echo $key; ?>" class="alert_table_container <?php echo $table_class; ?>">
		<table id="" class="standard material category_table nohover">
			<?php 
			foreach($data['data'] as $order) : 
				switch  ($data['alert_type']) { 
					case 'Contact' :
						$col1 = '<b>Lead: </b>' . $order['Contact']['name_first'] . ' ' . $order['Contact']['name_last'];
						$col2 = '' . $order['Contact']['company_name'];
						$link = $data['redirect'];
						$link[] = $order['Contact']['id'];
						break;
						
					case 'Invoice' : 
						$col1 = '<b>Order: </b>' . $order['Invoice']['name'];
						$col2 = '<b>For: </b>' . $order['Invoice']['customer_name'];
						$link = $data['redirect'];
						$link[] = $order['Invoice']['id'];
						break;
						
					case 'OrderTask' :
						switch($key) {
							case 'warning_task_not_started' :
								$col1 = '<b>Order: </b>'. $order['Order']['order_name'];
								$col2 = '<b>Requirement: </b>' . $order['Order']['item'] . '<br/ >' . '<span class="small light">Assigned To: <b>' . $order['Order']['assigned_to'] . '</b><br />Start Date: <b>' . $this->Web->dt($order['Order']['date_start'], 'short_4') . '</b></span>';
								$link = $data['redirect'];
								$link[] = $order['Order']['order_id'];
								$link[] = $order['Order']['id'];
								break;
								
							case 'alert_task_due_date_expired' :
							default :
								$col1 = '<b>Order: </b>'. $order['Order']['order_name'];
								$col2 = '<b>Requirement: </b>' . $order['Order']['item'] . '<br/ >' . '<span class="small light">Assigned To: <b>' . $order['Order']['assigned_to'] . '</b><br />Due: <b>' . $this->Web->dt($order['Order']['date_request'], 'short_4') . '</b></span>';
								$link = $data['redirect'];
								$link[] = $order['Order']['order_id'];
								$link[] = $order['Order']['id'];
								break;
						}
						break;
						
					case 'Order' :
					default :	
						$col1 = '<b>Order: </b>' . $order['Order']['name'];
						$col2 = '<b>For: </b>' . $order['Order']['customer_name'];
						$link = $data['redirect'];
						$link[] = $order['Order']['id'];
				} ?>
				<tr>
					<td class="large">
						<?php echo CELL_PAD; ?>
						<?php echo $col1; ?>
					</td>
					<td class="large">
						<?php echo $col2; ?>
					</td>
					<td class="actions">
					<?php 
					echo $this->Html->link('go', $link); ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	</div>
<?php	endif;
	endforeach;
endif; ?>
</div>