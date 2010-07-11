<?php

namespace Verbier;

class RequestHandler {
	
	public function handleRequest(Request $request, Response $response) {
		$matches = \Verbier\Router::match($request);
		
		if ($matches === NULL) {
			throw new \Exception('No routes match the given path `'.$request->getPath().'`');
		}
		
		if (!is_callable($matches['callback'])) {
			throw new \BadMethodCallException('The supplied callback is not valid.');
		}
		
		$request->setParams($matches['params']);
		
		$contextClass = option('contextClass');
		$context = new $contextClass($request, $response);
		
		$result = $matches['callback']($context);
		
		if (is_string($result)) {
			$response->setContent($result);
		} elseif (is_int($result)) {
			$response->setStatus($result);
		}
		
		$response->finish();
	}
}