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
	 * undocumented function
	 *
	 * @param string $directory 
	 */
	public function __construct($directory) {
		$this->directory = $directory;
	}
	
	/**
	 * Render the damn template
	 *
	 * @param string $templateName 
	 * @return string
	 */
	public function render($templateName) {
		ob_start();
		
		$path = $this->directory . $templateName . '.phtml';
		if (!file_exists($path)) {
			throw new \Exception('The template `'.$path.'` was not found');
		}
		
		include $path;
		
		return ob_get_clean();
	}
}