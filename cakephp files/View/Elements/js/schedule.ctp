<?php #$this->Html->script('jquery/jquery.form.min', array('inline' => false)); ?>
<script type="text/javascript">
	var basic_shedule_frame = '<div id="date_session"></div><div id="time_start"></div><div id="time_end"></div><div id="time_start_display"></div><div id="time_end_display"></div><div id="date_session_display"></div><div id="order_id"></div><div id="worker_id"></div><div id="creator_id"></div><div id="modified"></div><div id="sections"></div><div id="time_worked"></div><div id="tasks"></div>';

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
	    $('.scheduleJobForm').submit(function() { 
	    	$('div#ajax_loader_schedule').css('display', 'block');
	    	// Clear all (previous) Validation Errors
			$('.error-message').each( function() {
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
				$('div#ajax_loader_schedule').css('display', 'none');
				$(this).find('input[type="submit"]').attr('disabled',false);
			}
			 
			// !!! Important !!! 
	        // always return false to prevent standard browser submit and page navigation 
			return false;
	    }); 
	}); 

	function populateDisplay(jquery_id, sched_id, css_val, sections, order_id) {
		var css = 'status-3';
		switch(css_val) {
			case 'current' :
				css = 'status-2';
			  	break;
			case 'past' :
				css = 'status-1';
			  	break;
			default:
				css = 'status-3';
		}
		$('#'+jquery_id).addClass(css);
		var opacity = '1';
		if($('input#order_id').length && order_id.length) {
			if(order_id != $('input#order_id').attr('value')) {
				opacity = '0.4';
			}
		}
		$('#'+jquery_id).css('opacity', opacity);
		$('#'+jquery_id+' div').attr('id', sched_id);
		$('#'+jquery_id+' div').addClass('schedule_worker_marker_'+sched_id);
		if(sections > 1) {
			var object = $('#'+jquery_id).next();
			for (iCount = 1; iCount < sections; iCount++) {
				object.addClass(css);
				object.css('opacity', opacity);
				object.children(":first").attr('id', sched_id);
				object.children(":first").addClass('schedule_worker_marker_'+sched_id);
				if((iCount + 1) == sections) {
					object.addClass('border_right');
				}
				object = object.next();
			}			
		}		
	}
	 
	function validate(form_obj) {
		var valid = true; 
	    var data = new Array();
	    var error = new Array();
	    data['ScheduleOrderId'] = form_obj.find('#ScheduleOrderId').attr('value');
	    data['ScheduleOrders'] = form_obj.find('#ScheduleOrders').attr('value');
	    data['ScheduleScheduleId'] = form_obj.find('#ScheduleScheduleId').attr('value');
	    data['ScheduleEmployee'] = form_obj.find('#ScheduleEmployee').attr('value');
	    data['ScheduleDateSession'] = form_obj.find('#ScheduleDateSession').attr('value');
	    data['hour_start'] = form_obj.find('#hour_start').attr('value');
	    data['minute_start'] = form_obj.find('#minute_start').attr('value');
	    data['post_meridiem'] = form_obj.find('#post_meridiem_start').attr('value');  
	   	data['ScheduleTimeEndDisplay'] = form_obj.find('#ScheduleTimeEndDisplay').attr('value');
	   	data['ScheduleTimeEnd'] = form_obj.find('#ScheduleTimeEnd').attr('value');

		/**
		* Order (Job)
		* Verify that an order_id is present
		*/
		if(!data['ScheduleOrderId'].length){
			valid = false;
			error['ScheduleOrderId'] = 'Error:  A <?php echo Configure::read('Nomenclature.Order');?> must be selected.';
		}

		/**
		* User (Employee/Worker)
		*/
		if(!data['ScheduleEmployee'].length){
			valid = false;
			error['ScheduleEmployee'] = 'Error:  A <?php echo Configure::read('Nomenclature.Employee');?> must be selected.';
		}

		/**
		* Time Start
		*/
		if((!data['hour_start'].length) || (!data['post_meridiem'].length)){
			valid = false;
			error['hour_start'] = 'Error:  A Start time must be entered.';
		}
		var start_time = constructStartTime(data['hour_start'], data['minute_start'], data['post_meridiem']);

		//(parseInt(data['hour_start']*100)) + parseInt(data['minute_start']);
		//if(data['post_meridiem'] == 'pm') {
		//	start_time = parseInt(start_time) + 1200;
		//}
		// Start time must be less than the end time
		if(start_time > data['ScheduleTimeEnd']) {
			valid = false;
			error['hour_start'] = 'Error:  Time conflict (Start time must be before end time).';
		}
		
		/**
		* Time End
		*/
		if(!data['ScheduleTimeEnd'].length){
			valid = false;
			error['ScheduleTimeEnd'] = 'Error:  An End time must be selected.';
		}
		error['valid'] = valid;
	    return error; 
	} 
	 
	// post-submit callback 
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
		var schedules = obj.schedules;
		var order = obj.order;
		var success = obj.success;
		var error = obj.error;
		
	    ////////////////////////////////////////////
	    // Loop through each of the schedules returned
	    // For each schedule, update the schedule_sessions within the data-bank as well
	    // as update the color blocks within the display
	    jQuery.each(schedules, function(i, val) {
			var id = i;

			// DATA BANK //
		  	// Determine if the schedule exists within the databank
			if(!$('div#data_bank div#schedule_sessions div#schedule_'+id).length) {
				// create the basic framework for a schedule_session within the databank
				var shedule_frame = '<div id="schedule_'+id+'">' + basic_shedule_frame + '</div>';
				$('div#data_bank div#schedule_sessions').append(shedule_frame);
			} 
			
			// Update each of the values within the databank
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#time_start').attr('value', val.time_start);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#time_end').attr('value', val.time_end);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#time_start_display').attr('value', val.time_start_display);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#time_end_display').attr('value', val.time_end_display);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#date_session').attr('value', val.date_session);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#date_session_display').attr('value', val.date_session_display);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#order_id').attr('value', val.order_id);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#worker_id').attr('value', val.worker_id);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#creator_id').attr('value', val.creator_id);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#modified').attr('value', val.modified);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#sections').attr('value', val.sections);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#time_worked').attr('value', val.time_worked);
			$('div#data_bank div#schedule_sessions div#schedule_' + id + ' div#tasks').attr('value', val.ScheduleSessionsTask);

			// BROWSER DISPLAY
			// First, clear all current blocks for this schedule.  This is required in case the times have changed.
			$('div.schedule_worker_marker_'+id).each(function() {
				$(this).parent().removeClass('status-1 status-2 status-3 border_right');
				$(this).removeClass('schedule_worker_marker_'+id);
				$(this).attr('id', '');
			});
			// Repopulate Display
			// Execute the same functionality as did when the page was loaded.
			// parameters: session_id, jquery_id, css (status), sections
			var jquery_id = 'employee_id_'+val.worker_id+'_'+val.date_session+'_'+val.time_start;
			populateDisplay(jquery_id, id, val.css, val.sections, val.order_id);		
		});


		/////////////////////////////////////////////////////////////////////
	    // Loop through each of the orders returned (should be only one)
	    // For each order, update the orders within the data-bank as well
	    // as update the percent and hours (if displayed)
	    jQuery.each(order, function(i, order_val) {
	    	$('div#data_bank div#orders div#order_' + i + ' div#total_estimated_hours').attr('value', order_val.total_estimated_hours);
			$('div#data_bank div#orders div#order_' + i + ' div#total_estimated_minutes').attr('value', order_val.total_estimated_minutes);
			$('div#data_bank div#orders div#order_' + i + ' div#total_scheduled_minutes').attr('value', order_val.total_scheduled_minutes);
			$('div#data_bank div#orders div#order_' + i + ' div#total_scheduled_hours').attr('value', order_val.total_scheduled_hours);

			// Determine if the order time and percentage needs updated.
			if($('input#order_id').length) {
				$('img#status_pie').attr('src', myBaseUrl+'img/pie/'+order_val.pie+'.png');
				$('#scheduled_hours').html(order_val.total_scheduled_hours);
			}
	    });

	    $('div#ajax_loader_schedule').css('display', 'none');
		
	    if(!error || error.length == 0) {
	    	//$('.clicktip-schedule').cluetip.cluetipClose();
	    	$(document).trigger('hideCluetip')
		}
	} 
</script>

<script type="text/javascript">
	$(document).ready(function(){
		$("div#time_line").scrollLeft(390);
		
		$('.jobs a.button').bind('click', function(){
			$('.jobs').each(function() {
	      	  $(this).toggle();
	        });
			
			return false;
		});
		
		$('.employee_record_deactivate').bind('click', function(){
			var employee_id = $(this).attr('id').split('_')[2];
			var schedule_id = $(this).children(":first").attr('id');

			// Get child data
			var data = new Array();
			data[0] = $('#employee_schedule_data_' + employee_id + '_' + schedule_id + ' div#address').html();
			data[1] = $('#employee_schedule_data_' + employee_id + '_' + schedule_id + ' div#time_start').html();
			data[2] = $('#employee_schedule_data_' + employee_id + '_' + schedule_id + ' div#time_stop').html();

			alert(data[0]);
		});
		
		$('.employee_record').hover(
			function () {
				$('.minute_marker').each( function() {
					$(this).removeClass('higlighted_time_cell');
				});
				
				$('#header_'+$(this).attr('id').split('_')[4]).addClass('higlighted_time_cell');
			}
		);

		function formatTime(time) {
		    var hh = parseInt(time/100);
		    var m = time%100;
		    var dd = "am";
		    var h = hh;
		    if (h >= 12) {
		        h = hh-12;
		        dd = "pm";
		    }
		    if (h == 0) {
		        h = 12;
		    }
		    m = m<10?"0"+m:m;
		    /* if you want 2 digit hours:
		    h = h<10?"0"+h:h; */

		   // var pattern = new RegExp("0?"+hh+":"+m);

		    replacement = new Array();
		    replacement['hour'] = h
		    replacement['min'] = m
		    replacement['post'] = dd;
		    replacement['hmp'] = h + ':' + m + ' ' + dd;
		    return replacement;
		}

		function deconstructSelectedDate(date) {
			// Date will always be in the form 'yyyy-mm-dd'
		    var y = date.substring(0,4);
		    var m = date.substring(5,7);
		    var d = date.substring(8);

			result = new Array();
			result['year'] = y;
			result['month'] = m;
			result['day'] = d;
			result['raw'] = y+m+d;
		    return result['raw'];
		}

		function formatDate(date) {
			// Date will always be in the form 'yyyymmdd'
		    var y = date.substring(0,4);
		    var m = date.substring(4,6);
		    var d = date.substring(6);

			result = new Array();
			result['year'] = y;
			result['month'] = m;
			result['day'] = d;
			result['raw'] = date;
		    return result;
		}

		function getScheduleData(schedule_id) {
			var schedule_str = 'div#schedule_sessions div#schedule_' + schedule_id + ' ';
			var schedule = new Array();

			if($(schedule_str).length) {
				// Collect Order information
				schedule['date_session'] = $(schedule_str + 'div#date_session').attr('value');
				schedule['time_start'] = $(schedule_str + 'div#time_start').attr('value');
				schedule['time_end'] = $(schedule_str + 'div#time_end').attr('value');
				schedule['time_start_display'] = $(schedule_str + 'div#time_start_display').attr('value');
				schedule['time_end_display'] = $(schedule_str + 'div#time_end_display').attr('value');
				schedule['date_session_display'] = $(schedule_str + 'div#date_session_display').attr('value');
				schedule['order_id'] = $(schedule_str + 'div#order_id').attr('value');
				schedule['worker_id'] = $(schedule_str + 'div#worker_id').attr('value');
				schedule['creator_id'] = $(schedule_str + 'div#creator_id').attr('value');
				schedule['modified'] = $(schedule_str + 'div#modified').attr('value');
				schedule['sections'] = $(schedule_str + 'div#sections').attr('value');
				schedule['tasks'] = $(schedule_str + 'div#tasks').attr('value');
				schedule['time_worked'] = $(schedule_str + 'div#time_worked').attr('value');
			} 
			
			return schedule;
		}
		
		function getOrderData(order_id) {
			var order_str = 'div#orders div#order_' + order_id + ' ';
			var order = new Array();

			if($(order_str).length) {
				// Collect Order information
				order['name'] = $(order_str + 'div#name').attr('value');
				order['customer_name'] = $(order_str + 'div#customer_name').attr('value');
				order['contact_name'] = $(order_str + 'div#contact_name').attr('value');
				order['contact_phone'] = $(order_str + 'div#contact_phone').attr('value');
				order['description'] = $(order_str + 'div#description').attr('value');
				order['total_estimated_hours'] = $(order_str + 'div#total_estimated_hours').attr('value');
				order['total_estimated_minutes'] = $(order_str + 'div#total_estimated_minutes').attr('value');
				order['total_scheduled_minutes'] = $(order_str + 'div#total_scheduled_minutes').attr('value');
				order['total_scheduled_hours'] = $(order_str + 'div#total_scheduled_hours').attr('value');
				order['line1'] = $(order_str + 'div#line1').attr('value');
				order['line2'] = $(order_str + 'div#line2').attr('value');
				order['city'] = $(order_str + 'div#city').attr('value');
				order['st_prov'] = $(order_str + 'div#st_prov').attr('value');
				order['zip_post'] = $(order_str + 'div#zip_post').attr('value');
				order['country'] = $(order_str + 'div#country').attr('value');
			} 
			
			return order;
		}

		function getEmployeeData(worker_id) {
			var employee_str = 'div#employees div#employee_' + worker_id + ' ';
			var employee = new Array();

			if($(employee_str).length) {
				// Collect Order information
				employee['id'] = worker_id;
				employee['name'] = $(employee_str + 'div#name').attr('value');
			} 
			
			return employee;
		}

		function constructTimeList(start, stop) {
			var hh = parseInt(start/100);
			var m = start%100;
			var time_inc = 0;
			var str = '';
			time = start;
			var i = 0;
			while(parseInt(time) < parseInt(stop)) {
				time_inc = time_inc + 15;
				if((m + 15) == 60) {
					hh = hh + 1;
					m = 0;
				} else {
					m = m + 15;
				}

				var displayClass = 'hide_quarter_slot';
				if((m == 0 || m == 30) && i < 20) {
					displayClass = '';
				}
				
				time = (hh*100)+m;
				if (time_inc < 60) {
					time_inc_display = time_inc + ' minutes';
				} else {
					time_inc_display = time_inc/60 + ' hour(s)';
				}
				
                // Include the time if it is less than the stop time
                if(parseInt(time) <= parseInt(stop)) {
                	display = formatTime(time)
                    str = str + '<tr id="' + time + '" class="' + time + ' ' + displayClass + '"><td class="first">&nbsp;&nbsp;&nbsp;</td><td>' + display['hmp'] + '</td><td class="time_increment">' +  time_inc_display + '</td></tr>';
                }
                i = i + 1;
			}
			
			return str;
		}
		
		function findNextScheduleStartTime(selected_element_id) {
			var sched_str = '#'+selected_element_id;

			// Test if the next element exists
			if($(sched_str).length) {
				if($(sched_str).hasClass('status-1') || $(sched_str).hasClass('status-2') || $(sched_str).hasClass('status-3')) {
					return $(sched_str).attr('id').split('_')[4];
				} else {
					var next_sched = $(sched_str).next();
					var test_the_next_element = true;
					while(test_the_next_element) {
						// check if the object exists -- if not the end has been reach - no more schedules found.
						if(!next_sched.length) {
							return 2400;
						}

						// Check if the object has a schedule-session indicator.
						if(next_sched.hasClass('status-1') || next_sched.hasClass('status-2') || next_sched.hasClass('status-3')) {
							return next_sched.attr('id').split('_')[4];
						}
						next_sched = next_sched.next();
					}
				}
			} else {
				return 2400;
			}
		}

		function findNextScheduleStartTimeFromSchedule(schedule_id) {
			var schedule = getScheduleData(schedule_id);
			var sched_str = 'employee_id_' + schedule['worker_id'] + '_' + schedule['date_session'] + '_' + schedule['time_end'];
			return findNextScheduleStartTime(sched_str);
		}

		/**
		*	Construct a Scheduling form for an existing Session.
		*/
		function displayExistingSessionForm(schedule_id) {
			// Using the schedule_id, Obtain all the data for that schedule_session... Order...
			// Access the schedule data bank to obtain data.
			var schedule = getScheduleData(schedule_id);
			var order = getOrderData(schedule['order_id']);
			var employee = getEmployeeData(schedule['worker_id']);

			// Because the Schedule Session exists, obtain the schedule_session_id and Job information
			$('input#ScheduleScheduleId').val(schedule_id);
			setJobData(schedule['order_id'], order['name'], schedule['tasks']);
			
			// Assigned Employee
			$('select.employee_select option').attr('selected', false);
			$('select.employee_select option[value = '+schedule['worker_id']+']').attr('selected', true);
			
			// Session Date - Freeze.  Do not let the user adjust.  User will have to delete to reschedule on a different date.
			setSessionDate(schedule['date_session'], 1)
			
			// Start time is the time grabbed from the databank -- Not the cell selected --
			setStartTime(schedule['time_start']);

			// Construct the end time select box.
			setEndTime(schedule_id, schedule['time_end']);
			
			// Generate a list of times the user can select from of the time_end value.
			// This requires finding when the next schedule starts (if one does)
			var stop = findNextScheduleStartTimeFromSchedule(schedule_id);
			var list = constructTimeList(schedule['time_start'], stop);
			$('#time-end-select').html(list);

			// Determine if the User can delete the session.
			$('#delete_container').css('display', 'none');
			if(schedule['time_worked'] == 0) {
				// There is no time marked... Schedule can be deleted.
				$('#delete_container').css('display', 'inline-block');
			}
		}

		function setJobData(order_id, order_name, selected_tasks) {
			// Display the Order (Job) Input box.  Populate with name and disable it.
			$('input#ScheduleJob').css('display', 'block');
			$('input#ScheduleJob').val(order_name);
			$('input#ScheduleJob').attr('disabled', 'disabled');

			// Turn off the Order Dropdown box - User doesn't have the ablility to change the job.
			$('select.order_select').css('display', 'none');

			// Set the order_id in a hidden field.
			$('input#ScheduleOrderId').val(order_id);

			// Populate the Order Line Item Container
			getOrderLineItems(order_id, selected_tasks);
		}

		function getOrderLineItems(order_id, selected_tasks) {
			var element = '';
			var block_start = '<div class="checkbox">';
			var label_start = '<label for="ScheduleTask1">';
			var label_end = '</label>';
			var block_end = '</div>';
			var checked = '';
			/*
			'<div class="checkbox">'.
			'<input id="ScheduleTask1" type="checkbox" value="1" name="data[Schedule][task][]">'.
			'<label for="ScheduleTask1">'. Install new main panel . '</label>'.
			'</div>';
			*/
			var task_array = new Array();
			if(selected_tasks && selected_tasks.length > 0) {
				task_array=selected_tasks.split(",");
			}
			
			// Clear Order Line Item Container
			$('div#order_line_item_container').html('');
			var order_line_items = $('div#data_bank div#orders div#order_'+order_id+' div#order_line_items');
			if(order_line_items.length > 0) {
				$('div#order_line_item_container').html('<div class="label">Assign To Tasks</div>');
				order_line_items.find('div.order').each(function() {
					checked = '';
					if(jQuery.inArray($(this).attr('id'), task_array) > -1) {
						checked = ' checked="checked"';
					}
			       	element = 
			        	'<div class="checkbox clear">' + 
			        	'<input id="ScheduleTask' + $(this).attr('id') + '" type="checkbox" value="' + $(this).attr('id') + '" name="data[Schedule][task][]"' + checked + '>' + 
			        	'<label for="ScheduleTask1">' + $(this).find('div#name').attr('value') + '</label>' + 
				        '</div>';

			        $('div#order_line_item_container').append(element);
			    });
			}
		}

		function enableJobSelect() {
			// Turn off the Order (Job) drop-down box.  Order unknown
			$('input#ScheduleJob').css('display', 'none');

			// Clear the Order_id within the hidden field (No Order selected yet.
			$('input#ScheduleOrderId').val('');
			
			// Turn on the Order Dropdown box - User has the ablility to change the job.
			$('select.order_select').css('display', 'block');
			$('select.order_select option').attr('selected', false);
		}
		
		/**
		*	Construct a Scheduling form for a blank section (WHITE Block... no existing schedule_session
		*	Order (Job) is known
		*/
		function displayScheduleForOrderForm(order_id, selected_element_id) {
			var order = getOrderData(order_id);
			var employee = getEmployeeData(selected_element_id.split('_')[2]);
			var date = formatDate(selected_element_id.split('_')[3]);
			var start_time = selected_element_id.split('_')[4];

			// White Block selected... no current schedule session.
			$('input#ScheduleScheduleId').val('');
			
			// Assign Order (Job) information
			setJobData(order_id, order['name']);
			
			// Assigned Employee
			$('select.employee_select option').attr('selected', false);
			$('select.employee_select option[value = '+employee['id']+']').attr('selected', true);
			
			// Session Date - Comes from the element clicked.
			setSessionDate(date['raw'], 1)

			// Start time is the time from the cell selected --
			setStartTime(start_time);

			// Construct the end time select box.
			// This requires finding when the next schedule starts (if one does).  To do this,
			// execute the findNextScheduleStartTime for the element slected to find when next schedule starts (if one does)
			var stop = findNextScheduleStartTime(selected_element_id);
			// Use the next schedule time as the end time if the next schedule time is less than one hour from the schedules
			// start time.
			if(stop < (parseInt(start_time) + 100)) {
				setEndTime(null, stop);
			} else {
				// Default to one hour ahead.
				if((parseInt(start_time)+100) < 2500) {
					setEndTime(null, parseInt(start_time) + 100);
				} else {
					setEndTime(null, start_time);
				}
			}

			// Generate a list of times the user can select from of the time_end value.  Ending at when the next schedule starts.
			var list = constructTimeList(start_time, stop);
			$('#time-end-select').html(list);

			// New Session.... No Delete.
			$('#delete_container').css('display', 'none');
		}
		
		/*
		 * 	Here is the case when a user has selected an empty session (White Block) for all orders (User is not Scheduling for a specific Order)
		 *	User should be able to assign session for any Order and assign to any employee (not just one selected)
		 */
		function displayScheduleFormFromGrid(selected_element_id) {
			// This option will give the user to select a Job (Order) from a select box
			var employee = getEmployeeData(selected_element_id.split('_')[2]);
			var date = formatDate(selected_element_id.split('_')[3]);
			var start_time = selected_element_id.split('_')[4];

			// White Block selected... no current schedule session.
			$('input#ScheduleScheduleId').val('');
			
			// Enable the Select Drop-down.
			enableJobSelect();
			
			// Assigned Employee
			$('select.employee_select option').attr('selected', false);
			$('select.employee_select option[value = '+employee['id']+']').attr('selected', true);
			
			// Session Date - Comes from the element clicked.
			setSessionDate(date['raw'], 1)

			// Start time is the time from the cell selected --
			setStartTime(start_time);

			// Initialize Order Line Items.
			$('div#order_line_item_container').html('');

			// Construct the end time select box.
			// This requires finding when the next schedule starts (if one does).  To do this,
			// execute the findNextScheduleStartTime for the element slected to find when next schedule starts (if one does)
			var stop = findNextScheduleStartTime(selected_element_id);
			// Use the next schedule time as the end time if the next schedule time is less than one hour from the schedules
			// start time.
			if(stop < (parseInt(start_time) + 100)) {
				setEndTime(null, stop);
			} else {
				// Default to one hour ahead.
				if((parseInt(start_time)+100) < 2500) {
					setEndTime(null, parseInt(start_time) + 100);
				} else {
					setEndTime(null, start_time);
				}
			}

			// Generate a list of times the user can select from of the time_end value.  Ending at when the next schedule starts.
			var list = constructTimeList(start_time, stop);
			$('#time-end-select').html(list);

			// New Session... No Delete.
			$('#delete_container').css('display', 'none');
		}

		/*
		 *	Current action that require this function:
		 *	User Selects the Schedule button when within an Order.	 
		 */
		function displayScheduleFormForOrderNoGrid() {
			if(!$('#order_id').val().length) {
				// Here is the case when a user has selected an empty session for all orders.
				//*******  As of now, the application doesn't provide the user a Schedule button 
				//*******  when the user is in the Schedule module (No selected Order).
				
				// User should be able to assign session for any Order and assign to any employee (not just one selected)
		
			} else {
				var order_id = $('#order_id').attr('value');
				var order = getOrderData(order_id);
				
				// Assign Order (Job) information
				setJobData(order_id, order['name']);
			}
			var date = deconstructSelectedDate($('#date_selected').attr('value'));
			var employee_id = '';
			var start_time = '';
			
			// Assigned Employee
			// Do not assign an employee to the dropdown box.
			$('select.employee_select option').attr('selected', false);

			// Session Date - Comes from the element clicked.
			setSessionDate(date, 1)

			// Start time is the time from the cell selected --
			clearStartTime();

			// Construct the end time select box.
			var stop = 2400;
		
			// Use the next schedule time as the end time if the next schedule time is less than one hour from the schedules
			// start time.
			setEndTime(null, stop);

			// Generate a list of times the user can select from of the time_end value.  Ending at when the next schedule starts.
			//var list = constructTimeList(start_time, stop);
			//$('#time-end-select').html(list);
		}
	
		function setSessionDate(date_session, disable) {
			var date = formatDate(date_session);
			$('input#ScheduleDateSession').val(date['year'] + '-' + date['month'] + '-' + date['day']);
			$('input#ScheduleDate').val(date['month'] + '/' + date['day'] + '/' + date['year']);
			if(disable) {
				$('input#ScheduleDate').attr('disabled', 'disabled');
			} 
		}
		
		function setStartTime(start_time) {
			var parsed_start_time = formatTime(start_time);
			$('select.minute_start option').attr('selected', false);
			$('select.post_meridiem_start option').attr('selected', false);
			$('input.hour_start').val(parsed_start_time['hour']);
			$('select.minute_start option[value = '+parseInt(parsed_start_time['min'])+ ']').attr('selected', true);
			$('select.post_meridiem_start option[value = '+parsed_start_time['post']+ ']').attr('selected', true);
		}

		function clearStartTime() {
			$('select.minute_start option').attr('selected', false);
			$('select.post_meridiem_start option').attr('selected', false);
			$('input.hour_start').val('');
		}

		function setEndTime(schedule_id, time) {
			// schedule_id was provided, thus the time_end can be pulled from the data_bank
			var time_format = formatTime(time);
			$('#ScheduleTimeEndDisplay').val(time_format['hmp']);
			$('#ScheduleTimeEnd').val(time);
		}
		
		function init_scheduler(e) {
			// Initial Maintentance
			$('#ScheduleDisplay').val($('#display').val());
			$('.time-end-select-container').css('display', 'none');
			$('td.first').html('');

			if($(e).attr('id') == 'scheduled-job-button') {
				// Schedule Order started from the "Schedule" button located within the Order Statistics element
				displayScheduleFormForOrderNoGrid();
			} else {
				var employee_id = $(e).attr('id').split('_')[2];
				var date = formatDate($(e).attr('id').split('_')[3]);
				var start_time = $(e).attr('id').split('_')[4];
				
				// Check if the user selected a time already scheduled.  If so, obtain the schedule_id.
				var schedule_id = 0;
				var order_id = 0;
				var order_name = '';
				if($(e).children(":first").attr('id')) {
					// A ScheduleSession has been found for a specific Employee.... Clue tip form should only allow the user to update
					// for this specific session for this employee.
					
					// Using the schedule_id, Obtain all the data for that schedule_session... Order...
					// Access the schedule data bank to obtain data.
					schedule_id = $(e).children(":first").attr('id');
					displayExistingSessionForm(schedule_id);
					return false;
					
				} else {
					// No schedule selected... Next check to see if the User is Scheduling for a specific Order
					if($('#order_id') && $('#order_id').val().length) {
						// User is scheduling for a specific Order and has slected an empty session.
						// Allow the user to assign sessions to any employees but only for this Order.
						var order_id = $('#order_id').attr('value');
						displayScheduleForOrderForm(order_id, $(e).attr('id'));
					} else {
						// Here is the case when a user has selected an empty session for all orders
						// User should be able to assign session for any Order and assign to any employee (not just one selected)
						displayScheduleFormFromGrid($(e).attr('id'));
					}
				}
			}
		}

		function deleteScheduleSession(schedule_session_id) {
			$.ajax({
				url:  myBaseUrl + "schedules/ajax_delete_schedule/"+schedule_session_id,
				beforeSend: function() {
					if (schedule_session_id==""){
						return false;
					}
					$('div#ajax_loader_schedule').css('display', 'block');
				},
				complete: function(data, textStatus){
					// Handle the complete event
					var results = data.responseText;
					var obj = jQuery.parseJSON(data.responseText);
					var result = obj.result;
					var error = obj.error;
					var data = obj.data;
					if(result == 'success') {
						// Remove Schedule Session from the data bank;
						$('div#data_bank div#schedule_sessions div#schedule_'+schedule_session_id).remove();

						// BROWSER DISPLAY
						// First, clear all current blocks for this schedule.
						$('div.schedule_worker_marker_'+schedule_session_id).each(function() {
							$(this).parent().removeClass('status-1 status-2 status-3 border_right');
							$(this).removeClass('schedule_worker_marker_'+schedule_session_id);
							$(this).attr('id', '');
						});
						$(document).trigger('hideCluetip');
					} else {
						// Dislpay Error
						ajax_result_message.html(error);
					}
					
					$('div#ajax_loader_schedule').css('display', 'none');
				},
			});
		}

		/***********
		*	Open the end time select box
		*	CHOOSE AN END TIME FOR THE SCHEDULE
		*/
		$('#ScheduleTimeEndDisplay').bind('click', function() {
			// Clear any previous selected times.
			$('tr').each(function() {
				$(this).children('td.first').html('');
				$(this).css('font-weight', 'normal');
			});
			
			// Mark the current time within the selection box.
			var end_time = $('#ScheduleTimeEnd').val();
			$('tr.'+end_time + ' td.first').html('&rarr;');
			$('tr.'+end_time).css('font-weight', 'bold');
			$('tr.'+end_time).removeClass('hide_quarter_slot');

			// initialize arrows
			$('img#icon-arrow-down').css('display', 'inline-block');
			$('img#icon-arrow-up').css('display', 'none');
			
			$('.time-end-select-container').css('display', 'block');
		});

		/***********
		*	Select the "show more" arrows in the End Time select box.
		*/
		$('img.icon-arrow').bind('click', function() {
			// loop through all the rows.  Removing 
			$('tr.hide_quarter_slot').toggle();
			$('img#icon-arrow-down').toggle();
			$('img#icon-arrow-up').toggle();
		});

		/***********
		*	A TIME IS SELECTED FROM THE END TIME
		*/
		$(document).on("click", "table#time-end-select tr", function() {
			var selected_date = formatTime($(this).attr('id'));

			$('.time_end_display').val(selected_date['hmp']);
			$('.time_end').val($(this).attr('id'));
			$('.time-end-select-container').css('display', 'none');
		});

		/***********
		*	A COMPONENT THAT IS PART OF THE START TIME IS CHANGED (hour-start, minute-start, post_meridiem_start)
		*/
		$(document).on("change", "select.time_input", function() {
			//var form = $(this).parent('form#scheduleJob');
			var form = $(this).parent('div.time_select_container');
			var hour = form.find('#hour_start').attr('value');
			var minute = form.find('#minute_start').attr('value');
			var pm = form.find('#post_meridiem_start').attr('value');
			var start_time = constructStartTime(hour, minute, pm);
		});

		$(document).on("keyup", 'input.time_input', function() {
			//var form = $(this).parent('form#scheduleJob');
			var form = $(this).parent('div.time_select_container');
			var hour = form.find('#hour_start').attr('value');
			var minute = form.find('#minute_start').attr('value');
			var pm = form.find('#post_meridiem_start').attr('value');
			var start_time = constructStartTime(hour, minute, pm);
		});

		$('input#hour_start').blur(function() {
			if($(this).attr('value').length == 0) {
				var container = $(this).parent('div.time_select_container');
				container.find('#error-message-hour_start').html('Error:  A Start time must be entered.');
				//$('#error-message-hour_start').html('Error:  A Start time must be entered.');
				$(this).focus();
			} else {
				$('#error-message-hour_start').html('');
			}
		});

		/***********
		*	An ORDER (JOB) IS SELECTED FROM THE DROP DOWN
		*/
		$(document).on("change", 'select#ScheduleOrders', function() {
			$('input#ScheduleOrderId').attr('value', $(this).attr('value')); 
			getOrderLineItems($(this).attr('value'));
		});
		
		$('.clicktip-schedule').cluetip({
			activation: 'click',
			sticky: true,
			width: 400,
			closePosition: 'title',
			closeText: 'X',
			local: true,
			onActivate:  function(event) {
				// When a block is selected, execute the init_scheduler function
				init_scheduler(this);
			}
		});

		/***********
		*	An ORDER (JOB) IS SELECTED FROM THE DROP DOWN
		*/
		$(document).on("click", "#delete_schedule_session", function() {
			var scheduled_session_id = $('#ScheduleScheduleId').val();;
			deleteScheduleSession(scheduled_session_id);
			return false;
		});

		<?php 
		/**
		 * Graphical Display
		 * START BLOCK
		 */
		unset($scheduled_jobs['Summary']); 
		if(!empty($scheduled_jobs)) :
			foreach($scheduled_jobs as $key=>$employee_job) :
				$id = $employee_job['Worker']['id'];
				if(!empty($employee_job['ScheduleSession'])) :
					foreach($employee_job['ScheduleSession'] as $job_key => $job) : 
						$jquery_id = 'employee_id_' .$id. '_' . $job['date_session']. '_' . $job['time_start']; 
		?>
		
		// CALL Javascript function used to populate session data on the display.
		populateDisplay('<?php echo $jquery_id; ?>', '<?php echo $job['id']; ?>', '<?php echo $job['css']; ?>', '<?php echo $job['sections']; ?>', '<?php echo $job['order_id']; ?>');
		
		<?php 		endforeach;
				endif;
			endforeach;
		endif;
		/**
		 * END BOCK
		 */
		?>
	});
</script>