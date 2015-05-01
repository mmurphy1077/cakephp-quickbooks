$(document).ready(function() {
	$('table.hover tr').click(function() {
		var href = $(this).find('a.row-click').attr('href');
		if (href) {
			window.location = href;
		}
	});
});