<?php
foreach($ui as $data) : 
	switch ($data) : 
		case 'OrderLineItem': 	?>
		<script type="text/javascript">
			$(document).ready(function(){
			    $(document).on("keyup", "#OrderLineItemLaborCostHours", function() {
			    	var hours = $('#OrderLineItemLaborCostHours').attr('value');
			    	var dollar = parseFloat(Math.round((hours*<?php echo Configure::read('Pricing.QuoteLineItem.labor'); ?>) * 100) / 100).toFixed(2);
			    	$('#OrderLineItemLaborCostDollars').attr('value', dollar);
				});
			});
		</script>	
<?php 	break;
	endswitch;
endforeach;
?>