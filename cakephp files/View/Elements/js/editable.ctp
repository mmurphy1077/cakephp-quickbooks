<script type="text/javascript">
	$(document).ready(function() {
		$('.<?php echo $editClass; ?>').editable('<?php echo $postback; ?>', {
			<?php if (!empty($values)): ?>
				data: '<?php echo json_encode($values); ?>',
				type: 'select',
				onblur: 'cancel',
			<?php else: ?>
				type: 'text',
				data: function(string) {return $.trim(string)},
				select : true,
				onblur : 'submit',
			<?php endif; ?>
			<?php if (!empty($output)): ?>
				submitdata: {output: <?php echo json_encode(serialize($output)); ?>},
			<?php endif; ?>
			<?php if(empty($submitOnChange)) : ?>
			submit: 'OK',
			<?php endif; ?>
		});

		<?php if(!empty($submitOnChange)) : ?>
		$(document).on("change", '.<?php echo $editClass; ?> form select', function() {	
            $(this).parent().submit();
      	});
		<?php endif; ?>
	});
</script>