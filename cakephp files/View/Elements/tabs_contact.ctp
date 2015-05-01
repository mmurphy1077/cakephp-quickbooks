<?php
$steps = array(
	'page1' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'page2' => array(
		'class' => 'normal',
		'params' => array(),
	),
	'docs' => array(
		'class' => 'normal',
		'params' => null,
	),
);

$steps['page1']['params'] = array($data['Contact']['id']);
$steps['page2']['params'] = array($data['Contact']['id']);
$steps['docs']['params'] = array($data['Contact']['id']);

$steps['page1']['class'] = 'normal';
$steps['page2']['class'] = 'normal';
$steps['docs']['class'] = 'normal';

$location = $this->name.METHOD_SEPARATOR.$this->action;
switch ($location) {
	case 'Contacts::edit_lead':
		$steps['page1']['class'] = 'active';
		break;
	case 'Contacts::additional_info':
		$steps['page2']['class'] = 'active';
		break;
	case 'Contacts::docs':
		$steps['docs']['class'] = 'active';
		break;
}
?>
<div class="order tab">
	<div id="button-comment" class="slider-activate icon-print-active left">
	</div>
	<?php if(($permissions['enable_save'] == 1) || ($permissions['read_only'] == 1)) : ?>
	<div class="first block <?php echo $steps['page1']['class']; ?>">
		<?php
		$link = Set::merge(array('controller' => 'contacts', 'action' => 'edit_lead'), $steps['page1']['params']);
		if ($steps['page1']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Contact Info'), $link, array('class' => 'text'));
		?>
	</div>
	<?php endif; ?>
	<?php if(($permissions['enable_save'] == 1) || ($permissions['read_only'] == 1)) : ?>
	<div class="block <?php echo $steps['page2']['class']; ?>">
		<?php
		$link = Set::merge(array('controller' => 'contacts', 'action' => 'additional_info'), $steps['page2']['params']);
		if ($steps['page2']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Additional Info'), $link, array('class' => 'text'));
		?>
	</div>
	<?php endif; ?>
	<?php if($permissions['enable_file_upload'] == 1) : ?>
	<div class="block <?php echo $steps['docs']['class']; ?>">
		<?php
		$items = '';
		if(!empty($doc_count)) {
			$items = '<span class="doc-count-container">' . $doc_count . '</span>';
		}
		$link = Set::merge(array('controller' => 'contacts', 'action' => 'docs'), $steps['docs']['params']);
		if ($steps['docs']['class'] == 'disabled') {
			$link = '#';
		}
		echo $this->Html->link(__('Docs & Files'). $items , $link, array('class' => 'text', 'escape' => false));
		?>
	</div>
	<?php endif; ?>
</div>