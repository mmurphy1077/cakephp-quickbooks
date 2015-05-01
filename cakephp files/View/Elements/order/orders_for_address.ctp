<?php 
if(!empty($orders_for_address)) : ?>
	<div class="row">
		<?php 	foreach ($orders_for_address as $otherJob) : ?>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<h4><?php echo $otherJob['Order']['name']; ?></h4>
					<?php if(!empty($otherJob['Order']['description'])) : ?>
						<div class="left">
							<b>Scope of Work</b><br />
							<?php echo $otherJob['Order']['description']; ?><br /><br />&nbsp;
						</div>
					<?php endif; ?>
				</div>
		<?php 		if(!empty($otherJob['OrderLineItem'])) : ?>
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<?php 		foreach($otherJob['OrderLineItem'] as $key=>$otherItem) : ?>
						<div class="item-container clear left">
							<div class="black-pill item-number left clear"><?php echo str_pad($key + 1, 3, 0, STR_PAD_LEFT); ?></div>
							<div class="item-desc clear left">
								<b><?php echo $otherItem['name']; ?></b><br/>
								<?php echo $otherItem['description']; ?>
							</div>
						</div>
		<?php 		endforeach;?>
				</div>
		<?php 		endif; ?>
					
				<div id="previous-order-comments-container" class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		<?php 		if(!empty($otherJob['Comments'])) : ?>
						<h4>Comments</h4>
						<h5>The following comments were posted at the <?php echo Configure::read('Nomenclature.Order'); ?> level</h5>
						<?php echo $this->element('communication/message_view', array('messages' => $otherJob['Comments'], 'mode' => 'view_message_thread_only', 'style' => 'comment', 'display' => 'inline')); ?>
		<?php 		endif; ?>
		
						<h5>The following comments were posted at the <?php echo Configure::read('Nomenclature.Order'); ?> Time level</h5>
		<?php 		if(!empty($otherJob['OrderTime'])) : ?>
		<?php 			foreach($otherJob['OrderTime'] as $key=>$orderTime) : ?>
		<?php 				if(!empty($orderTime['Comments'])) : ?>
		<?php 					echo $this->element('communication/message_view', array('messages' => $orderTime['Comments'], 'mode' => 'view_message_thread_only', 'style' => 'comment', 'display' => 'inline')); ?>
		<?php 				endif;?>
		<?php 			endforeach;?>
		<?php 		endif; ?>			
				</div>
					
		
		<?php 	endforeach; ?>
		</div>
	</div>
<?php endif; ?>