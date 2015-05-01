<h3><?php echo Configure::read('Nomenclature.Invoice').' Info'; ?></h3>	
<h1>Customer Info</h1>

<?php echo $order['Order']['customer_name']; ?> <br />
<?php echo $invoice['Invoice']['contact_name']; ?> (<?php echo  $invoice['Invoice']['contact_phone']; ?>)<br />
<?php echo $this->Web->address($invoice['Address'], false); ?><br />
<?php echo $order['Order']['contact_email']; ?>

<h1>Job Info</h1>
<?php echo $this->Web->address($order['Address'], false); ?><br />

<h1>Labor</h1>
<ul class="stats">
	<li>1hr @ $135.00/hr <span class="value">$135.00</span></li>
	<li>8hrs @ $88.00/hr <span class="value">$704.00</span></li>
	<li>8hrs @ $55.00/hr <span class="value">$440.00</span></li>
</ul>
<br />
Removal of all outdated wiring.<br />
Replace with Romex wiring.<br />
Dry-wall/paint and finish work.<br />
<br /><br />
<span class="label">Subtotal</span>
<span class="value">$1,279.00</span>
<br />
<br />
<h1>Materials</h1>
<h1>Purchases</h1>
<br />
<br />
<br />
<span class="label total">TOTAL</span>
<span class="value total"><?php echo '$'.number_format($invoice['Invoice']['total'], 2); ?></span>