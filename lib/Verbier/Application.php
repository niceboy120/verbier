<?php

namespace Verbier;

/**
 * The Application class is the heart of the framework and it is here all the magic happen.
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
	
	protected $helpers = array(
		'core' => array('get', 'post', 'put', 'delete', 'configure', 'set', 'enable', 'disable', 'setting', 'helper', 'layout', 'filter', 'before', 'after', 'halt', 'run', 'render', 'redirect'),
		'user' => array()
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
	
	static $plugins = array();
	
	protected $coreDependencies = array(
		'dispatcher' => 'Verbier\Dispatcher',
		'template'       => 'Verbier\Template',
		'request'    => 'Verbier\Request',
		'response'   => 'Verbier\Response'
	);
	
	/**
	 * Construct a new application.
	 *
	 * @param Dispatcher $dispatcher 
	 * @param Config $config 
	 * @todo Need the fix dependency creation
	 */
	public function __construct() {
		foreach ($this->coreDependencies as $dependency => $className) {
			$this->$dependency = $this->dependency($dependency, $className);
		}
		$this->template->setPath(APP_PATH . '/views/');
		
		foreach (static::$plugins as $plugin) {
			$plugin($this);
		}
	}
	
	public function dependency($name,  $className) {
		if ($dependency = $this->setting($name)) {
			return new $dependency();
		}
		return in_array($name, array('dispatcher', 'view')) ? new $className($this) : new $className;
	}

	public function configure($envs, $handler) {
		if (in_array($this->env, (array) $envs)) {
			$handler($this);
		}
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
	
	public function setting($key) {
		return isset($this->settings[$key]) ? $this->settings[$key] : null;
	}

	public function helper($helperName, \Closure $closure) {
		$this->helpers['user'][$helperName] = $closure;
	}
	
	/**
	 * Set the layout name.
	 *
	 * @param string $layout 
	 * @return void
	 */
	public function layout($layout) {
		$this->template->setLayout($layout);
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
	
	public function halt() {
		$this->response->finish();
	}
	
	/**
	 * Run the application.
	 *
	 * @return void
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
	public function redirect($location, $status = 302) {
		$this->response->setStatus($status);
		$this->response->setHeader('Location: ' . $location);
		$this->response->finish();
	}
	
	public function __call($method, $arguments) {
		if (isset($this->helpers['user'][$method])) {
			return call_user_func_array($this->helpers['user'][$method], $arguments);
		}
		throw new \BadMethodCallException('Call to undefined method '.$method.' on ' . get_class($this));
	}
	
	public function getHelpers() {
		return array_merge($this->helpers['core'], array_keys($this->helpers['user']));
	}
	
	static public function registerPlugin($pluginName, \Closure $closure) {
		static::$plugins[$pluginName] = $closure;
	}
	
	static public function unregisterPlugin($pluginName) {
		unset(static::$plugins[$pluginName]);
	}
}