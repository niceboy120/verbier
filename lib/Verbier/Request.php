<?php

namespace Verbier;

/**
 * Class representing a HTTP request
 *
 * @package default
 * @author Hans-Kristian Koren
 */
class Request {
	
	/**
	 * Our enviroment variable stack
	 *
	 * @var array
	 */
	public $environment = array();
	
	/**
	 * Array of request parameters. These includes _GET, _POST and arguments from the router
	 *
	 * @var array
	 */
	public $params = array();
	
	/**
	 * Construct a new Request-object
	 *
	 * This will basically populate the environment and arguments array as well as fix the _FILES array
	 *
	 * @author Hans-Kristian Koren
	 */
	public function __construct() {
		$this->initializeFiles();
		$this->environment = array_merge($_SERVER, $_ENV);
		$this->params   = array_merge($_POST, $_GET, $_FILES);
	}
	
	/**
	 * Get an array of URI params and other request params (_POST, _GET and _FILES)
	 *
	 * @return array
	 */
	public function getParams() {
		return $this->params;
	}
	
	/**
	 * Return the method with whom the current request is requested with. It emulates browser support for PUT and DELETE
	 * by looking for a _method parameter in the _POST array on POST type request.
	 *
	 * @return string  The requested method
	 * @author Hans-Kristian Koren
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
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getPath() {
		return $this->getURI()->getPath();
	}
	
	/**
	 * Return the current URI
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getURI() {
		if (isset($this->environment['PATH_INFO'])) {
			$requestURIString = $this->getProtocol() . '://' . $this->getHTTPHost() . $this->environment['PATH_INFO'] . (strlen($this->environment['QUERY_STRING']) ? '?' . $this->environment['QUERY_STRING'] : '');
		} else {
			$requestURIString = $this->getProtocol() . '://' . $this->getHTTPHost() . '/';
		}
		return new \Verbier\URIProperty($requestURIString);
	}
	
	/**
	 * Get the request protocol
	 *
	 * @todo Make it more useful by adding support for other protocols than http
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getProtocol() {
		// @todo implement https
		return 'http';
	}
	
	/**
	 * Get the HTTP HOST variable
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getHTTPHost() {
		return $this->environment['HTTP_HOST'];
	}
	
	/**
	 * Get the HTTP referer variable
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getHTTPReferer() {
		return isset($this->environment['HTTP_REFERER']) ? $this->environment['HTTP_REFERER'] : NULL;
	}
	
	/**
	 * Get the HTTP user agent
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getHTTPUserAgent() {
		return isset($this->environment['HTTP_USER_AGENT']) ? $this->environment['HTTP_USER_AGENT'] : NULL;
	}
	
	/**
	 * Get the remote IP
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getRemoteIP() {
		return isset($this->environment['REMOTE_ADDR']) ? $this->environment['REMOTE_ADDR'] : NULL;
	}
	
	/**
	 * Get the remote host
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getRemoteHost() {
		return isset($this->environment['REMOTE_HOST']) ? $this->environment['REMOTE_HOST'] : NULL;
	}
	
	/**
	 * Get the server name
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getServerName() {
		return isset($this->environment['SERVER_NAME']) ? $this->environment['SERVER_NAME'] : NULL;
	}
	
	/**
	 * Get the server address
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getServerAddr() {
		return isset($this->environment['SERVER_ADDR']) ? $this->environment['SERVER_ADDR'] : NULL;
	}
	
	/**
	 * Get a comma separated list of accepts
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getAccepts() {
		return explode(',', $this->environment['HTTP_ACCEPT']);
	}
	
	/**
	 * Determine what content type to provide based on a set of candidates
	 *
	 * @param array $candidates 
	 * @return void
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
	 * By default, the nested _FILES array is quite a mess, so we have to restructure it
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function reorderFilesGlobal($group) {
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
	}
	
	/**
	 * Return a response object that is suitable for requests made with this Request-object
	 * Since this is a HTTP-request it is most useful and suitable to return a HTTP-response ;)
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function getResponse() {
		return new \Rapide\MVC\Http\Response();
	}
	
	/**
	 * Convenience method to check if the request is of type GET
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function isGet() {
		return $this->getMethod() === 'GET';
	}
	
	/**
	 * Convenience method to check if the request is of type PUT
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function isPut() {
		return $this->getMethod() === 'PUT';
	}
	
	/**
	 * Convenience method to check if the request is of type POST
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function isPost() {
		return $this->getMethod() === 'POST';
	}
	
	/**
	 * Convenience method to check if the request is of type DELETE
	 *
	 * @return void
	 * @author Hans-Kristian Koren
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
		$_FILES = array_map(array($this, 'reorderFilesGlobal'), $_FILES);
		
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
}