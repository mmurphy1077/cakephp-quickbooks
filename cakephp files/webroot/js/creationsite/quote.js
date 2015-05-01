//$( ".combobox" ).combobox();
function calculateLabor() {
	var total = 0;
	$('.labor_cost_dollars').each( function() {
		total = total + Number($(this).val());
	});
	return total;
}

function calculateCost() {
	var d1 = $('#QuoteLineItemMaterialsCostDollars').val(),
		d2 = calculateLabor(),
		d3 = $('#QuoteLineItemEquipmentCostDollars').val(),
		unit = (Number(d1) + Number(d2) + Number(d3)).toFixed(2),
		qty = $('#QuoteLineItemQty').val(),
		total = unit;
	
	if(qty > 0) {
		total = unit * qty;
	}
	$('#QuoteLineItemPriceUnit').val(unit);
	//$('#OrderLineItemTotal').val(total);
}

//QuoteLineItemLaborItems
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
	$('#labor_estimate_item_'+ next+ ' .rate_id').attr('id', 'labor_hour_item_rate_id_'+next);
	$('#labor_estimate_item_'+ next+ ' .labor_qty').attr('id', 'labor_hour_item_labor_qty_'+next);
	$('#labor_estimate_item_'+ next+ ' .labor_cost_dollars').attr('id', 'labor_hour_item_labor_cost_dollars_'+next);
	$('#labor_estimate_item_'+ next+ ' .labor-hour-item-delete').attr('id', 'labor-hour-item-delete-'+next);
	
	// Update the Name attribute
	$('#labor_hour_item_id_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][id]');
	$('#labor_hour_item_order_id_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][order_id]');
	$('#labor_hour_item_order_line_item_id_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][order_line_item_id]');
	$('#labor_hour_item_labor_cost_hours_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][labor_cost_hours]');
	$('#labor_hour_item_rate_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][rate]');
	$('#labor_hour_item_rate_id_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][rate_id]');
	$('#labor_hour_item_labor_qty_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][labor_qty]');
	$('#labor_hour_item_labor_cost_dollars_'+ next).attr('name', 'data[QuoteLineItemLaborItem]['+next+'][labor_cost_dollars]');
	
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
		url: myBaseUrl + "quote_line_item_labor_items/ajax_delete_labor_item/"+params,
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

function refreshPage() {
	$('input.required').each( function() {
		if(($(this).val().length > 0) && ($(this).val() != 'Required')) {
			$(this).removeClass('required_color');
		} else if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
			$(this).val('Required');
			$(this).addClass('required_color');
		}
	});
}

function clearCustomerContacts(id) {
	alert('go');
	
	// Loop through the existing contact on the page... removing each table row
	$('table#Quote-contact tr.contact-row').each( function() {
		$(this).remove();
	})
	
	// Open up the new add_status_toggle_display
	addContact();
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
	
	$(document).on("keyup", ".line_item_dollars", function() {
		calculateCost();
    	return false;
    });
	$(document).on("keyup", "#QuoteLineItemMaterialsCostDollars", function() {
		calculateCost();
    	return false;
    });
	$(document).on("keyup", "#QuoteLineItemEquipmentCostDollars", function() {
		calculateCost();
    	return false;
    });
	$(document).on("keyup", "#QuoteLineItemLaborCostDollars", function() {
		calculateCost();
    	return false;
    });
    $(document).on("keyup", "#QuoteLineItemLaborCostHours", function() {
    	var hours = $('#QuoteLineItemLaborCostHours').val(),
    		qty = 1,
    		dollar = 0;
    	
    	if($('#QuoteLineItemLaborQty').val()) {
    		qty = $('#QuoteLineItemLaborQty').val();
    	}
    	dollar = parseFloat(Math.round(((hours*qty) * $('#quote_line_item_labor').val()) * 100) / 100).toFixed(2);
    	$('#QuoteLineItemLaborCostDollars').val(dollar);
    	calculateCost();
	});
    $(document).on("keyup", "#QuoteLineItemLaborQty", function() {
    	var hours = $('#QuoteLineItemLaborCostHours').val(),
    		qty = 1,
    		dollar = 0;
    	
    	if($('#QuoteLineItemLaborQty').val()) {
    		qty = $('#QuoteLineItemLaborQty').val();
    	}
    	dollar = parseFloat(Math.round(((hours*qty) * $('#quote_line_item_labor').val()) * 100) / 100).toFixed(2);
    	
    	$('#QuoteLineItemLaborCostDollars').val(dollar);
    	calculateCost();
	});
	$(document).on("click", "#CostEstimateSummary", function() {
		var content = $(this).html();
		$('#QuoteLineItemPriceUnit').val(Number(content.replace('$','')).toFixed(2));
    	return false;
    });
	$(document).on("keyup", '#QuoteLineItemLaborCostHours', function() {
		var hours = $('#QuoteLineItemLaborCostHours').val(),
			labor_per_hour = $('#laborPerHour').attr('value'),
			dollar = parseFloat(Math.round((hours*labor_per_hour) * 100) / 100).toFixed(2);
    	$('#QuoteLineItemLaborCostDollars').attr('value', dollar);
    	calculateCost();
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
    
    
	
	$('.alert_nav').bind('click', function() {
		var id = $(this).attr('id');

		if(id == 'alert_nav_show') {
			$('#alert_nav_show').addClass('hide');
			$('#alert_nav_hide').removeClass('hide');
			$('#alert-container-quotes-index').removeClass('hide');
		} else {
			$('#alert_nav_show').removeClass('hide');
			$('#alert_nav_hide').addClass('hide');
			$('#alert-container-quotes-index').addClass('hide');
		}
	});
	
	$('#add-status').bind('click', function() {
		$('#add-status-item-container').toggle();
		return false;
	});
	
	$('ul.tabs li').bind('click', function() {
		$('ul.tabs li').each( function() {
			$(this).removeClass('active');
		});
		
		$(this).addClass('active');
	});
	
	$('#include_cover_letter').bind('click', function() {
		if($(this).prop('checked')) {
			$('#cover-letter-container').css('display', 'block');
		} else {
			$('#cover-letter-container').css('display', 'none');
		}
	});
	
	$('#customer_name').keyup(function() {
		var value = $(this).val();
		if(value.length >= 3) {
			searchCustomers(value);
			searchLeads(value);
		}

		// ASSUMPTION
		// A new customer is being entered.  Clear the 'QuoteQuoteCustomerMode' and 'QuoteQuoteCustomerId' values.
		// These values will be set when a customer/lead is selected.
		$('#QuoteQuoteCustomerMode').val('');
		$('#QuoteQuoteCustomerId').val('');
		
		// Clear the contacts table
		$('table#contact_search_table').html('');
	});

	function clearPrevSelectedRows() {
		$('tr.search_table_row').each( function() {
			$(this).removeClass('selected');
		});
	}
	
	$('table#customer_search_table').on('click', "tr.customer_search_table_row", function(){
		var id = $(this).attr('id');
		var name = $('#customer_name_' + id).val();
		var mode = 'customer';
		// Clear all search_table_rows
		clearPrevSelectedRows();
		$(this).addClass('selected');

		$('#customer_name').val(name);
		$('#QuoteQuoteCustomerMode').val(mode);
		$('#QuoteQuoteCustomerId').val(id);
		
		// Clear out Existsing Contacts (Usesful when coping quote to a new Customer)
		clearCustomerContacts(id);
		
		// Obtain the contact information for the Customer.
		getCustomerContactData(id);

		//Obtain address information for the Customer
		getCustomerAddressData(id);
		
		return false;
	});

	$('table#lead_search_table').on('click', "tr.lead_search_table_row", function(){
		var id = $(this).attr('id');
		var mode = 'contact';
		
		// Clear all search_table_rows
		clearPrevSelectedRows();
		$(this).addClass('selected');

		// Access the databank
		var name = $('#lead_data_bank_' + id + ' #name').val();
		$('#QuoteQuoteCustomerMode').val(mode);
		$('#QuoteQuoteCustomerId').val(id);
		var company_name = $('#lead_data_bank_' + id + ' #company_name').val();
		var title = $('#lead_data_bank_' + id + ' #title').val();
		var phone = $('#lead_data_bank_' + id + ' #phone').val();
		var email = $('#lead_data_bank_' + id + ' #email').val();

		if(company_name.length > 0) {
			$('#customer_name').val(company_name);
		} else {
			$('#customer_name').val(name);
		}
		
		// Clear out Existsing Contacts (Usesful when coping quote to a new Customer)
		clearCustomerContacts(id);
		
		// Load contact info into the table#contact_search_table 
		var html = '<tr id="' + id + '" class="contact_search_table_row search_table_row">' + 
				'	<td>' + 
				'		<div class="contact">' + 
				'			<span class="semi-bold">' + name + '</span><br />' +
							title + '<br />' +
							phone + '<br />' +
							email +
				'		</div>' +
				'		<div id="contact_data_bank_' + id + '" class="data_bank">'+
				'			<input id="name" type="hidden" value="' + name + '">' + 
				'			<input id="title" type="hidden" value="' + title + '">' + 
				'			<input id="phone" type="hidden" value="' + phone + '">' + 
				'			<input id="email" type="hidden" value="' + email + '">' + 
				'		</div>';
				'	</td>' +
				'</tr>';
		$('table#contact_search_table').html(html);
		return false;
	});

	/**************************
	 *	When a Contact has been selected from the List
	 */
	$('table#contact_search_table').on('click', "tr.contact_search_table_row", function(){
		var id = $(this).attr('id');
		$('table#contact_search_table tr').each( function() {
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');

		// Access the databank
		$('#QuoteContactName').val($('#contact_data_bank_' + id + ' #name').val());
		$('#QuoteContactTitle').val($('#contact_data_bank_' + id + ' #title').val());
		$('#QuoteContactPhone').val($('#contact_data_bank_' + id + ' #phone').val());
		$('#QuoteContactEmail').val($('#contact_data_bank_' + id + ' #email').val());
		$('#QuoteContactId').val(id);

		// Change the "Add Contact profile" to "Update Contact Info" .... And non-visible
		$('#LabelAddToCustomer').html('Update Contact Info');
		$('#QuoteAddToCustomer').prop('checked', false);
		$('#LabelAddToCustomer').css('display', 'none');
		$('#QuoteAddToCustomer').css('display', 'none');
		
		// Disable the Add to Customer Profile.
		//$('#QuoteAddToCustomer').prop('checked', false);
		//$('#QuoteAddToCustomer').prop('disabled', true);
		
		refreshPage();
		return false;
	});

	$('.contact_input').bind('click', function() {
		$('#LabelAddToCustomer').css('display', 'block');
		$('#QuoteAddToCustomer').css('display', 'block');
	});

	$('#QuoteContactName').bind('keyup', function() {
		// If the length of the data is 1 (or less)... 
		if($(this).val().length <= 1) {
			$('#LabelAddToCustomer').html('Add Contact Profile');
			$('#QuoteContactId').val('');
		}
	});

	$('#button_generate_quote').bind('click', function() {
		$('#QuoteAction').val('generate_quote');
		document.getElementById('printDocs').submit();
		return false;
	});

	$('#button_generate_system_docs').bind('click', function() {
		$('#QuoteAction').val('generate_system_docs');
		document.getElementById('printDocs').submit();
		return false;
	});
});