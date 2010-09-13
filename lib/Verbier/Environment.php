<?php

namespace Verbier;

class Environment {
	
	/**
	 * Development mode
	 */
	const DEVELOPMENT = 'Development';
	
	/**
	 * Production mode
	 */
	const PRODUCTION  = 'Production';
	
	/**
	 * Returns true if the environment is development.
	 *
	 * @return boolean
	 */
	static public function isDevelopment() {
		return getenv('VERBIER_ENV') !== static::PRODUCTION;
	}
}