<script type="text/javascript">
	function updateOrderLaborStats(order_id) {
		// Build a parameter list to lend to the server
		var params = 'order_id:'+ order_id + '/';
		
		$.ajax({
			url: myBaseUrl + "order_times/ajax_update_labor_stats/"+params,
			beforeSend: function() {
				if (params==""){
					return false;
				}
			},
			complete: function(data, textStatus){
				// Handle the complete event
				if(textStatus == 'success') {
					var results = data.responseText,
						obj = jQuery.parseJSON(results),
						html = obj.html;
			
					$('#job_status_container').html(html);
					return true;
				} else {
					return false;
				}
			},
		});
	}
	
	$(document).ready(function() {
		$('.edit-status-admin').editable('<?php echo $this->Html->url(array('controller' => 'order_times', 'action' => 'edit_field')); ?>', {
				data: '<?php echo json_encode($adminStatuses); ?>',
				type: 'select',
				onblur: 'cancel',
				submitdata: {output: <?php echo json_encode(serialize($adminStatuses)); ?>},
				callback : function(value, settings) {
			         // Obtain the order_id and update stats.
			         var order_id = $('#order_id').val();
			         updateOrderLaborStats(order_id);
			     }
		});

		$(document).on("change", '.edit-status-admin form select', function() {	
            $(this).parent().submit();
      	});
	});
</script>