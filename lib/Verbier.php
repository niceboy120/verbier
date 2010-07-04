<?php

spl_autoload_register(function($className) {
	include str_replace('\\', '/', $className) . '.php';
});

/**
 * Flip the option to TRUE
 *
 * @param string $key 
 * @return void
 */
function enable($key) {
	set($key, true);
}

/**
 * Flip the option to FALSE
 *
 * @param string $key 
 * @return void
 */
function disable($key) {
	set($key, false);
}

/**
 * Set an option
 *
 * @param string $key 
 * @param string $value 
 * @return void
 */
function set($key, $value) {
	\Verbier\Config::$$key = $value;
}

/**
 * Get an option
 *
 * @param string $key 
 * @return mixed
 */
function option($key) {
	return \Verbier\Config::$$key;
}

/**
 * Add configuration options for the given environment
 * The closure won't be invoked unless the environment is correct
 *
 * @param string $env 
 * @param Closure $closure 
 * @return void
 */
function configure($env, \Closure $closure) {
	if ($env == getenv('VERBIER_ENV') || $env == '*') {
		$closure();
	}
}

function get($pattern, $callback) {
	route($pattern, 'GET', $callback);
}

function post($pattern, $callback) {
	route($pattern, 'POST', $callback);
}

function put($pattern, $callback) {
	route($pattern, 'PUT', $callback);
}

function delete($pattern, $callback) {
	route($pattern, 'DELETE', $callback);
}

function route($pattern, $method, $callback) {
 	$route = new \Verbier\Route($pattern, $method, $callback);
	\Verbier\Router::getInstance()->addRoute($route);
}

function run() {
	$request  = new \Verbier\Request();
	$response = new \Verbier\Response();
	
	$requestHandler = new \Verbier\RequestHandler();
	$requestHandler->handleRequest($request, $response);
}