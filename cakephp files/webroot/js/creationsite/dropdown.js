$(function () {
	$('#quick-actions ul').hide();
    $('#quick-actions').hover(function () {
        $(this).find('ul').show(200);
    }, function () {        
        $(this).find('ul').hide();
    });
});