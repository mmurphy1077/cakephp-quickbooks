<?php
class DATABASE_CONFIG {

	public $default;
	/**
	 * Defines the database configuration for each environment
	 * 		specifically native to this application
	 * 
	 * @var array
	 */
	public $app = array(
		'development' => array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			//'host' => '127.0.0.1',
			'host' => 'localhost',
			'login' => 'root',
			'password' => 'root',
			//'database' => 'squires-business360',
			'database' => '360_quickbooks_integration',
		),
		'stage' => array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			'host' => 'localhost',
			'login' => 'squiresdb',
			'password' => '#J&3)]]VXvC?;u9kz4Niz33FCU53bd',
			'database' => 'stage_squires_electric',
		),
		'production' => array(
			'datasource' => 'Database/Mysql',
			'persistent' => false,
			//'host' => 'localhost',
			'host' => 'my360e.com',
			'login' => 'creationsitedb',
			'password' => '5#CRJ39kz4NivUEX3b)]Vd3]',
			'database' => 'creationsitebusiness360db',
		),
	);
	/**
	 * Define additional database configurations for external
	 * 		application connectivity here
	 */
	
	function __construct($environment = array()) {
		if (empty($environment)) {
			$this->default = $this->app[Configure::read('Environment.platform')];
		} else {
			$this->default = $this->app[$environment['platform']];
		}
	}
}