<?php

namespace Verbier;

/**
 * The context class is passed to the app Closures and is quite hacky.
 *
 * @package default
 */
class Context {
	
	public $layout = NULL;
	
	/**
	 * Constructor de magice
	 *
	 * @param \Verbier\Request $request 
	 * @param \Verbier\Response $response 
	 */
	public function __construct(Request $request, Response $response) {
		$this->request  = $request;
		$this->response = $response;
		$this->template = new \Verbier\Template(option('templatePath'));
	}
	
	/**
	 * Get the requested format
	 *
	 * @return string
	 * @todo Accepts
	 */
	public function getFormat() {
		if (isset($this->params['format'])) {
			return $this->params['format'];
		}
		// find a decent one based on accept headers
		return NULL;
	}
	
	/**
	 * Set a flash message that will be available on the next request.
	 *
	 * @param string $notice 
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function setFlashNotice($notice) {
		\Verbier\FlashMessage::set('notice', $notice);
	}
	
	/**
	 * Render a template
	 *
	 * Templates are loaded from Config::$templatePath and must have an extension .phtml.
	 * Layouts should reside in templates/layouts/ an can be set by $that->layout = 'mylayout' in your definitions.
	 *
	 * @param string $templateName 
	 * @return void
	 * @author Hans-Kristian Koren
	 */
	public function render($templateName) {
		foreach (get_object_vars($this) as $property => $value) {
			$this->template->$property = $value;
		}
		if ($this->layout !== NULL) {
			$this->template->contentForLayout = $this->template->render($templateName);
			$content = $this->template->render('layouts/' . $this->layout);
		} else {
			$content = $this->template->render($templateName);
		}
		
		return $content;
	}
	
	/**
	 * Redirect to a new location
	 *
	 * @param string $location 
	 * @param array $options 
	 * @return void
	 * @todo Allow custom status codes via $options array
	 */
	public function redirect($location, $status = 302) {
		$this->response->setStatus($status);
		$this->response->setHeader('Location: ' . $location);
		$this->response->finish();
	}
}