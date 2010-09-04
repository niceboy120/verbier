<?php

spl_autoload_register(function($className) {
	include str_replace(array('\\', '_'), '/', $className) . '.php';
});

session_start();
\Verbier\FlashMessage::init();

$verbier = new \Verbier\Application();


/**
 * Flip the option to TRUE
 *
 * @param string $key 
 * @return void
 */
function enable($key) {
	global $verbier;
	$verbier->enable($key);
}

/**
 * Flip the option to FALSE
 *
 * @param string $key 
 * @return void
 */
function disable($key) {
	global $verbier;
	$verbier->disable($key);
}

/**
 * Set an option
 *
 * @param string $key 
 * @param string $value 
 * @return void
 */
function set($key, $value) {
	global $verbier;
	$verbier->set($key, $value);
}

function content_type($type) {
	global $verbier;
	$verbier->response->setContentType($type);
}

function render($template, $variables = array()) {
	global $verbier;
	return $verbier->render($template, $variables);
}

function redirect($location, $options = array()) {
	global $verbier;
	return $verbier->redirect($location, $options);
}

function before($filter) {
	global $verbier;
	$verbier->before($filter);
}

function after($filter) {
	global $verbier;
	$verbier->after($filter);
}

function flash($name, $value = null) {
	global $verbier;
	return $verbier->flash($name, $value);
}

function configure($env, \Closure $closure) {
	if ($env == getenv('VERBIER_ENV') || $env == '*') {
		$closure();
	}
}

function get($pattern, $handler) {
	global $verbier;
	$verbier->get($pattern, $handler);
}

function post($pattern, $handler) {
	global $verbier;
	$verbier->post($pattern, $handler);
}

function put($pattern, $handler) {
	global $verbier;
	$verbier->put($pattern, $handler);
}

function delete($pattern, $handler) {
	global $verbier;
	$verbier->delete($pattern, $handler);
}

function run() {
	global $verbier;
	$verbier->run();
}