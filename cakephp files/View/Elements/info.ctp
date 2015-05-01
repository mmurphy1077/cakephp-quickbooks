<?php
if(!isset($class)) {
	$class = null;
}
?>
<div class="info-box <?php echo $class;?> ">
	<?php
	if (!empty($content)) {
		if (!is_array($content)) {
			$content = array($content);
		}
		foreach ($content as $p) {
			echo '<p>'.__($p).'</p>';
		}
	}
	?>
</div>