<?php

namespace Plugins;
use \Verbier\Application;

Application::registerPlugin('Flash', function($app) {
	$app->flash = new FlashMessages();
	
	$app->helper('flash', function($name, $message = null) use ($app) {
		if ($message === null) {
			return $app->flash->get($name);
		}
		$app->flash->set($name, $message);
	});
});

class FlashMessages {
	
	protected $messages = array();

	public function __construct() {
		session_start();
		if (!empty($_SESSION['flashMessages'])) {
			$this->messages = $_SESSION['flashMessages'];
		}
		$_SESSION['flashMessages'] = array();
	}
	
	public function set($name, $message) {
		$_SESSION['flashMessages'][$name] = $message;
	}
	
	public function get($name) {
		return isset($this->messages[$name]) ? $this->messages[$name] : NULL;
	}
}