$(document).ready(function(){
	function startLoaderPO() {
		$('#po-loader').css('display', 'block');
	}
	function stopLoaderPO() {
		$('#po-loader').css('display', 'none');
	}
	$( "form" ).submit(function( event ) {
		//startLoaderPO();
	});
	
	// Adding a New PO
	$('#add_po').bind('click', function() {
		$('input#PurchaseOrderId').val('');
		$('input#PurchaseOrderPoNumber').val('');
		$('input#PurchaseOrderDateInstall').val($('input#default_date_install').val());
		$('input#PurchaseOrderCustomerNumber').val('');
		$('select#PurchaseOrderStatus').val('1');
		$('textarea#PurchaseOrderDescription').val('');
		
		$('input#PurchaseOrderShipTo0').prop('checked', true);
		$('input#PurchaseOrderShipTo1').prop('checked', false);
		$('input#PurchaseOrderShipTo2').prop('checked', false);
		$('.ship_to_buttonset').buttonset('refresh');
		shipToJobSite();
		
		$('input#vendor_name').val('');
		$('input#VendorLine1').val('');
		$('input#VendorLine2').val('');
		$('input#VendorCity').val('');
		$('input#VendorStProv').val('OR');
		$('input#zipcode-Vendor').val('');
		$('input#PurchaseOrderTotal').val('');
		$('input#PurchaseOrderTax').val('');
		
		// Loop through each element to see if there is a value
		$('div#ship-to-address input.required').each( function() {
			if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
				$(this).val('Required');
				$(this).addClass('required_color');
			} else {
				$(this).removeClass('required_color');
			}
		});
		initPOItem();
		
		// Initialize the PurchaseOrderItem table
		var html = '<tr><th>&nbsp;</th><th>Qty</th><th>Description</th><th>Unit Cost</th><th>Item Total</th><th>&nbsp;</th></tr>';
		$('table#purchase-order-items').html(html);
		$('#add_po_item_toggle_display').css('display', 'block');
		
		// Adjust the approve box
		$('#po-approval-container').css('display', 'none');
    	return false;
    });
	
	function initPOItem() {
		// Clear values
		$('#purchase_order_item_id').val('');
		$('#purchase_order_item_qty').val('1');
		$('#purchase_order_item_description').val('');
		$('#purchase_order_item_price_unit').val('');
		$('#PurchaseOrderItemIncluded0').prop('checked', true);
		$('#PurchaseOrderItemIncluded1').prop('checked', false);
		$('.buttonset').buttonset('refresh');
	}
	
	$('#add_po_item').bind('click', function() {
		var id = $(this).attr('id');
		$('#'+id+'_toggle_display').toggle();
		initPOItem();
    	return false;
    });
	
	function constructPurchaseOrderItem(item) {
		var index = 0,
			html = '';
		
		// If the item is a current item (beign edited)... Use the index given.  If its a new item, use the new index and then incraent that index.
		if(item['id'].length) {
			index = Number($('#purchase_order_item_index').val());
		} else {
			index = Number($('#new_purchase_order_item_index').val());
			$('#new_purchase_order_item_index').val(index + 1);  // Incrament for the next one.
		}
		html = '<tr id="row-' + index + '" class="purchase-order-row">' + 
		'<td>&nbsp;</td>' + 
			'<td>' + item['qty'] + '</td>' + 
			'<td>' + item['desc'] + '</td>' + 
			'<td>$' + Number(item['unit']).toFixed(2) + '</td>' + 
			'<td>$' + Number(item['total']).toFixed(2) + '</td>' + 
			'<td class="actions">' + 
				'<a href="#" id="" class="delete_po_item">Delete</a>' + 
				'<a id="edit_purchase_order_item_' + index + '" class="edit_purchase_order_item row-click" href="#">Edit</a>' +
				'<div id="purchase-order-item-bank-' + index + '" class="purchase-order-item-bank hide">' + 
					'<input id="PurchaseOrderItemIndex" type="hidden" name="data[PurchaseOrderItem][' + index + '][index]" value="' + index + '">' +
					'<input id="PurchaseOrderItemId" type="hidden" name="data[PurchaseOrderItem][' + index + '][id]" value="' + item['id'] + '">' + 
					'<input id="PurchaseOrderItemQty" type="hidden" name="data[PurchaseOrderItem][' + index + '][qty]" value="' + item['qty'] + '">' +
					'<input id="PurchaseOrderItemPriceUnit" type="hidden" name="data[PurchaseOrderItem][' + index + '][price_unit]" value="' + item['unit'] + '">' +
					'<input id="PurchaseOrderItemTotal" type="hidden" name="data[PurchaseOrderItem][' + index + '][total]" value="' + item['total'] + '">' +
					'<input id="PurchaseOrderItemDescription" type="hidden" name="data[PurchaseOrderItem][' + index + '][description]" value="' + item['desc'] + '">' +
					'<input id="PurchaseOrderItemIncluded" type="hidden" name="data[PurchaseOrderItem][' + index + '][included]" value="' + item['include'] + '">' +
				'</div>' + 
			'</td>' +
		'</tr>';
		//item['id']
		//item['include']

		return html;
	}
	
	$('#save_purchase_order_item').bind('click', function(event) {
		// Gather Data
		var item = new Array();
		item['id'] = $('#purchase_order_item_id').val();
		item['qty'] = $('#purchase_order_item_qty').val();
		item['desc'] = $('#purchase_order_item_description').val();
		item['unit'] = $('#purchase_order_item_price_unit').val();
		item['include'] = 0;
		item['total'] = 0;
		
		if($('#PurchaseOrderItemIncluded1').prop('checked')) {
			 item['include'] = 1;
		}
		
		if(item['unit'].length) {
			 item['total'] = Number( item['unit']) * Number(item['qty']);
		}
		
		// Construct the table row and data bank.
		var $html = constructPurchaseOrderItem(item);
		// Append or Replace?
		// Grab the index... Loop through the rows.  If no rows exist with that index, Append.  else, Replace
		var index = $('#purchase_order_item_index').val(),
			mode = 'append';
		$('tr.purchase-order-row').each( function() {
			if($(this).attr('id') == 'row-' + index) {
				mode = 'replace';
			}
		});
		if(mode == 'replace') {
			$('tr#row-' + $('#purchase_order_item_index').val()).replaceWith($html);
		} else {
			$('table#purchase-order-items').append($html);
		}
		
		// Turn off the input box.
		//$('#add_po_item_toggle_display').toggle();
		initPOItem();
		
		// Calculate the total
		updateTotal();
		
		event.preventDefault();
    	return false;
    });
	
	function updateTotal() {
		var total = 0;
		$('.purchase-order-item-bank').each( function() {
			var item_value = Number($(this).find('#PurchaseOrderItemTotal').val());
			total = total + item_value;
		});
		total = total + Number($('#PurchaseOrderTax').val());
		$('#PurchaseOrderTotal').val(total.toFixed(2));
	}
	
	$('#PurchaseOrderTax').keyup(function() {
		updateTotal();
	});
	
	function disableShipTo() {
		$('#ShipToLine1').attr('readonly', true);
		$('#ShipToLine2').attr('readonly', true);
		$('#ShipToCity').attr('readonly', true);
		$('#ShipToStProv').attr('readonly', true);
		$('#zipcode-ShipTo').attr('readonly', true);
	}
	
	function enableShipTo() {
		$('#ShipToLine1').val('');
		$('#ShipToLine2').val('');
		$('#ShipToCity').val('');
		$('#ShipToStProv').val('OR');
		$('#zipcode-ShipTo').val('');
		
		$('#ShipToLine1').removeAttr('readonly');
		$('#ShipToLine2').removeAttr('readonly');
		$('#ShipToCity').removeAttr('readonly');
		$('#ShipToStProv').removeAttr('readonly');
		$('#zipcode-ShipTo').removeAttr('readonly');
		
		// Loop through each element to see if there is a value
		$('div#ship-to-address input.required').each( function() {
			if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
				$(this).val('Required');
				$(this).addClass('required_color');
			} else {
				$(this).removeClass('required_color');
			}
		});
	}
	
	function removeShipToRequired() {
		// Loop through each element to see if there is a value
		$('div#ship-to-address input.required').each( function() {
			$(this).removeClass('required_color');
		});
	}
	
	function shipToJobSite() {
		disableShipTo();
		$('input#ShipToLine1').val($('div#ship-to-data-bank #jobsite input#bank-line1').val());
		$('input#ShipToLine2').val($('div#ship-to-data-bank #jobsite input#bank-line2').val());
		$('input#ShipToCity').val($('div#ship-to-data-bank #jobsite input#bank-city').val());
		$('input#ShipToStProv').val($('div#ship-to-data-bank #jobsite input#bank-st-prov').val());
		$('input#zipcode-ShipTo').val($('div#ship-to-data-bank #jobsite input#bank-zip-post').val());
		removeShipToRequired();
	}
	
	$('.ship_to_buttonset').buttonset().click(function(){ 
		var id = $(".ship_to_buttonset :radio:checked").attr('id');
		switch(id) {
			case 'PurchaseOrderShipTo0' :
				// Job site
				// Grab the Jobsite address
				shipToJobSite();
				break;
			case 'PurchaseOrderShipTo1' :
				// Security Signs
				// Grab the Company address
				disableShipTo();
				$('input#ShipToLine1').val($('div#ship-to-data-bank #company input#bank-line1').val());
				$('input#ShipToLine2').val($('div#ship-to-data-bank #company input#bank-line2').val());
				$('input#ShipToCity').val($('div#ship-to-data-bank #company input#bank-city').val());
				$('input#ShipToStProv').val($('div#ship-to-data-bank #company input#bank-st-prov').val());
				$('input#zipcode-ShipTo').val($('div#ship-to-data-bank #company input#bank-zip-post').val());
				removeShipToRequired();
				break;
			case 'PurchaseOrderShipTo2' :
				enableShipTo();
				break;
		}
    });
	
	function searchVendors(search_value) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'search_value:'+ search_value + '/';
		
		$.ajax({
			url: myBaseUrl + "accounts/ajax_search_vendors/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				document.getElementById("ajax-loader-vendors").style.display = 'inline-block';
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText;
					var obj = jQuery.parseJSON(results);
					var success = obj.success;
					var result = obj.result;
					var error = obj.error;
					
					if(result.length) {
						$('#vendor_search_table').html(result);
					} else {
						$('#vendor_search_table').html('No matching vendors.');
					}
				} else {
					
				}
				document.getElementById("ajax-loader-vendors").style.display = 'none';
			},
		});
	}
	
	function approvePurchaseOrder(po_id, value) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'id:'+ po_id + '/';
		params = params + 'value:'+ value + '/';
		
		$.ajax({
			url: myBaseUrl + "purchase_orders/ajax_approve/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startLoaderPO();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText;
					var obj = jQuery.parseJSON(results);
					var success = obj.success;
					var user = obj.user;
					var status = obj.status;
					var error = obj.error;
					if(user.length) {
						$('#approve-container label').html('Approved by: ' + user);
					} else {
						$('#approve-container label').html('Approve');
					}
					$('select#PurchaseOrderStatus').val(status);
				} else {
					
				}
				stopLoaderPO();
			},
		});
	}
	
	$('#vendor_name').keyup(function() {
		var value = $(this).val();
		if(value.length >= 3) {
			searchVendors(value);
		}
	});
	
	function clearPrevSelectedRows() {
		$('tr.search_table_row').each( function() {
			$(this).removeClass('selected');
		});
	}
	
	$('table#vendor_search_table').on('click', "tr.vendor_search_table_row", function(){
		var id = $(this).attr('id'),
			name = $(this).find('#vendor_name_' + id).val(),
			address_id = $(this).find('.address_data_bank #id').val(),
			line1 = $(this).find('.address_data_bank #line1').val(),
			line2 = $(this).find('.address_data_bank #line2').val(),
			city = $(this).find('.address_data_bank #city').val(),
			state = $(this).find('.address_data_bank #state').val(),
			zip = $(this).find('.address_data_bank #zip').val();
		
		clearPrevSelectedRows();
		$(this).addClass('selected');
		$('#PurchaseOrderVendorId').val(id);

		$(this).addClass('selected');
		$('#vendor_name').val(name);
		$('#VendorAddressId').val(address_id);
		$('#VendorLine1').val(line1);
		$('#VendorLine2').val(line2);
		$('#VendorCity').val(city);
		$('#VendorStProv').val(state);
		$('#zipcode-Vendor').val(zip);
		
		// Check each required fields to determine if the address has been entered
		// Loop through each element to see if there is a value
		$('div#vendor-address input.required').each( function() {
			if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
				$(this).val('Required');
				$(this).addClass('required_color');
			} else {
				$(this).removeClass('required_color');
			}
		});
		
		return false;
	});
	
	$(document).on('keyup', "div#vendor-address input", function (event) {
		// If the name is being changed.. New Vendor and Address
		if($(this).attr('id') == 'vendor_name') {
			$('#PurchaseOrderVendorId').val('');
		} 
		$('#VendorAddressId').val('');
    });
	
	$('table#purchase-order-items').on('click', '.edit_purchase_order_item', function () {
		var id = $(this).attr('id').replace('edit_purchase_order_item_', ''),
			included_val = $('div#purchase-order-item-bank-' + id + ' input#PurchaseOrderItemIncluded').val();
			include_yes = false;
			include_no = true;
			
		if(Number(included_val) == 1) {
			include_yes = true;
			include_no = false;
		}
		$('#purchase_order_item_index').val($('div#purchase-order-item-bank-' + id + ' input#PurchaseOrderItemIndex').val());
		$('#purchase_order_item_id').val($('div#purchase-order-item-bank-' + id + ' input#PurchaseOrderItemId').val());
		$('#purchase_order_item_qty').val($('div#purchase-order-item-bank-' + id + ' input#PurchaseOrderItemQty').val());
		$('#purchase_order_item_description').val($('div#purchase-order-item-bank-' + id + ' input#PurchaseOrderItemDescription').val());
		$('#purchase_order_item_price_unit').val(Number($('div#purchase-order-item-bank-' + id + ' input#PurchaseOrderItemPriceUnit').val()).toFixed(2));
		$('#PurchaseOrderItemIncluded0').prop('checked', include_no);
		$('#PurchaseOrderItemIncluded1').prop('checked', include_yes);
		$('.buttonset').buttonset('refresh');
		
		// Make Visible
		$('#add_po_item_toggle_display').css('display', 'block');
		return false;
	});
	
	$('table#purchase-order-items').on('click', '.delete_po_item', function () {
		startLoaderPO();
		var id = $(this).attr('id').replace('delete_po_item_', '');
		var r=confirm("Are you sure you want to delete this item?");
        if (r==true) {
        	$(this).parents('tr.purchase-order-row').remove();
        	updateTotal();
        	if(id.length) {
    			// Record exists.
        		// Set the hidden '__delete_po_item_id' to the record that needs to be deleted. Then force a submit to save the current data (and delete)
        		$('#__delete_po_item_id').val(id);
    			$("#PurchaseOrderPurchasingForm").submit();
    		}  else {
    			stopLoaderPO();
    		}
        } else {
        	stopLoaderPO();
        }
        
		return false;
	});
	
	$(document).on('click', '.arrove-po', function () {
		var id = $(this).attr('id').replace('arrove-po-', ''),
			value = 0;
		if($(this).is(':checked')) {
			value = 1;
		}		
		approvePurchaseOrder(id, value);
		return true;
	});
});