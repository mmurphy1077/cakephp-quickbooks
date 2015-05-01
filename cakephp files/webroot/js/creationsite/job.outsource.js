$(document).ready(function(){
	$(document).on('click', ".edit_outsource", function () {
		var id = $(this).attr('id');
		$('#OrderOutsourceId').val($('#' + id + '_outsource_data_bank #id').val());
		// OrderId always stays the same.
		$OrderOutsource_id = $('#' + id + '_outsource_data_bank #id').val();
		$('#OrderOutsourceId').val($OrderOutsource_id);
		$('#OrderOutsourceName').val($('#' + id + '_outsource_data_bank #name').val());
		if($('#OrderOutsourceName').hasClass('required_color')) {
			$('#OrderOutsourceName').removeClass('required_color');
		}
		$('#OrderOutsourceCost').val($('#' + id + '_outsource_data_bank #cost').val());
		$('#OrderOutsourceDescription').val($('#' + id + '_outsource_data_bank #description').val());
		$('#OrderOutsourceDistribute0').prop('checked', false);
		$('#OrderOutsourceDistribute1').prop('checked', false);
		$('#OrderOutsourceDistribute' + $('#' + id + '_outsource_data_bank #distribute').val()).prop('checked', true);
		//$('.buttonset').buttonset('refresh');
		
		$('#add_outsource_toggle_display').css('display', 'block');
    	return false;
    });
	
	$('#add_outsource').bind('click', function() {
		$('#add_outsource_toggle_display').toggle();
		
		// Clear values
		$('#OrderOutsourceId').val('');
		$('#OrderOutsourceName').val('');
		$('#OrderOutsourceName').addClass('required_color');
		$('#OrderOutsourceDescription').val('');
		$('#OrderOutsourceCost').val('');
		
		$('#OrderOutsourceDistribute0').prop('checked', true);
		$('#OrderOutsourceDistribute1').prop('checked', false);
		//$('.buttonset').buttonset('refresh');
		
    	return false;
    });
	
	function saveOrderOutsource() {
		var id = $('#OrderOutsourceId').val();
		var order_id = $('#OrderOutsourceOrderId').val();
		var name = $('#OrderOutsourceName').val();
		var description = $('#OrderOutsourceDescription').val();
		var cost = $('#OrderOutsourceCost').val();
		var distribute = 0;
		if($('#OrderOutsourceDistribute1').prop('checked')) {
			distribute = 1;
		}
			
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'id:'+ id + '/';
		params = params + 'order_id:'+ order_id + '/';
		params = params + 'name:'+ name + '/';
		params = params + 'description:'+ description + '/';
		params = params + 'cost:'+ cost + '/';
		params = params + 'distribute:'+ distribute + '/';
		var target_url = myBaseUrl + 'order_outsources/ajax_edit/'+params;
		$.ajax({
			url: target_url,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				document.getElementById("ajax-loader-outsource").style.display = 'inline-block';
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText;
					var obj = jQuery.parseJSON(results);
					var success = obj.success;
					var message = obj.message;
					var html = obj.html;
					var id = obj.id;
					var error = obj.error;
					var record_status = obj.record_status;
					
					if(record_status == 'edit') {
						// Replace the appropriate line
						$('tr#outsource-row-' + id).replaceWith(html);
					} else {
						// Append to the end of the table.
						$('#order-outsource tbody').append(html);
					}
					
					$('#add_outsource_toggle_display').css('display', 'none');
					$('#ajax-message-success-' + id).fadeIn('fast').delay(2500).fadeOut('fast');
				} else {
					// Display Error
					$('#ajax-message-error-outsource').css('display', 'block');
					$('#ajax-message-error-outsource').html(error);
				}
				document.getElementById("ajax-loader-outsource").style.display = 'none';
			},
		});
	}
	
	$('#save_outsource').bind('click', function() {
		// Saving the OrderOutsource data to either Order of Quote.
		saveOrderOutsource();
		
    	return false;
    });
});