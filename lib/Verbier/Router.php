<?php

namespace Verbier;

class Router {
	
	static protected $routes = array();
	
	static public function addRoute(array $route) {
		self::$routes[$route['method']][] = $route;
	}
	
	static public function match(Request $request) {
		foreach (self::$routes[$request->getMethod()] as $route) {
			$regex = $route['pattern'] . '(\.(?P<format>\w+))?';
			$regex = preg_replace('/(:([a-z]+))/', '(?P<$2>[\w-]+)', $regex);
			$regex = '/^' . str_replace('/', '\/', $regex) . '\/?$/i';

			if (preg_match($regex, $request->getPath(), $matches)) {
				return array(
					'callback' => $route['callback'],
					'params'   => $matches
				);
			}
		}
		return NULL;
	}
	
	static public function getRoutes() {
		return self::$routes;
	}
}