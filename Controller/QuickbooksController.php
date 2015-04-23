<?php
App::uses('AppController', 'Controller');
/**
 * Quickbooks Controller
 *
 * @property Contact $Quickbook
 */
class QuickbooksController extends AppController {
	public $uses = array('QuickbooksQueue', 'Customer');
	public $acoType = ACL_ACO_TYPE_PUBLIC;
	public $allowNonSSL = true;
	
	public function beforeFilter() {
		parent::beforeFilter();
		//$this->response->type(array('plain' => 'text/plain', 'xml' => 'text/xml'));
		$this->response->type(array('xml' => 'text/xml'));
	}
	
	public function quickbooks_web_connector() {
		// Launch the web Connector
		#$result = $this->QuickbooksQueue->TESTlaunchWebConnector();
		$result = $this->QuickbooksQueue->launchWebConnector();
		
		// Set the response Content-Type to plain.
		$this->response->type('plain');
		$this->response->body($result);
		// Return response object to prevent controller from trying to render a view
		return $this->response;
		
		// If you wanted, you could do something with $response here for debugging
		/*
		$path = Configure::read('Path.files').DS.'Document'.DS.'file.log';
		$fp = fopen($path, 'a+');
		fwrite($fp, $result);
		fclose($fp);
		*/
	}

	public function quickbooks_customer_add_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale) {
		$this->layout = false;
		Configure::write('debug', 0);
		
		// Obtain the Customer from information from the Customer Model.
		$result = $this->Customer->getQuickbooksFormated($ID);
		if(!empty($result)) {
			$qbxml = '<?xml version="1.0" encoding="utf-8"?>
			<?qbxml version="7.0"?>
			<QBXML>
				<QBXMLMsgsRq onError="stopOnError">
					<CustomerAddRq requestID="' . $requestID . '">
						<CustomerAdd>
							<Name>' . $result['name'] . '</Name>
							<CompanyName>' . $result['company_name'] . '</CompanyName>
							<Contact>' . $result['contact'] . '</Contact>
							<BillAddress>
								<Addr1>' . $result['billing_addr1'] . '</Addr1>
								<Addr2>' . $result['billing_addr2'] . '</Addr2>
								<City>' . $result['billing_city'] . '</City>
								<State>' . $result['billing_state'] . '</State>
								<PostalCode>' . $result['billing_post_code'] . '</PostalCode>
								<Country>' . $result['billing_country'] . '</Country>
							</BillAddress>
							<Phone>' . $result['phone'] . '</Phone>
							<AltPhone>' . $result['alt_phone'] . '</AltPhone>
							<Fax>' . $result['fax'] . '</Fax>
							<Email>' . $result['email'] . '</Email>
						</CustomerAdd>
					</CustomerAddRq>
				</QBXMLMsgsRq>
			</QBXML>';
		}
		
		// Set the response Content-Type to plain.
		$this->response->type('xml');
		$this->response->body($qbxml);
		return $this->response;
	}
	
	public function quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents) {
		/*
		 * Great, customer $ID has been added to QuickBooks with a QuickBooks
		 * ListID value of: $idents['ListID']
		 *
		 * We probably want to store that ListID in our database, so we can use it
		 * later. (You'll need to refer to the customer by either ListID or Name
		 * in other requests, say, to update the customer or to add an invoice for
		 * the customer.
		 */
		$list_id = $idents['ListID'];
		$seq = $idents['EditSequence'];
		$this->Customer->id = $ID;
		$this->Customer->saveField(Configure::read('Quickbooks.foreign_key.customer'), $list_id);
		$this->Customer->saveField('quickbooks_editsequence', $seq);
	}
	
	public function quickbooks_customer_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale) {
		$result = $this->Customer->getQuickbooksFormated($ID);
		if(!empty($result)) {
			$qbxml = '<?xml version="1.0" encoding="utf-8"?>
			<?qbxml version="8.0"?>
			<QBXML>
				<QBXMLMsgsRq onError="stopOnError">
					<CustomerAddRq requestID="' . $requestID . '">
						<CustomerAdd>
							<ListID>' . $result['list_id'] . '</ListID>
							<Name>' . $result['name'] . '</Name>
							<CompanyName>' . $result['company_name'] . '</CompanyName>
							<Contact>' . $result['contact'] . '</Contact>
							<BillAddress>
								<Addr1>' . $result['billing_addr1'] . '</Addr1>
								<Addr2>' . $result['billing_addr2'] . '</Addr2>
								<City>' . $result['billing_city'] . '</City>
								<State>' . $result['billing_state'] . '</State>
								<PostalCode>' . $result['billing_post_code'] . '</PostalCode>
								<Country>' . $result['billing_country'] . '</Country>
							</BillAddress>
							<Phone>' . $result['phone'] . '</Phone>
							<AltPhone>' . $result['alt_phone'] . '</AltPhone>
							<Fax>' . $result['fax'] . '</Fax>
							<Email>' . $result['email'] . '</Email>
						</CustomerAdd>
					</CustomerAddRq>
				</QBXMLMsgsRq>
			</QBXML>';
		}
		
		// Set the response Content-Type to plain.
		$this->response->type('xml');
		$this->response->body($qbxml);
		return $this->response;
	}
	
	public function quickbooks_customer_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents) {
		$list_id = $idents['ListID'];
		$this->Customer->id = $ID;
		$this->Customer->saveField(Configure::read('Quickbooks.foreign_key.customer'), $list_id);
	}
	
	public function quickbooks_customer_error_catchall($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg) {
		$this->Customer->id = $ID;
		$this->Customer->saveField('quickbooks_errnum', mysql_real_escape_string($errnum));
		$this->Customer->saveField('quickbooks_errmsg', mysql_real_escape_string($errmsg));
	}
}