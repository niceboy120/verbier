<?php

namespace Verbier;


class Template {
	
	protected $layoutName;
	
	public function __construct($directory) {
		$this->directory = $directory;
	}
	
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