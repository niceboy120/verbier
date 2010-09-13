<?php

namespace Verbier;

/**
 * Generic template class
 *
 * @package Verbier
 * @author Hans-Kristian Koren
 */
class Template {
	
	/**
	 * Template not found error message
	 */
	const TEMPLATE_NOT_FOUND = 'The template %s was not found.';
	
	/**
	 * The directory in which the templates are located.
	 *
	 * @var string
	 */
	protected $path;
	
	/**
	 * The layout to use.
	 *
	 * @var string
	 */
	protected $layout = null;
	
	/**
	 * Constructor.
	 */
	public function __construct() {
	}
	
	public function setPath($path) {
		$this->path = $path;
	}
	
	/**
	 * Set the layout to use. Layouts must reside inside layouts/ in the template directory.
	 *
	 * @param string $layout 
	 * @return void
	 */
	public function setLayout($layout = null) {
		$this->layout = $layout;
	}
	
	/**
	 * Renders the given template.
	 *
	 * @param string $templateName 
	 * @return string
	 */
	public function render($templateName) {
		if ($this->layout === null) {
			return $this->renderWithoutLayout($templateName);
		}
		return $this->renderWithLayout($templateName);
	}
	
	/**
	 * Renders the template inside a layout.
	 *
	 * @param string $templateName The template to render.
	 * @return string The rendered template.
	 */
	public function renderWithLayout($templateName) {
		$this->layoutContent = $this->renderWithoutLayout($templateName);
		return $this->renderWithoutLayout('layouts/' . $this->layout);
	}
	
	/**
	 * Renders the template without a layout.
	 *
	 * @param string $templateName The template to render.
	 * @return string The rendered template.
	 */
	public function renderWithoutLayout($templateName) {
		ob_start();
		
		$path = $this->path . $templateName . '.phtml';
		if (!is_readable($path)) {
			throw new \Exception(sprintf(static::TEMPLATE_NOT_FOUND, $path));
		}
		
		include $path;
		
		return ob_get_clean();
	}
}