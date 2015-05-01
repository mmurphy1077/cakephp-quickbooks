$(document).ready(function(){
	
	function assignAccountRep(contact_id, account_rep_id) {
		var xmlhttp_addResponderUser;    
		
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'contact_id:'+ contact_id + '/';
		params = params + 'account_rep_id:'+ account_rep_id + '/';
	
		
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp_addResponderUser=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp_addResponderUser=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp_addResponderUser.onreadystatechange=function()
		{
			// Use the readyState to turn on and off the loaders.
			// document.getElementById("ajax_loader_districts").show()
			// 
			if (xmlhttp_addResponderUser.readyState==1)
			{  
				$('#'+contact_id).hide();
				$('#ajax_loader_'+contact_id).show();
			}
			
			if (xmlhttp_addResponderUser.readyState==4 && xmlhttp_addResponderUser.status==200)
			{
				// Grab the result from the server.
				var results = xmlhttp_addResponderUser.responseText,
					obj = jQuery.parseJSON(xmlhttp_addResponderUser.responseText),
					success = obj.success,
					result = obj.result,
					error = obj.error;

				// Insert some error handeling
				if(obj.success.length > 0) {
					$('#ajax_loader_'+contact_id).hide();
					$('#ajax_loader_success_'+contact_id).show();
					
					// Remove the row from the table.
					///setTimeout(function() {
					///	$('#lead_row_'+contact_id).fadeOut()
			    	///},700);
					
					// Instead of removing row (ABOVE), insert the name of the account rep.
					$('#lead_row_'+contact_id+' td.account_rep').html(result);
					setTimeout(function() {
						$('#ajax_loader_success_'+contact_id).fadeOut()
				    },700);
					return false;
				} else {
					//error
					$('#'+contact_id).hide();
					$('#ajax_loader_'+contact_id).hide();
					$('#ajax_loader_error_'+contact_id).show();
					
					setTimeout(function() {
						$('#ajax_loader_error_'+contact_id).hide();
						$('#'+contact_id).show();
			    	},1000);
				}
				
				$('#'+contact_id).show();
				$('#ajax_loader_'+contact_id).hide();
				
				return false;
			}
		}
		xmlhttp_addResponderUser.open("GET", myBaseUrl + "contacts/ajax_assign_account_rep/"+params,true);
		xmlhttp_addResponderUser.send();
	}
	
	$('.assign_lead_to').bind('click', function() {
		// Determine IDs
		var contact_id = $(this).attr('id');
		var account_rep_id = $('#assign_lead_to_'+contact_id).val();
		
		if(account_rep_id.length == 0) {
			return false;
		}
		
		// At this point, all the necessary info is here.
		assignAccountRep(contact_id, account_rep_id);
		
		return false;
	});

	/* Ajax functionality covering the sorting (Up/Down) of the elements within the calculation taxonomies
	 * 
	 */
	function reorder(params)
	{
		var xmlhttp;    
		if (params[0]=="")
		{
			document.getElementById("ajax_msg").innerHTML="id variable is empty.";
			return;
		}
		if (params[2]=="")
		{
			document.getElementById("ajax_msg").innerHTML="delta variable is empty.";
			return;
		}
		var parent_id = params[1];
		
		//Use the params variable to create an named/value string to be sent to the server
		var params_array = '';
		params_array = params_array + 'id:'+ params[0] + '/';
		params_array = params_array + 'delta:' + params[2] + '/';
		params_array = params_array + 'parent:' + parent_id.substring(9) + '/';
		
		if (window.XMLHttpRequest)
		{// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		}
		else
		{// code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}

		xmlhttp.onreadystatechange=function()
		{
			// Use the readyState to turn on and off the loaders.
			if (xmlhttp.readyState==1)
			{  //document.getElementById("ajax_loader").style.display = 'block';  
				
			}
			
			if (xmlhttp.readyState==4 && xmlhttp.status==200)
			{
				// Once the function is executed, the following is the element is updated.
				// Repopulate the list of the parent 
				var results = xmlhttp.responseText;	
				document.getElementById(parent_id).innerHTML=results;
				//document.getElementById("ajax_loader").style.display = 'none';
			}
		}
		
		xmlhttp.open("GET", myBaseUrl + "calculation_taxonomies/reorder_ajax/"+escape(params_array),true);
		xmlhttp.send();
	}
	
	$(document).on("click", ".menu_item_move_up", function() { 
		var params = [$(this).attr('id'),$(this).closest("ul").attr('id'),1];
		
		// Using this, grab the id of the parent <div> having a class 'menu_items_container'
		reorder(params);
		return false;
	});
	// trigger when the down arrow is triggered
	$(document).on("click", ".menu_item_move_down", function() {
		var params = [$(this).attr('id'),$(this).closest("ul").attr('id'),-1];
		
		// Using this, grab the id of the parent <div> having a class 'menu_items_container'
		reorder(params);
		return false;
	});
	
});