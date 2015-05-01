$(document).ready(function(){
	$(document).on("click", "input#SearchKeyword", function() {
		$('#background').css('display', 'none');
		return true;
	});
	
	$(document).on("click", "#background", function() {
		$(this).css('display', 'none');
		$('input#SearchKeyword').select();
		return false;
	});
	
	$(document).on("click", "#search-criteria-select", function(e) {
		$('#search-criteria-cluetip-container').removeClass('hide');
		$('#search-criteria-cluetip-container').css('display', 'block');
		
		var position = $(this).offset(),
			mousePosX = position.left-150,
			mousePosY = position.top+20;
		
		$('#search-criteria-cluetip-container').offset({ top: mousePosY, left: mousePosX });
		return false;
	});

	function closeSearchCriteriaCluetip() {
		$('#search-criteria-cluetip-container').css('display', 'none');
	}
	
	$(document).on("click", ".search-criteria-option", function() {
		var value = $(this).attr('id').replace('search-criteria-option-', ''),
			display = $(this).html();
		$('#SearchIndexCriteria').val(value);
		$('#search-criteria-select').html(display);
		closeSearchCriteriaCluetip();
		
		// Determine if the option selected has the class 'message-type-option'
		if($(this).hasClass('message-type-option')) { 
			$('#selected_filter').val(value);
			filter(value);
		}
		return false;
	});
	
	$(document).on("click", "#search-criteria-cluetip-container-close", function() {
		closeSearchCriteriaCluetip();
	});
	
	$(document).on("mouseleave", "#search-criteria-cluetip-container", function() {
		closeSearchCriteriaCluetip();
    });
	
	/**
	 * Invoice Index
	 */
	$(document).on("change", "#filter-invoice-ready-to-invoice", function() {
		if($(this).prop('checked')) {
			// Remove all Filters associated 
			$('.filter-status').each( function() {
				$(this).prop('checked', false);
			})
		}
	});
	
	$(document).on("change", ".filter-status", function() {
		if($(this).prop('checked')) {
			// Remove all Filters from any independent searches. 
			if($('#filter-invoice-ready-to-invoice').length) {
				$('#filter-invoice-ready-to-invoice').prop('checked', false);
			}
		}
	});
	
	$(document).on('keyup', "#table-filter-keyword", function (event) {
		var value = $(this).val().toLowerCase(),
			key = event.key,
			haystack = '',
			id = '',
			index = -1;
		
		$("table.table-filter tr").each( function() {
			haystack = $('div#data-bank-' + $(this).attr('id')).html();
			if(haystack) {
				index = haystack.toLowerCase().indexOf(value);
				if((value.length > 0) && index == -1) {
					// Doesn't exist
					$(this).css('display', 'none');
				} else {
					$(this).css('display', 'table-row');
				}
			}
		});
		return false;
	});
	
	$(document).on('click', "a#clear-table-filter", function (event) {
		$("#table-filter-keyword").val('');
		$("table.table-filter tr").each( function() {
			$(this).css('display', 'table-row');
		});
		return false;
	});
});

function filter(val) {
	if(val == 'all') {
		// Turn them all on
		$('.individual-message-container').each( function() {
			$(this).css('display', 'block');
			$(this).removeClass('hidden');
		});
	} else {
		// Turn off all
		$('.individual-message-container').each( function() {
			$(this).css('display', 'none');
		});
		
		// Then turn on the ones with class equal to val
		$('.individual-message-container').each( function() {
			if($(this).hasClass(val)) {
				$(this).css('display', 'block');
				$(this).removeClass('hidden');
			}
		});
		
	}
}