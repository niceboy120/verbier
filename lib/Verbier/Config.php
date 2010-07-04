<?php

namespace Verbier;

/**
 * Config class that holds our config values
 *
 * Using singleton because PHP does not have __setStatic and __getStatic
 *
 * @package default
 * @author Hans-Kristian Koren
 */
class Config {
	
	static protected $instance = NULL;
	protected $values = array();
	
	static public function getInstance() {
		if (!self::$instance instanceof self) {
			self::$instance = new self;
		}
		return self::$instance;
	}
	
	public function __set($key, $value) {
		$this->values[$key] = $value;
	}
	
	public function __get($key) {
		return $this->values[$key];
	}
}