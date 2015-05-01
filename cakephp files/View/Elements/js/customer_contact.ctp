<script type="text/javascript">
<?php if(!empty($quote)) : ?>
$(document).ready(function(){
	$('#QuoteContactPhone').combobox();
	$('#QuoteContactName').combobox();
	$("#QuoteContactName").change(function() {
		// When a option is selected from the contact name drop down, we want to 
		// Automatically change the contact phone number to that of the selected value.
	    var contact_id = this.value;
	    
	    // Obtain the contacts phone number
	    var contactPhones = $('#contact_to_phone_list').attr('value');
	    var contact_phone_array = jQuery.parseJSON(contactPhones);
		
	    // Set the QuoteContactPhone value to that of the selected contact.
	    var phone_number = contact_phone_array[contact_id];
	    $('#QuoteContactPhone').combobox('autocomplete', phone_number); 
	});
	
	<?php 
	if(!empty($quote)) :
		if(array_key_exists('contact_name', $quote['Quote']) && !empty($quote['Quote']['contact_name'])) : 
	?>
		$('#QuoteContactName').combobox('autocomplete', '<?php echo $quote['Quote']['contact_name']; ?>');
	<?php 
		endif;
		if(array_key_exists('contact_phone', $quote['Quote']) && !empty($quote['Quote']['contact_phone'])) : ?>
		$('#QuoteContactPhone').combobox('autocomplete', '<?php echo $quote['Quote']['contact_phone']; ?>');
	<?php 
		endif;
	endif;
	?>

	function clearContactData() {
		$('#QuoteContactName').html('');
		$('#QuoteContactPhone').html('');
		$('#QuoteContactName').combobox('loadData');
		$('#QuoteContactPhone').combobox('loadData');
		$('#QuoteContactName').combobox('autocomplete');
		$('#QuoteContactPhone').combobox('autocomplete');
	}
	
	function getContactData(id) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'customer_id:'+ id + '/';
		
		$.ajax({
			url: myBaseUrl + "contacts/ajax_get_contact_names_and_phones_for_customer/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				document.getElementById("loader-contact_name").style.display = 'inline-block';
				document.getElementById("loader-contact_phone").style.display = 'inline-block';
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText;
					var obj = jQuery.parseJSON(results);
					var success = obj.success;
					var error = obj.error;

					if(success == 1) {
						var contactNames = obj.contactNames;
						var contactPhones = obj.contactPhones;
						var contactPhonesList = obj.contactPhonesList;
						var default_phone = obj.default_phone;

						$('#QuoteContactName').html(contactNames);
						$('#QuoteContactPhone').html(contactPhones);
						$('#QuoteContactName').combobox('loadData');
						$('#QuoteContactPhone').combobox('loadData');
						$('#contact_to_phone_list').val(contactPhonesList);

						//$('#QuoteContactPhone').combobox('autocomplete', default_phone); 
					}
				} else {
					
				}
				document.getElementById("loader-contact_name").style.display = 'none';
				document.getElementById("loader-contact_phone").style.display = 'none';
			},
		});
	}
	
	$('#QuoteContactId').change(function() {
		if ($(this).val() != '') {
			$('#QuoteCustomerId').attr('disabled', true);
			$('#QuoteCustomerName').attr('disabled', true);

			// Populate the QuoteContactName & QuoteContactPhone comboboxes with data for the Customer.
			getContactData($(this).val());
		} else {
			$('#QuoteCustomerId').removeAttr('disabled');
			$('#QuoteCustomerName').removeAttr('disabled');

			// Clear the QuoteContactName & QuoteContactPhone comboboxes
			clearContactData();
		}
	});
	$('#QuoteCustomerId').change(function() {
		if ($(this).val() != '') {
			$('#QuoteContactId').attr('disabled', true);
			$('#QuoteCustomerName').attr('disabled', true);

			// Populate the QuoteContactName & QuoteContactPhone comboboxes with data for the Contact.
			getContactData($(this).val());
		} else {
			$('#QuoteContactId').removeAttr('disabled');
			$('#QuoteCustomerName').removeAttr('disabled');

			// Clear the QuoteContactName & QuoteContactPhone comboboxes
			clearContactData();
		}
	});
	$('#QuoteCustomerName').keyup(function() {
		if ($(this).val() != '') {
			$('#QuoteContactId').attr('disabled', true);
			$('#QuoteCustomerId').attr('disabled', true);
		} else {
			$('#QuoteContactId').removeAttr('disabled');
			$('#QuoteCustomerId').removeAttr('disabled');
		}

		// Clear the QuoteContactName & QuoteContactPhone comboboxes
		clearContactData();
	});
});
<?php elseif(!empty($order)) : ?>
$(document).ready(function(){
	$('#OrderContactPhone').combobox();
	$('#OrderContactName').combobox();
	$("#OrderContactName").change(function() {
		// When a option is selected from the contact name drop down, we want to 
		// Automatically change the contact phone number to that of the slected value.
	    var contact_id = this.value;
	    
	    // Obtain the contacts phone number
	    var contactPhones = $('#contact_to_phone_list').attr('value');
	    var contact_phone_array = jQuery.parseJSON(contactPhones);
		
	    // Set the OrderContactPhone value to that of the selected contact.
	    var phone_number = contact_phone_array[contact_id];
	    //$('#OrderContactPhone').val(phone_number);
	    $('#OrderContactPhone').combobox('autocomplete', phone_number); 
	});
	
	<?php 
	if(!empty($order)) :
		if(array_key_exists('contact_name', $order['Order']) && !empty($order['Order']['contact_name'])) : 
	?>
		$('#OrderContactName').combobox('autocomplete', '<?php echo $order['Order']['contact_name']; ?>');
	<?php 
		endif;
		if(array_key_exists('contact_phone', $order['Order']) && !empty($order['Order']['contact_phone'])) : ?>
		$('#OrderContactPhone').combobox('autocomplete', '<?php echo $order['Order']['contact_phone']; ?>');
	<?php 
		endif;
	endif;
	?>
});
<?php endif; ?>
</script>		

