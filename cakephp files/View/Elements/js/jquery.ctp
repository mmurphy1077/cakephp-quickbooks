<?php
/**
 * The commented out script inclusions are now located in the main template
 */
//$this->Html->css('jquery/ui/blue1/jquery-ui-1.8.11.custom', null, array('inline' => false));
//$this->Html->css('jquery/ui/b360/jquery-ui-1.8.11.custom', null, array('inline' => false));

if (!empty($css)) {
	// Load a custom CSS file to override the default jQuery UI styles
	$this->Html->css($css, null, array('inline' => false));	
}
switch ($ui) {
	case 'accordion':
		$this->Html->script('jquery/ui/jquery.ui.accordion', array('inline' => false));
		$this->Html->script('jquery/accordion', array('inline' => false));
		break;
	case 'autocomplete':
		$this->Html->script('jquery/ui/jquery.ui.position', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.autocomplete', array('inline' => false));
		break;
	case 'button':
	case 'buttons':
		$this->Html->script('jquery/ui/jquery.ui.button', array('inline' => false));
		$this->Html->script('creationsite/button', array('inline' => false));
		break;
	case 'chosen':
		$this->Html->script('jquery/jquery.chosen.min', array('inline' => false));
		$this->Html->css('jquery/jquery.chosen', null, array('inline' => false));
		break;
	case 'cluetip':
		$this->Html->script('jquery/jquery.cluetip', array('inline' => false));
		$this->Html->script('creationsite/cluetip', array('inline' => false));
		$this->Html->css('jquery/jquery.cluetip', null, array('inline' => false));
		break;
	case 'col_resize':
		$this->Html->script('jquery'.DS.'colResizable-1.3.min', false);
		//$this->Html->script('creationsite/col.resize.init', array('inline' => false));
		break;
	case 'combobox':
		//$this->Html->script('jquery/ui/jquery.ui.button', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.position', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.menu', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.autocomplete', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.tooltip', array('inline' => false));
		$this->Html->script('jquery/jquery.combobox', array('inline' => false));
		$this->Html->script('creationsite/combo', array('inline' => false));
	
		/*
		$this->Html->script('jquery/jquery.ajax-combobox.7.3.1', array('inline' => false));
		$this->Html->css('jquery/jquery.ajax-combobox', null, array('inline' => false));
		$this->Html->script('creationsite/combo', array('inline' => false));
		*/
		break;
	case 'datepicker':
		#$this->Html->script('jquery/ui/jquery.ui.core', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.datepicker', array('inline' => false));
		$this->Html->script('creationsite/datepicker.init', false);
		break;
	case 'dialog':
	case 'modal':
		$this->Html->script('jquery/jquery.bgiframe-3.0.1', array('inline' => false));
		#$this->Html->script('jquery/ui/jquery.ui.core', array('inline' => false));
		#$this->Html->script('jquery/ui/jquery.ui.widget', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.mouse', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.draggable', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.position', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.resizable', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.dialog', array('inline' => false));
		$this->Html->script('creationsite/modal', array('inline' => false));
		break;
	case 'sortable':
		#echo '<script>var id = "'.$id.'";</script>';
		$this->Html->script('jquery/ui/jquery.ui.mouse', array('inline' => false));
		$this->Html->script('jquery/ui/jquery.ui.sortable', array('inline' => false));
		$this->Html->script('creationsite/sortable', array('inline' => false));
		break;
}
?>