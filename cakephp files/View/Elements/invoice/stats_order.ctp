<fieldset>
	<div class="fieldset-wrapper">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<h4>Statistics</h4>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="row">
							<div class="clear col-xs-4 col-sm-4 col-md-4 col-lg-4">
								Est./Quote
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<?php echo '$'.number_format($order['Order']['price_total'], 2); ?>
								<?php if(!empty($order['Order']['price_nte'])) : ?>
								<br />
								<b>nte</b> <?php echo '$'.number_format($order['Order']['price_nte'], 2); ?>
								<?php endif; ?>
							</div>
							
							<div class="clear col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<b>Expenses</b>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<b><?php echo '$'.number_format($stats['total']['expense'], 2); ?></b>
							</div>
							<div class="clear col-xs-4 col-sm-4 col-md-4 col-lg-4">&nbsp;</div>
						</div>	
					</div>
					<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
						<div class="row">
							<div class="clear col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<b>Cost</b>
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<b><?php echo '$'.number_format($stats['total']['billable'], 2); ?></b>
							</div>	 
							<div class="clear col-xs-4 col-sm-4 col-md-4 col-lg-4">
								Margin
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<?php echo number_format($stats['total']['margin'], 2) . '%'; ?>
							</div>
							<div class="clear col-xs-4 col-sm-4 col-md-4 col-lg-4">
								Markup
							</div>
							<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
								<?php echo number_format($stats['total']['markup'], 2) . '%'; ?>
							</div>
							<div class="clear col-xs-4 col-sm-4 col-md-4 col-lg-4">&nbsp;</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-6">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
						<div class="row">
						<?php //  INVOICE STATS?>
							<div class="col-xs-4 col-sm-6 col-md-6 col-lg-6 clear">
								<b>Invoices</b>
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<b><?php echo '$'.number_format($stats['invoice']['total'], 2); ?></b>
							</div>
							<?php
							$perc = 0;
							$perc_display = 0;
							$folder = 'grey';
							if($stats['total']['margin_invoice'] > 0) {
								$perc_display = $stats['total']['margin_invoice'];
								$perc = intval(round($stats['total']['margin_invoice'],-1));
								$folder = 'grey';
								
								if($stats['total']['margin_invoice'] >= $stats['total']['margin']) {
									$perc = 100;
									$folder = 'green';
								}
							} ?>
							<div class="clear col-xs-4 col-sm-6 col-md-6 col-lg-6">
								Margin
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<?php echo number_format($perc_display, 2) . '%'; ?>
							</div>
							<?php
							$perc = 0;
							$perc_display = 0;
							$folder = 'grey';
							if($stats['total']['markup_invoice'] > 0) {
								$perc_display = $stats['total']['markup_invoice'];
								$perc = intval(round($stats['total']['markup_invoice'],-1));
								$folder = 'grey';
								
								if($stats['total']['markup_invoice'] >= $stats['total']['markup']) {
									$perc = 100;
									$folder = 'green';
								}
							} ?>
							<div class="clear col-xs-4 col-sm-6 col-md-6 col-lg-6">
								Markup
							</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
								<?php echo number_format($perc_display, 2) . '%'; ?>
							</div>					
						</div>
						<div class="hidden-lg">&nbsp;</div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
						<div class="row">
							<?php 
							if(!empty($order['Order']['price_total'])) : 
								$perc = 0;
								$perc_display = 0;
								$folder = 'grey';
								if($order['Order']['price_total'] > 0) {
									$perc_display = ($stats['invoice']['total']/$order['Order']['price_total']) * 100;
									$perc = intval(round($perc_display,-1));
									$folder = 'grey';
									/*
									if($stats['invoice']['total'] < $stats['total']['expense']) {
										// Invoice amount hasn't even covered expenses.
										$folder = 'red';
									} else if($perc_display >= 100) {
										$perc = 100;
										$folder = 'green';
									}
									*/
								} ?>
								<div class="clear col-xs-4 col-sm-6 col-md-6 col-lg-6">
									Invoice/Est. Ratio
								</div>
								<div class="col-xs-8 col-sm-6 col-md-6 col-lg-6">
									<?php echo number_format($perc_display, 2) . '%'; ?>
								</div>
							<?php endif; ?>
							<?php 
							if(!empty($stats['total']['billable'])) : 
								$perc = 0;
								$perc_display = 0;
								$folder = 'grey';
								if($order['Order']['price_total'] > 0) {
									$perc_display = ($stats['invoice']['total']/$stats['total']['billable']) * 100;
									$perc = intval(round($perc_display,-1));
									$folder = 'grey';
									
									if($stats['invoice']['total'] < $stats['total']['expense']) {
										// Invoice amount hasn't even covered expenses.
										$folder = 'red';
									} else if($perc_display >= 100) {
										$perc = 100;
										$folder = 'green';
									} else if($stats['invoice']['total'] >= $stats['total']['expense']) {
										$perc = 100;
										$folder = 'orange';
									}
								} ?>
								<div class="pie-container clear col-xs-4 col-sm-6 col-md-6 col-lg-6">
									<b>Invoice/Cost Ratio</b>
								</div>
								<div class="pie-container col-xs-8 col-sm-6 col-md-6 col-lg-6">
									<b><?php echo number_format($perc_display, 2) . '%'; ?></b>
									<?php echo $this->Html->image('pie/'.$folder.'/'.$perc.'.png', array('class' => 'pie-invoice-stats')); ?>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="note small light clear col-xs-12 col-sm-12 col-md-12 col-lg-12">
					<br />
					&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*Margin/Markup percentages use Cost/Invoice versus Expense values.  
				</div>
			</div>
		</div>
	</div>
	<br />
</fieldset>