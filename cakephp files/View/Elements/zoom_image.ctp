<?php
echo $this->element('js'.DS.'fancybox');
if (empty($size)) {
	$size = array('medium', 'large');
} elseif (!is_array($size)) {
	$size = array($size, $size);
}
if (empty($gallery)) {
	$gallery = 'page-gallery';
}
if (empty($options)) {
	$options = array();
}
?>
<a href="<?php echo $this->Image->get($model, $imageName, Configure::read('Images.'.$model.'.'.$size[1]), array(), 'string'); ?>" class="lightbox-image" rel="<?php echo $gallery; ?>">
<?php echo $this->Image->get($model, $imageName, Configure::read('Images.'.$model.'.'.$size[0]), $options); ?></a>