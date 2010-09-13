<?php

namespace Plugins;
use Verbier\Application;

Application::registerPlugin('Hello', function($app) {
	$app->helper('hello', function() use($app) {
		return 'Hello World';
	});
});