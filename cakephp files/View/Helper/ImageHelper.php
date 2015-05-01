<?php
/**
 * @author Josh Hundley (hundleyj)
 * @link http://bakery.cakephp.org/articles/view/image-resize-helper
 * @author Kevin DeCapite (etipaced), minor modifications only
 * @author TimThumb
 * @link http://code.google.com/p/timthumb/
 * @since 2010-04-22
 */
App::uses('Helper', 'View');
class ImageHelper extends Helper {

    var $helpers = array('Html', 'Web');	
	var $cacheDir = 'cache';
	var $quality = array(
		'jpeg' => 90,
		'png' => 0,
		'gif' => null,
	);
	
	/**
	 * Automatically resizes an image and returns formatted img tag
	 *
	 * @param string $path path to the image file, relative to the Configure::read('Path.files') path
	 * @param array	$htmlAttributes Array of HTML attributes
	 * @return string the image tag referencing the properly sized image from the cache directory
	 */
	function get($model, $image, $imgAttributes = array(), $htmlAttributes = array(), $returnType = 'tag') {
		if (!is_array($image)) {
			$image = array('name' => $image);
		}
		extract($imgAttributes);
		$types = array(1 => "gif", "jpeg", "png", "swf", "psd", "wbmp");
		$fullpath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS.$model.DS;
		// Create cache directory if it doesn't exist
		if (!@file_exists($fullpath.$this->cacheDir)) {
			@mkdir($fullpath.$this->cacheDir);
		}
		$url = $fullpath.$image['name'];
		if (!@file_exists($url) || !($size = getimagesize($url))) {
			return null;
		}
		// $size[0]:width, [1]:height, [2]:type
		$width = $size[0];
		$height = $size[1];
		$src_x = $src_y = 0;
		// Determine crop values for the image
		if ($crop) {
			$src_w = $size[0];
			$src_h = $size[1];
			$cmp_x = $width / $w;
			$cmp_y = $height / $h;
			// Calculate x or y coordinates and width or height of source
			if ($cmp_x > $cmp_y) {
				$src_w = round(($width / $cmp_x * $cmp_y));
				$src_x = round(($width - ($width / $cmp_x * $cmp_y)) / 2);
			} elseif ($cmp_y > $cmp_x) {
				$src_h = round(($height / $cmp_y * $cmp_x));
				$src_y = round(($height - ($height / $cmp_y * $cmp_x)) / 2);
			}
			$width = $src_w;
			$height = $src_h;		
		} elseif ($aspect) {
			if (($size[1] / $h) > ($size[0] / $w)) {				
				$w = ceil(($size[0] / $size[1]) * $h);
			} else { 
				$h = ceil($w / ($size[0] / $size[1]));
			}
		}	
		$relfile = $this->webroot.Configure::read('Path.files').'/'.$model.'/'.$this->cacheDir.'/'.$w.'x'.$h.'_'.basename($image['name']);
		$cachefile = $fullpath.$this->cacheDir.DS.$w.'x'.$h.'_'.basename($image['name']);
		// Determine if image file is cached
		if (file_exists($cachefile)) {
			// Make sure the cached file is the size being requested
			$csize = getimagesize($cachefile);
			$cached = ($csize[0] == $w && $csize[1] == $h);
			// Make sure the cached file is newer than the original
			if (@filemtime($cachefile) < @filemtime($url)) {
				$cached = false;
			}
		} else {
			// $cached could get set to false in above if block, or could fall through here
			$cached = false;
		}
		// If no valid cache file exists for the image, copy and resize it
		if (!$cached) {
			$image['name'] = call_user_func('imagecreatefrom'.$types[$size[2]], $url);
			if (function_exists("imagecreatetruecolor") && ($temp = imagecreatetruecolor($w, $h))) {
				imagecopyresampled($temp, $image['name'], 0, 0, $src_x, $src_y, $w, $h, $width, $height);
	  		} else {
				$temp = imagecreate($w, $h);
				imagecopyresized($temp, $image['name'], 0, 0, $src_x, $src_y, $w, $h, $width, $height);
			}
			/**
			 * TODO Be careful because not all image<type> methods allow a quality parameter
			 */
			if (!empty($quality)) {
				$this->quality = $quality;
			}
			call_user_func("image".$types[$size[2]], $temp, $cachefile, $this->quality[$types[$size[2]]]);
			imagedestroy($image['name']);
			imagedestroy($temp);
		}
		if (!empty($image['caption'])) {
			$htmlAttributes = Set::merge($htmlAttributes, array(
				'alt' => __($image['caption'], true),
				'title' => __($image['caption'], true)
			));
		}
		if ($returnType == 'tag') {
			return $this->Web->image($relfile, $htmlAttributes);
		} elseif ($returnType = 'string') {
			return $relfile;
		}
		return null;
	}
	
}
?>