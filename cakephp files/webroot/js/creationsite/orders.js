function calculateLabor() {
	var total = 0;
	$('.labor_cost_dollars').each( function() {
		total = total + Number($(this).val());
	});
	return total;
}

function clearOrderContacts() {
	var id = $('#OrderId').val();
	if(id) {
		var params = 'foreign_key:'+ id + '/model:Quote/';
		$.ajax({
			url: myBaseUrl + "order_contacts/ajax_delete_all_contacts/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
		
				} else {
					
				}
			},
		});
		// Loop through the existing contact on the page... removing each table row
		$('table#Order-contact tr.contact-row').each( function() {
			$(this).remove();
		})
		
		// Clear values
		$('#ContactId').val('');
		$('#ContactContactName').val('');
		$('#ContactContactPhone').val('');
		$('#ContactContactEmail').val('');
		$('#ContactContactTitle').val('');
		$('#ContactContactTypeId').val('');
		
		$('.buttonset').buttonset('refresh');
		
		$('#add_status_toggle_display').removeClass('hide');
	}
}

function clearBillingAddress() {
	$('#BillingAddressName').val('');
	$('#BillingAddressLine1').val('');
	$('#BillingAddressLine2').val('');
	$('#BillingAddressCity').val('');
	$('#BillingAddressStProv').val('');
	$('#BillingAddressZipPost').val('');
}

function calculateCost() {
	var d1 = $('#OrderLineItemMaterialsCostDollars').val(),
		d2 = calculateLabor(),
		d3 = $('#OrderLineItemEquipmentCostDollars').val(),
		//d4 = $('#OrderLineItemOtherCostDollars').val();
		d4 = 0;
	
	// Determine if the total values are automatically updated.
	if (autoUpdateEnabled) {
		var unit = (Number(d1) + Number(d2) + Number(d3) + Number(d4)).toFixed(2),
			qty = $('#OrderOrderLineItemQty').val(),
			total = unit;
			if(qty > 0) {
				total = unit * qty;
			}
			$('#OrderLineItemPriceUnit').val(unit);
			$('#OrderLineItemTotal').val(total);
	} else {
		$('#CostEstimateSummary').html('$'+(Number(d1) + Number(d2) + Number(d3) + Number(d4)).toFixed(2));
	}
}

// OrderLineItemLaborItems
function calculateLine(id) {
	var hours = $('#labor_hour_item_labor_cost_hours_' + id).val(),
	qty = $('#labor_hour_item_labor_qty_' + id).val(),
	rate_id = $('#labor_hour_item_rate_id_' + id).val(),
	rate = 0,
	total = 0,
	temp = 0;

	// Before we can calculate... access the rate databank to obtain the rate
	temp = $('div#rate-data-bank div#rate-container-' + rate_id + ' input#rate').val();
	if(temp) {
		rate = temp;
	}
	
	// Set the rate value
	$('#labor_hour_item_rate_' + id).val(rate);
	
	if(!qty.length) {
		qty = 1;
		$('#labor_hour_item_labor_qty_' + id).val(1);
	}
	total = ((Number(hours) * Number(rate)) * Number(qty));
	$('#labor_hour_item_labor_cost_dollars_' + id).val(total);
	
	// Re-calculate total cost
	calculateCost();
}

function add_labor_line() {
	// Get the last '.labor_estimate_item' and obtain the id.
	var last_id = $('table#labor_hour_items_container tr.labor_estimate_item').last().attr('id'),
		next =  parseInt(last_id.substring(20)) + 1;
	
	var new_line = '<tr class="labor_estimate_item" id="labor_estimate_item_'+ next +'">' + $('tr#labor_estimate_item_'+last_id.substring(20)).html() + '</tr>';
	$('table#labor_hour_items_container').append(new_line);
	// Update the id's of the new line.
	$('#labor_estimate_item_'+ next+ ' .id').attr('id', 'labor_hour_item_id_'+next);
	$('#labor_estimate_item_'+ next+ ' .order_id').attr('id', 'labor_hour_item_order_id_'+next);
	$('#labor_estimate_item_'+ next+ ' .order_line_item_id').attr('id', 'labor_hour_item_order_line_item_id_'+next);
	$('#labor_estimate_item_'+ next+ ' .labor_cost_hours').attr('id', 'labor_hour_item_labor_cost_hours_'+next);
	$('#labor_estimate_item_'+ next+ ' .rate').attr('id', 'labor_hour_item_rate_'+next);
	$('#labor_estimate_item_'+ next+ ' .labor_qty').attr('id', 'labor_hour_item_labor_qty_'+next);
	$('#labor_estimate_item_'+ next+ ' .labor_cost_dollars').attr('id', 'labor_hour_item_labor_cost_dollars_'+next);
	$('#labor_estimate_item_'+ next+ ' .labor-hour-item-delete').attr('id', 'labor-hour-item-delete-'+next);
	
	// Update the Name attribute
	$('#labor_hour_item_id_'+ next).attr('name', 'data[OrderLineItemLaborItem]['+next+'][id]');
	$('#labor_hour_item_order_id_'+ next).attr('name', 'data[OrderLineItemLaborItem]['+next+'][order_id]');
	$('#labor_hour_item_order_line_item_id_'+ next).attr('name', 'data[OrderLineItemLaborItem]['+next+'][order_line_item_id]');
	$('#labor_hour_item_labor_cost_hours_'+ next).attr('name', 'data[OrderLineItemLaborItem]['+next+'][labor_cost_hours]');
	$('#labor_hour_item_rate_'+ next).attr('name', 'data[OrderLineItemLaborItem]['+next+'][rate]');
	$('#labor_hour_item_labor_qty_'+ next).attr('name', 'data[OrderLineItemLaborItem]['+next+'][labor_qty]');
	$('#labor_hour_item_labor_cost_dollars_'+ next).attr('name', 'data[OrderLineItemLaborItem]['+next+'][labor_cost_dollars]');
	

	// Reset the value of the Rate field
	$('#labor_hour_item_rate_'+ next).val('');
	
}

function deleteLaborItemRowContent(id) {
	$('#labor_hour_item_labor_cost_hours_' + id).val('');
	$('#labor_hour_item_rate_' + id).val('');
	$('#labor_hour_item_labor_qty_' + id).val('');
	$('#labor_hour_item_labor_cost_dollars_' + id).val('');
	$('#labor_hour_item_id_' + id).val('');
	calculateCost();
}

function deleteLaborHourItem(id, labor_hour_item_id) {
	// Build a parameter list to lend to the server
	var params = 'id:'+ labor_hour_item_id + '/';
	$.ajax({
		url: myBaseUrl + "order_line_item_labor_items/ajax_delete_labor_item/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			startPageLoader();
		},
		complete: function(data, textStatus){
			stopPageLoader();
			// Handle the complete event
			if(textStatus == 'success') {
				var results = data.responseText,
					obj = jQuery.parseJSON(results),
					success = obj.success,
					error = obj.error;
				
				if(success) {
					deleteLaborItemRowContent(id);
				} else {
					
				}
				return success;
			} else {
				return false;
			}
		},
	});
}

function clearPrevSelectedRows() {
	$('tr.search_table_row').each( function() {
		$(this).removeClass('selected');
	});
}

function getCustomerContactData(id) {
	// Build a parameter list to lend to the server
	var params = '';
	params = params + 'customer_id:'+ id + '/';
	
	$.ajax({
		url: myBaseUrl + "contacts/ajax_get_customer_contacts/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			//document.getElementById("ajax-loader-contacts").style.display = 'inline-block';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var results = data.responseText;
				var obj = jQuery.parseJSON(results);
				var success = obj.success;
				var result = obj.result;
				var error = obj.error;
				
				$('table#contact_search_table').html(result);
			} else {
				
			}
			//document.getElementById("ajax-loader-contacts").style.display = 'none';
		},
	});
}

function getCustomerAddressData(id) {
	// Build a parameter list to lend to the server
	var params = '';
	params = params + 'customer_id:'+ id + '/';
	
	$.ajax({
		url: myBaseUrl + "customers/ajax_get_customer_addresses/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			//document.getElementById("ajax-loader-contacts").style.display = 'inline-block';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var results = data.responseText;
				var obj = jQuery.parseJSON(results);
				var success = obj.success;
				var result = obj.result;
				var error = obj.error;
				
				$('table#jobsite_search_table').html(result);
				$('table#billing_search_table').html(result);
			} else {
				
			}
			//document.getElementById("ajax-loader-contacts").style.display = 'none';
		},
	});
}

function searchCustomers(search_value) {
	// Build a parameter list to lend to the server
	var params = '';
	params = params + 'search_value:'+ search_value + '/';
	
	$.ajax({
		url: myBaseUrl + "customers/ajax_search_customers/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			//document.getElementById("ajax-loader-customers").style.display = 'inline-block';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var results = data.responseText;
				var obj = jQuery.parseJSON(results);
				var success = obj.success;
				var result = obj.result;
				var error = obj.error;
				
				$('#customer_search_table').html(result);

				if($('div#autocomplete_container_customer').find(".jspVerticalBar").is(":visible")) {
					$('div#autocomplete_container_customer').css('height', '400px');
				} else {
					$('div#autocomplete_container_customer').css('height', '200px');
				}
			} else {
				
			}
			//document.getElementById("ajax-loader-customers").style.display = 'none';
		},
	});
}

function searchLeads(search_value) {
	// Build a parameter list to lend to the server
	var params = '';
	params = params + 'search_value:'+ search_value + '/';
	
	$.ajax({
		url: myBaseUrl + "contacts/ajax_search_leads/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			//document.getElementById("ajax-loader-leads").style.display = 'inline-block';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var results = data.responseText;
				var obj = jQuery.parseJSON(results);
				var success = obj.success;
				var result = obj.result;
				var error = obj.error;
				
				$('#lead_search_table').html(result);

				if($('div#autocomplete_container_lead').find(".jspVerticalBar").is(":visible")) {
					$('div#autocomplete_container_lead').css('height', '400px');
				} else {
					$('div#autocomplete_container_lead').css('height', '200px');
				}
			} else {
				
			}
			//document.getElementById("ajax-loader-leads").style.display = 'none';
		},
	});
}
	
$(document).ready(function(){
	var autoUpdateEnabled = true;
	$('#OrderLineItemLineItemType').bind('change', function() {
		// Determine if a template is selected
		var selected = $(this).val();
		if(selected) {
			// A template was selected from the dropdown.  Turn off validation and save the record
			$('#__validation').val('none');
			document.getElementById('OrderLineItemAddForm').submit();
		}
    	return false;
    });
	
	$(document).on("keyup", ".line_item_dollars", function() {
		calculateCost();
    	return false;
    });
	$(document).on("keyup", "#OrderLineItemLaborCostHours", function() {
    	var hours = $('#OrderLineItemLaborCostHours').val(),
	    	qty = 1,
			dollar = 0;
    		
    	if($('#OrderLineItemLaborQty').val()) {
    		qty = $('#OrderLineItemLaborQty').val();
    	}
    	dollar = parseFloat(Math.round(((hours*qty) * $('#line_item_labor').val()) * 100) / 100).toFixed(2);
    	$('#OrderLineItemLaborCostDollars').val(dollar);
    	calculateCost();
	});
	$(document).on("keyup", "#OrderLineItemLaborQty", function() {
    	var hours = $('#OrderLineItemLaborCostHours').val(),
    		qty = 1,
    		dollar = 0;
    	
    	if($('#OrderLineItemLaborQty').val()) {
    		qty = $('#OrderLineItemLaborQty').val();
    	}
    	dollar = parseFloat(Math.round(((hours*qty) * $('#line_item_labor').val()) * 100) / 100).toFixed(2);
    	
    	$('#OrderLineItemLaborCostDollars').val(dollar);
    	calculateCost();
	});
    $(document).on("keyup", "#OrderLineItemQty", function() {
    	calculateCost();
	});
    
    $(document).on("keyup", "#OrderLineItemMaterialsCostDollars", function() {
    	calculateCost();
	});
    $(document).on("keyup", "#OrderLineItemEquipmentCostDollars", function() {
    	calculateCost();
	});
    $(document).on("keyup", "#OrderLineItemLaborCostDollars", function() {
		calculateCost();
    	return false;
    });
    
    $(document).on("keyup", ".labor_cost_hours", function() {
		var id = $(this).attr('id').replace('labor_hour_item_labor_cost_hours_','');
		calculateLine(id);
    	return false;
    });
    $(document).on("keyup", ".labor_qty", function() {
    	var id = $(this).attr('id').replace('labor_hour_item_labor_qty_','');
    	calculateLine(id);
    	return false;
    });
    $(document).on("change", ".rate_id", function() {
    	var id = $(this).attr('id').replace('labor_hour_item_rate_id_','');
    	calculateLine(id);
    	return false;
    });
    $(document).on("keyup", ".labor_cost_dollars", function() {
    	calculateCost();
    	return false;
    });
    $(document).on("click", "#add_more_labor_items", function() {
		add_labor_line();
		return false;
	});
    
    $(document).on("click", ".labor-hour-item-delete", function() {
        var id = $(this).attr('id').replace('labor-hour-item-delete-', ''),
        	labor_hour_item_id = $('#labor_hour_item_id_' + id).val();
       
    	// Is there a table ID asscociated with the line item?
    	if(labor_hour_item_id.length) {
    		var r=confirm('Are you sure you want to delete this item?');
            if (r==true) {
            	deleteLaborHourItem(id, labor_hour_item_id);
            }
    	} else {
    		deleteLaborItemRowContent(id);
    	}
		return false;
	});
    
	$(document).on("click", "#CostEstimateSummary", function() {
		var content = $(this).html();
		$('#OrderLineItemPriceUnit').val(Number(content.replace('$','')).toFixed(2));
    	return false;
    });
	$(document).on("keyup", '#LineItemLaborCostHours', function() {
		var hours = $('#LineItemLaborCostHours').val(),
			labor_per_hour = $('#laborPerHour').attr('value'),
			dollar = parseFloat(Math.round((hours*labor_per_hour) * 100) / 100).toFixed(2);
    	$('#LineItemLaborCostDollars').attr('value', dollar);
    	calculateCost();
	});
	
	$('.updated-line-item').bind('click', function() {
		// Determine if a template is selected
		var id = $(this).attr('id');
		
		if($('#update-' + id).css('display') == 'none') {
			$('.update-' + id).each( function() {
				$(this).css('display', 'table-row');
			});
			$('.description-' + id).each( function() {
				$(this).css('display', 'table-row');
			});
		} else {
			$('.update-' + id).each( function() {
				$(this).css('display', 'none');
			});
			$('.description-' + id).each( function() {
				$(this).css('display', 'none');
			});
		}
    	return false;
    });
	
	$('#OrderLineItemOrderLineItemTypeId').bind('change', function() {
		/* Confirm that user wishes to add a tmeplate to the order */
		var r = confirm("Proceed with adding an Estimate to this Job. (This will remove any estimates that have been prviously entered.)");
		if (r == true) {
			$('#OrderLineItemMode').val('add_template');
			$( "#OrderLineItemAddForm" ).submit();
		}
	});
	
	$('#customer_name').keyup(function() {
		var value = $(this).val();
		if(value.length >= 5) {
			searchCustomers(value);
			
			if($('div#autocomplete_container_lead').length) {
				searchLeads(value);
			}
		}

		// ASSUMPTION
		// A new customer is being entered.  Clear the 'QuoteQuoteCustomerMode' and 'QuoteQuoteCustomerId' values.
		// These values will be set when a customer/lead is selected.
		$('#OrderOrderCustomerMode').val('');
		$('#OrderOrderCustomerId').val('');
		
		clearOrderContacts();
		clearBillingAddress();
		
		// Clear the contacts table
		$('table#contact_search_table').html('');
	});
	
	$('table#customer_search_table').on('click', "tr.customer_search_table_row", function(){
		var id = $(this).attr('id');
		var name = $('#customer_name_' + id).val();
		var mode = 'customer';
		// Clear all search_table_rows
		clearPrevSelectedRows();
		$(this).addClass('selected');

		$('#customer_name').val(name);
		$('#OrderOrderCustomerMode').val(mode);
		$('#OrderOrderCustomerId').val(id);
		
		// Obtain the contact information for the Customer.
		getCustomerContactData(id);

		//Obtain address information for the Customer
		getCustomerAddressData(id);
		return false;
	});
});