$(document).ready(function () {
    var schedule_display_placement = 'top',
    	ajax_calls_complete = 0,
    	ajax_call_schedules_detail_complete = 0,
    	ajax_call_order_detail_complete = 0,
    	ajax_call_order_schedule_status_complete = 0,
    	ajax_call_employees_complete = 0,
    	ajax_call_job_types_complete = 0,
    	deviceDisplay = $('input#device_display').val(),
    	defaultOrderViewBy = 'onscreen';

	/************
	 * Access the server to obtain the schedule data bank information
	 */
	startLoaderSchedule();
	$.getJSON( myBaseUrl + "schedules/ajax_schedule_details", function(data) {
		jQuery.data( document.body, "schedules_detail", data);
		ajax_call_schedules_detail_complete = 1;
		checkForPageLoadCompletion();
	});
	/*
	$.getJSON( myBaseUrl + "schedules/ajax_schedule_list_for_current_view", function( data ) {
		jQuery.data( document.body, "schedules_list_for_current_view", data);	
	});
	*/
	
	var link = 'schedules/ajax_order_details';
	if(deviceDisplay == 'mobile') {
		// Check the selected_order input to an order_id
		var order_id = $('input#selected_order').val();
		if(order_id.length) {
			link = 'schedules/ajax_order_details/' + order_id;
		}
	}
	$.getJSON( myBaseUrl + link, function(data) {
        jQuery.data( document.body, "orders_detail", data);	
        ajax_call_order_detail_complete = 1;
        constructOrderList('request_date', defaultOrderViewBy);
        checkForPageLoadCompletion();
	});
	
	
	$.getJSON( myBaseUrl + "schedules/ajax_order_schedule_status", function(data) {
        jQuery.data( document.body, "orders_schedule_status", data);	
		ajax_call_order_schedule_status_complete = 1;
		checkForPageLoadCompletion();
	});
	$.getJSON( myBaseUrl + "schedules/ajax_employees", function(data) {
		jQuery.data( document.body, "employees", data);
		ajax_call_employees_complete = 1;
		checkForPageLoadCompletion();
	});
	$.getJSON( myBaseUrl + "schedules/ajax_job_types", function(data) {
		jQuery.data( document.body, "jobTypes", data);	
		ajax_call_job_types_complete = 1;
		checkForPageLoadCompletion();
	});
	//stopLoaderSchedule();
	/************
	 * END SERVER DATA GRAB ON PAGE INITIALIZE
	 */
	
	function checkForPageLoadCompletion() {
		if(ajax_call_schedules_detail_complete == 1 && ajax_call_order_detail_complete == 1 && ajax_call_order_schedule_status_complete == 1 && ajax_call_employees_complete == 1 && ajax_call_job_types_complete == 1) {
			startLoaderSchedule();
			ajax_calls_complete == 1;
			
			// Build the Schedule Table
			buildScheduleTable();
			stopLoaderSchedule();
			checkForActiveOrder();
		}
	}
    
	if(deviceDisplay == 'standard') {
		// On mobile devices... not icluding resize functions
		$("#schedule_container").colResizable({
		    liveDrag:true,
		    gripInnerHtml:"<div class='grip'></div>", 
		    draggingClass:"dragging",
		    minWidth:0,
		});  
	}
	
	$('#toggle-schedule-columns').on('click', function() {
		$('#schedule_container_orders').toggle();
        
       if($('#schedule_container_orders').css('display') == 'none') {
           $(this).css('background-image', 'url(img/icon-arrow-left.png)');
       } else {
           $(this).css('background-image', 'url(img/icon-arrow-right.png)');
       }
		return true;
	});
	
    $(window).scroll(function(e) { 
    	var deviceDisplay = $('input#device_display').val();
    	
    	// Only allow for sticky elements on standard view devices... not mobile devices.
    	if(deviceDisplay == 'standard') {
	        if($(this).scrollTop() >= $('#schedule-table-container').offset().top) {
	            $('#schedule_container_orders').addClass('fix_schedule');
	            $('#toggle-schedule-columns').addClass('fix_schedule');
	        } else {
	            $('#schedule_container_orders').removeClass('fix_schedule');
	            $('#toggle-schedule-columns').removeClass('fix_schedule');
	        }
    	}
    });
	
	/*************
	 * Miscellanious functions
	 */
	function startLoaderOrder() {
		$('#order-loader').css('display', 'block');
	}
	function stopLoaderOrder() {
		$('#order-loader').css('display', 'none');
		$('#page-loader').css('display', 'none');
	}
	function startLoaderSchedule() {
		$('#schedule-loader').css('display', 'block');
	}
	function stopLoaderSchedule() {
		$('#schedule-loader').css('display', 'none');
	}
	function startLoaderScheduleSummary() {
		$('#schedule-summary-loader').css('display', 'block');
	}
	function stopLoaderScheduleSummary() {
		$('#schedule-summary-loader').css('display', 'none');
	}
	function convert_status_to_class(status) {
		var result = 'unscheduled';
        switch(Number(status)){
			case 40:
				 result = 'in-process';
            break;
			case 50:
                result = 'work_complete';
            break;
			case 100:
                result = 'closed';
            break;
			case 1:
			case 30:
			default:
			  result = 'open';
		}
        return result;
	}
	// Re-Initialize the cluetips
	function initializeClueTips() {
		$('.cluetip-schedule-order-desc').cluetip({
			width: '500px',
			local: true,
			showTitle: false,
			topOffset: 5,
			cursor: 'pointer',
		});
		
		$('.sched').cluetip({
			width: '150px',
			local: true,
			showTitle: false,
			topOffset: 5,
			cursor: 'pointer',
			activation: 'click',
			sticky: true,
			cluetipClass: 'schedule',
			dropShadow: false,
			positionBy: 'mouse',
			mouseOutClose: true,
			closePosition: 'title',
			onShow:  function(ct, ci) {
				//var id = this.id;
				//var params = id.split("|");
				// Obtain the current status and model/foreign_key values.
				//openOrderScheduleStatusContainer(params[0], params[1], params[2]);
			}
		});
	}
	
	// Re-Initialize the datepickers
	function initializeDatePickers() {
		var display_date_from = $('#datepicker_schedule_display_from').html(),
            display_date_to = $('#datepicker_schedule_display_to').html();
		$("div.schedule-container #datepicker_schedule_from").datepicker({
			defaultDate: display_date_from,
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			//maxDate: display_date_to,
			onSelect: function(selectedDate) {
				var	instance = $( this ).data("datepicker"),
                    date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings ),
                    selected_date = $.datepicker.formatDate('mm/dd/yy', $(this).datepicker('getDate')),
                    option = "minDate";
				$('#datepicker_schedule_display_from').html(selected_date);
				$('div.schedule-container input#datepicker_schedule_from').val(selected_date);
				$('div.schedule-container #datepicker_schedule_to').datepicker("option", option, date);
				
				// Verify that the dates/times entered are ok.
				adjustEndTimesPerStartAndDuration();
			}
		}).attr('readonly','readonly');
        
		$( "div#master-schedule-edit-content #datepicker_schedule_to" ).datepicker({
			defaultDate: display_date_to,
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			minDate: display_date_from,
			onSelect: function( selectedDate ) {
				var	instance = $( this ).data( "datepicker" );
				var	date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				var selected_date = $.datepicker.formatDate('mm/dd/yy', $(this).datepicker('getDate'));
				//var option = "maxDate";

				// Display Date
				$('#datepicker_schedule_display_to').html(selected_date);
                $('div.schedule-container input#datepicker_schedule_to').val(selected_date);
				//$('div.schedule-container #datepicker_schedule_from').datepicker("option", option, date);  Turn off the max date.. allow user to select what they want.

				// Verify that the dates/times entered are ok.
				checkScheduleTimes();
			}
		}).attr('readonly','readonly');
		
		// Update the hidden values.
		$('div.schedule-container input#datepicker_schedule_from').val(display_date_from);
		$('div.schedule-container input#datepicker_schedule_to').val(display_date_to);
	}
    
    function initializeEndTimeSelect() {
        $('#time-end-select-container').jScrollPane({
            showArrows: true,
            autoReinitialise: true,
        });
    }
    
	function refreshOrderView(order_id, model, foreign_key) {
		var update_task_view = false;
		if(model == 'order_line_item') {
			// Before refreshing the item view... Determine if the line_item_task(s) were enabled or hidden
			var line_item_display = $('#line-item-button-' + foreign_key + '_toggle_display').css('display');
			buildItemsForOrder(order_id);
			$('#line-item-button-' + foreign_key + '_toggle_display').css('display', line_item_display);
		} else if(model == 'order_line_item_task') {
			buildItemsForOrder(order_id);
			// If the buildItemsForOrder function is being executed because an order_line_item_task's status was updated..., then 
			// the line_item_tasks must have been enabled.  The trick is to find the line_item when all we have is the line_item_task_id.
			
			var line_item_id = $('#order-line-item-task-' + foreign_key).parent('div.line-item-task-container').attr("id");
			$('#' + line_item_id).css('display', 'block');
		} else {
			// The user is viewing the orders in task view.
			buildTasksForOrder(order_id);
		}
		return true;
	}
	function turnScheduleOn(order_id) {
		// Grab the contents of the "#order-schedule-container"
		// But before... Clear all .schedule-container.  Only one at a time.
		$('.schedule-container').each( function() {
			var id = $(this).attr('id').replace('schedule-container-', '');
			turnScheduleOff(id);
		});
        
        $('#schedule_container_orders').css('display', 'block'); // Verify that the Orders Column is open
        switch (schedule_display_placement) {
            case 'inline' :
                $('#schedule-container-' + order_id).html($('#order-schedule-container').html());
                $('#schedule-container-' + order_id).css('display', 'block');
                $('#view-by-' + order_id).css('display', 'none');
                break;
            case 'top' :
                // Grab the order title html from the order-container-## container
                $('#master-schedule-edit-container #order-title').html($('#order-container-' + order_id + ' div.order-title').html());
                $('#master-schedule-edit-content').html($('#order-schedule-container').html());
                $('#master-schedule-edit-container').css('display', 'block');
                $('#master-schedule-edit-content').css('display', 'block');
                break;
        }
        /*
        if($('#yaxis_view_type').val() == 'employee'){
            toggleWhoViewTypeToEmployee();
        } else {
            toggleWhoViewTypeToJobType();
        }
        */
        toggleWhoViewTypeToEmployee();
		initializeClueTips();		// Re-Initialize the cluetips
		initializeDatePickers(); 	// Reinitialize Date pickers!
	}
	function turnScheduleOff(order_id) {
        switch (schedule_display_placement) {
            case 'inline' :
                $('#schedule-container-' + order_id).css('display', 'none');
                $('#schedule-container-' + order_id).html('');
                $('#view-by-' + order_id).css('display', 'block');
                break;
            case 'top' :
                scheduleMasterOff();
                break;
        }
	}
    function scheduleMasterOff() {
    	// Clear's the content of the master-schedule-edit-container element.
        $('#master-schedule-edit-content').html('');
        $('#master-schedule-edit-container #order-title').html('');
        $('#master-schedule-edit-container').css('display', 'none');
    }
	function constructTime24(hour, minute, pm) {
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
	function secondsTimeSpanToHMS(s) {
	    var h = Math.floor(s/3600); //Get whole hours
	    s -= h*3600;
	    var m = Math.floor(s/60); //Get remaining minutes
	    s -= m*60;
	    time = new Array();
	    time['hour'] = h
	    time['min'] = (m < 10 ? '0'+m : m); //zero padding on minutes 
	    time['sec'] = (s < 10 ? '0'+s : s); //zero padding on seconds
	    return time;
	    
	    h+":"+(m < 10 ? '0'+m : m)+":"+(s < 10 ? '0'+s : s); //zero padding on minutes and seconds
	}
	function deconstructSelectedDate(date) {
		if(!date) {
			return null;
		}
		// "date" will always be passed in the form 'yyyy-mm-dd'
	    var y = date.substring(0,4),
	    	m = date.substring(5,7),
	    	d = date.substring(8);

		result = new Array();
		result['year'] = y;
		result['month'] = m;
		result['day'] = d;
		result['raw'] = y+m+d;
	    return result['raw']; 
	}
	function formatDate(date) {
		if(!date) {
			return null;
		}
		// "date" will always be past in the form 'yyyymmdd'
	    var y = date.substring(0,4),
	    	m = date.substring(4,6),
	    	d = date.substring(6);

		result = new Array();
		result['year'] = y;
		result['month'] = m;
		result['day'] = d;
		result['raw'] = date;
		result['formated'] = m + '/' + d + '/' + y; 
	    return result;
	}
	function formatDateObject(date) {
		var result = ('0' + (date.getMonth()+1)).slice(-2) + '/' + ('0' + date.getDate()).slice(-2) + '/' + date.getFullYear();
	    return result;
	}
	function flattenDateObject(date) {
		var result = date.getFullYear() + ('0' + (date.getMonth()+1)).slice(-2) + ('0' + date.getDate()).slice(-2);
	    return result;
	}
	function setStartTime(start_time) {
		var parsed_start_time = formatTime(start_time);
		$('#order-schedule-container select.minute_start option').attr('selected', false);
		$('#order-schedule-container select.post_meridiem_start option').attr('selected', false);
		$('#order-schedule-container input.hour_start').val(parsed_start_time['hour']);
		$('#order-schedule-container select.minute_start option[value = '+parseInt(parsed_start_time['min'])+ ']').attr('selected', true);
		$('#order-schedule-container select.post_meridiem_start option[value = '+parsed_start_time['post']+ ']').attr('selected', true);
	}
	function clearStartTime() {
		$('select.minute_start option').attr('selected', false);
		$('select.post_meridiem_start option').attr('selected', false);
		$('input.hour_start').val('');
	}
	function setEndTime(time) {
		var time_format = formatTime(time);
		//$('#order-schedule-container #time_end_display').val(time_format['hmp']);
		//$('#order-schedule-container #time_end').val(time);
		
		$('#end-time-container input#hour_end').val(time_format['hour']);
        $('#end-time-container input#minute_end').val(time_format['min']);
        $('#end-time-container select#post_meridiem_end').val(time_format['post']);
	}
	function populateScheduleToDateTimeValues(start_date, end_date, start_time, estimated_time, include_weekends) {
		var days = new Array();
		var duration = estimated_time;
		var end_time = $('#workday_end').val();
		var day_count = 1;
		var list_start_time = start_time;
		var popup_start_duration = 0;
		$('div#order-schedule-container #datepicker_schedule_display_from').html(start_date);
		$('input#datepicker_schedule_from').val(start_date);
		$('div#order-schedule-container #datepicker_schedule_display_to').html(end_date);
		$('input#datepicker_schedule_to').val(end_date);
		
		if(!estimated_time || estimated_time == 0) {
			// If there is no estimated time... set the end date to the same day
			// And the end time to the start_time + one hour
			$('#datepicker_schedule_display_to').html(start_date);
			duration = ($('#workday_end').val() - start_time)/100;
		} else {
			days = buildDateTimeArray(start_date, start_time, duration, include_weekends);
			day_count = days.length - 1;
			end_time = days[day_count]['end_time'].toString();
			list_start_time = days[day_count]['start_time'].toString();
			popup_start_duration = days[day_count]['popup_start_duration'];
			$('div#order-schedule-container #datepicker_schedule_display_to').html(days[day_count]['date']);
		}
		constructDurationInput(duration);
		constructScheduleStartTime(start_time);
		constructScheduleEndTime(end_time);

		/*
		 * Generate a list of times the user can select from of the time_end value. 
		 * If the end date is the same as the start date... use the start_time, else use the start of the work day.
		 */ 
		if(day_count == 1) {
			var list = constructTimeList(list_start_time, $('#workday_end').val(), popup_start_duration, 0);
		} else {
			var list = constructTimeList($('#workday_start').val(), $('#workday_end').val(), popup_start_duration, 1);
		}
		$('#order-schedule-container table#time-end-select').html(list);
	}
	function getStartTime() {
		var hour = $('div.schedule-container #hour_start').val();
		var minute = $('div.schedule-container #minute_start').val();
		var pm = $('div.schedule-container #post_meridiem_start').val();
		var start_time = constructTime24(hour, minute, pm);
		return start_time;
	}
	function getEndTime() {
		var hour = $('div.schedule-container #hour_end').val();
		var minute = $('div.schedule-container #minute_end').val();
		var pm = $('div.schedule-container #post_meridiem_end').val();
		var end_time = constructTime24(hour, minute, pm);
		return end_time;
	}
	function convertHoursToTime(hours, removeExtraMinutes) {
		hours = hours - removeExtraMinutes;
		var breakDown = formatTime(hours),
			base_hour = breakDown['hour24'],
			min = 60*(breakDown['min']/100),
			new_min = Number(min) + Number(removeExtraMinutes),
			time_add = secondsTimeSpanToHMS(new_min*60),
			newTime = (Number(base_hour) + Number(time_add['hour'])) + '' + time_add['min'];
		
		return newTime;
	}
	function buildDateTimeArray(start_date, start_time, estimated_time, include_weekends) {
		var days = new Array(),
        	day_count = 1,
        	popup_start_duration = 0, // Stores the number of hours (duration) at the beginning of each day.
        	normalWorkDay = Number($('#workday_end').val()) - Number($('#workday_start').val()),
        	startDateObject = new Date(start_date),
        	counterDateObject = new Date(start_date);

		estimated_time = estimated_time * 100;
		while (estimated_time > 0) {
			days[day_count] = new Array();
			if(day_count == 1) {
				days[day_count]['start_time'] = Number(start_time);
				days[day_count]['date'] = start_date;
				//days[day_count]['hours'] = estimated_time;
				//days[day_count]['end_time'] = convertHoursToTime(Number(days[day_count]['start_time']) + Number(days[day_count]['hours']));
               //counterDateObject.setDate(startDateObject.getDate());
			} else {
				days[day_count]['start_time'] = Number($('#workday_start').val());
				//var target_date = new Date();
				//target_date.setDate(startDateObject.getDate()+(day_count - 1));
                
                // Check if the current counterDate is at Friday.  If so, determine if the schedule will accomodate the weekend
                var dayOfTheWeek = counterDateObject.getDay();
                if ((counterDateObject.getDay() == 5) && (include_weekends == 0)) {
                    //Skip the weekened
                    counterDateObject.setDate(counterDateObject.getDate() + 3);
                } if ((counterDateObject.getDay() == 6) && (include_weekends == 0)) {
                    //Skip the Sunday
                    counterDateObject.setDate(counterDateObject.getDate() + 2);
                } else {
                    counterDateObject.setDate(counterDateObject.getDate() + 1);
                }
				//days[day_count]['date'] = formatDateObject(target_date);
                days[day_count]['date'] = formatDateObject(counterDateObject);
            }
			days[day_count]['popup_start_duration'] = popup_start_duration;
			popup_start_duration = popup_start_duration + (normalWorkDay); // Adjust popup duration for the next time
			
			var hoursInDay = Number($('#workday_end').val()) - Number(days[day_count]['start_time']),
				st = formatTime(days[day_count]['start_time']);
			if(estimated_time > hoursInDay) {
				days[day_count]['hours'] = hoursInDay;
				estimated_time =  Number(estimated_time) - Number(hoursInDay);
			} else {
				days[day_count]['hours'] = estimated_time;
				estimated_time = 0;
			}
			days[day_count]['end_time'] = convertHoursToTime(Number(days[day_count]['start_time']) + Number(days[day_count]['hours']), st['min']);
			day_count = day_count + 1;
		}
		return days;
	}
    
	function buildDateTimeArrayFromTime(start_date, start_time, end_date, end_time, include_weekends) {
		var diff = new Date(end_date - start_date),
            days = (diff/1000/60/60/24) + 1,
            normalWorkDay = Number($('#workday_end').val()) - Number($('#workday_start').val()),
            day_duration = 0,
            day_count = 1,
            icount = 1;
            total_duration = 0,
            results = new Array(),
            day_start_time = 0,
            day_end_time = 0,
            counterDateObject = new Date(start_date),
            include_day = true;
            
		while (day_count <=  days) {
			if(day_count == 1) {
				// first day, duration is from start time to the end of day
				day_end_time = $('#workday_end').val();
				day_start_time = start_time;
                include_day = true;
			} else {
				if(day_count == days) {
					// last day, duration is from start of day to the end time
					day_end_time = end_time;
					day_start_time = $('#workday_start').val();
                    include_day = true;
				} else {
                    var include_day = true,
                        dayOfTheWeek = counterDateObject.getDay(),
                        isWeekend = (dayOfTheWeek == 6) || (dayOfTheWeek == 0);
                    if(isWeekend && include_weekends == 0) {
                        include_day = false;
                    } 
                    day_end_time = $('#workday_end').val();
                    day_start_time = $('#workday_start').val();
				}
			} 
            if(include_day) {
                day_duration = day_duration + (Number(day_end_time) - Number(day_start_time));

                var startDate = new Date(start_date); // convert to actual date
                var newDate = new Date(startDate.getFullYear(), startDate.getMonth(), startDate.getDate()+(day_count-1)); 
                results[icount] = new Array();
                results[icount]['duration'] = day_duration;
                results[icount]['date'] = formatDateObject(newDate);
                results[icount]['start_time'] = day_start_time.toString();
                results[icount]['end_time'] = day_end_time.toString();
                icount = icount + 1;
            }
            counterDateObject.setDate(counterDateObject.getDate() + 1);
 			day_count = day_count + 1;
		}
		return results;
	}
	/**
	 * Compare the to and from date/time vales to make sure that the 'to' does not come before the 'from'
	 */
	function checkScheduleTimes() {
		var fromDate = new Date($('#datepicker_schedule_display_from').html()),
            toDate = new Date($('#datepicker_schedule_display_to').html()),
            endTime = getEndTime(),
            startTime = getStartTime(),
            include_weekends = $('#include_weekends').val(); 
		
        if(toDate.getTime() == fromDate.getTime()) {
            // Compare the start and end times
			if(endTime <= startTime) {
                // Update the End Date and End Time to Start Time + 1 hour 
				days = buildDateTimeArray($('#datepicker_schedule_display_from').html(), startTime, 1, include_weekends);
				var last_day_index = days.length - 1;
				endTime = days[last_day_index]['end_time'].toString();
				
                $('#datepicker_schedule_display_to').html(days[last_day_index]['date']);
				$('div.schedule-container input#datepicker_schedule_to').val(days[last_day_index]['date']);
				setEndTime(endTime);
				//$('div.schedule-container #time_end_display').val(time_format['hmp']);
				//$('div.schedule-container #time_end').val(endTime);
			} 
		}
		
		/*
		 * Recalculate Duration and End Time List
		 * Duration
		 * What we know... StartDate and End Date, StartTime and EndTime
		 */
		// end - start returns difference in milliseconds 
		var diff = new Date(toDate - fromDate);
		var days = diff/1000/60/60/24;
		var duration = 0;
		var list = '';
		
		if(days == 0) {
			// Same Day... Duration is endTime - startTime
			duration = Number(endTime) - Number(startTime);
			//list = constructTimeList(startTime, $('#workday_end').val(), 0, 0);
		} else {
			// Spans multiple days
			var timeTable = buildDateTimeArrayFromTime(fromDate, startTime, toDate, endTime, include_weekends);
			duration = timeTable[timeTable.length-1]['duration'];
			//list = constructTimeList(timeTable[timeTable.length - 1]['start_time'], timeTable[timeTable.length - 1]['end_time'], timeTable[timeTable.length - 2]['duration'], 1);
            //list = constructTimeList(timeTable[timeTable.length - 1]['start_time'], $('#workday_end').val(), timeTable[timeTable.length - 2]['duration'], 1);
		}
		
		$('div.schedule-container #duration').val((duration/100).toFixed(2));
		//$('div.schedule-container table#time-end-select').html(list);
		return true;
	}
	
    function adjustEndTimesPerStartAndDuration() {		
        // Obtain the start_date and start_time.  Once these values are obtianed... Run the buildDateTimeArray(start_date, start_time, estimated_time) function.
		var start_date = $('div.schedule-container #datepicker_schedule_display_from').html(),
            start_time = getStartTime(),
            estimated_time = $('div#duration-container #duration').val(),
            days = new Array(),
            include_weekends = $('#include_weekends').val();  
        
		days = buildDateTimeArray(start_date, start_time, estimated_time, include_weekends);
        var last_day_index = days.length - 1,
        	end_time = days[last_day_index]['end_time'].toString();
        
        $('#datepicker_schedule_display_to').html(days[last_day_index]['date']);
        $('input#datepicker_schedule_to').val(days[last_day_index]['date']);
        setEndTime(end_time);
        validate();
      
        
        /*
        $('div.schedule-container #time_end_display').val(time_format['hmp']);
		$('div.schedule-container #time_end').val(end_time);
		
		// Update the popup time selector.
		if(last_day_index == 1) {
			var list = constructTimeList(start_time, $('#workday_end').val(), days[last_day_index]['popup_start_duration'], 0);
		} else {
			var list = constructTimeList($('#workday_start').val(), $('#workday_end').val(), days[last_day_index]['popup_start_duration'], 1);
		}
		$('div.schedule-container table#time-end-select').html(list);
		*/
        initializeDatePickers();
    }
    
    function adjustDuration() {
    	checkScheduleTimes();
    	//var start_time = getStartTime(),
    	//	end_time = getEndTime(),
    	//	diff = calculateStartEndDifference(start_time, end_time);
    	//$('input#duration').val(diff);
    	
    	// Validate Schedule Form
    	validate();
    }
    
    function validate() {
    	var valid = true;
    	if($('input#duration').val() <= 0) {
    		$('input#duration').addClass('redText');
    		valid = false;
    	} else {
    		$('input#duration').removeClass('redText');
    	}
    	
    	
		// Did not pass... do not allow submition.
		$('#master-schedule-edit-container input.submit').each( function() {
			$(this).prop( "disabled", false );
			if(!valid) {
				$(this).prop( "disabled", true );
			}
		})
    }
    
	function constructTimeList(start, stop, start_duration, include_start) {
		var hh = parseInt(start/100),
            m = start%100,
            time = start,
            time_inc = ((start_duration/100) * 60),
            time_inc_d = '',
            str = '',
            i = 0;
        
		if(include_start == 1) {
			time_inc = Number(time_inc) - 15;
			m = m - 15;
		}
		while(parseInt(time) < parseInt(2400)) {
			time_inc = Number(time_inc) + 15;
			if((m + 15) == 60) {
				hh = hh + 1;
				m = 0;
			} else {
				m = m + 15;
			}

			var displayClass = 'hide_quarter_slot';
			//if((m == 0 || m == 30) && i < 20) {
            if((time >= start) && (time <= stop) && (m == 0 || m == 30)) {
				displayClass = '';
			}
			
			time = (hh*100)+m;
			if (time_inc < 60) {
				time_inc_display = time_inc + ' minutes';
				time_inc_d = time_inc/60;
			} else {
				time_inc_display = time_inc/60 + ' hour(s)';
				time_inc_d = time_inc/60;
			}
			
            // Include the time if it is less than the stop time
            //if(parseInt(time) <= parseInt(stop)) {
            	display = formatTime(time.toString())
                str = str + '<tr id="' + time + '" class="' + time + ' ' + displayClass + '"><td class="first">&nbsp;&nbsp;&nbsp;</td><td>' + display['hmp'] + '</td><td class="time_increment">' +  time_inc_display + '<div id="duration" class="hide">' + time_inc_d + '</div></td></tr>';
            //}
            i = i + 1;
		}
        return str;
	}
    
    function constructScheduleTableRow(yAxisId, schedules_dedicated_row) {
        var html = $('#time-row-' + yAxisId + '-' + (schedules_dedicated_row - 1)).html();
        $('#stagging').html(html);
        $('#stagging td.time-y-axis-record').each(function() {
            $(this).html('<div>&nbsp;</div>');
        });
        html = $('#stagging').html();
        $('#stagging').html('');
        return html;
    }
	
	 
	/***********
	 * DATA FORMAT FUNCTIONS
	 */
	// Access the schedules_detail to build a multiDimensional array for the provided schedule_id.
	function buildScheduleArray(schedule_id) {
		var schedules = jQuery.data( document.body, "schedules_detail");
		var target_schedule = new Array();
		target_schedule['ScheduleLineItem'] = new Array();
		$.each(schedules, function(key, schedule) {
			if(schedule['schedule_id'] == schedule_id) {
				target_schedule['schedule_id'] = schedule['schedule_id'];
				target_schedule['schedule_status'] = schedule['schedule_status'];
				target_schedule['schedule_status_name'] = schedule['schedule_status_name'];
				target_schedule['order_id'] = schedule['order_id'];
				target_schedule['order_name'] = schedule['order_name'];
				target_schedule['date_session_start'] = schedule['date_session_start'];
				target_schedule['date_session_end'] = schedule['date_session_end'];
				target_schedule['time_start'] = schedule['time_start'];
				target_schedule['time_end'] = schedule['time_end'];
				target_schedule['duration_in_seconds'] = schedule['duration_in_seconds'];
				target_schedule['schedule_in_current_view'] = schedule['schedule_in_current_view'];
				target_schedule['schedule_type'] = schedule['schedule_type'];
				target_schedule['assigned_to_model'] = schedule['assigned_to_model'];
				target_schedule['assigned_to_foreign_key'] = schedule['assigned_to_foreign_key'];
				target_schedule['assigned_to_name'] = schedule['assigned_to_name'];
				target_schedule['created_by_name'] = schedule['created_by_name'];
				target_schedule['status_created'] = schedule['schedule_created'];
				
				// Check the schedule_item_id to see if a key already exist for this schedule... If one doesn't add an array.
				var index_line_item = schedule['schedule_item_id'];
				if(!(index_line_item in target_schedule['ScheduleLineItem'])) {
					target_schedule['ScheduleLineItem'][index_line_item] = new Array();
				} 
				target_schedule['ScheduleLineItem'][index_line_item]['schedule_item_id'] = schedule['schedule_item_id'];
				target_schedule['ScheduleLineItem'][index_line_item]['schedule_item_status'] = schedule['schedule_item_status'];
				target_schedule['ScheduleLineItem'][index_line_item]['order_line_item_id'] = schedule['order_line_item_id'];
				target_schedule['ScheduleLineItem'][index_line_item]['order_line_item_name'] = schedule['order_line_item_name'];
				target_schedule['ScheduleLineItem'][index_line_item]['order_line_item_desc'] = schedule['order_line_item_desc'];
				target_schedule['ScheduleLineItem'][index_line_item]['scheduled_item_includes_tasks'] = schedule['scheduled_item_includes_tasks'];
				
				// Check if the schedule_line_item has a task(s) associated with it.
				if(schedule['schedule_item_task_id']) {
					// Does the 'ScheduleLineItemTask' array exists?
					if(!('ScheduleLineItemTask' in target_schedule['ScheduleLineItem'][index_line_item])) {
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'] = new Array();
					}
					if(!(schedule['schedule_item_task_id'] in target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'])) {
						var index_task = schedule['schedule_item_task_id'];
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'][index_task] = new Array();
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'][index_task]['schedule_item_task_id'] = schedule['schedule_item_task_id'];
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'][index_task]['schedule_item_task_status'] = schedule['schedule_item_task_status'];
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'][index_task]['task_id'] = schedule['task_id'];
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'][index_task]['task_code'] = schedule['task_code'];
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'][index_task]['task_name'] = schedule['task_name'];
						target_schedule['ScheduleLineItem'][index_line_item]['ScheduleLineItemTask'][index_task]['task_desc'] = schedule['task_desc'];
					}
				}
			}
		});
		
		return target_schedule;
	}
    
    // Access the schedules_detail to build a multiDimensional array for the User's associated with the schedule
	function buildScheduleResourceArray(schedule_id) {
        var schedules = jQuery.data( document.body, "schedules_detail"),
            schedule_users = new Array(),
            results = new Array(),
            count = 0;
		
        $.each(schedules, function(key, schedule) {
			if(schedule['schedule_id'] == schedule_id) {
				schedule_users[schedule['assigned_to_foreign_key']] = new Array();
                schedule_users[schedule['assigned_to_foreign_key']]['id'] = schedule['assigned_to_foreign_key'];
                schedule_users[schedule['assigned_to_foreign_key']]['name'] = schedule['assigned_to_name'];
                schedule_users[schedule['assigned_to_foreign_key']]['job_type_id'] = schedule['assigned_to_job_type_id'];
                schedule_users[schedule['assigned_to_foreign_key']]['job_type_name'] = schedule['assigned_to_job_type'];
			}
		});
		
        // Clean up the array
        $.each(schedule_users, function(key, data) {
            if(schedule_users[key]) {
                results[count] = data;
                count = Number(count) + 1;
            }
		});
		return results;
	}
	
    function buildOrdersSummaryArray() {
        var orders = jQuery.data( document.body, "orders_detail"),
            data = new Array();
        if(orders) {
            $.each(orders, function(key, order) {
            	if(!(order['order_id'] in data)) { 
                    var request_complete_sort = order['date_request_complete'];
                    var request_complete = order['date_request_complete'];
                    if(!(order['date_request_complete'])) {
                        request_complete_sort = 'ZZZZZZZZZZZZ';
                    } else {
                        /// Format the date;
                        var view_date = formatDate(deconstructSelectedDate(order['date_request_complete']));
                        request_complete = view_date['month'] + '/' + view_date['day'] + '/' + view_date['year'];
                    }
                    // The Order does not have a spot in the data array... create one.
                    data[order['order_id']] = new Array();
                    data[order['order_id']]['order_id'] = order['order_id'];
                    data[order['order_id']]['name'] = order['name'];
                    data[order['order_id']]['customer_name'] = order['customer_name'];
                    data[order['order_id']]['job_site_address'] = order['job_site_address'];
                    data[order['order_id']]['status'] = order['status'];
                    data[order['order_id']]['request_date'] = request_complete;
                    data[order['order_id']]['request_date_sort'] = request_complete_sort;
                    data[order['order_id']]['scheduled_hours'] = Number(order['scheduled_hours']).toFixed(2);
                    data[order['order_id']]['estimated_hours'] = 0;
                    data[order['order_id']]['order_has_tasks'] = 0;
                    data[order['order_id']]['order_has_schedules_in_view'] = order['order_in_active_view'];
                    data[order['order_id']]['OrderLineItem'] = new Array();
                }

                // Check the order_line_item_id to see if a key already exist for this schedule... If one doesn't add an array.
                var index_line_item = order['order_line_item_id'];
                if(!(order['order_line_item_id'] in data[order['order_id']]['OrderLineItem'])) {
                    data[order['order_id']]['OrderLineItem'][index_line_item] = new Array();
                    data[order['order_id']]['OrderLineItem'][index_line_item]['estimated_hours'] = order['order_line_item_hours'];
                    //data[order['order_id']]['OrderLineItem'][index_line_item]['scheduled_hours'] = order['order_line_item_scheduled_hours'];

                    // Add Hours to the Order
                    //data[order['order_id']]['scheduled_hours'] = Number(data[order['order_id']]['scheduled_hours']) + Number(order['order_line_item_hours']);
                    data[order['order_id']]['estimated_hours'] = Number(data[order['order_id']]['estimated_hours']) + Number(order['order_line_item_hours']);
                }
                // Check if the order_line_item has a task(s) associated with it.
                if(order['order_line_item_task_id']) {
                    data[order['order_id']]['order_has_tasks'] = 1;
                }

            });
        }
        return data;
    }
    
	// Access the orders_detail to build a multiDimensional array for the provided order_id.
	function buildOrderArray(order_id) {
		var orders = jQuery.data( document.body, "orders_detail");
		var targeted_order = new Array();
		$.each(orders, function(key, order) {
			if(order['order_id'] == order_id) {
				if(!('order_id' in targeted_order)) {
					targeted_order['order_id'] = order['order_id'];
					targeted_order['sid'] = order['sid'];
					targeted_order['project_manager_id'] = order['project_manager_id'];
					targeted_order['address_id'] = order['address_id'];
					targeted_order['name'] = order['name'];
					targeted_order['customer_name'] = order['customer_name'];
					targeted_order['contact_name'] = order['contact_name'];
					targeted_order['contact_phone'] = order['contact_phone'];
					targeted_order['contact_email'] = order['contact_email'];
					targeted_order['description'] = order['description'];
					targeted_order['price_total'] = order['price_total'];
					targeted_order['price_nte'] = order['price_nte'];
					targeted_order['date_request_complete'] = order['date_request_complete'];
					targeted_order['status'] = order['status'];
					targeted_order['status_contract_signed'] = order['status_contract_signed'];
					targeted_order['status_deposit_received'] = order['status_deposit_received'];
					targeted_order['est_lead_time'] = order['est_lead_time'];
					targeted_order['project_manager'] = order['project_manager'];
					targeted_order['job_site_address'] = order['job_site_address'];
					targeted_order['lat'] = order['lat'];
					targeted_order['lng'] = order['lng'];
					
					targeted_order['OrderLineItem'] = new Array();
				}	
				// Check the order_line_item_id to see if a key already exist for this schedule... If one doesn't add an array.
				var index_line_item = order['order_line_item_id'];
				if(!(order['order_line_item_id'] in targeted_order['OrderLineItem'])) {
					targeted_order['OrderLineItem'][index_line_item] = new Array();
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_id'] = order['order_line_item_id'];
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_name'] = order['order_line_item_name'];
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_description'] = order['order_line_item_description'];
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_hours'] = order['order_line_item_hours'];
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_total'] = order['order_line_item_total'];
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_status'] = order['order_line_item_status'];
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_scheduled_hours'] = order['order_line_item_scheduled_hours'];
					targeted_order['OrderLineItem'][index_line_item]['order_line_item_scheduling_complete'] = order['order_line_item_scheduling_complete'];
					targeted_order['OrderLineItem'][index_line_item]['selected'] = 0;
					targeted_order['OrderLineItem'][index_line_item]['expand'] = 0;
					targeted_order['OrderLineItem'][index_line_item]['visible'] = 0;
				}
					// Check if the order_line_item has a task(s) associated with it.
				if('order_line_item_task_id' in order && order['order_line_item_task_id']) {
                    // Does the 'OrderLineItemTask' array exists?
					if(!('OrderLineItemTask' in targeted_order['OrderLineItem'][index_line_item])) {
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'] = new Array();
					}
					if(!(order['order_line_item_task_id'] in targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'])) {
						var index_task = order['order_line_item_task_id'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task] = new Array();
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_id'] = order['order_line_item_task_id'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_code'] = order['order_line_item_task_code'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_name'] = order['order_line_item_task_name'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_description'] = order['order_line_item_task_description'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_hours'] = order['order_line_item_task_hours'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_total'] = order['order_line_item_task_total'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_status'] = order['order_line_item_task_status'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_scheduled_hours'] = order['order_line_item_task_scheduled_hours'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['order_line_item_task_scheduling_complete'] = order['order_line_item_task_scheduling_complete'];
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['selected'] = 0;
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['expand'] = 0;
						targeted_order['OrderLineItem'][index_line_item]['OrderLineItemTask'][index_task]['visible'] = 0;
					}
				}
				
			}
		});
		
		return targeted_order;
	}
	
	/************
	 * AJAX function
	 * Functions that require data to be sent to the server. 
	 */
	function ajaxUpdateDataStoreForOrder(order_id, model, foreign_key) {
		var scheduel_detail_complete = false;
		var order_detail_complete = false;
		var order_schedule_status_complete = false;
		$.getJSON( myBaseUrl + "schedules/ajax_order_schedule_status/" + order_id, function( data ) {
			// Upon receiving the upated data for the orders_schedule_status, grab the array from the JQuery.data,
			// Update the array, and re-store the values.
			var result = jQuery.data( document.body, "orders_schedule_status");
			
			// Loop through the returned data
            if(data) {
                $.each(data, function(key, element) {
                    result[key] = element;
                });
            }
			jQuery.data(document.body, "orders_schedule_status", result);
			
			/* TEST FOR ASYNC COMPLETION */
			order_schedule_status_complete = true;
			if(scheduel_detail_complete && order_detail_complete && order_schedule_status_complete) {
                if(foreign_key && model) {
				    refreshOrderView(order_id, model, foreign_key);
                } else {
                    // Initialize the order
                    initializeOrder(order_id);
                }
			}
		})
		$.getJSON( myBaseUrl + "schedules/ajax_schedule_details/" + order_id, function( data ) {
			var result = jQuery.data(document.body, "schedules_detail");
			
			// Loop through the returned data
			if(data) {
                $.each(data, function(key, element) {
                    result[key] = element;
                });
            }
			jQuery.data( document.body, "schedules_detail", result);
			
			/* TEST FOR ASYNC COMPLETION */
			scheduel_detail_complete = true;
			if(scheduel_detail_complete && order_detail_complete && order_schedule_status_complete) {
				if(foreign_key && model) {
				    refreshOrderView(order_id, model, foreign_key);
                } else {
                    // Initialize the order
                    initializeOrder(order_id);
                }
			}
		});
		$.getJSON( myBaseUrl + "schedules/ajax_order_details/" + order_id, function( data ) {
			var result = jQuery.data( document.body, "orders_detail");
			
			// Loop through the returned data
			if(data) {
                $.each(data, function(key, element) {
                    result[key] = element;
                });
            }
			jQuery.data( document.body, "orders_detail", result);
			
			/* TEST FOR ASYNC COMPLETION */
			order_detail_complete = true;
			if(scheduel_detail_complete && order_detail_complete && order_schedule_status_complete) {
				if(foreign_key && model) {
				    refreshOrderView(order_id, model, foreign_key);
                } else {
                    // Initialize the order
                    initializeOrder(order_id);
                }
			}
		});
		return true;
	}
    function ajaxUpdateDataStoreForSchedule(schedule_id, order_id) {
		var scheduel_detail_complete = false;
		var order_detail_complete = false;
		var order_schedule_status_complete = false;
		$.getJSON( myBaseUrl + "schedules/ajax_order_schedule_status/" + order_id, function( data ) {
			// Upon receiving the upated data for the orders_schedule_status, grab the array from the JQuery.data,
			// Update the array, and re-store the values.
			var result = jQuery.data( document.body, "orders_schedule_status");
			
			// Loop through the returned data
            if(data) {
                $.each(data, function(key, element) {
                    result[key] = element;
                });
            }
			jQuery.data(document.body, "orders_schedule_status", result);
			
			/* TEST FOR ASYNC COMPLETION */
			order_schedule_status_complete = true;
			if(scheduel_detail_complete && order_detail_complete && order_schedule_status_complete) {
                // Initialize the order
                initializeOrder(order_id);
			}
		})
		$.getJSON( myBaseUrl + "schedules/ajax_schedule_details/" + order_id, function( data ) {
			var result = jQuery.data( document.body, "schedules_detail");
			
			// Loop through the returned data
			if(data) {
                $.each(data, function(key, element) {
                    result[key] = element;
                });
            }
			jQuery.data( document.body, "schedules_detail", result);
			
			/* TEST FOR ASYNC COMPLETION */
			scheduel_detail_complete = true;
			if(scheduel_detail_complete && order_detail_complete && order_schedule_status_complete) {
				// Initialize the order
                initializeOrder(order_id);
			}
		});
		$.getJSON( myBaseUrl + "schedules/ajax_order_details/" + order_id, function( data ) {
			var result = jQuery.data( document.body, "orders_detail");
			
			// Loop through the returned data
			if(data) {
                $.each(data, function(key, element) {
                    result[key] = element;
                });
            }
			jQuery.data( document.body, "orders_detail", result);
			
			/* TEST FOR ASYNC COMPLETION */
			order_detail_complete = true;
			if(scheduel_detail_complete && order_detail_complete && order_schedule_status_complete) {
				// Initialize the order
                initializeOrder(order_id);
			}
		});
		return true;
	}
	function ajaxDeleteSchedule(order_id, schedule_id) {
        // Build a parameter list to lend to the server
		var params = '';
		params = params + 'order_id:'+ order_id + '/';
		params = params + 'schedule_id:'+ schedule_id + '/';
		
		var url_path = "schedules/ajax_delete_schedule/"
		$.ajax({
			url: myBaseUrl + url_path + params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startLoaderSchedule();
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
						/*
						 * Update... check the device.  If the user is using a mobile device and creating schedules through the order,
						 * then after a successful delete, the user should be redirected to the Order::schedules page
						 */
						var deviceDisplay = $('input#device_display').val();
						if(deviceDisplay == 'mobile') {
							var order_id = $("input#selected_order").val();
							if(order_id.length) {
								$('#page-loader').css('display', 'block');
								window.location.href = myBaseUrl + "orders/schedules/" + order_id + '/';
							}
						} else {
							/*
							 * Remove all records for the deleted schedule form withing the schedules_detail databank.
	                         * Update the "schedules_detail", "orders_detail", and "orders_schedule_status" for the 
							 * order_id.
							 */
	                        removeScheduleFromDetails(schedule_id);
	                        ajaxUpdateDataStoreForOrder(order_id, null, null);	
	                        
	                        // For now... Call the method that rebuilds the entire schedule table.
	                        //buildScheduleTable();
	                        // Remove all instances of the schedule from the table
	                        $('.sched-' + schedule_id).replaceWith('<div>&nbsp;</div>');
						}
					} else {
                        // Display Error
                        // Keep the schedule open
                        constructScheduleActionMessage(success, message + '<br />' + error);
                    }
				} else {
					var error = 'An Error occured. Please try again or contact the System Administrator.';
                     constructScheduleActionMessage(0, error);
				}
				stopLoaderSchedule();
			},
		});
        return false;
    }
    function ajaxDeleteAllSchedules(order_id) {
        // Build a parameter list to lend to the server
		var params = '';
		params = params + 'order_id:'+ order_id + '/';
		
		var url_path = "schedules/ajax_delete_all_schedules_for_order/"
		$.ajax({
			url: myBaseUrl + url_path + params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startLoaderSchedule();
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
						/*
						 * Remove all records for the deleted schedule form withing the schedules_detail databank.
                         * Update the "schedules_detail", "orders_detail", and "orders_schedule_status" for the 
						 * order_id.
						 */
                        removeSchedulesForOrderFromDetails(order_id);
                        ajaxUpdateDataStoreForOrder(order_id, null, null);	
                        // For now... Call the method that rebuilds the entire schedule table.
                        buildScheduleTable();
					} else {
                        // Display Error
                        // Keep the schedule open
                        constructScheduleActionMessage(success, message + '<br />' + error);
                    }
				} else {
					var error = 'An Error occured. Please try again or contact the System Administrator.';
                     constructScheduleActionMessage(0, error);
				}
				stopLoaderSchedule();
			},
		});
        return false;
    }
	function ajaxUpdateScheduleStatusesForOrder(model, foreign_key, status) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'model:'+ model + '/';
		params = params + 'foreign_key:'+ foreign_key + '/';
		params = params + 'status:'+ status + '/';
		
		var url_path = "schedules/ajax_update_schedules_for_order_tasks/"
		// Update path depending on the model
        switch (model) {
            case 'order_line_item' :
                url_path = "schedules/ajax_update_schedules_for_order_item/";
                break;
            case 'order_line_item_task' :
                url_path = "schedules/ajax_update_schedules_for_order_item_task/";
                break;
            case 'order' :
                url_path = "schedules/ajax_update_schedules_for_order/";
                break
        }
		$.ajax({
			url: myBaseUrl + url_path+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startLoaderOrder;
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						success = obj.success,
						message = obj.message,
						order_id = obj.order_id;
					if(success) {
						// Upon success.. change the color of the status when all schedules displayed within the table.
						var new_status = 'status-' + status;
						$('.sched.order-' + order_id).each( function() {
                            $(this).removeClass('status-1');
                            $(this).removeClass('status-30');
                            $(this).removeClass('status-40');
                            $(this).removeClass('status-50');
                            $(this).removeClass('status--1');
                            $(this).addClass(new_status);
                            $(this).attr('status', status);
                        }); 
                        
                        // Now update the status for the order within the clipboard.
                        var new_id = 'order|' + order_id + '|' + status,
                        	targetElement = '#order-container-' + order_id + ' div.action-status';
                        $(targetElement).attr('id', new_id);
                        $(targetElement).removeClass('status-1');
                        $(targetElement).removeClass('status-30');
                        $(targetElement).removeClass('status-40');
                        $(targetElement).removeClass('status-50');
                        $(targetElement).removeClass('status--1');
                        $(targetElement).addClass(new_status);
                        
                        // Test the status... If it is set to 1, the schedule is 'New', or 'UnScheduled'.
                        // Else, make sure that the div.order-container does not have the 'unscheduled' class.
                        if(status == 1) {
                        	$('div#order-container-' + order_id).addClass('unscheduled');
                        } else {
                        	$('div#order-container-' + order_id).removeClass('unscheduled');
                        	if($('div#order-filter-container div#unscheduled').hasClass('bold')) {
                        		$('div#order-container-' + order_id).css('display', 'none');
                        	}
                        }
						/*
						 * Update the "schedules_detail", "orders_detail", and "orders_schedule_status" for the 
						 * order_id.
						 */
						ajaxUpdateDataStoreForOrder(order_id, model, foreign_key);	
						$('#order-schedule-status-cluetip-container').css('display', 'none');
					}
				} else {
					
				}
				stopLoaderOrder;
			},
		});
	}
	function ajaxUpdateScheduleStatuses(schedule_id, status, order_id) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'schedule_id:'+ schedule_id + '/';
		params = params + 'status:'+ status + '/';
		
		var url_path = "schedules/ajax_update_schedule_status/"
		$.ajax({
			url: myBaseUrl + url_path+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startLoaderSchedule();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText;
					var obj = jQuery.parseJSON(results);
					var success = obj.success;
					var message = obj.message;
					if(success) {
                        // Upon success.. change the color of the status.
                        $('.sched-' + schedule_id).each( function() {
                        	$(this).removeClass('status-1');
                            $(this).removeClass('status-30');
                            $(this).removeClass('status-40');
                            $(this).removeClass('status-50');  
                            $(this).removeClass('status--1');
                            $(this).addClass('status-' + status);
                        }); 
                        
                        /*
						 * Update the "schedules_detail", "orders_detail", and "orders_schedule_status" for the 
						 * order_id.
						 */
						ajaxUpdateDataStoreForSchedule(schedule_id, order_id);
                        $('.sched-' + schedule_id).each(function() {
                            $(this).attr('status', status);
                        });
					}
				} else {
					
				}
				stopLoaderSchedule();
			},
		});
	}
	
	
	/*******************
	 * Order Schedule Status functionality
	 */
	function openOrderScheduleStatusContainer(model, id, status, order_id, event) {
        var mouseX = event.pageX, 
            mouseY = event.pageY;
        /*
		 * When a status is selected... only one can be selected at a time.  Clear out all checkboxes... then select the one
		 */
		$('.order_schedule_status_checkbox').each( function() {
			$(this).prop('checked', false);
		});
        
        $('#order_schedule_status_checkbox_' + status).prop('checked', true);
        $('#schedule_order_status_order_id').val(order_id);
        $('#schedule_order_status_model').val(model);
		$('#schedule_order_status_foreign_key').val(id);
		$('#schedule_order_status').val(status);
        $('#order-schedule-status-cluetip-container').css('top', mouseY-15);
        $('#order-schedule-status-cluetip-container').css('left', mouseX-170);
        $('#order-schedule-status-cluetip-container').css('display', 'block');
	}
	$(document).on("click", ".order_schedule_status_checkbox", function() {
		/*
		 * When a status is selected... only one can be selected at a time.  Clear out all checkboxes... then select the one
		 * NOTE... A checkbox can not be de-selected.  Test for that and return false if user is attempting to de-select.
		 */
		if(!$(this).prop('checked')) {
			return false;
		}
		
		$('.order_schedule_status_checkbox').each( function() {
			$(this).prop('checked', false);
		});
		var status = $(this).attr('id').replace('order_schedule_status_checkbox_', '');
		// Loop through setting all clutip checkboxes for the correct status.
		$('.order_schedule_status_checkbox_' + status).each( function() {
			$(this).prop('checked', true);
		});
		$('.order_schedule_status').each( function() {
			$(this).val(status);
		});
		
		/*
		 * Ok.. The new status is selected... time to call the server and update the status of the order & (schedule/schedule_line_items/schedule_line_item_tasks)
		 * Pararmeters to send are
		 * model
		 * foreign_key
		 * 
		 */
		var foreign_key = $('#schedule_order_status_foreign_key').val(),
			model = $('#schedule_order_status_model').val();
		ajaxUpdateScheduleStatusesForOrder(model, foreign_key, status);
	});
    $(document).on('change', '#yaxis_type', function() {
        var index = $(this).val(),
            action =  'index_yaxis_' + index;
        if(index == 'job-type') {
            action =  'index_yaxis_job_type';
        } 
        window.location.href = myBaseUrl + "schedules/" + action + '/';
    });
    
    function setScheduleStatus(schedule_id, status) {
        /*
		 * When a status is selected... only one can be selected at a time.  Clear out all checkboxes... then select the one
		 */
		$('.schedule_status_checkbox').each( function() {
			$(this).prop('checked', false);
		});
        $('#schedule_status_checkbox_' + status).prop('checked', true);
        $('#schedule_status_status').val(status);
    }
    $(document).on("click", ".schedule_status_checkbox", function() {
		/*
		 * When a status is selected... only one can be selected at a time.  Clear out all checkboxes... then select the one
		 * NOTE... A checkbox can not be de-selected.  Test for that and return false if user is attempting to de-select.
		 */
		if(!$(this).prop('checked')) {
			return false;
		}
		var status = $(this).attr('id').replace('schedule_status_checkbox_', ''),
            sched_id = $('#schedule_status_sched_id').val();
			order_id = $('#schedule_status_order_id').val();
        setScheduleStatus(sched_id, status);
	
		/*
		 * Ok.. The new status is selected... time to call the server and update the status of the schedule_line_items/schedule_line_item_tasks
		 * Pararmeters to send are
		 * schedule_id and status
		 */
        ajaxUpdateScheduleStatusesForOrder('order', order_id, status);
		//ajaxUpdateScheduleStatuses(sched_id, status, order_id);
	});
    $(document).on("click", ".schedule_button", function() {
        var id = $(this).attr('id').replace('schedule_button_', '');
        
        // LOOP through and select all the users within that JobType.
        $('.schedule_button').addClass('collapse');
        $('#schedule_button_' + id).removeClass('collapse');

        $('#schedule-when-container').css('display', 'none');
        $('#schedule-who-container').css('display', 'none');
        $('#who-view-by-container').css('display', 'none');
        $('#schedule-what-container').css('display', 'none');
        $('#schedule-comment-container').css('display', 'none');
        $('#existing-schedules_toggle_display').css('display', 'none');
        switch(id) {
	        case 'who':
	        	$('#schedule-who-container').css('display', 'block');
	        	$('#who-view-by-container').css('display', 'block');
	            break;
	        case 'what':
	        	$('#schedule-what-container').css('display', 'block');
	            break;
	        case 'schedules':
	        	$('#existing-schedules_toggle_display').css('display', 'block');
	            break;
	        case 'comment':
	        	$('#schedule-comment-container').css('display', 'block');
	        	break;
	        default:
	        	$('#schedule-when-container').css('display', 'block');
	    } 
    });
	$(document).on("click", ".sr-job-type", function() {
        var id = $(this).attr('id').replace('sr-job-type-', ''),
            checked = $(this).prop('checked');
        
        // LOOP through and select all teh users within that JobType.
        $('.sr-user-for-job-type-' + id).each(function() {
            $(this).prop('checked', checked);
        });
    });
    $(document).on("click", ".sr-user-for-job-type", function() {
        // Obtain the id for the parent job_type checkbox
        var id = $(this).parents('.users-for-jobtype-container').attr('id');
        // Clean up the id.
        id = id.replace('users-for-jobtype-', '').replace('_toggle_display', '');
        
        // If checked... Make sure the parent JobType is selected.
        if($(this).prop('checked')) {
            $('#sr-job-type-' + id).prop('checked', true);
        } else {
            // Loop through the other users within the JobType to see if any others are selected.  If none, uncheck the JobType.
            var checked_value = false;
            $('div#master-schedule-edit-content div#schedule-who-jobtype .sr-user-for-job-type-' + id).each(function() {
            	if($(this).prop('checked')) {
                    checked_value = true;
                }
            });
            $('#sr-job-type-' + id).prop('checked', checked_value);
        }
    });
    function addScheduleToOrder(params, order_id, model, foreign_key, default_start, default_end, default_start_time, default_estimated_time, who_view_type, selected_who) {
        scheds = jQuery.data( document.body, "schedules_detail"),
        schedules = new Array(),
        target_who = who_view_type;
        
        /*
		 * Build Order Array
		 */
		var selected_order = buildOrderArray(order_id);
		/*
		 * Build Existing Schedule Array
		 */
		switch(model){
            case 'order' :
                // loop through the schedules... looking for schedules where ['order_id'] is equal to the foreign_key.
                if(scheds) {
					$.each(scheds, function(key, schedule) {
						if(schedule['order_id'] == foreign_key) {
							schedules[schedule['schedule_id']] = schedule['schedule_id'];
						}
					});
				}
                /*
				 * toggle the expand/visible/selected fields
				 * When the order is selected to be scheduled, each order_line_item (and any tasks) are set as visible and selected,
				 * Nothing is expanded.  Thus, only a list of Order Line Items will be seen
				 */
                default_estimated_time = 0;
                for(var index in selected_order['OrderLineItem']) {
                    default_estimated_time = default_estimated_time + Number(selected_order['OrderLineItem'][index]['order_line_item_hours']);
                    selected_order['OrderLineItem'][index]['selected'] = 1;
                    selected_order['OrderLineItem'][index]['visible'] = 1;
                    if(!($.isEmptyObject(selected_order['OrderLineItem'][index]['OrderLineItemTask']))) {
                        for(var index2 in selected_order['OrderLineItem'][index]['OrderLineItemTask']) {
                            selected_order['OrderLineItem'][index]['OrderLineItemTask'][index2]['selected'] = 1;
                            // selected_order['OrderLineItem'][foreign_key]['OrderLineItemTask'][index]['visible'] = 1;  At this time do not show the tasks 
                        }
                    }   
				}
                /* 
				 * Set the WHO section to display by employees 
				 */
				who_view_type = 'employee';
                break
			case 'order_line_item':
				// loop through the schedules... looking for schedules where ['order_line_item_id'] is equal to the foreign_key.
				if(scheds) {
					$.each(scheds, function(key, schedule) {
						if(schedule['order_line_item_id'] == foreign_key) {
							schedules[schedule['schedule_id']] = schedule['schedule_id'];
						}
					});
				}
				
				/*
				 * toggle the expand/visible/selected fields
				 * When an order_line_item is selected to be scheduled, Only that order_line_item (and any tasks) are set as visible and selected,
				 * Nothing is expanded.
				 */
                selected_order['OrderLineItem'][foreign_key]['selected'] = 1;
                selected_order['OrderLineItem'][foreign_key]['visible'] = 1;
                if(('OrderLineItemTask' in selected_order['OrderLineItem'][foreign_key]) && selected_order['OrderLineItem'][foreign_key]['OrderLineItemTask']) {
                    for(var index2 in selected_order['OrderLineItem'][foreign_key]['OrderLineItemTask']) {
                        selected_order['OrderLineItem'][foreign_key]['OrderLineItemTask'][index2]['selected'] = 1;
                    }
                }   
       
				// Estimated time is the order_line_item.labor_cost_hours ()
				default_estimated_time = selected_order['OrderLineItem'][foreign_key]['order_line_item_hours'];
				/* 
				 * Set the WHO section to display by employees 
				 */
				who_view_type = 'employee';
				break;
			case 'order_line_item_task':
				// Extra parameter
				var order_line_item_id = params[3];
				
				// loop through the schedules... looking for schedules where ['order_line_item_task_id'] is equal to the foreign_key.
				if(scheds) {
                    $.each(scheds, function(key, schedule) {
                        if(schedule['task_id'] == foreign_key) {
                            schedules[schedule['schedule_id']] = schedule['schedule_id'];
                        }
                    });
                }
				/*
				 * toggle the expand/visible/selected fields
				 * When an order_line_item is selected to be scheduled, The parent order_line_item and the selected task are selected.  
				 * The parent order_line_item and all tasks are set to visible
				 * The parent order_line_item has expanded selected.
				 */
				selected_order['OrderLineItem'][order_line_item_id]['selected'] = 1;
				selected_order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][foreign_key]['selected'] = 1;
				selected_order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][foreign_key]['visible'] = 1;
				selected_order['OrderLineItem'][order_line_item_id]['visible'] = 1;
				selected_order['OrderLineItem'][order_line_item_id]['expand'] = 1;
				/* AT THIS TIME ONLY SHOW THE SELECTED ONES
				for(var index in selected_order['OrderLineItem'][order_line_item_id]['OrderLineItemTask']) {
					selected_order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][index]['visible'] = 1;
				}
				*/
				
				// Estimated time is the order_line_item_task.labor_cost_hours (order_line_item_task_hours)
				default_estimated_time = selected_order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][foreign_key]['order_line_item_task_hours'];
				
				/* 
				 * Set the WHO section to display by employees 
				 */
				who_view_type = 'employee';
				
				break;
			case 'order_task':
				// loop through the schedules... looking for schedules where ['task_code'] is equal to the foreign_key and the order_id.
				$.each(scheds, function(key, schedule) {
					if((schedule['task_code'] == foreign_key) && (schedule['order_id'] == order_id)) {
						schedules[schedule['schedule_id']] = schedule['schedule_id'];
					}
				});
				
				/*
				 * toggle the expand/visible/selected fields
				 * When an order_task is selected to be scheduled, The parent order_line_item and the task of the selected task_code are marked as 'selected'.  
				 * The parent order_line_item and all tasks are set to visible
				 * The parent order_line_item has expanded selected.
				 */
                var selected_task_code = params[1];
				default_estimated_time = 0;
				for(var index in selected_order['OrderLineItem']) {
					//selected_order['OrderLineItem'][index]['OrderLineItemTask'][index]['visible'] = 1;
					if(selected_order['OrderLineItem'][index]['OrderLineItemTask'].length) {
						for(var index_task in selected_order['OrderLineItem'][index]['OrderLineItemTask']) {
							// Check if the task codes the the same.
							if(selected_order['OrderLineItem'][index]['OrderLineItemTask'][index_task]['order_line_item_task_code'] == selected_task_code) {
								selected_order['OrderLineItem'][index]['expand'] = 1;
								selected_order['OrderLineItem'][index]['selected'] = 1;
								selected_order['OrderLineItem'][index]['visible'] = 1;
								selected_order['OrderLineItem'][index]['OrderLineItemTask'][index_task]['selected'] = 1;
								selected_order['OrderLineItem'][index]['OrderLineItemTask'][index_task]['visible'] = 1;
								
								default_estimated_time = default_estimated_time + Number(selected_order['OrderLineItem'][index]['OrderLineItemTask'][index_task]['order_line_item_task_hours']);
							} 
						}
					}
				}
				
				/* 
				 * Set the WHO section to display by job_types 
				 * 
				 */
				who_view_type = 'job_type';
				
				break;
			default:
			  return false;
		}
		
		/*
		 * Current Schedules for the Order
		 */
		// Condense the result so the schedule ids are distinct.
		targeted_schedules = new Array();
		for(var index in schedules) {
			if(index.length) {
				targeted_schedules.push(index);
			} 
		}
        
		// Now that there is a list of distinct schedule... build a schedule array
		var schedule_array = new Array();
		for(var index in targeted_schedules) {
			schedule_array[index] = buildScheduleArray(targeted_schedules[index]);
		}
		
		/*
		 * POSITION THE SCHEDULE WINDOW
		 */
        // Determine if the Client's default view and if they can see both employee's and/or job_type 
		if(selected_who) {
			if(target_who == 'employee') {
				constructScheduleWho('job_type', null, 'add');
		        constructScheduleWho('employee', selected_who, 'add');
			} else {
				constructScheduleWho('job_type', selected_who, 'add');
		        constructScheduleWho('employee', null, 'add');
			}
		} else {
			constructScheduleWho('job_type', null, 'add');
	        constructScheduleWho('employee', null, 'add');
		}
		constructScheduleWhat(order_id, selected_order);
        constructScheduleHidden(null);
		constructCurrentSchedule(order_id, schedule_array);
		
		/*
		 * DATES
		 * Populate the from/to dates
		 */
        // Using the default_estimated_time value along with the currently displayed date to determine the 'to date' and 'time' and duration
		populateScheduleToDateTimeValues(default_start, default_end, default_start_time, default_estimated_time, 0);
        
        /*
         * Adjust the schedule container to reflect the schedule is in Add mode.
         * Diable the Delete Button
         */
        $('#schedule-title #schedule-mode').html('Add Schedule');
        $('#master-schedule-edit-container #delete_schedule').css('display', 'none');
        /* $('#order-schedule-container #delete_schedule').css('display', 'none'); */
        
		turnScheduleOn(order_id);	
        
        /*
         * After the Schedule is turned on... Determine the appropriate view
         */
		if(selected_who) {
			who_view_type = target_who;
		}
        if(who_view_type == 'employee'){
            toggleWhoViewTypeToEmployee();
        } else {
            toggleWhoViewTypeToJobType();
        }
		return false;
    }
    
    function findSelectedOrder() {
        //$('.order-container')
        var order_id = $('#orders_container').find('.order-container.selected').attr('id');
        if(order_id) {
        	order_id = order_id.replace('order-container-', '');
        }
        return order_id;
    }
    $(document).on('click', '.time-y-axis-record', function() {
        if(!$(this).find('div').hasClass('sched')) { 
            var id = $(this).attr('id').replace('time-y-axis-id-',''),
                yAxis_id = id.split('-')[0],
                startDateFlat = id.split('-')[1],
                startTime = id.split('-')[2],
                axis_type = $('#yaxis_type').val(),
                order_id = findSelectedOrder(),
                model = 'order',
                foreign_key = '',
                default_estimated_time = 1,
                view_date = formatDate(startDateFlat),
                start_date = view_date['month'] + '/' + view_date['day'] + '/' + view_date['year'],
                end_date = start_date,
                selected_who = null;
            
            // If start time doesn't exist.. defautl to company start time.
            if (!startTime) {
            	startTime = $('#workday_start').val();
            }
            
            // If the axis_type is job... Use the id to display the appropriate job.
            switch (axis_type) {
                case 'order' :
                    order_id = yAxis_id;
                    default_view_type_for_who = 'job_type';
                    break;

                case 'job-type' :
                    selected_who = new Array();
                    selected_who[0] = new Array();
                    selected_who[0]['job_type_id'] = yAxis_id;
                    default_view_type_for_who = 'job_type';
                    break;

                case 'employee' :
                    selected_who = new Array();
                    selected_who[0] = new Array();
                    selected_who[0]['id'] = yAxis_id;
                    default_view_type_for_who = 'employee';
                    break;
            }
            foreign_key = order_id;
            if(order_id) {
                if(startTime.length == 1 || startTime.length == 2) {
                    startTime = startTime.toString() + '00';
                } else if (startTime.length == 0) {
                    startTime = $('#workday_start').val();
                }
                addScheduleToOrder(null, order_id, model, foreign_key, start_date, end_date, startTime, default_estimated_time, default_view_type_for_who, selected_who);
            } else {
                alert('Select a Job from the column to the right.');
            }
        }
    });
	$(document).on("click", ".action-schedule", function() {
		var id = $(this).attr('id'),
        	params = id.split('|');
		actionSchedule(params[0], params[1], params[2], params); 
		return false;
	});
	
	function checkForActiveOrder() {
		var order_id = $('input#selected_order').val(),
			schedule_id = $('input#selected_schedule').val();
	
		if((order_id.length)) {
			if($('#origination_module').val() == 'schedule') {
				expand_schedule_order(order_id);
			}
			actionSchedule('order', order_id, order_id, null);
		}
		if(schedule_id.length) {
			editSchedule(schedule_id);
		}
	}

	function actionSchedule(model, foreign_key, order_id, params) {
		//var id = $(this).attr('id'),
        var default_estimated_time = 1,
	    	view_date = formatDate(deconstructSelectedDate($('#date_selected').val())),
	    	default_view_type_for_who = 'job_type';
        
       /*
		 * DATES
		 * Populate the from/to dates
		 * Because this function is being called from the Orders section and in relation to a schedule being added,
		 * default the date to the date to the value held in the "#date_selected" element.
		 */
		start_date = view_date['month'] + '/' + view_date['day'] + '/' + view_date['year'];
		end_date = start_date;
		addScheduleToOrder(params, order_id, model, foreign_key, start_date, end_date, $('#workday_start').val(), default_estimated_time, default_view_type_for_who, '');
	}
	
	function editSchedule(schedule_id) {
		//var scheds = jQuery.data( document.body, "schedules_detail");
		var schedule = buildScheduleArray(schedule_id),
            order = buildOrderArray(schedule['order_id']),
            users = buildScheduleResourceArray(schedule_id);
		
		order['schedule_status'] = schedule['schedule_status'];
		
		// Loop through the Schedule.. Locate the LineItems and LineItemTasks
		for(var index in schedule['ScheduleLineItem']) {
			var lineItem = schedule['ScheduleLineItem'][index],
				schedule_item_id = lineItem['schedule_item_id'],
				order_line_item_id = lineItem['order_line_item_id'];
			/*
			 * The Schedule has a line item... Locate the line item record in the order object using the order_line_item_id.
			 * Add the necessary fields to mark it.
			 */
			order['OrderLineItem'][order_line_item_id]['expand'] = lineItem['scheduled_item_includes_tasks']; // For now.. until we determine if tasks were selected.
			order['OrderLineItem'][order_line_item_id]['selected'] = 1;
			order['OrderLineItem'][order_line_item_id]['visible'] = 1;
			order['OrderLineItem'][order_line_item_id]['schedule_item_id'] = schedule_item_id;
			order['OrderLineItem'][order_line_item_id]['schedule_item_status'] = lineItem['schedule_item_status'];
			if('ScheduleLineItemTask' in lineItem) {
				var lineItemTasks = lineItem['ScheduleLineItemTask'];
				for(var indexTask in lineItemTasks) {
					var lineItemTask = lineItemTasks[indexTask];
					
					/*
					 * The Schedule has a line item tasks... Locate the line item tasks record in the order object using the order_line_item_id & lineItemTask['task_id'] fields.
					 * Add the necessary fields to mark it.
					 */
					order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][lineItemTask['task_id']]['expand'] = 0; 
					order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][lineItemTask['task_id']]['selected'] = 1;
					order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][lineItemTask['task_id']]['visible'] = 1;
					order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][lineItemTask['task_id']]['schedule_item_task_status'] = lineItemTask['schedule_item_task_status'];
					order['OrderLineItem'][order_line_item_id]['OrderLineItemTask'][lineItemTask['task_id']]['schedule_item_task_id'] = lineItemTask['schedule_item_task_id'];
				}
			}
		}
		// At this point the order array has been modified to contain the necessary fields/markings to identify which items/lineitems have been selected in the order.
		constructScheduleWhat(schedule['order_id'], order);
		
		/*
		 * Dates and Times
		 */
		var start_date = formatDate(deconstructSelectedDate(schedule['date_session_start'])),
			end_date = formatDate(deconstructSelectedDate(schedule['date_session_end'])),
			start_time = formatTime(schedule['time_start']),
			end_time = formatTime(schedule['time_end']);
		start_time = constructTime24(start_time['hour'].toString(), start_time['min'], start_time['post']);
		end_time = constructTime24(end_time['hour'].toString(), end_time['min'], end_time['post']);
		var duration = schedule['duration_in_seconds'] / 3600;	
		
		$('#datepicker_schedule_display_from').html(start_date['formated']);
		$('input#datepicker_schedule_from').val(start_date['formated']);
		$('#datepicker_schedule_display_to').html(end_date['formated']);
		$('input#datepicker_schedule_to').val(end_date['formated']);
		days = buildDateTimeArray(start_date, start_time, duration, schedule['include_weekends']);
		day_count = days.length - 1;
		popup_start_duration = days[day_count]['popup_start_duration'];
		constructDurationInput(duration);
		constructScheduleStartTime(start_time.toString());
		constructScheduleEndTime(end_time.toString());
		constructScheduleHidden(schedule_id);

		/*
		 * Generate a list of times the user can select from of the time_end value. 
		 * If the end date is the same as the start date... use the start_time, else use the start of the work day.
		 */ 
		/*
		if(day_count == 1) {
			var list = constructTimeList(days[day_count]['start_time'].toString(), $('#workday_end').val(), popup_start_duration, 0);
		} else {
			var list = constructTimeList($('#workday_start').val(), $('#workday_end').val(), popup_start_duration, 1);
		}
		$('#order-schedule-container table#time-end-select').html(list);
	    */
		
        /*
         * Construct the "Who" section.  
         */
        constructScheduleWho('job_type', users, 'edit');
		constructScheduleWho('employee', users, 'edit');  
		
        /*
         * Adjust Anything else...
         */
        $('#schedule-title #schedule-mode').html('Edit Schedule');
        $('#master-schedule-edit-container #delete_schedule').css('display', 'block');
        //$('#order-schedule-container  #delete_schedule').css('display', 'block');
        
		//constructCurrentSchedule(schedule['order_id'], schedule_array);
		turnScheduleOn(schedule['order_id']);
	}
	

	/***********
	 *	A ORDER EXPAND/COLLAPSE IS SELECTED
	 *	When the Client selects to open or close an order to see it's details
	 */
	$(document).on("click", ".order_button", function() {
		var order_id = $(this).attr('id').substring(13);
		if($(this).hasClass('collapse')) {
			expand_schedule_order(order_id);
		} else {
			collapse_schedule_order(order_id);
		}
		return false;
	});
    
    $(document).on("click", ".order-title", function() {
		//var order = $(this).find('div.order_button'),
    	var order = $(this).closest('div.order-container'),
    		order_id = order.attr('id').substring(16);
        
    	if(order.hasClass('collapse')) {
			expand_schedule_order(order_id);
		} else {
			collapse_schedule_order(order_id);
		}
		return false;
	});
	
	function expand_schedule_order(order_id) {
		var source_module = $('input#origination_module').val();
		
		// If the source module is 'order'... no need to collapse...  This is 
		// because only one order is displayed on the screen.
		if(source_module == 'schedule') {
			// COLLAPSE ALL OPENED ORDERS //
			collapse_all_schedule_orders();
		}
		
		/*
		 * Before expanding the selected order...
		 * First determine the Appropriate view (by item or by task) This will be obtained from a hidden field on the schedule form
		 */
		initializeOrder(order_id);

		// Adjust buttons displayed.
		//$('#order-button-'+order_id).addClass('expand');
		//$('#order-button-'+order_id).removeClass('collapse');
		$('#order-container-'+order_id).removeClass('collapse');
		$('#order-container-'+order_id).addClass('expand');
        $('#order-container-'+order_id).addClass('selected');
        $('#order-button-'+order_id+'_toggle_display').css('display', 'block');  // Display the data
        
        // Schedule Table Modifications
        $('div.sched').removeClass('sched_hovered');
        $('div#schedule-table-sched-summary-container').css('display','none');
        formatOrderDisplay(order_id);
	}

	function collapse_schedule_order(order_id) {
		// Adjust buttons displayed.
		//$('#order-button-'+order_id).addClass('collapse');
        //$('#order-button-'+order_id).removeClass('expand');
        $('#order-container-'+order_id).addClass('collapse');
        $('#order-container-'+order_id).removeClass('expand');
        $('#order-container-'+order_id).removeClass('selected');
        $('#order-button-'+order_id+'_toggle_display').css('display', 'none');  // Hide the data
	}
    
    function collapse_all_schedule_orders() {
        $('.order_button').removeClass('expand');
        $('.order_button').addClass('collapse');
        $('.order-container').removeClass('selected');
        $('.order-detail-container').css('display', 'none'); 
    }
	
	/***********
	 *	A Job Types for a Schedule's "Who" section EXPAND/COLLAPSE IS SELECTED
	 */
	$(document).on("click", ".arrow-button", function() {
		if($(this).hasClass('collapse')) {
			$(this).removeClass('collapse');
			$(this).addClass('expand');
		} else {
			$(this).removeClass('expand');
			$(this).addClass('collapse');
		}
		return false;
	});
	
	
	
	
	/**
	 * Function accessing the databank
	 */
	function buildItemsForOrder(order_id) {
        // This will create an initial array of task/task_codes along with the summed estimated values.
		var orders = jQuery.data( document.body, "orders_detail"),
			line_items = new Array(),
			all_records_for_order = new Array(),
			line_item_html = '';
		
		$.each(orders, function(key, order) {
			if(order['order_id'] == order_id) {
				/* Collect the Line Item Data */
				line_items[order['order_line_item_id']] = new Array();
				line_items[order['order_line_item_id']]['id'] = order['order_line_item_id'];
				line_items[order['order_line_item_id']]['order_id'] = order_id;
				line_items[order['order_line_item_id']]['name'] = order['order_line_item_name'];
				line_items[order['order_line_item_id']]['description'] = order['order_line_item_description'];
				line_items[order['order_line_item_id']]['total'] = order['order_line_item_total'];
				line_items[order['order_line_item_id']]['status'] = order['order_line_item_status'];
				line_items[order['order_line_item_id']]['scheduling_complete'] = order['order_line_item_scheduling_complete'];
				line_items[order['order_line_item_id']]['estimated_time'] = order['order_line_item_hours'];
				line_items[order['order_line_item_id']]['scheduled_time'] = order['order_line_item_scheduled_hours'];
				
				// Store each record to be passed to the task construction.  This will save the application from having to 
				// reiterate back through all the order records.
				all_records_for_order.push(order);
			}
		});
		// Clear the Order Html container for the order's line line_items.
		$('div#view-by-item-' + order_id).html('');
		// Loop through the line_items for the selected order.
		for(var index in line_items) {
			// Once the line item has been created... Determine if the Line Item has any tasks associated with it.
			var tasks = new Array();
			for(var index2 in all_records_for_order) {
				// Loop through all the order records for the selected order.. grabbing the ones for the current line item.
				if((all_records_for_order[index2]['order_line_item_id'] == line_items[index]['id']) && (all_records_for_order[index2]['order_line_item_task_id'] != null)) {
					// Grab the order record.
					tasks[all_records_for_order[index2]['order_line_item_task_id']] = all_records_for_order[index2];
				}
			}
            
            hasTasks = false;
            if(tasks.length) {
                hasTasks = true;
            }
            line_item_html = constructLineItem(line_items[index], hasTasks);
            $('div#view-by-item-' + order_id).append(line_item_html);
			
			// If the tasks array is not empty... then construct the html to contain the tasks for the line item.
			var line_item_task_html = '';
			if(tasks.length) {
				line_item_task_html = constructLineItemTask(tasks, line_items[index]['id']);
			}
			$('div#view-by-item-' + order_id).append(line_item_task_html);
		}
		
		// Re-Initialize the cluetips
		initializeClueTips();
	}
	function buildTasksForOrder(order_id, schedule_id) {
		schedule_id = schedule_id || 0;
		
		// First build an array of the tasks used for the selected order.
		// This will create an initial array of task/task_codes along with the summed estimated values.
		var orders = jQuery.data( document.body, "orders_detail");
		var order_schedule_status = jQuery.data(document.body, "orders_schedule_status");
		var items = new Array();
        if(orders) {
            $.each(orders, function(key, order) {
                if(order['order_id'] == order_id) {
                    // Order Line Item Task Status
                    // Grab the status of the task.  Access the "orders_schedule_status" data to determine the status flag for the current line item
                    var status = 0;
                    var taskId = order['order_line_item_task_id'];
                    if('order_line_item_task-' + taskId in order_schedule_status) {
                        status = order_schedule_status['order_line_item_task-' + taskId]['status'];
                    }

                    // Check if the key already exists... If not, create a new array
                    if (!(order['order_line_item_task_code'] in items)) {
                        // Set the default values.
                        items[order['order_line_item_task_code']] = new Array();
                        items[order['order_line_item_task_code']]['name'] = order['order_line_item_task_name'];
                        items[order['order_line_item_task_code']]['estimated_time'] = order['order_line_item_task_hours'];
                        items[order['order_line_item_task_code']]['scheduled_time'] = 0;
                        items[order['order_line_item_task_code']]['order_schedule_status'] = status;
                    } else {
                        // Element already exists... Add onto the estimated time
                        items[order['order_line_item_task_code']]['estimated_time'] = Number(items[order['order_line_item_task_code']]['estimated_time']) + Number(order['order_line_item_task_hours']);

                        // Test the status... If the recorded status is less than the new one... keep the lesser of the two.
                        if((Number(status) == 1) || (Number(items[order['order_line_item_task_code']]['order_schedule_status']) > Number(status))) {
                            items[order['order_line_item_task_code']]['order_schedule_status'] = status;
                        }
                    }
                }
            });
        }
		// Loop through all the schedules to determine if any are for the selected order.
		var schedules = jQuery.data( document.body, "schedules_detail");
		var used_schedules = new Array();
        if(schedules) {
		$.each( schedules, function( key, schedule) {
                if(schedule['order_id'] == order_id) {
                    // Ok... in a schedule that belongs to a selected order.
                    // Now determine if the user provided a schedule_id... further restricting the data to be queried
                    if((schedule_id == 0) || (schedule_id == schedule['schedule_id'])) {
                        if(schedule['task_code'] != null && schedule['schedule_item_task_id'] != null) {
                            // Before adding the scheduled time... verify that the time has not already been added by checking if the schedule_id exists
                            // in the used_schedules array. Use a combo of schedule_id & task_code 
                            if(!(schedule['schedule_id']+'-'+schedule['task_code'] in used_schedules)) {
                                var time = schedule['duration_in_seconds'] / 3600;
                                if (items[schedule['task_code']]) {
                                    items[schedule['task_code']]['scheduled_time'] = Number(items[schedule['task_code']]['scheduled_time']) + Number(time);
                                }

                                // Now... Add that schedule to the used_schedules array so the time will not get added.
                                used_schedules[schedule['schedule_id']+'-'+schedule['task_code']] = schedule['schedule_id']+'-'+schedule['task_code'];
                            }
                        }
                    }
                }
            });
        }
		$content = '';
		for(var index in items) {
			//var status_class = convert_status_to_class(items[index]['order_schedule_status']);
			var status_class = 'status-' + items[index]['order_schedule_status'],
				status_id = '',
				update_status_class = '',
				ref_status_class = '';

			status_id = 'task-code-' + index + '|' + order_id + '|' + items[index]['order_schedule_status'];
			update_status_class='cluetip-schedule-status-update';
			$content = $content + '<div class="clear line-item-container">';
			$content = $content + '<div class="grid">';
			$content = $content + '<div class="col-1of4">' + items[index]['name'] + '</div>';
			$content = $content + '<div class="col-1of2">Est. - ' + items[index]['estimated_time'] + ' hrs. | Sched. - ' + items[index]['scheduled_time'] + ' hrs.</div>';
			$content = $content + '<div class="col-1of4"' + 
				'<div class="action">' + 
					'<div id="' + status_id + '" class="action-status ' + status_class + ' ' + update_status_class + '" ' + ref_status_class + '>&nbsp;</div>' + 
					'<div id="order_task|' + index + '|' + order_id + '" class="action-schedule"> </div>' + 
				'</div>' +
			'</div>';
			$content = $content + '</div>';
			$content = $content + '</div>';
		}
		
		$('#view-by-task-' + order_id).html($content);
		
		// Re-Initialize the cluetips
		initializeClueTips();
		return true;
	}
    function removeScheduleFromDetails(schedule_id) {
        /**
         * Access the schedules_details data bank and loop through it, removing any records with the schedule_id provided.
         * After completion, resave the data.
         */
        var schedules = jQuery.data( document.body, "schedules_detail"),
            data = new Array();
        if(schedules) {
            $.each(schedules, function(key, schedule) {
                if(schedule['schedule_id'] != schedule_id) {
                    data.push(schedule); 
                }
            });
            jQuery.data( document.body, "schedules_detail", data);
        }
        return true;
    }
    function removeSchedulesForOrderFromDetails(order_id) {
        /**
         * Access the schedules_details data bank and loop through it, removing any records with the schedule_id provided.
         * After completion, resave the data.
         */
        var schedules = jQuery.data( document.body, "schedules_detail"),
            data = new Array();
        if(schedules) {
            $.each(schedules, function(key, schedule) {
                if(schedule['order_id'] != order_id) {
                    data.push(schedule); 
                }
            });
            jQuery.data( document.body, "schedules_detail", data);
        }
        return true;
    }
	
	/**
	 * The following functions control the toggling of the Orders (expanding and closing)
	 * initializeOrder is called when an order is expanded by the user selecting the arrow button.  Depending on the default client view, either
	 * the Items or Tasks are expanded.
	 * 
	 * FOR e360... default_view_type should always be Item
	 */
	function initializeOrder(order_id) { 
		var default_view_type = $('#default_view_type').val();
		
        // Turn off the master Schedule Edit form. This will happen on both computer/mobile.. From the schedule and order modules.
        scheduleMasterOff();
        
		if(!default_view_type.length) {
			default_view_type = 'items';
            //default_view_type = 'tasks';
		}
		if(default_view_type == 'items') {
			toggleOrderViewTypeToItem(order_id);
		} else {
			toggleOrderViewTypeToTask(order_id);
		}
		return false;
	}
	function toggleOrderViewTypeToItem(order_id) {
		startLoaderOrder();
		buildItemsForOrder(order_id);
		$('#order-view-type-item-'+order_id).css('display', 'block');
		$('#order-view-type-task-'+order_id).css('display', 'none');
		//$('#view-by-item-'+order_id).css('display', 'block');
		//$('#view-by-task-'+order_id).css('display', 'none');
		
		// Reset the view/schedule containers
		$('#view-by-' + order_id).css('display', 'block');
		$('#schedule-container-' + order_id).css('display', 'none');
		stopLoaderOrder();
		return true;
	}
	function toggleOrderViewTypeToTask(order_id) {
        buildTasksForOrder(order_id);
		$('#order-view-type-item-'+order_id).css('display', 'none');
		$('#order-view-type-task-'+order_id).css('display', 'block');
		//$('#view-by-item-'+order_id).css('display', 'none');
		//$('#view-by-task-'+order_id).css('display', 'block');
		stopLoaderOrder();
		return true;
	}
    function toggleWhoViewTypeToEmployee() {
		$('div.schedule-container #toggle-schedule-view-who-employee-container').css('display', 'block');
		$('div.schedule-container #toggle-schedule-view-who-job-type-container').css('display', 'none');
		$('div.schedule-container #schedule-who-employee').css('display', 'block');
		$('div.schedule-container #schedule-who-jobtype').css('display', 'none');
		return true;
	}
    function toggleWhoViewTypeToJobType() {
		$('div.schedule-container #toggle-schedule-view-who-employee-container').css('display', 'none');
		$('div.schedule-container #toggle-schedule-view-who-job-type-container').css('display', 'block');
		$('div.schedule-container #schedule-who-employee').css('display', 'none');
		$('div.schedule-container #schedule-who-jobtype').css('display', 'block');
		return true;
	}
	/**
	 * The following click events deal with the toggling of the order's view type.
	 * Does the client want to see the order's by items or tasks.
	 */
    $(document).on("click", ".toggle-order-view-type-to-item", function() {
		var order_id = $(this).attr('id').substring(31);
		toggleOrderViewTypeToItem(order_id);
		return false;
	});
    $(document).on("click", ".toggle-order-view-type-to-task", function() {
		var order_id = $(this).attr('id').substring(31);
		toggleOrderViewTypeToTask(order_id)
		return false;
	});
	/**
	 * The following click events deal with the toggling of the "Who"s container.
	 * Does the client want to Schedule by employees or jobtype
	 */
	$(document).on("click", ".toggle-schedule-view-who-employee", function() {
        toggleWhoViewTypeToEmployee();
		return false;
	});
	$(document).on("click", ".toggle-schedule-view-who-job-type", function() {
        toggleWhoViewTypeToJobType();
		return false;
	});
	
	
	/*
	 * Scheduling Function
	 * This functions will concern themselves with the actual scheduling of time.
	 */
	$(document).on("blur", "input.hour_start", function() {
		var id = $(this).attr('id');
		if($(this).val().length == 0) {
			var container = $(this).parent('div.time_select_container');
			container.find('#error-message-hour_start').html('Error:  A Start time must be entered.');
			//$('#error-message-hour_start').html('Error:  A Start time must be entered.');
			$(this).focus();
		} else {
			$('#error-message-hour_start').html('');
            adjustEndTimesPerStartAndDuration();
		}
	});
	function formatTimeMinutes(input) {
		if(input.val() == 0) {
			input.val('00');
		} else if(input.val().length == 1) {
			input.val('0' + input.val());
		} else {
			input.val(input.val().replace(/^0+/, ''));
		}
	}
	$(document).on("change", "div.schedule-container #minute_start", function() {
		formatTimeMinutes($(this));
		adjustEndTimesPerStartAndDuration();
	});
	$(document).on("change", "div.schedule-container #post_meridiem_start", function() {
		adjustEndTimesPerStartAndDuration();
	});
	/*
	 * End Time change
	 */
	$(document).on("blur", "input.hour_end", function() {
		adjustDuration();
	});
	$(document).on("change", "div.schedule-container #minute_end", function() {
		adjustDuration();
	});
	$(document).on("change", "div.schedule-container #post_meridiem_end", function() {
		adjustDuration();
	});
	
	
	$(document).on("click", "div.select-schedule-to-edit", function() {
		var id = $(this).attr('id').replace('select-schedule-to-edit-', '');
		editSchedule(id);
	});
	$(document).on("click", "div.view-all-line-items", function() {
		var id = $(this).attr('id').replace('view-all-line-items-', '');
		
		// Loop through all the lineitems in the #schedule-container-<order-id> #schedule-what .what-line-item-container and enabling them
		$('#master-schedule-edit-container #schedule-what .line-item-checkbox-container').each(function() {
			$(this).css('display', 'block');
		}); 
		$('#master-schedule-edit-container #schedule-what .line-item-task-checkbox-container').each(function() {
			$(this).css('display', 'block');
		}); 
	});
	$(document).on("click", "div.view-all-tasks", function() {
		var id = $(this).attr('id').replace('view-all-tasks-', '');
		// Loop through all the lineitems in the #line-item-task-checkbox-container-<order-line-id> and enabling them
		$('#line-item-task-checkbox-container-' + id + ' .what-line-item-task-container').each(function() {
			$(this).css('display', 'block');
		}); 
	});
	$(document).on("click", "div.what-line-item-container input[type='checkbox']", function() {
		var id = $(this).attr('id').replace('ScheduleItem', '');
		
		var checked = $(this).is(':checked');
		$("#line-item-task-checkbox-container-" + id + " input[type='checkbox']").each(function() {
			$(this).prop('checked', checked);
		}); 
	});
	$(document).on("change", "#schedule-status-select", function() {
		// Obtain the current ScheduleId (Probably Dont need it)
		var id = $('input#ScheduleId').val();
		var value = $(this).val();
		$('select.schedule-status').each(function() {
			$(this).val(value);
		});
	});
	$(document).on("change", ".line-item-status", function() {
		// Obtain the current ScheduleId (Probably Dont need it)
		var id = $(this).attr('id').replace('line-item-status-', '');
		var value = $(this).val();
		$('select.task-of-line-item-status-' + id).each(function() {
			$(this).val(value);
		});
	});
    $(document).on("click", "a#cancel_schedule", function() {
        var obj = $(this).parents('div.schedule-container'),
            order_id = obj.find('input#order_id').val();
		initializeOrder(order_id);
		turnScheduleOff(order_id);
        return false;
	});
    $(document).on("click", "a#delete_schedule", function() {
        var obj = $(this).parents('div#master-schedule-edit-container');
            order_id = obj.find('input#order_id').val();
            schedule_id = obj.find('input#ScheduleId').val();
        var r=confirm("Are you sure you want to delete this item?");
        if (r==true) {
            // You pressed OK!  Go Ahead and delete the schedule
            ajaxDeleteSchedule(order_id, schedule_id);
        } else {
           // You pressed Cancel!
            return false;
        } 
	});
    $(document).on('click', '.delete_schedule_from_table', function() {
		var sched_id = $('#schedule_status_sched_id').val();
			order_id = $('#schedule_status_order_id').val();
        ajaxDeleteSchedule(order_id, sched_id);
        return false;
	});
    $(document).on('click', '.delete_schedules_for_order', function() {
		var order_id = $('#schedule_order_status_order_id').val();
        ajaxDeleteAllSchedules(order_id);
        return false;
	});
    $(document).on('click', '.jump_to_order_from_table', function() {
		var order_id = $('#schedule_status_order_id').val();
		window.open(myBaseUrl + "order_line_items/add/" + order_id + '/', '_blank');
        return false;
	});
    $(document).on('click', '.jump_to_order_from_order', function() {
		var order_id = $('#schedule_order_status_order_id').val();
		window.open(myBaseUrl + "order_line_items/add/" + order_id + '/', '_blank');
        return false;
	});
   
	
	/***********
	 *	A TIME IS SELECTED FROM THE END TIME
	 */
	$(document).on("click", "table#time-end-select tr", function() {
		var selected_date = formatTime($(this).attr('id'));
		$('.time_end_display').val(selected_date['hmp']);
		$('.time_end').val($(this).attr('id'));
		$('.time-end-select-container').css('display', 'none');
		$('input#duration').val($(this).find('div#duration').html());
	});
	
	/***********
	 *	Open the end time select box
	 *	CHOOSE AN END TIME FOR THE SCHEDULE
	 */
	$(document).on("click", "div.schedule-container #time_end_display", function() {
		// Clear any previous selected times.
		$('tr').each(function() {
			$(this).children('td.first').html('');
			$(this).css('font-weight', 'normal');
		});
		
		// Mark the current time within the selection box.
		var end_time = $('#time_end').val();
        $('tr.'+end_time + ' td.first').html('&rarr;');
		$('tr.'+end_time).css('font-weight', 'bold');
		$('tr.'+end_time).removeClass('hide_quarter_slot');

		// initialize arrows
		$('img#icon-arrow-down').css('display', 'inline-block');
		$('img#icon-arrow-up').css('display', 'none');
		$('tr.hide_quarter_slot').css('display', 'none');
        $('div#time-end-select-container').css('height', '240px');
		$('#time-end-select-container').css('display', 'block');
	});

    /***********
     *	Select the "show more" arrows in the End Time select box.
     */
    $(document).on("click", "img#icon-arrow-down", function() {
        // lengthen height
        $('div.time-end-select-container').css('height', '340px');
        toggle_time_container();
    });
    $(document).on("click", "img#icon-arrow-up", function() {
        // Restore height
        $('div.time-end-select-container').css('height', '240px');
        toggle_time_container();
    });

    function toggle_time_container() {
        // loop through all the rows.  Removing 
        $('tr.hide_quarter_slot').toggle();
        $('img#icon-arrow-down').toggle();
        $('img#icon-arrow-up').toggle();
    };
    
	/***********
	 *	User changes the value within the Duration Input
	 */
	$(document).on("keyup", "div.schedule-container #duration", function() {
		adjustEndTimesPerStartAndDuration();
	});
	
	
	/*************
	 * CONSTRUCT
	 * Construct Functions will be used to build html for the page
	 */
    function constructScheduleActionMessage(success, message) {
        if(success == 1) {
			// Flash success
            $('#schedule-action-status-container #ajax_message_success').html(message);
            $('#schedule-action-status-container #ajax_message_success').fadeIn('fast').delay(2000).fadeOut('fast');
		} else {
			// error occured
	    	// Flash error message
            $('#schedule-action-status-container #ajax_message_fail').html(message);
            $('#schedule-action-status-container #ajax_message_fail').fadeIn('fast');
		}
		
    }
	function constructLineItemTitle(title, identifier, length) {
		var length = length || 80,
            title_display = title.replace(/<br.*?>/g,'').replace(/<p>/g,'').replace(/<p.*?>/g,''),
            n = title.indexOf(' ', length);
		if(n > 0) {
			title_display = title_display.substr(0,n);
		}
		var html = title_display;
		if(title.length > length) {
			html = html + '<a class="cluetip-schedule-order-desc" href="#" rel="#' + identifier + '">...</a>';
			html = html + '<div id="' + identifier + '" class=""><div class="cluetip-schedule-pad">' + title + '</div></div>';
		}
		
		return html;
	}
	
	function constructLineItem(line_item, hasTasks) {
		/*
		 * Order Line Items
		 */ 
        var label = '&nbsp;';
        if(hasTasks) {
            label = 'view tasks';
        }
		var estimate = 0;
		var scheduled = 0;
		if((line_item['estimated_time'] != null) && (line_item['estimated_time'].length)) {
			estimate = line_item['estimated_time'];
		}
		if((line_item['scheduled_time'] != null) && (line_item['scheduled_time'].length)) {
			scheduled = line_item['scheduled_time'];
		}
		var html = '';
		
		// Are we in the schedule module.. or are we scheduling fro the order module?
//		if($('#origination_module').val() == 'schedule') {
			html = '<div class="clear line-item-container">' + constructLineItemTitle(line_item['name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;' +  line_item['description'], 'line-item-description-' + line_item['id']);		
			html = html + '<br />';
			html = html + '<div class="grid">' + 
								'<div id="line-item-button-' + line_item['id'] + '" class="col-1of4 toggle_display_button small-link">' + label + '</div>' + 
								'<div class="col-1of2">Est. - ' + parseFloat(estimate).toFixed(2) + ' hrs' + '</div>' +
								'<div class="col-1of4">' +
									'<div class="action">' + 
										//constructOrderScheduleStatus('order_line_item', line_item['id']) +  
										constructScheduleButton('order_line_item', line_item['id'], line_item['order_id']) + 
									'</div>'
								'</div>' + 
							'</div>' + 
						'</div>';
					
			/* EXTRA DATA
			items[order['order_line_item_id']]['total'] = order['order_line_item_total'];
			items[order['order_line_item_id']]['status'] = order['order_line_item_status'];
			items[order['order_line_item_id']]['scheduling_complete'] = order['order_line_item_scheduling_complete'];
			*/
//		} else {
			// Must be in the order module
		
//		}
		return html;
	}
	function constructLineItemTask(order_line_item_task_results, line_item_id) {
		/*
		 * Order Line Item Tasks
		 */
		var html = '<div id="line-item-button-' + line_item_id + '_toggle_display" class="hide clear line-item-task-container">';
		for(var index in order_line_item_task_results) {		
			var estimate = 0;
			var scheduled = 0;
			if((order_line_item_task_results[index]['order_line_item_task_hours'] != null) && (order_line_item_task_results[index]['order_line_item_task_hours'].length)) {
				estimate = order_line_item_task_results[index]['order_line_item_task_hours'];
			}
			if((order_line_item_task_results[index]['order_line_item_task_scheduled_hours'] != null) && (order_line_item_task_results[index]['order_line_item_task_scheduled_hours'].length)) {
				scheduled = order_line_item_task_results[index]['order_line_item_task_scheduled_hours'];
			}
			
			// Order Line Item Task Status
			// Access the "orders_schedule_status" data to determine the status flag for the current line item
			var taskId = order_line_item_task_results[index]['order_line_item_task_id'];
			/*
			var status_class='unscheduled';
			var order_schedule_status = jQuery.data(document.body, "orders_schedule_status");
			if('order_line_item_task-' + taskId in order_schedule_status) {
				status_class = convert_status_to_class(order_schedule_status['order_line_item_task-' + taskId]['status']);
			}
			*/
			html = html + '<div id="order-line-item-task-' + taskId + '" class="grid">';
			html = html + '<div class="col-1of4">' + order_line_item_task_results[index]['order_line_item_task_name'] + '</div>';
			html = html + '<div class="col-1of2">Est. - ' + parseFloat(estimate).toFixed(2) + ' hrs' + '</div>';
			html = html + '<div class="col-1of4">';
			html = html + '<div class="action">'; 
			html = html + constructOrderScheduleStatus('order_line_item_task', taskId);
			html = html + constructScheduleButton('order_line_item_task', taskId, order_line_item_task_results[index]['order_id'], line_item_id);
			html = html + '</div>';
			html = html + '</div>';
			html = html + '</div>';
		}
		html = html + '&nbsp;';
		html = html + '</div>';

		/* EXTRA DATA
		'order_line_item_id' => '1',
		'order_line_item_total' => '5626.00',
		'order_line_item_status' => '1',
		'order_line_item_scheduled_hours' => null,
		'order_line_item_scheduling_complete' => '0',
		'order_line_item_task_id' => '2',
		'order_line_item_task_code' => '20',
		'order_line_item_task_total' => '3400.00',
		'order_line_item_task_status' => '1',
		'order_line_item_task_scheduling_complete' => '0'
		*/
		return html;
	}
	function constructOrderScheduleStatus(model, foreign_key, status) {
		status = status || 1;
		
		// The model parameter will designate if the status is for an order line item or a line item task
		// Foreign_key is the appropriate id. 
		// Access the "orders_schedule_status" data to determine the status flag
		var status_class='unscheduled',
			update_status_class='',
			ref_status_class='',
			order_schedule_status = jQuery.data(document.body, "orders_schedule_status"),
			order_detail = jQuery.data( document.body, "orders_detail"),
			id = '';
		
        if(status.length) {
        	status_class = 'status-' + status;
        	id = model + '|' + foreign_key + '|' + status;
        } else if(order_schedule_status && model + '-' + foreign_key in order_schedule_status) {
			status_class = 'status-' + order_schedule_status[model + '-' + foreign_key]['status'];
			id = model + '|' + foreign_key + '|' + order_schedule_status[model + '-' + foreign_key]['status'];
		}
		update_status_class='cluetip-schedule-status-update';

		var html = '<div id="' + id + '" class="action-status ' + status_class + ' ' + update_status_class + '" ' + ref_status_class + '>&nbsp;</div>';
		return html;							
	}
	function constructScheduleButton(model, foreign_key, order_id, order_line_item_id) {
		order_line_item_id = order_line_item_id || 0;
		id = model + '|' + foreign_key + '|' + order_id;
		if(order_line_item_id > 0) {
			id = id + '|' + order_line_item_id;
		}
		var html = '<div id="' + id + '" class="action-schedule">&nbsp;</div>';
		return html					
	}
	function constructCurrentSchedule(order_id, schedule_array) {
		// First check if the #schedule-other container exists... On mobile versions where only the current order
		// is being displayed, this section is left out.
		if($("#order-schedule-container #schedule-other").length) {
	        if($.isEmptyObject(schedule_array)) {
				$('#order-schedule-container div#schedule-other').html('');
			} else {
	            var expand_link = ''; //'<div id="existing-schedules" class="toggle_display_button arrow-button collapse"></div>'
	            var expand_container = '<div id="existing-schedules_toggle_display" class="left hide">';
				$('#order-schedule-container div#schedule-other').html('<h1>Current Schedules<div id="schedule_button_schedules" class="schedule_button collapse right">&nbsp;</div></h1>' + expand_link);
				var html = expand_container;
	            $.each(schedule_array, function(key, schedule) { 
	                var users = buildScheduleResourceArray(schedule['schedule_id']),
	                    users_block = constructScheduleUsersBlock(users),
	                    createdDate = formatDate(deconstructSelectedDate(schedule['status_created'])),
	                    startDate = formatDate(deconstructSelectedDate(schedule['date_session_start'])),
	                    startTime = formatTime(schedule['time_start']),
	                    endDate = formatDate(deconstructSelectedDate(schedule['date_session_end'])),
	                    endTime = formatTime(schedule['time_end']),
	                    duration = secondsTimeSpanToHMS(schedule['duration_in_seconds']);
					html = html + 
					'<div class="select-schedule-to-edit" id="select-schedule-to-edit-' + schedule['schedule_id'] + '">' +
	                    '<div class="schedule-view-title clear">' + 
	                        '<div class="grid">' +
	                            '<div class="col-1of2"><b>Created By</b></div>' + 
	                            '<div class="col-1of2">' +
	                                '<div class="grid">' +
	                                    '<div class="col-1of2"><b>Created</b></div>' +
	                                    '<div class="col-1of2"><b>Status</b></div>' + 
	                                '</div>' +
	                            '</div>' + 
	                            '<div class="col-1of2">' + schedule['created_by_name'] + '</div>' + 
	                            '<div class="col-1of2">' +
	                                '<div class="grid">' +
	                                    '<div class="col-1of2">' + createdDate['month'] + '/' + createdDate['day'] + '/' + createdDate['year'] + '</div>' +
	                                    '<div class="col-1of2">' + schedule['schedule_status_name'] + '</div>' + 
	                                '</div>' +
	                            '</div>' + 
	                        '</div>' +
	                    '</div>'+
	                    '<div class="schedule-view-title">' +
	                        '<div class="grid">' +
	                            '<div class="col-1of1"><b>Assigned To</b></div>' + 
	                            '<div class="col-1of1 clear">' + users_block + 
	                                '<div class="hide">' + schedule['schedule_id'] + '</div>' +
	                                '<div class="hide">' + schedule['schedule_in_current_view'] + '</div>' +
	                                '<div class="hide">' + schedule['schedule_type'] + '</div>' +
	                            '</div>' + 
	                        '</div>'+
	                    '</div>'+
	                    '<div class="schedule-view-title">' +
	                        '<div class="grid">' +
	                            '<div class="col-1of3"><b>From</b></div>' +
	                            '<div class="col-1of3"><b>To</b></div>' + 
	                            '<div class="col-1of3"><b>Duration</b></div>' + 
	                            '<div class="col-1of3 clear">' +
	                                '<div class="left pad-right">' + startDate['month'] + '/' + startDate['day'] + '/' + startDate['year'] + '</div>' +
	                                '<div class="left">' + startTime['hmp'] + '</div>' +
	                            '</div>' +
	                            '<div class="col-1of3">' +
	                                '<div class="left pad-right">' + endDate['month'] + '/' + endDate['day'] + '/' + endDate['year'] + '</div>' +
	                                '<div class="left">' + endTime['hmp'] + '</div>' +
	                            '</div>' +
	                            '<div class="col-1of3">' +
	                                '<div class="left clear"><b></b>&nbsp;&nbsp;' + duration['hour'] + ':' + duration['min'] + ' hrs</div>' +
	                            '</div>' + 
	                        '</div>'+
	                    '</div>'+
	                    '<div class="schedule-view-title">' +
	                        '<div class="grid summary">' +
	                            '<div class="col-1of1 clear"><b>Scheduled Items</b></div>' + 
	                        '</div>';
					
					if(!$.isEmptyObject(schedule['ScheduleLineItem'])) {
						// Line Items Exist... Loop through
						for(var li_index in schedule['ScheduleLineItem']) {
							html = html + '<div class="clear line-item-container">' + constructLineItemTitle(schedule['ScheduleLineItem'][li_index]['order_line_item_name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;' +  schedule['ScheduleLineItem'][li_index]['order_line_item_desc'], 'schedule-' + schedule['schedule_id'] + '-line-item-description-' + schedule['ScheduleLineItem'][li_index]['schedule_item_id'], 75);
							html = html + '</div>';
							
							// LINE ITEM TASKS
							// Check to See if any Line Item Tasks are associated to the schedule.
							if('ScheduleLineItemTask' in schedule['ScheduleLineItem'][li_index]) {
								// Line Items Tasks exist... Loop through
								for(var task_index in schedule['ScheduleLineItem'][li_index]['ScheduleLineItemTask']) {
									html = html + '<div class="clear task-container">Task: ' + schedule['ScheduleLineItem'][li_index]['ScheduleLineItemTask'][task_index]['task_name'] + '</div>';
								}
							}
						}
					}
					html = html + '</div></div>';
				});
	            $('#order-schedule-container div#schedule-other').append(html + '</div>');
			}
			initializeClueTips();
		}
		return true;
	}
	
	function constructScheduleWhatLineItemTasks(lineItemTasks, line_item_id) {
		/*
		['order_line_item_task_code']
		['order_line_item_task_description']
		['order_line_item_task_hours']
		['order_line_item_task_name']
		['order_line_item_task_scheduled_hours']
		['order_line_item_task_scheduling_complete']
		['order_line_item_task_status']
		['order_line_item_task_total']
		 */
		var html_visible = '',
			html_invisible = '',
			view_tasks = '';
		for(var index in lineItemTasks) {
			var task = lineItemTasks[index],
				checked = null;
			if(task['selected'] == 1) {
				checked = 'checked="checked"';
			}
			var visible = 'hide';
			if(task['visible'] == 1) {
				visible = '';
			} else {
				view_tasks = '<div class="view-all-tasks toggle_display_button arrow-button collapse" id="view-all-tasks-' + line_item_id + '">view all tasks&nbsp;</div>';
			}
			
			var estimatedHours = 0;
			if(!(task['order_line_item_task_hours']==null || task['order_line_item_task_hours']===false)) {
				estimatedHours = task['order_line_item_task_hours'] + ' hrs.'
			}
			var id = task['order_line_item_task_id'];
			if('schedule_item_task_id' in task) {
				id = id + '-' + task['schedule_item_task_id'];
			}
			
			var selected = 'selected="1"';
			var scheduled = ''
			var in_process = ''
			var complete = '';
			if(task['schedule_item_task_status']) {
				switch (task['schedule_item_task_status']) {
					case '50':
						in_process = selected;
						break;
					case '100':
						complete = selected;
						break;
					default:
						scheduled = selected;
				}
			}
			var html = 
			'<div class="grid what-line-item-task-container checkbox clear">' +
				'<div class="col-1of2">' +
					'<div id="' + id + '">' + 
						'<input id="ScheduleItemTask' + id + '" ' + checked + ' type="checkbox" value="' + id + '" name="data[ScheduleItem][' + line_item_id + '][ScheduleItemTask][' + id + '][order_line_item_task_id]">' + 
						'<label for="ScheduleItemTask' + id + '">' + task['order_line_item_task_name'] + '</label>' + 
					'</div>' + 
				'</div>' +
				'<div class="col-1of4">' +
					'Est. - ' + estimatedHours +
				'</div>' +
				'<div class="col-1of4">' +
					'<select id="status" name="data[ScheduleItem][' + line_item_id + '][ScheduleItemTask][' + id + '][status]" class="status schedule-status task-of-line-item-status-' + line_item_id + '">' + 
						'<option value="1" ' + scheduled + '>Scheduled</option>' + 
						'<option value="50" ' + in_process + '>In Process</option>' + 
						'<option value="100" ' + complete + '>Complete</option>' + 			
					'</select>' + 
				'</div>' +
			'</div>';
			
			if(task['visible'] == 1) {
				html_visible = html_visible + html;
			} else {
				html_invisible = html_invisible + html;
			}
		}
		return (html_visible + view_tasks + '<div class="hide" id="view-all-tasks-' + line_item_id + '_toggle_display">' + html_invisible + '</div>');
	}
	
	function constructScheduleWhatLineItem(lineItems) {
		/*
		['order_line_item_description']
		['order_line_item_scheduled_hours']
		['order_line_item_scheduling_complete']
		*/
		var html_visible = '',
			html_invisible = '',
			count = 0,
			count_checked = 0,
			checked = null,
			lineItem = null,
			estimatedHours = 0,
			visible = 'hide',
			id = 0,
			selected = '',
			scheduled = '',
			in_process = '',
			complete = '';
			
		for(var index in lineItems) {
			count = count + 1;
			lineItem = lineItems[index],
			checked = null;
			if(lineItem['selected'] == 1) {
				checked = 'checked="checked"';
				count_checked = count_checked + 1;
			}
			estimatedHours = 0;
			if(!(lineItem['order_line_item_hours']==null || lineItem['order_line_item_hours']===false)) {
				estimatedHours = lineItem['order_line_item_hours'];
			}
			visible = 'hide';
			if(lineItem['visible'] == 1) {
				visible = '';
			}
			id = lineItem['order_line_item_id'];
			if('schedule_item_id' in lineItem) {
				id = id + '-' + lineItem['schedule_item_id'];
			}
			/*
			selected = 'selected="1"';
			scheduled = '';
			in_process = '';
			complete = '';
			if(lineItem['schedule_item_status']) {
				switch (lineItem['schedule_item_status']) {
					case '50':
						in_process = selected;
						break;
					case '100':
						complete = selected;
						break;
					default:
						scheduled = selected;
				}
			}
			*/
			var html =	'';
			if($('#origination_module').val() == 'schedule') {
				html =	
				'<div id="line-item-checkbox-container-' + id + '" class="line-item-checkbox-container clear ' + visible + '">' +
					'<div class="grid">' +
						'<div class="col-3of4">' +
							'<div id="' + id + '" class="what-line-item-container checkbox">' + 
								'<input id="ScheduleItem' + id + '" ' + checked + ' type="checkbox" value="' + id + '" name="data[ScheduleItem][' + id + '][order_line_item_id]">' + 
								'<label for="ScheduleItem' + id + '">' + constructLineItemTitle(lineItem['order_line_item_name'] + '&nbsp;&nbsp;|&nbsp;&nbsp;' + lineItem['order_line_item_description'], 'line-item-checkbox-cluetip-' + id, 100) + '</label>' + 
							'</div>' +
						'</div>' +
						'<div class="col-1of4">' +
							'Est. - ' + estimatedHours + ' hrs.' + 
						'</div>' +
						/*
						'<div class="col-1of4">' +
							'<select value=" ' + lineItem['schedule_item_status'] + '" name="data[ScheduleItem][' + id + '][status]" class="schedule-status line-item-status" id="line-item-status-' + id + '">' + 
							'<option value="1" ' + scheduled + '>Scheduled</option>' + 
							'<option value="50" ' + in_process + '>In Process</option>' + 
							'<option value="100" ' + complete + '>Complete</option>' + 		 			
							'</select>' + 
						'</div>' +
						*/
					'</div>' +
				'</div>';
			} else {
				// in the Order module
				html =	
					'<div id="line-item-checkbox-container-' + id + '" class="line-item-checkbox-container clear ' + visible + '">' +
						'<div class="row">' +
							'<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">' +
								'<div id="' + id + '" class="what-line-item-container checkbox">' + 
									'<input id="ScheduleItem' + id + '" ' + checked + ' type="checkbox" value="' + id + '" name="data[ScheduleItem][' + id + '][order_line_item_id]">' + 
									'<label for="ScheduleItem' + id + '">' + lineItem['order_line_item_name'] + '</label>' + 
								'</div>' +
							'</div>' +	
							
							'<div class="col-xs-12 col-sm-8 col-md-9 col-lg-10">' +
								lineItem['order_line_item_description'] + '<br />' + '<br />' +  
							'</div>' +
							'<div class="col-xs-12 col-sm-4 col-md-3 col-lg-2">' +
								'Estimated Hours - ' + estimatedHours  + '<br />' + '<br />' + 
							'</div>' +
						'</div>' +
					'</div>';
			}
				
			// Check if there are any Line Item Tasks
			if(!$.isEmptyObject(lineItem['OrderLineItemTask'])) {
				var expand = 'hide';
				if(lineItem['expand'] == 1 || lineItem['visible'] == 1) {
					expand = '';
				}
				html = html + '<div id="line-item-task-checkbox-container-' + id + '" class="line-item-task-checkbox-container ' + expand + '">';
				html = html + constructScheduleWhatLineItemTasks(lineItem['OrderLineItemTask'], id);
				html = html + '</div>';
			}
			
			if(lineItem['visible'] == 1) {
				html_visible = html_visible + html;
			} else {
				html_invisible = html_invisible + html;
			}
		};
			
		var result = new Array();
		result['html'] = html_visible + html_invisible;
		result['count'] = count;
		result['selected_count'] = count_checked;
		return result;
	}
	
	function constructScheduleWhat(order_id, selected_order) {
		var yesno = new Array();
		yesno[0] = 'No';
		yesno[1] = 'Yes';
	
		// First get the basic order information.
		var html = '<input id="order_id" class="" type="hidden" name="data[Schedule][order_id]" value="' + selected_order['order_id'] + '">',
			lineItem = constructScheduleWhatLineItem(selected_order['OrderLineItem']),
			header_html = '<h1>What<div id="schedule_button_what" class="schedule_button collapse right">&nbsp;</div></h1>',
			hide = 'hide';
		
		html = html + lineItem['html'];
		
		// initializeClueTips();
		// Clear current schedule-what container.
		/*
		var selected = 'selected="1"',
			scheduled = '',
			in_process = '',
			complete = '';
		if(selected_order['schedule_status']) {
			switch (selected_order['schedule_status']) {
				case '50':
					in_process = selected;
					break;
				case '100':
					complete = selected;
					break;
				default:
					scheduled = selected;
			}
		}

		'<div class="grid">' + 
			'<div class="col-3of4">' + 
				'<h1>What<div id="schedule_button" class="schedule_button collapse right">&nbsp;</div></h1>' + 
			'</div>' +
			'<div class="col-1of4"><div>' +
				'<select name="data[Schedule][status]" class="schedule-status-select" id="schedule-status-select">' + 
					'<option value="1" ' + scheduled + '>Scheduled</option>' + 
					'<option value="50" ' + in_process + '>In Process</option>' + 
					'<option value="100" ' + complete + '>Complete</option>' + 		 			
				'</select>' + 
			'</div></div>' + 
		'</div>';
		 */
		
		if($('#origination_module').val() == 'order') {
			// Format for Order module.
			header_html = '';
			hide = '';
		}
		
		$('#order-schedule-container div#schedule-what').html(header_html);
		// Determine if a link to view all line items is required
		if(lineItem['count'] > 1 && (lineItem['count'] > lineItem['selected_count'])) {
			html = '<div class="view-all-line-items" id="view-all-line-items-' + order_id + '">view all items</div>' + html;
		}
		html = '<div id="schedule-what-container" class="schedule-what-container ' + hide + '">' + html + '</div>';
		$('#order-schedule-container div#schedule-what').append(html);
		return true;
	}
	
	function constructScheduleWho(view_type, current_users, mode) {
		// view_type will either be 'employee' or 'job_type'
		var by_employees = jQuery.data( document.body, "employees"),
			by_job_type = jQuery.data( document.body, "jobTypes"),
			html = '',
			arrayCount = 0,
			count = 0,
			i = 0;
		if(view_type == 'employee') {
			arrayCount = by_employees.length;
        	count = parseInt(arrayCount/2);
        	if((arrayCount % 2) > 0) {
        		count = count + 1;
        	}
        	count = count + 1;
        	html = html + '<div class="who-line-item-block">';
			for(var index in by_employees) {
				var id = by_employees[index]['id'],
					name = by_employees[index]['name'],
					checked = '';
				i = i + 1;
				if(i == count) {
					html = html + '</div><div class="who-line-item-block">';
				}
                if(current_users) {
                    $.each(current_users, function(key, data) {
                        if(!(data['job_type_id']) && data['id'] == id) {
                            // The user was not assigned to a job_type;
                            checked = 'checked=true';
                        }
                    });
                }
				html = html + 
				'<div id="' + id + '" class="who-line-item-container checkbox">' + 
					'<input id="ScheduleResource' + id + '" ' + checked + ' type="checkbox" value="' + id + '" name="data[ScheduleResource-User][' + id + '][User][id]">' + 
					'<label for="ScheduleResource' + id + '">' + name + '</label>' + 
				'</div>';
			}
        	html = html + '</div>';
			$('.schedule-who-employee').html(html);
			$('.schedule-who-jobtype').css('display', 'none');
			$('.schedule-who-employee').css('display', 'block');
		} else {
			// job_type
			for(var index in by_job_type) {
				var id = by_job_type[index]['JobType']['id'];
				var name = by_job_type[index]['JobType']['name'];
				var checked = '';
				if(current_users) {
                    // Detemine if the JobType is selected.
                    $.each(current_users, function(key, data) {
                        if(data['job_type_id'] && data['job_type_id'] == id) {
                            checked = 'checked=true';
                            return false; // Break out of the loop
                        }
                    });
                }
				html = html + 
				'<div id="' + id + '" class="who-line-item-container checkbox">' + 
					'<input id="sr-job-type-' + id + '" class="sr-job-type" ' + checked + ' type="checkbox" value="' + id + '" name="data[ScheduleResource-JobType][' + id + '][JobType][id]">' + 
					'<label for="sr-job-type-' + id + '">' + name + '</label><div class="toggle_display_button arrow-button collapse" id="users-for-jobtype-' + id + '"></div>' + 
				'</div>';
                /*
                 Create the user list under the Jobtype
                */
				html = html + '<div class="users-for-jobtype-container hide" id="users-for-jobtype-' + id + '_toggle_display">';
				if(!($.isEmptyObject(by_job_type[index]['User']))) {
					for(var index_user in by_job_type[index]['User']) {
						var id_user = by_job_type[index]['User'][index_user]['id'],
                            name_user = by_job_type[index]['User'][index_user]['name'],
                            checked_user = '',
                            user_checked = '';
                        
						if(mode == 'edit') {
							if(current_users) {
	                            // Detemine if the JobType is selected.
	                        	$.each(current_users, function(key, data) {
	                                if(data['job_type_id'] && data['job_type_id'] == id && data['id'] == id_user) {
	                                    user_checked = 'checked=true';
	                                }
	                            });
	                        }
						} else {
							user_checked = 'checked=true';
						}
                        
						html = html + 
						'<div id="' + id_user + '" class="what-line-item-container checkbox">' + 
							'<input id="ScheduleResource' + id_user + '" class="sr-user-for-job-type sr-user-for-job-type-' + id + '"' + checked_user + ' type="checkbox" ' + user_checked + ' value="' + id_user + '" name="data[ScheduleResource-JobType][' + id + '][User][' + id_user + '][id]">' + 
							'<label for="ScheduleResource' + id_user + '">' + name_user + '</label>' + 
						'</div>';
					}
				}
				html = html + '</div>';
			}
			$('.schedule-who-jobtype').html(html);
			$('.schedule-who-jobtype').css('display', 'block');
			$('.schedule-who-employee').css('display', 'none');
		}
		return true;
	}
	
	function constructDurationInput(duration) {
		var html = '<b>Duration</b>&nbsp;&nbsp;<input id="duration" value="' + duration + '" class="duration num_only" type="text" name="data[Schedule][duration]">&nbsp;hrs.';
		$('div#order-schedule-container #duration-container').html(html);
		return true;
	}
	
	function constructScheduleHidden(schedule_id) {
        var value_html = ''
        if(schedule_id) {
            value_html = 'value="' + schedule_id + '"';
        }
		var html = '<input id="ScheduleId" type="hidden" name="data[Schedule][id]" ' + value_html + '>';
		$('#order-schedule-container div#schedule-what').append(html);
	}
	
	function constructScheduleStartTime(start_time) {
		var parsed_start_time = formatTime(start_time),
			minutes_start = parsed_start_time['min'];
		/*
		 * $('#order-schedule-container input.hour_start').val(parsed_start_time['hour']);
		 * $('#order-schedule-container select.minute_start option[value = '+parseInt(parsed_start_time['min'])+ ']').attr('selected', true);
		 * $('#order-schedule-container select.minute_start option').attr('selected', false);
		 * $('#order-schedule-container select.post_meridiem_start option').attr('selected', false);
		 */
		
		/*
		var selected0 = '';
		var selected15 = '';
		var selected30 = '';
		var selected45 = '';
		switch(parseInt(parsed_start_time['min'])) {
			case 15:
				selected15 = 'selected=true';
				break;
			case 30:
				selected30 = 'selected=true';
				break;
			case 45:
				selected45 = 'selected=true';
				break;
			default:
				selected0 = 'selected=true';
		}
		*/
		var pm = '';
		var am = 'selected=true';
		if(parsed_start_time['post'] == 'pm') {
			pm = 'selected=true';
			am = '';
		} 
		//$('#order-schedule-container select.post_meridiem_start option[value = '+parsed_start_time['post']+ ']').attr('selected', true);
	
/* DELETE
var html = 
'<input id="hour_start" value="' + parsed_start_time['hour'] + '" class="time_input hour_start" type="number" name="data[Schedule][hour_start]">&nbsp;:' + 
'<select id="minute_start" class="time_input minute_start" name="data[Schedule][minute_start]">' + 
	'<option value="0" ' + selected0 + '>00</option>' + 
	'<option value="15" ' + selected15 + '>15</option>' + 
	'<option value="30" ' + selected30 + '>30</option>' + 
	'<option value="45" ' + selected45 + '>45</option>' + 
'</select>' + 
'<select id="post_meridiem_start" class="time_input post_meridiem_start" name="data[Schedule][post_meridiem]">' + 
	'<option value="am" ' + am + '>am</option>' + 
	'<option value="pm" ' + pm + '>pm</option>' + 
'</select>';
*/		
		var html = 
			'<input id="hour_start" value="' + parsed_start_time['hour'] + '" class="time_input hour_start num_only hours_only" type="number" name="data[Schedule][hour_start]">&nbsp;:&nbsp;' + 
			'<input id="minute_start" class="time_input minute_start minutes_only" name="data[Schedule][minute_start]" value="' + minutes_start + '">&nbsp;' + 
			'<select id="post_meridiem_start" class="time_input post_meridiem_start" name="data[Schedule][post_meridiem]">' + 
				'<option value="am" ' + am + '>am</option>' + 
				'<option value="pm" ' + pm + '>pm</option>' + 
			'</select>';
		$('div#order-schedule-container #start-time-container').html(html);
		return true;
	}
	
	function constructScheduleEndTime(time) {
		/*
		var time_format = formatTime(time);
		var html = 
		'<input id="time_end_display" value="' + time_format['hmp'] + '" class="time_end_display" type="text" readonly="readonly" name="data[Schedule][time_end_display]">' + 
		'<input id="time_end" class="time_end" type="hidden" readonly="readonly" name="data[Schedule][time_end]" value="' + time + '">';
		$('div#order-schedule-container #end-time-container').html(html);
		$('#order-schedule-container #time-end-select-container').css('display', 'none'); // Turn off the pop-up
		return true;
		
		*/
		
		
		var parsed_end_time = formatTime(time),
			minutes_end = parsed_end_time['min'],
			pm = '',
			am = 'selected=true';
		
		if(parsed_end_time['post'] == 'pm') {
			pm = 'selected=true';
			am = '';
		} 
		var html = 
			'<input id="hour_end" value="' + parsed_end_time['hour'] + '" class="time_input hour_end num_only hours_only" type="number" name="data[Schedule][hour_end]">&nbsp;:&nbsp;' + 
			'<input id="minute_end" class="time_input minute_end minutes_only" name="data[Schedule][minute_end]" value="' + minutes_end + '">&nbsp;' + 
			'<select id="post_meridiem_end" class="time_input post_meridiem_end" name="data[Schedule][post_meridiem_end]">' + 
				'<option value="am" ' + am + '>am</option>' + 
				'<option value="pm" ' + pm + '>pm</option>' + 
			'</select>';
		$('div#order-schedule-container #end-time-container').html(html);
		return true;
	
	
	}
    
    function constructScheduleUsersBlock(users) {
        var temp = new Array();
        // Loop through the users.  Construct an array by Job Types
        temp[0] = new Array();
        $.each(users, function(key, data) {
            if(!(data['job_type_id'])) {
                //temp[0][data['id']] = data['name'];
                temp[0].push(data['name']);
            } else {
                if(!(temp[data['job_type_name']])) {
                    // Create JobType array
                    temp[data['job_type_name']] = new Array();
                }
                temp[data['job_type_name']].push(data['name']);
            }
        });
        
        // Now loop through and create the html block
        var html = '';
        if(temp) {
            for(var index in temp) {
                if(index == 0) {
                    html = html + temp[index].join(", ") + '<br />';
                } else {
                    html = html + index + ' (' + temp[index].join(", ") + ')<br />';
                }
            }
        }
        return html;
    }
    
    function constructOrderList(sort_type, view_order_by) {
    	view_order_by = view_order_by || 'all';
    	
        // Build an array of Orders from the orders_detail data bank.
        // What we need to know for each order is the number of hours scheduled against it, the total estimated hours for it, and if any of the orders
        // have a schedule within the current view.
        var orders = jQuery.data( document.body, "orders_detail"),
            data = new Array();
        if(orders) {
            data = buildOrdersSummaryArray();
            switch(sort_type) {
                case 'request_date':
                    var sort = data.sort(function(a, b){
                                var keyA = a.request_date_sort,
                                    keyB = b.request_date_sort;
                                if(keyA < keyB) return -1;
                                if(keyA > keyB) return 1;
                                return 0;
                            });
                    break;
                default:
                    var sort = data.sort(function(a, b){
                                var keyA = a.name,
                                    keyB = b.name;
                                if(keyA.toLowerCase() < keyB.toLowerCase()) return -1;
                                if(keyA.toLowerCase() > keyB.toLowerCase()) return 1;
                                return 0;
                            });
            }
     
            /*
             * The data is now in the propper order.
             * Loop back through the array... building the List.
             */
            for(var index in sort) {
                $('#scheduled_jobs').append(constructOrderContainer(sort[index]));
            }
            
            /*
             * Once the order list is generated... determine which are visable.  all, unscheduled, or those onscreen
             */
            filterOrderListByViewBy(view_order_by);
            
        } else {
            // Display a message that no orders are present
            $('#scheduled_jobs').html('No Orders');
        }
    }
    function constructOrderContainer(data) {
    	var client_default_view_type = $('#default_view_type').val(),
            requestDate = '',
            classHasVisableSched = '',
            classUnscheduled = 'unscheduled'
        if(data['request_date']) {
            requestDate = data['request_date'];
        }
        if(data['order_has_schedules_in_view'] == 1) {
            classHasVisableSched = 'in-table';
        }
        if(data['status'] > 1) {
            classUnscheduled = '';
        }
        var html = 
        '<div id="order-container-' + data['order_id'] + '" class="order-container clear collapse ' + classHasVisableSched + ' ' + classUnscheduled + '" sort="">' + 
            '<div class="order-title">' + 
            	//'<div id="name" class="left">' + data['name'] + ' <br/><span class="schedule-tiny">(' + data['job_site_address'] + ')</span>' + '</div>' + 
            	'<div id="name" class="left">' + data['customer_name'] + ' <br/><span class="schedule-tiny">(' + data['job_site_address'] + ')</span>' + '</div>' + 
                //'<div id="order-button-' + data['order_id'] + '" class="order_button collapse right">&nbsp;</div>' + 
                '<div id="date"  class="right">' + 
					requestDate + 
				'</div>' + 
				'&nbsp;' + 
			'</div>' + 
			'<div id="order-button-' + data['order_id'] + '_toggle_display" class="order-detail-container clear hide">' + 
				'<div id="view-by-' + data['order_id'] + '" class="clear">' + 
					'<div id="" class="order-summary-view-container grid">' + 
                        '<div class="col-1of4">&nbsp;</div>' + 
                        '<div class="col-1of2">Est. - ' + Number(data['estimated_hours']).toFixed(2) + ' hrs. | Sched. - ' + data['scheduled_hours'] + ' hrs.</div>' +
                        '<div class="col-1of4">' + 
                            '<div class="action">&nbsp;' + 
								constructScheduleButton('order', data['order_id'], data['order_id']) +
								constructOrderScheduleStatus('order', data['order_id'], data['status']) +
                            '</div>' +
                        '</div>' +
                        '<div class="col-1of4 clear">&nbsp;</div>' + 
                        '<div class="col-1of2">&nbsp;</div>' +
                        '<div id="order-line-item-view-link-' + data['order_id'] + '" class="col-1of4 align_right order-line-item-view-link">schedule by items' + '</div>' +
                    '</div>' + 
                    '<div id="view-by-item-container-' + data['order_id'] + '" class="view-by-container hide">';
                    if(data['order_has_tasks'] == 0) {
                        //html = html + '<div id="name" class="left">view by units</div>';
                    } else {
                        var class_view_item = '';
                        var class_view_task = 'hide';
                        if(client_default_view_type == 'tasks') {
                            class_view_item = 'hide';
                            class_view_task = '';
                        } 
                        html = html + 
                        '<div id="order-view-type-item-' + data['order_id'] + '" class="order-view-type-item left ' + class_view_item + '">' + 
                            '<b>view by units</b> / <a id="toggle-order-view-type-to-task-' + data['order_id'] + '" class="toggle-order-view-type-to-task">view by tasks</a>' + 
                        '</div>' + 
                        '<div id="order-view-type-task-' + data['order_id'] + '" class="order-view-type-task left ' + class_view_task + '">' + 
                            '<a id="toggle-order-view-type-to-item-' + data['order_id'] + '" class="toggle-order-view-type-to-item">view by units</a>' + ' / <b>view by tasks</b>' + 
                        '</div>';
                    }
                    html = html + 
                    '&nbsp;' + 
                    '</div>' + 
                    '<div id="view-by-item-' + data['order_id'] + '" class="clear hide"></div>' + 
                    '<div id="view-by-task-' + data['order_id'] + '" class="clear hide"></div>' +    
                '</div>';
        
                if(schedule_display_placement == 'inline') {
                     html = html + 
                    '<form id="ScheduleIndexForm" class="ScheduleIndexForm standard schedule" accept-charset="utf-8" method="post" novalidate="novalidate" action="' + myBaseUrl + 'schedules/ajax_add">' +   
                        '<div style="display:none;"><input type="hidden" value="POST" name="_method"></div>' + 
                        '<div id="schedule-container-' + data['order_id'] + '" class="schedule-container clear"></div>' +
                    '</form>';
                }
            '</div>' + 
        '</div>';
        return html;
    }
    
    function resetYAxisRows (yElement_id) {
        // First.. Eliminate all but the first row
        $('table#time-' + yElement_id + ' tr.time-row-' + yElement_id).each( function() {
            if($(this).attr('id').replace('time-row-' + yElement_id + '-','') > 1) {
                $(this).remove();
            }
        });
        $('tr#time-row-' + yElement_id + '-1 td').each( function() {
            $(this).html('<div>&nbsp;</div>');
        });
    }
    
    function buildScheduleTableYaxisRow(yElement) {
        var workday_start_time = formatTime($('#workday_start').val()),
            workday_end_time = formatTime($('#workday_end').val()),
            screen_date_start = formatDate(deconstructSelectedDate($('#table_start_day').val())),
            screen_date_end = formatDate(deconstructSelectedDate($('#table_end_day').val())),
            schedule_type = $('input#display').val(),
            type =$('#yaxis_view_type').val();
        
        /*
         * We've now isolated the yElement (aka... employee).  Now loop through the schedules Finding each one that 
         * is assingned to the yElement and is marked as 'schedule_in_current_view'
         */
        var yElement_id = yElement['id'],
            schedules = jQuery.data( document.body, "schedules_detail"),
            processed_sched = new Array(),
            current_row = 1;
        resetYAxisRows(yElement_id);
        if(!$.isEmptyObject(schedules)) {
            $.each(schedules, function(key, schedule) { 
                // FIRST... determine if the schedule is in the current view.
                if(schedule['schedule_in_current_view'] == 1) {
                    var valid_sched = false,
                        print_label = false;
                    
                    // Now, are we looking for a yAxis of Employee or jobType?
                    if(type == 'employee' && schedule['assigned_to_foreign_key'] == yElement_id) {
                        valid_sched = true;
                    } else if (type == 'job-type' && schedule['assigned_to_job_type_id'] == yElement_id) {
                        valid_sched = true;
                    } else if (type == 'order' && schedule['order_id'] == yElement_id) {
                        valid_sched = true;
                    }
                    
                    // Check if the schedule_id is already in the processed_sched array.
                    if(valid_sched && !(schedule['schedule_id'] in processed_sched)) {
                        processed_sched[schedule['schedule_id']] = schedule['schedule_id'];
                    } else {
                        valid_sched = false;
                    }

                    if(valid_sched) {
                        /* 
                         * Great, we now have a schedule that should be displayed on the screen 
                         * Grab the data you need from it.
                         */
                        var startDate = formatDate(deconstructSelectedDate(schedule['date_session_start'])),
                            endDate = formatDate(deconstructSelectedDate(schedule['date_session_end'])),
                            startTime = formatTime(schedule['time_start']),
                            endTime = formatTime(schedule['time_end']),
                            daySpan = schedule['day_span'],  // Day span will be caclulated to inlude weekends
                            initialDate = new Date(startDate['year'],Number(startDate['month'])-1,startDate['day']),
                            stopDate = new Date(endDate['year'],Number(endDate['month'])-1,endDate['day']),
                            loopDate = new Date(startDate['year'],Number(startDate['month'])-1,startDate['day']),
                            schedule_starts_on_a_weekend = 0,
                            schedule_ends_on_a_weekend = 0,
                            weekend_offset = 0,
                            schedules_dedicated_row = 0,
                            cellCount = 0,
                            scheduled_hours = schedule['duration_in_seconds']/3600;
                        
                        switch(schedule_type) {
                            case 'week' :
                                // For a week view... minumum hours needed is 5
                                if(scheduled_hours >= 5) {
                                    print_label = true;
                                }
                                break;
                                
                            case 'month':
                                // For a month view... always false (for now)
                                print_label = false;
                                break;
                                
                            case 'day' :
                                // For a week view... minumum hours needed is 2
                                if(scheduled_hours >= 2) {
                                    print_label = true;
                                }
                                break;
                        }
                        if(print_label) {
                            var label = schedule['order_name'].substring(0, 5) + '...'; 
                        }
                    
                        /* Determine if the schedule starts on a weekend */
                        if (initialDate.getDay() == 6 || initialDate.getDay() == 0) {
                            schedule_starts_on_a_weekend = startDate['raw']; //1;  // Sunday.. 1 day in exception
                        }
                        /* Determine if the schedule ends on a weekend */
                        if (stopDate.getDay() == 6 || stopDate.getDay() == 0) {
                            //stopDate.setDate(stopDate.getDate()-1);
                            schedule_ends_on_a_weekend = stopDate['raw'];
                        }
                        
                        for(var day = 1; day <= daySpan; day++) {
                            if(day > 1) {
                                var day_index = ((day-1) * 86400000) + (weekend_offset * 86400000);
                                loopDate = new Date(initialDate.getTime() + day_index)
                            }
                            
                            // Is the loopDate in the range of the dates displayed on the screen
                            // Convert the loopDate to a text/number that can be compared to the screen_date_start/screen_date_end values
                            var loopDateFlat = flattenDateObject(loopDate);
                            if(loopDateFlat >= Number(screen_date_start['raw']) && loopDateFlat <= Number(screen_date_end['raw'])) {
                                /*
                                 * CHECK IF THE date IS A WEEKEND 
                                 * If the date is a weekend date (and the schedule does not include_weekends)... 
                                 * Increment the date till its a weekday.
                                 * UNLESS the endDate occurs during the weekend
                                 */
                                var dayOfTheWeek = loopDate.getDay(),
                                    isWeekend = (dayOfTheWeek == 6) || (dayOfTheWeek == 0),
                                    post = true;
                                    
                                // If the schedule allows weekends... Proceed normally, skipping the following.
                                if(isWeekend && (schedule['include_weekends'] == 0)) {
                                    post = false;
                                    // Does the day land on a start date?
                                    if((schedule_starts_on_a_weekend > 0) && (flattenDateObject(loopDate) == schedule_starts_on_a_weekend)) {
                                        schedule_starts_on_a_weekend = schedule_starts_on_a_weekend - 1;
                                        post = true;
                                    }
                                    // Are we approching the weekend 
                                    if((schedule_ends_on_a_weekend > 0) && (flattenDateObject(loopDate) == schedule_ends_on_a_weekend)) {
                                        post = true;
                                    }
                                }
                                if(post){
                                    /* We now have a date that is to be marked on the schedule table
                                     * Set the default time to 100
                                     */
                                    var time = Number(workday_start_time['hour24'].toString() + workday_start_time['min'].toString()),
                                        start_of_day_time = Number(workday_start_time['hour24'].toString() + workday_start_time['min'].toString()),
                                        end_of_day_time = Number(adjustedEndTime(workday_end_time)),
                                        continuing_marking_time = true;
                                    
                                    // Adjust the times depending on the day
                                    if(day == 1) { 
                                        // The very first day
                                        time = Number(startTime['hour24'].toString() + startTime['min'].toString()); 
                                        start_of_day_time = Number(startTime['hour24'].toString() + startTime['min'].toString()); 
                                    } 
                                    if(day == daySpan) { 
                                        end_of_day_time = Number(adjustedEndTime(endTime));
                                    }
                        
                                    while(continuing_marking_time) {
                                        continuing_marking_time = false;
                                        if(time <= end_of_day_time) {
                                            continuing_marking_time = true;

                                            // Embed so that a marker isn't placed at the end time.
                                            // sched-start, sched-end, sched-full
                                            var sched_class = 'sched sched-' + schedule['schedule_id'] + ' order-' + schedule['order_id'],
                                                sched_status = 'status-' + schedule['schedule_status'];
                                            if((day == 1) && (time == Number(startTime['hour24'].toString() + startTime['min'].toString()))) {
                                                sched_class = sched_class + ' sched-start';
                                            }
                                            if((day == daySpan) && (time == Number(adjustedEndTime(endTime)))) {
                                                sched_class = sched_class + ' sched-end';
                                            }
                                            sched_class = sched_class + ' ' + sched_status;
                                            var row_identifier = '',
                                                i_row = 1,
                                                cell = 'td#time-y-axis-id-' + yElement_id + '-' + loopDateFlat + '-' + time;
                                            if(schedule_type == 'week') {
                                                cell = 'td#time-y-axis-id-' + yElement_id + '-' + loopDateFlat + '-' + parseInt(time/100);
                                            }
                                            if(schedule_type == 'month') {
                                                cell = 'td#time-y-axis-id-' + yElement_id + '-' + loopDateFlat;
                                            }
                                            // Determine which row has space for the schedule... or does a new row need added? 
                                            if(schedules_dedicated_row == 0) {
                                                while(i_row <= current_row) {
                                                    if(schedules_dedicated_row == 0) {
                                                        row_identifier = 'tr#time-row-' + yElement_id + '-' + i_row;
                                                        if(!($(row_identifier + ' ' + cell + ' div').hasClass('sched'))) {
                                                            schedules_dedicated_row = i_row;
                                                        }
                                                    }
                                                    i_row = i_row + 1;
                                                }          
                                                if(schedules_dedicated_row == 0) {
                                                    // If no spots were found, Increase the current_row
                                                    current_row = current_row + 1;
                                                    schedules_dedicated_row = current_row;
                                                    var html = '<tr id="time-row-' + yElement_id + '-' + schedules_dedicated_row + '" class="time-row time-row-' + yElement_id + '">' + 
                                                        constructScheduleTableRow(yElement_id, schedules_dedicated_row) + 
                                                        '</tr>';
                                                    $('table#time-' + yElement_id).append(html);
                                                }
                                            }
                                            row_identifier = 'tr#time-row-' + yElement_id + '-' + schedules_dedicated_row;
                                            $(row_identifier + ' ' + cell + ' div').addClass(sched_class);
                                            $(row_identifier + ' ' + cell + ' div').attr('id', 'sched-'+schedule['schedule_id']+'-order-'+schedule['order_id']);
                                            $(row_identifier + ' ' + cell + ' div').attr('status', schedule['schedule_status']);
                                            
                                            cellCount = cellCount + 1;
                                            if(print_label && (cellCount == 2)) {
                                              
                                                var label_container = '<div class="abs-label">' + label + '</div>';
                                                //$(row_identifier + ' ' + cell + ' div').html(label_container); 
                                            }
                                        }
                                        time = incrementTime(time);
                                        if(time == 0) {
                                            continuing_marking_time = false;
                                        }
                                    }
                                }
                            } else if(loopDateFlat > Number(screen_date_start['raw'])) {
                                // The loop function has shot past the visible table... the rest can be skipped.
                                day = 999999;
                            }
                        } 
                    }
                }
            });
        }

        //$('td#y-axis-td-' + yElement_id).attr('rowspan', current_row);
        var height = $('td#y-axis-td-' + yElement_id).height();
        $('td#y-axis-td-' + yElement_id).height(height * current_row + (current_row - 1));
    }
    
    function buildScheduleTable() {
    	startLoaderSchedule();
        var type =$('#yaxis_view_type').val();
        switch(type) {
            case 'employee' :
                var yAxis = jQuery.data( document.body, "employees");
                break;
            case 'job-type' :
                var yAxis = structureJobTypes(jQuery.data( document.body, "jobTypes"));
                break;
            case 'order' :
                var yAxis = structureOrders(jQuery.data( document.body, "orders_detail"));
                break;
        }
        
        if(!$.isEmptyObject(yAxis)) {
            $.each(yAxis, function(key, yElement) {
                buildScheduleTableYaxisRow(yElement); 
            });
        }  
        initializeClueTips();
        $("div#time_line").scrollLeft(390);
        stopLoaderSchedule();
    }
	
    function adjustedEndTime(time) {
        var hour = Number(time['hour24']),
            min = Number(time['min']);
        
        if(min == 0) {
            min = 45;
            hour = hour - 1;
        } else {
            min = min - 15;
        }
        
        var newTime = hour.toString() + min;
        if(min == 0) {
            newTime = hour.toString() + '00';
        }
        return newTime;
    }
    
    function structureJobTypes(job_types) {
        var data = new Array(),
            i = 0;
        if(!$.isEmptyObject(job_types)) {
            $.each(job_types, function(key, job_type) {
                data[i] = new Array();
                data[i]['id'] = job_type['JobType']['id'];
                data[i]['name'] = job_type['JobType']['name'];
                data[i]['type'] = 'job-type';
                i++;
            });
        }
        return data;
    }
    
    function structureOrders(orders) {
        var data = new Array(),
            i = 0,
            clean = new Array();
        if(!$.isEmptyObject(orders)) {
            $.each(orders, function(key, order) {
                if(!(order['order_id'] in data)) {
                    data[order['order_id']] = new Array();
                    data[order['order_id']]['id'] = order['order_id'];
                    data[order['order_id']]['name'] = order['name'];
                    data[order['order_id']]['type'] = 'order';
                }
            });
        }
        
        if(!$.isEmptyObject(data)) {
            $.each(data, function(key,element) {
                if(element) {
                    clean[i] = new Array();
                    clean[i] = element;
                    i++;
                }
            });
        }
        return clean;
    }
    
    function incrementTime(time) {
        var hour = parseInt(time/100),
            min = time%100;
        
        min  = min + 15;
        var newTime = hour.toString() + min.toString();
        if(min == 60) {
            hour = hour + 1;
            newTime = hour.toString() + '00';
        }
        return newTime;
    }
    
    function hoverOverSchedule(schedule_id) {
        var id = $('.sched-' + schedule_id).attr('id');
        if($('.sched-' + schedule_id).length) {
            order_id = $('.sched-' + schedule_id).attr('id').split('-')[3];
            $('div.sched').removeClass('sched_hovered');
            $('div.sched-' + schedule_id).addClass('sched_hovered');
            formatOrderDisplay(order_id);
        }
    }
    
    $(document).on("mouseover", "td.time-y-axis-record", function() {
        $('div').removeClass('box_hl');
        
        if($(this).children('.sched').attr('id')) {
            var id = $(this).children('.sched').attr('id'),
                schedule_id = id.split('-')[1],
                order_id = id.split('-')[3];
            
            $('div.sched').removeClass('sched_hovered');
            $('div.sched-' + schedule_id).addClass('sched_hovered');
            if($('input#display').val() == 'day') {
                $('.minute_marker').removeClass('higlighted_time_cell');
                $('div.sched-' + schedule_id).each(function() {
                    $('#header_' + $(this).parent('td.time-y-axis-record').attr('id').split('-')[6]).addClass('higlighted_time_cell');
                });
            }
            
            /*
             * Orders... Execute a function that will format all the items related to the same order.  This isn't inline because the same functionality
             * will also be executed when a order is hovered on.
             */
            formatOrderDisplay(order_id);
            
            /*
             * Schedule... Take the selected schedule and obtian the schedule details for it.
             */
            constructScheduleSummaryDisplay(schedule_id);
            
        } else {
            if($('input#display').val() == 'day') {
                $(this).children('div').addClass('box_hl');
                $('.minute_marker').removeClass('higlighted_time_cell');
                $('#header_'+$(this).attr('id').split('-')[6]).addClass('higlighted_time_cell');
            }
            $('#schedule-table-sched-summary-container').css('display', 'none');
        }
    });
    
    $(document).on("mouseover", ".select-schedule-to-edit", function() {
        var id = $(this).attr('id').replace('select-schedule-to-edit-', '');
        hoverOverSchedule(id);
    });
    
    $(document).on("mouseleave", "#schedule-status-cluetip-container", function() {
        $(this).css('display', 'none');
    });
    
    $(document).on('mouseleave', '#order-schedule-status-cluetip-container', function() {
        $(this).css('display', 'none');
    });
    
    $(document).on('mouseleave', '#time-end-select-container', function() {
        $(this).css('display', 'none');
    });
    
    $(document).on("mousedown", "div.sched", function(event) {
        var schedule_id = $(this).attr('id').split('-')[1],
            order_id = $(this).attr('id').split('-')[3];
        
        switch (event.which) {
            case 1:
                $('#schedule-table-sched-summary-container').css('display','none'); 
                expand_schedule_order(order_id);
                editSchedule(schedule_id);
                break;
            case 3:
                var mouseX = event.pageX, 
                    mouseY = event.pageY;
                $('#schedule-status-cluetip-container').css('top', mouseY-10);
                $('#schedule-status-cluetip-container').css('left', mouseX);
                $('#schedule-status-cluetip-container').css('display', 'block');
                $('#schedule_status_sched_id').val(schedule_id);
                $('#schedule_status_order_id').val(order_id);
                setScheduleStatus(schedule_id, $(this).attr('status'));
                break;
            default:
                alert('You have a strange Mouse!');
        }
    });
    $(document).on('click', '.cluetip-schedule-status-update', function(event) {
        var id = this.id,
            params = id.split("|"),
            order_id = $(this).parents('.order-container').attr('id').replace('order-container-','');
        // Obtain the current status and model/foreign_key values.
        openOrderScheduleStatusContainer(params[0], params[1], params[2], order_id, event);
    });
    
    $(document).bind("contextmenu", function(event) {
        event.preventDefault();
        
        //$("<div class='custom-menu'>Custom menu</div>")
        //    .appendTo("body")
        //    .css({top: event.pageY + "px", left: event.pageX + "px"});
    });
    
    $(document).on('click', '.order-line-item-view-link', function(event) {
    	var id = $(this).attr('id').replace('order-line-item-view-link-','');
    	$('#view-by-item-' + id).toggle();
	});
    
    $(document).on("click", "div.order-filter", function() {
        $('div.order-filter').removeClass('bold');
        filterOrderListByViewBy($(this).attr('id'));
        $(this).addClass('bold');
    });
    
    function filterOrderListByViewBy(id) {
    	switch (id) {
	        case 'unscheduled':
	            $('div.order-container').css('display', 'none');
	            $('div.order-container.unscheduled').css('display', 'block');
	            break;
	        case 'onscreen':
	            $('div.order-container').css('display', 'none');
	            $('div.order-container.in-table').css('display', 'block');
	            break;
	        default :
	            $('div.order-container').css('display', 'block');
	    }
    }
    
    $(document).on("mouseover", "div.order-container", function() {
        var order_id = $(this).attr('id').replace('order-container-','');
        $(this).addClass('hl-all-for-order-2');
        $('div.order-' + order_id).addClass('hl-all-for-order-2');
    });
    $(document).on("mouseout", "div.order-container", function() {
        var order_id = $(this).attr('id').replace('order-container-','');
        $(this).removeClass('hl-all-for-order-2');
        $('div.order-' + order_id).removeClass('hl-all-for-order-2');
    });
    function formatOrderDisplay(order_id) {
        $('div.sched').removeClass('hl-all-for-order');
        $('div.order-' + order_id).addClass('hl-all-for-order');
        $('div.order-container').removeClass('hl-all-for-order');
        $('div#order-container-' + order_id).addClass('hl-all-for-order');
    }
    
    function constructScheduleSummaryDisplay(schedule_id) {
        startLoaderScheduleSummary();
        var schedule = buildScheduleArray(schedule_id),
            users = buildScheduleResourceArray(schedule['schedule_id']),
            users_block = constructScheduleUsersBlock(users),
            createdDate = formatDate(deconstructSelectedDate(schedule['status_created'])),
            startDate = formatDate(deconstructSelectedDate(schedule['date_session_start'])),
            startTime = formatTime(schedule['time_start']),
            endDate = formatDate(deconstructSelectedDate(schedule['date_session_end'])),
            endTime = formatTime(schedule['time_end']),
            duration = secondsTimeSpanToHMS(schedule['duration_in_seconds']);
        
        var html = '';
        html = 
            '<div class="grid">' +
                '<div class="col-1of1"><b class="inline">Schedule For</b>&nbsp;&nbsp;' + schedule['order_name'] + '</div>' +
                '<div class="col-1of4"><b class="inline">From</b>&nbsp;&nbsp;' + startDate['month'] + '/' + startDate['day'] + '/' + startDate['year'] + '&nbsp;&nbsp;' + startTime['hmp'] + '</div>' +
                '<div class="col-1of4"><b class="inline">To</b>&nbsp;&nbsp;' + endDate['month'] + '/' + endDate['day'] + '/' + endDate['year'] + '&nbsp;&nbsp;' + endTime['hmp'] + '</div>' + 
                '<div class="col-1of4">&nbsp;&nbsp;</div>' +
                '<div class="col-1of4"><b class="inline">Duration</b>&nbsp;&nbsp;' + duration['hour'] + ':' + duration['min'] + ' hrs.</div>' + 
            '</div>' + 
            '<div class="grid">' +
                '<div class="col-1of1">' + 
                    '<b class="inline">Assigned To</b>&nbsp;&nbsp;' + 
                    '<div class="inline">' + users_block + '</div>' + 
                '</div>' + 
            '</div>' + 
            '<div class="grid">' +
                '<div class="col-1of4"><b class="inline">Created</b>&nbsp;&nbsp;' + createdDate['month'] + '/' + createdDate['day'] + '/' + createdDate['year'] + '</div>' +
                '<div class="col-1of4"><b class="inline">By</b>&nbsp;&nbsp;' + schedule['created_by_name'] + '</div>' + 
                '<div class="col-1of4">&nbsp;&nbsp;</div>' +
                '<div class="col-1of4"><b class="inline">Status</b>&nbsp;&nbsp;' + schedule['schedule_status_name'] + '</div>' +
            '</div>';
        $('#schedule-table-sched-summary-container').html(html);
        $('#schedule-table-sched-summary-container').css('display', 'block');
        stopLoaderScheduleSummary();
    }
    
	/**
	 * AJAX FORM SUBMIT
	 */
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

	// Bind to the form's submit event 
    $('.ScheduleIndexForm').submit(function() { 
    	startLoaderOrder();
    	// Clear all (previous) Validation Errors
		$('.error-message').each( function() {
			$(this).html('');
		});
		
		// VALIDATE
	    //var result = validate($(this));
	    var result = true;
		//if(result['valid']) { 
		if(true) {
			// inside event callbacks 'this' is the DOM element so we first 
	        // wrap it in a jQuery object and then invoke ajaxSubmit.
	        $(this).ajaxSubmit(options); 
	        
		} else {
			// Display Validation Errors
			for(var index in result) {
				$(this).find('#error-message-'+index).html(result[index]);
			}
			$(this).find('input[type="submit"]').attr('disabled',false);
		}
		
		// !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
		return false;
    }); 
	
    function showResponse(responseText, statusText, xhr, $form)  { 
	    // for normal html responses, the first argument to the success callback 
	    // is the XMLHttpRequest object's responseText property 
	 
	    // if the ajaxForm method was passed an Options Object with the dataType 
	    // property set to 'xml' then the first argument to the success callback 
	    // is the XMLHttpRequest object's responseXML property 
	 
	    // if the ajaxForm method was passed an Options Object with the dataType 
	    // property set to 'json' then the first argument to the success callback 
	    // is the json data object returned by the server 
	    //$('#ajax-return').html(responseText);
		obj = jQuery.parseJSON(responseText);
		var success = obj.success,
            error = obj.error,
            message = obj.message,
            order_id = obj.order_id,
            schedule_id = obj.schedule_id,
            deviceDisplay = $('input#device_display').val();
		
		// What to do now.  Standard use will proceed as normal, staying on the page and refresheing.
		// Mobile devices should redirect the user back to the Order::schedules page.
		if(deviceDisplay == 'mobile') {
			var order_id = $("input#selected_order").val();
			if(order_id.length) {
				$('#page-loader').css('display', 'block');
				window.location.href = myBaseUrl + "orders/schedules/" + order_id + '/';
			}
		} else {
			if(success == 1) {
	            // On success... store the schedule_id.
	            $('.schedule-container #ScheduleId').val(schedule_id);
	            // Activate the delete button
	            $('.schedule-container #delete_schedule').css('display', 'block');
			}
	        constructScheduleActionMessage(success, message);
			
			$.getJSON( myBaseUrl + "schedules/ajax_order_schedule_status/" + order_id, function( data ) {
				// Upon receiving the upated data for the orders_schedule_status, grab the array from the JQuery.data,
				// Update the array, and re-store the values.
				var result = jQuery.data( document.body, "orders_schedule_status");
				
				// Loop through the returned data
	            if(!result) {
	                result = data;
	            } else {
	                if(data) {
	                    $.each(data, function(key, element) {
	                        result[key] = element;
	                    });
	                }
	            }
				jQuery.data(document.body, "orders_schedule_status", result);
			})
			$.getJSON( myBaseUrl + "schedules/ajax_schedule_details/" + order_id, function( data ) {
				var result = jQuery.data( document.body, "schedules_detail");
				
				// Loop through the returned data
	            if(!result) {
	                result = data;
	            } else {
	                if(data) {
	                    $.each(data, function(key, element) {
	                        result[key] = element;
	                    });
	                }
	            }
				jQuery.data( document.body, "schedules_detail", result);
	            // For now... Call the method that rebuilds the entire schedule table.
	            buildScheduleTable();
	            
			});
			$.getJSON( myBaseUrl + "schedules/ajax_order_details/" + order_id, function( data ) {
				var result = jQuery.data( document.body, "orders_detail");
				
				// Loop through the returned data
	            if(!result) {
	                result = data;
	            } else {
	                if(data) {
	                    $.each(data, function(key, element) {
	                        result[key] = element;
	                    });
	                }
	            }
				jQuery.data( document.body, "orders_detail", result);
			});	
			
			initializeOrder(order_id);
			turnScheduleOff(order_id);
			stopLoaderOrder();
		}
	} 
});