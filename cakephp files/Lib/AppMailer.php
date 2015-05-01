#!/usr/bin/php
<?php
/**
 * No core.php file to load so we must manually set the timezone
 */
date_default_timezone_set('America/Los_Angeles');
/**
 * Load configuration file(s)
 */
require_once('AppFrameworkConnector.php');

/**
 * Script configuration
 */
require_once($environment['path']['cake_core'].'/vendors/swift/lib/swift_required.php');

$smtp = $config['Smtp'];

/**
 * Script processing 
 */
$query = "SELECT * FROM email_messages WHERE status = 0 ORDER BY created ASC LIMIT 15";
$results = mysql_query($query);
$hasAttachments = false;
if (mysql_num_rows($results) > 0) {
	// If results found, setup Swift_SmtpTransport
	$transport = Swift_SmtpTransport::newInstance($smtp['hostname'], $smtp['port']);
	$transport->setUsername($smtp['username']);
	$transport->setPassword($smtp['password']);
	if ($smtp['ssl']) {
		$transport->setEncryption('ssl');
	}
	// Instantiate Swift_Mailer object with appropriate transport object
	$mailer = Swift_Mailer::newInstance($transport);
	while ($data = mysql_fetch_array($results)) {
		// Instantiate Swift_Message object (setting subject in loop below)
		$message = Swift_Message::newInstance();
		try {
			// Configure Swift_Message object per record found
			$message->setSubject($data['subject']);
			$message->setFrom(unserialize($data['from']));
			$message->setTo(unserialize($data['to']));
			$message->setBody($data['body_text'], 'text/plain');
			if (!empty($data['body_html'])) {
				$message->addPart($data['body_html'], 'text/html');
			}
			if(!empty($data['cc'])) {
				$message->setCc(unserialize($data['cc']));
			}
			if (!empty($data['attachments'])) {
				$hasAttachments = true;
				$documents = unserialize($data['attachments']);
				if(!empty($documents)) {
					foreach($documents as $document) {
						$attachment = Swift_Attachment::fromPath($document);
						$message->attach($attachment);
					}
				}
			}
			
			// Perform send operation
			$sent = $mailer->send($message);
		} catch (Exception $e) {
			$sent = false;
			// Log exception error to db & log file
			$dbLog = mysql_real_escape_string(date('Y-m-d G:i:s').': '.$e->getTraceAsString());
			echo date('Y-m-d G:i:s').': Failed sending to: '.array_shift(unserialize($data['to'])).'. DB record ID: '.$data['id']."\r\n";
		}
		if ($sent !== false) {
			if ($environment['platform'] == 'development') {
				// Leave status as unsent for easier testing
				$status = 0;
			} else {
				// In staging & production, flag message as sent
				$status = 1;
			}
			$query = "UPDATE email_messages SET modified=NOW(), status=".$status." WHERE id=".$data['id']." LIMIT 1";
		} else {
			// Send operation failed, log full stack trace to db
			$query = "UPDATE email_messages SET modified=NOW(), status=-1, log='".$dbLog."' WHERE id=".$data['id']." LIMIT 1";
		}
		mysql_query($query);
		
		/*
		 * Clean Up Attachment Files
		 */
		if($hasAttachments) {
			$query_cleanup = "SELECT * FROM email_messages WHERE process_id = '" . $argv[2] . "'";
			$results_cleanup = mysql_query($query_cleanup);
			if (mysql_num_rows($results_cleanup) > 0) {
				while ($data2 = mysql_fetch_array($results_cleanup)) {	
					if (!empty($data2['attachments'])) {	
						$process_id = $data2['process_id'];
						$documents = unserialize($data['attachments']);
						if(!empty($documents)) {
							foreach($documents as $document) {
								if(is_file($document)) {
							    	unlink($document); // delete file
							  	}
							}
							
							// After deleting the files, remove the directory
							$length = strpos($document, $process_id) + strlen($process_id);
							$dir = substr($document, 0, $length); 
							rmdir($dir);
						}
					}			
				}
			}
		}
		
		// Detach $message object body property for next iteration
		$message->detach(Swift_MimePart::newInstance($data['body_html'], 'text/html'));
		$message = null;
	}
	// Finished iteration, exit quietly
	die();
}
// Nothing to do, exit quietly
die();
?>