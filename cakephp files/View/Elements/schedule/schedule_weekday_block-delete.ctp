<div class="grid grid-12col block">
	<?php for ($c1 = 1; $c1 <= 12; $c1++): ?>
		<div id="<?php echo 'employee_id_'. $i .'_'.date('Ymd', strtotime($weekdate)).'_'.($c1 + 6).'00'; ?>" class="employee_record clicktip-schedule"  title="Scheduled Job" rel="#scheduled-job">
			<div>&nbsp;</div>
		</div>
	<?php endfor; ?>
</div>
