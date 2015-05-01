$(document).ready(function(){
	$(document).on('click', "input.read_only", function () {
		var id = $(this).attr('id');
		var checked = $(this).prop('checked');
		
		// Find the parent div.permissions_container element, loop trough all the inputs and unselect all but the read_only element
		var container = $(this).closest('div.permissions_container');
		if(checked) {
			// Read Only... unselect all other checkboxes
			var container_id = container.attr('id');
			$('#' + container_id + ' div.checkbox input.permission').each( function() {
				$(this).prop('checked', false);
			});
		}
	});
	
	$(document).on('click', "input.permission", function () {
		var id = $(this).attr('id');
		var checked = $(this).prop('checked');
		
		// Find the parent div.permissions_container element, loop trough all the inputs and unselect all but the read_only element
		var container = $(this).closest('div.permissions_container');
		if(checked) {
			// uncheck the read_only permission
			var container_id = container.attr('id');
			$('#' + container_id + ' div.checkbox input.read_only').each( function() {
				$(this).prop('checked', false);
			});
		}
	});
	
	$(document).on('click', "input.access", function () {
		var id = $(this).attr('id');
		var checked = $(this).prop('checked');
		if(checked) {
			// Enable all elements
			$('div#permissions_container_' + id + ' div.checkbox input').each( function() {
				$(this).prop('disabled', false);
			});
		} else {
			// Clear all the values within the permissions_container block.
			$('div#permissions_container_' + id + ' div.checkbox input').each( function() {
				$(this).prop('checked', false);
				$(this).prop('disabled', true);
			});
		}
	});
	
});