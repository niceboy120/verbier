<?php

namespace Verbier;

class Application {
	
	/**
	 * Reference to the Dispatcher object.
	 *
	 * @var Dispatcher
	 */
	protected $dispatcher;
	
	/**
	 * Reference to the Request object.
	 *
	 * @var Request
	 */
	public $request;
	
	/**
	 * Reference to the Response object.
	 *
	 * @var Response
	 */
	public $response;
	
	/**
	 * Holds settings set via set(), enable() and disable()
	 *
	 * @var array
	 */
	public $settings = array();
	
	/**
	 * Holds defined filters.
	 *
	 * @var array
	 */
	protected $filters = array(
		'before' => array(),
		'after'  => array()
	);
	
	/**
	 * Construct a new application.
	 *
	 * @param Dispatcher $dispatcher 
	 * @param Config $config 
	 * @todo Need the fix dependency creation
	 */
	public function __construct(Dispatcher $dispatcher = null) {
		$this->dispatcher = $dispatcher === null ? new Dispatcher($this) : $dispatcher;
		$this->request    = new Request();
		$this->response   = new Response();
		$this->template   = new Template('views/');
	}
	
	public function get($pattern, $handler) {
		$this->dispatcher->addRoute('get', $pattern, $handler);
	}
	
	public function post($pattern, $handler) {
		$this->dispatcher->addRoute('post', $pattern, $handler);
	}
	
	public function put($pattern, $handler) {
		$this->dispatcher->addRoute('put', $pattern, $handler);
	}
	
	public function delete($pattern, $handler) {
		$this->dispatcher->addRoute('delete', $pattern, $handler);
	}
	
	public function set($key, $value = null) {
		$this->settings[$key] = $value;
	}
	
	public function enable($key) {
		$this->set($key, true);
	}
	
	public function disable($key) {
		$this->set($key, false);
	}
	
	public function filter($type) {
		foreach ((array) $this->filters[$type] as $filter) {
			$filter($this);
		}
	}
	
	public function before($filter) {
		$this->filters['before'][] = $filter;
	}
	
	public function after($filter) {
		$this->filters['after'][] = $filter;
	}
	
	public function run() {
		$this->filter('before');
		$response = $this->dispatcher->dispatch($this->request, $this->response);
		$this->filter('after');
		$response->finish();
	}
	
	public function render($templateName, $variables = array()) {
		$variables = array_merge($variables, get_object_vars($this));
		foreach ($variables as $key => $value) {
			$this->template->$key = $value;
		}
		return $this->template->render($templateName);
	}
	
	/**
	 * Redirect to a new location
	 *
	 * @param string $location 
	 * @param array $options 
	 * @return void
	 */
	public function redirect($location, $status = 302) {
		$this->response->setStatus($status);
		$this->response->setHeader('Location: ' . $location);
		$this->response->finish();
	}
}