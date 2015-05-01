<?php #$this->Html->script('jquery/jquery.form.min', array('inline' => false)); ?>
<script type="text/javascript">
$(function(){

	//prepare the form when the DOM is ready 
	$(document).ready(function() { 
	    var options = { 
	        //target:        '#output1',   // target element(s) to be updated with server response 
	  		// beforeSubmit:  validate,  // pre-submit callback 
	        success:       showResponse  // post-submit callback 
	 
	        // other available options: 
	        //url:       url         // override for form's 'action' attribute 
	        //type:      type        // 'get' or 'post', override for form's 'method' attribute 
	        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
	        //clearForm: true        // clear all form fields after successful submit 
	        //resetForm: true        // reset the form after successful submit 
	 
	        // $.ajax options can be used here too, for example: 
	        //timeout:   3000 
	    }; 
	 
	    // bind form using 'ajaxForm' 
	   	//$('.scheduleJobForm').ajaxForm(options); 
		
		// bind to the form's submit event 
	    $('#CommunicationLogAjaxAddForm').submit(function() { 
	    		$('#page-loader').css('display', 'none');
	    		$('div#ajax_loader_log').css('display', 'block');
	    	
	    		// Clear all (previous) Validation Errors
			$('div.error-message-log').each( function() {
				$(this).html('');
			});
			
			// VALIDATE
		    var result = validate($(this));
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
			$('div#ajax_loader_log').css('display', 'none');
			
			// Enable the submit button.
			$(this).find('input[type="submit"]').attr('disabled',false);
			
			// !!! Important !!! 
	        // always return false to prevent standard browser submit and page navigation 
			return false;
	    }); 
	});

	function validate(form_obj) {
		var valid = true; 
	    var data = new Array();
	    var error = new Array();

		data['CommunicationLogComment'] = form_obj.find('#CommunicationLogComment').val();
		
		/**
		* Comment
		* Verify that an comment is present
		*/
		if(!data['CommunicationLogComment'].length){
			valid = false;
			error['CommunicationLogComment'] = 'Error:  A description of the communication is required.';
		}

		error['valid'] = valid;
	    return error; 
	} 

	// post-submit callback 
	function showResponse(responseText, statusText, xhr, $form)  { 
		obj = jQuery.parseJSON(responseText);
		var html = obj.html;
		var message = obj.message;
		var success = obj.success;
		var error = obj.error;
		var new_id = obj.new_id;

		// If an id was passed back (initial save), store the value within the id element
		if(new_id && new_id.length){
			$('#id').val(new_id);
		}
		
		// Append the html to the beginning of the call_container element.
		if(success > 0){
			$('#call_container').prepend(html);
			$('#ajax-message-log').html(message);
			$('#ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');

			// Reset the Communication Log input fields
			$('#CommunicationLogComment').val('');
			var now = new Date();
			var month = now.getMonth()+1;
			var day = now.getDate();
			var year = now.getFullYear();
			$('#CommunicationLogDateCommunication').val(month + '/' + day + '/' + year);
			
		} else {
			// Error
			$('#error-ajax-message-log').html(message);
			$('#error-ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');
		}
	} 

   function deleteCallRecord(params, contact_id) {
		$.ajax({
			url: myBaseUrl + "communication_logs/ajax_delete/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				//document.getElementById("ajax-loader-contacts").style.display = 'inline-block';
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var obj = jQuery.parseJSON(data.responseText);
					var success = obj.success;
					var id = obj.id;
					var error = obj.error;
					$('#call_element_' + id).remove();
				} else {
					
				}
				//document.getElementById("ajax-loader-contacts").style.display = 'none';
			},
		});
	}
    
   $(document).on('click', ".delete_call_record", function () {
	   var response=confirm("Are you sure you want to delete this item?");
   		if (response==true) {
	   		var contact_id = $(this).attr('id');
	   		  
	       	// Build a name value parameters to send to the server
	   		var params = '';
	   		params = params + 'id:' + contact_id + '/';
	   		deleteCallRecord(params, contact_id);
		}
		return false;
	});
});
</script>