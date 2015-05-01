$(document).ready(function(){
	$('table.sortable tbody').sortable({
		cursor: 'move',
		//cancel: "table.sortable tbody tr.nodrag",
		stop: function(e, ui) {
		 //alert(ui.item.attr('id'));
		}
	});
});