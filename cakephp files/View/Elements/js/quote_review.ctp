<?php #$this->Html->script('jquery/jquery.form.min', array('inline' => false)); ?>
<script type="text/javascript">
	function addCommas(nStr) {
	    nStr += '';
	    x = nStr.split('.');
	    x1 = x[0];
	    x2 = x.length > 1 ? '.' + x[1] : '';
	    var rgx = /(\d+)(\d{3})/;
	    while (rgx.test(x1)) {
	        x1 = x1.replace(rgx, '$1' + ',' + '$2');
	    }
	    return x1 + x2;
	}
	
	$(document).ready(function(){
		
		/****************************
		* REVIEW PAGE
		*/
		$('#calculate_quote_total').bind('click', function() {
			var total = 0;
			$('input.quote_line_item').each( function() {
				if($(this).val().length) {
					total = parseFloat(total) + parseFloat($(this).val());
				}
			});
			// Include Price Adjustments
			total = total + Number($('#QuotePriceAdjustment').val());
			$('#QuotePriceTotal').val(Number(total).toFixed(2));
			return false;
		});

		function checkLineItem(id) {
			//$('#order_line_item_' + id).css('display', 'inline-block');
			$('#order_item_convert_container_' + id).html('$'+ $('#quote_line_item_' + id).val());
			$('#order_line_item_' + id).val($('#quote_line_item_' + id).val());
			checkConvertToOrderTotal();
		}

		function uncheckLineItem(id) {
			//$('#order_line_item_' + id).css('display', 'none');
			$('#order_item_convert_container_' + id).html('');
			$('#order_line_item_' + id).val('');
			checkConvertToOrderTotal();
		}

		function enableConvertOrderTotal() {
			$('#order_line_item_total').css('display', 'inline-block');
		
			// Add all order line item values.
			var total = 0;
			var order_cost = 0;
			$('.order_line_item').each( function() {
				order_cost = Number($(this).val());
				total = total + order_cost;
			});

			// Populate Line Item Total
			$('#order_line_item_total').html('$'+ parseFloat(total).toFixed(2));
			
			// Populate surcharge total
			var order_adjust = 1;
			if($('#OrderPriceAdjustmentPerc').val()) {
				order_adjust = $('#OrderPriceAdjustmentPerc').val();
			}
			var order_total = total * order_adjust;
			$('#OrderPriceTotal').val(order_total.toFixed(2));
			$('#OrderPriceSubtotal').val(total.toFixed(2));
			/*$('#order_total_display').html('$'+ parseFloat(order_total).toFixed(2))*/
		}

		function checkConvertToOrderTotal() {
			// If any of the LineItems are checked... enable the adjustment and total boxes.
			var kill_total = true;
			$('.order_line_item_check').each(function() {
				if($(this).prop('checked')) {
					kill_total = false;
				}
			});

			if(kill_total) {
				$('#order_total_display').css('display', 'none');
				$('#OrderPriceTotal').css('display', 'none');
				$('#OrderPriceTotal').val('');
				$('#order_line_item_total').css('display', 'none');
				$('#order_line_item_total').html('');
			} else {
				// Only display the order_line_item_price_adjustment if it's not already visible
				if($('#order_total_display').css('display') == 'none') {
					$('#order_total_display').css('display', 'block');
					$('#OrderPriceTotal').css('display', 'inline-block');
					$('#OrderPriceTotal').val($('#QuotePriceTotal').val());
					refreshPercChange('Order');
				}

				enableConvertOrderTotal();
			}
		}

		function recalculateQuoteTotal() {
			// Add all order line item values.
			var total = 0;
			$('.quote_line_item').each( function() {
				total = total + Number($(this).val());
			});

			// Populate surcharge total
			var order_surcharge = 1;
			if($('#QuotePriceAdjustmentPerc').val()) {
				order_surcharge = $('#QuotePriceAdjustmentPerc').val();
			}
			var quote_total = total * order_surcharge;
			$('#quote_total_display').html('$'+ parseFloat(quote_total).toFixed(2))
			$('#QuotePriceTotal').val(quote_total);
		}

		$('#order_line_item_price_adjustment').bind('keyup', function() {
			checkConvertToOrderTotal();
		});

		$('.order_line_item').bind('keyup', function() {
			checkConvertToOrderTotal();
		});

		$('.quote_line_item').bind('keyup', function() {
			recalculateQuoteTotal();
		});
		
		$('#QuotePriceAdjustment').bind('keyup', function() {
			recalculateQuoteTotal();
		});

		$('#QuotePriceAdjustmentPerc').bind('keyup', function() {
			var value = $(this).val();
			if(value < 1) {
				value = 1;
			}

			$(this).val(value);
			recalculateQuoteTotal();
		});

		$('#order_line_item_surcharge').bind('keyup', function() {
			var value = $(this).val();
			if(value < 1) {
				value = 1;
			}

			$(this).val(value);
			checkConvertToOrderTotal();
		});
		
		$('.order_line_item_check').bind('click', function() {
			var id = $(this).attr('id').replace('order_line_item_check_', '');
			if($(this).prop('checked')) {
				checkLineItem(id);
			} else {
				uncheckLineItem(id);
			}
		});

		$('#order_line_item_check_all').bind('click', function() {
			var id = null;
			if($(this).prop('checked')) {
				$('.order_line_item_check').each(function() {
					$(this).prop('checked', true);
					id = $(this).attr('id').replace('order_line_item_check_', '');
					checkLineItem(id);
				});
			} else {
				$('.order_line_item_check').each(function() {
					$(this).prop('checked', false);
					id = $(this).attr('id').replace('order_line_item_check_', '');
					uncheckLineItem(id);
				});
			}
		});

		$(document).on('click', ".cancel_quote_task_unit_add", function () {
			var id = $(this).attr('id').replace('cancel_', '');
			$('div#quote_task_unit_container_' + id).remove();
		});
	});

	/*	
	 *	REVIEW ADJUST UNIT NAME/DECRIPTION UPDATE
	 */
	$(document).ready(function() { 
	    var options = {  
	        success:       showResponse  // post-submit callback 
	    }; 

		// bind to the form's submit event 
	    $('#UpdateQuoteLineItems').submit(function() { 
		   $('div.ajax_loader_inline').css('display', 'block');
	    	
	    	// Clear all (previous) Validation Errors
			$('div.error-message-log').each( function() {
				$(this).html('');
			});

			// VALIDATE
		    var result = validate($(this));
			if(result['valid']) { 
				// Inside event callbacks 'this' is the DOM element so we first 
		        // wrap it in a jQuery object and then invoke ajaxSubmit.
		        $(this).ajaxSubmit(options); 
			} else {
				// Display Validation Errors
				for(var index in result) {
					$(this).find('#error-message-'+index).html(result[index]);
				}
			}
			
			// Enable the submit button.
			$(this).find('input[type="submit"]').attr('disabled',false);
			
			// !!! Important !!! 
	        // always return false to prevent standard browser submit and page navigation 
			return false;
	    }); 

		
	    /**************************
		 *  DETERMINE IF THE USER IS SAVING DATA OR CONVERTING TO ORDER
		 */
		 $('form#ReviewForm input').keydown(function (e) {
		    if (e.keyCode == 13) {
		        e.preventDefault();
		        return false;
		    }
		});
			
		$('#ReviewForm').on('click', "#submit_save", function () {
			$('#task').val('save');
			return true;
		});

		$('#ReviewForm').on('click', "#submit_convert_to_order", function () {
			$('#task').val('convert_to_order');
			return true;
		});
		
		$("#ReviewForm").submit(function( event ) {
			var type = 'Quote';
			if($('#task').val() == 'convert_to_order') {
				type = 'Order';
			} 
			
			if(!checkPriceAdjustment(type)) {
				event.preventDefault();
			}
		});
		
		$('#QuotePriceTotal').focusout(function() {
			checkPriceAdjustment('Quote');
		});
		
		$('#QuotePriceTotal').bind('keyup', function() {
			checkPriceAdjustmentForType('Quote');
		});

		$('#OrderPriceTotal').focusout(function() {
			checkPriceAdjustment('Order');
		});
		
		$('#OrderPriceTotal').bind('keyup', function() {
			checkPriceAdjustmentForType('Order');
		});

		function checkPriceAdjustment(type) {
			// Test the values for the QuotePriceTotal and/or OrderPriceTotal
			var price_adj_is_valid = true;
			price_adj_is_valid = checkPriceAdjustmentForType(type);
			if(!price_adj_is_valid) {
				$('#price_adjust_alert').css('display', 'inline-block');
			}
			
			return price_adj_is_valid;
		}
	});

	function checkPriceAdjustmentForType(type) {
		var price = Number($('#' + type + 'PriceTotal').val());
		var estimated_cost = Number($('#' + type + 'PriceSubtotal').val());
		var result = true;
		if((price - estimated_cost) < 0) {
			// Varify that the price is within 10% of the original value.
			if((price/estimated_cost)*100 < 90) {
				// Value is less than 10%.  Reset the value back to the original (subtotal) value.
				result = false;
			}
		}
		$('#' + type + 'PriceAdjustmentPerc').val((price/estimated_cost).toFixed(4));
		refreshPercChange(type);
		return result;
	}

	function refreshPercChange(type) {
		$('#' + type + '_percentage_change_display').css('display', 'none');
		var quote_change = 0;
		if($('#' + type + 'PriceAdjustmentPerc').val() != 1) {
			var value = $('#' + type + 'PriceAdjustmentPerc').val() * 100;
			var diff = 0;
			var arrow = '&#8593; ';
			$('#' + type + '_percentage_change_display').removeClass('red');
			if((value - 100) > 0) {
				diff = value - 100;
			} else {
				diff = 100 - value;
				arrow = '&#8595;';
				if(diff > 10) {
					// signal alert
					$('#' + type + '_percentage_change_display').addClass('red');
				}
			}
			
			$('#' + type + '_percentage_change_display').html(arrow + ' ' + diff.toFixed(2) + '%');
			$('#' + type + '_percentage_change_display').css('display', 'inline-block');
		}
	}

	function validate(form_obj) {
		var valid = true; 
	    var data = new Array();
	    var error = new Array();

		/**
		* FIELDS
		* Name, Description, Options
		* Verify that a name is present.
		* Verify that a description is present.
		*/
		data['QuoteLineItemNameExternal'] = form_obj.find('#QuoteLineItemNameExternal').val();
		data['QuoteLineItemDescriptionExternal'] = form_obj.find('#QuoteLineItemDescriptionExternal').val();
		data['QuoteLineItemOptionsExternal'] = form_obj.find('#QuoteLineItemOptionsExternal').val();

		if(!data['QuoteLineItemNameExternal'].length){
			valid = false;
			error['QuoteLineItemNameExternal'] = 'Error:  Unit Name is required.';
		}
		if(!data['QuoteLineItemDescriptionExternal'].length){
			valid = false;
			error['QuoteLineItemDescriptionExternal'] = 'Error:  Unit Description is required.';
		}

		error['valid'] = valid;
	    return error; 
	} 

	// post-submit callback 
	function showResponse(responseText, statusText, xhr, $form)  { 
		obj = jQuery.parseJSON(responseText);
		var html = obj.html;
		var message = obj.message;
		var success = obj.success;
		var error = obj.error;
		var line_item_id = obj.id;

		$('div.ajax_loader_inline').css('display', 'none');

		// Append the html to the beginning of the call_container element.
		if(success > 0){
			$('td#cell-' + line_item_id + ' div.cell_container').html(html);

			// Update the data bank
			$('div#line-item-data-bank-' + line_item_id + ' input#name_external').val(obj.name);
			$('div#line-item-data-bank-' + line_item_id + ' input#description_external').val(obj.desc);
			$('div#line-item-data-bank-' + line_item_id + ' input#options_external').val(obj.optn);
			
			$('.ajax-message-log').html(message);
			$('.ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');			
		} else {
			// Error
			$('.error-ajax-message-log').html(message);
			$('.error-ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');
		}
	} 
</script>