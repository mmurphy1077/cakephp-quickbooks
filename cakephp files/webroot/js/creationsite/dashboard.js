$(document).ready(function(){
	$('.dashboard-completed').bind('click', function() {
		var id = $(this).attr('id');
		$('.completed-row-' + id).each( function() {
			$(this).toggle();
		});
		return false;
	});
});