<?php

namespace Verbier;

class Route {
	
	protected $pattern;
	protected $method;
	protected $callback;
	
	public function __construct($pattern, $method, $callback) {
		$this->pattern  = $pattern;
		$this->method   = $method;
		$this->callback = $callback;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getPattern() {
		return $this->pattern;
	}
	
	public function getCallback() {
		return $this->callback;
	}
}