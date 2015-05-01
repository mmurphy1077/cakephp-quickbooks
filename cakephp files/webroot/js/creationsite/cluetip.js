$(document).ready(function(){
	$('.cluetip').cluetip({
		splitTitle: '|',
		topOffset: 5,
		cursor: 'pointer'
	});
	$('.url-cluetip').cluetip({
		width: 600,
		height: 400
	});
	$('.div-cluetip').cluetip({
		activation: 'click',
		sticky: true,
		width: 797,
		closePosition: 'title',
		closeText: 'X',
		local: true,
	});
	$('.sticky-cluetip').cluetip({
		width: 600,
		sticky: true,
		local: true
	});
	$('.div-clicktip').cluetip({
		activation: 'click',
		sticky: true,
		width: 700,
		closePosition: 'title',
		closeText: 'X',
		local: true
	});
	$('.div-clicktip-short').cluetip({
		activation: 'click',
		sticky: true,
		width: 400,
		closePosition: 'title',
		closeText: 'X',
		local: true,
		onActivate:  function(event) {
			$('input.date_schedule').each(function() {
	        	  $(this).val(today_date());
	        });
		}
	});
	$('.div-clicktip-order-requirement').cluetip({
		activation: 'click',
		sticky: true,
		width: 400,
		closePosition: 'title',
		closeText: 'X',
		local: true,
		onActivate:  function(event) {
			$('input.order_req_datepicker_cluetip').each(function() {
	        	  $(this).val(today_date());
	        });
			  
			// Update the OrderRequirementsOrder.id 
			var id = $(this).attr('id').substring(21); 
			$('input.order_requirements_order_id').each(function() {
	        	  $(this).val(id);
	        });    
		}
	});
	$('.cluetip-schedule-comment').cluetip({
		activation: 'click',
		sticky: true,
		width: '600px', 
		local: true,
		leftOffset: -50,
		topOffset: 20,
		cursor: 'pointer',
		closePosition: 'title',
		closeText: 'X',
	});
});