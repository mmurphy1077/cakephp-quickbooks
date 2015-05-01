function getCityFromZip(str) {
	$.ajax({
		url:  myBaseUrl + "zip_codes/ajax_get_city_for_zip/"+str,
		beforeSend: function() {
			if (str==""){
				return false;
			}
			document.getElementById("ajax-loader-city").style.display = 'inline';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var city = data.responseText.trim();
				// Once the function is executed, the following is the element is updated.
				$('input.city').each( function() {
					$(this).val(city);
				});
			}
			document.getElementById("ajax-loader-city").style.display = 'none';
		},
	});
}

function getStateFromZip(str) {
	$.ajax({
		url:  myBaseUrl + "zip_codes/ajax_get_states_for_zip/"+str,
		beforeSend: function() {
			if (str==""){
				return false;
			}
			document.getElementById("ajax-loader-state").style.display = 'inline';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var state = data.responseText.trim();
				// Once the function is executed, the following is the element is updated.
				$('select.state').each( function() {
					$(this).val(state);
				});
			}
			document.getElementById("ajax-loader-state").style.display = 'none';
		},
	});
}

function getZipFromAddress(str, id) {
	$.ajax({
		url:  myBaseUrl + "zip_codes/ajax_zip_code_from_address/"+str,
		beforeSend: function() {
			if (str==""){
				return false;
			}
			document.getElementById("ajax-loader-zip-" + id).style.display = 'inline';
		},
		complete: function(data, textStatus){
			// Handle the complete event
			if(textStatus == 'success') {
				var zip = data.responseText.trim();
				
				// Once the function is executed, the following is the element is updated.
				$('input#' + id + 'ZipPost').val(zip);
				$('input#' + id + 'ZipPost').removeClass('required_color');
			}
			document.getElementById("ajax-loader-zip-" + id).style.display = 'none';
		},
	});
}

$(document).ready(function(){
	$('.zipcode-lookup').bind('click', function() {
		var id = $(this).attr('id');
		// Remove "zipcode-lookup-" to isolate the id.
		id = id.replace('zipcode-lookup-', '');
		var line1 = $('#' + id + 'Line1').val() + ', ';
		var line2 = $('#' + id + 'Line2').val() + ', ';
		var city = $('#' + id + 'City').val() + ', ';
		var state = $('#' + id + 'StProv').val();
		getZipFromAddress(line1+city+state, id);

		return false;
	});
	$('#adress-lookup').bind('click', function() {
		var zip = $('#zipcode').attr('value');
		if(zip.length > 0) {
			getCityFromZip(zip);
			getStateFromZip(zip);
		}
		return false;
	});
});