<?php
$this->Html->script('creationsite/email', array('inline' => false));
echo $this->element('js'.DS.'jquery', array('ui' => 'combobox'));
?>
<script>
$(document).ready(function(event){
	//$('#email-to').combobox();
	<?php 
	/*
	$options_string = '';
	if(!empty($email_to_customer)) {
		foreach($email_to_customer as $key=>$data) {
			$options_string = $options_string . '"'.$key.'" : { ';
			if(!empty($data)) {
				$i = 0;
				foreach($data as $email=>$display) { 
	 				if($i > 0) {
						$options_string = $options_string . ',';
	 				}
	 				$options_string = $options_string . '"'.$email.'":"'.$display.'"';
	 				$i = $i + 1;
				}
			}
			$options_string = $options_string . '},';
		}
	} 
	*/
	$options_string = '';
	if(!empty($email_to_customer)) {
		$i = 0;
		$options_string = $options_string . '{ ';
		foreach($email_to_customer as $email=>$display) {
			if($i > 0) {
				$options_string = $options_string . ',';
			}
			$options_string = $options_string . '"'.$email.'":"'.$display.'"';
			$i = $i + 1;
		}
		$options_string = $options_string . '}';
	}
	?>
	
	function populateEmailTo(options) {
		if(options) {
			$.each(options, function(key, value) {
				$("#email-to").append($("<option></option>")
			     .attr("value", key).text(value));
			});
		}
	}

	//var options = {<?php #echo $options_string ?>};
	var options = <?php echo $options_string ?>;
	populateEmailTo(options);
	
	$('#email-customer').bind('change', function(){
		var value = $(this).val();
		newOptions = options[value];

		$("#email-to").empty(); // remove old options.
		populateEmailTo(newOptions);
		$("#email-to option:selected").prop("selected", false);
		$("#email-to").val([]);
		$("#email-to").combobox('autocomplete', $("#email-to").val());
	});
});
</script>
<?php echo $this->Form->create('Quote', array('class' => 'standard', 'novalidate' => true, 'url' => array('controller' => 'quotes', 'action' => 'email'))); ?>
	<fieldset>
		<div class="fieldset-wrapper">
			<h4><?php echo __('Email Customer Quote'); ?></h4>				
			<?php 
			echo $this->Form->hidden('customer', array('id' => 'email-customer', 'value' => $quote['Quote']['customer_id']));
			echo $this->Form->input('to', array('type' => 'select', 'id' => 'email-to', 'empty' => '', 'options' => $quote['Quote']['tos'], 'error' => array('length' => __('text_100'))));
			echo $this->Form->input('from', array('id' => 'email-from', 'class' => 'email-input', 'value' => $__user['User']['email'], 'error' => array('length' => __('text_100'))));
			echo $this->Form->input('subject', array('id' => 'email-subject', 'class' => 'email-input', 'value' => Configure::read('Public.name').' '.METHOD_SEPARATOR.' '.Configure::read('Nomenclature.Quote').' '.$quote['Quote']['number'], 'error' => array('length' => __('text_255'))));
			?>
			<div class="input text">
				<label>&nbsp;</label>
				<div class="block"><?php echo $this->Form->input('cc', array('label' => __('Send a copy to my inbox.'), 'checked' => false)); ?></div>
			</div>
			<div id="email_attachment_container" class="input text">
				<label><?php echo __('Attachments'); ?></label>
				<div class="block">
					<ul class="attactment_list">
						<li>
							<div class="input checkbox">
							<?php 
							$img_class = 'icon-file-pdf';
							$label= $this->Html->link(Configure::read('Nomenclature.Quote').' '.$quote['Quote']['number'], array('controller' => 'quotes', 'action' => 'print_pdf', $quote['Quote']['id']), array('div' => false, 'class' => $img_class)); 
							echo $this->Form->input('attach_quote', array('label' => $label, 'checked' => true)); 
							#echo $this->Form->input('attach_cover_sheet', array('label' => false, 'div' => false, 'checked' => false)) .  __('Include').' '.__('Cover Sheet'); ?>
							</div>
						</li>
			<?php if(!empty($quote['QuoteDocument'])) : ?>
			<?php 	foreach($quote['QuoteDocument'] as $doc) : ?>
						<li>
							<?php 
							$img_class = 'icon-file-default';
							$pos =  substr(strrchr ($doc['name'], '.'), 1);
							if(array_key_exists($pos, $doc_types)) {
								$img_class = 'icon-file-'.$pos;
							} 
							$label= $this->Html->link($this->Web->excerpt_file_name($doc['title'], 30), array('controller' => 'documents', 'action' => 'download', $doc['id'], 'QuoteDocument'), array('class' => $img_class));
							echo $this->Form->input('quote_doc', array('label' => $label, 'name' => 'QuoteDocument['.$doc['id'].']', 'checked' => false)); ?>
						</li>
			<?php 	endforeach;?>
			<?php endif; ?>
					</ul>
				</div>
			</div>
			<?php
			echo $this->Form->input('message', array('type' => 'textarea', 'div' => 'input textarea required large flush tall', 'label' => false));
			?>
			<br />
			<?php echo $this->Form->submit(__('Send'), array('class' => 'title-buttons right')); ?>
			<br /><br />
		</div>
	</fieldset>
<?php echo $this->Form->hidden('id', array('value' => $quote['Quote']['id'])); ?>
<?php echo $this->Form->end(); ?>
<div id="email-loader-container" class="loader-container"><?php echo $this->Html->image('loader-large.gif'); ?></div>