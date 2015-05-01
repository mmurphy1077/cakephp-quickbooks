<?php 
$job_site['labor'] = number_format($invoice['Invoice']['labor_amount_jobsite'], 2);
$job_site['material'] = number_format($invoice['Invoice']['material_amount_jobsite'], 2);
$job_site['total'] = number_format($invoice['Invoice']['total_jobsite'], 2);
$est['labor'] = number_format($invoice['Invoice']['labor_amount_est'], 2);
$est['material'] = number_format($invoice['Invoice']['material_amount_est'], 2);
$est['total'] = number_format($invoice['Invoice']['total_est'], 2);
$actual['labor'] = number_format($invoice['Invoice']['labor_amount'], 2);
$actual['material'] = number_format($invoice['Invoice']['material_amount'], 2);
$actual['total'] = number_format($invoice['Invoice']['total'], 2);
$ratio['labor'] = 0;
$ratio['material'] = 0;
$ratio['total'] = 0;
$ratio['labor_perc'] = 0;
$ratio['labor_folder'] = 0;
$perc = 0;
$folder = 'grey';
if($invoice['Invoice']['labor_amount_jobsite'] > 0) {
	$ratio['labor'] = number_format(($invoice['Invoice']['labor_amount'] / $invoice['Invoice']['labor_amount_jobsite'])*100, 2);
	if($ratio['labor'] > 0) {
		$perc = intval(round($ratio['labor'],-1));
	}
	if($perc >= 100) {
		$perc = 100;
		$folder = 'green';
	}
}
$ratio['labor_perc'] = $perc;
$ratio['labor_folder'] = $folder;

$perc = 0;
$folder = 'grey';
if($invoice['Invoice']['material_amount_jobsite'] > 0) {
	$ratio['material'] = number_format(($invoice['Invoice']['material_amount'] / $invoice['Invoice']['material_amount_jobsite'])*100, 2);

	if($ratio['material'] > 0) {
		$perc = intval(round($ratio['material'],-1));
	}
	if($perc >= 100) {
		$perc = 100;
		$folder = 'green';
	}
}
$ratio['material_perc'] = $perc;
$ratio['material_folder'] = $folder;

$perc = 0;
	$folder = 'grey';
if($invoice['Invoice']['total_est'] > 0) {
	$ratio['total'] = number_format(($invoice['Invoice']['total'] / $invoice['Invoice']['total_est'])*100, 2);
	
	if($ratio['total'] > 0) {
		$perc = intval(round($ratio['total'],-1));
	}
	if($perc >= 100 && $ratio['total'] < 100) {
		$perc = 90;
	}
	if($perc >= 100) {
		$perc = 100;
		$folder = 'green';
	} 
}
$ratio['total_perc'] = $perc;
$ratio['total_folder'] = $folder;
?>
<div id="invoice-stats-container" class="row">
	<div class="align-right col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div class="row">
			<div class="invoice-stats-header align-right col-xs-2 col-sm-2 col-md-2 col-lg-2">&nbsp;</div>
			<div class="invoice-stats-header col-xs-2 col-sm-2 col-md-2 col-lg-2">Estimated</div>
			<div class="invoice-stats-header col-xs-2 col-sm-2 col-md-2 col-lg-2">Job Site</div>
			<div class="invoice-stats-header col-xs-2 col-sm-2 col-md-2 col-lg-2">Invoiced</div>
			<div class="invoice-stats-header col-xs-2 col-sm-2 col-md-2 col-lg-2">&nbsp;</div>
			<div class="invoice-stats-header col-xs-2 col-sm-2 col-md-2 col-lg-2">&nbsp;</div>
		</div>
		<div class="row">
			<div class="align-right clearfix col-xs-2 col-sm-2 col-md-2 col-lg-2">Labor:</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><?php echo '$'.$est['labor']; ?></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><?php echo '$'.$job_site['labor']; ?></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><?php echo '$'.$actual['labor']; ?></div>
			<div class="align-right col-xs-3 col-sm-3 col-md-3 col-lg-3">	
				<?php echo $ratio['labor']; ?> %
				<?php echo $this->Html->image('pie/'.$ratio['labor_folder'].'/'.$ratio['labor_perc'].'.png', array('class' => 'pie-invoice-stats')); ?>
			</div>
			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</div>
		</div>
		<div class="row">
			<div class="align-right clearfix col-xs-2 col-sm-2 col-md-2 col-lg-2">Material:</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><?php echo '$'.$est['material']; ?></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><?php echo '$'.$job_site['material']; ?></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><?php echo '$'.$actual['material']; ?></div>
			<div class="align-right col-xs-3 col-sm-3 col-md-3 col-lg-3">	
				<?php echo $ratio['material']; ?> %
				<?php echo $this->Html->image('pie/'.$ratio['material_folder'].'/'.$ratio['material_perc'].'.png', array('class' => 'pie-invoice-stats')); ?>
			</div>
			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</div>
		</div>
		<div class="row">
			<div class="align-right clearfix col-xs-2 col-sm-2 col-md-2 col-lg-2"><b>Total:</b></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
				<b><?php echo '$'.$est['total']; ?></b>
				<?php if(!empty($invoice['Invoice']['total_est_nte'])) : ?>
				<br /><span class="small">(NTE: $<?php echo number_format($invoice['Invoice']['total_est_nte'], 2); ?>)</span>
				<?php endif; ?>
			</div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><b><?php echo '$'.$job_site['total']; ?></b></div>
			<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2"><b><?php echo '$'.$actual['total']; ?></b></div>
			<div class="align-right col-xs-3 col-sm-3 col-md-3 col-lg-3">	
				<b><?php echo $ratio['total']; ?> %</b>
				<?php echo $this->Html->image('pie/'.$ratio['total_folder'].'/'.$ratio['total_perc'].'.png', array('class' => 'pie-invoice-stats')); ?>
			</div>
			<div class="col-xs-1 col-sm-1 col-md-1 col-lg-1">&nbsp;</div>
		</div>
	</div>
</div>