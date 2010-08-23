<?php

namespace Verbier;

/**
 * This is where the magic happens. All API methods are defined here.
 *
 * @package Verbier
 * @author Hans-Kristian Koren
 */
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
	public $settings = array(
		'templates' => 'views/'
	);
	
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
		$this->template   = new Template($this->settings['templates']);
	}
	
	/**
	 * Add a GET route.
	 *
	 * @param string $pattern 
	 * @param \Closure $handler 
	 * @return void
	 */
	public function get($pattern, $handler) {
		$this->dispatcher->addRoute('get', $pattern, $handler);
	}
	
	/**
	 * Add a POST route.
	 *
	 * @param string $pattern 
	 * @param \Closure $handler 
	 * @return void
	 */
	public function post($pattern, $handler) {
		$this->dispatcher->addRoute('post', $pattern, $handler);
	}
	
	/**
	 * Add a PUT route.
	 *
	 * @param string $pattern 
	 * @param \Closure $handler 
	 * @return void
	 */
	public function put($pattern, $handler) {
		$this->dispatcher->addRoute('put', $pattern, $handler);
	}
	
	/**
	 * Add a DELETE route.
	 *
	 * @param string $pattern 
	 * @param \Closure $handler 
	 * @return void
	 */
	public function delete($pattern, $handler) {
		$this->dispatcher->addRoute('delete', $pattern, $handler);
	}
	
	/**
	 * Set a setting value.
	 *
	 * @param string $key 
	 * @param mixed $value 
	 * @return void
	 */
	public function set($key, $value = null) {
		$this->settings[$key] = $value;
	}
	
	/**
	 * Flips a setting to true.
	 *
	 * @param string $key 
	 * @return void
	 */
	public function enable($key) {
		$this->set($key, true);
	}
	
	/**
	 * Flips a setting to false.
	 *
	 * @param string $key 
	 * @return void
	 */
	public function disable($key) {
		$this->set($key, false);
	}
	
	/**
	 * Set a flash message.
	 *
	 * @param string $name 
	 * @param string $value 
	 * @return void
	 */
	public function flash($name, $value) {
		FlashMessage::set($name, $value);
	}
	
	/**
	 * Run filters of type $type
	 *
	 * @param string $type 
	 * @return void
	 */
	public function filter($type) {
		foreach ((array) $this->filters[$type] as $filter) {
			$filter($this);
		}
	}
	
	/**
	 * Add a before-type filter.
	 *
	 * @param \Closure $filter 
	 * @return void
	 */
	public function before($filter) {
		$this->filters['before'][] = $filter;
	}
	
	/**
	 * Add an after-type filter.
	 *
	 * @param \Closure $filter 
	 * @return void
	 */
	public function after($filter) {
		$this->filters['after'][] = $filter;
	}
	
	/**
	 * Run the application.
	 *
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function run() {
		$this->filter('before');
		$response = $this->dispatcher->dispatch($this->request, $this->response);
		$this->filter('after');
		$response->finish();
	}
	
	/**
	 * Render a template. Given variables along with the instance variables
	 * of Application will be available through $this in the template.
	 *
	 * @param string $templateName 
	 * @param array $variables 
	 * @return string
	 */
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
	public function redirect($location, $options = array()) {
		if (isset($options['notice'])) {
			$this->flash('notice', $options['notice']);
		}
		$status = isset($options['status']) ? $options['status'] : 302;
		$this->response->setStatus($status);
		$this->response->setHeader('Location: ' . $location);
		$this->response->finish();
	}
}