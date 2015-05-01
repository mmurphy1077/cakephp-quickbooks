<?php
$this->extend('/Templates/dashboard');
$pageTitle = 'Dashboard';
$this->assign('pageTitle', $pageTitle);
?>
<?php 
echo $this->element('js'.DS.'jquery', array('ui' => 'datepicker'));
echo $this->Html->script('creationsite/datepicker.init', false);
echo $this->Html->script('creationsite/ajax', false);
?>
<?php $this->start('leftbar'); ?>
		<div class="widget left bg">
			<h3><?php echo __('Statistics'); ?></h3>
			<?php echo $this->Form->create(); ?>
			<?php #echo $this->Form->input('view_by', array('type' => 'radio', 'options' => $optionsStats)); ?>
			<div class="grid flat">
				<div class="col-2of5">
					<?php echo $this->Form->input('Start', array('div' => 'input text short')); ?>
				</div>
				<div class="col-2of5">
					<?php echo $this->Form->input('End', array('div' => 'input text short')); ?>
				</div>
				<div class="col-1of5">
					<?php echo $this->Form->submit(__('Go'), array('id' => 'statistics_go')); ?>
				</div>
			</div>			
			<?php echo $this->Form->end(); ?>
			<ul class="stats h-750">
				<li>
					<span class="label"><?php echo __('Leads Entered'); ?></span>
					<span class="value"><?php echo $stats['leads_entered']; ?></span>
				</li>
				<li>
					<span class="label"><?php echo __('Leads Called'); ?></span>
					<span class="value"><?php echo $stats['leads_called']; ?></span>
				</li>
				<li class="div">
					<span class="label"><?php echo __('Active Quotes'); ?></span>
					<span class="value"><?php echo $stats['active_quotes']; ?></span>
				</li>
				<li>
					<span class="label"><?php echo __('Approved Quotes'); ?></span>
					<span class="value"><?php echo $stats['approved_quotes']; ?></span>
				</li>
				<li class="div">
					<span class="label"><?php echo __('Lead-To-Order Ratio'); ?></span>
					<span class="value"><?php echo $this->Number->toPercentage($stats['lead_to_order']); ?></span>
				</li>
				<li>
					<span class="label"><?php echo __('Target Ratio'); ?></span>
					<span class="value"><?php echo $stats['target_ratio']; ?></span>
				</li>
			</ul>
		</div>
<?php $this->end(); ?>
				<div class="widget center">
					<?php echo $this->Html->link(__('Show More'), array('controller' => 'contacts', 'action' => 'index_leads'), array('class' => 'button medium blue right')); ?>
					<h3 class="left"><?php echo __('Leads'); ?></h3>
					<table class="standard">
						<tr>
							<th><?php echo __('Created'); ?></th>
							<th><?php echo __('Contact'); ?></th>
							<th><?php echo __('Zip'); ?></th>
							<th><?php echo __('Description'); ?></th>
							<th><?php echo __('Assigned'); ?></th>
						</tr>
						<?php if(!empty($results)) : ?>
						<?php 	foreach($results as $key=>$result) : 
									$link = array('controller'=>'contacts', 'action'=>'edit_lead', $result['Contact']['id']); ?>
							<tr id="lead_row_<?php echo $result['Contact']['id']; ?>" class="div-cluetip" title="<?php echo $result['Contact']['name_forward']; ?>" rel="#lead<?php echo $key; ?>">
								<td class="nowrap">
									<?php echo $this->Web->dt($result['Contact']['created'], 'short_4', '12hr'); ?>
									<br /><span class="small light"><?php echo __('By'); ?>&nbsp;<?php echo $this->Web->humanName($result['Creator']); ?></span>
								</td>
								<td class="nowrap">
									<b><?php echo $this->Html->link($result['Contact']['name_forward'], $link); ?></b><br />
									<span class="light"><?php echo $this->Web->phone($result['Contact']); ?></span>
								</td>
								<td><?php echo $result['Address']['zip_post']; ?></td>
								<td class="small"><?php echo $result['Contact']['notes']; ?></td>
								<td><?php echo $this->Web->humanName($result['AccountRep']); ?></td>
							</tr>
						<?php 	endforeach;	?>
						<?php else : ?>
							<tr>
								<td class="nowrap" colspan="5">
									<?php echo __('no_data_returned'); ?>
								</td>
							</tr>
						<?php endif; ?>
					</table>
				</div>
				<div class="widget center">
					<?php echo $this->Html->link(__('Show More'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'my'), array('class' => 'button medium blue right')); ?>
					<h3 class="left"><?php echo __('My Quotes'); ?></h3>
					<table class="standard">
						<tr>
							<th><?php echo __('Quote No.'); ?></th>
							<th><?php echo __('Total'); ?></th>
							<th><?php echo __('Submitted'); ?></th>
							<th><?php echo __('Customer'); ?></th>
							<th><?php echo __('Zip'); ?></th>
							<th><?php echo __('Type'); ?></th>
						</tr>
						<?php if(!empty($my_quotes)) :	?>
						<?php 	foreach($my_quotes as $key1=>$my_quote) : 
									$link = array('controller'=>'quotes', 'action'=>'view', $my_quote['Quote']['id']); ?>
									<tr class="div-cluetip" title="<?php echo $my_quote['Quote']['name']; ?>" rel="#qt<?php echo $key1; ?>">
										<td><?php echo $this->Html->link($my_quote['Quote']['number'], $link); ?></td>
										<td><?php echo $this->Number->currency($my_quote['Quote']['price_total']); ?></td>
										<td class="nowrap">
											<?php echo $this->Web->dt($my_quote['Quote']['created'], 'short_4', '12hr'); ?>
											<br /><span class="small light"><?php echo __('By'); ?>&nbsp;<?php echo $this->Web->humanName($my_quote['Creator']); ?></span>
										</td>
										<td class="nowrap">
											<b><?php echo $my_quote['Quote']['customer_name']; ?></b><br />
											<span class="light"><?php echo $my_quote['Quote']['contact_phone']; ?></span>
										</td>
										<td><?php echo $my_quote['AddressExisting']['zip_post']; ?></td>
										<td><?php echo $my_quote['QuoteType']['name']; ?></td>
									</tr>
						<?php 	endforeach;	?>
						<?php else : ?>
							<tr>
								<td class="nowrap" colspan="6">
									<?php echo __('no_items'); ?>
								</td>
							</tr>
						<?php endif; ?>
					</table>
				</div>
				<div class="widget center">
					<?php echo $this->Html->link(__('Show More'), array('controller' => 'quotes', 'action' => 'index', 'status' => 'all'), array('class' => 'button medium blue right')); ?>
					<h3 class="left"><?php echo __('Company Quotes'); ?></h3>
					<table class="standard">
						<tr>
							<th><?php echo __('Quote No.'); ?></th>
							<th><?php echo __('Total'); ?></th>
							<th><?php echo __('Submitted'); ?></th>
							<th><?php echo __('Customer'); ?></th>
							<th><?php echo __('Zip'); ?></th>
							<th><?php echo __('Type'); ?></th>
							<th><?php echo __('Assigned'); ?></th>
						</tr>
						<?php if(!empty($all_quotes)) :	?>
						<?php 	foreach($all_quotes as $key1=>$all_quote) : 
									$link = array('controller'=>'quotes', 'action'=>'view', $all_quote['Quote']['id']); ?>
									<tr class="div-cluetip" title="<?php echo $all_quote['Quote']['name']; ?>" rel="#qt<?php echo $key1; ?>">
										<td><?php echo $this->Html->link($all_quote['Quote']['number'], $link); ?></td>
										<td><?php echo $this->Number->currency($all_quote['Quote']['price_total']); ?></td>
										<td class="nowrap">
											<?php echo $this->Web->dt($all_quote['Quote']['created'], 'short_4', '12hr'); ?>
											<br /><span class="small light"><?php echo __('By'); ?>&nbsp;<?php echo $this->Web->humanName($all_quote['Creator']); ?></span>
										</td>
										<td class="nowrap">
											<b><?php echo $all_quote['Quote']['customer_name']; ?></b><br />
											<span class="light"><?php echo $all_quote['Quote']['contact_phone']; ?></span>
										</td>
										<td><?php echo $all_quote['AddressExisting']['zip_post']; ?></td>
										<td><?php echo $all_quote['QuoteType']['name']; ?></td>
										<td><?php echo $this->Web->humanName($all_quote['AccountRep']); ?></td>
									</tr>
						<?php 	endforeach;	?>
						<?php else : ?>
							<tr>
								<td class="nowrap" colspan="7">
									<?php echo __('no_items'); ?>
								</td>
							</tr>
						<?php endif; ?>
					</table>
				</div>
				<div class="widget center">
					<?php echo $this->Html->link(__('Show More'), array('controller' => 'quotes', 'action' => 'index', 'status' => QUOTE_STATUS_SOLD), array('class' => 'button medium blue right')); ?>
					<h3 class="left"><?php echo __('Approved Quotes'); ?></h3>
					<table class="standard">
						<tr>
							<th><?php echo __('Quote No.'); ?></th>
							<th><?php echo __('Total'); ?></th>
							<th><?php echo __('Submitted'); ?></th>
							<th><?php echo __('Customer'); ?></th>
							<th><?php echo __('Zip'); ?></th>
							<th><?php echo __('Type'); ?></th>
							<th><?php echo __('Assigned'); ?></th>
						</tr>
						<?php if(!empty($quotes_approved)) :	?>
						<?php 	foreach($quotes_approved as $key1=>$approved_quote) : 
									$link = array('controller'=>'quotes', 'action'=>'view', $approved_quote['Quote']['id']); ?>
									<tr class="div-cluetip" title="<?php echo $approved_quote['Quote']['name']; ?>" rel="#qt<?php echo $key1; ?>">
										<td><?php echo $this->Html->link($approved_quote['Quote']['number'], $link); ?></td>
										<td><?php echo $this->Number->currency($approved_quote['Quote']['price_total']); ?></td>
										<td class="nowrap">
											<?php echo $this->Web->dt($approved_quote['Quote']['created'], 'short_4', '12hr'); ?>
											<br /><span class="small light"><?php echo __('By'); ?>&nbsp;<?php echo $this->Web->humanName($approved_quote['Creator']); ?>></span>
										</td>
										<td class="nowrap">
											<b><?php echo $approved_quote['Quote']['customer_name']; ?></b><br />
											<span class="light"><?php echo $approved_quote['Quote']['contact_phone']; ?></span>
										</td>
										<td><?php echo $approved_quote['AddressExisting']['zip_post']; ?></td>
										<td><?php echo $approved_quote['QuoteType']['name']; ?></td>
										<td><?php echo $this->Web->humanName($approved_quote['AccountRep']); ?></td>
									</tr>
						<?php 	endforeach;	?>
						<?php else : ?>
							<tr>
								<td class="nowrap" colspan="7">
									<?php echo __('no_items'); ?>
								</td>
							</tr>
						<?php endif; ?>
					</table>
				</div>