<?php

use Verbier\Application;

Application::registerPlugin('Auth', function($app) {
	
	$app->helper('ensure_user_is_logged_in', function() use($app) {
		if ($app->request->env('PHP_AUTH_USER') === null) {
			authenticate();
		} else {
			if ($app->request->env('PHP_AUTH_USER') !== ADMIN_EMAIL || $app->request->env('PHP_AUTH_PW') !== ADMIN_PASSWORD) {
				authenticate();
			}
		}
		return true;
	});
	
	$app->helper('authenticate', function() use ($app) {
		$app->response->setHeader('WWW-Authenticate: Basic realm="Verbier Auth"');
		$app->response->setHeader('HTTP/1.1 401 Unauthorized');
		$app->response->setContent('Authentication required');
		$app->response->finish();
	});
});