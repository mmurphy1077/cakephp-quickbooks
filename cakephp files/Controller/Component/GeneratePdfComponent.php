<?php
class GeneratePdfComponent extends Component {
	
	/**
	 * Contains a reference to the current controller
	 * 
	 * @var AppController object
	 */
	public $Controller;
	
	/**
	 * Contains a reference to the Document model
	 * 
	 * @var AppModel object
	 */
	public $Document;
	
	/**
	 * Set default configuration settings here
	 * Overridden during component initialization if passed in
	 * 
	 * @var array
	 */
	public $__defaultSettings = array(
	);
	
	/**
	 * Stores component configuration settings keyed according
	 * 		to the controller name property using it
	 * 
	 * @var array
	 */
	public $settings = array();
	
	/**
	 * The URL for wkhtmltopdf to convert into a PDF document
	 * 
	 * @var string
	 */
	public $url = null;
	
	/**
	 * The URL for wkhtmltopdf to convert into a PDF header
	 * 
	 * @var string
	 */
	var $header = null;
	
	/**
	 * The URL for wkhtmltopdf to convert into a PDF footer
	 * 
	 * @var string
	 */
	var $footer = null;
	
	/**
	 * The Document::model value this PDF document belongs to
	 * 
	 * @var string
	 */
	public $model = null;
	
	/**
	 * The Document::foreign_key value this PDF document belongs to
	 * 
	 * @var string
	 */
	public $foreignKey = null;
	
	/**
	 * Called before Controller::beforeFilter()
	 * 
	 * @param $Controller Controller object
	 * @param $settings array runtime configuration settings
	 * 		merged with Notification::__defaultSettings
	 * @return void
	 */
	public function initialize(Controller $Controller, $settings = array()) {
		#$this->Controller =& $Controller;
		#$this->settings[$Controller->name] = Set::merge($this->__defaultSettings, (array)$settings);
		#$this->settings[$Controller->name]['WkHtmlToPdf'] = Configure::read('WkHtmlToPdf.'.Configure::read('Environment.platform'));
		$this->settings = Set::merge($this->__defaultSettings, (array)$settings);
		$this->settings['WkHtmlToPdf'] = Configure::read('WkHtmlToPdf.'.Configure::read('Environment.platform'));
		#$this->Document = ClassRegistry::init('Document');
	}
	
	/**
	 * Advanced execution routine
	 * 
	 * @see http://code.google.com/p/wkhtmltopdf/wiki/IntegrationWithPhp
	 * 
	 * @param string $cmd The command to execute
	 * @param string $input Any input not in arguments
	 * @return array An array of execution data; stdout, stderr and return "error" code
	 */
	private function __pipe_execution($cmd, $input = null) {
		$proc = proc_open($cmd,
			array(
				0 => array('pipe', 'r'),
				1 => array('pipe', 'w'),
				2 => array('pipe', 'w')
			),
			$pipes,
			null,
			array('DYLD_LIBRARY_PATH' => '/usr/lib')
		);
		fwrite($pipes[0], $input);
		fclose($pipes[0]);
		$stdout = stream_get_contents($pipes[1]);
		fclose($pipes[1]);
		$stderr = stream_get_contents($pipes[2]);
		fclose($pipes[2]);
		$rtn = proc_close($proc);
		$results = array(
			'stdout' => $stdout,
			'stderr' => $stderr,
			'return' => $rtn,
		);
		return $results;
	}
	
	public function convert($alternate_destination = null, $filename=null) {
		$file = array(
			'name' => uniqid(),
			'extension' => 'pdf',
		);
		if(!empty($filename)) {
			$file['name'] = $filename;
		}
		$filename = join('.', $file);
		$destination = APP.WEBROOT_DIR.DS.Configure::read('Path.files').DS.$this->model.DS;
		if(!empty($alternate_destination)) {
			$destination = $alternate_destination;
		}
		
		/*
		if(isset($this->Controller)) {
			$controller = $this->Controller->name;
		} else {
			$controller = Inflector::pluralize($this->model);
			
		}
		if(empty($controller)) {
			$controller = 'Quotes';
		}
		*/
		$wkhtml['executable'] = Configure::read('WkHtmlToPdf.'.Configure::read('Environment.platform').'.executable');
		// Buld Parameters
		if(empty($this->header)) {
			$top = Configure::read('WkHtmlToPdf.margin_top.no-header');
		} else {
			$top = Configure::read('WkHtmlToPdf.margin_top.header');
		}
		$right = Configure::read('WkHtmlToPdf.margin_right.normal');
		$left = Configure::read('WkHtmlToPdf.margin_left.normal');
		if(empty($this->footer)) {
			$bottom = Configure::read('WkHtmlToPdf.margin_bottom.no-footer'); 
		} else {
			$bottom = Configure::read('WkHtmlToPdf.margin_bottom.footer');
		}
		$page = '--page-size Letter';
		$params = $top . ' ' . $right . ' ' . $left . ' ' . $bottom . ' ' . $page;
		$cmd = array(
			#$this->settings[$this->Controller]['WkHtmlToPdf']['executable'],
			#$this->settings[$this->Controller]['WkHtmlToPdf']['params'],
			$wkhtml['executable'],
			$params,
		);
		
		$header = array();
		if(!empty($this->header)) {
			$header = array('--header-html ' . $this->header);
		}
		
		$footer = array();
		if(!empty($this->footer)) {
			$footer = array('--footer-html ' . $this->footer);
		}
		
		$post = array(
			'http://'.$this->url,
			$destination.join('.', $file),
			#'> /dev/null 2>&1 & echo $!',
		);
		$cmd = array_merge($cmd, $header, $footer, $post);
		
		if (!file_exists($destination)) {
			if (!@mkdir($destination)) {
				return false;
			}
		}
		$response = $this->__pipe_execution(join(' ', $cmd));
		if (empty($response['return']) || $response['return'] > -1) {
			// Return value of 0 (zero) means there were no errors
			if (!file_exists($destination.$filename)) {
				// Make sure the file actually generated to the file system
				return false;
			} else {
				// Return an array of the various parameters used during the conversion
				// along with additional details on the newly generated file
				$this->Document = ClassRegistry::init('Document');
				$file['mime_type'] = $this->Document->getMimeType($destination.$filename);
				$file['size'] = filesize($destination.$filename);
				return array(
					'cmd' => $cmd,
					'destination' => $destination,
					'file' => $file,
					'response' => $response,
				);
			}
		}
	}
}
?>