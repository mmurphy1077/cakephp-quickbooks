<?php 
$invoice_num = Configure::read('Invoice.prefix') . Configure::read('Invoice.start-count') + $this->data['Invoice']['id']; 
$inline_font = 'font-size:18px;';
if(isset($disable_inline_font) && $disable_inline_font == true) {
	$inline_font = '';
}
if(!isset($licenses)) {
	$licenses = null;
} ?>
<div class="header page_element" style="<?php echo $inline_font; ?>">
	<div id="invoice_header" class="page_container">
		<div id="invoice_header_address">
			<?php if (array_key_exists('ApplicationSetting', $applicationSettings) && (!empty($applicationSettings['ApplicationSetting']['company_name']))) : ?>
				<b><?php echo $applicationSettings['ApplicationSetting']['company_name']; ?></b><br />
			<?php endif; ?>
			<?php if(!empty($billingAddress['Location']['phone_billing'])) : ?>
				Phone: <?php echo $billingAddress['Location']['phone_billing']; ?><br />
			<?php elseif(!empty($billingAddress['Location']['phone'])) : ?>
				Phone: <?php echo $billingAddress['Location']['phone']; ?><br />
			<?php endif; ?>
			<?php if(!empty($billingAddress['Address']['line1'])) : ?>
				<?php echo $billingAddress['Address']['line1']; ?><br />
			<?php endif; ?>
			<?php if(!empty($billingAddress['Address']['line2'])) : ?>
				<?php echo $billingAddress['Address']['line2']; ?><br />
			<?php endif; ?>
			<?php echo $billingAddress['Address']['city']; ?>, <?php echo $billingAddress['Address']['st_prov']; ?>&nbsp;<?php echo $billingAddress['Address']['zip_post']; ?><br />
			<?php 
			if(!empty($licenses)) {
				foreach($licenses as $key=>$data) {
					echo $data . '<br />';
				}
			} ?>
		</div>
		<div id="invoice_header_logo">
		<?php if (array_key_exists('CompanyImage', $applicationSettings) && (!empty($applicationSettings['CompanyImage']['bytes']))) {
			$image = $applicationSettings['CompanyImage'];
			echo $this->Html->image('https://s3.amazonaws.com/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket') . '/' . Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder') . '/CompanyImage/' . $image['name'], array('id' => 'company-logo-image', 'class' => 'company-logo-image dim300x130')); 
		} else {
			echo $this->Html->image('logo-company.png'); 
		} ?>
		</div>
		<div id="invoice_header_info">
			<div id="invoice_header_info_container">
				<h1>INVOICE</h1>
				<ul>
					<li>
						<span class="label">Date:</span>
						<span class="value"><?php echo $this->Web->dt($this->data['Invoice']['date_invoiced'], 'short_4'); ?></span>
					</li>
					<li>
						<span class="label">Invoice #:</span>
						<span class="value"><?php echo $invoice_num; ?></span>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>