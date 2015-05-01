<?php
$this->Html->css('jquery/jquery.jscrollpane', null, array('inline' => false));
$this->Html->script('jquery/jquery.mousewheel', false);
//$this->Html->script('jquery/jquery.jscrollpane.min', false);
$this->Html->script('jquery/jquery.jscrollpane', false);
?>
<script type="text/javascript">
$(function() {
	$('.jscrollpane').jScrollPane({
		showArrows: true,
		autoReinitialise: true,
	});
	$('.jscrollpane_mini').jScrollPane({
		showArrows: true,
		autoReinitialise: true,
		/*arrowScrollOnHover: true,*/
	});
});
</script>