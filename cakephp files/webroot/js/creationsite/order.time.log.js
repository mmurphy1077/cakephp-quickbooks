/*
function setStartTime(start_time, type) {
	if(start_time.length > 0) {
		var parsed_start_time = formatTime(start_time);
		$('select#minute_start_' + type + ' option').attr('selected', false);
		$('select#post_meridiem_start_' + type + ' option').attr('selected', false);
		$('input#hour_start_' + type).val(parsed_start_time['hour']);
		$('select#minute_start_' + type + ' option[value = '+parseInt(parsed_start_time['min'])+ ']').attr('selected', true);
		$('select#post_meridiem_start_' + type + ' option[value = '+parsed_start_time['post']+ ']').attr('selected', true);
		$('input#time_start_' + type).val(start_time);
	} else {
		clearStartTime(type);
	}
}
*/
function setEndTime(time, timeLapse) {
	// schedule_id was provided, thus the time_end can be pulled from the data_bank
	if(time > 0) {
		var time_format = formatTime(time);
			time_format['lapse'] = timeLapse;
			
		$('input#hour_end_work').val(time_format['hour']);
		$('input#minute_end_work').val(time_format['min']);
		$('select#post_meridiem_end_work').val(time_format['post']);
		$('input#time_end_work').val(time);
	} else {
		// Clear Value
		clearEndTime('work');
	}
}

function constructStartTime(hour, minute, pm) {
	var start_time = (parseInt(hour*100)) + parseInt(minute);
	if(pm == 'pm') {
		if(hour < 12){
			start_time = parseInt(start_time) + 1200;
		}
	} else {
		if(hour == 12){
			start_time = parseInt(start_time) + 1200;
		}
	}

	return start_time;
}

function recalculate_session_time() {
	// Grab the difference in work time.
	var total = 0,
		work_start = 0,
		work_end = 0;

	// Obtain the time difference
	if($('#time_start_work').val().length > 0) {
		work_start = parseInt($('#time_start_work').val());
	}
	if($('#time_end_work').val().length > 0) {
		work_end = parseInt($('#time_end_work').val());
	}
	if(work_end > 0) { 
		total = calculateStartEndDifference(work_start, work_end);
		if(total < 0) {
			total = 0;
		}
		// Obtain current session time.
		var current = $('#session-time').val(),
			reg = 0,
			ot = 0,
			dt = 0;
		if($('#time_input_reg').val().length > 0) {
			reg = parseFloat($('#time_input_reg').val());
		}
		if($('#time_input_ot').val().length > 0) {
			ot = parseFloat($('#time_input_ot').val());
		}
		if($('#time_input_dt').val().length > 0) {
			dt = parseFloat($('#time_input_dt').val());
		}
		if(Number(current) > Number(total)) {
			// time has been decreased - Remove time from DT, then OT, then reg time.
			var diff = Math.abs(total - current);
			if(diff > dt) {
				// Set input to zero
				$('#time_input_dt').val(0);
				// deduct the input from the diff.
				diff = diff - dt;
			} else {
				// deduct the diff from the input
				$('#time_input_dt').val(dt - diff);
				diff = 0;
			}
	
			if(diff > ot) {
				// Set input to zero
				$('#time_input_ot').val(0);
				// deduct the input from the diff.
				diff = diff - ot;
			} else {
				// deduct the diff from the input
				$('#time_input_ot').val(ot - diff);
				diff = 0;
			}
	
			if(diff > reg) {
				// Set input to zero
				$('#time_input_reg').val(0);
				// deduct the input from the diff.
				diff = diff - reg;
			} else {
				// deduct the diff from the input
				$('#time_input_reg').val(reg - diff);
				diff = 0;
			}
		} else {
			// time has been increased - add difference to the reg time.
			$('#time_input_reg').val(total - (ot + dt));
		}
		$('#session-time').val(total);
	} else {
		// clear Adjust fields
		$('#time_input_reg').val(0);
		$('#time_input_ot').val(0);
		$('#time_input_dt').val(0);
	}
}

/*
function constructTimeList(start, stop, insertTime) {
	insertTime = insertTime || null;
	var hh = parseInt(start/100),
		m = start%100,
		time_inc = 0,
		str = '',
		minute_round = 0,
		minute_diff = 0,
		i = 0,
		time = start,
		marked_time_str = '',
		marked_time_included = false;
	
	// Round the minute up to the nearest quarter hour (0, 15, 30, 45)
	if(m > 45) {
		minute_round = 0;
		minute_diff = 60 - m;
		hh = hh + 1;
	} else if(m > 30) {
		minute_round = 45;
		minute_diff = 45 - m;
	} else if(m > 15) {
		minute_round = 30;
		minute_diff = 30 - m;
	} else if(m > 0) {
		minute_round = 15;
		minute_diff = 15 - m;
	}
	if(insertTime) {
		var marked_time = '' + insertTime['hour'] + insertTime['min'];
		time_inc_display = 0;
		if(insertTime['lapse']) {
			time_inc_display = insertTime['lapse'];
			if(time_inc_display < 1) {
				time_inc_display = Math.floor(time_inc_display*60) + '.00 minutes';
			} else {
				time_inc_display = time_inc_display + ' hour(s)';
			}
		}
		marked_time_str = '<option value="' + marked_time + '" id="' + time + '" class="' + marked_time + ' ' + displayClass + '">' + insertTime['hmp'] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +  time_inc_display  + '</option>';
	} else {
		// No Time has been previously inserted... Insert a blank option.
		str = str + '<option value="" id="" class="default"></option>';
	}
	
	time_inc = minute_diff;
	while(parseInt(time) < parseInt(stop)) {
		time_inc = time_inc + 15;
		if((minute_round + 15) == 60) {
			hh = hh + 1;
			minute_round = 0;
		} else {
			minute_round = minute_round + 15;
		}

		//var displayClass = 'hide_quarter_slot';
		var displayClass = '';
		if((minute_round == 0 || minute_round == 30) && i < 20) {
			displayClass = '';
		}
		
		time = (hh*100)+minute_round;
		if (time_inc < 60) {
			time_inc_display = parseFloat(time_inc).toFixed(2) + ' minutes';
		} else {
			time_inc_display = parseFloat(time_inc/60).toFixed(2) + ' hour(s)';
		}
		
		if(marked_time_str.length && !marked_time_included && (marked_time < time)) {
			// If a mark_time string exists and has not been included and the marked time is less than the current time being inserted
			str = str + marked_time_str;
			marked_time_included = true;
		}
		
        // Include the time if it is less than the stop time
        if(parseInt(time) <= parseInt(stop)) {
        	display = formatTime(time)
            //str = str + '<tr id="' + time + '" class="' + time + ' ' + displayClass + '"><td class="first">&nbsp;&nbsp;&nbsp;</td><td>' + display['hmp'] + '</td><td class="time_increment">' +  time_inc_display  + '</td></tr>';
        	//str = str + '<option id="' + time + '" class="' + time + ' ' + displayClass + '"><span class="first">&nbsp;&nbsp;&nbsp;</span><span>' + display['hmp'] + '</span><span class="time_increment">' +  time_inc_display  + '</span></option>';
        	str = str + '<option value="' + time + '" id="' + time + '" class="' + time + ' ' + displayClass + '">' + display['hmp'] + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' +  time_inc_display  + '</option>';
        }
        i = i + 1;
	}
	
	return str;
}
*/

function clearStartTime(type) {
	$('#time_start_'+type).val(0);
	$('input#hour_start_' + type).val('');
	$('input#minute_start_' + type).val('');
	$('select#post_meridiem_start_' + type + ' option').attr('selected', false);
	$('select#post_meridiem_start_' + type + ' option[value = am]').attr('selected', true);
}

function disableEndTime(type) {
	$('input#hour_end_' + type).prop('disabled', true);
	$('#minute_end_' + type).prop('disabled', true);
	$('select#post_meridiem_end_' + type).prop('disabled', true);
}

function clearEndTime(type) {
	$('#time_end_' + type).val(0);
	$('input#hour_end_' + type).val('');
	$('#minute_end_' + type).val('00');
	$('select#post_meridiem_end_' + type + ' option').attr('selected', false);
	$('select#post_meridiem_end_' + type + ' option[value = am]').attr('selected', true);
}

function enableEndTime(type) {
	$('input#hour_end_' + type).prop('disabled', false);
	$('#minute_end_' + type).prop('disabled', false);
	$('select#post_meridiem_end_' + type).prop('disabled', false);
	$('select#post_meridiem_end_' + type + ' option').each(function() {
		$(this).prop('disabled', false);
	});
	if($('input#hour_end_' + type).val().length == 0) {
		clearEndTime(type);
	}
}

function start_time_change(type) {
	var hour = $('#hour_start_' + type).val();
	if(($('#hour_start_' + type).val() == 0) || ($('#hour_start_' + type).val().length == 0)) {
		hour = 0;
		clearStartTime(type);
		clearEndTime(type);
		disableEndTime(type);
	} else {
		var minute = $('#minute_start_' + type).val(),
			pm = $('#post_meridiem_start_' + type).val(),
			start_time = null;

		if(minute.length == 0) {
			minute = 0;
		}
		start_time = constructStartTime(hour, minute, pm);
		
		$('#time_start_'+type).val(start_time);
		// Update End time if it is empty. (List too)
		// Or if the End time is less than the start time. 
		// OR if the End time is greater than 1 hour
		/*
		if((!$('#time_end_' + type).length) || ($('#time_end_' + type).val().length == 0) || ($('#time_end_' + type).val() == 0)  || ($('#time_end_' + type).val() <= start_time) || (($('#time_end_' + type).val() - start_time) > 100)) {
			// CHANGE - Default to one hour ahead.
			populateEndTimeRoundNow();
		} 
		*/
		enableEndTime(type);
		
		//var list = constructTimeList(start_time, 2445);
		//$('#time-end-select-' + type).html(list);
		//$('#time_end_display_work').html(list);
	}
	// Recalculate the session time
    recalculate_session_time(); 
}

function end_time_change(type) {
	var hour = $('#hour_end_' + type).val();
	if(($('#hour_end_' + type).val() == 0) || ($('#hour_end_' + type).val().length == 0)) {
		hour = 0;
		clearEndTime(type);
	} else {
		var minute = $('#minute_end_' + type).val(),
			pm = $('#post_meridiem_end_' + type).val(),
			end_time = null;
		
		if(minute.length == 0) {
			minute = 0;
		}
		end_time = constructStartTime(hour, minute, pm);
		$('#time_end_'+type).val(end_time);
	}
	// Recalculate the session time
    recalculate_session_time(); 
}

function toggle_time_container() {
	// loop through all the rows.  Removing 
	$('tr.hide_quarter_slot').toggle();
	$('img#icon-arrow-down').toggle();
	$('img#icon-arrow-up').toggle();
};

//**********************************
// TIMER BLOCK
function populateStartTime() {
	var dt = new Date(),
		hour24 = dt.getHours(),
		hour = hour24;
		minute = dt.getMinutes(),
		meridian = 'am';
	
	if(hour24 == 12) {
		meridian = 'pm';
	} else if(hour24 > 12) {
		meridian = 'pm';
		hour = hour24 - 12;
	}
	
	if(quarterlyIntervals) {
		minuteAdjusted = 0;
		if(minute >= 45) {
			minuteAdjusted = 45;
		} else if(minute >= 30) {
			minuteAdjusted = 30;
		} else if(minute >= 15) {
			minuteAdjusted = 15;
		} 
	} else {
		// No adjustment.  Use value entered.
		if(minute.toString().length == 1) {
			minuteAdjusted = 0 + minute.toString();
		} else {
			minuteAdjusted = minute;
		}
	}
	$('#hour_start_work').val(hour);
	$('#minute_start_work').val(minuteAdjusted);
	$('#post_meridiem_start_work').val(meridian);
	return true;
}

function populateEndTimeRoundNow(time, timeDiff) { 
	//var time = time || calculateEndTime();
	var time = time || calculateCurrentTime(),
		timeDiff = timeDiff || 0;
	setEndTime(time, timeDiff);
	return true;
}

function killTimer() {
	// Determine if the timer is even on.
	$('#timer-container').removeClass('active');
	
	// Ajax call to remove the timer record.
	// Get the OrderTime.id and the OrderTime.type
	var orderTimeId = $('#OrderTimeId').val(),
		// Call the deleteTimerRecord in the active.timer.js
		result = deleteTimerRecord(orderTimeId, 'OrderTime');
	if(result) {
		// Timer was successfully deleted.
		// Remove the timer form the index
		$('#timer-image-' + id).addClass('hide');
	}
	return result;
}

function retrieveUserPayRate(user_id) {
	var params = '';
	params = params + 'id:'+ user_id + '/';
	
	$.ajax({
		url: myBaseUrl + "users/ajax_retrieve_users_pay_rate/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			startPageLoader();
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var obj = jQuery.parseJSON(data.responseText),
					rate = obj.data,
					error = obj.error,
					success = obj.success;
					
				
				if($('#OrderTimeRateId').length) {
					$('#OrderTimeRateId').val(rate['rate_id']);
				}
				if($('#OrderTimeRate').length) {
					$('#OrderTimeRate').val(rate['rate']);
				}
				if($('#OrderTimeExpenseRate').length) {
					$('#OrderTimeExpenseRate').val(rate['exp_rate']);
				}
			} else {
				
			}
			stopPageLoader();
		},
	});
}

function validate(form_obj) {
	var valid = true,
		data = new Array(),
		error = new Array();
    return error; 
} 

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  { 
	obj = jQuery.parseJSON(responseText);
	var html = obj.html;
	var message = obj.message;
	var success = obj.success;
	var error = obj.error;

	// Append the html to the beginning of the call_container element.
	if(success > 0){
		
		
	} else {
		// Error
		$('#error-ajax-message-log').html(message);
		$('#error-ajax-message-log').fadeIn('fast').delay(2500).fadeOut('fast');
	}
	stopPageLoader();
	$('#page-loader').css('display', 'none');
} 

function activateStopSelectBox() {
	// Determine if a stop time exists... This would occur in edit mode of a of a time record that has been recorded with start and stop times
	var end = $('#time_end_work').val(),
		end_format = null,
		list = '';
	
	/*
	if(end.length) {
		end_format = formatTime(end);
		// Assumption... if there is an end time there is a total_time (session-time)
		end_format['lapse'] = Number($('#session-time').val()).toFixed(2);
		list = constructTimeList($('#time_start_work').val(), 2445, end_format);
	} else {
		list = constructTimeList($('#time_start_work').val(), 2445);
	}
	
	//$('#time-end-select-work').html(list);
	$('#time_end_display_work').html(list);
	$('#time_end_display_work').removeAttr('disabled');
	if(end.length) {
		$('#time_end_display_work').val(end);
	}
	*/
}

$(document).ready(function(){
	$("#OrderTimeDateSession.datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);
	    }
	});

	/***********
	*	Open the end time select box
	*	CHOOSE AN END TIME FOR THE SCHEDULE
	*/
	$(document).on("click", '.time_end_display', function() {
		// First make sure all displays are closed ... reset
		$('.time-end-select-container').css('display', 'none');
		
		// Clear any previous selected times.
		$('table.time-end-select tr').each(function() {
			$(this).children('td.first').html('&nbsp;&nbsp;&nbsp;');
			$(this).css('font-weight', 'normal');
		});
		// Mark the current time within the selection box.
		// Obtain the end time from the associated hidden time_end container.
		var end_time = $(this).next('.time_end').val(),
			type = $(this).attr('id').substring(17),
			select_container = $('div#time-end-select-container-' + type);
		
		if(end_time) {
			select_container.find('table.time-end-select tbody tr.'+end_time).css('font-weight', 'bold');
			select_container.find('table.time-end-select tbody tr.'+end_time + ' td.first').html('&rarr;');
			select_container.find('table.time-end-select tbody tr.'+end_time).css('font-weight', 'bold');
			select_container.find('table.time-end-select tbody tr.'+end_time).removeClass('hide_quarter_slot');
		}
		// initialize arrows and disable the non-important times.
		$('img#icon-arrow-down').css('display', 'inline-block');
		$('img#icon-arrow-up').css('display', 'none');
		$('tr.hide_quarter_slot').css('display', 'none');
		$('div.time-end-select-container').css('height', '240px');
		
		select_container.css('display', 'block');
	});

	/***********
	*	Select the "show more" arrows in the End Time select box.
	*/
	$('img#icon-arrow-down').bind('click', function() {
		// lengthen height
		$('div.time-end-select-container').css('height', '300px');
		toggle_time_container();
	});
	$('img#icon-arrow-up').bind('click', function() {
		// Restore height
		$('div.time-end-select-container').css('height', '240px');
		toggle_time_container();
	});

	/***********
	*	A TIME IS SELECTED FROM THE END TIME
	*/
	$(document).on("change", "select#time_end_display_work", function() {
		var selected_date = formatTime($(this).val()),
			type = 'work';
		//$('#time_end_display_' + type).val(selected_date['hmp']);
		$('#time_end_' + type).val($(this).val());
		//$('#time-end-select-container-' + type).css('display', 'none');
		recalculate_session_time();
		killTimer();
	});
	
	/***********
	*	A COMPONENT THAT IS PART OF THE START TIME IS CHANGED (hour-start, minute-start, post_meridiem_start)
	*/
	/*
	$(document).on("change", "select.minute_start", function() {
		var type = $(this).attr('id').substring(13);
		start_time_change(type);
	});
	*/
	$(document).on("keyup", "input.minute_start", function() {
		var type = $(this).attr('id').replace('minute_start_', '');
		start_time_change(type);
	});
	$(document).on("change", "select.post_meridiem_start", function() {
		var type = $(this).attr('id').replace('post_meridiem_start_', '');
		start_time_change(type);
	});
	$(document).on("keyup", "input.hour_start", function() {
		var type = $(this).attr('id').replace('hour_start_', '');
		start_time_change(type);
	});
	
	$(document).on("keyup", "input.minute_end", function() {
		var type = $(this).attr('id').replace('minute_end_', '');
		end_time_change(type);
	});
	$(document).on("change", "select.post_meridiem_end", function() {
		var type = $(this).attr('id').replace('post_meridiem_end_', '');
		end_time_change(type);
	});
	$(document).on("change", "input.hour_end", function() {
		var type = $(this).attr('id').replace('hour_end_', '');
		end_time_change(type);
	});

	function setMargins() {
		var left = ($(window).width() - 930)/2;
		$('div#add-time-log-container').css('left', left);
	}
	
	/***********
	*	ADD TIME LOG LINK IS SELECTED
	*	Enable the add log box.  Default view to select a Schedue session
	*/
	$('#add_time_log').bind('click', function() {
		reset_form();
		init_schedule_session_navigation();
		setMargins();
		$('#add-time-log-container').css('display', 'block');
		return false;
	});

	/***********
	*	ADD COSTS LINK IS SELECTED
	*	Enable the add log box.  Default view to select a Materials tab
	*/
	$('#add_costs').bind('click', function() {
		reset_form();
		init_costs_navigation();
		setMargins();
		$('#add-time-log-container').css('display', 'block');
		return false;
	});
	
	$('#timer-container').on('click', function() {
		var status = 'start',
			start_hour = $('#hour_start_work').val();
		
		if($(this).hasClass('active')) {
			// Stopping timer
			// Confirm that the user wishes to Log the time selected.
			var start_time = $('#time_start_work').val(),
				//end_time = calculateEndTime(),
				end_time = calculateCurrentTime(),
				timeDiff = calculateStartEndDifference(start_time, end_time),
				start_time_display = formatTime(start_time),
				end_time_display = formatTime(end_time),
				msg = 'Log time: ' + start_time_display['hmp'] + ' to ' + end_time_display['hmp'] + ' (' + timeDiff + ' hours)?',
				r=confirm(msg);
			
			enableEndTime('work');
	        if (r==true) {
	        	// Log the time... else the timer will just be stopped.
	        	// When yes is chosen... populate the end time with the current time and calculate the session time.
	        	populateEndTimeRoundNow(end_time, timeDiff);
	        	recalculate_session_time();
	        	
	        	// DO AN AJAX SUBMIT OF THE FORM 
	        	$("#OrderTimeAddForm").submit();
	        }
		
			status = 'stop';
			$(this).removeClass('active');
			killTimer();
		} else {
			// Determine if a value was supplied for the start time.
			if(start_hour.length == 0) {
				// Populate the start hour/minute with the current time
				populateStartTime();
			} 
			// Starting timer
			$(this).addClass('active');
			
			// Populate the end time and construct the values in the select box.
			start_time_change('work');
			disableEndTime('work');

			// Log timer record.
			createTimerRecord();
		}
		
		return false;
	});
	
	$('#worker-select').on('change', function() {
		$('#OrderTimeWorkerId').val($("#worker-select option:selected").val());
		$('#OrderTimeWorkerName').val($("#worker-select option:selected").text());
	});
	
	$('.post').on('click', function() {
		$('#submit-type').val('post');
		return true;
	});

	//prepare the form when the DOM is ready 
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
    $('#OrderTimeAddForm').submit(function() { 
    	/*
    	$('div#ajax_loader_log').css('display', 'block');
    	*/
    	// Clear all (previous) Validation Errors
		/*
    	$('div.error-message-log').each( function() {
			$(this).html('');
		});
		*/
    	if($('#submit-type').val() == 'post') {
    		// /com.business360/com.squires.business360/order_times/ajax_add/1
    		var action = myBaseUrl + "order_times/edit/" + $('#OrderTimeId').val() + '/';
    		$("#OrderTimeAddForm").attr("action", action);
    	} else {
			// VALIDATE
		    var result = true,
		    	id = $(this).attr('id');
		    
		    //validate($(this));
	
			if(result) {
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
    	}
    }); 
    
    $(document).on("change", "#worker-select", function() {
		var user_id = $(this).val();
		
		// Use ajax to retrieve the rate of the selected user
		retrieveUserPayRate(user_id);
    	return false;
    });
    
    $(document).on("change", "#OrderTimeRateId", function() {
    	var rate_id = $(this).val();
    	
    	if(rate_id) {
    		rate = $('div#rate-data-bank div#rate-container-' + rate_id + ' input#rate').val();
    	} else {
    		// grab "empty" databank value.
    		rate = $('div#rate-data-bank div#empty input#rate').val();
    	}
    	$('#OrderTimeRate').val(rate);
    	return false;
    });
	
	// Initialize Page
	// Check if a value exists within the 
	if($('#time_start_work').val().length && !$('#time_start_work').prop('disabled')) {
		// Determine if the Start input has been disabled
		activateStopSelectBox();
	}
});
