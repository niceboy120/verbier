<?php

namespace Verbier;

class RequestHandler {
	
	public function handleRequest(Request $request, Response $response) {
		$matches = \Verbier\Router::getInstance()->match($request);
		
		if ($matches === NULL) {
			throw new \Exception('No routes match the given path `'.$request->getPath().'`');
		}
		
		if (!is_callable($matches['callback'])) {
			throw new \BadMethodCallException('The supplied callback is not valid.');
		}
		
		$context = new Context($request, $response);
		$context->setParams($matches['params']);
		
		$result = call_user_func($matches['callback'], $context);
		
		if (is_string($result)) {
			$response->setContent($result);
		}
		
		$response->finish();
	}
}