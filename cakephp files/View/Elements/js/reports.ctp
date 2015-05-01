<?php #$this->Html->script('jquery/jquery.form.min', array('inline' => false)); ?>
<script type="text/javascript">
	$(document).ready( function() {
		function generateAjaxReport() {
			// Build a parameter list to lend to the server
			//var params = '';
			//params = params + 'customer_id:'+ id + '/';
			
			$.ajax({
				url: myBaseUrl + "reports/ajax_generate_report/",
				data: $("#form-report").serialize(),
				beforeSend: function() {
					//if (params==""){
					//	return false;
					//}
					document.getElementById("page-loader").style.display = 'block';
				},
				complete: function(data, textStatus){
					// Handle the complete event
					if(textStatus == 'success') {
						var obj = jQuery.parseJSON(data.responseText);
						var success = obj.success;
						var result = obj.result;
						var error = obj.error;
						$('div#report_data_container').html(result);
					} else {
						
					}
					document.getElementById("page-loader").style.display = 'none';
				},
			});
		}
		
	    $('div#reports_container ul li a').bind('click', function() {
			var id = $(this).attr('id');
			$('#ReportCurrentReport').val(id);

			//document.getElementById('form-report').submit();\
			generateAjaxReport(); 
			return false;
		});

		function getThisMonth() {
			var d = new Date();
		 	var n = ('0' + (Number(d.getMonth()) + 1)).slice(-2);
		    var y = d.getFullYear();
			var lastDay = new Date(y, d.getMonth() + 1, 0);
			var ld = lastDay.getDate();

			var from = n+'/01/'+y;
			var to = n+'/'+ld+'/'+y;
			$('#datepicker_to').val(to);
			$('#datepicker_from').val(from);
		} 

		function getLastMonth() {
			var d = new Date();
			var y = d.getFullYear();
			var lastDayOfLastMonth = new Date(y, d.getMonth(), 0);
			var n = ('0' + (Number(lastDayOfLastMonth.getMonth()) + 1)).slice(-2);
			var y = lastDayOfLastMonth.getFullYear();
			var ld = lastDayOfLastMonth.getDate();

			var from = n+'/01/'+y;
			var to = n+'/'+ld+'/'+y;
			$('#datepicker_to').val(to);
			$('#datepicker_from').val(from);
		} 

		function getThisYear() {
			var d = new Date();
			var n = ('0' + (Number(d.getMonth()) + 1)).slice(-2);
			var s = ('0' + (Number(d.getDate()) + 1)).slice(-2);
			var y = d.getFullYear();
			var from = '01/01/'+y;
			var to = n + '/' + s + '/'+y;
			$('#datepicker_to').val(to);
			$('#datepicker_from').val(from);
		} 
		
		$('#ReportQuickDates').bind('change', function() {
			var value = $(this).val();
			
			/*
			 * For now the user can select on of four options.
			 * 'Select' (val of 0)... Does nothing to the date
			 * 'this-month'
			 * 'last-month'
			 * 'year-to-date'
			 */
			 switch(value)
			 {
			 	case 'this-month':
			   		getThisMonth();
			   		break;
			 	case 'last-month':
					getLastMonth();
			  	 	break;
			 	case 'year-to-date':
				 	getThisYear();
				 	break;
			 	default:
			   		// Nothing for now.
			 }
			
			return false;
		});

		var displayOpions = [];
		<?php 
		if(!empty($display_by_options_array)) :
			foreach($display_by_options_array as $key => $data) : 
				$html = '<option value="0">Select</option>';
				if(is_array($data)) :
					foreach($data as $id=>$element) :
						$html = $html."<option value=\"".$id."\">".str_replace("'", "\'", $element)."</option>";
					endforeach;
				endif; 
				?>
		displayOpions['<? echo $key; ?>'] = '<?php echo $html; ?>';	
		<?php endforeach;
		endif; 
		?>
		$('#ReportDisplayBy').bind('change', function() {
			var value = $(this).val();
			// Clear out the displayOptions
			$('#ReportShowOnly').html('');
			
			if(displayOpions.hasOwnProperty(value)) {
				$('#ReportShowOnly').html(displayOpions[value]);
				$('#ReportShowOnly').prop('disabled', false);
			} else {
				$('#ReportShowOnly').prop('disabled', true);
			}
			return false;
		});
	});
</script>