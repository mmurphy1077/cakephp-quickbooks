<script type="text/javascript">
	$(document).ready(function(){
		function formatTime(time) {
			var hh = parseInt(time/100);
		    var m = time%100;
		    var dd = "am";
		    var h = hh;
		    if (h >= 12 && h < 24) {
		        h = hh-12;
		        dd = "pm";
		    } else if(h == 24) {
		    	h = hh-12;
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
		    replacement['min_decimal'] = m/60;
		    return replacement;
		}

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
		
		function setEndTime(time, type) {
			// schedule_id was provided, thus the time_end can be pulled from the data_bank
			if(time > 0) {
				var time_format = formatTime(time);
				$('#time_end_display_' + type).val(time_format['hmp']);
				$('#time_end_' + type).val(time);
				$('#time_end_display_' + type).removeAttr('disabled');
			} else {
				// Clear Value
				clearEndTime(type);
			}
		}

		function deconstructSelectedTime(time) {
			// Date will always be in the form 'hh:mm:ss'
		    var h = time.substring(0,2);
		    var m = time.substring(3,5);
			return h+m;
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

		function calculate_time_under_hour(start, end) {
			var ts = formatTime(start);
			var te = formatTime(end);
			if(te['min_decimal'] == 0) {
				te['min_decimal'] = 1;
			}
			return te['min_decimal'] - ts['min_decimal'];
		}
		function calculate_time_over_hour(start, end) {
			var ts = formatTime(start);
			var te = formatTime(end);
			var hour_ds = parseInt(start/100);
			var hour_de = parseInt(end/100);
			start_time = parseFloat(hour_ds) + parseFloat(ts['min_decimal']);
			end_time = parseFloat(hour_de) + parseFloat(te['min_decimal']);
			return end_time - start_time;
		}

		function recalculate_session_time() {
			// Grab the difference in work time.
			var total = 0;
			var work_start = 0;
			var work_end = 0;
			var drive_start = 0;
			var drive_end = 0;

			// Populate values
			if($('#time_start_work').val().length > 0) {
				work_start = parseInt($('#time_start_work').val());
			}
			if($('#time_end_work').val().length > 0) {
				work_end = parseInt($('#time_end_work').val());
			}
			if($('#time_start_drive').val().length > 0) {
				drive_start = parseInt($('#time_start_drive').val());
			}
			if($('#time_end_drive').val().length > 0) {
				drive_end = parseInt($('#time_end_drive').val());
			}

			// Calculate
			if((work_end - work_start) > 0) {
				if((work_end - work_start) >= 100) {
					total = total + calculate_time_over_hour(work_start, work_end);
				} else {
					// Difference is less than an hour
					total = total + calculate_time_under_hour(work_start, work_end);
				}
			}
			if((drive_end - drive_start) > 0) {
				if((drive_end - drive_start) >= 100) {
					total = total + calculate_time_over_hour(drive_start, drive_end);
				} else {
					// Difference is less than an hour
					total = total + calculate_time_under_hour(drive_start, drive_end);
				}
			}
			
			// Obtain current session time.
			var current = $('#session-time').val();
			var reg = 0;
			var ot = 0;
			var dt = 0;
			if($('#time_input_reg').val().length > 0) {
				reg = parseFloat($('#time_input_reg').val());
			}
			if($('#time_input_ot').val().length > 0) {
				ot = parseFloat($('#time_input_ot').val());
			}
			if($('#time_input_dt').val().length > 0) {
				dt = parseFloat($('#time_input_dt').val());
			}

			if(current > total) {
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
				$('#time_input_reg').val(reg + (total - current));
			}
			$('#session-time').val(total);
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

		function clearStartTime(type) {
			$('#time_start_'+type).val(0);
			$('#hour_start_' + type).attr('value', 0);
			$('select#post_meridiem_start_' + type + ' option').attr('selected', false);
			$('select#post_meridiem_start_' + type + ' option[value = am]').attr('selected', true);
			$('select#minute_start_' + type + ' option').attr('selected', false);
			$('select#minute_start_' + type + ' option[value = 0]').attr('selected', true);
			$('input#hour_start_' + type).val('');
		}
		
		function clearEndTime(type) {
			$('#time_end_' + type).val(0);
			$('#time_end_display_' + type).val('');
			$('#time_end_display_' + type).attr("disabled", "disabled");
			
			var list = constructTimeList(600, 2445);
			$('#time-end-select-' + type).html(list);
		}

		function start_time_change(type) {
			var hour = $('#hour_start_' + type).attr('value');
			if(($('#hour_start_' + type).val() == 0) || ($('#hour_start_' + type).attr('value').length == 0)) {
				hour = 0;
				clearStartTime(type);
				clearEndTime(type);
			} else {
				var minute = $('#minute_start_' + type).attr('value');
				var pm = $('#post_meridiem_start_' + type).attr('value');
				var start_time = constructStartTime(hour, minute, pm);
				$('#time_start_'+type).val(start_time);
	
				// Update End time if it is empty. (List too)
				// Or if the End time is less than the start time. 
				// OR if the End time is greater than 1 hour
				if((!$('#time_end_' + type).length) || ($('#time_end_' + type).attr('value').length == 0) || ($('#time_end_' + type).attr('value') == 0)  || ($('#time_end_' + type).attr('value') <= start_time) || (($('#time_end_' + type).attr('value') - start_time) > 100)) {
					// Default to one hour ahead.
					if((parseInt(start_time)+100) < 2500) {
						setEndTime(parseInt(start_time) + 100, type);
					} else {
						setEndTime(parseInt(start_time) + 30, type);
					}
				}
				$('#time_end_display_' + type).removeAttr('disabled');
				
				var list = constructTimeList(start_time, 2445);
				$('#time-end-select-' + type).html(list);
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

		/***********
		*	Open the end time select box
		*	CHOOSE AN END TIME FOR THE SCHEDULE
		*/
		$('.time_end_display').bind('click', function() {
			// First make sure all displays are closed ... reset
			$('.time-end-select-container').css('display', 'none');
			
			// Clear any previous selected times.
			$('table.time-end-select tr').each(function() {
				$(this).children('td.first').html('');
				$(this).css('font-weight', 'normal');
			});
			
			// Mark the current time within the selection box.
			// Obtain the end time from the associated hidden time_end container.
			var end_time = $(this).next('.time_end').val();

			// Determine the type (time_end_display_work)
			var type = $(this).attr('id').substring(17);
			var select_container = $('div#time-end-select-container-' + type);	
			select_container.find('table.time-end-select tbody tr.'+end_time).css('font-weight', 'bold');
			select_container.find('table.time-end-select tbody tr.'+end_time + ' td.first').html('&rarr;');
			select_container.find('table.time-end-select tbody tr.'+end_time).css('font-weight', 'bold');
			select_container.find('table.time-end-select tbody tr.'+end_time).removeClass('hide_quarter_slot');

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
		$(document).on("click", "table.time-end-select tr", function() {
			var selected_date = formatTime($(this).attr('id'));

			// Obtain the type --- table#time-end-select-work
			var type = $(this).closest('table').attr('id').substring(16);
			$('#time_end_display_' + type).val(selected_date['hmp']);
			$('#time_end_' + type).val($(this).attr('id'));
			$('#time-end-select-container-' + type).css('display', 'none');

			recalculate_session_time();
		});
		
		/***********
		*	A COMPONENT THAT IS PART OF THE START TIME IS CHANGED (hour-start, minute-start, post_meridiem_start)
		*/
		$(document).on("change", "select.minute_start", function() {
			var type = $(this).attr('id').substring(13);
			start_time_change(type);
		});
		$(document).on("change", "select.post_meridiem_start", function() {
			var type = $(this).attr('id').substring(20);
			start_time_change(type);
		});
		$(document).on("keyup", "'input.hour_start'", function() {
			var type = $(this).attr('id').substring(11);
			start_time_change(type);
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
		
		// Initialize Page
		$('div#add-time-log-container').css('display', 'none');

		//**********************************
		// SCHEDULE NAVIGATION BLOCK
		function init_schedule_session_navigation() {
			$('#label_labor').css('display', 'block');
			$('#label_materials').css('display', 'none');
			$('#schedule_session_input_container').css('display', 'block');
			$('#schedule_session_time_container').css('display', 'block');
			$('#schedule_session_materials_container').css('display', 'none');
			$('#schedule_session_container #update_schedule_session_time_container').css('display', 'none');
			$('#schedule_session_container #select').css('display', 'block');
		}
		function init_costs_navigation() {
			$('#label_labor').css('display', 'none');
			$('#label_materials').css('display', 'block');
			$('#schedule_session_input_container').css('display', 'block');
			$('#schedule_session_time_container').css('display', 'none');
			$('#schedule_session_container #update_schedule_session_time_container').css('display', 'none');
			$('#schedule_session_materials_container').css('display', 'block');
		}

		$(document).on("click", "#schedule_session_time_navigation .button", function() {
			var id = $(this).attr('id');
			if(id == 'nav_time') {
				$('#label_labor').css('display', 'block');
				$('#label_materials').css('display', 'none');
				$('#schedule_session_time_container').css('display', 'block');
				$('#schedule_session_materials_container').css('display', 'none');
			} else {
				// nav_materials
				$('#label_labor').css('display', 'none');
				$('#label_materials').css('display', 'block');
				$('#schedule_session_time_container').css('display', 'none');
				$('#schedule_session_materials_container').css('display', 'block');
			}
			return false;
		});

		$(document).on("click", "#close-time-log-container", function() { 
			init_schedule_session_navigation();
			$('div#add-time-log-container').css('display', 'none');
			return false;
		});
		
		$(document).on("click", "#return_to_schedule_list", function() { 
			reset_form();
			$('#schedule_session_container #update_schedule_session_time_container').css('display', 'none');
			$('#schedule_session_container #select').css('display', 'block');
			return false;
		});
		
		// END - SCHEDULE NAVIGATION BLOCK

		// *********************************
		// MATERIALS BLOCK
		var units_for_materials = new Array();
		<?php 
		if(!empty($units_for_materials)) : 
			foreach($units_for_materials as $key=>$data) : ?>
			units_for_materials[<?php echo $key; ?>] = '<?php echo $data; ?>';
		<?php 
			endforeach;
		endif; ?>

		$(document).on("change", ".material_select", function() {
			var value = $(this).val();
			var id = $(this).attr('id');
			var unit = units_for_materials[value];
			$('div#'+id+'_units').html(unit);
		});

		function add_material_line() {
			// Get the last '.material_select' and obtain the id.
			var last_id = $('#material_select_container div.material_select_item').last().attr('id');
			var next =  parseInt(last_id.substring(21)) + 1;
			var new_line = '<div id="material_select_item_'+ next +'" class="material_select_item">' + $('div#material_select_item_'+last_id.substring(21)).html() + '</div>';
			$('div#material_select_container').append(new_line);
			// Update the id's of the new line.
			$('#material_select_item_'+ next+ ' .material_select').attr('id', 'material_'+next);
			$('#material_select_item_'+ next+ ' .material_select').attr('name', 'data[OrderMaterial]['+next+'][material_id]');
			$('#material_select_item_'+ next+ ' .material_qty').attr('id', 'material_qty_'+next);
			$('#material_select_item_'+ next+ ' .material_qty').attr('name', 'data[OrderMaterial]['+next+'][qty]');
			$('#material_select_item_'+ next+ ' .units').attr('id', 'material_'+next+'_units');
		}
		
		$(document).on("click", "#add_more_materials", function() {
			add_material_line();
			return false;
		});

		function add_purchase_line() {
			// Get the last '.material_purchase_item' and obtain the id.
			var last_id = $('#material_purchase_container div.material_purchase_item').last().attr('id');
			var next =  parseInt(last_id.substring(23)) + 1;
			var new_line = '<div id="material_purchase_item_'+ next +'" class="material_purchase_item">' + $('div#material_purchase_item_'+last_id.substring(23)).html() + '</div>';
			$('div#material_purchase_container').append(new_line);
			
			// Update the id's of the new line.
			$('#material_purchase_item_'+ next+ ' .material_purchase').attr('id', 'purchase_'+next);
			$('#material_purchase_item_'+ next+ ' .material_purchase').attr('name', 'data[PurchasesOrder]['+next+'][description]');
			$('#material_purchase_item_'+ next+ ' .material_purchase_amount').attr('id', 'purchase_amount_'+next);
			$('#material_purchase_item_'+ next+ ' .material_purchase_amount').attr('name', 'data[PurchasesOrder]['+next+'][amount]');
		}
		
		$(document).on("click", "#add_more_store_materials", function() {
			add_purchase_line();
			return false;
		});

		function add_equipment_line() {
			// Get the last '.equipment_input_item' and obtain the id.
			var last_id = $('#equipment_input_container div.equipment_input_item').last().attr('id');
			var next =  parseInt(last_id.substring(21)) + 1;
			var new_line = '<div id="equipment_input_item_'+ next +'" class="equipment_input_item">' + $('div#equipment_input_item_'+last_id.substring(21)).html() + '</div>';
			$('div#equipment_input_container').append(new_line);
			
			// Update the id's of the new line.
			$('#equipment_input_item_'+ next+ ' .equipment_input').attr('id', 'equipment_'+next);
			$('#equipment_input_item_'+ next+ ' .equipment_input').attr('name', 'data[EquipmentItemsOrder]['+next+'][description]');
			$('#equipment_input_item_'+ next+ ' .equipment_amount').attr('id', 'equipment_amount_'+next);
			$('#equipment_input_item_'+ next+ ' .equipment_amount').attr('name', 'data[EquipmentItemsOrder]['+next+'][amount]');
		}
		
		$(document).on("click", "#add_more_equipment", function() { 
			add_equipment_line();
			return false;
		});
		// END - MATERIALS BLOCK
		
		function loadScheduleSessionData(params) {
			var worker = params['worker'];
			var date_session = params['date_session'];
			var start_time = formatTime(deconstructSelectedTime(params['start_time']));
			var end_time = formatTime(deconstructSelectedTime(params['end_time']));
			var estimate_num = params['estimate']/60;
			var task_string = params['tasks']
			var tasks = task_string.split('|');
			var selected_tasks_string = params['selected_tasks'];
			var selected_tasks = selected_tasks_string.split('|');
			
			$('#update_schedule_session_time_container div#selected_worker').html('<label>Worker:</label>'+worker);
			$('#update_schedule_session_time_container div#selected_date_session').html('<label>Date:</label>'+date_session);
			$('#update_schedule_session_time_container div#selected_time').html('<label>Time:</label>'+start_time['hmp'] + ' to ' + end_time['hmp']);
			$('#update_schedule_session_time_container div#estimate').html('<label>Estimated Time:</label>'+estimate_num+' hour(s)');

			// OTHER POSSIBLE PARAMETERS
			//scheduled_sessions_data_bank[id]['time_total'];
			//scheduled_sessions_data_bank[id]['notes'];
			//scheduled_sessions_data_bank[id]['invoiced'];
			//scheduled_sessions_data_bank[id]['worker'];

			// Loop through the '#order_line_item_container'
			// For each checkbox with a class = 'schedule_session_task_select' check if the value exists within the tasks array.  If it does, select
			$('div#order_line_item_container input.schedule_session_task_select').each( function() {
				if(jQuery.inArray($(this).attr('value'), tasks) >= 0) {
					$(this).parent('div.input.checkbox').css('display', 'block');

					if(jQuery.inArray($(this).attr('value'), selected_tasks) >= 0) {
						$(this).attr('checked', 'checked');
					} else {
						$(this).removeAttr('checked');
					}
				} else {
					$(this).parent('div.input.checkbox').css('display', 'none');
					$(this).removeAttr('checked');
				}
			});
		}

		function reset_form() {
			// Time
			$('#ScheduleSessionTimeId').val('');
			$('#ScheduleSessionTimeScheduleSessionId').val('');
			$('#ScheduleSessionTimeWorkerId').val('');
			$('#ScheduleSessionTimeDateSession').val('');
			$('#ScheduleSessionTimeNotes').val('');
			clearStartTime('work');
			clearEndTime('work');
			clearStartTime('drive');
			clearEndTime('drive');
			
			$('#hour_start_drive').attr('value', 0);
			start_time_change('drive');
			$('#time_input_reg').val(0);
			$('#time_input_ot').val(0);
			$('#time_input_dt').val(0);
			$('#session-time').val(0);
			
			// Materials
			$('.material_select').each( function() {
				$(this).val(0);
			});
			$('.qty_input').each( function() {
				$(this).val('');
			});
			$('.units').each( function() {
				$(this).html('');
			});
			$('.material_purchase').each( function() {
				$(this).val('');
			});
			$('.amount_input').each( function() {
				$(this).val('');
			});
			
			// Equipment
			$('.equipment_input').each( function() {
				$(this).val('');
			});
		}
		
		/***********
		*	Select a Schedule Session 
		*	Obtain all the data for the selected session and pre-populate the time log.
		*/
		$(document).on("click", "tr.time_log_session_select", function() { 
			reset_form();
			var id = $(this).attr('id');
			$('#ScheduleSessionTimeScheduleSessionId').val(id);
			$('#ScheduleSessionTimeWorkerId').val($(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionWorkerId').val());
			$('#ScheduleSessionTimeDateSession').val($(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionDateSession').val());
			
			// WORK TIME
			// Start Time - Work
			var start_time = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionTimeStart').val();
			setStartTime(deconstructSelectedTime(start_time), 'work');
			
			// End Time
			var end_time = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionTimeEnd').val();
			setEndTime(deconstructSelectedTime(end_time), 'work');
			$('.time-end-select-container').css('display', 'none');

			// DRIVE TIME
			$('#hour_start_drive').attr('value', 0);
			start_time_change('drive');
			
			// Regular Hours
			// Determine the difference between the start and End times
			if((parseInt(deconstructSelectedTime(end_time)) - parseInt(deconstructSelectedTime(start_time))) >= 100) {
				var reg_time = formatTime(parseInt(deconstructSelectedTime(end_time)) - parseInt(deconstructSelectedTime(start_time)));
			} else {
				var reg_time = new Array;
				reg_time['hour'] = 0;
				reg_time['min_decimal'] = parseInt(deconstructSelectedTime(end_time) - deconstructSelectedTime(start_time))/60;
			}
			$('#time_input_reg').val(0);
			$('#time_input_ot').val(0);
			$('#time_input_dt').val(0);
			$('#time_input_reg').val(reg_time['hour'] + reg_time['min_decimal']);
			$('#session-time').val(reg_time['hour'] + reg_time['min_decimal']);

			// Generate a list of times the user can select from of the time_end value.  Ending at when the next schedule starts.
			var list = constructTimeList(deconstructSelectedTime(start_time), 2445);
			$('#time-end-select-drive').html(list);
			$('#time-end-select-work').html(list);

			// Job Information (ScheduleSession specific data)
			var params = new Array();
			params['worker'] = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionWorker').val();
			params['date_session'] = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionDateSessionDisplay').val();
			params['start_time'] = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionTimeStart').val();
			params['end_time'] = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionTimeEnd').val();
			params['estimate'] = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionEstimate').val();
			params['tasks'] = $(this).find('div#schedule_session_data_bank_'+id+' input#ScheduleSessionsTaskTaskId').val();
			params['selected_tasks'] = '';
			loadScheduleSessionData(params);

			// Toggle displays
			init_schedule_session_navigation();

			// Give the user the ability to return to the schedule list.
			$('#return_to_schedule_list').css('display', 'block');
			$('#optional_instructions').css('display', 'block');
					
			// Turn OFF the schedule session selection box in the time log window.
			$('#schedule_session_container #update_schedule_session_time_container').css('display', 'block');
			$('#schedule_session_container #select').css('display', 'none');
		});

		//**********************************
		// SCHEDULE SESSION EDIT BLOCK
		// When a time log event is 'edit'ed.
		var scheduled_sessions_data_bank = new Array();
		<?php 
		if(!empty($time_logs)):
			foreach($time_logs as $key=>$data) : ?>
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>] = new Array();
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['id'] = '<?php echo $data['ScheduleSessionTime']['id']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_start_drive'] = '<?php echo $data['ScheduleSessionTime']['time_start_drive']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_end_drive'] = '<?php echo $data['ScheduleSessionTime']['time_end_drive']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_start_work'] = '<?php echo $data['ScheduleSessionTime']['time_start_work']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_end_work'] = '<?php echo $data['ScheduleSessionTime']['time_end_work']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_reg'] = '<?php echo $data['ScheduleSessionTime']['time_reg']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_ot'] = '<?php echo $data['ScheduleSessionTime']['time_ot']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_dt'] = '<?php echo $data['ScheduleSessionTime']['time_dt']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['time_total'] = '<?php echo $data['ScheduleSessionTime']['time_total']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['rate'] = '<?php echo $data['ScheduleSessionTime']['rate']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['notes'] = '<?php echo str_replace("\'", "\\'\'", $data['ScheduleSessionTime']['notes']); ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['invoiced'] = '<?php echo $data['ScheduleSessionTime']['invoiced']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['date_session'] = '<?php echo $data['ScheduleSession']['date_session']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['worker_id'] = '<?php echo $data['Worker']['id']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['worker'] = '<?php echo $this->Web->humanName($data['Worker'], 'full'); ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['ScheduleSession'] = new Array();
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['ScheduleSession']['id'] = '<?php echo $data['ScheduleSession']['id']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['ScheduleSession']['date_session'] = '<?php echo date('F d, Y', strtotime($data['ScheduleSession']['date_session'])); ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['ScheduleSession']['time_start'] = '<?php echo $data['ScheduleSession']['time_start']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['ScheduleSession']['time_end'] = '<?php echo $data['ScheduleSession']['time_end']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['ScheduleSession']['estimate'] = '<?php echo $data['ScheduleSession']['estimate']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['ScheduleSession']['selected_task_list'] = '<?php echo $data['ScheduleSessionTime']['selected_task_list']; ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Materials'] = new Array();
		<?php	if(!empty($data['OrderMaterial'])) :
					foreach($data['OrderMaterial'] as $mat_key => $materials) : ?>
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Materials'][<?php echo $mat_key; ?>] = new Array();
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Materials'][<?php echo $mat_key; ?>]['qty'] = '<?php echo $materials['qty'] ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Materials'][<?php echo $mat_key; ?>]['material_id'] = '<?php echo $materials['material_id'] ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Materials'][<?php echo $mat_key; ?>]['description'] = '<?php echo $materials['description'] ?>';
		<?php 
					endforeach;
				endif; ?>
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Purchases'] = new Array();
		<?php	if(!empty($data['PurchasesOrder'])) :
					foreach($data['PurchasesOrder'] as $purchase_key => $purchase) : ?>
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Purchases'][<?php echo $purchase_key; ?>] = new Array();
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Purchases'][<?php echo $purchase_key; ?>]['description'] = '<?php echo $purchase['description'] ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Purchases'][<?php echo $purchase_key; ?>]['amount'] = '<?php echo $purchase['amount'] ?>';
		<?php 
					endforeach;
				endif; ?>
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Equipment'] = new Array();
		<?php	if(!empty($data['EquipmentItemsOrder'])) :
					foreach($data['EquipmentItemsOrder'] as $equip_key => $equipment) : ?>
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Equipment'][<?php echo $equip_key; ?>] = new Array();
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Equipment'][<?php echo $equip_key; ?>]['description'] = '<?php echo $equipment['description'] ?>';
				scheduled_sessions_data_bank[<?php echo $data['ScheduleSessionTime']['id']; ?>]['Equipment'][<?php echo $equip_key; ?>]['amount'] = '<?php echo $equipment['amount'] ?>';
		<?php 
					endforeach;
				endif; 
			endforeach;
		endif; ?>	

		$(document).on("click", '.edit_schedule_session_button', function() { 
			reset_form();
			var id = $(this).attr('id').substring(29);
			// Set TIME values within the window
			$('#ScheduleSessionTimeId').val(id);
			$('#ScheduleSessionTimeScheduleSessionId').val(scheduled_sessions_data_bank[id]['ScheduleSession']['id']);
			$('#ScheduleSessionTimeWorkerId').val(scheduled_sessions_data_bank[id]['worker_id']);
			$('#ScheduleSessionTimeDateSession').val(scheduled_sessions_data_bank[id]['date_session']);
			setStartTime(deconstructSelectedTime(scheduled_sessions_data_bank[id]['time_start_drive']), 'drive');
			setEndTime(deconstructSelectedTime(scheduled_sessions_data_bank[id]['time_end_drive']), 'drive');
			setStartTime(deconstructSelectedTime(scheduled_sessions_data_bank[id]['time_start_work']), 'work');
			setEndTime(deconstructSelectedTime(scheduled_sessions_data_bank[id]['time_end_work']), 'work');
			$('#time_input_reg').val(scheduled_sessions_data_bank[id]['time_reg']);
			$('#time_input_ot').val(scheduled_sessions_data_bank[id]['time_ot']);
			$('#time_input_dt').val(scheduled_sessions_data_bank[id]['time_dt']);
			$('#session-time').val(parseFloat(scheduled_sessions_data_bank[id]['time_reg']) + parseFloat(scheduled_sessions_data_bank[id]['time_ot']) + parseFloat(scheduled_sessions_data_bank[id]['time_dt']));
			$('#ScheduleSessionTimeRate').val(scheduled_sessions_data_bank[id]['rate']);
			
			var list_drive = constructTimeList(deconstructSelectedTime(scheduled_sessions_data_bank[id]['time_start_work']), 2445);
			var list_work = constructTimeList(deconstructSelectedTime(scheduled_sessions_data_bank[id]['time_start_drive']), 2445);
			$('#time-end-select-drive').html(list_drive);
			$('#time-end-select-work').html(list_work);

			// Job Information
			var params = new Array();
			params['worker'] = scheduled_sessions_data_bank[id]['worker'];
			params['date_session'] = scheduled_sessions_data_bank[id]['ScheduleSession']['date_session'];
			params['start_time'] = scheduled_sessions_data_bank[id]['ScheduleSession']['time_start'];
			params['end_time'] = scheduled_sessions_data_bank[id]['ScheduleSession']['time_end'];
			params['estimate'] = scheduled_sessions_data_bank[id]['ScheduleSession']['estimate'];
			// TASKS
			// Get the schedule session id and access the forms 
			var sch_session_id = scheduled_sessions_data_bank[id]['ScheduleSession']['id'];
			params['tasks'] = $('div#schedule_session_data_bank_'+sch_session_id+' input#ScheduleSessionsTaskTaskId').val();
			params['selected_tasks'] = scheduled_sessions_data_bank[id]['ScheduleSession']['selected_task_list'];
			loadScheduleSessionData(params);

			// Materials and Equipment.
			if (scheduled_sessions_data_bank[id]['Materials'].length != 0) {
				var i, currentElem;
				l = scheduled_sessions_data_bank[id]['Materials'].length;
				for( i = 0; i < l; i++ ) {
					if(i > 4) {
						// More than 5 elements... Add another line
						add_material_line();
					}
					currentElem = scheduled_sessions_data_bank[id]['Materials'][i];
					$('#material_'+i).val(currentElem['material_id']);
					$('#material_qty_'+i).val(currentElem['qty']);
					var unit = units_for_materials[currentElem['material_id']];
					$('div#material_'+i+'_units').html(unit);
				}
			}
			if (scheduled_sessions_data_bank[id]['Purchases'].length != 0) {
				var i, currentElem;
				l = scheduled_sessions_data_bank[id]['Purchases'].length;
				for( i = 0; i < l; i++ ) {
					if(i > 0) {
						// More than 1 element... Add another line
						add_purchase_line();
					}
					currentElem = scheduled_sessions_data_bank[id]['Purchases'][i];
					$('#purchase_'+i).val(currentElem['description']);
					$('#purchase_amount_'+i).val(currentElem['amount']);
				}
			}
			if (scheduled_sessions_data_bank[id]['Equipment'].length != 0) {
				var i, currentElem;
				l = scheduled_sessions_data_bank[id]['Equipment'].length;
				for( i = 0; i < l; i++ ) {
					if(i > 0) {
						// More than 1 element... Add another line
						add_equipment_line();
					}
					currentElem = scheduled_sessions_data_bank[id]['Equipment'][i];
					$('#equipment_'+i).val(currentElem['description']);
					$('#equipment_amount_'+i).val(currentElem['amount']);
				}
			}
			
			// Open the window
			$('#add-time-log-container').css('display', 'block');
			init_schedule_session_navigation();
			
			// Turn OFF the schedule session selection box in the time log window.
			$('#schedule_session_container #update_schedule_session_time_container').css('display', 'block');
			$('#schedule_session_container #select').css('display', 'none');
			$('#return_to_schedule_list').css('display', 'none');
			$('#optional_instructions').css('display', 'none');
			setMargins();

			return false;
		});
	});
</script>