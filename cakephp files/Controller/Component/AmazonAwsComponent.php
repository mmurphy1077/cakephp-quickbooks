<?php
class AmazonAwsComponent extends Component {
	
	/**
	 * @var CFRuntime object
	 * Stores Amazon S3 SDK object instance
	 */
	public $core;
	
	/**
	 * @var string the Amazon S3 bucket to use for the current request
	 */
	public $bucket;
	
	function initialize(Controller $Controller) { 
		App::import('Vendor', 'AWS_SDK', array('file' => Configure::read('AWS.'.Configure::read('Environment.platform').'.sdk')));
		
		$options = array(
			'key' => Configure::read('AWS.'.Configure::read('Environment.platform').'.key'),
			'secret' => Configure::read('AWS.'.Configure::read('Environment.platform').'.secret'),
		);
		$this->core = new AmazonS3($options);
		$this->bucket = Configure::read('AWS.'.Configure::read('Environment.platform').'.bucket');
	}
		
	function getHeaders($filename, $bucket=null) {
		if(empty($bucket)) {
			$bucket = $this->bucket;
		}
		return $this->core->get_object_headers($bucket, $filename);
	}
	
	function getContent($filename, $bucket=null) {
		if(empty($bucket)) {
			$bucket = $this->bucket;
		}
		$file = $this->core->get_object($bucket, $filename);
		$status = Configure::read('AWS.'.Configure::read('Environment.platform').'.status');
		if ($file->status == $status['ok']) {
			return $file->body;
		}
		return null;
	}
	
	function put($source, $destination, $options = array(), $bucket=null) {
		if(empty($bucket)) {
			$bucket = $this->bucket;
		}
		
		$requiredOptions = array(
			'fileUpload' => $source,
			'encryption' => Configure::read('AWS.'.Configure::read('Environment.platform').'.encryption'),
		);
		return $this->core->create_object($bucket, $destination, Set::merge($options, $requiredOptions));
	}
	
	function delete($filename) {
		return $this->core->delete_object($this->bucket, $filename);
	}
}
?>