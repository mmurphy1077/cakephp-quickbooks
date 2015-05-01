<script type="text/javascript">	
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

	function clearQuoteContacts() {
		var id = $('#QuoteId').val();
		if(id) {
			var params = 'foreign_key:'+ id + '/model:Quote/';
			$.ajax({
				url: myBaseUrl + "quote_contacts/ajax_delete_all_contacts/"+params,
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
			$('table#Quote-contact tr.contact-row').each( function() {
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

	function clearReceipientAddress() {
		// Billing Address
		$('#BillingAddressName').val('');
		$('#BillingAddressLine1').val('');
		$('#BillingAddressLine2').val('');
		$('#BillingAddressCity').val('');
		$('#BillingAddressStProv').val('');
		$('#BillingAddressZipPost').val('');
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
						//$('div#autocomplete_container_customer').css('height', '400px');
					} else {
						//$('div#autocomplete_container_customer').css('height', '200px');
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
						//$('div#autocomplete_container_lead').css('height', '400px');
					} else {
						//$('div#autocomplete_container_lead').css('height', '200px');
					}
				} else {
					
				}
				//document.getElementById("ajax-loader-leads").style.display = 'none';
			},
		});
	}

	function clearPrevSelectedRows() {
		$('tr.search_table_row').each( function() {
			$(this).removeClass('selected');
		});
	}

	function convertQuoteToOrder(id) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'quote_id:'+ id + '/';
		$.ajax({
			url: myBaseUrl + "quotes/ajax_convert_to_order/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				document.getElementById("ajax-loader-modal").style.display = 'block';
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					// Display forwarding message!
					$('div.modal div#msg_container').html('Conversion Successful... forwarding to the new Job record.');
			
					// Forward the user to the order number returned to the server.
					var obj = jQuery.parseJSON(data.responseText);
					var success = obj.success;
					var order_id = obj.order_id;
					var error = obj.error;
					if(error.length == 0 && order_id.length > 0) {
						// Forward the user to the new onject.
						var controller = 'orders';
						var action = 'view';
						var url = controller + '/' + action + '/' + order_id; 
						if (url) { // require a URL
				              window.location = myBaseUrl + url; // redirect
				        }
					} else {
						document.getElementById("ajax-loader-modal").style.display = 'none';
						$('div.modal div.msg_container').html(error);
					}
				} else {
					document.getElementById("ajax-loader-modal").style.display = 'none';
					$('div.modal div.msg_container').html('Conversion Failed');
				}
			},
		});
	}
	
	function buildConvertToJob() {
		var html = 	'<div class="convert_quote_to_order_container">' + 
					'<div id="title_container">Convert Quote #<span id="sid_container"></span> to a <?php echo Configure::read('Nomenclature.Quote'); ?></div>' + 
					'<div id="content_container">' +
					'Converting this quote to an order will carry forward all existing  customer info, job details, documents and active records.  After conversion, this item will no longer appear in the Active Quotes List.  It will appear in the Jobs Section with a status marked, New Order.' +
					'</div>' +	
					'<div id="msg_container">Are you sure you want to convert this Quote to an Order?</div>' +		
					'<div id="button_container" class="title-buttons">' +
					'<?php echo $this->Html->link('No', '#', array('id' => 'no', 'class' => 'action_convert_quote_to_job')); ?>' +
					'<?php echo $this->Html->link('Yes', '#', array('id' => 'yes', 'class' => 'red action_convert_quote_to_job')); ?>' +
					'</div>' +
					'<div id="ajax-loader-modal"><?php echo $this->Html->image('loader-large.gif'); ?></div>'				
					'</div>';
		return html;
	} 
	
	$(document).ready(function(){
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

			clearQuoteContacts();
			clearReceipientAddress();
			
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

			// Test if a CustomerID already exists
			var existingCustomer = $('#QuoteQuoteCustomerId').val();
			if(!existingCustomer || (existingCustomer != id)) {
				// Clear out Existsing Contacts (Usesful when coping quote to a new Customer)
				clearQuoteContacts();
				clearReceipientAddress();
			}
			
			$('#customer_name').val(name);
			$('#QuoteQuoteCustomerMode').val(mode);
			$('#QuoteQuoteCustomerId').val(id);
			
			// Obtain the contact information for the Customer.
			getCustomerContactData(id);

			//Obtain address information for the Customer
			getCustomerAddressData(id);
			
			return false;
		});

		$('table#lead_search_table').on('click', "tr.lead_search_table_row", function(){
			var id = $(this).attr('id');
			var mode = 'contact';
			
			// Test if a CustomerID already exists
			var existingCustomer = $('#QuoteQuoteCustomerId').val();
			if(existingCustomer && existingCustomer != id) {
				// Clear out Existsing Contacts (Usesful when coping quote to a new Customer)
				clearQuoteContacts();
				clearReceipientAddress();
			}

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

			//Obtain address information for the Contact
			
			
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
		
		$('.convert_quote_to_job').bind('click', function() {
			var id = $(this).attr('id');
			$('div.modal').html(buildConvertToJob());
			var sid = $('#QuoteSid').val();
			$('div.modal span#sid_container').html(sid);
			$('div.modal').css('display', 'block');
			return false;
		});

		$('div.modal').on('click', ".action_convert_quote_to_job", function () {
			var id = $(this).attr('id');
			if(id == 'no') {
				// Close the modal window
				$('div.modal').html('');
				$('div.modal').css('display', 'none');
			} else {
				/*
				 * Yes .. Obtain the quote id
				 * Then execute an ajax function that will call the server to execute the quote to order conversion
				 */
				 var quote_id = $('#QuoteId').val();
				 convertQuoteToOrder(quote_id);
			}
			return false;
		});
	});	
</script>