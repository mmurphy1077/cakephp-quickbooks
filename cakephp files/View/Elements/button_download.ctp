<?php
if (empty($target)) {
	$target = '_self';
}
echo $this->Html->link('&nbsp;', $link, array('class' => 'download', 'title' => __('Download', true), 'target' => $target, 'escape' => false));
?>