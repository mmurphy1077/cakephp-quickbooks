<script type="text/javascript">
	$(function() {
		$("#OrderDateRequest").datepicker({
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) {
		          var date = $(this).datepicker('getDate');
		          $(this).val(dateStr);

		          var dateText = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate"));
				  var required_days = '<?php echo date ('Y-m-d', strtotime(ORDER_REQUEST_DATE_MINIMUM_DAYS .' weekdays')); ?>';
		          if(dateText < required_days) {
			          // Activate label for 'Rush approval required'
			          $('div#input-warning-msg-date-req').removeClass('hide');
			          if($('div#approve_date_request_container').length) {
		        	  	$('div#approve_date_request_container').removeClass('hide');
		        	  }

		        	  $('#OrderApproveDateRequest').prop('checked', false);
		        	  $('#OrderAlertDateRequestRequireApproval').val(1);
		          } else {
		        	  $('div#input-warning-msg-date-req').addClass('hide');
		        	  if($('div#approve_date_request_container').length) {
		        	  	$('div#approve_date_request_container').addClass('hide');
		        	  }
		        	  $('#OrderAlertDateRequestRequireApproval').val(0);
		          }	
		    }
		});

		$("#OrderDateRequestComplete").datepicker({
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $(this).val(dateStr);

	          var dateText = $.datepicker.formatDate("yy-mm-dd", $(this).datepicker("getDate"));
			  var required_days = '<?php echo date ('Y-m-d', strtotime(ORDER_REQUEST_DATE_COMPLETE_MINIMUM_DAYS .' weekdays')); ?>';
	          if(dateText < required_days) {
		          // Activate label for 'Rush approval required'
		          $('div#input-warning-msg-date-req-complete').removeClass('hide');
		          if($('div#approve_date_request_complete_container').length) {
	        	  	$('div#approve_date_request_complete_container').removeClass('hide');
	        	  }

	        	  $('#OrderApproveDateRequestComplete').prop('checked', false);
		          $('#OrderAlertDateRequestCompleteRequireApproval').val(1);
	          } else {
	        	  $('div#input-warning-msg-date-req-complete').addClass('hide');
	        	  if($('div#approve_date_request_complete_container').length) {
	        	  	$('div#approve_date_request_complete_container').addClass('hide');
	        	  }
	        	  $('#OrderAlertDateRequestCompleteRequireApproval').val(0);
	          }	
		    }
		});

		$(".order_datepicker").datepicker({
			changeMonth: true,
			changeYear: true,
			onSelect: function(dateStr) {
		          var date = $(this).datepicker('getDate');
		          $(this).val(dateStr);
		    }
		});

		var dates = $("#OrderTaskDateCreated, #OrderTaskDateRequest").datepicker({
			changeMonth: true,
			changeYear: true,
			numberOfMonths: 1,
			onSelect: function( selectedDate ) {
				var option = this.id == "OrderTaskDateCreated" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				dates.not( this ).datepicker( "option", option, date );
			}
		}).attr('readonly','readonly');
	});
</script>