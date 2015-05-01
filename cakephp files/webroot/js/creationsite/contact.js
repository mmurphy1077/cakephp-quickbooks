function addContact(id) {
	$('#'+id+'_toggle_display').toggle();
	
	// Clear values
	$('#ContactId').val('');
	$('#ContactNameFirst').val('');
	$('#ContactNameLast').val('');
	$('#ContactPhone1Number').val('');
	$('#ContactPhone1LabelCell').prop('checked', false);
	$('#ContactPhone1LabelFax').prop('checked', false);
	$('#ContactPhone1LabelWork').prop('checked', false);
	$('#ContactPhone1LabelHome').prop('checked', false);
	$('#ContactEmail').val('');
	$('#ContactTitle').val('');
	$('#ContactCompanyName').val($('#customer_name').val());
	$('#ContactPrimary').prop('checked', false);
	$('#AddressId').val('');
	$('#AddressName').val('');
	$('#AddressAddressTypeId').val($('#base_address_type_id').val());
	$('#AddressLine1').val('');
	$('#AddressLine2').val('');
	$('#AddressCity').val('');
	$('#AddressStProv').val($('#base_state').val());
	$('#zipcode').val('');
	$('#AddressPrimary').prop('checked', false);
	
	$('.buttonset').buttonset('refresh');
}

function setPrimaryContact(params, id, check_val) {
	$.ajax({
		url:  myBaseUrl + "contacts/ajax_primary_contact_for_model/"+params,
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
				$('.contact_primary_checkbox').each( function () {
					$(this).prop('checked', false);
				});
				
				if(check_val) {
					$('#' + id + '_contact_primary_checkbox').prop('checked', true);
				} else {
					$('#' + id + '_contact_primary_checkbox').prop('checked', false);
				}
			} else {
				// Revert the Primary checkbox back to its origninal state
				if(check_val) {
					$('#' + id + '_contact_primary_checkbox').prop('checked', false);
				} else {
					$('#' + id + '_contact_primary_checkbox').prop('checked', true);
				}
			}
			document.getElementById("ajax-loader-" + id).style.display = 'none';
		},
	});
}

$(document).ready(function(){
	$('.edit_contact').bind('click', function() {
		var id = $(this).attr('id');
		$('#ContactId').val($('#' + id + '_contact_data_bank #id').val());
		$('#ContactNameFirst').val($('#' + id + '_contact_data_bank #name_first').val());
		$('#ContactNameLast').val($('#' + id + '_contact_data_bank #name_last').val());
		$('#ContactEmail').val($('#' + id + '_contact_data_bank #email').val());
		$('#ContactTitle').val($('#' + id + '_contact_data_bank #title').val());
		$('#ContactCompanyName').val($('#' + id + '_contact_data_bank #company_name').val());
		$('#ContactPhone1Label').val($('#' + id + '_contact_data_bank #phone_1_label').val());
		$('#ContactPhone1Number').val($('#' + id + '_contact_data_bank #phone_1_number').val());
		$('#ContactPrimary').prop('checked', $('#' + id + '_contact_primary_checkbox').prop('checked'));
		$('#ContactAddressId').val($('#' + id + '_contact_data_bank #address_id').val());
		$('#AddressModel').val($('#' + id + '_contact_data_bank #address_model').val());
		$('#AddressForeignKey').val($('#' + id + '_contact_data_bank #address_foreign_key').val());
		$('#AddressName').val($('#' + id + '_contact_data_bank #address_name').val());
		$('#AddressLine1').val($('#' + id + '_contact_data_bank #address_line1').val());
		$('#AddressLine2').val($('#' + id + '_contact_data_bank #address_line2').val());
		$('#AddressCity').val($('#' + id + '_contact_data_bank #address_city').val());
		$('#AddressStProv').val($('#' + id + '_contact_data_bank #address_st_prov').val());
		$('#AddressZipPost').val($('#' + id + '_contact_data_bank  #address_zip_post').val());
		$('#AddressCountry').val($('#' + id + '_contact_data_bank  #address_country').val());
		//$('#zipcode-Address').val($('#' + id + '_contact_data_bank #address_zip_post').val());
		
		$('#AddressPrimary').prop('checked', $('#' + id + '_contact_primary_checkbox').prop('checked'));
		//$('#' + id + '_contact_data_bank #country').val()
		
		var address_type = $('#' + id + '_contact_data_bank #address_type_id').val();
		if(address_type.length) {
			$('#AddressAddressTypeId').val(address_type);
		} else {
			$('#AddressAddressTypeId').val($('#base_address_type_id').val());
		}
		
		// Phone Buttons
		$('#ContactPhone1LabelCell').prop('checked', false);
		$('#ContactPhone1LabelFax').prop('checked', false);
		$('#ContactPhone1LabelWork').prop('checked', false);
		$('#ContactPhone1LabelHome').prop('checked', false);
		$('#ContactPhone1Label' + $('#' + id + '_contact_data_bank #phone_1_label').val()).prop('checked', true);
		$('.buttonset').buttonset('refresh');
		
		// Either set or disable the Required fields
		$('input.required').each( function() {
			if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
				$(this).val('Required');
				$(this).addClass('required_color');
			} else {
				$(this).removeClass('required_color');
			}
		});
		
		// Id there is an address_id, loop throught the customer addresses an select a match.
		$('.company_address').each( function() {
			$(this).prop('checked', false);
		});
		var address_id = $('#' + id + '_contact_data_bank #address_id').val();
		if(address_id.length) {
			$('.company_address').each( function() {
				if($(this).attr('id') == address_id) {
					$(this).prop('checked', true);
				}
			});
		}
		
		$('#add_contact_toggle_display').css('display', 'block');
    	return false;
    });
	
	$('#add_contact').bind('click', function() {
		addContact($(this).attr('id'));
    	return false;
    });
	
	$('.contact_primary_checkbox').bind('click', function() {
		var id = $(this).attr('id');
		id = id.replace('_contact_primary_checkbox','');
		var foreign_key = $('#ContactForeignKey').val(),
			model = $('#ContactModel').val(),
			value = 0;
		if($(this).prop('checked')) {
			value = 1;
		}
		var params = '';
		params = params + 'foreign_key:'+ foreign_key + '/';
		params = params + 'model:'+ model + '/';
		params = params + 'contact_id:'+ id + '/';
		params = params + 'primary_value:'+ value + '/';
		console.log('params',params);
		setPrimaryContact(params, id, value);
		return false;
	});
	
	$(document).on("keyup", "#AddressLine1.filter", function(event) {
		var val = $(this).val(),
			data = '';

		$('.address-item').each( function() {
			data = $(this).data( "address" );
			$(this).css('display', 'none');
			if (data.toLowerCase().indexOf(val.toLowerCase()) >= 0) {
				$(this).css('display', 'block');
			} else {
				$(this).css('display', 'none');
			}
			
		});
	});
	
	$(document).on("change", ".company_address", function(event) {
		$('.company_address').each( function() {
			$(this).attr('checked', false);
		});
		
		// Check this item
		$(this).prop('checked', true);
	
		var id = $(this).attr('id'),
			address_id = $('#' + id + '_customer_address_data_bank #address_id').val(),
			model = $('#' + id + '_customer_address_data_bank #address_model').val(),
			foreign_key = $('#' + id + '_customer_address_data_bank #address_foreign_key').val(),
			name = $('#' + id + '_customer_address_data_bank #address_name').val(),
			line1 = $('#' + id + '_customer_address_data_bank #address_line1').val(),
			line2 = $('#' + id + '_customer_address_data_bank #address_line2').val(),
			city = $('#' + id + '_customer_address_data_bank #address_city').val(),
			state = $('#' + id + '_customer_address_data_bank #address_st_prov').val(),
			zip = $('#' + id + '_customer_address_data_bank  #address_zip_post').val(),
			country = $('#' + id + '_customer_address_data_bank  #address_country').val();
			
		$('#ContactAddressId').val(address_id);
		$('#AddressLine1').val(line1);
		$('#AddressLine2').val(line2);
		$('#AddressCity').val(city);
		$('#AddressStProv').val(state);
		$('#AddressZipPost').val(zip);
			
		return false;
	});
	
	$('input.address-input').bind('keyup', function() {
		// Make sure ContactAddressId.id field is blank.  Signal new address to the server.
		// BUT ONLY do it if the id is associated with a customer address.  If the id is a contact address.. then just modify the address (keep the id field)
		var id = $('#ContactAddressId').val(),
			customer_address = false;
		$('.company_address').each( function() {
			if($(this).prop('checked') && ($(this).attr('id') == id)) {
				customer_address = true;
			}
		});
		
		if(customer_address) {
			$('#ContactAddressId').val('');
			
			// Clear any addresses selected in the customer address container
			$('#'+id).prop('checked', false);
		}
	});
});