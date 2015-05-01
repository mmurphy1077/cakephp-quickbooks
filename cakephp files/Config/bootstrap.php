<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', array('engine' => 'File'));

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build(array(
 *     'Model'                     => array('/path/to/models', '/next/path/to/models'),
 *     'Model/Behavior'            => array('/path/to/behaviors', '/next/path/to/behaviors'),
 *     'Model/Datasource'          => array('/path/to/datasources', '/next/path/to/datasources'),
 *     'Model/Datasource/Database' => array('/path/to/databases', '/next/path/to/database'),
 *     'Model/Datasource/Session'  => array('/path/to/sessions', '/next/path/to/sessions'),
 *     'Controller'                => array('/path/to/controllers', '/next/path/to/controllers'),
 *     'Controller/Component'      => array('/path/to/components', '/next/path/to/components'),
 *     'Controller/Component/Auth' => array('/path/to/auths', '/next/path/to/auths'),
 *     'Controller/Component/Acl'  => array('/path/to/acls', '/next/path/to/acls'),
 *     'View'                      => array('/path/to/views', '/next/path/to/views'),
 *     'View/Helper'               => array('/path/to/helpers', '/next/path/to/helpers'),
 *     'Console'                   => array('/path/to/consoles', '/next/path/to/consoles'),
 *     'Console/Command'           => array('/path/to/commands', '/next/path/to/commands'),
 *     'Console/Command/Task'      => array('/path/to/tasks', '/next/path/to/tasks'),
 *     'Lib'                       => array('/path/to/libs', '/next/path/to/libs'),
 *     'Locale'                    => array('/path/to/locales', '/next/path/to/locales'),
 *     'Vendor'                    => array('/path/to/vendors', '/next/path/to/vendors'),
 *     'Plugin'                    => array('/path/to/plugins', '/next/path/to/plugins'),
 * ));
 *
 */

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 * Inflector::rules('plural', array('rules' => array(), 'irregular' => array(), 'uninflected' => array()));
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */
CakePlugin::load('DebugKit');

/**
 * You can attach event listeners to the request lifecyle as Dispatcher Filter . By Default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', array(
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		array('callable' => $aFunction, 'on' => 'before', 'priority' => 9), // A valid PHP callback type to be called on beforeDispatch
 *		array('callable' => $anotherMethod, 'on' => 'after'), // A valid PHP callback type to be called on afterDispatch
 *
 * ));
 */
Configure::write('Dispatcher.filters', array(
	'AssetDispatcher',
	'CacheDispatcher'
));

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', array(
	'engine' => 'FileLog',
	'types' => array('notice', 'info', 'debug'),
	'file' => 'debug',
));
CakeLog::config('error', array(
	'engine' => 'FileLog',
	'types' => array('warning', 'error', 'critical', 'alert', 'emergency'),
	'file' => 'error',
));

/**
 * Load custom configuration settings
 * Auto-detect application environment based on server hostname
 * Write environment settings to Configure class
 */
Configure::load('config');
$environments = Configure::read('Environments');
if (!empty($_SERVER['HTTP_HOST'])) {
	// Dynamically configure environment if running through web server
	foreach ($environments as $environment) {
		if ($_SERVER['HTTP_HOST'] == $environment['host']) {			
			Configure::write('Environment', $environment);
			Configure::write('Email', Set::merge($environment['email'], Configure::read('Email')));
		}
	}
} else {
	// Configure environment based on -environment arg passed via shell
	$env = null;
	if (!empty($_SERVER['argv'])) {
		// Set $env by looking for -environment argument
		foreach ($_SERVER['argv'] as $i => $argv) {
			if ($argv == '-environment') {
				// Set object property upon match
				$env = $_SERVER['argv'][++$i];
				break;
			}
		}
	}
	// Look for matching environment in Configure
	foreach ($environments as $environment) {
		if ($env == $environment['platform']) {
			Configure::write('Environment', $environment);
			Configure::write('Email', Set::merge($environment['email'], Configure::read('Email')));
			break;
		}
	}
	$env = Configure::read('Environment');
	if (empty($env)) {
		// Error & die if no environment match found
		echo __('Missing/Invalid Required Argument', true).":\n".__('-environment ', true).join('|', Set::extract('{n}.platform', $environments))."\n\n";
		die();
	}
}

/**
 * Define application-specific constants
 */
define('METHOD_SEPARATOR', '::');
define('NEWLINE', "\r\n");
define('NL', NEWLINE);
define('TAB', "\t");
define('CELL_PAD', "&nbsp;&nbsp;&nbsp;&nbsp;");

define('ENABLE_SSL', 1);
define('LABOR_REQUIRE_APPPROVAL', 0);
define('APPRENTACE_FLOAT', 1);
define('LEAD_BECOMES_CUSTOMER_WITH_QUOTE', 1);
define('DEFAULT_GROUP_ID', 2);
define('PROFILE_IMAGE_DEFAULT', '__default.png');

define('ACL_ACO_TYPE_ADMIN', 'Admin');
define('ACL_ACO_TYPE_APPLICATION', 'Application');
define('ACL_ACO_TYPE_MEMBERSHIP', 'Membership');
define('ACL_ACO_TYPE_PUBLIC', 'Public');
define('ACL_ACO_TYPE_RESTRICTED', 'Restricted');
define('ACL_ACO_TYPE_BLACKHOLE', 'Blackhole');
define('ACL_ARO_GUEST', 'Guest');
define('ACL_PERMISSION_ACCESS_DENIED', -1);
define('ACL_PERMISSION_ACCESS_ALLOWED', 1);

define('ACCOUNT_STATUS_DELETE', -100);
define('ACCOUNT_TYPE_VENDOR', 42);

define('ADDRESS_TYPE_ID_COMPANY', 3);
define('ADDRESS_TYPE_ID_MAIN', 20);
define('ADDRESS_TYPE_ID_BILLING', 2);
define('ADDRESS_TYPE_ID_JOBSITE', 1);
define('ADDRESS_TYPE_ID_SHIPPING', 96);

define('ALERT_JOB_MATERIAL_SUBMIT_DAYS', 1);
define('ALERT_JOB_MATERIAL_APPROVE_DAYS', 1);
define('ALERT_JOB_TIME_SUBMIT_DAYS', 1);
define('ALERT_JOB_TIME_APPROVE_DAYS', 1);
define('ALERT_JOB_REQUEST_DATE_DAYS', 2);
define('ALERT_INVOICE_SUBMITTED_TO_APPROVED_DAYS', 3);
define('ALERT_INVOICE_APPROVED_TO_BILLEDED_DAYS', 3);

define('ORDER_REQUEST_DATE_MINIMUM_DAYS', 2);
define('ORDER_REQUEST_DATE_COMPLETE_MINIMUM_DAYS', 2);

define('CONTACT_TYPE_ID_SERVICE', 32);

define('CALCULATION_REQUIREMENT_TYPE_IMPERIAL_LINEAR_ID', 48);
define('CALCULATION_REQUIREMENT_TYPE_IMPERIAL_VOLUME_ID', null);
define('CALCULATION_REQUIREMENT_TYPE_METRIC_LINEAR_ID', null);
define('CALCULATION_REQUIREMENT_TYPE_METRIC_VOLUME_ID', null);
define('CALCULATION_REQUIREMENT_TYPE_HOURLY_ID', 49);

define('CLOUD_STATUS_EXISTS_ID', 1);
define('CLOUD_STATUS_NOT_FOUND_ID', 2);

define('CONTACT_STATUS_UNSAVED', -10);
define('CONTACT_STATUS_PROTECTED', -1);
define('CONTACT_STATUS_INACTIVE', 0);
define('CONTACT_STATUS_ACTIVE', 1);
define('CONTACT_ACTIVE_STATUS_NO_CONTACT', 1);
define('CONTACT_ACTIVE_STATUS_CONTACT_MADE', 2);
define('CONTACT_ACTIVE_STATUS_MEETING_SCHEDULED', 3);
define('CONTACT_ACTIVE_STATUS_QUOTE_REQUESTED', 4);
define('CONTACT_ACTIVE_STATUS_PENDING', 5);

define('DISTRIBUTION_ACCOUNT_REP_REQUEST', 1);

define('FORMAT_FOR_DROPDOWN', 1);
define('FORMAT_FOR_SQL', 0);

define('GROUP_EXECUTIVES_ID', 2);
define('GROUP_DEVELOPER_ID', 1);
define('GROUP_ADMINISTRATORS_ID', 2);
define('GROUP_EMPLOYEES_ID', 3);
define('GROUP_APPRENTICE_ID', 4);

define('HOURLY_RATE', 100);
define('HOURLY_EXPENSE_RATE', 40);

define('INVOICE_STATUS_INACTIVE', -5);
define('INVOICE_STATUS_PENDING', 1);
define('INVOICE_STATUS_WAITING_PO', 8);
define('INVOICE_STATUS_SUBMITTED', 10);
define('INVOICE_STATUS_APPROVED', 20);
define('INVOICE_STATUS_BILLED', 30);
define('INVOICE_STATUS_PAID', 100);

define('QUOTE_LINE_ITEM_TYPE_STANDARD', 1);
define('QUOTE_LINE_ITEM_TYPE_CUSTOM', 2);
define('QUOTE_LINE_ITEM_LABEL', 'Enter Unit Name');
define('QUOTE_LINE_ITEM_QTY_LABEL', 'Qty');

define('QUOTE_LINE_ITEM_TYPE_ID_SERVICE', 33);
define('ORDER_LINE_ITEM_TYPE_ID_SERVICE', 33);

define('QUOTE_OPTION_TYPE_DOLLAR_ID', 51);
define('QUOTE_OPTION_TYPE_PERCENTAGE_ID', 52);

define('QUOTE_REQUEST_DATE_MINIMUM_DAYS', 1);
define('QUOTE_REQUEST_DATE_COMPLETE_MINIMUM_DAYS', 7);

define('QUOTE_STATUS_DELETE', -100);
define('QUOTE_STATUS_UNSAVED', -10);
define('QUOTE_STATUS_INACTIVE', -2);
define('QUOTE_STATUS_UNSUBMITTED', 0);
define('QUOTE_STATUS_SUBMITTED', 1);
define('QUOTE_STATUS_DOUBTFUL', 2);
define('QUOTE_STATUS_LOST', 3);
define('QUOTE_STATUS_SOLD', 100);

define('QUOTE_TASK_TYPE_TASK', 0);
define('QUOTE_TASK_TYPE_APPROVAL', 1);

define('QUOTE_TASK_STATUS_CONVERTED_TO_JOB_TASK', -5);
define('QUOTE_TASK_STATUS_INACTIVE', 0);
define('QUOTE_TASK_STATUS_OPEN', 1);
define('QUOTE_TASK_STATUS_IN_PROCESS', 10);
define('QUOTE_TASK_STATUS_APPROVED', 100);
define('QUOTE_TASK_STATUS_COMPLETE', 100);

define('QUOTE_TYPE_ID_GENERAL_SERVICE', 16);
define('ORDER_TYPE_ID_GENERAL_SERVICE', 16);

define('ORDER_JOB_INFO', 'job-info');
define('ORDER_ORDER_ITEMS', 'order-items');
define('ORDER_CHANGE_REQUEST', 'change-order');

define('ORDER_REQUIREMEN_OUTSTANDING', 1);
define('ORDER_REQUIREMENT_COMPLETED', 2);

define('ORDER_STATUS_DELETE', -100);
define('ORDER_STATUS_UNSAVED', -10);
define('ORDER_STATUS_CANCELLED', -2);
define('ORDER_STATUS_HOLD', -1);
define('ORDER_STATUS_INCOMPLETE', 0);
define('ORDER_STATUS_NEW', 1);
define('ORDER_STATUS_IN_PROCESS', 30);
define('ORDER_STATUS_ROUGH_READY', 40);
define('ORDER_STATUS_READY_TRIM', 45);
define('ORDER_STATUS_WORK_COMPLETE', 50);
define('ORDER_STATUS_COMPLETE', 100);

define('ORDER_TASK_STATUS_INACTIVE', 0);
define('ORDER_TASK_STATUS_OPEN', 1);
define('ORDER_TASK_STATUS_IN_PROCESS', 10);
define('ORDER_TASK_STATUS_APPROVED', 100);
define('ORDER_TASK_STATUS_COMPLETE', 100);

define('ORDER_MATERIAL_STATUS_DELETE', -100);
define('ORDER_MATERIAL_STATUS_UNSAVED', -10);
define('ORDER_MATERIAL_STATUS_INPROCESS', 1);
define('ORDER_MATERIAL_STATUS_SUBMIT', 10);
define('ORDER_MATERIAL_STATUS_UNBILLABLE', 20);
define('ORDER_MATERIAL_STATUS_APPROVED', 50);
//define('ORDER_MATERIAL_STATUS_INVOICED', 100);

define('ORDER_TIME_STATUS_DELETE', -100);
define('ORDER_TIME_STATUS_UNSAVED', -10);
define('ORDER_TIME_STATUS_REJECT', -1);
define('ORDER_TIME_STATUS_INPROCESS', 1);
define('ORDER_TIME_STATUS_SUBMIT', 10);
define('ORDER_TIME_STATUS_UNBILLABLE', 20);
define('ORDER_TIME_STATUS_APPROVE', 30);
//define('ORDER_TIME_STATUS_INVOICED', 100);

define('ORDER_WIZARD_CUSTOMER', 1);
define('ORDER_WIZARD_SERVICE_ADDRESS', 2);
define('ORDER_WIZARD_LINE_ITEM', 3);
define('ORDER_WIZARD_REQUIREMENTS', 4);
define('ORDER_WIZARD_SCHDULE', 5);
define('ORDER_WIZARD_VIEW_ORDER', 6);

define('ORDER_ITEM_STATUS_OPEN', 1);
define('ORDER_ITEM_STATUS_COMPLETE', 100);

define('PURCHASE_ORDER_SHIP_TO_JOB_SITE', 0);
define('PURCHASE_ORDER_SHIP_TO_SEC_SIGNS', 1);
define('PURCHASE_ORDER_SHIP_TO_OTHER', 2);

define('PURCHASE_ORDER_STATUS_DELETE', -100);
define('PURCHASE_ORDER_STATUS_UNSAVED', -10);
define('PURCHASE_ORDER_STATUS_INACTIVE', -2);
define('PURCHASE_ORDER_STATUS_NEW', 1);
define('PURCHASE_ORDER_STATUS_SUBMIT_FOR_APPROVAL', 10);
define('PURCHASE_ORDER_STATUS_APPROVED', 30);
define('PURCHASE_ORDER_STATUS_INPROGRESS', 50);
define('PURCHASE_ORDER_STATUS_COMPLETE', 100);

define('ORDER_PRODUCTION_PURCHASING', 'purchasing');
define('ORDER_PRODUCTION_REQUIREMENT', 'requirement');
define('ORDER_PRODUCTION_SCHEDULE', 'schedule');
define('ORDER_PRODUCTION_LABOR', 'labor');
define('ORDER_PRODUCTION_MATERIAL', 'materials');
define('ORDER_PRODUCTION_PURCHASE', 'purchases');
define('ORDER_PRODUCTION_INVOICE', 'invoice');

define('STATUS_ACTIVE', 1);
define('STATUS_INACTIVE', 0);
define('STATUS_PROTECTED', -1);

define('SCHEDULE_WORKDAY_START', 700);
define('SCHEDULE_WORKDAY_END', 1600);
define('SCHEDULE_TYPE_LABOR', 'labor');
define('SCHEDULE_TYPE_DESIGN', 'design');
define('SCHEDULE_TYPE_INSTALLATION', 'installation');

define('USER_STATUS_UNSAVED', -10);
define('USER_STATUS_PROTECTED', -1);
define('USER_STATUS_INACTIVE', 0);
define('USER_STATUS_ACTIVE', 1);
define('USER_STATUS_UNVERIFIED', 2);