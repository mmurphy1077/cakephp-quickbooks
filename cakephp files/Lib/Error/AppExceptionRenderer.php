<?php
App::uses('ExceptionRenderer', 'Error');

class AppExceptionRenderer extends ExceptionRenderer {

    /**
     * Overridden, to always use a bare controller.
     * 
     * @see http://stackoverflow.com/questions/3899130/cakephp-how-to-make-error-pages-have-its-own-layouts
     * 
     * @param Exception $exception the exception to get a controller for
     * @return Controller
     */
	protected function _getController($exception) {
		if (!$request = Router::getRequest(true)) {
			$request = new CakeRequest();
		}
		$response = new CakeResponse(array('charset' => Configure::read('App.encoding')));
		$controller = new Controller($request, $response);
		$controller->viewPath = 'Errors';
		$controller->layout = 'error';
		return $controller;
	}
	
	/*
	protected function _outputMessage($template) {
		$this->controller->layout = 'error';
		parent::_outputMessage($template);
	}
	*/
	
}