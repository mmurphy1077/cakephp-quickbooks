$(document).ready(function(){
	// Fancybox gallery image configuration
	$("a[rel=page-gallery]").fancybox({
		'transitionIn': 'none',
		'transitionOut': 'elastic',
		'titlePosition': 'over',
		'cyclic': true
	});
	// Fancybox iframe configuration (i.e. product details)
	$("a.iframe").fancybox({
		'transitionIn': 'none',
		'transitionOut': 'elastic',
		'titlePosition': 'over',
		'width': 936,
		'height': 520,
		'type': 'iframe'
	});	
});