<div id="pdf_footer">
	<?php $primaryAddress = $this->Session->read('Application.address.primary');?>
	<?php echo $primaryAddress['Address']['line1']; ?>&nbsp;&nbsp;&bull;&nbsp;&nbsp;
	<?php echo $primaryAddress['Address']['line2']; ?>&nbsp;&nbsp;&bull;&nbsp;&nbsp;Ph: 
	<?php echo $primaryAddress['Location']['phone']; ?>
</div>