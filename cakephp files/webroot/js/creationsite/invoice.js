function toggleInvoiceTimesTable(invoice_id, display_time_by) {
	var params = '';
	params = params + 'invoice_id:'+ invoice_id + '/';
	params = params + 'display_time_by:'+ display_time_by + '/';
	
	$.ajax({
		url: myBaseUrl + "invoices/ajax_toggle_invoice_display_type/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			startPageLoader();
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var obj = jQuery.parseJSON(data.responseText),
					html = obj.html,
					order_type = obj.order_type;
				
				$('#invoice-summary-tables').html(html);
			} else {
				
			}
			stopPageLoader();
		},
	});
}

function changeInvoiceSummaryDisplay(invoice_id, type) {
	var params = ''
	params = params + 'invoice_id:'+ invoice_id + '/';
	params = params + 'labor_section_type:'+ type + '/';
	
	$.ajax({
		url: myBaseUrl + "invoices/ajax_edit_invoice_labor_section_type/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			startPageLoader();
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var obj = jQuery.parseJSON(data.responseText),
					html = obj.html;
				
				// Update the view to display the appropriate data.
				$('.summary-row').each( function () {
					$(this).css('display', 'none');
				});
				switch (type) {
					case 'summary' :
						$('.summary').each( function () {
							$(this).css('display', 'table-row');
						});
						break;
						
					case 'itemized' :
						$('.itemized').each( function () {
							$(this).css('display', 'table-row');
						});
						break;
						
					case 'custom' :
						$('.custom').each( function () {
							$(this).css('display', 'table-row');
						});
						break;
				}
			} else {
				
			}
			stopPageLoader();
		},
	});
}

function add_labor_line() {
	// Get the last '.material_select' and obtain the id.
	var last_id = $('table#material_select_container tr.invoice-labor-item').last().attr('id'),
		next =  parseInt(last_id.substring(19)) + 1;
	
	var new_line = '<tr id="invoice-labor_item_'+ next +'" class="invoice-labor-item">' + $('tr#invoice-labor_item_'+last_id.substring(19)).html() + '</tr>';
	$('#material_select_container').append(new_line);
	
	// Update the id's and name attribute of the new line.
	$('tr#invoice-labor_item_'+ next+ ' .id').attr('id', 'invoice_labor_item_id_'+next).attr('name', 'data[InvoiceLaborItem]['+next+'][id]');
	$('tr#invoice-labor_item_'+ next+ ' .invoice_labor_item_description').attr('id', 'invoice_labor_item_description_'+next).attr('name', 'data[InvoiceLaborItem]['+next+'][description]');
	$('tr#invoice-labor_item_'+ next+ ' .invoice_labor_item_cost').attr('id', 'invoice_labor_item_cost_'+next).attr('name', 'data[InvoiceLaborItem]['+next+'][cost]');
	$('tr#invoice-labor_item_'+ next+ ' .invoice_labor_item_qty').attr('id', 'invoice_labor_item_qty_'+next).attr('name', 'data[InvoiceLaborItem]['+next+'][qty]');
	$('tr#invoice-labor_item_'+ next+ ' .invoice_labor_item_unit_cost').attr('id', 'invoice_labor_item_unit_cost_'+next).attr('name', 'data[InvoiceLaborItem]['+next+'][unit_cost]');
	$('tr#invoice-labor_item_'+ next+ ' .invoice-labor-item-delete').attr('id', 'invoice-labor-item-delete-'+next);	
}

function add_misc_line() {
	// Get the last '.misc_select' and obtain the id.
	var last_id = $('table#misc_select_container tr.invoice-misc-item').last().attr('id'),
		next =  parseInt(last_id.substring(18)) + 1;
	
	var new_line = '<tr id="invoice-misc_item_'+ next +'" class="invoice-misc-item">' + $('tr#invoice-misc_item_'+last_id.substring(18)).html() + '</tr>';
	$('#misc_select_container').append(new_line);
	
	// Update the id's and name attribute of the new line.
	$('tr#invoice-misc_item_'+ next+ ' .id').attr('id', 'order_misc_item_id_'+next).attr('name', 'data[InvoiceMiscItem]['+next+'][id]');
	$('tr#invoice-misc_item_'+ next+ ' .invoice_misc_item_description').attr('id', 'invoice_misc_item_description_'+next).attr('name', 'data[InvoiceMiscItem]['+next+'][description]');
	$('tr#invoice-misc_item_'+ next+ ' .invoice_misc_item_cost').attr('id', 'invoice_misc_item_cost_'+next).attr('name', 'data[InvoiceMiscItem]['+next+'][cost]');
	$('tr#invoice-misc_item_'+ next+ ' .invoice-misc-item-delete').attr('id', 'invoice-misc-item-delete-'+next);
}

function add_material_line(type) {
	var table_id = 'material_vanstock_select_container';
	switch (type) {
		case 'stock' :
			table_id = 'material_stock_select_container';
			break;
		case 'purchase' :
			table_id = 'material_purchase_select_container';
			break;
	}	
	// over-ride
	table_id = 'material_select_container';

	// Get the last '.material_select' and obtain the id.
	var last_id = $('table#' + table_id + ' tr.invoice-material-item').last().attr('id'),
		next =  parseInt(last_id.replace('invoice-material_item_', '')) + 1;
	
	var new_line = '<tr id="invoice-material_item_'+ next +'" class="invoice-material-item">' + $('table#' + table_id + ' tr#invoice-material_item_'+last_id.replace('invoice-material_item_', '')).html() + '</tr>';
	$('table#' + table_id).append(new_line);
	
	// Update the id's and name attribute of the new line.
	$('table#' + table_id + ' tr#invoice-material_item_'+ next+ ' .id').attr('id', 'invoice_material_item_id_'+next).attr('name', 'data[InvoiceMaterialItem]['+next+'-'+type+'][id]');
	$('table#' + table_id + ' tr#invoice-material_item_'+ next+ ' .invoice_material_item_qty').attr('id', 'invoice_material_item_qty_'+next).attr('name', 'data[InvoiceMaterialItem]['+next+'-'+type+'][qty]');
	$('table#' + table_id + ' tr#invoice-material_item_'+ next+ ' .invoice_material_item_description').attr('id', 'invoice_material_item_description_'+next).attr('name', 'data[InvoiceMaterialItem]['+next+'-'+type+'][description]');
	$('table#' + table_id + ' tr#invoice-material_item_'+ next+ ' .invoice_material_item_unit_cost').attr('id', 'invoice_material_item_unit_cost_'+next).attr('name', 'data[InvoiceMaterialItem]['+next+'-'+type+'][unit_cost]');
	$('table#' + table_id + ' tr#invoice-material_item_'+ next+ ' .invoice_material_item_cost').attr('id', 'invoice_material_item_cost_'+next).attr('name', 'data[InvoiceMaterialItem]['+next+'-'+type+'][cost]');
	$('table#' + table_id + ' tr#invoice-material_item_'+ next+ ' .invoice-material-item-delete').attr('id', 'invoice-material-item-delete-'+next);
}

function deleteInvoiceLaborItemRowContent(id) {
	$('#invoice_labor_item_description_' + id).val('');
	$('#invoice_labor_item_id_' + id).val('');
	$('#invoice_labor_item_cost_' + id).val('');
	$('#invoice_labor_item_unit_cost_' + id).val('');
	$('#invoice_labor_item_qty_' + id).val('');
	calcLaborTotal();
}

function deleteInvoiceMiscItemRowContent(id) {
	$('#invoice_misc_item_description_' + id).val('');
	$('#invoice_misc_item_id_' + id).val('');
	$('#invoice_misc_item_cost_' + id).val('');
}

function deleteInvoiceMaterialItemRowContent(id, table_id) {
	$('table#' + table_id + ' #invoice_material_item_qty_' + id).val('');
	$('table#' + table_id + ' #invoice_material_item_description_' + id).val('');
	$('table#' + table_id + ' #invoice_material_item_id_' + id).val('');
	$('table#' + table_id + ' #invoice_material_item_unit_cost_' + id).val('');
	$('table#' + table_id + ' #invoice_material_item_cost_' + id).val('');
	$('table#' + table_id + ' #invoice_material_item_type_' + id).val('');
 
	if(table_id == 'material_purchase_select_container') {
		calcMaterialPurchase();
	} else if (table_id == 'material_select_container') {
		calcMaterial();
	} else {
		calcMaterialStock();
	}
}

function deleteInvoiceLaborItem(id, invoice_labor_item_id) {
	// Build a parameter list to lend to the server
	var params = 'id:'+ invoice_labor_item_id + '/';
	$.ajax({
		url: myBaseUrl + "invoice_labor_items/ajax_delete_invoice_labor_item/"+params,
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
					deleteInvoiceLaborItemRowContent(id);
				} else {
					
				}
				return success;
			} else {
				return false;
			}
		},
	});
}

function deleteInvoiceMaterialItem(id, invoice_material_item_id, table_id) {
	// Build a parameter list to lend to the server
	var params = 'id:'+ invoice_material_item_id + '/';
	$.ajax({
		url: myBaseUrl + "invoice_material_items/ajax_delete_invoice_material_item/"+params,
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
					deleteInvoiceMaterialItemRowContent(id, table_id);
				} else {
					
				}
				return success;
			} else {
				return false;
			}
		},
	});
}

function deleteInvoiceMiscItem(invoice_misc_item_id) {
	// Build a parameter list to lend to the server
	var params = invoice_misc_item_id + '/',
		url = "invoice_misc_items/delete_invoice_misc_item/"+params;
	
	if (url) { // require a URL
        window.location = myBaseUrl + url; // redirect
    }
}

function calcLaborTotal() {
	var total = 0,
		num = 0;
	$('input.invoice_labor_item_cost').each( function() {
		num = $(this).val();
		if(num.length && !isNaN(num)) {
			total = total + parseFloat(num);
		}
		
	});
	
	// Update the Labor Total Field
	$('input#InvoiceLaborAmount').val(total);
}

function calcMaterialStock() {
	var total = 0,
		num = 0;

	$('table#material_vanstock_select_container input.invoice_material_item_cost').each( function() {
		num = $(this).val();
		if(num.length && !isNaN(num)) {
			total = total + parseFloat(num);
		}
	});
	
	// Update the Material (Stock) Total Field
	$('input#InvoiceMaterialAmountStock').val(total);
}

function calcMaterial() {
	var total = 0,
		num = 0;

	$('table#material_select_container input.invoice_material_item_cost').each( function() {
		num = $(this).val();
		if(num.length && !isNaN(num)) {
			total = total + parseFloat(num);
		}
	});
	
	// Update the Material (Stock) Total Field
	$('input#InvoiceTotal').val(total);
}

function calcMaterialPurchase() {
	var total = 0,
		num = 0;
	$('table#material_purchase_select_container input.invoice_material_item_cost').each( function() {
		num = $(this).val();
		if(num.length && !isNaN(num)) {
			total = total + parseFloat(num);
		}
	});
	// Update the Material (Purchase) Total Field
	$('input#InvoiceMaterialAmountPurchase').val(total);
}

function calcMaterialLine(id, type) {
	var total = 0,
		table_id = 'material_select_container';
		//table_id = 'material_vanstock_select_container';
	//if(type == 'purchase') {
	//	table_id = 'material_purchase_select_container';
	//}
	
	
	total = Number($('table#' + table_id + ' input#invoice_material_item_qty_' + id).val()) * Number($('table#' + table_id + ' input#invoice_material_item_unit_cost_' + id).val());
	$('table#' + table_id + ' input#invoice_material_item_cost_' + id).val(total);

	//if(type == 'purchase') {
	//	calcMaterialPurchase();
	//} else {
	//	calcMaterialStock();
	//}
	calcMaterial();
}

function calcLaborLine(id) {
	var total = Number($('input#invoice_labor_item_qty_' + id).val()) * Number($('input#invoice_labor_item_unit_cost_' + id).val());
	$('input#invoice_labor_item_cost_' + id).val(total);
	calcLaborTotal();
}

function populateInvoiceLineItem(type, qty, description, unit_price, cost) {
	var element = '';
	var table_id = 'material_select_container';
	switch (type) {
		case 'labor' :
			//element = 'invoice_labor_item_';
			element = 'invoice_material_item_';
			break;
		case 'stock' :
			element = 'invoice_material_item_';
			//table_id = 'material_vanstock_select_container';
			break
		case 'purchase' :
			element = 'invoice_material_item_';
			//table_id = 'material_purchase_select_container';
			break;
	}
	// Detemine the invoice labor item line to add the material item to
	// If none exist... create a new line.
	var index = null,
		lastId = null;
	// Loop through the existing invoice labor item spaces to determine the location of the first empty line
	$('table#' + table_id + ' input.' + element + 'description').each( function() {
		lastId = $(this).attr('id').replace(element + 'description_', '');
		if((index == null) && ($(this).val() == 0)) {
			index = $(this).attr('id').replace(element + 'description_', '');
		}
	});

	// Determine if there are no more lines left... One must be added.
	if(index == null) {
		//if(type == 'labor') {
		//	add_labor_line();
		//} else {
		//	add_material_line(type);
		//}
		add_material_line(type);
		index = parseInt(lastId) + 1;
	}

	// Populate InvoiceLaborItem fields.
	// Category - clear it!
	$('table#' + table_id + ' #' + element + 'id_' + index).val(null);
	$('table#' + table_id + ' #' + element + 'qty_' + index).val(qty);
	$('table#' + table_id + ' #' + element + 'description_' + index).val(description);
	$('table#' + table_id + ' #' + element + 'unit_cost_' + index).val(unit_price);
	$('table#' + table_id + ' #' + element + 'cost_' + index).val(cost);
	$('table#' + table_id + ' #' + element + 'type_' + index).val(type);
	
	switch (type) {
		case 'labor' :
			// Update the Calculated Labor
			//calcLaborTotal();
			break;
		
		case 'stock' :
			//calcMaterialStock();
			break
			
		case 'purchase' :
			//calcMaterialPurchase();
			break
	}
	calcMaterial();	
}

$(document).ready(function(){
	$('#InvoiceSortBy').bind('change', function() {
		var display_time_by = $(this).val(),
			invoice_id = $('#InvoiceId').val();
		
		// Grab the invoice ID and update the display_time_by field
		toggleInvoiceTimesTable(invoice_id, display_time_by);
    	return false;
    });
	
	$('#InvoiceLaborSectionType').bind('change', function() {
		var type = $(this).val(),
			invoice_id = $('#InvoiceId').val();
		
		// Grab the invoice ID and update the display_time_by field.
		changeInvoiceSummaryDisplay(invoice_id, type);
    	return false;
    });
	
	$('div#body').on('click', '.toggle_page_items_button', function() {
		var id = $(this).attr('id');
		$('.'+id+'_toggle_item').each( function() {
			$(this).toggle();
		});
		
		// Adjust the summary
		if($('.'+id+'_toggle_item').css('display') == 'none') {
			$('tr#'+id+'-summary-row td').each( function() {
				$(this).css('background-color', 'inherit');
				$(this).css('border-bottom', '1px solid #cccccc');
			});
		} else {
			$('tr#'+id+'-summary-row td').each( function() {
				$(this).css('background-color', '#efefef');
				$(this).css('border-bottom', '4px solid #000000');
			});
		}
		
    	return false;
    });
	
	/**
	 * LABOR ITEM DELETE
	 */
	$(document).on("click", ".invoice-labor-item-delete", function() {
        var id = $(this).attr('id').replace('invoice-labor-item-delete-', ''),
        	invoice_labor_item_id = $('#invoice_labor_item_id_' + id).val();
       
    	// Is there a table ID asscociated with the line item?
    	if(invoice_labor_item_id.length) {
    		var r=confirm('Are you sure you want to delete this item?');
            if (r==true) {
            	deleteInvoiceLaborItem(id, invoice_labor_item_id);
            }
    	} else {
    		deleteInvoiceLaborItemRowContent(id);
    	}
		return false;
	});
	
	/**
	 * MISC ITEM DELETE
	 */
	$(document).on("click", ".invoice-misc-item-delete", function() {
        var id = $(this).attr('id').replace('invoice-misc-item-delete-', ''),
        	invoice_misc_item_id = $('#invoice_misc_item_id_' + id).val();
       
    	// Is there a table ID asscociated with the line item?
    	if(invoice_misc_item_id.length) {
    		var r=confirm('Are you sure you want to delete this item?');
            if (r==true) {
            	deleteInvoiceMiscItem(invoice_misc_item_id);
            }
    	} else {
    		deleteInvoiceMiscItemRowContent(id);
    	}
		return false;
	});
	
	/**
	 * VANSTOCK / PURCHASE MATERIAL ITEM DELETE
	 */
	$(document).on("click", ".invoice-material-item-delete", function() {
        var id = $(this).attr('id').replace('invoice-material-item-delete-', ''),
        	table_id = $(this).closest('table').attr('id')
        	tableObj = $('table#' + table_id),
        	invoice_material_item_id = tableObj.find('#invoice_material_item_id_' + id).val();
       
    	// Is there a table ID asscociated with the line item?
    	if(invoice_material_item_id.length) {
    		var r=confirm('Are you sure you want to delete this item?');
            if (r==true) {
            	deleteInvoiceMaterialItem(id, invoice_material_item_id, table_id);
            }
    	} else {
    		deleteInvoiceMaterialItemRowContent(id, table_id);
    	}
		return false;
	});
	
	/***********
	 *	A LABOR SUMMARY ITEM IS SELECTED
	 */
	$(document).on("click", '.invoice-include', function() {
		var id = $(this).attr('id'),
			desc = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-description').val(),
			time = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-hours').val(),
			cost = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-price').val(),
			date = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date').val(),
			date_short = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date-short').val(),
			name = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-name').val(),
			unit_price = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-rate').val(),
			qty = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-hours').val(),
			type = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-type').val(),
			description = '';
				
		description = time + ' hours';
		if(date.length) {
			//description = date + ' for ' + description;
			description = date_short + ' Labor';
		} else if(name.length) {
			//description = description + ' (' + name + ' - ' + type + ')';
			description = name + ' (' + type + ')';
		}
		if(qty.length) {
			qty = Number(qty) + 0;
		}
		
		populateInvoiceLineItem('labor', qty, description, unit_price, cost);
		return false;
	});
	
	
	/***********
	 *	A MATERIAL ITEM IS SELECTED
	 */
	$(document).on("click", '.invoice-material-item-include', function() {
		var id = $(this).attr('id'),
			desc = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-description').val(),
			price = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-price').val(),
			date = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date').val(),
			date_short = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date-short').val(),
			name = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-name').val(),
			unit_price = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-price').val(),
			qty = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-qty').val(),
			uom = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-uom').val(),
			total = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-total').val(),
			description = '';
		
		// Populate InvoiceMaterialItem fields.
		if(date.length) {
			description = date_short + ' ';
		}
	
		if(name.length) {
			description = description + name;
		}
		var uom_display = '';
		if(uom.length && uom != 'each') {
			uom_display = ' ' + uom;
		}
	
		if(qty.length) {
			qty = Number(qty) + 0;
		}
	
		populateInvoiceLineItem('stock', qty, description, unit_price, total);
		return false;
	});
	
	
	/***********
	 *	A MATERIAL SUMMARY ITEM IS SELECTED
	 */
	$(document).on("click", '.invoice-material-item-summary-include', function() {
		var id = $(this).attr('id'),
			desc = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-description').val(),
			unit_price = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-price').val(),
			name = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-name').val(),
			qty = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-qty').val(),
			uom = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-uom').val(),
			total = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-total').val(),
			description = '';
			
		// Populate InvoiceMaterialItem fields.
		if(name.length) {
			description = description + name;
		}
		var uom_display = '';
		if(uom.length && uom != 'each') {
			uom_display = ' ' + uom;
		}
		if(qty.length) {
			qty = Number(qty) + 0;
		}
		populateInvoiceLineItem('stock', qty, description, unit_price, total);
		return false;
	});
	
	$(document).on("click", '.invoice-unassoc-material-item-include', function() {
		var id = $(this).attr('id'),
			desc = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-description').val(),
			unit_price = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-price').val(),
			total = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-total').val(),
			date = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date').val(),
			date_short = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date-short').val(),
			name = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-name').val(),
			qty = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-qty').val(),
			uom = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-uom').val(),
			description = '';
		
		// Populate InvoiceMaterialItem fields.
		if(date.length) {
			description = date_short + ' ';
		}
		if(name.length) {
			description = description + name;
		}
		var uom_display = '';
		if(uom.length && uom != 'each') {
			uom_display = ' ' + uom;
		}
		if(qty.length) {
			qty = Number(qty) + 0;
		}
		populateInvoiceLineItem('stock', qty, description, unit_price, total);
		return false;
	});
	
	/***********
	 *	A MATERIAL PURCHASE ITEM IS SELECTED
	 */
	$(document).on("click", '.invoice-purchase-item-include', function() {
		var id = $(this).attr('id'),
			desc = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-description').val(),
			name = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-name').val(),
			price = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-price').val(),
			date = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date').val(),
			date_short = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date-short').val(),
			qty = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-qty').val(),
			total = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-total').val(),
			description = '';
		
		// Populate InvoiceMaterialItem fields.
		if(date.length) {
			description = date_short + ' ';
		}
		if(name.length) {
			description = description + name + ' ';
		}
		if(desc.length) {
			description = description + desc + ' ';
		}
		if(qty.length) {
			qty = Number(qty) + 0;
		}
		populateInvoiceLineItem('purchase', qty, description, price, total);
		return false;
	});
	
	
	/***********
	 *	A MATERIAL PURCHASE SUMMARY ITEM IS SELECTED
	 */
	$(document).on("click", '.invoice-purchase-summary-include', function() {
		var id = $(this).attr('id'),
			desc = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-description').val(),
			//date = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date').val(),
			//date_short = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-date-short').val(),
			total = $(this).parent('td#data-bank-cell').find('#data-bank-' + id + ' input#data-bank-item-total').val(),
			qty = 1,
			description = '';
	
		// Populate InvoiceMaterialItem fields.
		if(desc.length) {
			description = description + desc;
		}
		populateInvoiceLineItem('purchase', qty, description, total, total);
		return false;
	});
	
	/* Keypress on the labor cost field */
	$(document).on('keyup', ".invoice_labor_item_cost", function (event) {
		calcLaborTotal();
	});
	
	/* LINE ITEM Keypress on the labor fields */
	$(document).on('keyup', ".invoice_labor_item_qty", function (event) {
		var id = $(this).attr('id').replace('invoice_labor_item_qty_', '');
		calcLaborLine(id);
	});
	$(document).on('keyup', ".invoice_labor_item_unit_cost", function (event) {
		var id = $(this).attr('id').replace('invoice_labor_item_unit_cost_', '');
		calcLaborLine(id);
	});
	
	/* Keypress on the material (stock) cost field */
	$(document).on('keyup', "table#material_vanstock_select_container .invoice_material_item_cost", function (event) {
		calcMaterialStock();
	});
	
	/* Keypress on the material (purchase) cost field */
	$(document).on('keyup', "table#material_purchase_select_container .invoice_material_item_cost", function (event) {
		calcMaterialPurchase();
	});
	
	/* LINE ITEM Keypress on the material fields */
	/*
	$(document).on('keyup', "table#material_vanstock_select_container .invoice_material_item_qty", function (event) {
		var id = $(this).attr('id').replace('invoice_material_item_qty_', '');
		calcMaterialLine(id, 'vanstock');
	});
	$(document).on('keyup', "table#material_vanstock_select_container .invoice_material_item_unit_cost", function (event) {
		var id = $(this).attr('id').replace('invoice_material_item_unit_cost_', '');
		calcMaterialLine(id, 'vanstock');
	});
	$(document).on('keyup', "table#material_purchase_select_container .invoice_material_item_qty", function (event) {
		var id = $(this).attr('id').replace('invoice_material_item_qty_', '');
		calcMaterialLine(id, 'purchase');
	});
	$(document).on('keyup', "table#material_purchase_select_container .invoice_material_item_unit_cost", function (event) {
		var id = $(this).attr('id').replace('invoice_material_item_unit_cost_', '');
		calcMaterialLine(id, 'purchase');
	});
	*/
	$(document).on('keyup', ".invoice_material_item_qty", function (event) {
		var id = $(this).attr('id').replace('invoice_material_item_qty_', '');
		calcMaterialLine(id, 'vanstock');
	});
	$(document).on('keyup', ".invoice_material_item_unit_cost", function (event) {
		var id = $(this).attr('id').replace('invoice_material_item_unit_cost_', '');
		calcMaterialLine(id, 'vanstock');
	});
	$(document).on('keyup', ".invoice_material_item_cost", function (event) {
		var id = $(this).attr('id').replace('invoice_material_item_unit_cost_', '');
		calcMaterialLine(id, 'vanstock');
	});
	
	
	$(document).on("click", "#add_more_labor_items", function() {
		add_labor_line();
		return false;
	});
	
	$(document).on("click", "#add_more_material_vanstock_items", function() {
		add_material_line('stock');
		return false;
	});
	
	$(document).on("click", "#add_more_material_purchase_items", function() {
		add_material_line('purchase');
		return false;
	});
	
	$(document).on("click", "#add_more_misc_items", function() {
		add_misc_line();
		return false;
	});
	
	$(document).on("click", "#add_more_material_items", function() {
		add_material_line('material');
		return false;
	});
	
	
	function configure_mobile_form(status_track, status_order) {
		var track_on_class = 'col-md-6 col-lg-6',
			track_off_class = 'col-md-12 col-lg-12';
	

		if(status_track == 'on' && status_order == 'on') {
			// Items... Make in half width
			$('div#invoice-labor-container-mobile').removeClass('col-md-12').removeClass('col-lg-12').addClass('col-md-push-6').addClass('col-md-6').addClass('col-lg-6');
			
			// Track Items... make sure visible accross the board
			$('div#tracking-container').removeClass('hidden-xs').removeClass('hidden-sm').removeClass('hidden-md').removeClass('hidden-lg');
			
			// Turn off the order tabs in the Track Container
			//$('#form-tabs-element-scope-container').addClass('hidden-xs').addClass('hidden-sm');
			//$('#form-tabs-element-items-container').addClass('hidden-xs').addClass('hidden-sm');
			$('#form-tabs-element-scope-container').removeClass('hidden-xs').removeClass('hidden-sm');
			$('#form-tabs-element-items-container').removeClass('hidden-xs').removeClass('hidden-sm');
			
			// Order Container  will appear in tasks
			$('div#order-container').addClass('hidden-xs').addClass('hidden-sm').removeClass('hidden-md').removeClass('hidden-lg');
			
			
		} else if(status_track == 'on' && status_order == 'off') {
			// Items... Make in half width
			$('div#invoice-labor-container-mobile').removeClass('col-md-12').removeClass('col-lg-12').addClass('col-md-push-6').addClass('col-md-6').addClass('col-lg-6');
			
			// Track Items... make sure visible accross the board
			$('div#tracking-container').removeClass('hidden-xs').removeClass('hidden-sm').removeClass('hidden-md').removeClass('hidden-lg');
			
			// Turn off Order Container
			$('div#order-container').addClass('hidden-md').addClass('hidden-lg');
			
			// Turn off the order tabs in the Track Container
			$('#form-tabs-element-scope-container').addClass('hidden-xs').addClass('hidden-sm');
			$('#form-tabs-element-items-container').addClass('hidden-xs').addClass('hidden-sm');
		
		} else if(status_track == 'off' && status_order == 'on') {
			// Items... Stretch the whole screen
			$('div#invoice-labor-container-mobile').removeClass('col-md-6').removeClass('col-lg-6').removeClass('col-md-push-6').addClass('col-md-12').addClass('col-lg-12');

			//$('div#tracking-container').('col-md-pull-6');
			$('div#tracking-container').addClass('hidden-xs').addClass('hidden-sm').addClass('hidden-md').addClass('hidden-lg');
			$('div#order-container').removeClass('hidden-xs').removeClass('hidden-sm').removeClass('hidden-md').removeClass('hidden-lg');
		} else {
			// Both off
			// Items... Stretch the whole screen
			$('div#invoice-labor-container-mobile').removeClass('col-md-6').removeClass('col-lg-6').removeClass('col-md-push-6').addClass('col-md-12').addClass('col-lg-12');
			
			$('div#tracking-container').addClass('hidden-xs').addClass('hidden-sm').addClass('hidden-md').addClass('hidden-lg');
			$('div#order-container').addClass('hidden-xs').addClass('hidden-sm').addClass('hidden-md').addClass('hidden-lg');
			
		}
		
		return false;
	}
	
	$(document).on("click", "#view-track-items", function() {
		status_track = $('input#tracked_item_status').val(),
		status_order = $('input#order_item_status').val();
		
		if(status_track == 'on') {
			// turn off
			$('input#tracked_item_status').val('off');
			status_track = 'off';
		} else {
			// turn on
			$('input#tracked_item_status').val('on');
			status_track = 'on';
		}
		
		configure_mobile_form(status_track, status_order);
		return false;
	});
	$(document).on("click", "#view-order-items", function() {
		status_track = $('input#tracked_item_status').val(),
		status_order = $('input#order_item_status').val();
		
		if(status_order == 'on') {
			// turn off
			$('input#order_item_status').val('off');
			status_order = 'off';
		} else {
			// turn on
			$('input#order_item_status').val('on');
			status_order = 'on';
		}
		
		configure_mobile_form(status_track, status_order);
		return false;
	});
	
	/* Once page is loaded */
	configure_mobile_form($('#tracked_item_status').val(), $('#order_item_status').val());
	
	
	$(".labor-combobox").combobox({ });
	$(document).on("select", ".labor-combobox", function() {
		addQuickSelect($(this), 'labor');
	});
	$(document).on("change", ".labor-combobox", function() {
		addQuickSelect($(this), 'labor');
	});
	$(".stock-combobox").combobox({ });
	$(document).on("select", ".stock-combobox", function() {
		addQuickSelect($(this), 'stock');
	});
	$(document).on("change", ".stock-combobox", function() {
		addQuickSelect($(this), 'stock');
	});
	function addQuickSelect(obj, type) {
		var val = obj.val(),
			desc = $('#' + type + '-data-bank #' + val + ' #description').val(),
			unit = $('#' + type + '-data-bank #' + val + ' #unit_item_cost').val(),
			qty = $('#' + type + '-data-bank #' + val + ' #unit').val(),
			cost = unit * qty;
			
		populateInvoiceLineItem(type, qty, desc, unit, cost);
		
		// Rest the Quick Select List
		obj.combobox("destroy");
		obj.val('');
		obj.combobox();
	}
	
	
	/**************************
	 *  REVIEW PAGE
	 */
	$(document).on("change", "#InvoicePaymentTermId", function() {
		var date = '',
			payment_term = $(this).val(),
			days = $('div#payment_term_data_bank div#data-bank-element-' + payment_term + ' input#day').val();
		
		if($("#InvoiceDateInvoiced").length) {
			date = $("#InvoiceDateInvoiced").val();
		} else if($("#InvoiceDateInvoicedMobile").length) {
			date = $("#InvoiceDateInvoicedMobile").val()
		}
			
		if(date.length) {
			calcDueDate(date, days);
		}
	});
	
	if($("#InvoiceDateDue").length) {
		$( "#InvoiceDateDue" ).datepicker({
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) { }	
		});
	}
	if($("#InvoiceDateInvoiced").length) {
		$( "#InvoiceDateInvoiced" ).datepicker({
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) { 
				var date = $(this).datepicker('getDate');
				if($('#InvoicePaymentTermId').val().length) {
					// Calculate the Due Date.
					var payment_term = $('#InvoicePaymentTermId').val(),
						days = $('div#payment_term_data_bank div#data-bank-element-' + payment_term + ' input#day').val();
					calcDueDate(dateStr, days);
				}
			}	
		});
	}
	
	function calcDueDate(date, days_incrament) {
		var msec = Date.parse(date),
			d = new Date(msec),
			myDate = new Date();
		
		//var myDate = new Date();
		myDate.setDate(d.getDate() + Number(days_incrament));
		if($('#InvoiceDateDue').length) {
			$('#InvoiceDateDue').val(myDate.format('m/d/Y'));	
		} else if($('#InvoiceDateDueMobile').length) {
			$('#InvoiceDateDueMobile').val(myDate.format('m/d/Y'));
		}
	}
	
	if($(".datepicker_invoice").length) {
		$( ".datepicker_invoice" ).datepicker({
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) {
		          //var date = $(this).datepicker('getDate');
		          //$('input.date_schedule').each(function() {
		        	//  $(this).val(dateStr);
		          //});
		    }	
		});
	}
	
	function scaleInvoicePreview() {
		var defaultWidth = 1199,
			currentWidth = Number($('div#page-simulator-container').get(0).offsetWidth),
			container_height = Number($('div#page-simulator-container').offset().top),
			perc = currentWidth/defaultWidth,
			xChange = ((defaultWidth - currentWidth) / 2)*-1;
		
		if (perc > 1) {
			perc = 1;
		}
		$('div#page-simulator').css({
			  'left' : xChange,	
			  '-webkit-transform' : 'scale(' + perc + ')',
			  '-moz-transform'    : 'scale(' + perc + ')',
			  '-ms-transform'     : 'scale(' + perc + ')',
			  '-o-transform'      : 'scale(' + perc + ')',
			  'transform'         : 'scale(' + perc + ')'
			});
		
		$('div#page-simulator').offset({ top: container_height });
	}
	// Call When page is laoded.
	if($('div#page-simulator-container').length) {
		scaleInvoicePreview();
	}
	
	$( window ).resize(function() {
		if($('#page-simulator').length) {
			scaleInvoicePreview()
		}
	});
	
	
	$(document).on("click", "#billing-details-edit", function() {
		$('#billing-details-display-container').css('display', 'none');
		$('#billing-details-edit-container').css('display', 'block');
		return false;
	});
	$(document).on("click", "#billing-details-display", function() {
		$('#billing-details-display-container').css('display', 'block');
		$('#billing-details-edit-container').css('display', 'none');
		return false;
	});
	
	function constructInvoiceTo() {
		var name = $('input#InvoiceCustomerName').val(),
			line1 = $('input#AddressLine1').val(),
			line2 = $('input#AddressLine2').val(),
			city = $('input#AddressCity').val(),
			state = $('input#AddressStProv').val(),
			zip = $('input#AddressZipPost').val(),
			contact = $('input#InvoiceContactName').val(),
			phone = $('input#InvoiceContactPhone').val(),
			email = $('input#InvoiceContactEmail').val(),
			newString = '';
		
		if(name.length) {
			newString = newString + name + '<br />';
		}
		if(line1.length) {
			newString = newString + line1 + '<br />';
		}
		if(line2.length) {
			newString = newString + line2 + '<br />';
		}
		if(city.length) {
			newString = newString + city + ', ';
		}
		if(state.length) {
			newString = newString + state + ' ';
		}
		if(zip.length) {
			newString = newString + zip;
		}
		if(contact.length) {
			newString = newString + '<br /><br />';
			newString = newString + contact;
		}
		if(phone.length) {
			newString = newString + '&nbsp;&nbsp;Ph:&nbsp;' + phone;
		}
		if(email.length) {
			newString = newString + '<br />' + email;
		}
	
		$('#details-content').html(newString);
	}
	$(document).on("blur", "input#InvoiceCustomerName", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#AddressLine1", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#AddressLine2", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#AddressCity", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#AddressStProv", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#AddressZipPost", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#InvoiceContactName", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#InvoiceContactPhone", function() {
		constructInvoiceTo();
	});
	$(document).on("blur", "input#InvoiceContactEmail", function() {
		constructInvoiceTo();
	});
});