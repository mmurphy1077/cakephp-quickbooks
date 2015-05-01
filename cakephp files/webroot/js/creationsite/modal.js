$(document).ready(function(){
	 $( "#dialog-form" ).dialog({
		 autoOpen: false,
		 width: 800,
		 modal: true,
	 });
	 
	 $( "#dialog-form-opener" ).click(function() {
		 $( "#dialog-form" ).dialog( "open" );
	 });
});