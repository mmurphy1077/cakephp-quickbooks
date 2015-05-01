$(document).ready(function(){
	$('.edit_task').bind('click', function() {
		var id = $(this).attr('id');
		$('#QuoteTaskId').val($('#' + id + '_task_data_bank #id').val());
		$('#QuoteTaskItem').val($('#' + id + '_task_data_bank #item').val());
		$('#QuoteTaskTaskType').val($('#' + id + '_task_data_bank #task_type').val());
		$('#QuoteTaskDescription').val($('#' + id + '_task_data_bank #description').val());
		$('#QuoteTaskRequestedById').val($('#' + id + '_task_data_bank #requested_by_id').val());
		$('#QuoteTaskAssignedToId').val($('#' + id + '_task_data_bank #assigned_to_id').val());
		$('#QuoteTaskDateCreated').val($('#' + id + '_task_data_bank #date_created').val());
		$('#QuoteTaskDateRequest').val($('#' + id + '_task_data_bank #date_request').val());
		$('#QuoteTaskDateStart').val($('#' + id + '_task_data_bank #date_start').val());
		
		$('#QuoteTaskTaskType0').prop('checked', false);
		$('#QuoteTaskTaskType1').prop('checked', false);
		$('#QuoteTaskTaskType' + $('#' + id + '_task_data_bank #task_type').val()).prop('checked', true);
		$('.buttonset').buttonset('refresh');
		
		$('#add_status_toggle_display').css('display', 'block');
    	return false;
    });
	
	$('#add_status').bind('click', function() {
		var id = $(this).attr('id');
		$('#'+id+'_toggle_display').toggle();
		
		// Clear values
		$('#QuoteTaskId').val('');
		$('#QuoteTaskItem').val('');
		$('#QuoteTaskTaskType').val('');
		$('#QuoteTaskDescription').val($('#base_QuoteTask_type_id').val());
		$('#QuoteTaskRequestedById').val('');
		$('#QuoteTaskAssignedToId').val('');
		$('#QuoteTaskDateCreated').val('');
		$('#QuoteTaskDateStart').val('');
		$('#QuoteTaskDateRequest').val('');
		
		$('#QuoteTaskTaskType0').prop('checked', true);
		$('#QuoteTaskTaskType1').prop('checked', false);
		$('.buttonset').buttonset('refresh');
		
    	return false;
    });
});