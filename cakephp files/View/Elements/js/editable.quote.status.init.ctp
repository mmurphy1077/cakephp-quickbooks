<script type="text/javascript">
	$(document).ready(function() {
		<?php $postback = $this->Html->url(array('controller' => 'quotes', 'action' => 'edit_status_field')); ?>
		$('.<?php echo $editClass; ?>').editable('<?php echo $postback; ?>', { 
			data: '<?php echo json_encode($values); ?>',
			submitdata: {output: <?php echo json_encode(serialize($values)); ?>},
		    type: 'select',
		    onblur: 'cancel',
	    });
		
		$(document).on("change", '.<?php echo $editClass; ?> form select', function() {	
			var value = $(this).val();
			/*
			if(value == 100) {
				var msg = "Marking the quote as 'Sold' will convert the quote to a job.  Do you wish to proceed?",
					r=confirm(msg);
		        if (r==false) {
			        // Do not submit!
		        	return(false);
		        } 
			}
			*/
            $(this).parent().submit();
      	});
	});
</script>