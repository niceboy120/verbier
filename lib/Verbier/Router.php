<?php

namespace Verbier;

class Router {
	
	static $instance = NULL;
	
	protected $routes;
	
	/**
	 * I hate this, but I don't find a more decent way
	 *
	 * @return void
	 */
	static public function getInstance() {
		if (!self::$instance instanceof self) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function addRoute(array $route) {
		$this->routes[$route['method']][] = $route;
	}
	
	public function match(Request $request) {
		foreach ($this->routes[$request->getMethod()] as $route) {
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
	
	public function getRoutes() {
		return $this->routes;
	}
}