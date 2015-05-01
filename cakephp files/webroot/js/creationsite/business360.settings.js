function add_rate_line() {
	var table_id = 'rate_select_container',
		last_id = $('table#' + table_id + ' tr.rate-item').last().attr('id'),
		next =  parseInt(last_id.replace('rate_item_', '')) + 1;
	
	var new_line = '<tr id="rate_item_'+ next +'" class="rate-item">' + $('table#' + table_id + ' tr#rate_item_'+last_id.replace('rate_item_', '')).html() + '</tr>';
	$('table#' + table_id).append(new_line);
	
	// Update the id's and name attribute of the new line.
	$('table#' + table_id + ' tr#rate_item_'+ next+ ' .id').attr('id', 'invoice_material_item_id_'+next).attr('name', 'data[InvoiceMaterialItem]['+next+'][id]');
	$('table#' + table_id + ' tr#rate_item_'+ next+ ' .rate_name').attr('id', 'rate_name_'+next).attr('name', 'data[Rate]['+next+'][name]');
	$('table#' + table_id + ' tr#rate_item_'+ next+ ' .rate_rate').attr('id', 'rate_rate_'+next).attr('name', 'data[Rate]['+next+'][rate]');
	$('table#' + table_id + ' tr#rate_item_'+ next+ ' .rate-item-delete').attr('id', 'rate-item-delete-'+next);
}

$(document).ready(function() {
	
	$(document).on("click", "#add_more_rates", function() {
		add_rate_line();
		return false;
	});
});