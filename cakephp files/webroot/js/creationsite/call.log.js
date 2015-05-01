function today_date() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = mm+'/'+dd+'/'+yyyy;
	
	return mm+'/'+dd+'/'+yyyy;
}
function addCallRecord(params, contact_id) {
	/*
	 * 	Proceed to execute appropriate serverside functionality to add the Communication.
	 */
	
	if (window.XMLHttpRequest)
	{	// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{	// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	
	xmlhttp.onreadystatechange=function()
	{ 
		// Use the readyState to turn on and off the loaders.
		// document.getElementById("ajax_loader_districts").show()
		// 
		if (xmlhttp.readyState==1)
		{  
			//document.getElementById("").style.display = 'block';  
			$('.ajax_loader').each(function(index, value) {
				$(this).css('display', 'inline');
			});
		}
		
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			// Grab the result from the server.
			var results = xmlhttp.responseText;
			var obj = jQuery.parseJSON(xmlhttp.responseText);
			var success = obj.success;
			var message = obj.message;
			var html = obj.html;
			var error = obj.error;
			var new_id = obj.new_id;
			
			if($.trim(success) == '1') {
				$('div#notes_public').prepend(html);
				$('div#comm_log_' + contact_id).prepend(html);
				var value = message + $('input#note_public_' + contact_id).val();
				$('.ajax_message_success').fadeIn('fast').delay(2500).fadeOut('fast');
			} else {
				$('.ajax_message_fail').fadeIn('fast').delay(2500).fadeOut('fast');
			}
			
			//document.getElementById("ajax_loader").style.display = 'none'; 
			$('.ajax_loader').each(function(index, value) {
				$(this).css('display', 'none');
			});
			
			// Clear Notes and reset date.
			$('textarea.new_call_record').each(function(index, value) {
				$(this).val('');
			}); 
			$('input.new_date').each(function(index, value) {
				$(this).val(today_date());
			}); 
		}
	}
	// code to retrieve the skill for the Skill.id.
	//xmlhttp.open("GET", myBaseUrl + "contacts/ajax_add_call_record/" + escape(params), true);
	xmlhttp.open("GET", myBaseUrl + "communication_logs/ajax_add/" + escape(params), true);
	xmlhttp.send();
}

$(document).ready(function(){
	$('.transfer_id').bind('click', function(){
		var id = $(this).attr('id');
		var note = $('#comm_log_'+id).html();
		
		// Update Values on the cluetip dialog box.
		$('.widget_container').attr('id', 'widget_container_'+id);
		$('#ContactId').val(id);
		$('div#notes_public').html(note);
		
		// Reset messages
		$('.ajax_message_success').each(function(index, value) {
			$(this).css('display', 'none');
		});
		$('.ajax_message_fail').each(function(index, value) {
			$(this).css('display', 'none');
		});
		
		// Reset date.
		$('input.new_date').each(function() {
      	  $(this).val(today_date());
        });
	});
		
    $('#save_call_record').on('click', function(){
    	var contact_id = $('#ContactId').attr('value');
    	$('.widget_container').each(function(index, value) {
    		if($(this).parent().css('display') != 'none') {
    			var contact_date = $(this).find('#datepicker_cluetip').val();
    			var comments = $(this).find('#new_call_record').val();
    			var model = $(this).find('#model').val();
    			
    			// Remove line breaks
    			comments = comments.replace(/(\r\n|\n|\r)/gm,"||||");
    			
    			// Build a name value parameters to send to the server
    			var params = '';
    			params = params + 'date_communication:'+ contact_date.replace(/\//g,'-') + '/';
    			params = params + 'foreign_key:' + contact_id + '/';
    			params = params + 'model:' + model + '/';
    			params = params + 'comment:'+ comments + '/';
    			params = params + 'communication_type:phone/';
    			addCallRecord(params, contact_id);
    		}
    	});
		return false;
	});
    
    $('#add_note_button').on('click', function(){
    	$(this).css('display', 'none');
    	$('div#add_note_input_container').css('display', 'block');
    	
    	return false;
	});
});