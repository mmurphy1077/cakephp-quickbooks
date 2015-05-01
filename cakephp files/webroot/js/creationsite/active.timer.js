	function createTimerRecord() {
		// Build a parameter list to lend to the server
		var id = $('#OrderTimeId').val(),
			worker_id = $('#OrderTimeWorkerId').val(),
			time = formatTime($('#time_start_work').val()),
			params = '';
		params = params + 'foreign_key:'+ id + '/';
		params = params + 'model:OrderTime/';
		params = params + 'time:'+ time['db'] + '/';
		params = params + 'worker_id:'+ worker_id + '/';
		
		$.ajax({
			url: myBaseUrl + "timers/ajax_add/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				stopPageLoader();
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						message = obj.message,
						error = obj.error,
						id = obj.id,
						time_start = obj.time_start;
					
					if(success) {
						$('#active-timer-container').removeClass('hide');
						constructActiveTimerElementHtml(id, time_start);
					} else {
						
					}
					return success;
					
				} else {
					return false;
				}
			},
		});
	}	
	
	function constructActiveTimerElementHtml(id, time_start) {
		/* Information needed...
		 * Who, Customer, JobName, start time.
		 */
		var start_time = formatTime($('#time_start_work').val()),
			job_name = $('#OrderCustomerName').val(),
			who = $('#OrderTimeWorkerName').val(),
			link = $('#active-timer-item-link').attr('href');
		
		$('#active-timer-item-when').append(start_time['hmp']);
		//$('#active-timer-item-who').append(who);
		$('#active-timer-item-order-name').append(who + ' at ' + job_name);
		$('#new-timer-element').data('date', time_start);
		$('#active-timer-item-link').attr('href', link + '/' + $('#OrderTimeId').val());
		
		// Reset the timer
		$('#new-timer-element').TimeCircles().rebuild().restart();
		$('#new-timer-element-container').removeClass('hide');
		$('#new-timer-element-container').attr('id', 'timer-element-container-' + id);
	}
		
	function deleteTimerRecord(id, model) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'foreign_key:'+ id + '/';
		params = params + 'model:'+ model + '/';
		$.ajax({
			url: myBaseUrl + "timers/ajax_delete/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						message = obj.message,
						error = obj.error;
					
					if(success) {
						// Remove the timer from the active timer container
						checkAndRemoveTimer(id);
					} else {
						//alert('not deleted');
					}
					return true;
					
				} else {
					return false;
				}
			},
		});
	}
	
	function buildConfirmMessage(timer_id) {
		// Access the appropriate databank to get the required data.
		var start_time = deconstructSelectedTime($('#ActiveTimerStartTime').val()),
			end_time = calculateCurrentTime(),
			order_name = $('#ActiveTimerOrderName').val(),
			worker_name = $('#ActiveTimerWorkerName').val(),
			start_time_display = formatTime(start_time),
			end_time_display = formatTime(end_time);
			timeDiff = calculateStartEndDifference(start_time, end_time);
		
		msg = '<b>Log Time</b><br />' + 
				'Do you want to log ' +  timeDiff + ' hours for <b>' + worker_name + '</b> towards Job: ' + order_name + '?&nbsp;&nbsp;&nbsp;' + 
				'<span class="small">(The timer recorded from ' + start_time_display['hmp'] +  ' to ' + end_time_display['hmp'] + '.)</span>';
		return msg;
	}
	
	function jumpToOrderTimeDetails() {
		var id = $('#log-time-confirm-box-id').val();
		if(id.length > 0) {
			startPageLoader();
			window.location.href = myBaseUrl + "order_times/edit/" + id + '/';
		}
		return false;
	}
	
	function checkAndRemoveTimer(id) {
		$('#timer-element-container-' + id).remove();
		// Check if the user is on the OrderTime detail page.
		if($('#OrderTimeId').length && $('#OrderTimeId').val() == id) {
			$('#timer-container').removeClass('active');
		}
	}
	
	function logOrderTimeFromActiveBar() {
		// Grab the time and the ordertime.id and the end time. (the time the link was selected);
		var id = $('#log-time-confirm-box-id').val(),
			time = formatTime($('#log-time-confirm-box-time').val());

		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'id:'+ id + '/';
		params = params + 'end_work:'+ time['db'] + '/';
		
		$.ajax({
			url: myBaseUrl + "order_times/ajax_log_time_from_active_timer_bar/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				stopPageLoader();
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						message = obj.message,
						error = obj.error;
					
					if(success) {
						// Remove the timer from the active timer container
						checkAndRemoveTimer(id);
					} else {
						//alert('not deleted');
					}
					return true;
					
				} else {
					return false;
				}
			},
		});
		
		
		if(id.length > 0 && time.length > 0) {
			startPageLoader();
			window.location.href = myBaseUrl + "order_times/ajax_log_time_from_active_timer_bar/" + id + '/';
		}
		return false;
	}
		
$(document).ready(function(){

	/***********
	 * Active Timer scripts.
	 */
	$(document).on("click", '.active-timer-delete', function() {
		// Obtain the id from the timer-element-container element.
		var id = $(this).closest('div.timer-element-container').attr('id').replace('timer-element-container-', ''),
			msg = 'Are you sure you want to cancel this timer? (Time will not be logged)',
			r=confirm(msg);
		
        if (r==true) {
        	result = deleteTimerRecord(id, 'OrderTime');
        }
        return false;
	});
	
	$("#log-time-confirm-box").dialog({
		autoOpen: false,
		resizable: false,
		height:140,
		width:400,
		modal: true,
		buttons: {
			 "Log Time": function() {
				 logOrderTimeFromActiveBar();
				 $( this ).dialog( "close" );
			 },
			 "Jump to Details": function() {
				 jumpToOrderTimeDetails();
			 },
			 Cancel: function() {
				 $('#log-time-confirm-box-id').val('');
				 $('#log-time-confirm-box-time').val('');
				 $( this ).dialog( "close" );
			 }
		},
		create: function( event, ui ) {
			$(".ui-widget-header").hide();
		},
		open: function( event, ui ) {}
	});
	
	$(".log-active-timer").click(function() {
		var id = $(this).closest('div.timer-element-container').attr('id').replace('timer-element-container-', ''),
			msg = buildConfirmMessage(id);
		$('#log-time-confirm-box').html(msg);
		$('#log-time-confirm-box').dialog( "open" );
		$('#log-time-confirm-box-id').val(id);
		$('#log-time-confirm-box-time').val(calculateCurrentTime());
		return false;
	});
	
	$(document).on("click", '#active-timer-toggle-container', function() {
		var i = 0;
		$('.timer-element-container').each( function() {
			if($(this).attr('id') == 'new-timer-element-container'){
				if(!$(this).hasClass('hide')) {
					$(this).toggle();
					i = i + 1;
				}
			} else {
				$(this).toggle();
				i = i + 1;
			}
		});
		$('#active-timer-toggle-container #container-closed').html(i);
		$('#active-timer-toggle-container #container-closed').toggle();
		$('#active-timer-toggle-container #container-open').toggle();
        return false;
	});
});