$(document).ready(function(){
	$("#QuoteDateRequest").datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);    

	          var dateText = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")),
	          	  required_days = $('input#quote_minimum_days_to_quote_due').val(),
	          	  today = new Date();
	          	  min_days_alert = new Date(today.getTime() + (required_days * 86400000));
	          if($(this).datepicker("getDate") < min_days_alert) {
		          // Activate label for 'Rush approval required'
		          $('div#input-warning-msg-date-req').removeClass('hide');
		          if($('div#approve_date_request_container').length) {
	        	  	$('div#approve_date_request_container').removeClass('hide');
	        	  }

	        	  $('#QuoteApproveDateRequest').prop('checked', false);
	        	  $('#QuoteAlertDateRequestRequireApproval').val(1);
	          } else {
	        	  $('div#input-warning-msg-date-req').addClass('hide');
	        	  if($('div#approve_date_request_container').length) {
	        	  	$('div#approve_date_request_container').addClass('hide');
	        	  }
	        	  $('#QuoteAlertDateRequestRequireApproval').val(0);
	          }	
	    }
	});

	$("#QuoteDateRequestComplete").datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);

	          var dateText = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate")),
	          	  required_days = $('input#quote_minimum_days_to_job_start').val(),
	          	  today = new Date();
	          	  min_days_alert = new Date(today.getTime() + (required_days * 86400000));
	          if($(this).datepicker("getDate") < min_days_alert) {
		          // Activate label for 'Rush approval required'
		          $('div#input-warning-msg-date-req-complete').removeClass('hide');
		          if($('div#approve_date_request_complete_container').length) {
	        	  	$('div#approve_date_request_complete_container').removeClass('hide');
	        	  }

	        	  $('#QuoteApproveDateRequestComplete').prop('checked', false);
		          $('#QuoteAlertDateRequestCompleteRequireApproval').val(1);
	          } else {
	        	  $('div#input-warning-msg-date-req-complete').addClass('hide');
	        	  if($('div#approve_date_request_complete_container').length) {
	        	  	$('div#approve_date_request_complete_container').addClass('hide');
	        	  }
	        	  $('#QuoteAlertDateRequestCompleteRequireApproval').val(0);
	          }	
	    }
	});

	var dates = $("#QuoteTaskDateCreated, #QuoteTaskDateRequest").datepicker({
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		onSelect: function( selectedDate ) {
			var option = this.id == "QuoteTaskDateCreated" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		}
	}).attr('readonly','readonly');

	$(".quote_datepicker").datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);
	    }
	});
});