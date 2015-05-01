$(document).ready(function() {
	function edit_task(id) {
		$('#OrderTaskId').val($('#' + id + '_task_data_bank #id').val());
		$('#OrderTaskItem').val($('#' + id + '_task_data_bank #item').val());
		$('#OrderTaskTaskType').val($('#' + id + '_task_data_bank #task_type').val());
		$('#OrderTaskDescription').val($('#' + id + '_task_data_bank #description').val());
		$('#OrderTaskRequestedById').val($('#' + id + '_task_data_bank #requested_by_id').val());
		$('#OrderTaskAssignedToId').val($('#' + id + '_task_data_bank #assigned_to_id').val());
		$('#OrderTaskDateCreated').val($('#' + id + '_task_data_bank #date_created').val());
		$('#OrderTaskDateRequest').val($('#' + id + '_task_data_bank #date_request').val());
		$('#OrderTaskDateStart').val($('#' + id + '_task_data_bank #date_start').val());
		
		$('#OrderTaskTaskType0').prop('checked', false);
		$('#OrderTaskTaskType1').prop('checked', false);
		$('#OrderTaskTaskType' + $('#' + id + '_task_data_bank #task_type').val()).prop('checked', true);
	
		$('.buttonset').buttonset('refresh');
		
		$('#add_status_toggle_display').css('display', 'block');
	}
	
	$('.edit_task').bind('click', function() {
		var id = $(this).attr('id');
		edit_task(id);
    	return false;
    });
	
	$('#add_status').bind('click', function() {
		var id = $(this).attr('id');
		$('#'+id+'_toggle_display').toggle();
		
		// Clear values
		$('#OrderTaskId').val('');
		$('#OrderTaskItem').val('');
		$('#OrderTaskTaskType').val('');
		$('#OrderTaskDescription').val($('#base_OrderTask_type_id').val());
		$('#OrderTaskRequestedById').val('');
		$('#OrderTaskAssignedToId').val('');
		$('#OrderTaskDateCreated').val('');
		$('#OrderTaskDateStart').val('');
		$('#OrderTaskDateRequest').val('');
		
		$('#OrderTaskTaskType0').prop('checked', true);
		$('#OrderTaskTaskType1').prop('checked', false);
		$('.buttonset').buttonset('refresh');
		
    	return false;
    });
	
	/*
	 * PAGE LOAD
	 * Check if the page is in edit mode... That is there a value within the 'display_order_task_id' field, place the page in edit mode... 
	 * opening the tab for the spcified element
	 */
	var edit_id = $('#display_order_task_id').val();
	if(edit_id.length) {
		edit_task(edit_id);
	}
});