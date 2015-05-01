function today_date() {
	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth()+1; //January is 0!
	var yyyy = today.getFullYear();
	if(dd<10){dd='0'+dd} if(mm<10){mm='0'+mm} today = mm+'/'+dd+'/'+yyyy;
	
	return mm+'/'+dd+'/'+yyyy;
}

$(function() {
	var dates = $( "#datepicker_from, #datepicker_to" ).datepicker({
		changeMonth: true,
		changeYear: true,
		numberOfMonths: 1,
		onSelect: function( selectedDate ) {
			var option = this.id == "datepicker_from" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		}
	}).attr('readonly','readonly');
});

$(function() {
	$( ".datepicker_cluetip" ).datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
	          var date = $(this).datepicker('getDate');
	          $('input.date_schedule').each(function() {
	        	  $(this).val(dateStr);
	          });
	    }	
	});
});

$(function() {
	$( ".order_req_datepicker_cluetip" ).datepicker({
		changeMonth: true,
		changeYear: true,
		onSelect: function(dateStr) {
          var date = $(this).datepicker('getDate');
          $('input.order_req_datepicker_cluetip').each(function() {
        	  $(this).val(dateStr);
          });
	    }	
	});
});

// SELECT THE ENTIRE WEEK //
$(function () {	
    $('.weekpicker').datepicker({
    	onSelect: function(dateStr) {
	    	// No matter what day is picked, always get the 'Monday', This way if they select 
	    	// A sunday, we will return the date for the previous Monday in order to display the appropriate week.
	        var mon = $(this).datepicker('getDate');
	        mon.setDate(mon.getDate() + 1 - (mon.getDay() || 7));
	       
	        var selected = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));
	        var week_beginning = $.datepicker.formatDate('yy-mm-dd', mon);
	        
	        // Determine if the form has an order id
	        var controller = 'schedules',
	        	action = 'index_week',
	        	params = '',
	        	order_id = null
	        if($('#order_id').length > 0) {
	        	order_id = $('#order_id').attr('value');
	        	controller = 'orders';
	        }
			params = params + 'week_start:'+ week_beginning + '/';
			params = params + 'date_selected:'+ selected + '/';
			params = params + 'order_id:'+ order_id + '/';
			
			var url = controller + '/' + action + '/' + params; // get selected value
			if (url) { // require a URL
	              window.location = myBaseUrl + url; // redirect
	        }
	        return false;
    	}
    });
    
    $('.daypicker').datepicker({
    	onSelect: function(dateStr) {
	    	var selected = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));

	        // Determine if the form has an order id
	    	var controller = 'schedules',
	        	action = 'index_day',
	        	params = '',
	        	order_id = null
	        if($('#order_id').length > 0) {
	        	order_id = $('#order_id').attr('value');
	        	controller = 'orders';
	        }
	 
			params = params + 'date_selected:'+ selected + '/';
			params = params + 'order_id:'+ order_id + '/';
			
			var url = controller + '/' + action + '/' + params; // get selected value
			if (url) { // require a URL
	              window.location = myBaseUrl + url; // redirect
	        }
	        return false;
    	}
    });
    
    $('.mappicker').datepicker({
    	onSelect: function(dateStr) {
	    	var selected = $.datepicker.formatDate('yy-mm-dd', $(this).datepicker('getDate'));

	        // Determine if the form has an order id
	        var order_id = null;
	        if($('#order_id').length > 0) {
	        	order_id = $('#order_id').attr('value');
	        }

	        var controller = 'schedules';
			var action = 'index_map';
			var params = '';
			params = params + 'date_selected:'+ selected + '/';
			params = params + 'order_id:'+ order_id + '/';
			
			var url = controller + '/' + action + '/' + params; // get selected value
			if (url) { // require a URL
	              window.location = myBaseUrl + url; // redirect
	        }
	        return false;
    	}
    });
});