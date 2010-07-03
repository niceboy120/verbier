<?php

namespace Verbier;

class Route {
	
	protected $pattern;
	protected $requestMethod;
	protected $callback;
	
	public function __construct($pattern) {
		$this->pattern = $pattern;
	}
	
	public function on($requestMethod) {
		$this->requestMethod = strtoupper($requestMethod);
		return $this;
	}
	
	public function to($callback) {
		$this->callback = $callback;
		Router::getInstance()->addRoute($this);
	}
	
	public function getMethod() {
		return $this->requestMethod;
	}
	
	public function getPattern() {
		return $this->pattern;
	}
	
	public function getCallback() {
		return $this->callback;
	}
}