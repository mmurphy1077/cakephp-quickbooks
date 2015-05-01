function toggleOrderTimesTable(type, order_id) {
	var params = '';
	params = params + 'order_type:'+ type + '/';
	params = params + 'order_id:'+ order_id + '/';
	
	$.ajax({
		url: myBaseUrl + "order_times/ajax_toggle_table_order_type/"+params,
		beforeSend: function() {
			if (params==""){
				return false;
			}
			startPageLoader();
		},
		complete: function(data, textStatus){
			// Handle the complete event
			stopPageLoader();
			if(textStatus == 'success') {
				var obj = jQuery.parseJSON(data.responseText),
					html = obj.html,
					order_type = obj.order_type;
				$('#order-times-table-container').html(html);
			} else {
				
			}
		},
	});
}

$(document).ready(function(){
	$('#order_by').bind('change', function() {
		var order_id = $('#order_id').val();
		toggleOrderTimesTable($(this).val(), order_id);
    	return false;
    });
});