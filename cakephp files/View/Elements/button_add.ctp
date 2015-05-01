<?php
if (empty($item)) {
	$item = null;
}
echo $this->Html->link('&nbsp;', $link, array('class' => 'gear', 'title' => __('Click to add', true).$item, 'escape' => false));
?>