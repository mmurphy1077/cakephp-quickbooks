$(function(){
	$('#group-buttons-container .ui-helper-hidden-accessible').bind('click', function() {
		var id = $(this).attr('id').replace('UserGroupId', '');
		$('.checkbox_container').each( function() {
			$(this).css('display', 'none');
		});
		$('.checkbox_container input').each( function() { 
			$(this).attr('readonly', true);
			$(this).attr('disable', true);
		});
		$('#checkbox_container_' + id).css('display', 'block');
		$('#checkbox_container_' + id + ' input').each( function() { 
			$(this).attr('readonly', false);
			$(this).attr('disable', false);
		});
	});
});