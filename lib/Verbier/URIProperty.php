<?php

namespace Verbier;
/**
 * An object representation of an URI
 * Essentially a duplicate of FLOW3\Resource\DataType\URI. Thanks to them.
 *
 * @package default
 * @author Robert Lemke
 * @author Hans-Kristian Koren
 */
class URIProperty {
	
	/**
	 * The scheme part of the URI
	 *
	 * @var string
	 */
	protected $scheme;
	
	/**
	 * The username part of the URI
	 *
	 * @var string
	 */
	protected $username;
	
	/**
	 * The password part of the URI
	 *
	 * @var string
	 */
	protected $password;
	
	/**
	 * The host part of the URI
	 *
	 * @var string
	 */
	protected $host;
	
	/**
	 * The port part of the URI
	 *
	 * @var string
	 */
	protected $port;
	
	/**
	 * The URI path
	 *
	 * @var string
	 */
	protected $path;
	
	/**
	 * The query string of the URI
	 *
	 * @var string
	 */
	protected $query;
	
	/**
	 * The query string as array
	 *
	 * @var array
	 */
	protected $arguments = array();
	
	/**
	 * Fragment/Anchor for the uri
	 *
	 * @var string
	 */
	protected $fragment;
	
	public function __construct($uriString) {
		if (!is_string($uriString)) { throw new \InvalidArgumentException('URI must be a string.'); }
		
		$parts = parse_url($uriString);
		
		if (is_array($parts)) {
			$this->scheme   = isset($parts['scheme'])   ? $parts['scheme'] : null;
			$this->username = isset($parts['username']) ? $parts['username'] : null;
			$this->password = isset($parts['password']) ? $parts['password'] : null;
			$this->host     = isset($parts['host'])     ? $parts['host'] : null;
			$this->port     = isset($parts['port'])     ? $parts['port'] : null;
			$this->path     = isset($parts['path'])     ? $parts['path'] : null;
			
			if (isset($parts['query'])) {
				$this->setQuery($parts['query']);
			}
			
			$this->fragment = isset($parts['fragment']) ? $parts['fragment'] : null;
		}
	}

	public function getScheme() {
		return $this->scheme;
	}
	
	public function getUsername() {
		return $this->username;
	}
	
	public function getPassword() {
		return $this->password;
	}
	
	public function getHost() {
		return $this->host;
	}
	
	public function getPort() {
		return $this->port;
	}
	
	public function getPath() {
		return $this->path;
	}
	
	public function setQuery($query) {
		$this->query = $query;
		parse_str($query, $this->arguments);
	}
	
	public function getQuery() {
		return $this->query;
	}
	
	public function getFragment() {
		return $this->fragment;
	}
	
	public function getArguments() {
		return $this->arguments;
	}
	
	public function __toString() {
		$uriString = '';
		
		$uriString .= isset($this->scheme) ? $this->scheme . '://' : '';
		
		if (isset($this->username)) {
			if (isset($this->password)) {
				$uriString .= $this->username . ':' . $this->password . '@';
			} else {
				$uriString .= $this->username . '@';
			}
		}
		$uriString .= $this->host;
		$uriString .= isset($this->port) ? ':' . $this->port : '';
		if (isset($this->path)) {
			$uriString .= $this->path;
			$uriString .= isset($this->query) ? '?' . $this->query : '';
			$uriString .= isset($this->fragment) ? '#' . $this->fragment : '';
		}
		return $uriString;
	}
}