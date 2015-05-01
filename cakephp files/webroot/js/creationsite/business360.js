	var quarterlyIntervals = false;
	
	function startPageLoader() {
		$('#page-loader').css('display', 'block');
	}
	function stopPageLoader() {
		$('#page-loader').css('display', 'none');
	}
	$('.trigger-page-loader').bind('click', function() {
		startPageLoader();
		return true;
	});
	
	function formatTime(time) {
		time = time + '';
		
		// Format input
		if (time.indexOf(':') >= 0) {
			time_array = time.split(":");
			time = time_array[0] + time_array[1];
		}
	    var hh = parseInt(time/100);
	    var m = time%100;
	    var dd = "am";
	    var h = hh;
	    if (h >= 12) {
	        h = hh-12;
	        dd = "pm";
	    }
	    if (h == 0) {
	        h = 12;
	    }
	    m = m<10?"0"+m:m;
	    /* if you want 2 digit hours:
	    h = h<10?"0"+h:h; */

	   // var pattern = new RegExp("0?"+hh+":"+m);

	    replacement = new Array();
	    replacement['hour'] = h;
	    replacement['hour24'] = hh;
	    replacement['min'] = m;
	    replacement['post'] = dd;
	    replacement['hmp'] = h + ':' + m + ' ' + dd;
	    replacement['db'] = hh + ':' + m + ':00';
	    replacement['min_decimal'] = m/60;
	    return replacement;
	}
	
	function calculateCurrentTime() {
		var dt = new Date(),
		hour = dt.getHours(),
		minute = dt.getMinutes(),
		minuteAdjusted = '00';
		
		if(quarterlyIntervals) {
			if(minute == 0) {
				minuteAdjusted = '00';
			} else if(minute <= 15) {
				minuteAdjusted = '15';
			} else if(minute <= 30) {
				minuteAdjusted = '30';
			} else if(minute <= 45) {
				minuteAdjusted = '45';
			} else {
				minuteAdjusted = '00';
				
				// Pop up to the next hour!
				hour = hour + 1;
				if(hour == 13) {
					hour = 1;
				}
			}		
		} else {
			minuteAdjusted = minute.toString();
		}
		minuteAdjusted = String("00" + minuteAdjusted).slice(-2); 
		
		var newEndTime = hour + minuteAdjusted;
		return newEndTime;
	}
	
	function deconstructSelectedTime(time) {
		// Date will always be in the form 'hh:mm:ss'
	    var h = time.substring(0,2);
	    var m = time.substring(3,5);
		return h+m;
	}
	
	function calculateStartEndDifference(startTime, endTime) {	
		// Grab the difference in work time.
		var total = 0,
			start = formatTime(startTime),
			end = formatTime(endTime),
			diff = new Date("Jan 01 1970 " + end['db']) - new Date("Jan 01 1970 " + start['db']),
			conv = diff/(60*60*1000);

		if(conv < 0) {
			//conv = 0;
		}
		conv = parseFloat(conv).toFixed(2)
		return conv;
	}
	
	function calculate_time_under_hour(start, end) {
		var ts = formatTime(start),
			te = formatTime(end);

		return (0 + te['min_decimal']) - ts['min_decimal'];
	}
	function calculate_time_over_hour(start, end) {
		var ts = formatTime(start);
		var te = formatTime(end);
		var hour_ds = parseInt(start/100);
		var hour_de = parseInt(end/100);
		start_time = parseFloat(hour_ds) + parseFloat(ts['min_decimal']);
		end_time = parseFloat(hour_de) + parseFloat(te['min_decimal']);
		return end_time - start_time;
	}

$(document).ready(function() {
	// Determine if the Response message needs to fade out
	if ($('div.response-msg').length > 0) { 
		if(!$('div.response-msg').hasClass('error')) {
			setTimeout(function() {
				$('div.response-msg').fadeOut( "slow", function() {
					// Animation complete.
				});
			}, 5000);
		}
	}
	
	$('div#close-response-msg').bind('click', function() {
		$('div.response-msg').css('display', 'none');
	});
	
	$('form').submit(function() {
		var submit = $(this).find('input[type="submit"]');

		// Only disable if the form is not part of a modal window.
		// If submit resides within a modal doc, enable in 10 seconds
//		submit.attr('disabled','disabled');
		if(submit.hasClass('modal_form') || submit.hasClass('ajax_form')) {
			setTimeout(function(){
				submit.prop('disabled', false);
		    }, 10000);
		} else {
			startPageLoader();
		}
		//$('.loader-container').css('display', 'block');
	});
	
	$('.redirect').bind('click', function() {
		var id = $(this).attr('id');
		
		// parse the id to determine the direction of the redirect.
		var n=id.split("_"); 
		$('#redirect').attr('value', n[1]);
		return true;
	});
	
	function isNumberKey(evt, value, allow_neg) {
	   var charCode = (evt.which) ? evt.which : evt.keyCode;
       if(!allow_neg) {
	       if (charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
	       		return false;
	       }
       } else {
    	   if (charCode != 45 && charCode != 46 && charCode > 31 && (charCode < 48 || charCode > 57)) {
	       		return false;
	       }
    	   if(charCode == 45) {
        	   // Check if '-' doesn't already exist one in the value variable
        	   if(value.indexOf("-") != -1 && value.length > 0) {
        		   return false;
        	   }
           }
       }
       if(charCode == 46) {
    	   // Check if there doesn't already exist one in the value variable
    	   if(value.indexOf(".") != -1) {
    		   return false;
    	   }
       }
       return true;
    }
	
	$(document).on('keypress', ".num_only", function (event) {
		var value = $(this).val();
		return isNumberKey(event, value, false);
	});
	
	$(document).on('keypress', ".num_only_allow_neg", function (event) {
		var value = $(this).val();
		return isNumberKey(event, value, true);
	});
	
	$(document).on('keyup', ".hours_only", function (event) {
		var value = $(this).val(),
			key = event.key;
			
		if(isNumberKey(event, value)) {
			if (value >= 1 && value <= 12) {
				return true;
			} 
		}
		// remove the last key entered
		var n = value.lastIndexOf(key);
		if(n == 0) {
			$(this).val('');
		} else if(n > 0) {
			$(this).val(value.substring(0, n));
		}
		return false;
	});
	
	$(document).on('keyup', ".minutes_only", function (event) {
		var value = $(this).val(),
			key = event.key;
		
		if(isNumberKey(event, value)) {
			if (value >= 0 && value <= 59) {
				return true;
			}
		}
		// remove the last key entered
		var n = value.lastIndexOf(key);
		if(n == 0) {
			$(this).val('00');
		} else if(n > 0) {
			$(this).val(value.substring(0, n));
		}
		return false;
	});
	
	$(document).on('blur', ".minutes_only", function (event) {
		if($(this).val() == 0) {
			$(this).val('00');
		} 
		return false;
	});
	
	/*$('.toggle_display_button').bind('click', function() {*/
	$('div#body').on('click', '.toggle_display_button', function() {
		var id = $(this).attr('id');
		$('#'+id+'_toggle_display').toggle();
    	return false;
    });
	
	$('div#body').on('click', '.tab_display_button', function() {
		var id = $(this).attr('id');
		$('.tab_display_button').each(function() {
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');
		
		$('.tab_display').each(function() {
			$(this).css('display', 'none');
			$(this).attr('readonly', true);
		});
		$('#'+id+'_tab_display').css('display', 'block');
		$('#'+id+'_tab_display').attr('readonly', false);
    	return false;
    });
	
	$('.toggle_page_link').bind('click', function() {
		var id = $(this).attr('id');
		
		$('.toggle_page_link').each( function() {
    		$(this).removeClass('current');
    	});
		$('#' + id).addClass('current');
		
    	$('.toggle_page_container').each( function() {
    		$(this).css('display', 'none');
    		$(this).removeClass('default');
    	});
    	$('#toggle_page_container_' + id).css('display', 'block');
    	
    	return false;
    });
	
	/*************************
	 * PHONE NUMBERS
	 */
	$(document).on('keyup', ".phone_number", function (event) {
		var id = $(this).attr('id');
		if($(this).val().length) {
			$('#ContactPhone' + id + 'LabelWork').prop('checked', true).button('refresh');
		} else {
			$('.ContactPhone' + id + 'Label').each(function() {
				$(this).prop('checked', false).button('refresh');
			});
		}
    });
	
	
	/*************************
	 * REQUIRED FIELDS
	 */
	
	function setRequired(element) {
		element.val('Required');
		element.addClass('required_color');
	}
	
	$('.required').focus( function() {
		if($(this).val() == 'Required') {
			$(this).val('');
			$(this).removeClass('required_color');
		}
	});
	$('.required').blur( function() {
		if($(this).val() == '') {
			setRequired($(this));
		}
	});
	
	
	// Loop through each element to see if there is a value
	$('input.required').each( function() {
		if(($(this).val().length == 0) || ($(this).val() == 'Required')) {
			setRequired($(this));
		}
	});
	
	$('#quick_comm_select').bind('change', function() {
		var text = $('#quick_comm_select').find(":selected").text() + ' ';
		$('#CommunicationLogComment').val($('#CommunicationLogComment').val() + text);
		
		// Reset the the drpop down
		$("#quick_comm_select").val('');
		return false;
	});
	
	
	/***********
	 *	AN ALERT BUTTON IS SELECTED
	 */
	function updateAlertSession(alert_id, mode, model) {
		// Build a parameter list to lend to the server
		var params = '';
		params = params + 'alert_id:'+ alert_id + '/';
		params = params + 'mode:'+ mode + '/';
		$.ajax({
			url: myBaseUrl + model + "/ajax_update_alerts_index/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					
				}
			},
		});
	}
	
	function expand_alert(alert_id, model) {
		// Adjust buttons displayed.
		$('#'+alert_id).addClass('expand');
		$('#'+alert_id).removeClass('collapse');
		
		// Adjust css for the categories table.
		$('#alert_table_container_'+alert_id).css('display', 'block');

		// Call ajax functionality to update the Order.Alert Session values
		updateAlertSession(alert_id, 'add', model);
	}

	function collapse_alert(alert_id, model) {
		// Adjust buttons displayed.
		$('#'+alert_id).removeClass('expand');
		$('#'+alert_id).addClass('collapse');

		// Adjust css for the categories table.
		$('#alert_table_container_'+alert_id).css('display', 'none');

		// Call ajax functionality to update the Materials Session values
		updateAlertSession(alert_id, 'delete', model);
	}
	
	$('.alerts_button').on('click', function() {
		var alert_id = $(this).attr('id');
		var model = $('input#model').val();
		if($(this).hasClass('collapse')) {
			expand_alert(alert_id, model);
		} else {
			collapse_alert(alert_id, model);
		}
		return false;
	});
	
	$('ul.form-tabs li').on('click', function() {
		// get the ul id
		var ul_id = $(this).closest('ul').attr('id'),
			selected_id = $(this).attr('id').replace('form-tabs-element-', '');
	
		$('ul#' + ul_id + ' li').each( function() {
			$(this).removeClass('active');
		});
		$(this).addClass('active');
		
		$('div.' + ul_id).each( function() {
			$(this).css('display', 'none');
		});
		$('div#' + selected_id + '.' + ul_id).css('display', 'block');
		return false;
	});
	
	$(document).on('click', '.cs-cluetip', function(event) {
        //var id = $(this).attr('id').replace('cs-cluetip-', ''),
        var	mouseX = event.pageX, 
        	mouseY = event.pageY,
        	scafold = '',
        	content = $(this).data('content');
        
	    
        // Close all other boxes on the page.
        $('.cs-cluetip-container').each( function() {
        	$(this).css('display', 'none');
        });
        $('#cs-cluetip-container' + ' #cs-cluetip-content').html(content);
	    $('#cs-cluetip-container').css('top', mouseY);
	    $('#cs-cluetip-container').css('left', mouseX);
	    $('#cs-cluetip-container').css('display', 'block');
	    
	    return false;
    });
	
	$(document).on('click', '#cs-cluetip-action-close', function(event) {
		// Close all other boxes on the page.
        $('.cs-cluetip-container').each( function() {
        	$(this).css('display', 'none');
        });
	});
	
	$(document).on("mouseleave", "#cs-cluetip-container", function() {
		// Close all other boxes on the page.
        $('.cs-cluetip-container').each( function() {
        	$(this).css('display', 'none');
        });
    });
	
	$(document).on('click', '#top-return', function( event ) {
		//event.preventDefault();	
		$("html, body").animate({ scrollTop: "0px" });
		return true;
	});
	
	/*
	 * SLIDER FUNCTIONALITY
	 */
	$( function(){
		if($('#button-comment').length) {
			// Check the initial Poistion of the Sticky Header
			var stickyCommentTop = $('#button-comment').offset().top + 40;
			
			$(window).scroll(function(){
				// Check Header position
				if( $(window).scrollTop() > stickyCommentTop ) {
					$("#comment-container-fixed").slideDown();
				} else {
					$("#comment-container-fixed").slideUp();
				}
			});
		}
	});
	
	$(document).on('click', '.slider-activate', function(event) {
		$('.slider').toggle('slide');
		return false;
	});
	
	$(document).on('click', '.slider-close', function(event) {
		$('.slider').toggle('slide');
		return false;
	});
	
	$(document).on('click', '#mobile-menu-toggle', function(event) {
		if($('#mobile-menu-container').hasClass('hidden')) {
			//$('#mobile-content-container').css('left', '310px');
			$('#mobile-content-container').animate({left: '310px'}, 100);
			$('#mobile-menu-container').removeClass('hidden');
		} else {
			$('#mobile-content-container').css('left', '0');
			$('#mobile-menu-container').addClass('hidden');
		}
		return false;
	});
	
	/**
	 * Status and Assinged to changes for Order, Quote, and Invoice
	 */
	function updateStatusAssignment(id, field, value, model) {
		var params = 'id:'+ id + '/';
		params = params + 'field:'+ field + '/';
		params = params + 'value:'+ value + '/';
		
		// Determine which model to update.
		switch(model) {
			case 'Contact' :
				controller = 'contacts';
				break;
			case 'Order' :
				controller = 'orders';
				break;
			case 'Quote' :
				controller = 'quotes';
				break;
			case 'Invoice' :
				controller = 'invoices';
				break;
		}
		
		$.ajax({
			url: myBaseUrl + controller + "/ajax_update_field/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
				startPageLoader();
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					//var obj = jQuery.parseJSON(data.responseText);
					switch(field) {
						case 'status' :
							$('#status-value').html($("#status-container-status-select option:selected").text());
							break;
						case 'assigned_to_id' :
							$('#assigned-to-value').html($("#status-container-assigned-select option:selected").text());
							break;
					}
				} else {
					
				}
				stopPageLoader();
			},
		});
	}
	
	$(document).on('change', '#status-container-status-select', function(event) {
		// Determine which model to update.
		var model = $('#status-container-model').val(),
			id = $('#status-container-foreign-key').val(),
			value = $(this).val();
			
		updateStatusAssignment(id, 'status', value, model);
		return false;
	});
	$(document).on('change', '#status-container-assigned-select', function(event) {
		// Determine which model to update.
		var model = $('#status-container-model').val(),
			id = $('#status-container-foreign-key').val(),
			value = $(this).val();
		
		updateStatusAssignment(id, 'assigned_to_id', value, model);
		return false;
	});
	$(document).on('click', '#status-container-button-open', function(event) {
		$('#status-assignment-container').slideDown(300);
		$(this).css('display', 'none');
		$('#status-container-display').css('display', 'none');
		
	});
	$(document).on('click', '#status-container-button-close', function(event) {
		$('#status-assignment-container').slideUp(300);
		$('#status-container-button-open').slideDown(400);
		$('#status-container-display').css('display', 'block');
	});
	
	/*******
	 * 	CHECKBOX as Radio button
	 */
	$(document).on("click", 'div.checkbox-radio-style input[type="checkbox"]', function( evt ) {
        $('div.checkbox-radio-style input[type="checkbox"]').not($(this)).removeAttr("checked");
        if($(this).attr("checked")) {
        	return false;
        } else {
        	$(this).attr("checked", true);
        }
    });
});