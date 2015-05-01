<?php
App::uses('AppModel', 'Model');
/**
 * QuickbooksQueue Model
 * 
 * @property Customer $Customer
 * @property Invoice $Invoice
 * @property InvoiceLineItem $InvoiceLineItem
 */
class QuickbooksQueue extends AppModel {

	public $name = 'QuickbooksQueue';
	public $belongsTo = array(
		'Customer' => array(
			'conditions' => array('QuickbooksQueue.model' => 'Customer'),
			'foreignKey' => 'foreign_key',
			'dependent' => false,
		),
		'Invoice' => array(
			'conditions' => array('QuickbooksQueue.model' => 'Invoice'),
			'foreignKey' => 'foreign_key',
			'dependent' => false,
		),
	);
	
	public function TESTlaunchWebConnector() {
		/**
		 * Example QuickBooks SOAP Server / Web Service
		 *
		 * This is an example Web Service which adds customers to QuickBooks desktop
		 * editions via the QuickBooks Web Connector.
		 *
		 * MAKE SURE YOU READ OUR QUICK-START GUIDE:
		 * 	http://wiki.consolibyte.com/wiki/doku.php/quickbooks_integration_php_consolibyte_webconnector_quickstart
		 * 	http://wiki.consolibyte.com/wiki/doku.php/quickbooks
		 *
		 * You should copy this file and use this file as a reference for when you are
		 * creating your own Web Service to add, modify, query, or delete data from
		 * desktop versions of QuickBooks software.
		 *
		 * The basic idea behind this method of integration with QuickBooks desktop
		 * editions is to host this web service on your server and have the QuickBooks
		 * Web Connector connect to it and pass messages to QuickBooks. So, every time
		 * that an action occurs on your website which you wish to communicate to
		 * QuickBooks, you'll queue up a request (shown below, using the
		 * QuickBooks_Queue class).
		 *
		 * You'll write request handlers which generate qbXML requests for each type of
		 * action you queue up. Those qbXML requests will be passed by the Web
		 * Connector to QuickBooks, which will then process the requests and send back
		 * the responses. Your response handler will then process the response (you'll
		 * probably want to at least store the returned ListID or TxnID of anything you
		 * create within QuickBooks) and this pattern will continue until there are no
		 * more requests in the queue for QuickBooks to process.
		 *
		 * @author Keith Palmer <keith@consolibyte.com>
		 *
		 * @package QuickBooks
		 * @subpackage Documentation
		 */		
		// I always program in E_STRICT error mode...
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 1);
		
		// We need to make sure the correct timezone is set, or some PHP installations will complain
		if (function_exists('date_default_timezone_set'))
		{
			// * MAKE SURE YOU SET THIS TO THE CORRECT TIMEZONE! *
			// List of valid timezones is here: http://us3.php.net/manual/en/timezones.php
			date_default_timezone_set('America/Los_Angeles');
		}
		
		// Require the framework
		$this->__requireFramwork();
		
		
		//  A username and password you'll use in:
		//	a) Your .QWC file
		//	b) The Web Connector
		//	c) The QuickBooks framework
		//
		// 	NOTE: This has *no relationship* with QuickBooks usernames, Windows usernames, etc.
		// 		It is *only* used for the Web Connector and SOAP server!
		$user = 'business360quickbooks';
		$pass = 'password';
		
		
		// Map QuickBooks actions to handler functions
		$map = array(
			QUICKBOOKS_ADD_CUSTOMER => array( 'quickbooks_customer_add_request', 'quickbooks_customer_add_response' ),
		);

		// This is entirely optional, use it to trigger actions when an error is returned by QuickBooks
		$errmap = array(
			3070 => 'quickbooks_error_stringtoolong',				// Whenever a string is too long to fit in a field, call this function: quickbooks_error_stringtolong()
			'CustomerAdd' => 'quickbooks_error_customeradd', 	// Whenever an error occurs while trying to perform an 'AddCustomer' action, call this function: quickbooks_error_customeradd()
			'*' => 'quickbooks_error_catchall', 				// Using a key value of '*' will catch any errors which were not caught by another error handler
		);
		
		// An array of callback hooks
		$hooks = array();
		
		/*
		 function quickbooks_hook_loginsuccess($requestID, $user, $hook, &$err, $hook_data, $callback_config)
		 {
		// Do something whenever a successful login occurs...
		}
		*/
		
		// Logging level
		//$log_level = QUICKBOOKS_LOG_NORMAL;
		//$log_level = QUICKBOOKS_LOG_VERBOSE;
		//$log_level = QUICKBOOKS_LOG_DEBUG;
		$log_level = QUICKBOOKS_LOG_DEVELOP;		// Use this level until you're sure everything works!!!
		
		// What SOAP server you're using
		//$soapserver = QUICKBOOKS_SOAPSERVER_PHP;			// The PHP SOAP extension, see: www.php.net/soap
		$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)
		
		$soap_options = array();	// See http://www.php.net/soap
		
		$handler_options = array(
				//'authenticate' => ' *** YOU DO NOT NEED TO PROVIDE THIS CONFIGURATION VARIABLE TO USE THE DEFAULT AUTHENTICATION METHOD FOR THE DRIVER YOU'RE USING (I.E.: MYSQL) *** '
				//'authenticate' => 'your_function_name_here',
				//'authenticate' => array( 'YourClassName', 'YourStaticMethod' ),
				'deny_concurrent_logins' => false,
				'deny_reallyfast_logins' => false,
		);		// See the comments in the QuickBooks/Server/Handlers.php file
		
		$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
				//'max_log_history' => 1024,	// Limit the number of quickbooks_log entries to 1024
				//'max_queue_history' => 64, 	// Limit the number of *successfully processed* quickbooks_queue entries to 64
		);
		 
		$callback_options = array();
		
		// * MAKE SURE YOU CHANGE THE DATABASE CONNECTION STRING BELOW TO A VALID MYSQL USERNAME/PASSWORD/HOSTNAME *
		//
		// This assumes that:
		//	- You are connecting to MySQL with the username 'root'
		//	- You are connecting to MySQL with an empty password
		//	- Your MySQL server is located on the same machine as the script ( i.e.: 'localhost', if it were on another machine, you might use 'other-machines-hostname.com', or '192.168.1.5', or ... etc. )
		//	- Your MySQL database name containing the QuickBooks tables is named 'quickbooks' (if the tables don't exist, they'll be created for you)
		//$dsn = 'mysql://root:root@localhost/quickbooks_server';
		//$dsn = 'mysql://creationsitedb:5#CRJ39kz4NivUEX3b)]Vd3]@my360e.com/creationsitebusiness360db';
		//$dsn = 'mysql://root:password@localhost/your_database';				// Connect to a MySQL database with user 'root' and password 'password'
		//$dsn = 'mysqli://root:@localhost/quickbooks_mysqli';					// Connect to a MySQL database using the PHP MySQLi extension
		//$dsn = 'mssql://kpalmer:password@192.168.18.128/your_database';		// Connect to MS SQL Server database
		//$dsn = 'pgsql://pgsql:password@localhost/your_database';				// Connect to a PostgreSQL database
		//$dsn = 'pearmdb2.mysql://root:password@localhost/your_database';		// Connect to MySQL using the PEAR MDB2 database abstraction library
		//$dsn = 'sqlite://example.sqlite';										// Connect to an SQLite database
		//$dsn = 'sqlite:///Users/keithpalmerjr/Projects/QuickBooks/docs/example.sqlite';	// Connect to an SQLite database
		$dsn = $this->__dataSource();
		
		if (!QuickBooks_Utilities::initialized($dsn)) {
			// Initialize creates the neccessary database schema for queueing up requests and logging
			QuickBooks_Utilities::initialize($dsn);
		
			// This creates a username and password which is used by the Web Connector to authenticate
			QuickBooks_Utilities::createUser($dsn, $user, $pass);
		
			// Queueing up a test request
			//
			// You can instantiate and use the QuickBooks_Queue class to queue up
			//	actions whenever you want to queue something up to be sent to
			//	QuickBooks. So, for instance, a new customer is created in your
			//	database, and you want to add them to QuickBooks:
			//
			//	Queue up a request to add a new customer to QuickBooks
			//	$Queue = new QuickBooks_Queue($dsn);
			//	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_new_customer);
			//
			// Oh, and that new customer placed an order, so we want to create an
			//	invoice for them in QuickBooks too:
			//
			//	Queue up a request to add a new invoice to QuickBooks
			//	$Queue->enqueue(QUICKBOOKS_ADD_INVOICE, $primary_key_of_new_order);
			//
			// Remember that for each action type you queue up, you should have a
			//	request and a response function registered by using the $map parameter
			//	to the QuickBooks_Server class. The request function will accept a list
			//	of parameters (one of them is $ID, which will be passed the value of
			//	$primary_key_of_new_customer/order that you passed to the ->enqueue()
			//	method and return a qbXML request. So, your request handler for adding
			//	customers might do something like this:
			//
			//	$arr = mysql_fetch_array(mysql_query("SELECT * FROM my_customer_table WHERE ID = " . (int) $ID));
			//	// build the qbXML CustomerAddRq here
			//	return $qbxml;
			//
			// We're going to queue up a request to add a customer, just as a test...
			//
			// NOTE: You would normally *never* want to do this in this file! This is
			//	meant as an initial test ONLY. See example_web_connector_queueing.php for more
			//	details!
			//
			// IMPORTANT NOTE: This particular example of queueing something up will
			//	only ever happen *once* when these scripts are first run/used. After
			//	this initial test, you MUST do your queueing in another script. DO NOT
			//	DO YOUR OWN QUEUEING IN THIS FILE! See
			//	docs/example_web_connector_queueing.php for more details and examples
			//	of queueing things up.
		
			$primary_key_of_your_customer = 5;
		
			$Queue = new QuickBooks_WebConnector_Queue($dsn);
			$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer);
		
			//  Also note the that ->enqueue() method supports some other parameters:
			// 	string $action				The type of action to queue up
			//	mixed $ident = null			Pass in the unique primary key of your record here, so you can pull the data from your application to build a qbXML request in your request handler
			//	$priority = 0				You can assign priorities to requests, higher priorities get run first
			//	$extra = null				Any extra data you want to pass to the request/response handler
			//	$user = null				If you're using multiple usernames, you can pass the username of the user to queue this up for here
			//	$qbxml = null
			//	$replace = true
			//
			//  Of particular importance and use is the $priority parameter. Say a new
			//	customer is created and places an order on your website. You'll want to
			//	send both the customer *and* the sales receipt to QuickBooks, but you
			//	need to ensure that the customer is created *before* the sales receipt,
			//	right? So, you'll queue up both requests, but you'll assign the
			//	customer a higher priority to ensure that the customer is added before
			//	the sales receipt.
			//
			//	Queue up the customer with a priority of 10
			// 	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer, 10);
			//
			//	Queue up the invoice with a priority of 0, to make sure it doesn't run until after the customer is created
			//	$Queue->enqueue(QUICKBOOKS_ADD_SALESRECEIPT, $primary_key_of_your_order, 0);
		} else {
			QuickBooks_Utilities::createUser($dsn, $user, $pass);
			$primary_key_of_your_customer = 5;
			$Queue = new QuickBooks_WebConnector_Queue($dsn);
			$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer);
		}
		
		// Create a new server and tell it to handle the requests
		// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
		$Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
		$response = $Server->handle(true, true);
		return $response;
	}
	
	public function launchWebConnector() {
		// I always program in E_STRICT error mode...
		error_reporting(E_ALL | E_STRICT);
		ini_set('display_errors', 1);
		
		// We need to make sure the correct timezone is set, or some PHP installations will complain
		if (function_exists('date_default_timezone_set'))
		{
			// * MAKE SURE YOU SET THIS TO THE CORRECT TIMEZONE! *
			// List of valid timezones is here: http://us3.php.net/manual/en/timezones.php
			date_default_timezone_set('America/Los_Angeles');
		}
		
		// Require the framework
		$this->__requireFramwork();
		
		// A username and password you'll use in:
		//	a) Your .QWC file
		//	b) The Web Connector
		//	c) The QuickBooks framework
		//
		// 	NOTE: This has *no relationship* with QuickBooks usernames, Windows usernames, etc.
		// 		It is *only* used for the Web Connector and SOAP server!
		$user = 'business360quickbooks';
		$pass = 'password';
		
		// The next three parameters, $map, $errmap, and $hooks, are callbacks which
		//	will be called when certain actions/events/requests/responses occur within
		//	the framework. The examples below show how to register callback
		//	*functions*, but you can actually register any of the following, using
		//	these formats:
		// Map QuickBooks actions to handler functions
		$map = array(
			QUICKBOOKS_ADD_CUSTOMER => array( 'quickbooks_customer_add_request', 'quickbooks_customer_add_response' ),
			QUICKBOOKS_MOD_CUSTOMER => array( 'quickbooks_customer_mod_request', 'quickbooks_customer_mod_response' ),
		);
		
		// This is entirely optional, use it to trigger actions when an error is returned by QuickBooks
		$errmap = array(
			3070 => 'quickbooks_error_stringtoolong',				// Whenever a string is too long to fit in a field, call this function: quickbooks_error_stringtolong()
			'CustomerAdd' => 'quickbooks_error_customeradd', 	// Whenever an error occurs while trying to perform an 'AddCustomer' action, call this function: quickbooks_error_customeradd()
			'*' => 'quickbooks_error_catchall', 				// Using a key value of '*' will catch any errors which were not caught by another error handler
		);
		
		// An array of callback hooks
		$hooks = array(
			// There are many hooks defined which allow you to run your own functions/methods when certain events happen within the framework
			// QuickBooks_WebConnector_Handlers::HOOK_LOGINSUCCESS => 'quickbooks_hook_loginsuccess', 	// Run this function whenever a successful login occurs
		);
		
		// Logging level
		//$log_level = QUICKBOOKS_LOG_NORMAL;
		//$log_level = QUICKBOOKS_LOG_VERBOSE;
		$log_level = QUICKBOOKS_LOG_DEBUG;
		//$log_level = QUICKBOOKS_LOG_DEVELOP;		// Use this level until you're sure everything works!!!
		
		// What SOAP server you're using
		//$soapserver = QUICKBOOKS_SOAPSERVER_PHP;			// The PHP SOAP extension, see: www.php.net/soap
		$soapserver = QUICKBOOKS_SOAPSERVER_BUILTIN;		// A pure-PHP SOAP server (no PHP ext/soap extension required, also makes debugging easier)
		
		$soap_options = array(  // See http://www.php.net/soap  
		);
		
		$handler_options = array(
			//'authenticate' => ' *** YOU DO NOT NEED TO PROVIDE THIS CONFIGURATION VARIABLE TO USE THE DEFAULT AUTHENTICATION METHOD FOR THE DRIVER YOU'RE USING (I.E.: MYSQL) *** '
			//'authenticate' => 'your_function_name_here',
			//'authenticate' => array( 'YourClassName', 'YourStaticMethod' ),
			'deny_concurrent_logins' => false,
			'deny_reallyfast_logins' => false,
		);		// See the comments in the QuickBooks/Server/Handlers.php file
		
		$driver_options = array(		// See the comments in the QuickBooks/Driver/<YOUR DRIVER HERE>.php file ( i.e. 'Mysql.php', etc. )
			//'max_log_history' => 1024,	// Limit the number of quickbooks_log entries to 1024
			//'max_queue_history' => 64, 	// Limit the number of *successfully processed* quickbooks_queue entries to 64
		);
			
		$callback_options = array();
		
		// * MAKE SURE YOU CHANGE THE DATABASE CONNECTION STRING BELOW TO A VALID MYSQL USERNAME/PASSWORD/HOSTNAME *
		//
		// This assumes that:
		//	- You are connecting to MySQL with the username 'root'
		//	- You are connecting to MySQL with an empty password
		//	- Your MySQL server is located on the same machine as the script ( i.e.: 'localhost', if it were on another machine, you might use 'other-machines-hostname.com', or '192.168.1.5', or ... etc. )
		//	- Your MySQL database name containing the QuickBooks tables is named 'quickbooks' (if the tables don't exist, they'll be created for you)
		//$dsn = 'mysql://root:root@localhost/quickbooks_server';
		//$dsn = 'mysql://root:password@localhost/your_database';				// Connect to a MySQL database with user 'root' and password 'password'
		//$dsn = 'mssql://kpalmer:password@192.168.18.128/your_database';		 	// Connect to MS SQL Server database
		$dsn = $this->__dataSource();
		
		if (!QuickBooks_Utilities::initialized($dsn))
		{
			// Initialize creates the neccessary database schema for queueing up requests and logging
			QuickBooks_Utilities::initialize($dsn);
			
			// This creates a username and password which is used by the Web Connector to authenticate
			QuickBooks_Utilities::createUser($dsn, $user, $pass);
		} else {
			QuickBooks_Utilities::createUser($dsn, $user, $pass);
		}
		
		// Create a new server and tell it to handle the requests
		// __construct($dsn_or_conn, $map, $errmap = array(), $hooks = array(), $log_level = QUICKBOOKS_LOG_NORMAL, $soap = QUICKBOOKS_SOAPSERVER_PHP, $wsdl = QUICKBOOKS_WSDL, $soap_options = array(), $handler_options = array(), $driver_options = array(), $callback_options = array()
		$Server = new QuickBooks_WebConnector_Server($dsn, $map, $errmap, $hooks, $log_level, $soapserver, QUICKBOOKS_WSDL, $soap_options, $handler_options, $driver_options, $callback_options);
		$response = $Server->handle(true, true);	
		return $response;
	}

	public function queueCustomerAdd($foreign_key) {
		$data['id'] = null;
		$data['model'] = 'Customer';
		$data['foreign_key'] = $foreign_key;
		$data['action'] = 'add';
		if($this->save($data)) {
			$this->enqueue('QUICKBOOKS_ADD_CUSTOMER', $foreign_key);
			
		}
	}

	public function queueCustomerModify($foreign_key) {
		$data['id'] = null;
		$data['model'] = 'Customer';
		$data['foreign_key'] = $foreign_key;
		$data['action'] = 'modify';
		if($this->save($data)) {
			$this->enqueue('QUICKBOOKS_MOD_CUSTOMER', $foreign_key);
		}
	}

	public function queueInvoiceAdd($foreign_key) {
		$data['id'] = null;
		$data['model'] = 'Invoice';
		$data['foreign_key'] = $foreign_key;
		$data['action'] = 'add';
		if($this->save($data)) {
			$this->enqueue('QUICKBOOKS_ADD_INVOICE', $foreign_key);
		}
	}
	
	public function queueInvoiceModify($foreign_key) {
		$data['id'] = null;
		$data['model'] = 'Invoice';
		$data['foreign_key'] = $foreign_key;
		$data['action'] = 'modify';
		if($this->save($data)) {
			$this->enqueue('QUICKBOOKS_MOD_INVOICE', $foreign_key);
		}
	}
	
	public function enqueue($method = null, $id = null) {
		$this->__requireFramwork();
		
		//$dsn = 'mysql://creationsitedb:5#CRJ39kz4NivUEX3b)]Vd3]@my360e.com/creationsitebusiness360db';
		$dsn = $this->__dataSource();
		$Queue = new QuickBooks_WebConnector_Queue($dsn);
		
		// Queue it up!
		switch ($method) {
			case 'QUICKBOOKS_ADD_CUSTOMER' :
				$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $id, 100);
				break;
				
			case 'QUICKBOOKS_MOD_CUSTOMER' :
				$Queue->enqueue(QUICKBOOKS_MOD_CUSTOMER, $id, 50);
				break;
				
			case 'QUICKBOOKS_ADD_INVOICE' :
				$Queue->enqueue(QUICKBOOKS_ADD_INVOICE, $id, 10);
				break;
				
			case 'QUICKBOOKS_MOD_INVOICE' :
				$Queue->enqueue(QUICKBOOKS_MOD_INVOICE, $id, 0);
				break;
		}
	}
	
	private function __dataSource() {
		App::uses('ConnectionManager', 'Model');
		$dataSource = ConnectionManager::getDataSource('default');
		$login = $dataSource->config['login'];
		$password = $dataSource->config['password'];
		$host = $dataSource->config['host'];
		$database = $dataSource->config['database'];
		
		//$dsn = 'mysql://creationsitedb:5#CRJ39kz4NivUEX3b)]Vd3]@my360e.com/creationsitebusiness360db';
		//$dsn = 'mysql://root:password@localhost/your_database';				// Connect to a MySQL database with user 'root' and password 'password'
		
		$dsn = 'mysql://' . $login . ':' . $password . '@' . $host . '/' . $database;
		return $dsn;
	}
	
	private function __requireFramwork() {
		require_once APP.'Vendor'.DS.'QuickBooks'.DS.'QuickBooks.php';
		/*
		switch (Configure::read('Environment.platform')) {
			case 'development' :
				require_once APP.'Vendor'.DS.'QuickBooks'.DS.'QuickBooks.php';
				break;
				
			default:
				App::import('Vendor', 'QuickBooks', array('file' => Configure::read('Quickbooks.sdk')));
		}
		*/
	}
	
	/**
	 * Generate a qbXML response to add a particular customer to QuickBooks
	 *
	 * So, you've queued up a QUICKBOOKS_ADD_CUSTOMER request with the
	 * Quickbooks_Queue class like this:
	 * 	$Queue = new QuickBooks_Queue('mysql://user:pass@host/database');
	 * 	$Queue->enqueue(QUICKBOOKS_ADD_CUSTOMER, $primary_key_of_your_customer);
	 *
	 * And you're registered a request and a response function with your $map
	 * parameter like this:
	 * 	$map = array(
	 * 		QUICKBOOKS_ADD_CUSTOMER => array( 'quickbooks_customer_add_request', 'quickbooks_customer_add_response' ),
	 * 	 );
	 *
	 * This means that every time QuickBooks tries to process a
	 * QUICKBOOKS_ADD_CUSTOMER action, it will call the
	 * 'quickbooks_customer_add_request' function, expecting that function to
	 * generate a valid qbXML request which can be processed. So, this function
	 * will generate a qbXML CustomerAddRq which tells QuickBooks to add a
	 * customer.
	 */
	
	function quickbooksCustomerAddRequest($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale) {
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
		return $qbxml;
	}
	
	/**
	 * Receive a response from QuickBooks
	 * Our response function will in turn receive a qbXML response from QuickBooks
	 * which contains all of the data stored for that customer within QuickBooks.
	 *
	 * @param string $requestID					The requestID you passed to QuickBooks previously
	 * @param string $action						The action that was performed (CustomerAdd in this case)
	 * @param mixed $ID							The unique identifier of the record
	 * @param array $extra
	 * @param string $err						An error message, assign a valid to $err if you want to report an error
	 * @param integer $last_action_time			A unix timestamp (seconds) indicating when the last action of this type was dequeued (i.e.: for CustomerAdd, the last time a customer was added, for CustomerQuery, the last time a CustomerQuery ran, etc.)
	 * @param integer $last_actionident_time	A unix timestamp (seconds) indicating when the combination of this action and ident was dequeued (i.e.: when the last time a CustomerQuery with ident of get-new-customers was dequeued)
	 * @param string $xml						The complete qbXML response
	 * @param array $idents						An array of identifiers that are contained in the qbXML response
	 * @return void
	 */
	function quickbooks_customer_add_response($requestID, $user, $action, $ID, $extra, $err, $last_action_time, $last_actionident_time, $xml, $idents) {
		
	}
	
	function quickbooks_customer_mod_request($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $version, $locale) {
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
		return $qbxml;
	}
	
	function quickbooks_customer_mod_response($requestID, $user, $action, $ID, $extra, &$err, $last_action_time, $last_actionident_time, $xml, $idents) {
		$list_id = $idents['ListID'];
		$this->Customer->id = $ID;
		$this->Customer->saveField(Configure::read('Quickbooks.foreign_key.customer'), $list_id);
	}
	
	function quickbooks_customer_error_catchall($requestID, $user, $action, $ID, $extra, &$err, $xml, $errnum, $errmsg) {
		$this->Customer->id = $ID;
		$this->Customer->saveField('quickbooks_errnum', mysql_real_escape_string($errnum));
		$this->Customer->saveField('quickbooks_errmsg', mysql_real_escape_string($errmsg));
	}
} ?>