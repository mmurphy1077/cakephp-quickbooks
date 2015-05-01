<?php
$config = array(

	'Application' => array(
		'name' => 'Business 360',
		'cookieName' => 'Business360',
		'tag' => '/\[cs\:.*\]/',
	),
	
	'Environments' => array(
		0 => array(
			'platform' => 'development',
			'host' => 'localhost',
			'ssl_port' => 443,
			'debug' => 2,
			'email' => array(
				'system' => 'webmaster@creationsite.com',
				'billing' => 'webmaster@creationsite.com',
				'csr' => 'webmaster@creationsite.com',
				'webmaster' => 'webmaster@creationsite.com',
			),
			'path' => array(
				'cake_core' => '/usr/lib/cakephp/2.3-latest',
			),
		),
		2 => array(
			'platform' => 'production',
			'host' => 'quickbooks.my360e.com',
			'ssl_port' => 443,
			'debug' => 2,
			'email' => array(
				'system' => 'webmaster@creationsite.com',
				'billing' => 'webmaster@creationsite.com',
				'csr' => 'webmaster@creationsite.com',
				'webmaster' => 'webmaster@creationsite.com',
			),
			'path' => array(
				'cake_core' => '/usr/lib/cakephp/2.3-latest',
			),
		),
	),
	
	'Smtp' => array(
		'hostname' => 'stage.creationsite.com',
		'port' => 25,
		'ssl' => false,
		'username' => 'noreply@stage.creationsite.com',
		'password' => 'D2[Au4WGs968d>-v65Q<*;rJ>ZaAib',
	),
			
	'Quickbooks' => array(
		'sdk' => 'Quickbooks'.DS.'QuickBooks.php',
	),

	'Public' => array(
		'url' => 'creationsite.my360e.com',
		'name' => 'Your Company Name',
		'public_url' => 'www.creationsite.com',
		'title' => '360 Electric',
		'title1' => '360 Electric',
		'title2' => '',
		'meta' => array(
			'description' => null,
			'keywords' => null,
		),
		'address' => array(
			'line1' => '555 SW Main Street',
			'line2' => 'Portland, OR 97202',
			'city' => 'Portland',
			'state' => 'OR',
			'zip' => '97221',
			'phone' => '1.800.725.9897',
			'fax' => null,
		),
		'license' => array(
			'CCB' => 'CCB # 135085',
		),
		'state' => 'OR',
		'defaultTimezone' => 'America/Los_Angeles',
	),
	
	'Admin' => array(
		'name' => 'Creationsite',
		'title' => 'Business 360',
		'slug' => 'creationsite',
	),
	
	'Nomenclature' => array(
		'Account' => 'Account',
		'Address' => 'Address',
		'Amount' => 'Amount',
		'CalculationLineItem' => 'Calculator Item',
		'CalculationLineItemOption' => 'Option',
		'CalculationRequirement' => 'Data Entry Requirement',
		'CalculationSystem' => 'Calculator',
		'CalculationTaxonomy' => 'Calculator Item Type',
		'Catalog' => 'Catalog',
		'ChangeOrder' => 'Change Order',
		'Contact' => 'Lead',
		'Contract' => 'Contract',
		'Customer' => 'Customer',
		'Date' => 'Date',
		'Description' => 'Description',
		'Employee' => 'Employee',
		'Invoice' => 'Invoice',
		'Item' => 'Item',
		'Order' => 'Job',
		'Ordered' => 'Ordered',
		'OrderLineItem' => 'Line Item',
		'Outsource' => 'Subcontract',
		'OrderTime' => 'Labor Hour',
		'Material' => 'Material',
		'OrderRequirement' => 'Requirements',
		'Phone' => 'Phone',
		'Project' => 'Project',
		'ProjectNumber' => 'Project Number',
		'Proposal' => 'Proposal',
		'Quote' => 'Quote',
		'QuoteLineItem' => 'Job Item',
		'QuoteLineItemOption' => 'Quote Option',
		'QuoteJob' => 'Job Item',
		'QuoteJobLineItem' => 'Labor & Material',
		'QuoteJobRequirement' => 'Configuration Detail',
		'QuoteOption' => 'Discounting & Add-On',
		'Report' => 'Report',
		'Schedule' => 'Schedule',
		'WorkHour' => 'Time Log',
		'Rate' => 'Rate',
	),
	
	'Account' => array(
		'snap_shot' => 'Accounts::view',
		'general_info' => 'Accounts::edit',
		'address' => 'Accounts::addresses',
		'contact' => 'Accounts::contacts',
		'docs' => 'Accounts::docs',
		'pos' => 'Accounts::purchase_orders',
		'orders' => 'Accounts::orders',
		'invoice' => 'Accounts::invoices',
		'activity_log' => 'Accounts::activity_log',
		'messages' => 'Accounts::messages',
		'message-view' => 'Messages::view',
	),
	
	'Customer' => array(
		'snap_shot' => 'Customers::view',
		'general_info' => 'Customers::edit',
		'address' => 'Customers::addresses',
		'contact' => 'Customers::contacts',
		'docs' => 'Customers::docs',
		'messages' => 'Customers::messages',
		'message-view' => 'Messages::view',
		'quotes' => 'Customers::quotes',
		'orders' => 'Customers::orders',
		'invoice' => 'Customers::invoices',
		'activity_log' => 'Customers::activity_log',
	),
		
	'Quoting' => array(
		'customer_info' => 'Quotes::customer_info',
		'job_info' => 'Quotes::job_info',
		'line_item' => 'QuoteLineItems::add',
		'requirements' => 'Quotes::requirements',
		'docs' => 'Quotes::edit',
		'comm' => 'Quotes::communicate',
		'tasks' => 'QuoteTasks::index',
		'review' => 'Quotes::review',
		'tasks' => 'QuoteTasks::index',
		'activity_log' => 'Quotes::activity_log',
		'messages' => 'Quotes::messages',
		'message-view' => 'Messages::view',
		'view' => 'Quotes::view',
	),
	
	'QuoteConversion' => array(
		'step1' => 'Quotes::view_submitted',
		'step2' => 'OrderRequirements::edit',
		'step3' => array('Schedules::index','Schedules::index_week', 'Schedules::index_day', 'Schedules::index_map'),
		'complete' => 'Orders::view',
		'job_info' => 'Quotes::job_info',
		'requirements' => 'Quotes::requirements',
		'docs' => 'Quotes::edit',
		'comm' => 'Quotes::communicate',
		'status' => 'QuoteTasks::quote_tasks',
		'review' => 'Quotes::review',
	),
	
	'Order' => array(
		'snap_shot' => 'Orders::view',
		'customer_info' => 'Orders::customer_info',
		'items' => 'OrderLineItems::add',
		'schedules' => 'Orders::schedules',
		'history' => 'Orders::previous_orders_at_location_items',
		'invoices' => 'Invoices::index_order',
		'job_info' => 'Orders::job_info',
		'requirements' => 'OrderRequirements::edit',
		'production' => 'Orders::production',
		'purchasing' => 'Orders::purchasing',
		'docs' => 'Orders::docs',
		'comm' => 'Orders::communicate',
		'status' => 'OrderTasks::index',
		'activity_log' => 'Orders::activity_log',
		'messages' => 'Orders::messages',
		'message-view' => 'Messages::view',
	),
		
	'Quickbooks' => array(
		'foreign_key' => array(
			'customer' => 'quickbooks_listid',
			'invoice' => 'quickbooks_listid',
		)
	),
	
	'Paginate' => array(
		'chunk' => array(
			'limit' => 5,
		),
		'list' => array(
			'limit' => 20,
		),
		'grid' => array(
			'limit' => 100,
		),
		'long' => array(
			'limit' => 10000,
		),
	),
	
	'Pricing' => array(
		'QuoteLineItem' => array(
			'labor' => 100,
		),
	),
	
	'CalculationSystem' => array(
		'overhead' => 40,
	),
	
	'Email' => array(
		'subjectPrefix' => 'Business 360 [YOURCOMPANYNAME]: ',
		'archiveDays' => 45,
	),
		
	'Invoice' => array(
		'prefix' => '',
		'start-count' => 2000,
	),

	'Path' => array(
		'files' => 'uploads',
	),
	
	'FileSharing' => array(
		'expires' => '30',
	),
	
	'Images' => array(
		'DefaultImage' => array(
			'tiny' => array(
				'w' => 30,
				'h' => 30,
				'crop' => true,
			),
			'small' => array(
				'w' => 69,
				'h' => 69,
				'crop' => true,
			),
			'medium' => array(
				'w' => 250,
				'h' => 250,
				'crop' => true,
			),
			'large' => array(
				'w' => 800,
				'h' => 800,
				'crop' => false,
				'aspect' => true,
			),
		),
		'ProfileImage' => array(
			'tiny' => array(
				'w' => 30,
				'h' => 30,
				'crop' => true,
			),
			'small' => array(
				'w' => 69,
				'h' => 69,
				'crop' => true,
			),
			'medium' => array(
				'w' => 100,
				'h' => 100,
				'crop' => true,
			),
			'profile' => array(
				'w' => 250,
				'h' => 250,
				'crop' => true,
			),
			'large' => array(
				'w' => 666,
				'h' => 666,
				'crop' => false,
				'aspect' => true,
			),
		),
		'SignatureImage' => array(
			'thunb' => array(
					'w' => 30,
					'h' => 30,
					'crop' => true,
			),
			'normal' => array(
					'w' => 300,
					'h' => 100,
					'crop' => true,
			),
		),
		'CompanyImage' => array(
			'thumb' => array(
				'w' => 100,
				'h' => 45,
				'crop' => true,
			),
			'normal' => array(
				'w' => 300,
				'h' => 130,
				'crop' => true,
			),
		),
	),
);

$config['Images']['QuoteJobImage'] = $config['Images']['DefaultImage'];
$config['Images']['QuoteJobImage']['quotePdf'] = array(
	'w' => 700,
	'h' => 500,
	'crop' => false,
	'aspect' => true,
);
?>