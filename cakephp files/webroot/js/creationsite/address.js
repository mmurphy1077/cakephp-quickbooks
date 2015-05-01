function setPrimaryAddress(params, id, check_val) {
	$.ajax({
		url:  myBaseUrl + "addresses/ajax_primary_address_for_mod/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			document.getElementById("ajax-loader-" + id).style.display = 'inline-block';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				// Clear all other primary check boxes except the one delected.
				var results = data.responseText;
				var obj = jQuery.parseJSON(results);
				var success = obj.success;
				var message = obj.message;
				var error = obj.error;
				
				// Clear all check box values
				$('.address_primary_checkbox').each( function () {
					$(this).prop('checked', false);
				});
				
				if(check_val) {
					$('#' + id + '_address_primary_checkbox').prop('checked', true);
				} else {
					$('#' + id + '_address_primary_checkbox').prop('checked', false);
				}
			} else {
				// Revert the Primary checkbox back to its origninal state
				if(check_val) {
					$('#' + id + '_address_primary_checkbox').prop('checked', false);
				} else {
					$('#' + id + '_address_primary_checkbox').prop('checked', true);
				}
			}
			document.getElementById("ajax-loader-" + id).style.display = 'none';
		},
	});
}

function deleteAddress(id) {
	var params = '';
	params = params + 'id:'+ id + '/';
	
	$.ajax({
		url:  myBaseUrl + "addresses/ajax_delete/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			document.getElementById("page-loader").style.display = 'inline-block';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				// Remove the address form the row
				var results = data.responseText;
				var obj = jQuery.parseJSON(results);
				var success = obj.success;
				var error = obj.error;
				
				// Clear all check box values
				$('#row-' + id).remove();
			} else {
	
			}
			document.getElementById("page-loader").style.display = 'none';
		},
	});
}

function edit_address(id) {
	$('#AddressId').val($('#' + id + '_address_data_bank #id').val());
	$('#AddressModel').val($('#' + id + '_address_data_bank #model').val());
	$('#AddressForeignKey').val($('#' + id + '_address_data_bank #foreign_key').val());
	$('#AddressName').val($('#' + id + '_address_data_bank #name').val());
	$('#AddressAddressTypeId').val($('#' + id + '_address_data_bank #address_type_id').val());
	$('#AddressLine1').val($('#' + id + '_address_data_bank #line1').val());
	$('#AddressLine2').val($('#' + id + '_address_data_bank #line2').val());
	$('#AddressCity').val($('#' + id + '_address_data_bank #city').val());
	$('#AddressStProv').val($('#' + id + '_address_data_bank #st_prov').val());
	$('#AddressZipPost').val($('#' + id + '_address_data_bank #zip_post').val());
	$('#AddressPrimary').prop('checked', $('#' + id + '_address_primary_checkbox').prop('checked'));
	$('#AddressCountry').val($('#' + id + '_address_data_bank #country').val());
	$('#AddressNotes').val($('#' + id + '_address_data_bank #notes').val());
	
	// Either set or disable the Required fields
	$('input.required').each( function() {
		if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
			$(this).val('Required');
			$(this).addClass('required_color');
		} else {
			$(this).removeClass('required_color');
		}
	});
	
	if($('#address-form-label').length) {
		$('#address-form-label').html('Edit Address');
	}
	
	$('#add_address_toggle_display').css('display', 'block');
}

function add_address(id) {
	// Clear values
	var id = $(this).attr('id');
	$('#AddressId').val('');
	$('#AddressName').val('');
	$('#AddressAddressTypeId').val($('#base_address_type_id').val());
	$('#AddressLine1').val('');
	$('#AddressLine2').val('');
	$('#AddressCity').val('');
	$('#AddressStProv').val($('#base_state').val());
	$('#AddressZipPost').val('');
	$('#AddressNotes').val('');
	$('#AddressPrimary').prop('checked', false);
	
	// Reset the Required fields.
	// Loop through each element to see if there is a value
	$('input.required').each( function() {
		if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
			$(this).val('Required');
			$(this).addClass('required_color');
		}
	});
	
	//$('#add_address'+id+'_toggle_display').css('display', 'block');
	$('#add_address_toggle_display').css('display', 'block');
	
	if($('#address-form-label').length) {
		$('#address-form-label').html('Add Address');
	}
}

$(document).ready(function(){
	$('.edit_location').bind('click', function() {
		var id = $(this).attr('id');
		
		$('#LocationId').val($('#' + id + '_address_data_bank #location_id').val());
		$('#LocationName').val($('#' + id + '_address_data_bank #location_name').val());
		$('#LocationEmail').val($('#' + id + '_address_data_bank #location_email').val());
		$('#LocationPhone').val($('#' + id + '_address_data_bank #location_phone').val());
		$('#LocationEmailBilling').val($('#' + id + '_address_data_bank #location_email_billing').val());
		$('#LocationPhoneBilling').val($('#' + id + '_address_data_bank #location_phone_billing').val());
		
		var primary_val = $('#' + id + '_address_data_bank #location_primary').val(),
			billing_val = $('#' + id + '_address_data_bank #location_billing').val(),
			primary_check = false,
			billing_check = false;
		
		if(primary_val == 1) {
			primary_check = true;
		}
		if(billing_val == 1) {
			billing_check = true;
		}
		$('#LocationPrimary').prop('checked', primary_check);
		$('#LocationBilling').prop('checked', billing_check);
		
		edit_address(id);
		return false;
	});
	
	$('.edit_address').bind('click', function() {
		var id = $(this).attr('id');
		edit_address(id);
    	return false;
    });
	
	$('#add_location').bind('click', function() {
		var id = $(this).attr('id');
		
		$('#LocationId').val('');
		$('#LocationName').val('');
		$('#LocationPrimary').prop('checked', false);
		$('#LocationEmail').val('').prop('disabled', true);
		$('#LocationPhone').val('').prop('disabled', true);
		$('#LocationBilling').prop('checked', false);
		$('#LocationEmailBilling').val('').prop('disabled', true);
		$('#LocationPhoneBilling').val('').prop('disabled', true);

		add_address(id);
    	return false;
    });
	
	$('#add_address').bind('click', function() {
		var id = $(this).attr('id');
		add_address(id);
    	return false;
    });
	
	$('.delete_address').bind('click', function() {
		var id = $(this).attr('id'),
			r=confirm('Are you sure you want to delete this item?');
        if (r==true) {
        	deleteAddress(id);
        }
    	return false;
    });
	
	$('.address_primary_checkbox').bind('click', function() {
		var id = $(this).attr('id');
		id = id.replace('_address_primary_checkbox','');
		var model = $('#' + id + '_address_data_bank #model').val(),
			foreign_key = $('#' + id + '_address_data_bank #foreign_key').val(),
			value = 0;
		if($(this).prop('checked')) {
			value = 1;
		}
		var params = '';
		params = params + 'model:'+ model + '/';
		params = params + 'foreign_key:'+ foreign_key + '/';
		params = params + 'address_id:'+ id + '/';
		params = params + 'primary_value:'+ value + '/';
		
		setPrimaryAddress(params, id, value);
		return false;
	});
	
	$('#LocationPrimary').bind('click', function() {
		if($(this).prop('checked')) {
			$('#location-primary-container input').each( function () {
				$(this).prop('disabled', false);
			});
		} else {
			$('#location-primary-container input').each( function () {
				$(this).prop('disabled', true);
				$(this).val('');
			});
		}
	});
	
	$('#LocationBilling').bind('click', function() {
		if($(this).prop('checked')) {
			$('#location-billing-container input').each( function () {
				$(this).prop('disabled', false);
			});
		} else {
			$('#location-billing-container input').each( function () {
				$(this).prop('disabled', true);
				$(this).val('');
			});
		}
	});
	
});