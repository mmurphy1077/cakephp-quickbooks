<?php 
$inline_font = 'font-size:18px;';
if(isset($disable_inline_font) && $disable_inline_font == true) {
	$inline_font = '';
} ?>
<div id="invoice-view-page" class="page">
	<div class="clear" style="<?php echo $inline_font; ?>">
		If you have any questions or concerns please contact us:<br />
		<?php if(!empty($billingAddress['Location']['email_billing'])) : ?>
			<a href="mailto:<?php echo $billingAddress['Location']['email_billing']; ?>"><?php echo $billingAddress['Location']['email_billing']; ?></a><br />
		<?php else: 
			  if(!empty($this->data['Order']['AccountRep']['email'])) : ?>
			<a href="mailto:<?php echo $this->data['Order']['AccountRep']['email']; ?>"><?php echo $this->data['Order']['AccountRep']['email']; ?></a><br />
		<?php endif;
		endif; ?>
		<?php if(!empty($applicationSettings['ApplicationSetting']['company_url'])) : ?>
			<a href="<?php echo $applicationSettings['ApplicationSetting']['company_url']; ?>"><?php echo $applicationSettings['ApplicationSetting']['company_url']; ?></a><br />
		<?php endif; ?>
		<br>
	</div>
	<div id="invoice_footer" class="clear" style="<?php echo $inline_font; ?>">
		<b>Thank you for your business!</b>
		<br />
		<?php echo $billingAddress['Address']['line1']; ?>
		<?php if(!empty($billingAddress['Address']['line2'])) : ?>
		, <?php echo $billingAddress['Address']['line2']; ?>
		<?php endif; ?>
		, <?php echo $billingAddress['Address']['city']; ?>
		, <?php echo $billingAddress['Address']['st_prov']; ?>
		, <?php echo $billingAddress['Address']['zip_post']; ?>
	</div>
	<div id="trademark-left" class="trademark-container"></div>
	<div id="trademark-right" class="trademark-container">powered by <?php echo $this->Html->image('my360e-trademark-bw.png', array('id' => 'footer-trademark')); ?></div>
</div>