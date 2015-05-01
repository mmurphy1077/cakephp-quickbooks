$(document).ready(function() {
	$("#touchbase-datepicker.datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);
	          pass_date = (Number(date.getMonth( )) + 1) + '_' + date.getDate( ) + '_' + date.getFullYear( );
	          updateReminder(pass_date);
	    }
	});
	
	/**
	 * Status and Assinged to changes for Order, Quote, and Invoice
	 */
	function updateReminder(value) {
		var model = $('#status-container-model').val(),
			foreign_key = $('#status-container-foreign-key').val(),
			params = 'value:'+ value + '/';
		params = params + 'model:'+ model + '/';
		params = params + 'foreign_key:'+ foreign_key + '/';
			
		$.ajax({
			url: myBaseUrl + "reminders/ajax_update/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						html = obj.html,
						error = obj.error;
					
					if(success) {
						console.log('html', html);
						$('div#reminder-display').html(html);
					} else {
						
					}
				} else {
				}
				stopPageLoader();
			},
		});
	}
	
	function deleteReminder() {
		var model = $('#status-container-model').val(),
			foreign_key = $('#status-container-foreign-key').val(),
			params = '';
		params = params + 'model:'+ model + '/';
		params = params + 'foreign_key:'+ foreign_key + '/';
			
		$.ajax({
			url: myBaseUrl + "reminders/ajax_delete/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					$('div#reminder-display').html('');
				} else {
				}
				stopPageLoader();
			},
		});
	}
	
	$(document).on('change', '#touchbase-container #touch-base-type-select', function(event) {
		// Determine which model to update.
		var value = $(this).val(),
			calc_date = new Date(),
			today = new Date(),
			display_date = '',
			pass_date = '';
		
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
				pass_date = (Number(calc_date.getMonth( )) + 1) + '_' + calc_date.getDate( ) + '_' + calc_date.getFullYear( );
			}
			
			$("#touchbase-datepicker.datepicker").val(display_date);
			if(value == 'none') {
				deleteReminder();
			} else {
				updateReminder(pass_date);
			}
		}
		// Reset the options
		$('#touchbase-container #touch-base-type-select').val($("#touchbase-container #touch-base-type-select option:first").val())
		return false;
	});
});