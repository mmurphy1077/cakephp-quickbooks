function toggle_material_detail_container(target) {
	$('.material-detail-container').css('display', 'none');
	$('#material-detail-container-' + target).css('display', 'block');
}

function add_material_line() {
	// Get the last '.material_select' and obtain the id.
	var last_id = $('#material_select_container .material_select_item').last().attr('id');
	var next =  parseInt(last_id.substring(21)) + 1;
	var new_line = '<tr id="material_select_item_'+ next +'" class="material_select_item">' + $('#material_select_item_'+last_id.substring(21)).html() + '</tr>';
	$('#material_select_container').append(new_line);
	// Update the id's of the new line.
	$('#material_select_item_'+ next+ ' .id').attr('id', 'order_material_item_id_'+next);
	$('#material_select_item_'+ next+ ' .material_id').attr('id', 'order_material_item_material_id_'+next);
	$('#material_select_item_'+ next+ ' .material_name').attr('id', 'order_material_item_name_'+next);
	$('#material_select_item_'+ next+ ' .material_desc').attr('id', 'order_material_item_description_'+next);
	$('#material_select_item_'+ next+ ' .material_qty').attr('id', 'order_material_item_qty_'+next);
	$('#material_select_item_'+ next+ ' .price_per_unit_actual').attr('id', 'order_material_item_price_per_unit_actual_'+next);
	$('#material_select_item_'+ next+ ' .price_per_unit').attr('id', 'order_material_item_price_per_unit_'+next);
	$('#material_select_item_'+ next+ ' .uom').attr('id', 'order_material_item_uom_id_'+next);
	$('#material_select_item_'+ next+ ' .material-line-item-delete').attr('id', 'material-line-item-delete-'+next);
	
	
	// Update the name attribute.
	$('#order_material_item_id_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][id]');
	$('#order_material_item_material_id_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][material_id]');
	$('#order_material_item_name_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][name]');
	$('#order_material_item_description_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][description]');
	$('#order_material_item_qty_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][qty]');
	$('#order_material_item_price_per_unit_actual_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][price_per_unit_actual]');
	$('#order_material_item_price_per_unit_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][price_per_unit]');
	$('#order_material_item_uom_id_'+ next).attr('name', 'data[OrderMaterialItem]['+next+'][uom_id]');
	
}

function deleteOrderMaterialItemRowContent(id) {
	$('#order_material_item_name_' + id).val('');
	$('#order_material_item_description_' + id).val('');
	$('#order_material_item_material_id_' + id).val('');
	$('#order_material_item_id_' + id).val('');
	$('#order_material_item_qty_' + id).val('');
	$('#order_material_item_price_per_unit_actual_' + id).val('');
	$('#order_material_item_price_per_unit_' + id).val('');
	$('#order_material_item_uom_id_' + id).val('');
}

function deleteOrderMaterialItem(id, material_item_id) {
	// Build a parameter list to lend to the server
	var params = 'id:'+ material_item_id + '/';
	$.ajax({
		url: myBaseUrl + "order_materials/ajax_delete_order_material_item/"+params,
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
					error = obj.error;
				
				if(success) {
					deleteOrderMaterialItemRowContent(id);
				} else {
					
				}
				return success;
			} else {
				return false;
			}
		},
	});
}

$(document).ready(function(){
	$("#OrderMaterialDateSession.datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);
	    }
	});
	$("#OrderExpenseDateSession").datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);
	    }
	});

	$(document).on("click", "[for=OrderMaterialTypeStock]", function() {
		//toggle_material_detail_container('stock');
	});
	$(document).on("click", "[for=OrderMaterialTypePurchase]", function() {
		//toggle_material_detail_container('purchase');
	});
	
	$(document).on("click", ".material-line-item-delete", function() {
        var id = $(this).attr('id').replace('material-line-item-delete-', ''),
        	material_item_id = $('#order_material_item_id_' + id).val();
       
    	// Is there a table ID asscociated with the line item?
    	if(material_item_id.length) {
    		var r=confirm('Are you sure you want to delete this item?');
            if (r==true) {
            	deleteOrderMaterialItem(id, material_item_id);
            }
    	} else {
    		deleteOrderMaterialItemRowContent(id);
    	}
		return false;
	});
	
	/***********
	 *	A ITEM IS SELECTED FROM THE CATALOG
	 */
	function select_material_item(id) {
		var parent_id = $('div#data-bank-' + id + ' input#MaterialParentId').val(),
			name = $('div#data-bank-' + id + ' input#MaterialName').val();
			description = $('div#data-bank-' + id + ' input#MaterialDescription').val(),
			is_category = $('div#data-bank-' + id + ' input#MaterialIsCategory').val(),
			price_per_unit = $('div#data-bank-' + id + ' input#MaterialPricePerUnit').val(),
			price_per_unit_actual = $('div#data-bank-' + id + ' input#MaterialPricePerUnitActual').val(),
			uom_id = $('div#data-bank-' + id + ' input#MaterialUomId').val();
		
		// Detemine the (order_material_item) line to add the material item to
		// If none exist... create a new line.
		var index = null,
			lastId = null;
		$('table#material_select_container input.material_name').each( function() {
			lastId = $(this).attr('id').replace('order_material_item_name_', '');
			if((index == null) && ($(this).val() == 0)) {
				index = $(this).attr('id').replace('order_material_item_name_', '');
			}
		});
	
		if(index == null) {
			add_material_line();
			index = parseInt(lastId) + 1;
		}
		
		// Only add if the input boxes are not disabled (thus the material record is either approved or invoiced)
		if(!$('#order_material_item_name_' + index).prop('disabled')) {
			// Populate OrderMaterialItem fields.
			// Category - clear it!
			$('#order_material_item_id_' + index).val(null);
			$('#order_material_item_material_id_' + index).val(id);
			$('#order_material_item_name_' + index).val(name);
			$('#order_material_item_description_' + index).val(description);
			$('#order_material_item_qty_' + index).val(1);
			$('#order_material_item_price_per_unit_' + index).val(price_per_unit);
			$('#order_material_item_price_per_unit_actual_' + index).val(price_per_unit_actual);
			$('#order_material_item_uom_id_' + index).val(uom_id);
		}
	}
	
	$(document).on("click", '.material-item-container', function() {
		var id = $(this).attr('id');
		select_material_item(id);
		return true;
	});
	
	$(document).on("click", "div.assembly_button", function() {
        var assembly_id = $(this).attr('id').replace('assembly_button_', ''),
        	name = $('#assembly-' + assembly_id + ' div.name').html();
        	r=confirm("Click 'OK' to add " + name + " items to the order.");
        
        if (r==true) {
		    // Find each Material Item associated with the Assembly.
        	 $('div#assembly-container-children-' + assembly_id + ' div.material-item-container').each( function() {
		    	 select_material_item($(this).attr('id'));
		    });
        }
        
		return false;
	});
	
	// *********************************
	// MATERIALS BLOCK
	var units_for_materials = new Array();
	/*
	<?php 
	if(!empty($units_for_materials)) : 
		foreach($units_for_materials as $key=>$data) : ?>
		units_for_materials[<?php echo $key; ?>] = '<?php echo $data; ?>';
	<?php 
		endforeach;
	endif; ?>
	 */
	$(document).on("change", ".material_select", function() {
		var value = $(this).val();
		var id = $(this).attr('id');
		var unit = units_for_materials[value];
		$('div#'+id+'_units').html(unit);
	});
	
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
			if(jQuery.inArray($(this).val(), tasks) >= 0) {
				$(this).parent('div.input.checkbox').css('display', 'block');

				if(jQuery.inArray($(this).val(), selected_tasks) >= 0) {
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
	/*
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
	*/
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

	$(document).on('mouseleave', '.time-end-select-container', function() {
        $(this).css('display', 'none');
    });
});