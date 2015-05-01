<?php
/**
 * Define constants
 */
if (!defined('APP')) {
	define('APP', dirname(dirname(__FILE__)));	
}

if (!defined('CAKE_CORE')) {
	define('CAKE_CORE', '/usr/lib/cakephp/2.3-latest');
}

/**
 * Load configuration file(s)
 */
require APP.'/Config/config.php';
require APP.'/Config/database.php';

/**
 * Environment detection
 */
$Environments = $config['Environments'];
$environment = array();
foreach ($Environments as $Environment) {
	if (!empty($argv[1])) { 
		if ($argv[1] == $Environment['platform']) {
			$environment = $Environment;
		}
	} else {
		die(date('Y-m-d H:i:s').' Environment not specified.'."\r\n");
	}
}

/**
 * Determine db configuration object
 */
$dbConfig = new DATABASE_CONFIG($environment);


/**
 * Database connection method
 * 
 * @param dbConfig object expecting properties
 * 		- default (array) with keys:
 * 				- host
 * 				- login
 * 				- password
 * 				- database
 */
function dbConnect($dbConfig) {
	if (!@mysql_connect($dbConfig->default['host'], $dbConfig->default['login'], $dbConfig->default['password'])) {
		return false;
	} else {
		mysql_select_db($dbConfig->default['database']);
		return true;
	}
}

/**
 * Require db connection or die
 */
if (!dbConnect($dbConfig)) {
	die(date('Y-m-d H:i:s').' Cannot establish database connection.'."\r\n");
}
?>
