<?php
class NotificationComponent extends Component {
	
	public $components = array('Email', 'Session');
	
	/**
	 * Contains a reference to the current controller
	 * 
	 * @var AppController object
	 */
	public $controller;
	
	/**
	 * Contains a reference to the EmailMessage model
	 * 
	 * @var AppModel object
	 */
	public $EmailMessage;
	
	/**
	 * Contains a reference to the NotificationTrigger model
	 * 
	 * @var AppModel object
	 */
	public $NotificationTrigger;
	
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
	 * Called before Controller::beforeFilter()
	 * 
	 * @param $controller AppController object reference
	 * @param $settings array runtime configuration settings
	 * 		merged with Notification::__defaultSettings
	 * @return void
	 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->controller =& $controller;
		$this->settings[$controller->name] = Set::merge($this->__defaultSettings, (array)$settings);
		$this->NotificationTrigger = ClassRegistry::init('NotificationTrigger');
		$this->EmailMessage = ClassRegistry::init('EmailMessage');
	}
	
	public function send($trigger, $options = array()) {
		// For Cloud Processing
		// AWS access info
		$aws = Configure::read('environment.AWS');
		$s3_status = Configure::read('environment.AWS.status');
		$aws_options = array(
			'key' => $aws['key'],
			'secret' => $aws['secret'],
			'bucket' => $aws['bucket'],
			'acl' => $aws['acl'],
		);
	
		// Include the AWS_SDK class
		$options_sdk = Configure::read('AWS.'.Configure::read('Environment.platform'));
		App::import('Vendor', 'AWS_SDK', array('file' => $options_sdk['sdk']));
		$s3 = new AmazonS3($aws_options);
		// Get data and configuration for this trigger
		if (!$notification = $this->NotificationTrigger->getByTemplate($trigger)) {
			return false;
		}
	
		/*
		 * ******************
		 */
		if(empty($options['userId']) && array_key_exists('Message', $options['data'])) {
			$recipients = $options['data']['Message']['recipient_email'];
		} else {
			// Get list of all target recipients (filtered by NotificationPreference)
			$recipients = $this->NotificationTrigger->getRecipients($notification, $options);
		}
		if (!empty($recipients)) {
			// Format basic EmailComponent settings
			if($notification['NotificationTrigger']['sender'] == 'user') {
				// Obtain the current Logged-in user's
				$from = $options['userEmail'];
			} else {
				$from = Configure::read('Email.'.$notification['NotificationTrigger']['sender']);
			}
			$this->Email->from = $from;
			$this->Email->subject = 'Business 360 [' . $this->Session->read('Application.settings.ApplicationSetting.company_name') . ']: ' . $notification['NotificationTrigger']['subject'];
			
			// Generate a single process ID per recipient list (for sending in small batches)
			$processId = uniqid();
							
			/*
			 * Attachments
			 */
			$attachment_path = null;
			$attachmentPath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS.'attachments';
			if(!empty($options['data']['Message']['attachments'])) {
				$filePath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS;
				$attachment_list = unserialize($options['data']['Message']['attachments']);
				$cloud_path = $processId;
				/*
				 * Build document array.
				 */
				$attachment_path = array();
				foreach($attachment_list as $document) {
								
					#$doc = array($document['title'].'.pdf'=>$filePath.$document['order_id'].DS.$document['name']);
					#array_push($attachment_path, $doc);
					if(empty($document['cloud_status_id'])) {
						array_push($attachment_path, $filePath.$document['order_id'].DS.$document['name']);
					} else {
						// Move the file from the cloud to a temporary directory.
						$attachmentFile = str_replace(" " , "-", $document['title']).'.'.pathinfo($document['name'], PATHINFO_EXTENSION);
						$attachmentFile = str_replace("/" , "_", $document['title']).'.'.pathinfo($document['name'], PATHINFO_EXTENSION);
							
						// Check if $tmpPath exists
						if (!file_exists($attachmentPath)) {
							if (!@mkdir($attachmentPath)) {
								$this->controller->ScreenMessage->notice(__('Attachment processing failed due to directory path unavailable.', true));
								$this->controller->redirect('/');
							}
						}
							
						if (!file_exists($attachmentPath.DS.$cloud_path)) {
							if (!@mkdir($attachmentPath.DS.$cloud_path)) {
								$this->controller->ScreenMessage->notice(__('Attachment processing failed due to directory path unavailable.', true));
								$this->controller->redirect('/');
							}
						}
						$options_s3 = array(
							'fileDownload' => $attachmentPath.DS.$cloud_path.DS.$attachmentFile,
						);
						$result = $s3->get_object($aws['bucket'], $document['order_id'].DS.$document['name'], $options_s3);
						if ($result->status == $s3_status['ok']) {
							array_push($attachment_path, $attachmentPath.DS.$cloud_path.DS.$attachmentFile);
						}
					}
				}
			}
						
			$this->Email->template = $notification['NotificationTrigger']['template'];
			$this->Email->sendAs = 'text';
			$this->Email->attachments = $attachment_path;
			$this->controller->set('environment', Configure::read('environment'));
			// Allows controller to pass template-specific content to the email message
			$this->controller->set('options', $options);
				
			if(is_array($recipients)) {
				foreach ($recipients as $recipient) {
					// Format user-specific EmailComponent settings
					$this->controller->set('user', $recipient);
					$this->__emailMessageSave($recipient['User']['email'], $processId);
				}
			} else {
				$this->__emailMessageSave($recipients, $processId);
			}
						
			// Call app_mailer.php
			if (Configure::read('environment.platform') == 'production') {
				// !!! NO MAIL WILL BE SENT IN development OR stage ENVIRONMENTS !!!
				$options = '-c '.ROOT.DS.APP_DIR.DS.'php.ini ';
				$suffix = ' > /dev/null 2>&1 & echo $!';
				$attach_path = $attachmentPath.DS;
				$script = 'php '.$options.ROOT.DS.APP_DIR.DS.'app_mailer.php '.' '.Configure::read('environment.platform').' '.$processId.' '.$attach_path.$suffix;
				$pid = exec($script);
			}
		}
	}
	
	/**
	 * Gets the body_text or body_html field from an EmailMessage record
	 * 		based on the passed $processId value
	 * Used for development when sending emails via Cake's EmailComponent
	 * 		instead of the default app_mailer.php method
	 * 
	 * @param $processId string the id of the EmailMessage record
	 * @return string the email message as plain-text or HTML string
	 */
	private function __getEmailMessage($processId) {
		switch ($this->Email->sendAs) {
			case 'text':
				return $this->EmailMessage->field('body_text', array('process_id' => $processId));
				break;
			case 'html':
			case 'both':
				return $this->EmailMessage->field('body_html', array('process_id' => $processId));
				break;
		}
		return null;
	}

	private function __emailMessageSave($to, $processId) {
		// Create view object and render content into layout & template
		App::import('View');
		$View = new View($this->controller);
		// Always generate plain text body
		$View->layoutPath = 'Emails'.DS.'text';
		$View->viewPath = 'Emails'.DS.'text';
		$body_text = $View->render($this->Email->template, 'default');
		// Optionally generate HTML body
		if ($this->Email->sendAs == 'both' || $this->Email->sendAs == 'html') {
			$View->layoutPath = 'Emails'.DS.'html';
			$View->viewPath = 'Emails'.DS.'html';
			$body_html = $View->render($this->Email->template, 'default');
		} else {
			$body_html = null;
		}
		// Only allowing a single recipient (use multiple records for multiple recipients)
		$to = array($to);
		// Assume Cake formatted sender format of "Sender Name <sender@email.com>"
		$from = explode('<', $this->Email->from);		
		if (count($from) > 1) {
			// Format array for SwiftMailer name/email
			$from = array(substr($from[1], 0, -1) => $from[0]);
		} else {
			// No name specified, still use array structure
			$from = array($from[0]);
		}
		$data = array(
			'EmailMessage' => array(
				'process_id' => $processId,
				'to' => serialize($to),
				'from' => serialize($from),
				'subject' => $this->Email->subject,
				'body_text' => $body_text,
				'body_html' => $body_html,
			)
		);
		$this->EmailMessage->create();
		if ($this->EmailMessage->save($data, false)) {
			return true;
		} else {
			return false;
		}
	}
}
?>
