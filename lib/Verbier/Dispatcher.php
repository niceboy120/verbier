<?php

namespace Verbier;

/**
 * The dispatcher matches the request path against a set of defined routes and
 * calls the appropriate action.
 *
 * @package Verbier
 * @author Hans-Kristian Koren
 */
class Dispatcher {
	
	/**
	 * Holds the defined routes.
	 *
	 * @var array
	 */
	protected $routes = array();
	
	/**
	 * Constructor.
	 *
	 * @param Application $application 
	 */
	public function __construct(Application $application) {
		$this->application = $application;
		
		foreach (array('get', 'post', 'put', 'delete') as $method) {
			$this->routes[$method] = array();
		}
	}
	
	/**
	 * Add a new route.
	 *
	 * @param string $method The request method
	 * @param string $pattern The route pattern
	 * @param Closure $handler The handler
	 * @return void
	 */
	public function addRoute($method, $pattern, $handler) {
		$this->routes[$method][] = array(
			'pattern' => $pattern,
			'handler' => $handler
		);
	}
	
	/**
	 * Dispatch the given request by finding and invoking the handler matching the request data.
	 *
	 * @param Request $request 
	 * @param Response $response 
	 * @return Response
	 */
	public function dispatch(Request $request, Response $response) {
		$matches = $this->match($request->getPath(), $request->getMethod());
		
		if ($matches === null) {
			throw new \Exception('No routes match the path '.$request->getPath().'');
		}
		
		if (!is_callable($matches['handler'])) {
			throw new \Exception('The given handler is not a valid callback.');
		}

		$result = call_user_func_array($matches['handler'], array_merge(array($this->application), $matches['params']));
		
		if (is_string($result)) {
			$response->setContent($result);
		} elseif (is_numeric($result)) {
			$response->setStatus($result);
		}
		
		return $response;
	}
	
	/**
	 * Match the path and method against the defined routes.
	 *
	 * @param string $path 
	 * @param string $method 
	 * @return array|null
	 */
	public function match($path, $method) {
		$method = strtolower($method);
		foreach ($this->routes[$method] as $route) {
			$regex = $route['pattern'] . '(\.(?P<format>\w+))?';
			$regex = preg_replace('/(:([a-z]+))/', '([\w-]+)', $regex);
			$regex = '/^' . str_replace('/', '\/', $regex) . '\/?$/i';

			if (preg_match($regex, $path, $matches)) {
				return array(
					'handler' => $route['handler'],
					'path' => array_shift($matches),
					'params'   => $matches
				);
			}
		}
		return null;
	}
}