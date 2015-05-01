<script type="text/javascript">
	$(document).ready(function(){
		$('#button_generate_order').bind('click', function() {
			$('#OrderAction').val('generate_work_request');
			document.getElementById('printDocs').submit();
			return false;
		});

		$('#button_generate_system_docs').bind('click', function() {
			$('#OrderAction').val('generate_system_docs');
			document.getElementById('printDocs').submit();
			return false;
		});

		$('#button_generate_purchase_orders').bind('click', function() {
			$('#OrderAction').val('generate_po_docs');
			document.getElementById('printDocs').submit();
			return false;
		});

		$('#button_generate_change_order_requests').bind('click', function() {
			$('#OrderAction').val('generate_co_docs');
			document.getElementById('printDocs').submit();
			return false;
		});
	});
</script>