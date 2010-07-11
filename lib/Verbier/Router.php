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
	
	public function addRoute(Route $route) {
		$this->routes[$route->getMethod()][] = $route;
	}
	
	public function match(Request $request) {
		foreach ($this->routes[$request->getMethod()] as $route) {
			$regex = $route->getPattern() . '(\.(?P<format>\w+))?';
			$regex = preg_replace('/(:([a-z]+))/', '(?P<$2>[\w-]+)', $regex);
			$regex = '/^' . str_replace('/', '\/', $regex) . '\/?$/i';

			if (preg_match($regex, $request->getPath(), $matches)) {
				return array(
					'callback' => $route->getCallback(),
					'params'   => $matches
				);
			}
		}
		return NULL;
	}
	
	public function getRoutes() {
		return $this->routes;
	}
	
	static public function debugRoutes() {
		var_dump(self::getInstance()->getRoutes());
	}
}