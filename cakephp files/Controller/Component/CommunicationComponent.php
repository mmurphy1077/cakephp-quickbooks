<?php
class CommunicationComponent extends Component {
	
	public $components = array('Email', 'Paginator', 'GeneratePdf');
	
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
	 * 		merged with Communication::__defaultSettings
	 * @return void
	 */
	public function initialize(Controller $controller, $settings = array()) {
		$this->controller =& $controller;
		$this->settings[$controller->name] = Set::merge($this->__defaultSettings, (array)$settings);
		$this->NotificationTrigger = ClassRegistry::init('NotificationTrigger');
		$this->Message = ClassRegistry::init('Message');
		$this->ActionLog = ClassRegistry::init('ActionLog');
		$this->User = ClassRegistry::init('User');
		$this->Contact = ClassRegistry::init('Contact');
		$this->Document = ClassRegistry::init('Document');
		$this->Quote = ClassRegistry::init('Quote');
		$this->EmailMessage = ClassRegistry::init('EmailMessage');
	}
	
	function add_message($order_id = null) {
		$recipients = $this->Message->getRecipientList($this->userData['User']['id']);
		if (empty($recipients)) {
			$this->ScreenMessage->notice(__('You don\'t have a recipient list created.', true));
			$this->redirect(array('action' => 'index', 'sender'));
		}
		if (!empty($this->data)) {
			$this->data['Message']['sender_id'] = $this->userData['User']['id'];
			$this->Message->set($this->data);
			if ($this->Message->validates($this->data) && $messages = $this->Message->send(array_flip($recipients))) {
				foreach ($messages as $messageId) {
					$message = $this->Message->getById($messageId);
					// Send a separate notification to each recipient
					$this->Notification->send('message_received', array(
						'data' => $message,
						'userId' => $message['Message']['recipient_id'],
						'userEmail' => $this->userData['User']['email'],
					));
				}
				$this->ScreenMessage->success(__('Your message was sent successfully.', true));
				$this->redirect(array('action' => 'index', 'sender'));
			} else {
				$this->ScreenMessage->error(__('There was a problem sending your message. Please check the form below.', true));
			}
		}
		$this->set('recipients', $recipients);
		$this->set('orderId', $order_id);
	}
	
	private function __process_external_attachments($attachments, $process_id, $user) {
		$new_document_ids = array();
		if(!empty($attachments)) {
			foreach($attachments as $attachment) {
				$temp = explode('.', $attachment['name']['name']);
				$ext = array_pop($temp);
				$title = implode('.', $temp);
				
				$data['controller'] = 'messages';
				$data['model'] = 'Message';
				$data['foreign_key'] = null;
				$data['process_id'] = $process_id;
				$data['creator_id'] = $user['User']['id'];
				$data['name'] = $attachment['name']['name'];
				$data['title'] = $attachment['name']['name'];
				#$data['title'] = $title;
				$data['ext'] = $ext;
				$data['bytes'] = $attachment['name']['size'];
				$data['tmp_name'] = $attachment['name']['tmp_name'];
				$data['mime_type'] = $attachment['name']['type'];
				$data['type'] = $attachment['name']['type'];
				$data['size'] = $attachment['name']['size'];
				$data['upl'] = $attachment['name'];
				
				$this->Document->create();
				if($this->Document->save($data, false)) {
					// Obtain the new id.
					$id = $this->Document->getLastInsertID();
					$new_document_ids[$id] = $id;
				}
			}
		}
		if(empty($new_document_ids)) {
			return null;
		}
		return serialize($new_document_ids);
	}
	
	private function __generateSystemDocsToLocal($systemDocs, $processId) {
		$attachments = array();
		$systemDocs = unserialize($systemDocs);
	
		// Use the CustomersQuoteId selected from the form.
		//$this->request->data['Quote']['customer'] contains the customers_quote_id
		$attachmentPath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS.'Attachments';
		$path = $attachmentPath.DS.$processId;
		
		if (!file_exists($attachmentPath)) {
			if (!@mkdir($attachmentPath)) {
				return null;
			}
		}
		if (!file_exists($path)) {
			if (!@mkdir($path)) {
				return null;
			}
		}
		$path = $path . DS;
		
		if(!empty($systemDocs)) {
			foreach($systemDocs as  $systemDoc) {
				$filename = $systemDoc['display'];
				switch ($systemDoc['doc_type']) {
					case 'quote' :
						// Genrate PDF
						$this->GeneratePdf->model = 'Quote';
						$this->GeneratePdf->url = Configure::read('Environment.host').Router::url($systemDoc['generate_pdf_link']);
						$this->GeneratePdf->header = Configure::read('Environment.host').Router::url(array('controller' => 'quotes', 'action' => 'create_quote_pdf_header'));
						$this->GeneratePdf->footer = Configure::read('Environment.host').Router::url(array('controller' => 'quotes', 'action' => 'create_quote_pdf_footer'));
						break;
						
					case 'order' :
						// Genrate PDF
						$this->GeneratePdf->model = 'Invoice';
						$this->GeneratePdf->url = Configure::read('Environment.host').Router::url($systemDoc['generate_pdf_link']);
						$this->GeneratePdf->header = Configure::read('Environment.host').Router::url(array('controller' => 'invoices', 'action' => 'view_pdf_header', $systemDoc['id']));
						$this->GeneratePdf->footer = Configure::read('Environment.host').Router::url(array('controller' => 'invoices', 'action' => 'view_pdf_footer', $systemDoc['id']));
						break;
				}
				$filename = str_replace('.pdf','', $filename);
				// Save pdf to the server.  Useful for attachments.
				if ($results = $this->GeneratePdf->convert($path, $filename)) {
					$attachments[] = $path.$filename.'.pdf';
				} 
			}
		}
		return $attachments;
	}
	
	private function __transferAttachementsFromCloudToLocal($attachments, $processId) {
		if(empty($attachments)) {
			return null;
		}
		$document_ids = unserialize($attachments);
		$conditions = array('Document.id' => $document_ids);
		$documents = $this->Document->find('all', array('conditions' => $conditions));
		if(empty($documents)) {
			return null;
		}
		
		/* 
		 * Cloud Processing
		 * Initialize - obtain AWS access information
		 */
		$aws = Configure::read('AWS.' . Configure::read('Environment.platform'));
		$s3_status = $aws['status'];
		$aws_options = array(
			'key' => $aws['key'],
			'secret' => $aws['secret'],
			'bucket' => $aws['bucket'],
			'acl' => $aws['acl'],
		);
		
		// Include the AWS_SDK class and Instantiate the class
		$options = Configure::read('AWS.'.Configure::read('Environment.platform'));
		App::import('Vendor', 'AWS_SDK', array('file' => $options['sdk']));
		$s3 = new AmazonS3($aws_options);
		
		$attachment_string = array();
		$filePath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS;
		$attachmentPath = $filePath.'Attachments';
		foreach($documents as $document) {
			// Move the file from the cloud to a temporary directory.
			$attachmentFile = str_replace(" " , "-", $document['Document']['title']).'.'.pathinfo($document['Document']['name'], PATHINFO_EXTENSION);
			$attachmentFile = str_replace("/" , "_", $document['Document']['title']).'.'.pathinfo($document['Document']['name'], PATHINFO_EXTENSION);
						
			// Check if $tmpPath exists
			if (!file_exists($attachmentPath)) {
				if (!@mkdir($attachmentPath)) {
					return null;
				}
			}
			if (!file_exists($attachmentPath.DS.$processId)) {
				if (!@mkdir($attachmentPath.DS.$processId)) {
					return null;
				}
			}

			$subfolder = $aws['sub-folder'];
			if(!empty($subfolder)) {
				$filepath = $aws['sub-folder'].DS.'Document'.DS.$document['Document']['name'];
			} else {
				$filepath = 'Document'.DS.$document['Document']['name'];
			}
			
			$options_s3 = array(
				'fileDownload' => $attachmentPath.DS.$processId.DS.$attachmentFile,
			);
			$result = $s3->get_object($aws['bucket'], $filepath, $options_s3);
			if ($result->status == $s3_status['ok']) {
				array_push($attachment_string, $attachmentPath.DS.$processId.DS.$attachmentFile);
			}
		}
		
		if(empty($attachment_string)) {
			return null;
		}
		return $attachment_string;
	}
	
	private function __moveAttachmentFilesLocal($attachments, $process_id) {
		/*
		 * This is the functionality to use if not saving the attachments to the cloud but just a tempory spot on the server.
		 * The Goal is to move these documents into temperary storage on the server.
		 * building an array of
		 *
		 */
		$attachment_path = array();
		$attachmentPath = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS.'Attachments';
		
		// BEGIN Path Verification
		// Verify that the path exists on the server.
		if (!file_exists($attachmentPath)) {
			if (!@mkdir($attachmentPath)) {
				return null;
			}
		}
		if (!file_exists($attachmentPath.DS.$process_id)) {
			if (!@mkdir($attachmentPath.DS.$process_id)) {
				return null;
			}
		}
		// END Path Verification
		foreach($attachments as $attachment) {
			if (move_uploaded_file($attachment['name']['tmp_name'], $attachmentPath.DS.$process_id.DS.$attachment['name']['name'])) {
				// Add the path to the attacments array
				array_push($attachment_path, $attachmentPath.DS.$process_id.DS.$attachment['name']['name']);
			}
		}
		
		if(empty($attachment_path)) {
			return null;
		}
		return serialize($attachment_path);
	}
	
	public function getMessages($mode, $id, $type = null) {
		$process_ids = null;
		$limit = Configure::read('Paginate.list.limit');
		$conditions = null;
		switch ($mode) {
			case 'Customer' :
				// Access the Contact table to obtain a list of contacts for that Customer.
				$ids = $this->Contact->getContactIdsForCustomer($id);
				if(!empty($ids)) {
					// Obtain all the messages
					$process_ids = $this->Message->getProcessIdsForContacts($ids);
					$conditions = array('Message.process_id' => $process_ids, 'NOT' => array('Message.subject' => 'comment'));
				}
				break;
			case 'Quote' :
				$model = 'Quote';
				$foreign_key = $id;
				$process_ids = $this->Message->getProcessIdsForModel($model, $foreign_key);
				$conditions = array('Message.process_id' => $process_ids, 'NOT' => array('Message.subject' => 'comment'));
				break;
			case 'Order' :
				$model = 'Order';
				$foreign_key = $id;
				$process_ids = $this->Message->getProcessIdsForModel($model, $foreign_key);
				$conditions = array('Message.process_id' => $process_ids, 'NOT' => array('Message.subject' => 'comment'));
				break;
			case 'OrderExpense' :
				$model = 'OrderExpense';
				$foreign_key = $id;
				$process_ids = $this->Message->getProcessIdsForModel($model, $foreign_key);
				$conditions = array('Message.process_id' => $process_ids, 'NOT' => array('Message.subject' => 'comment'));
				$limit = 10000;
				break;
			case 'OrderMaterial' :
				$model = 'OrderMaterial';
				$conditions = array('Message.model' => $model, 'Message.foreign_key' => $id, 'NOT' => array('Message.subject' => 'comment'));
				$limit = 10000;
				break;
			case 'OrderTime' :
				$model = 'OrderTime';
				$conditions = array('Message.model' => $model, 'Message.foreign_key' => $id, 'NOT' => array('Message.subject' => 'comment'));
				$limit = 10000;
				break;
			case 'Schedule' :
				$model = 'Schedule';
				$foreign_key = $id;
				$process_ids = $this->Message->getProcessIdsForModel($model, $foreign_key);
				$conditions = array('Message.process_id' => $process_ids, 'NOT' => array('Message.subject' => 'comment'));
				$limit = 10000;
				break;
			case 'Account' :
				// Access the Contact table to obtain a list of contacts for that Account.
				$ids = $this->Contact->getContactIdsForAccount($id);
				if(!empty($ids)) {
					// Obtain all the messages
					$process_ids = $this->Message->getProcessIdsForContacts($ids);
					$conditions = array('Message.process_id' => $process_ids, 'NOT' => array('Message.subject' => 'comment'));
				}
				break;
		}
		
		if(empty($conditions)) {
			return null;
		}
		
		if(!empty($type)) {
			$conditions['Message.type'] = $type;
		}
		$this->Paginator->settings = array(
			'limit' => $limit,
			//'contain' => $this->Message->contain['default'],
			'order' => array('Message.read' => 'ASC', 'Message.created' => 'DESC'),
			'conditions' => $conditions,
			'fields' => array('Message.process_id'),
			'group' => array('Message.process_id'),
		);
		$results = $this->Paginator->paginate($this->Message);
		if(!empty($results)) {
			foreach($results as $key=>$result) {
				$results[$key] = $this->Message->getMessageForProcessId($result['Message']['process_id']);
			}
		}
		return $results;
	}
	
	public function getComments($model, $id, $type=null) {
		$results = $this->Message->getComments($model, $id, $type);
		return $results;
	}
	
	public function save_comment($data, $user) {
		/*
		 * Comments are different from messages in that (at this time) no emails are sent, and the user
		 * does not select whom receives the message.  The message is formost associated with the record,
		 * Secondly, the message may contain one or more $to's
		 */
		$message_ids = array();
		$processId = uniqid();
		$success = true;
		$error = null;
		$notify_by_email = false;
		
		$content = $data['Message']['content'];
		$subject = $data['Message']['subject'];
		$from = $data['Message']['from'];
		$tos = $data['Message']['to'];
		$model = $data['Message']['model'];
		$foreign_key = $data['Message']['foreign_key'];
		$parent_model = $data['Message']['parent_model'];
		$parent_foreign_key = $data['Message']['parent_foreign_key'];
		$type = $data['Message']['type'];
		$parent = null;
		
		$message['Message']['sender_id'] = $user['User']['id'];
		$message['Message']['sender_model'] = 'User';
		$message['Message']['subject'] = $subject;
		$message['Message']['content'] = $content;
		$message['Message']['process_id'] = $processId;
		$message['Message']['parent_id'] = $parent;
		$message['Message']['model'] = $model;
		$message['Message']['foreign_key'] = $foreign_key;
		$message['Message']['parent_model'] = $parent_model;
		$message['Message']['parent_foreign_key'] = $parent_foreign_key;
		$message['Message']['type'] = $type;
		$message['Message']['attachments'] = null;
		$message['Message']['attachments_system_docs'] = null;
		$message['Message']['recipient_model'] = 'User';
		$message['Message']['recipient_email'] = null;
		
		if(empty($tos)) {
			// save once!
			$message['Message']['recipient_id'] = null;
			$this->Message->save($message);
		} else {
			// loop through the to's
			foreach($tos as $key=>$to) {
				$message['Message']['recipient_id'] = $to;
				$this->Message->save($message);
			}
		}
		
		/* ACTION LOG */
		$message['Message']['to'] = null;
		$this->__action_log('post_comment', $message);
		return true;
	}
	
	public function send_message($data, $user) {
		$message_ids = array();
		$processId = uniqid();
		$success = true;
		$error = null;
		if (empty($data)) {
			return null;
		}
		$notify_by_email = true;
		if(array_key_exists('notify_by_email', $data['Message'])) {
			if($data['Message']['notify_by_email'] == 1) {
				$notify_by_email = true;
			} else {
				$notify_by_email = false;
			}
		}
		$content = $data['Message']['content'];
		if(array_key_exists('watermark', $data['Message'])){
			$content = 'Originated from: ' . $data['Message']['watermark'] . "\r\n\r\n" . $content;
		}
		$subject = $data['Message']['subject'];
		$from = $data['Message']['from'];
		$to = array();
		$parent = $data['Message']['parent_id'];
		$cc = 0;
		if(array_key_exists('cc', $data['Message']) && $data['Message']['cc'] == 1) {
			$cc = 1;
		}
		$message['Message']['sender_id'] = $user['User']['id'];
		$message['Message']['sender_model'] = 'User';
		$message['Message']['subject'] = $subject;
		$message['Message']['content'] = $content;
		$message['Message']['process_id'] = $processId;
		$message['Message']['parent_id'] = $parent;
		$message['Message']['cc'] = $cc;
		$message['Message']['model'] = $data['Message']['model'];
		$message['Message']['foreign_key'] = $data['Message']['foreign_key'];
		
		// ATTACHMENTS
		// Are there attachments?
		// External Attachments... Documents selected form the users machine.  These need to be uploaded to the cloud and stored in the Documents table
		if(!empty($data['AttachmentExternal'][0]['name']['name'])) {
			$message['Message']['attachments'] = $this->__process_external_attachments($data['AttachmentExternal'], $processId, $user);
		}
		
		// Second, are there any documents that have been uploaded to the system that have been selected for attachment?
		if(array_key_exists('AttachmentDocument', $data) && !empty($data['AttachmentDocument'])) {
			$currentAttachments = array();
			if(!empty($message['Message']['attachments'])) {
				$currentAttachments = unserialize($message['Message']['attachments']);
			}
			$selected_uploads = array();
			foreach($data['AttachmentDocument'] as $key=>$data_doc) {
				if($data_doc['id'] == 1) {
					// Document was selected.
					$selected_uploads[$key] = $key;
				}
			}
			$currentAttachments = array_merge($currentAttachments, $selected_uploads);
			if(!empty($currentAttachments)) {
				$message['Message']['attachments'] = serialize($currentAttachments);
			}
		}
		
		// Last... System generated documents (Proposals and such)
		$message['Message']['attachments_system_docs'] = null;
		if(array_key_exists('AttachmentSystemDocs', $data)) {
			$systemdocs = array();
			// Each model has it's own System generated documents
			switch ($data['Message']['model']) {
				case 'Quote' :
					$quote = $this->Quote->getById($data['Message']['foreign_key']);
					if(!empty($quote)) {
						// Check if Quote was selected
						if($data['AttachmentSystemDocs']['attach_quote'] == 1) {
							$tmp['display'] = Configure::read('Nomenclature.Proposal').'_SID#'.$quote['Quote']['sid'].'_' . date('Y-m-d') . '.pdf';	
							$tmp['id'] = $data['Message']['foreign_key'];
							$tmp['model'] = 'Quote';
							$tmp['doc_type'] = 'quote';
							$tmp['link'] = array('controller' => 'quotes', 'action' => 'print_pdf', $data['Message']['foreign_key']);
							$tmp['generate_pdf_link'] = array('controller' => 'quotes', 'action' => 'view_pdf', $data['Message']['foreign_key']);
							$tmp['img_class'] = 'icon-file-pdf';
							$systemdocs[] = $tmp;
						}
					}
					break;
				case 'Order' :
					// Loop through the documents
					foreach($data['AttachmentSystemDocs'] as $key=>$sys_docs) {
						if($key == 'attach_invoice' && !empty($sys_docs)) {
							// Determine if any invoices were selected
							foreach($sys_docs as $invoice_id=>$invoice) {
								if(!empty($invoice)) {
									// Include the Invoice!!!
									$tmp['display'] = 'Invoice_' . date('Y-m-d') . '.pdf';
									$tmp['id'] = $invoice_id;
									$tmp['model'] = 'Order';
									$tmp['doc_type'] = 'order';
									$tmp['link'] = array('controller' => 'invoices', 'action' => 'print_pdf', $invoice_id);
									$tmp['generate_pdf_link'] = array('controller' => 'invoices', 'action' => 'view_pdf', $invoice_id);
									$tmp['img_class'] = 'icon-file-pdf';
									$systemdocs[] = $tmp;
								}
							}
						}
					}
					break;
			}
			if(!empty($systemdocs)) {
				$message['Message']['attachments_system_docs'] = serialize($systemdocs);
			}
		}
		// END ATTACHMENTS
		$to_write_ins = null;
		$to_employees = null;
		$to_contacts = null;
		if(!empty($data['Message']['to_employee_id'])) {
			$to_employees = $data['Message']['to_employee_id'];
		}
		if(!empty($data['Message']['to_contact_id'])) {
			$to_contacts = $data['Message']['to_contact_id'];
		}
		
		if(!empty($data['Message']['to'])) {
			$to_write_ins = explode(';', $data['Message']['to']);
			// Loop through the write ins and check the contacts table (and users) to see if there are matches
			foreach($to_write_ins as $key=>$write_in) {
				// Check Users.
				$user_id = $this->User->field('id', array('User.email' => trim($write_in)));
				if(!empty($user_id)) {
					//  The write in is a User... Add the person to the $to_employees array().
					$to_employees[$user_id] = $user_id;
					unset($to_write_ins[$key]);
				} else {
					// Check the Contact table.
					$contact_id = $this->Contact->field('id', array('Contact.email' => trim($write_in)));
					if(!empty($contact_id)) {
						//  The write in is a Contact... Add the person to the $to_contacts array().
						$to_contacts[$contact_id] = $contact_id;
						unset($to_write_ins[$key]);
					}
				}
			}
			
			// Now Loop through... 
			// Creating message records in Message table for each write-in.
			if(!empty($to_write_ins)) {
				foreach($to_write_ins as $write_in) {
					$message['Message']['recipient_model'] = null;
					$message['Message']['recipient_id'] = null;
					$message['Message']['recipient_email'] = $write_in;
					$to[] = $write_in; 
					$this->Message->create();
					if($this->Message->save($message)) {
						$new_id = $this->Message->getLastInsertId();
						$message_ids[$new_id] = $new_id;
					} else {
						$success = false;
					}
				}
			}
		}
		if(!empty($to_employees)) {
			// Now Loop through...
			// Creating message records in Message table for each employee.
			foreach($to_employees as $to_employee) {
				$message['Message']['recipient_model'] = 'User';
				$message['Message']['recipient_id'] = $to_employee;
				$message['Message']['recipient_email'] = $this->User->field('email', array('User.id' => $to_employee));
				$to[] = $message['Message']['recipient_email'];
				$this->Message->create();
				if($this->Message->save($message)) {
					$new_id = $this->Message->getLastInsertId();
					$message_ids[$new_id] = $new_id;
				} else {
					$success = false;
				}
			}
		}
		if(!empty($to_contacts)) {
			// Now Loop through...
			// Creating message records in Message table for each contact.
			foreach($to_contacts as $to_contact) {
				$message['Message']['recipient_model'] = 'Contact';
				$message['Message']['recipient_id'] = $to_contact;
				$message['Message']['recipient_email'] = $this->Contact->field('email', array('Contact.id' => $to_contact));
				$to[] = $message['Message']['recipient_email'];
				$this->Message->create();
				if($this->Message->save($message)) {
					$new_id = $this->Message->getLastInsertId();
					$message_ids[$new_id] = $new_id;
				} else {
					$success = false;
				}
			}
		}
		
		if(!empty($message_ids) && $notify_by_email) {
			// EMAIL all the messages 
			$error = $this->__emailMessages($message_ids);
			if(!empty($error)) {
				$success = false;
			}
		}
		
		if($success) {
			$this->controller->ScreenMessage->success(__('Messages have been successfully sent.', true));
		} else {
			if(!empty($error)) {
				$this->controller->ScreenMessage->notice(__($error, true));
			} else {
				$this->controller->ScreenMessage->notice(__('Error occured during the messaging process.', true));
			}
		}
		/* ACTION LOG */
		$message['Message']['to'] = $to;
		$this->__action_log('send', $message);
		/*
		 * Redirect
		 * Determine where the user needs to be sent back too.
		 */
		$redirect = $data['Message']['redirect'];
		$redirect_exploded = explode(',', $redirect);
		switch ($redirect_exploded[0]) {
			case 'customer' :
				$link = array('controller' => 'customers', 'action' => 'messages', $redirect_exploded[1]);
				break;
			case 'users' :
				$link = array('controller' => 'users', 'action' => 'dashboard', 'messages', 'inbox');
				break;
			case 'quote' :
				$link = array('controller' => 'quotes', 'action' => 'messages', $redirect_exploded[1]);
				break;
			case 'order' :
				$link = array('controller' => 'orders', 'action' => 'messages', $redirect_exploded[1]);
				break;
			case 'account' :
				$link = array('controller' => 'accounts', 'action' => 'messages', $redirect_exploded[1]);
				break;
			case 'invoice' :
				$link = array('controller' => 'invoices', 'action' => 'edit', $redirect_exploded[1]);
				break;
		}
		$this->controller->redirect($link);
	}
	
	public function send_notification($trigger, $options = array()) {
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
		App::import('Vendor', 'AWS_SDK', array('file' => Configure::read('environment.AWS.sdk')));
		// Instantiate the class
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
			$this->Email->subject = 'Business 360 [' . $this->Session->read('Application.settings.ApplicationSetting.company_name') . ']: '.$notification['NotificationTrigger']['subject'];
			
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
				$attach_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS.'Attachments'.DS;
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
	
	private function __emailMessages($message_ids) {
		if(!empty($message_ids)) {
			$conditions = array('Message.id' => $message_ids);
			$results = $this->Message->find('all', array('conditions' => $conditions));
			if(!empty($results)) {
				// Before anything... Determine if there are attachments associated with the email.  If so, the documents 
				// are on the cloud and need to be moved to the sever temporarily
				$attachments = array();
				$attachment_sys_docs = array();
				if(!empty($results[0]['Message']['attachments'])) {
					$attachments = $this->__transferAttachementsFromCloudToLocal($results[0]['Message']['attachments'], $results[0]['Message']['process_id']);
				}
				if(!empty($results[0]['Message']['attachments_system_docs'])) {					
					$attachment_sys_docs = $this->__generateSystemDocsToLocal($results[0]['Message']['attachments_system_docs'], $results[0]['Message']['process_id']); 
				}
				$attachments = array_merge($attachments, $attachment_sys_docs);
				$attachments = serialize($attachments);
			
				// Extract all $results[n]['Message']['recipient_email']
				$to = Set::extract('/Message/recipient_email', $results);
				$this->User->id = $results[0]['Message']['sender_id'];
				$from = $this->User->field('email');
				$processId = $results[0]['Message']['process_id'];
				$subject = $results[0]['Message']['subject'];
				$body_text = $results[0]['Message']['content'];
				$body_html = null;
				$data = array(
					'EmailMessage' => array(
						'process_id' => $processId,
						'to' => serialize($to),
						'from' => serialize($from),
						'subject' => $subject,
						'body_text' => $body_text,
						'body_html' => $body_html,
					)
				);
				if(array_key_exists('cc', $results[0]['Message']) && $results[0]['Message']['cc'] == 1) {
					$data['EmailMessage']['cc'] = serialize(array($from));
				}
				if(!empty($attachments)) {
					$data['EmailMessage']['attachments'] = $attachments;
				}
				$this->EmailMessage = ClassRegistry::init('EmailMessage');
				$this->EmailMessage->create();	
				if (!$this->EmailMessage->save($data, false)) {
					return 'Error occured during the email process... Please contact the System Administrator.';
				}
				
				// Call app_mailer.php
				// if ((Configure::read('environment.platform') == 'production') || (Configure::read('environment.platform') == 'stage')) {
				if (Configure::read('Environment.platform') == 'production') {
					// !!! NO MAIL WILL BE SENT IN development OR stage ENVIRONMENTS !!!
					$options = '-c '.ROOT.DS.APP_DIR.DS.'php.ini ';
					$suffix = ' > /dev/null 2>&1 & echo $!';
					$attach_path = ROOT.DS.APP_DIR.DS.WEBROOT_DIR.DS.Configure::read('Path.files').DS.'Attachments'.DS;
					$script = 'php '.$options.ROOT.DS.APP_DIR.DS.'Lib'.DS.'AppMailer.php '.Configure::read('Environment.platform').' '.$processId.' '.$attach_path.$suffix;
					$pid = exec($script);
				}
			}
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
	
	private function __action_log($action, $data) {
		$user = $this->User->getById($data['Message']['sender_id']);
		$params['model'] = $data['Message']['model'];
		$params['foreign_key'] = $data['Message']['foreign_key'];
		$params['redirect']['controller'] = '';
		$params['redirect']['action'] = '';
		$params['redirect']['params'] = '';
		switch ($action) {
			case 'send' :
				$params['subject'] = 'Send Message';
				$params['action'] = 'sent: <i>' . $data['Message']['subject'] . ' : ' . $data['Message']['content'] . '</i><br/>to: ' . implode(', ', $data['Message']['to']);
				break;
			case 'post_comment' :
				$params['subject'] = 'Post Comment';
				$params['action'] = 'commented on: <i>' . $data['Message']['subject'] . ' : ' . $data['Message']['content'] . '</i>';
				break;
		}
	
		$this->ActionLog->add($params, $user);
	}
}
?>
