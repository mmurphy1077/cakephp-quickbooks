$(document).ready(function(){
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
	
	$(document).on('click', ".edit_contact", function () {
		var id = $(this).attr('id');
		$('#ContactId').val($('#' + id + '_contact_data_bank #id').val());
		// OrderId alwasy stays the same.
		//$('#ContactOrderId').val($('#' + id + '_contact_data_bank #order_id').val());
		$contact_id = $('#' + id + '_contact_data_bank #contact_id').val();
		if($contact_id) {
			// A contact_id is present... Thus the user can "Update Cantact Info"
			$('#LabelAddToCustomer').html('Update on Customer Profile');
		} else {
			// The contact is new... Thus the user can "Add Contact to Customer Info"
			$('#LabelAddToCustomer').html('Add to Customer Profile');
		}
		$('#ContactContactId').val($contact_id);
		$('#ContactContactName').val($('#' + id + '_contact_data_bank #contact_name').val());
		if($('#ContactContactName').hasClass('required_color')) {
			$('#ContactContactName').removeClass('required_color');
		}
		$('#ContactContactTitle').val($('#' + id + '_contact_data_bank #contact_title').val());
		$('#ContactContactPhone').val($('#' + id + '_contact_data_bank #contact_phone').val());
		$('#ContactContactEmail').val($('#' + id + '_contact_data_bank #contact_email').val());
		$('#ContactContactTypeId').val($('#' + id + '_contact_data_bank #contact_type_id').val());
		$('#ContactContactPrimary0').prop('checked', false);
		$('#ContactContactPrimary1').prop('checked', false);
		$('#ContactPrimary' + $('#' + id + '_contact_data_bank #primary').val()).prop('checked', true);
		$('.buttonset').buttonset('refresh');
		
		$('#add_status_toggle_display').css('display', 'block');
    	return false;
    });
	
	function clearContactValues() {
		$('#ContactId').val('');
		$('#ContactContactId').val('');
		$('#ContactContactName').val('');
		$('#ContactContactName').addClass('required_color');
		$('#ContactContactTitle').val('');
		$('#ContactContactPhone').val('');
		$('#ContactContactEmail').val('');
		$('#ContactContactTypeId').val('');
		$("#ContactContactTypeId option:selected").prop("selected", false)
	
		$('#ContactPrimary0').prop('checked', true);
		$('#ContactPrimary1').prop('checked', false);
		$('.buttonset').buttonset('refresh');
	}
	
	$('#add_status').bind('click', function() {
		$('#add_status_toggle_display').toggle();
		
		// Clear values
		clearContactValues();
    	return false;
    });
	
	function saveContact() {
		var id = $('#ContactId').val(),
			foreign_key_id = $('#ContactForeignKey').val(),
			model = $('#ContactModel').val(),
			contact_id = $('#ContactContactId').val(),
			contact_name = $('#ContactContactName').val(),
			contact_phone = $('#ContactContactPhone').val(),
			contact_email = $('#ContactContactEmail').val(),
			contact_title = $('#ContactContactTitle').val(),
			contact_type_id = $('#ContactContactTypeId').val(),
			primary = 0,
			add_to_customer = 0;
		
		if($('#ContactPrimary1').prop('checked')) {
			primary = 1;
		}
		if($('#ContactAddToCustomer').prop('checked')) {
			add_to_customer = 1;
		}
			
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'id:'+ id + '/';
		params = params + 'foreign_key_id:'+ foreign_key_id + '/';
		params = params + 'contact_id:'+ contact_id + '/';
		params = params + 'contact_name:'+ contact_name + '/';
		params = params + 'contact_title:'+ contact_title + '/';
		params = params + 'contact_phone:'+ contact_phone + '/';
		params = params + 'contact_email:'+ contact_email + '/';
		params = params + 'contact_type_id:'+ contact_type_id + '/';
		params = params + 'add_to_customer:'+ add_to_customer + '/';
		params = params + 'primary:'+ primary + '/';
		
		var target_url = myBaseUrl + model.toLowerCase() + '_contacts/ajax_edit/'+params;
		$.ajax({
			url: target_url,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				document.getElementById("ajax-loader-contact").style.display = 'inline-block';
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						message = obj.message,
						html = obj.html,
						id = obj.id,
						error = obj.error,
						record_status = obj.record_status;
					
					if(record_status == 'edit') {
						// Replace the appropriate line
						$('tr#contact-row-' + id).replaceWith(html);
					} else {
						// Append to the end of the table.
						$('#' + model + '-contact tbody').append(html);
					}
					
					$('#add_status_toggle_display').css('display', 'none');
					$('#ajax-message-success-' + id).fadeIn('fast').delay(2500).fadeOut('fast');
				} else {
					// Display Error
					$('#ajax-message-error-contact').css('display', 'block');
					$('#ajax-message-error-contact').html(error);
				}
				
				// Clear out the values
				clearContactValues();
				document.getElementById("ajax-loader-contact").style.display = 'none';
			},
		});
	}
	
	$('#save_contact_data').bind('click', function() {
		// Saving the contact data to either Order of Quote.
		saveContact();
    	return false;
    });
	
	/**************************
	 *	When a Contact has been selected from the List
	 */
	$('table#contact_search_table').on('click', "tr.contact_search_table_row", function() {
		var id = $(this).attr('id');
		$('table#contact_search_table tr').each( function() {
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');

		// Access the databank
		$('#ContactContactName').val($('#contact_data_bank_' + id + ' #name').val());
		$('#ContactContactTitle').val($('#contact_data_bank_' + id + ' #title').val());
		$('#ContactContactPhone').val($('#contact_data_bank_' + id + ' #phone').val());
		$('#ContactContactEmail').val($('#contact_data_bank_' + id + ' #email').val());
		$('#ContactContactId').val(id);

		// Change the "Add Contact profile" to "Update Contact Info" .... And non-visible
		$('#LabelAddToCustomer').html('Update Contact Info');
		$('#ContactAddToCustomer').prop('checked', false);
		$('#LabelAddToCustomer').css('display', 'none');
		$('#ContactAddToCustomer').css('display', 'none');
		
		refreshPage();
		return false;
	});
});