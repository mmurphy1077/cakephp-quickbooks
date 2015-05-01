$(document).ready(function(){
	function nl2br (str, is_xhtml) {
	    var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';
	    return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1' + breakTag + '$2');
	}
	function startPageLoader() {
		$('#page-loader').css('display', 'block');
	}
	function stopPageLoader() {
		$('#page-loader').css('display', 'none');
	}
	function removeEmployeeFromToList(id) {
		$('#employee_to_' + id).remove();
		$('input#employee_check_' + id).prop('checked', false);
	}
	function removeContactFromToList(id) {
		$('#contact_to_' + id).remove();
		$('input#contact_check_' + id).prop('checked', false);
	}
	
	function buildEmployeeTo(id, email) {
		html = '<div id="employee_to_' + id + '" class="employee_to"><div class="left employee_to_name">' + email + '</div><div id="employee_to_delete_' + id + '" class="right employee_to_delete"></div><input id="to_employee_id_' + id + '" type="hidden" value="' + id + '" name="data[Message][to_employee_id][' + id + ']"></div>';
		$('#to-container').append(html);
	}
	function buildContactTo(id, email) {
		html = '<div id="contact_to_' + id + '" class="contact_to"><div class="left employee_to_name">' + email + '</div><div id="contact_to_delete_' + id + '" class="right contact_to_delete"></div><input id="to_contact_id_' + id + '" type="hidden" value="' + id + '" name="data[Message][to_contact_id][' + id + ']"></div>';
		$('#to-container').append(html);
	}
	
	$("input.employee_check").click(function () {
		var id = $(this).attr('id').replace('employee_check_', ''),
			email = $('#employee_email_' + id).val(),
			html = '';
		
		// Was the checkbox selected?
		if ($(this).prop('checked')) {
			// Add email
			buildEmployeeTo(id, email);
		} else {
			// Remove email
			removeEmployeeFromToList(id);
		}
	});
	
	$(document).on("click", "input.contacts_email_check", function() {
		var id = $(this).attr('id').replace('contact_check_', ''),
			email = $('#contact_email_' + id).val(),
			html = '';
		
		// Was the checkbox selected?
		if ($(this).prop('checked')) {
			// Add email
			buildContactTo(id, email); 
		} else {
			// Remove email
			removeContactFromToList(id);
		}
	});
	
	$(document).on("click", ".employee_to_delete", function() {
		var id = $(this).attr('id').replace('employee_to_delete_', '');
		removeEmployeeFromToList(id);
	});
	$(document).on("click", ".contact_to_delete", function() {
		var id = $(this).attr('id').replace('contact_to_delete_', '');
		removeContactFromToList(id);
	});
	
	$(document).on("change", "#comment_type", function() {
		var value = $(this).val();
		switch(value) {
			case 'call_outbound': 
			case 'call_inbound': 
				// Activate the Reminder (Callback) element of the communication.
				// Clear the value in the touchbase-datepicker-message field.
				$('#message-touchbase-container input#touchbase-datepicker-message').val('');
				calculateDefaultDate();
				$('#message-touchbase-container').css('display', 'block');
				initializeDatePickers();
				break;
				
			default :
				$('#message-touchbase-container').css('display', 'none');
		}
	});
	$(document).on("change", "#message-touchbase-container #touch-base-type-select", function() {
		var value = $(this).val();
		calculateDate(value);
	});
	
	function initializeDatePickers() {
		$("#touchbase-datepicker-message.datepicker").datepicker({
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) {
		          var date = $(this).datepicker('getDate');
		          $(this).val(dateStr);
		    }
		});
	}
	
	function calculateDate(value) {
		var calc_date = new Date(),
			today = new Date(),
			display_date = '';
		
		if(value) {
			switch(value) {
				case '48_hrs' :
					// Add 2 days to today
					calc_date.setDate(today.getDate() + 2);
					break;
				case '1_week' :
					// Add 7 days to today
					calc_date.setDate(today.getDate() + 7);
					break;
				case '2_week' :
					// Add 14 days to today
					calc_date.setDate(today.getDate() + 14);
					break;
				case '1_month' :
					// Add 1 month to today
					calc_date.setMonth(today.getMonth() + 1);
					break;
				default :
					calc_date = '';		
			}		
			
			if(calc_date) {
				display_date = (Number(calc_date.getMonth( )) + 1)  + '/' + calc_date.getDate( ) + '/' + calc_date.getFullYear( );
			}
			
			$('#message-touchbase-container input#touchbase-datepicker-message').val(display_date);
			$('#message-touchbase-container select#touch-base-type-select').val(value);
		}
	}
	
	function calculateDefaultDate() {
		// If a default interval exists... calculate the appropriate date.
		var value = $('div#message-touchbase-container input#default_reminder_interval').val();
		calculateDate(value);
	}
	
	function searchContactEmails(search_value) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'search_value:'+ search_value + '/';
		
		$.ajax({
			url: myBaseUrl + "contacts/ajax_search_contact_emails/"+params,
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
					$('#contact_email_search_table').html(result);
				} else {
					
				}
				//document.getElementById("ajax-loader-customers").style.display = 'none';
			},
		});
	}
	
	$('#contact_search').keyup(function() {
		var value = $(this).val();
		if(value.length >= 3) {
			searchContactEmails(value);
		}
	});
	
	function deleteMessage(id, type) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'message_id:'+ id + '/';
		params = params + 'type:'+ type + '/';
		$.ajax({
			url: myBaseUrl + "messages/ajax_delete_message/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					/*
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						result = obj.result,
						error = obj.error;
					*/
					$('tr#message-row-' + id).remove();
					
				} else {
					
				}
				stopPageLoader();
			},
		});
	}
	
	function deleteComment(id) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'message_id:'+ id + '/';
		$.ajax({
			url: myBaseUrl + "messages/ajax_delete_comment/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					/*
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						result = obj.result,
						error = obj.error;
					*/
					$('#comment-' + id).remove();
					
				} else {
					
				}
				stopPageLoader();
			},
		});
	}
	
	$(document).on('click', '.delete_message_inbox', function() {
		var r=confirm("Delete this message from your In box?");
        if (r==true) {
        	var id = $(this).attr('id').replace('delete_message_', '');
        	deleteMessage(id, 'recipient');
        }
		return false;
	});
	$(document).on('click', '.delete_message_sent', function() {
		var r=confirm("Delete this message from your Sent box?");
        if (r==true) {
        	var id = $(this).attr('id').replace('delete_message_', '');
        	deleteMessage(id, 'sender');
        }
		return false;
	});
	$(document).on('click', '.delete-comment-link', function() {
		var r=confirm("Are you sure you wish to delete this comment?");
        if (r==true) {
        	var id = $(this).attr('id').replace('delete-comment-', '');
        	deleteComment(id);
        }
		return false;
	});
	
	$(document).on('click', '#message-action-reply', function(e) {
		if($('#send-to-selection-container').css('display') == 'none') {
			var attachments = '';
			if($('div#attachments-inline').length) {
				attachments = $('div#attachments-inline').html(); 
			}
			var content = $('#MessageContent').html(),
				from = $('input#email-from').val(),
				html = $('#message-thread-element-template').html(),
				posted = $('#posted-container').html();
			$('#attachments-container').css('display', 'none');
			$('div#select_attachment_container').css('display', 'block');
			$('#MessageContent').val('').focus();
			$('#MessageContent').attr('readonly', false);
			$('#email-subject').val('RE: ' + $('#email-subject').val());
			$('#email-subject').attr('readonly', false);
			$('input#email-from').val($('#reply_from').val());
			$('input#email-to').val('');
			$('#email-to').attr('readonly', false);
			$('input#MessageParentId').val($('input#MessageId').val());
			$('input#MessageId').val('');
			$('#send-to-selection-container').css('display', 'block');
					
			$('div.reply-to').each( function() {
				var reply_to_model = $(this).find('input#reply_to_model').val(),
					reply_to_id = $(this).find('input#reply_to_id').val(),
					reply_to_display = $(this).find('input#reply_to_display').val();
	
				if(reply_to_model == 'User') {
					buildEmployeeTo(reply_to_id, reply_to_display);
				} else {
					buildContactTo(reply_to_id, reply_to_display);
				}
			});
	
			// Move the orgininal email below.
			$('div#prev-message-container div#stage').html(html);
			$('div#prev-message-container div#stage').find('div#post-by').html('By: ' + from);
			$('div#prev-message-container div#stage').find('div#content').html(nl2br(content));
			$('div#prev-message-container div#stage').find('div#posted').html('Posted: ' + posted);
			if(attachments.length) {
				$('div#prev-message-container div#stage').find('ul#attachments-inline').html(attachments);
			} else {
				//$('div#prev-message-container div#stage').find('ul#attachments-inline').css('display', 'none');
			}
			if($('textarea#MessageContent').hasClass('isNotOwnerMessage')) {
				$('div#prev-message-container div#stage').find('div.prev-message').removeClass('isOwnerMessage');
				$('div#prev-message-container div#stage').find('div.prev-message').addClass('isNotOwnerMessage');
			}
			// Clear the portfolio pic and insert new one.
			$('div#prev-message-container div#stage div.message-portfolio-pic-container').html('');
			$('div#reply_data_bank img').appendTo('div#prev-message-container div#stage div.message-portfolio-pic-container');
			
			
			$('#view-message-').attr('id', 'view-message-' + $('input#MessageId').val());
			
			$('textarea#MessageContent').removeClass('isNotOwnerMessage');
			$('textarea#MessageContent').addClass('isOwnerMessage');
		}
		return false;
	});
	
	$(document).on('click', 'a.view-message-link', function() {
		var id = $(this).attr('id').replace('view-message-', ''),
			redirect = $('input#redirect').val();

		window.location.href = myBaseUrl + "messages/view/" + id + '/' + redirect + '/';
		return false;
	});
	
	

    var options = { 
        //target:        '#output1',   // target element(s) to be updated with server response 
  		// beforeSubmit:  validate,  // pre-submit callback 
        success:       showResponse  // post-submit callback 
    }; 
 
    // bind to the form's submit event 
    $('#ajax_comment_form').submit(function() { 
    	$('div#comment-loader').css('display', 'block');
 
    	// Clear all (previous) Validation Errors
		$('div.error-message-comment').each( function() {
			$(this).html('');
		});
		
		// VALIDATE
	    var result = validateComment($(this));
		if(result['valid']) { 
			// inside event callbacks 'this' is the DOM element so we first 
	        // wrap it in a jQuery object and then invoke ajaxSubmit.
	        $(this).ajaxSubmit(options); 
		} else {
			// Display Validation Errors
			for(var index in result) {
				$(this).find('#error-message-'+index).html(result[index]);
			}
		}
		$('div#comment-loader').css('display', 'none');
		
		// Enable the submit button.
		//$(this).find('input[type="submit"]').attr('disabled',false);
		
		// !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
		return false;
    }); 


	function validateComment(form_obj) {
		var valid = true; 
	    var data = new Array();
	    var error = new Array();

		data['MessageContent'] = form_obj.find('#MessageContent').val();
		
		/**
		* Comment
		* Verify that an comment is present
		*/
		if(!data['MessageContent'].length){
			valid = false;
			error['MessageContent'] = 'Error:  No comment to post.';
		}

		error['valid'] = valid;
	    return error; 
	} 

	// post-submit callback 
	function showResponse(responseText, statusText, xhr, $form)  { 
		obj = jQuery.parseJSON(responseText);
		var html = obj.html,
			success1 = obj.success,
			error = obj.error;

		$('#prev-message-container.enabled').html(html);
		$('textarea#MessageContent').val('');
		$('#message-touchbase-container').css('display', 'none');
		$('select#comment_type').val($("select#comment_type option:first").val());
	
		// Transfer the date to the Status Assignment Container
		$('#touchbase-datepicker').val($('input#touchbase-datepicker-message').val());
		
		// Obtain the filter value 
		var filterValue = $('#selected_filter').val();
		filter(filterValue);
	} 
});