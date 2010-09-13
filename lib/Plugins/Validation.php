<?php

class Validation {
	
	public function isPresent($value) {
		return !empty($value);
	}
	
	/**
	 * Returns true if the given value is a valid email address.
	 *
	 * @param string $value 
	 * @return boolean
	 */
	static public function isEmail($value) {
		return filter_var($value, FILTER_VALIDATE_EMAIL);
	}
	
	/**
	 * Returns true if the given value is a valid URL
	 *
	 * @param string $value 
	 * @return boolean
	 */
	static public function isUrl($value) {
		return filter_var($value, FILTER_VALIDATE_URL);
	}
	
	/**
	 * Returns true if the given value is a valid IP
	 *
	 * @param string $value 
	 * @return boolean
	 */
	static public function isIP($value) {
		return filter_var($value, FILTER_VALIDATE_IP);
	}
	
	/**
	 * Returns true if the given value only consists of alpha characters.
	 *
	 * @param string $value 
	 * @return boolean
	 */
	static public function isAlpha($value) {
		return ctype_alpha($value);
	}
	
	/**
	 * Returns true if the length of the given value matches the criteria set in $options.
	 *
	 * Valid options are `max`, `min`, `range`, `exact`. Setting $options to an integer
	 * is the same as using the `exact` option.
	 *
	 * @param string $value 
	 * @return boolean
	 */
	static public function lengthOf($value, $options = null) {
		if (is_string($value)) {
			$length = strlen($value);
		} elseif (is_array($value)) {
			$length = count($value);
		}
		
		// $options is a number
		// lengthOf($value, 10);
		if (is_int($options) && $length == $options) {
			return false;
		}
		
		if (isset($options['max']) && $length > $options['max']) {
			return false;
		}
		
		if (isset($options['min']) && $length < $options['min']) {
			return false;
		}
		
		// lengthOf($value, array('range' => array(20,30)))
		if (isset($options['range']) && in_array($length, range($options['range'][0], $options['range'][1]))) {
			return false;
		}
		
		return true;
	}
}