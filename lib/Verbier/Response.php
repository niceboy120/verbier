<?php

namespace Verbier;

/**
 * An class representing a HTTP response
 *
 * @package Verbier
 * @author Hans-Kristian Koren
 */
class Response {
	
	/**
	 * Array of headers which should be sent
	 *
	 * @var array
	 */
	protected $headers = array();
	
	/**
	 * The status code for this response, defaults to 200 (OK)
	 *
	 * @var integer
	 */
	protected $statusCode = 200;
	
	/**
	 * The body of the response
	 *
	 * @var string
	 */
	protected $content = NULL;
	
	/**
	 * The content type of the response
	 *
	 * @var string
	 */
	protected $contentType = 'text/html';
	
	/**
	 * Array of possible status codes and messages
	 *
	 * @var array
	 */
	protected $statusMessages = array(
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',

		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		226 => 'IM Used',

		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',

		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		426 => 'Upgrade Required',

		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		507 => 'Insufficient Storage',
		510 => 'Not Extended'
	);
	
	/**
	 * Set the HTTP status code
	 *
	 * @param integer $statusCode 
	 * @return void
	 */
	public function setStatus($statusCode) {
		if (!is_int($statusCode)) { throw new \InvalidArgumentException('The HTTP status code must be an integer'); }
		$this->statusCode = $statusCode;
	}
	
	/**
	 * Get the status header (200 OK)
	 *
	 * @return void
	 */
	public function getStatus() {
		return $this->statusCode . ' ' . $this->statusMessages[$statusCode];
	}
	
	/**
	 * Set the content type for the response
	 *
	 * @param string $contentType 
	 * @return void
	 */
	public function setContentType($contentType) {
		$this->contentType = $contentType;
	}
	
	/**
	 * Get the content type
	 *
	 * @return void
	 */
	public function getContentType() {
		return $this->contentType;
	}
	
	public function setContent($content) {
		$this->content = $content;
	}
	
	public function appendContent($content) {
		$this->content .= $content;
	}
	
	public function getContent() {
		return $this->content;
	}
	
	/**
	 * Set a response header
	 *
	 * @param string $header 
	 * @return void
	 */
	public function setHeader($header) {
		$this->headers[] = $header;
	}
	
	/**
	 * Get the response headers
	 *
	 * @return void
	 */
	public function getHeaders() {
		return $this->headers;
	}
	
	/**
	 * Send the HTTP headers if not already sent
	 *
	 * @return void
	 */
	public function sendHeaders() {
		if (headers_sent()) {
			return;
		}
		
		$this->setHeader('Content-Type: ' . $this->contentType . ';charset=utf-8');
		$this->setHeader('HTTP/1.1 ' . $this->statusCode . ' ' . $this->statusMessages[$this->statusCode]);
		
		// Reverse it because the http header must come first
		foreach (array_reverse($this->getHeaders()) as $header) {
			header($header);
		}
	}
	
	/**
	 * Send the entire response, headers and content
	 *
	 * @return void
	 */
	public function finish() {
		$this->sendHeaders();
		echo $this->getContent();
	}
}