<?php

namespace Verbier;

/**
 * Config class that holds our config values
 *
 * @package Verbier
 * @author Hans-Kristian Koren
 */
class Config {
	
	static $templatePath = 'templates/';
	static $contextClass = 'Verbier\Context';
	static $sessions = TRUE;
	static $flash = TRUE;
	static $errors = TRUE;
}