
function addCallRecord(params) {
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
			//document.getElementById("ajax_loader_skillsets").style.display = 'block';  
		
		}
		
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			// Grab the result from the server.
			result = xmlhttp.responseText;
			if($.trim(result) == '1') {
				$('#ajax_message_success').fadeIn('fast').delay(2000).fadeOut('fast');
				// Clear the inputs
				$('#comment').attr('value', 'Comment:');
			} else {
				$('#ajax_message_fail').fadeIn('fast').delay(2000).fadeOut('fast');
			}
			
			// Determine is an error array is contained inside the results
			// Test if '{"error":' is contained in the first 9 characters of the string.
			//alert(result);
			
		}
	}
	// code to retrieve the skill for the Skill.id.
	xmlhttp.open("GET", myBaseUrl + "contacts/add_call_record_ajax/" + escape(params), true);
	xmlhttp.send();
}

$(document).ready(function(){

	$('.transfer_id').bind('click', function(){
		var id = $(this).attr('id');
		var note = $('#note_internal_'+id).val();
	
		// Update Values on the cluetip dialog box.
		$('.widget_container').attr('id', 'widget_container_'+id);
		$('#ContactId').val(id);
		$('#ContactNotesInternal').val(note);
	});
		
    $('#save_call_record').live('click', function(){
    	var contact_id = $('#ContactId').attr('value');
    	
    	$('.widget_container').each(function(index, value) {
    		if($(this).parent().css('display') != 'none') {
    			var contact_date = $(this).find('#ContactDate').attr('value')
    			var comments = $(this).find('#new_call_record').attr('value');
    			
    			// Build a name value parameters to send to the server
    			var params = '';
    			params = params + 'contact_date:'+ contact_date + '/';
    			params = params + 'contact_id:' + contact_id + '/';
    			params = params + 'comments:'+ comments + '/';
    			addCallRecord(params);
    		}
    	});
		return false
	});
});
