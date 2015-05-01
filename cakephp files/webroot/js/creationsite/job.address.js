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
	
	/**************************
	 *	When a JOBSITE Address has been selected from the List
	 */
	$('table#jobsite_search_table').on('click', "tr.address_search_table_row", function(){
		var id = $(this).attr('id');
		$('table#jobsite_search_table tr').each( function() {
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');

		// Access the databank
		// Do not change the hidden model, foreign_key, address_type... Reserved for new addresses.	
		$('#AddressName').val($('#address_data_bank_' + id + ' #name').val());
		$('#AddressLine1').val($('#address_data_bank_' + id + ' #line1').val());
		$('#AddressLine2').val($('#address_data_bank_' + id + ' #line2').val());
		$('#AddressCity').val($('#address_data_bank_' + id + ' #city').val());
		$('#AddressStProv').val($('#address_data_bank_' + id + ' #st_prov').val());
		$('#AddressZipPost').val($('#address_data_bank_' + id + ' #zip_post').val());
		$('#AddressId').val(id);
		
		// Confirm that the "same as Recipient" checkbox is not selected.
		$('#QuoteJobsiteSameAsBilling').prop('checked', false);
		
		refreshPage();
		return false;
	});

	/**************************
	 *	When a BILLING/RECIPIENT Address has been selected from the List
	 */
	$('table#billing_search_table').on('click', "tr.address_search_table_row", function(){
		var id = $(this).attr('id');
		$('table#billing_search_table tr').each( function() {
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');

		// Access the databank
		// Do not change the hidden model, foreign_key, address_type... Reserved for new addresses.	
		$('#BillingAddressName').val($('#address_data_bank_' + id + ' #name').val());
		$('#BillingAddressLine1').val($('#address_data_bank_' + id + ' #line1').val());
		$('#BillingAddressLine2').val($('#address_data_bank_' + id + ' #line2').val());
		$('#BillingAddressCity').val($('#address_data_bank_' + id + ' #city').val());
		$('#BillingAddressStProv').val($('#address_data_bank_' + id + ' #st_prov').val());
		$('#BillingAddressZipPost').val($('#address_data_bank_' + id + ' #zip_post').val());
		$('#BillingAddressId').val(id);
		
		refreshPage();
		return false;
	});
	
	$('div#address_jobsite_container input').bind('keyup', function() {
		// Make sure Address.id field is blank.  Signal new address to the server.
		$('#AddressId').val('');

		// Clear any addresses selected form associated addresses.
		$('table#jobsite_search_table tr').each( function() {
			$(this).removeClass('selected');
		});
	});

	$('div#address_billing_container input').bind('keyup', function() {
		// Make sure BillingAddressId.id field is blank.  Signal new address to the server.
		$('#BillingAddressId').val('');

		// Clear any addresses selected form associated addresses.
		$('table#billing_search_table tr').each( function() {
			$(this).removeClass('selected');
		});
	});
	
	$('#jobsite_same_as_billing').bind('click', function() {
		if($(this).prop('checked')) {
			$('#AddressId').val($('#BillingAddressId').val());
			$('#AddressName').val($('#BillingAddressName').val());
			$('#AddressLine1').val($('#BillingAddressLine1').val());
			$('#AddressLine2').val($('#BillingAddressLine2').val());
			$('#AddressCity').val($('#BillingAddressCity').val());
			$('#AddressStProv').val($('#BillingAddressStProv').val());
			$('#AddressZipPost').val($('#BillingAddressZipPost').val());
			//$('#zipcode-Address').val($('#zipcode-BillingAddress').val());
			
			// Clear any addresses selected form associated addresses.
			$('table#billing_search_table tr').each( function() {
				$(this).removeClass('selected');
			});
		} else {
			$('#AddressId').val('');
		}

		refreshPage();
	});
});