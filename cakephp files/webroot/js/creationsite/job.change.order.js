$(document).ready(function(){
	function startLoaderPO() {
		$('#co-loader').css('display', 'block');
	}
	function stopLoaderPO() {
		$('#co-loader').css('display', 'none');
	}
	$( "form" ).submit(function( event ) {
		//startLoaderPO();
	});
	
	// Adding a New PO
	$('#add_co').bind('click', function() {
		$('input#ChangeOrderRequestId').val('');
		tinyMCE.get('ChangeOrderRequestDescription').setContent('');
		$('input#ChangeOrderRequestPrice').val('');
		$('#arrove-co').attr('checked',false);
		$('input#ChangeOrderRequestDateAdj').val('');
    	return false;
    });
});