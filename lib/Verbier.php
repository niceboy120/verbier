<?php

spl_autoload_register(function($className) {
	include str_replace('\\', '/', $className) . '.php';
});

function get($pattern, $callback) {
	R($pattern)->on('GET')->to($callback);
}

function post($pattern, $callback) {
	R($pattern)->on('POST')->to($callback);
}

function put($pattern, $callback) {
	R($pattern)->on('PUT')->to($callback);
}

function delete($pattern, $callback) {
	R($pattern)->on('DELETE')->to($callback);
}

function R($pattern) {
	return new \Verbier\Route($pattern);
}

function run() {
	$request  = new \Verbier\Request();
	$response = new \Verbier\Response();
	
	$requestHandler = new \Verbier\RequestHandler();
	$requestHandler->handleRequest($request, $response);
}