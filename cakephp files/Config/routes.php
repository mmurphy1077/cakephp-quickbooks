<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different urls to chosen controllers and their actions (functions).
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/**
 * Here, we are connecting '/' (base path) to controller called 'Pages',
 * its action called 'display', and we pass a param to select the view file
 * to use (in this case, /app/View/Pages/home.ctp)...
 */
	Router::connect('/', array('controller' => 'users', 'action' => 'login'));
	
/**
 * PagesController
 */
	Router::connect('/not-authorized', array('controller' => 'pages', 'action' => 'display', 'not_authorized'));
	Router::connect('/pages/*', array('controller' => 'pages', 'action' => 'display'));
	
/**
 * AclControlledsController
 * AclPermissionsController
 * AclRequestersController
 */
	Router::connect('/creationsite/acl/aros/:action/*', array('controller' => 'acl_requesters'));
	Router::connect('/creationsite/acl/acos/:action/*', array('controller' => 'acl_controlleds'));
	Router::connect('/creationsite/acl/:action/*', array('controller' => 'acl_permissions', 'action' => 'index'));
	Router::connect('/creationsite/acl/*', array('controller' => 'acl_permissions', 'action' => 'index'));
	Router::connect('/creationsite/*', array('controller' => 'acl_permissions', 'action' => 'dashboard'));

/**
 * ContactsController
 */
	Router::connect('/sales/leads/index/*', array('controller' => 'contacts', 'action' => 'index_leads'));
	Router::connect('/sales/leads/add/*', array('controller' => 'contacts', 'action' => 'add_lead'));
	Router::connect('/sales/leads/edit/*', array('controller' => 'contacts', 'action' => 'edit_lead'));
	Router::connect('/sales/leads/delete/*', array('controller' => 'contacts', 'action' => 'delete_lead'));
	Router::connect('/sales', array('controller' => 'contacts', 'action' => 'index_sales'));
	Router::connect('/contacts/:action', array('controller' => 'contacts'));
	
/**
 * UsersController
 */
	Router::connect('/login', array('controller' => 'users', 'action' => 'login'));
	Router::connect('/logout', array('controller' => 'users', 'action' => 'logout'));
	Router::connect('/my-account', array('controller' => 'users', 'action' => 'view_owner'));
	Router::connect('/reset-password', array('controller' => 'users', 'action' => 'reset_password'));

/**
 * Load all plugin routes. See the CakePlugin documentation on
 * how to customize the loading of plugin routes.
 */
	CakePlugin::routes();

/**
 * Load the CakePHP default routes. Only remove this if you do not want to use
 * the built-in default routes.
 */
	require CAKE . 'Config' . DS . 'routes.php';
