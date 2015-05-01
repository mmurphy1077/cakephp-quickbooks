<?php
class UploadableBehavior extends ModelBehavior {
	
	var $name = 'Uploadable';
	var $validate = array(
		'mime_type' => array(
			'file_type' => array(
				'rule' => array('checkMimeType', array('allowed_mime_types' => array())),
			),
		),
		'bytes' => array(
			'max_size' => array(
					'rule' => array('checkFilesize', array('max_size' => 20000000)),
			),
		),
	);
	
	
	var $__mimeTypes = array(
		'image' => array(
			'jpg' => array('image/jpeg', 'image/pjpeg'),
			'gif' => array('image/gif'),
			'png' => array('image/png'),
		),
		'file' => array(
			'jpg' => array('image/jpeg', 'image/pjpeg'),
			'gif' => array('image/gif'),
			'png' => array('image/png'),
			'doc' => array('application/msword'),
			'docx' => array('application/vnd.openxmlformats-officedocument.wordprocessingml.document'),
			'xls' => array('application/excel', 'application/x-msexcel', 'application/x-excel', 'application/vnd.ms-excel'),
			'zip' => array('application/zip', 'application/x-compressed', 'application/x-zip-compressed', 'multipart/x-zip'),
			'pdf' => array('application/pdf', 'application/x-pdf', 'application/binary', 'application/octet'),
			'rtf' => array('application/rtf', 'text/richtext'),
			'txt' => array('text/plain'),
			'ods' => array('application/vnd.oasis.opendocument.spreadsheet'),
			'odt' => array('application/vnd.oasis.opendocument.text'),
		),
	);
	/**
	 * type:
	 * 		Whether this behavior is expecting an image or non-image binary file
	 * allowEmpty:
	 * 		Determines whether or not a file is required on postback (default = true)
	 * 		This value can be overridden in the implementation model's validate property, too
	 * maxSize:
	 * 		Set the maximum allowable file size for the upload
	 * 		This value can be overridden in the implementation model's validate property, too
	 * validationKey:
	 * 		The name of the form field that represents the uploaded file
	 * uploadPath:
	 * 		If this value exists on postback, the file will be moved to the value set in it
	 * 		If no value is set, the implementation model's alias will be used as the path
	 */
	var $__defaultSettings = array(
		'type' => 'image',
		'allowEmpty' => true,
		'maxSize' => 20000000,
		'validationKey' => 'name',
		'uploadPath' => '__uploadPath',
		'renameFile' => true,
	);
	
	/**
	 * Sets up configuration for the model
	 * @see cake/libs/model/ModelBehavior#setup($model, $config)
	 */
	function setup(Model $model, $settings = array()) {
		if (!isset($this->settings[$model->alias])) {
			$this->settings[$model->alias] = $this->__defaultSettings;
		}
		$this->settings[$model->alias] = Set::merge($this->settings[$model->alias], (array)$settings);
		if (!array_key_exists('mimeTypes', $this->settings[$model->alias])) {
			// Use default mime types defined within this behavior
			$this->settings[$model->alias]['mimeTypes'] = $this->__mimeTypes[$settings['type']];
		}
		$settings = $this->settings[$model->alias];
		// Configure default validation property values based on model settings and uploadable type
		$this->validate[$settings['validationKey']]['uploaded']['rule'][1]['allowEmpty'] = $settings['allowEmpty'];
		$this->validate[$settings['validationKey']]['max_size']['rule'][1]['max_size'] = $settings['maxSize'];
		$this->validate[$settings['validationKey']]['file_type']['rule'][1]['allowed_mime_types'] = array('*');
		// Allow model to override behavior defaults
		$model->validate = Set::merge($this->validate, $model->validate);
	}
	
	function beforeSave(Model $model) {
		$settings = $this->settings[$model->alias];
		$aws = Configure::read('AWS.'.Configure::read('Environment.platform'));
		if (!array_key_exists($settings['uploadPath'], $model->data[$model->alias]) || empty($model->data[$model->alias][$settings['uploadPath']])) {
			// Move the uploaded file to a directory named by the implementation model's alias
			$uploadPath = Configure::read('Path.files').DS.$model->alias;
		} else {
			// Allow a postback custom path for the uploaded file
			$uploadPath = $model->data[$model->alias][$settings['uploadPath']];
		}
		if (!empty($model->data[$model->alias]['name'])) {
			// Move the uploaded file if it was posted back properly
			$successful_upload = false;
			if(empty($aws)) {
				/*
				 * 		SERVER STORAGE
				 * 		Move the uploaded file if it was posted back properly
				 */
				$cloud_status_id = 0;
				if ($file = $this->__move($model->data[$model->alias]['name'], $uploadPath.DS, $settings['renameFile'])) {
					$successful_upload = true;
				}
			} else {
				/*
				 * 		CLOUD STORAGE
			 	 */
				$cloud_status_id = 1;
				if ($file = $this->__move_cloud($model->data[$model->alias]['name'], $model->alias.DS, $settings['renameFile'])) {
					$successful_upload = true;
				}
			}
			if ($successful_upload) {	
				// Set binary field values
				$model->data[$model->alias]['cloud_status_id'] = $cloud_status_id;
				$model->data[$model->alias]['name'] = $file['name'].'.'.$file['extension'];
				return true;
			} else {
				// File could not be moved, abandon save operation
				return false;
			}
		} else {
			// No file was uploaded, remove from postback data
			unset($model->data[$model->alias][$settings['validationKey']]);
		}
		return false;
	}
	
	function beforeDelete(Model $model, $cascade = true) {
		$settings = $this->settings[$model->alias];
		$fileName = $model->field($settings['validationKey'], array('id' => $model->id));
		if (!array_key_exists('deletePath', $settings) || empty($settings['uploadPath'])) {
			// Look for the uploaded file in a directory named by the implementation model's alias
			$deletePath = $model->alias;
		} else {
			// Allow the implementation model to specifiy the path where the file should be
			$deletePath = $settings['deletePath'];
		}
		if (!empty($fileName)) {
			if (file_exists(Configure::read('Path.files').DS.$deletePath.DS.$fileName)) {
				unlink(Configure::read('Path.files').DS.$deletePath.DS.$fileName);
			}
		}
		return true;
	}
	
	/**
	 * Moves a file from one location on the filesystem to another
	 * Optionally renames the file (using value of time() if bool or string value of $rename)
	 * Automatically creates directory if destination folder does not exist
	 * 
	 * @param $source array the posted back data of the uploaded file
	 * @param $destination string the directory path where the uploaded file will reside
	 * @return mixed array with moved file's details, false if directory creation attempt fails
	 */
	function __move($source, $destination, $rename = true) {
		$fileinfo = pathinfo($source['name']);
		$filename = $fileinfo['filename'];
		$file_ext = strtolower($fileinfo['extension']);
		$file = array();
		if (!empty($rename)) {
			if ($rename === true) {
				$filename = strtoupper(md5($source['name'].uniqid()));
			} else {
				$filename = $rename;
			}
		}
		if (!file_exists($destination)) {
			if (!@mkdir($destination)) {
				return false;
			}
		}
		if (move_uploaded_file($source['tmp_name'], $destination.$filename.'.'.$file_ext)) {
			$file['path'] = $destination;
			$file['name'] = $filename;
			$file['extension'] = $file_ext;
			$file['size'] = $source['size'];
		}
		return $file;
	}
	
	/**
	 * Moves a file from one location on the filesystem to the cloud
	 * Optionally renames the file (using value of time() if bool or string value of $rename)
	 * Automatically creates directory if destination folder does not exist
	 *
	 * @param $source array the posted back data of the uploaded file
	 * @param $destination string the directory path where the uploaded file will reside
	 * @return mixed array with moved file's details, false if directory creation attempt fails
	 */
	function __move_cloud($source, $destination, $rename = true) {
		//AWS access info
		$options = Configure::read('AWS.'.Configure::read('Environment.platform'));
	
		// Include the AWS_SDK class
		App::import('Vendor', 'AWS_SDK', array('file' => $options['sdk']));
		$s3 = new AmazonS3($options);
		$fileinfo = pathinfo($source['name']);
		$file_name = $fileinfo['filename'];
		$file_ext = strtolower($fileinfo['extension']);
		$fileTempName = $source['tmp_name'];
		if (!empty($rename)) {
			if ($rename === true) {
				$file_name = strtoupper(md5($source['name'].uniqid()));
			} else {
				$file_name = $rename;
			}
		}
		$destination_path = $destination.$file_name.'.'.$file_ext;
		// Determine if the files are within a subfolder in the bucket.
		$subfolder = Configure::read('AWS.'.Configure::read('Environment.platform').'.sub-folder');
		if(!empty($subfolder)) {
			$destination_path = $subfolder.DS.$destination_path;
		}
	
		$requiredOptions = array(
				'contentType' => $source['type'],
				'length' => $source['size'],
				'fileUpload' => $fileTempName,
				#'acl' => AmazonS3::ACL_PUBLIC,
		);
		// Allow public access to images.
		if (strpos($source['type'],'image') !== false) {
			$requiredOptions['acl'] = AmazonS3::ACL_PUBLIC;
		}
	
		$result = $s3->create_object($options['bucket'], $destination_path, $requiredOptions);
		$status = $options['status'];
		$file = array();
		if($result->status == $status['ok']) {
			$file['path'] = $destination;
			$file['name'] = $file_name;
			$file['extension'] = $file_ext;
			$file['size'] = $source['size'];
			$file['cloud_status_id'] = '1';
		}
		return $file;
	}
}
?>
