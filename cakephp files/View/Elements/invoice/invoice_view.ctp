<?php 
$materialTotal = $this->data['Invoice']['material_amount_stock'] + $this->data['Invoice']['material_amount_purchase'];
#$total = $materialTotal + $this->data['Invoice']['labor_amount'] + $this->data['Invoice']['misc_amount'];
$total = $this->data['Invoice']['total'];
$invoice_num = Configure::read('Invoice.prefix') . Configure::read('Invoice.start-count') + $this->data['Invoice']['id'];
$payment_term = '';
if(!empty($this->data['PaymentTerm'])) {
	$payment_term = $this->data['PaymentTerm']['name'];
} 
$inline_font = 'font-size:18px;';
if(isset($disable_inline_font) && $disable_inline_font == true) {
	$inline_font = '';
}
?>
<?php if($this->data['Invoice']['status'] == INVOICE_STATUS_INACTIVE) : ?>
<div id="void"><?php echo $this->Html->image('void.png')?></div>
<?php endif; ?>
<?php if($this->data['Invoice']['status'] == INVOICE_STATUS_PAID) : ?>
<div id="void"><?php echo $this->Html->image('paid.png')?></div>
<?php endif; ?>
<div id="invoice-view-page" class="page">
	<div class="page_container">
		<div id="invoices_addresses" class="grid" style="<?php echo $inline_font; ?>">
			<div class="col-1of2">
				<table>
					<tr>
						<td class="label" rowspan="3">To:&nbsp;&nbsp;</td>
						<td>
							<?php 
							if(!empty($this->data['Invoice']['customer_name'])) {
								echo $this->data['Invoice']['customer_name'].'<br />';
							} ?>
							<?php 
							if(!empty($this->data['Invoice']['contact_name'])) {
								echo $this->data['Invoice']['contact_name'].'<br />';
							} 
							echo $this->Web->address($this->data['Address'], false, '<br />', false, false, true); ?>
						</td>
					</tr>
				</table>
			</div>
			<div class="col-1of2">
				<table>
					<tr>
						<td class="label" rowspan="3">Job:&nbsp;&nbsp;</td>
						<td>
							<?php if(!empty($order['Address']['name'])) {
								echo $order['Address']['name'].'<br />';
							} ?>
							<?php echo $this->Web->address($order['Address'], false, '<br />', false, false, true); ?>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<table id="invoice_po" class="invoice" style="<?php echo $inline_font; ?>">
			<tr>
				<th>PO#</th>
				<th>Project Name</th>
				<th>Payment Terms</th>
				<th>Due Date</th>
			</tr>
			<tr class="bottom border">
				<td><?php echo $this->data['Order']['purchase_order_number']; ?></td>
				<td><?php echo $this->data['Order']['name']; ?></td>
				<td><?php echo $payment_term; ?></td>
				<td><?php echo $this->Web->dt($this->data['Invoice']['date_due'], 'short_4')?></td>
			</tr>
		</table>
		<table id="invoice_qty" class="invoice" style="<?php echo $inline_font; ?>">
			<tr class="border">
				<th>Qty</th>
				<th class="col-wide">Description</th>
				<th>Unit Cost</th>
				<th>Line Total</th>
			</tr>
			<?php 
			$has_records = false;
			if(!empty($this->data['InvoiceMaterialItem'])) : 
				$has_records = true;
				foreach($this->data['InvoiceMaterialItem'] as $material_stock) : 
					$cost = 0;
					if(!empty($material_stock['cost'])) {
						$cost = $material_stock['cost'];
					}
					$desc = $material_stock['description']; ?>
			<tr class="border">
				<td><?php echo number_format($material_stock['qty'], 2); ?></td>
				<td class="col-wide"><?php echo $desc; ?></td>
				<td>$<?php echo number_format($material_stock['unit_cost'], 2); ?></td>
				<td class="align_right">$<?php echo number_format($cost, 2); ?></td>
			</tr>
			<?php 
				endforeach;
			endif; 
			
			if(!empty($this->data['Invoice']['vanstock_used'])) : ?>
			<tr class="border">
				<td>&nbsp;</td>
				<td class="col-wide"><?php echo $this->data['Invoice']['vanstock_used']; ?></td>
				<td>&nbsp;</td>
				<td class="align_right">
					<?php 
					/*
					if(!empty($this->data['Invoice']['labor_amount'])) : ?>
					<b>Sub Total:&nbsp;&nbsp;$<?php echo number_format($this->data['Invoice']['labor_amount'], 2); ?></b>
					<?php endif; 
					*/
					?>
				</td>
			</tr>
			<?php 
			endif; ?>
			<?php 
			/**
			 * TOTALS
			 */
			?>
			<tr class="bottom border">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td class="align_right">&nbsp;</td>
			</tr>
			<tr>
				<td colspan="3">&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td class="align_right" colspan="4"><b>Total:</b>&nbsp;&nbsp;&nbsp;&nbsp;<b>$ <?php echo number_format($total, 2); ?></b></td>
			</tr>
		</table>
		<div class="clear" style="<?php echo $inline_font; ?> margin-top:30px;">
			<?php 
			if(!empty($this->data['Invoice']['work_performed'])) : ?>
			<b>Work Performed:</b><br />
			<?php echo nl2br($this->data['Invoice']['work_performed']); ?>
			<?php endif;?>
		</div>
	</div>
</div>