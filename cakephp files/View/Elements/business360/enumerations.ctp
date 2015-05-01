<?php 
echo $this->element('js'.DS.'jquery', array('ui' => 'sortable'));
echo $this->Html->script('creationsite/business360.settings', false);
?>
<?php 
$title = '';
switch ($model) {
	case 'QuoteType' :
		$title = Configure::read('Nomenclature.Quote').'/'.Configure::read('Nomenclature.Order') . 'Types';
		break;
 
} ?>
<h3 class="left"><?php echo __('Lookup Tables'); ?> (<?php echo $title; ?>)</h3>
<?php echo $this->Form->create('Enumeration', array('class' => 'standard', 'novalidate' => true, 'type' => 'file', 'url' => '/'.$this->params->url)); ?>
<?php echo $this->Form->hidden('EnumerationModel.model', array('value' => $model)); ?>
<div class="row clear">
	<div class="col-xs-12 col-sm-9 col-md-6 col-lg-6">
		<div id="invoice-labor-table" class="clear left">
			<br /><br />
			<table id="enumeration_select_container" class="standard tight nohover clear sortable">
				<tr class="nodrag">
					<th class="tight">&nbsp;</th>
					<th>Name</th>
					<th class="tight">&nbsp;</th>
				</tr>
				<?php 
				$i = 0;
				if(!empty($data)) :
					foreach($data as $data_element) : 
						echo $this->element('business360'.DS.'enumeration_table_row', array('index' => $i, 'data' =>$data_element['Enumeration']));
						$i = $i + 1;
					endforeach; 
				endif; 

				// At least add one blank row.
				if($i <= 4) {
					for($i; $i <= 4; $i++) {
						echo $this->element('business360'.DS.'enumeration_table_row', array('index' => $i, 'data' => null, 'type' => null));
					}
				} else {
					echo $this->element('business360'.DS.'enumeration_table_row', array('index' => $i, 'data' =>null, 'type' => null));
				} ?>
			</table>
			<?php echo $this->Html->link('add more', array('#'), array('id'=>'add_more_enumerations', 'class'=>'add_more clear left')); ?>
		</div>
		<div class="title-buttons">
			<?php echo $this->Form->submit(__('Save', true), array('class' => 'red')); ?>
		</div>
	</div>
</div>
<?php echo $this->Form->end(); ?>