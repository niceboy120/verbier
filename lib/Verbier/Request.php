<?php

namespace Verbier;

/**
 * Class representing a HTTP request
 *
 * @package Verbier
 * @author Hans-Kristian Koren
 */
class Request {
	
	/**
	 * Our enviroment variable stack
	 *
	 * @var array
	 */
	protected $env = array();
	
	/**
	 * Array of request parameters. These includes _GET, _POST and arguments from the router
	 *
	 * @var array
	 */
	protected $params = array();
	
	/**
	 * Array of request headers (SERVER vars beginning with HTTP_)
	 *
	 * @var array
	 */
	protected $headers = array();
	
	/**
	 * Construct a new Request-object
	 *
	 * This will basically populate the environment and arguments array as well as fix the _FILES array
	 *
	 * @todo
	 */
	public function __construct() {
		$this->initializeFiles();
		$this->environment = array_merge($_SERVER, $_ENV);
		$this->params      = array_merge($_POST, $_GET, $_FILES);
		$this->headers     = $this->initHeaders();
	}
	
	public function env($name) {
		return isset($this->env[$name]) ? $this->env[$name] : NULL;
	}
	
	public function param($name) {
		return isset($this->params[$name]) ? $this->params[$name] : NULL;
	}
	
	public function header($name) {
		return isset($this->headers[$name]) ? $this->headers[$name] : NULL;
	}
	
	public function setParams(array $params) {
		$this->params = array_merge($this->params, $params);
	}
	
	/**
	 * Return the method with whom the current request is requested with. It emulates browser support for PUT and DELETE
	 * by looking for a _method parameter in the _POST array on POST type request.
	 *
	 * @return string  The requested method
	 */
	public function getMethod() {
		$requestMethod = strtoupper($this->environment['REQUEST_METHOD']);
		if ($requestMethod == 'POST' || $requestMethod == 'GET') {
			$validMethods = array('GET', 'POST', 'PUT', 'DELETE', 'HEAD');
			if (isset($this->params['_method']) && in_array(strtoupper($this->params['_method']), $validMethods)) {
				return strtoupper($this->params['_method']);
			}
		}
		return $requestMethod;
	}
	
	/**
	 * Return the path portion of the current URI
	 *
	 * @return string
	 */
	public function getPath() {
		return isset($this->environment['PATH_INFO']) ? $this->environment['PATH_INFO'] : '/';
	}
	
	/**
	 * Get a comma separated list of accepts
	 *
	 * @return array
	 */
	public function getAccepts() {
		return explode(',', $this->environment['HTTP_ACCEPT']);
	}
	
	/**
	 * Determine what content type to provide based on a set of candidates
	 *
	 * @param array $candidates 
	 * @return string
	 */
	public function negotiateMimeType(array $candidates = NULL) {
		$parsedAccepts = array();
		foreach ($this->getAccepts() as $accept) {
			$quality = 1;
			if (strstr($accept, ';q=')) {
				list($accept, $quality) = explode(';q=', $accept);
			}
			$parsedAccepts[$accept] = $quality;
		}
		arsort($parsedAccepts);

		if ($candidates === NULL) {
			return $parsedAccepts;
		}
		
		foreach ($parsedAccepts as $mime => $quality) {
			if ($quality && in_array($mime, $candidates)) {
				return $mime;
			}
		}
		
		return NULL;
	}
	
	/**
	 * Convenience method to check if the request is of type GET
	 *
	 * @return boolean
	 */
	public function isGet() {
		return $this->getMethod() === 'GET';
	}
	
	/**
	 * Convenience method to check if the request is of type PUT
	 *
	 * @return boolean
	 */
	public function isPut() {
		return $this->getMethod() === 'PUT';
	}
	
	/**
	 * Convenience method to check if the request is of type POST
	 *
	 * @return boolean
	 */
	public function isPost() {
		return $this->getMethod() === 'POST';
	}
	
	/**
	 * Convenience method to check if the request is of type DELETE
	 *
	 * @return boolean
	 */
	public function isDelete() {
		return $this->getMethod() === 'DELETE';
	}
	
	/**
	 * Initialize the _FILES global and make each element a FileUpload object
	 *
	 * @return void
	 */
	protected function initializeFiles() {
		$_FILES = array_map(function($group) {
			if (!is_array($group['tmp_name'])) {
				return $group;
			}
			$result = array();
			foreach ($group as $property => $array) {
				foreach ($array as $item => $value) {
					$result[$item][$property] = $value;
				}
			}
			return $result;
		}, $_FILES);
		
		foreach ((array) $_FILES as $name => $options) {
			if (isset($options['tmp_name'])) {
				$_FILES[$name] = new \Verbier\FileUpload($options);
			} else {
				foreach ($options as $attribute => $data) {
					$_FILES[$name][$attribute] = new \Verbier\FileUpload($data);
				}
			}
		}
	}
	
	protected function initHeaders() {
		$headers = array();
		foreach ($this->env as $key => $value) {
			if (substr($key, 0, 5) === 'HTTP_') {
				$headerName = strtolower(substr($key, 5));
				$headers[$headerName] = $value;
			}
		}
		return $headers;
	}
}