<?php
/**
 * @author Creationsite
 * @copyright 2008
 * @modified 2010
 * 
 * Contains logic and functionality for use in the public & admin pages
 */
class WebHelper extends AppHelper {
	
	var $helpers = array('Html', 'Time', 'Number', 'Form');
	
	function __getTagAttribs($tag) {
		$tag = explode('|', trim($tag, '[]'));
		array_shift($tag);
		$attribs = array();
		if (!empty($tag)) {
			foreach ($tag as $combined) {
				$attrib = explode(':', $combined);
				$attribs[$attrib[0]] = $attrib[1];
			}
		}
		return $attribs;
	}
	
	function __getTagType($tag) {
		$tag = explode('|', $tag);
		return substr($tag[0], strpos($tag[0], ':') + 1);
	}
	
	/**
	 * Retrieves a previously cached value (set in a particular model)
	 * Made a helper method to centralize the Cache::config parameters
	 * Cache concept taken from: http://teknoid.wordpress.com/2008/08/20/dynamic-menus-without-requestaction-in-cakephp-12/
	 * 
	 * @param key string the name of the var that was set in the cache
	 * @retun mixed the value of the var from the cache
	 */
	function getCache($key = null) {
		Cache::config(null, array('engine' => 'File', 'path' => CACHE));
		return Cache::read($key);
	}

	/**
	 * Accepts a Cake formatted array dataset of an address for formatting
	 * Accounts for the existence of 'line2' (i.e. apt/suite) formatting
	 * Does NOT include a trailing separator!
	 * 
	 * @param data array the array of the address data as key => value pairs
	 * @param separator string the text or HTML to go between each item (default = '<br />')
	 * @param name bool true to include the person's name if the keys exist, false to ignore (default = true)
	 * @param phone bool true to include the phone number if the key exists, false to ignore (default = false)
	 * @param country bool true to include the name of the country if the key exists, false to ignore (default = false)
	 * @return string the formatted address ready for output in a view
	 */
	function address($data, $mapLink = true, $separator = '<br />', $name = true, $phone = false, $country = false) {
		$address = '';
		if ($name) {
			if (array_key_exists('name', $data) && !empty($data['name'])) {
				$address .= $data['name'].' ';
			}
			if (array_key_exists('name_first', $data) && !empty($data['name_first'])) {
				$address .= $data['name_first'].' ';
			}
			if (array_key_exists('name_last', $data) && !empty($data['name_last'])) {
				$address .= $data['name_last'];
			}
			// Only works because the name fields are checked first
			if (!empty($address)) {
				$address .= $separator;
			}
		}
		if(!empty($data['line1']) || !empty($data['line2'])) {
			$address .= $data['line1'].$separator;
			if (!empty($data['line2'])) {
				$address .= $data['line2'].$separator;
			}
		}
		if (!empty($data['city']) || !empty($data['st_prov']) || !empty($data['zip_post'])) {
			if ($separator == '<br />' || $separator == '<br/>' || $separator == '<br>') {
				if(!empty($data['city'])) {
					$address .= $data['city'].', ';
				}
				$address .= $data['st_prov'].' '.$data['zip_post'];
			} else {
				$address .= $data['city'].$separator;
				$address .= $data['st_prov'].$separator;
				$address .= $data['zip_post'];
			}
		}
		if ($country && !empty($data['country'])) {
			$address .= $separator.$data['country'].$separator;;
		}
		if ($phone && !empty($data['phone'])) {
			$address .= $data['phone'].$separator;
		}
		if ($mapLink) {
			// Include Google Maps icon with link to map
			$mapdata = array();
			$mapdata['line1'] = $data['line1'];
			$mapdata['line2'] = $data['line2'];
			$mapdata['city'] = $data['city'];
			$mapdata['st_prov'] = $data['st_prov'];
			$mapdata['zip_post'] = $data['zip_post'];
			$link = 'https://maps.google.com/?q='.urlencode($this->address($mapdata, false, ','));
			$map = $this->Html->link($this->Html->image('icon-google-maps-48x48.png'), $link, array('target' => '_blank', 'escape' => false, 'class' => 'address-map-icon-link'));
			$address  = $map.$address;
		}
		return $address;
	}		
	
	function renderApplicationTagVideo($tag) {
		$html = '';
		if (!empty($tag['file'])) {
			$videoPath = $this->webroot.Configure::read('Path.videos').'/';
			if (empty($tag['w'])) {
				$tag['w'] = 480;
			}
			if (empty($tag['h'])) {
				$tag['h'] = 320;
			}
			if (empty($tag['preview'])) {
				$preview = null;
			} else {
				$preview = '&image='.$videoPath.$tag['preview'];
			}
			$embed = '
				<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" width="%2$s" height="%3$s">
					<param name="movie" value="'.$this->webroot.'js/mediaplayer/player.swf" />
					<param name="allowfullscreen" value="true" />
					<param name="allowscriptaccess" value="always" />
					<param name="flashvars" value="file='.$videoPath.'%1$s'.$preview.'" />
					<embed
						type="application/x-shockwave-flash"
						id="jwPlayer"
						name="jwPlayer"
						src="'.$this->webroot.'js/mediaplayer/player.swf" 
						width="%2$s" 
						height="%3$s"
						allowscriptaccess="always" 
						allowfullscreen="true"
						flashvars="file='.$videoPath.'%1$s'.$preview.'" 
					/>
				</object>			
			';
			$html = sprintf($embed, $tag['file'], $tag['w'], $tag['h']);
		}
		return $html;
	}
	
	function parseContent($content, $options = array()) {
		// Look for custom application tags
		if (preg_match_all(Configure::read('Application.tag'), $content, $matches)) {
			$tags = array();
			if (!empty($matches[0])) {
				// Only looking at index 0 because we're only matching one regex group
				foreach ($matches[0] as $match) {
					$tags[$this->__getTagType($match)][] = Set::merge($this->__getTagAttribs($match), array('tagString' => $match));
				}
			}
			if (!empty($tags)) {
				// Suppors multiple tag types; each type with multiple tags
				foreach ($tags as $tagType => $tags) {
					if (method_exists($this, 'renderApplicationTag'.ucFirst($tagType))) {
						foreach ($tags as $tag) {
							// Make sure the tag is supported & replace the tag string in $content accordingly
							$content = str_replace($tag['tagString'], $this->{'renderApplicationTag'.ucFirst($tagType)}($tag), $content);
						}
					}
				}
			}
		}
		if (!empty($options['parseLineBreaks'])) {
			$content = nl2br($content);
		}
		return $content;
	}
	
	/**
	 * Display pricing for a product based on individual line item prices
	 * Displays a range of 2 prices if line item pricing varies
	 * Assumes line items are sorted by price, low to high
	 * 
	 * @param $data array the ProductLineItem[0]['field'] data set
	 * @return string the product pricing formatted in USD, or null if data empty
	 */
	function lineItemPricing($data = array(), $price = 'price') {
		if (count($data) == 1) {
			return $this->Number->currency($data[0][$price]);
		} elseif (count($data) > 1) {
			$priceLo = $data[0][$price];
			$priceHi = $data[count($data)-1][$price];
			if ($priceLo < $priceHi) {
				return $this->Number->currency($priceLo).' - '.$this->Number->currency($priceHi);
			} else {
				return $this->Number->currency($priceLo);
			}
		} else {
			return null;
		}
	}
	
	/**
	 * Formats a product line items array dataset as array(id => name)
	 * This format is suitable for select boxes and radio tags
	 * 
	 * @param data array the Cake formatted dataset of product line items and their option values
	 * @return array formatted for a select box or radio tags in a form
	 */
	function formatLineItems($data, $price = 'price') {
		$list = array();	
		foreach ($data as $lineItem) {
			$list[$lineItem['id']] = $this->Number->currency($lineItem[$price]);
			if (!empty($lineItem['item_num'])) {
				#$list[$lineItem['id']] .= ' <span class="smalltext">('.$lineItem['item_num'].')</span>';	
				#$list[$lineItem['id']] .= ' ('.$lineItem['item_num'].')';
			}
			if (!empty($lineItem['ProductOptionValue'])) {
				foreach ($lineItem['ProductOptionValue'] as $option) {
					$list[$lineItem['id']] .= ', '.$option['name'];	
				}
			}
		}
		return $list;
	}

	/**
	 * Returns a well-formed tag for selecting product line items
	 * References $this->formatLineItems()
	 * 
	 * @param data array the Cake formatted dataset of product line items and their option values
	 * @param type string the type of tag to return: select or radio (a hidden form field will be returned if only one line item exists)
	 * @param model_field string the ModelName.fieldName of the line item value (default = CartLineItem.id)
	 * @return string a select box, radio button group or plain text ready for HTML output
	 */
	function selectLineItem($data, $type = 'radio', $price = 'price', $model_field = 'product_line_item_id') {
		$list = $this->formatLineItems($data, $price);
		return $this->Form->input($model_field, array('type' => $type, 'options' => $list, 'label' => false, 'separator' => '<br />'));
	}
	
	function sanitize($content, $strict = true) {
		$content = str_replace("\r\n", " ", $content);
		$content = str_replace(',', ' ', $content);
		if ($strict) {
			$content = str_replace('"', '', $content);
			$content = str_replace('\'', '', $content);
		}
		return $content;
	}
	
	function bullets($data, $options = array()) {
		$type = 'ul';
		extract($options);
		$output = NL.'<'.$type.'>'.NL;
		if (!empty($data)) {
			$lineItems = explode("\r\n", $data);
			if (is_array($lineItems)) {
				foreach ($lineItems as $lineItem) {
					$output .= '<li>'.$lineItem.'</li>'.NL;
				}
			}
		}
		return $output.'</'.$type.'>'.NL;
	}
	
	/**
	 * Return a portion of $data based on $maxChars
	 * If the string length of $data <= $maxChars, returns the original string
	 * Otherwise, returns up to $maxChars and appends an ellipses (...) if applicable
	 * 
	 * @param $data string the full text to obtain an excerpt from
	 * @param $options array
	 * 		ellipses:
	 * 			mixed string to append to the end of the excerpt if it's > $maxChars
	 * 			bool false to not append anything (default = '...')
	 * 		stripTags:
	 * 			bool if true (default), will call PHP's strip_tags() first
	 * 		wordBreak:
	 * 			bool if true (default), will NOT break a word in two
	 * 			functions by finding previous space char and truncating after it
	 * @return string the excerpt
	 */
	function excerpt($data, $maxChars = 50, $options = array()) {
		$ellipses = '...';
		$stripTags = true;
		$wordBreak = true;
		extract($options);
		$data = str_replace("\r\n", ' ', $data);
		if ($stripTags) {
			$data = strip_tags($data);
		}		
		if (strlen($data) <= $maxChars) {
			return $data;
		} else {			
			$excerpt = substr(trim($data), 0, $maxChars);
			if ($wordBreak) {
				$excerpt = rtrim(strrev(strstr(strrev($excerpt), ' ')));
			}
			if ($ellipses) {
				$excerpt .= '...';
			}
			return $excerpt;
		}
	}
	
	/**
	 * Return a two part array with the first being a portion of $data based on $maxChars
	 * and the second part being the remaining portion. If the string length of 
	 * $data <= $maxChars, returns the original string as part1 and the returns an 
	 * empty string as part2, otherwise, returns up to $maxChars and appends an 
	 * ellipses (...) if applicable
	 * 
	 * @param $data string the full text to obtain an excerpt from
	 * @param $options array
	 * 		ellipses:
	 * 			mixed string to append ellipses ('...') to the end of the parts
	 * 			bool false (default) to not append anything, true to append ellipses
	 * 		stripTags:
	 * 			bool if true (default), will call PHP's strip_tags() first
	 * @return array of two parts
	 */
	function excerpt_file_name($data, $maxChars = 50, $options = array()) {
		$stripTags = true;
		$wordBreak = true;
		$ellipses = '...';
		extract($options);
		if ($ellipses) $ellipses = '...';
		if ($stripTags) {
			$data = strip_tags($data);
		}		
		if (strlen($data) <= $maxChars) {
			return $data;
		} else {			
			$split = $maxChars/2;
      		$part1 = substr($data, 0, $split);
      		$part2 = str_replace ($part1, '', $data);
      		$part2 = substr($part2, ($split*-1));
      		if ($ellipses) { 
				$part1 = rtrim($part1, '.') . $ellipses;
			}
			return $part1.$part2;
		}
	}
	
	function formSafe($data, $maxChars = 255) {
		return substr(htmlentities(strip_tags($data)), 0, $maxChars);
	}
	
    /**
     * Returns a well-formed image tag for serving up images from any directory
     * 
     * @param image string the name of the image file
     * @param attribs array a name => value formatted array to create custom image tag attributes (i.e. alt, class, id, etc.)
     * @param path string the path (relative to $this->webroot) to the image file (assumes file upload path but can be overridden)
     * @return string an image tag ready for HTML output
     */
    function image($image, $attribs = array(), $path = null) {
    	// Build attribute string
    	$attributes = '';
    	if (!empty($attribs)) {
			foreach ($attribs as $attrib => $value) {
				$attributes .= ' '.$attrib.'="'.$value.'"';
			}
		}		
		// Define default image path
    	if (empty($path)) {
    		$path = Configure::read('Path.files');
    	}
    	// Use webroot relative path or unmolested value of $image
    	if (strpos($image, '/') === false) {
    		$path = $this->webroot.$path.'/'.$image;
    	} else {
    		$path = $image;
    	}
    	// Generate and return image tag
    	$img = '<img src="'.$path.'"'.$this->_parseAttributes($attribs, null, ' ', '').' />';
    	return $img;
    }
    
    /**
     * Returns MySQL dates and times in a variety of formats
	 * Uses predefined formats or accepts string formatted for PHP's date() function
	 *   
     * Predefined Format Examples:
	 * short_2 = 1/1/08
	 * short_4 = 1/1/2008
	 * text_full = January 1, 2008
	 * text_short = Jan 1, 2008
	 * month_year = 1/2008
	 * 12hr = 4:30pm
	 * 12hr_full = 04:30:47 pm
	 * 24hr = 16:30
	 * 24hr_full = 09:30:47
	 * 
	 * @param dt string MySQL datetime column type value
	 * @param format string one of the predefined date/time formats, or a custom string to pass into date()
	 * @param time string append the time to the date (only if not null)
	 * @return string the formatted datetime string 
     */
    function dt($dt, $date_format = null, $time_format = null, $separator = ' ') {    	
    	$date_formats = array(
    		'short_2' => 'n/j/y',
    		'short_4' => 'n/j/Y',
    		'text_full' => 'F j, Y',
    		'text_short' => 'M j, Y',
    		'month_year' => 'n/Y',
    		'thorough1' => 'l, F jS, Y, g:ia',
		);				
    	$time_formats = array(
    		'12hr' => 'g:i a',
    		'12hr_zero' => 'h:i a',
    		'12hr_full' => 'h:i:s',
    		'24hr' => 'G:i',
    		'24hr_full' => 'H:i:s',
		);
		if (array_key_exists($date_format, $date_formats)) {
			$date_format = $date_formats[$date_format];
		}		
		if (array_key_exists($time_format, $time_formats)) {
			$time_format = $time_formats[$time_format];
		}		
		$format = $date_format.$separator.$time_format;
		if (!empty($dt)) {
			$dt = strtotime($dt);
		} else {
			return null;
		}
		// Modify the timezone
		$dt += Configure::read('environment.timezoneOffset');
    	return date($format, $dt);
    }
    
    function sitemapLink($sitemap) {
    	if (!empty($sitemap['Sitemap']['model'])) {
			$link = array('controller' => AppInflector::tableize($sitemap['Sitemap']['model']), 'action' => 'index');
		} else {
			$link = '/'.$sitemap['Sitemap']['slug'];
		}
		return $link;
    }
    
	function humanFilesize($bytes, $decimals = 2) {
		#$sz = 'BKMGTP';
		$sz = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
		$factor = floor((strlen($bytes) - 1) / 3);
		return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)).' '.@$sz[$factor];
	}
	
	public function convertTo($measurementTypeId, $uom) {
		$uomFormatted = null;
		switch ($measurementTypeId) {
			case CALCULATION_REQUIREMENT_TYPE_IMPERIAL_LINEAR_ID:
				$uomFormatted .= intval($uom / 12).'\'';
				$uomFormatted .= ($uom % 12).'"';
				break;
			case CALCULATION_REQUIREMENT_TYPE_HOURLY_ID:
				$uomFormatted .= intval($uom / 60).' '.__('hours');
				if ($uom % 60 > 0) {
					$uomFormatted .= ' '.($uom % 60).' '.__('minutes');
				}
				break;
		}
		return $uomFormatted;
	}

	function phone($data) {
		if(!empty($data['phone_1_number'])) {
			return $data['phone_1_number'];
		}
		if(!empty($data['phone_2_number'])) {
			return $data['phone_2_number'];
		}
		if(!empty($data['phone_3_number'])) {
			return $data['phone_3_number'];
		}
	}
	
	function humanName($name, $format = null) {
		switch ($format) {
			case 'first':
				return $name['name_first'];
				break;
			case 'full':
				return $name['name_first'].' '.$name['name_last'];
				break;
			case 'first_initial':
				return substr($name['name_first'], 0, 1).'. '.$name['name_last'];
				break;
			case 'reverse':
				return $name['name_last'].', '.$name['name_first'];
				break;
			default:
				return $name['name_first'].' '.substr($name['name_last'], 0, 1).'.';
				break;
		}
	}
	
	function reportError($field, $data, $display_field_name) {
		if(!empty($data)) {
			if(array_key_exists($field, $data)) {
				echo '<div class="error_msg">&#8592; Missing '.$display_field_name.'</div>';
			}
		}
	}
	
	// Helper function that formats the file sizes
	function formatFileSize($bytes) {
		if (!ctype_digit($bytes)) {
			return '';
		}
	
		if ($bytes >= 1000000000) {
			return (number_format($bytes / 1000000000, 2) . ' GB');
		}
	
		if ($bytes >= 1000000) {
			return (number_format($bytes / 1000000, 2) . ' MB');
		}
	
		return (number_format($bytes / 1000, 2) . ' KB');
	}
	
	function displayPieImage($order) {
		$val = intval($order['ratio']/10);
		$dir = '';
		if($order['total_open_time'] > 0) {
			$dir = 'green/';
		}
		if($val >= 10) {
			$image_name = '100';
			$dir = 'red/';
		} else {
			$image_name = $val*10;
			if($image_name > 90) {
				$dir = 'orange/';
			}
		}
		return '<div class="pie-container-' . $image_name . ' pie-container">' . $this->Html->image('pie/' .  $dir . $image_name.'.png') . '</div>';
	}
	
	function displayQuoteToOrderRatio($type, $quote=0, $order=0, $order_without_quotes=0) {
		$ratio = 0;
		$quote = $quote + $order_without_quotes;
		if (!empty($quote)) {
			$ratio = $order/$quote;
		}
		
		if($type == 'score') {
			$score = $ratio * 10;
			$display = number_format($score, 1);
		} else {
			$perc = $ratio * 100;
			$remander = $perc % 100;
			if($remander > 0) {
				$ratio = number_format($ratio, 2);
			} else {
				$ratio = number_format($ratio, 0);
			}
			$display = '1 : ' . $ratio . ' (' . number_format($perc, 0) . '%)';
		}
		return $display;
	}
	
	function displayPhoneList($data) {
		$phone = '';
		if(!empty($data['phone_1_label'])) {
			$phone = $data['phone_1_label'] . ': ' . $data['phone_1_number'];
		}
		if(!empty($data['phone_2_label'])) {
			if(!empty($phone)) {
				$phone = $data['phone_2_label'] . ': ' . $data['phone_2_number'];
			} else {
				$phone = $phone . ' / '. $data['phone_2_label'] . ': ' . $data['phone_2_number'];
			}
		}
		if(!empty($data['phone_3_label'])) {
			if(empty($phone)) {
				$phone = $data['phone_3_label'] . ': ' . $data['phone_3_number'];
			} else {
				$phone = $phone . ' / '. $data['phone_3_label'] . ': ' . $data['phone_3_number'];
			}
		}
	}
}
?>