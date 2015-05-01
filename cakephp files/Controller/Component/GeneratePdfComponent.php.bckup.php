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
		$this->Controller =& $Controller;
		$this->settings[$Controller->name] = Set::merge($this->__defaultSettings, (array)$settings);
		$this->settings[$Controller->name]['WkHtmlToPdf'] = Configure::read('WkHtmlToPdf.'.Configure::read('Environment.platform'));
		$this->Document = ClassRegistry::init('Document');
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
		debug('heer'); die;
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
		
		if(empty($this->footer)) {
			$cmd = array(
				$this->settings[$this->Controller->name]['WkHtmlToPdf']['executable'],
				$this->settings[$this->Controller->name]['WkHtmlToPdf']['params'],	
				'https://'.$this->url,
				$destination.join('.', $file),
				#'> /dev/null 2>&1 & echo $!',
			);
		} else {
			$cmd = array(
				$this->settings[$this->Controller->name]['WkHtmlToPdf']['executable'],
				$this->settings[$this->Controller->name]['WkHtmlToPdf']['params'],	
				'--footer-html ' . $this->footer,	
				'https://'.$this->url,
				$destination.join('.', $file),
				#'> /dev/null 2>&1 & echo $!',
			);
		}
		
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