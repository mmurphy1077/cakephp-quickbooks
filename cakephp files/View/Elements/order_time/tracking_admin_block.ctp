<h4><?php echo __('Administrative'); ?></h4>
<fieldset>
	<div class="fieldset-wrapper">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="clear">
					<label><?php echo __('Actions'); ?></label>
					<div class="buttonset"><?php echo $this->Form->radio('OrderTime.admin_status', $admin_statuses, array_merge($permission_attr, array('div' => array('class' => 'input larger_label_space'), 'legend' => false))); ?></div><br />&nbsp;
				</div>
				<div class="clear">
					<label><?php echo __('Rate'); ?></label>
					<div id="timesheet-hours">
						<div><?php 
							$empty = '';
							$empty_value = null;
							$rate_id = $this->data['OrderTime']['rate_id'];
							$rate = $this->data['OrderTime']['rate'];
							if(!empty($rate_id) && (!array_key_exists($rate_id, $rates_data_banks) || $rate != $rates_data_banks[$rate_id]['Rate']['rate'])) {
								$name = '';
								if(array_key_exists($rate_id, $rates_data_banks)) {
									$name = $rates_data_banks[$rate_id]['Rate']['name'] . ' - ';
								}
								$empty = $name . number_format($rate, 2);
								$rate_id = null;
								$empty_value = number_format($rate, 2);
							}
							echo $this->Form->input('OrderTime.rate_id', array_merge($permission_attr, array('label' => false, 'class' => 'numerical', 'div' => false, 'before' => '$', 'after' => ' per hour', 'value' => $rate_id, 'empty' => $empty))); 
							echo $this->Form->hidden('OrderTime.rate', array('value' => $rate)); ?>
							<div id="rate-data-bank" class="hide">
								<div id="empty" class="rate-container">
									<?php echo $this->form->hidden('id', array('id' => 'id', 'value' => null)); ?>
									<?php echo $this->form->hidden('name', array('id' => 'name', 'value' => null)); ?>
									<?php echo $this->form->hidden('rate', array('id' => 'rate', 'value' => $empty_value)); ?>
								</div>
								<?php 
								if(!empty($rates_data_banks)) :
									foreach($rates_data_banks as $key => $rates_data_bank) : ?>
									<div id="rate-container-<?php echo $rates_data_bank['Rate']['id']; ?>" class="rate-container">
										<?php echo $this->form->hidden('id', array('id' => 'id', 'value' => $rates_data_bank['Rate']['id'])); ?>
										<?php echo $this->form->hidden('name', array('id' => 'name', 'value' => $rates_data_bank['Rate']['name'])); ?>
										<?php echo $this->form->hidden('rate', array('id' => 'rate', 'value' => $rates_data_bank['Rate']['rate'])); ?>
									</div>
								<?php endforeach;
								endif; ?>
							</div>
						</div>
					</div>
				</div>
				<div class="clear">
					<label><?php echo __('Expense Rate'); ?></label>
					<div id="timesheet-hours">
						<div><?php 
							$value = null;
							if(!empty($this->data['OrderTime']['expense_rate'])) {
								$value = number_format($this->data['OrderTime']['expense_rate'], 2);
							}
							echo $this->Form->input('OrderTime.expense_rate', array_merge($permission_attr, array('label' => false, 'class' => 'num_only', 'div' => false, 'before' => '$', 'after' => ' per hour', 'value' => $value))); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</fieldset>