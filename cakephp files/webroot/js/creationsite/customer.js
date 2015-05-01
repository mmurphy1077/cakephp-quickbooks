$(function(){
	$('.action').bind('click', function() {
		var id = $(this).attr('id')
		
		$('li.action').each( function() {
			$(this).removeClass('current');
		});
		$('li#' + id).addClass('current');
		
		$('.requirement_container').each( function() {
			$(this).removeClass('current');
		});
		$('#' + id + '_requirement_container').addClass('current');
	});
	
	$('#add-status').bind('click', function() {
		$('#add-status-item-container').toggle();
		return false;
	});
	
	$('ul.tabs li').bind('click', function() {
		$('ul.tabs li').each( function() {
			$(this).removeClass('active');
		});
		
		$(this).addClass('active');
	});
	
	$('#include_cover_letter').bind('click', function() {
		if($(this).prop('checked')) {
			$('#cover-letter-container').css('display', 'block');
		} else {
			$('#cover-letter-container').css('display', 'none');
		}
	});
	
	/***********
	*	A ACTIVITY LOG BUTTON IS SELECTED
	*/
	$('.materials_button').on('click', function() {
		var category_id = $(this).attr('id').substring(17);
		if($(this).hasClass('collapse')) {
			expand_category(category_id);
		} else {
			collapse_category(category_id);
		}
		return false;
	});
	
	function expand_category(category_id) {
		// Adjust buttons displayed.
		$('#materials_button_'+category_id).addClass('expand');
		$('#materials_button_'+category_id).removeClass('collapse');
		
		// Adjust css for the categories table.
		$('#category_table_container_'+category_id).css('display', 'block');

		// Call ajax functionality to update the Materials Session values
		//updateCategorySession(category_id, 'add');
	}

	function collapse_category(category_id) {
		// Adjust buttons displayed.
		$('#materials_button_'+category_id).removeClass('expand');
		$('#materials_button_'+category_id).addClass('collapse');

		// Adjust css for the categories table.
		$('#category_table_container_'+category_id).css('display', 'none');

		// Call ajax functionality to update the Materials Session values
		//updateCategorySession(category_id, 'delete');
	}
});