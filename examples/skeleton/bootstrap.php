<?php

set_include_path(implode(PATH_SEPARATOR, array(
	'../../../lib/',
	__DIR__ . '/models/',
	'/Users/hans-kristiankoren/Utvikling/Projects/rapide-activerecord/lib/',
	'/Users/hans-kristiankoren/Utvikling/Projects/rapide-framework/lib/',
	get_include_path()
)));

spl_autoload_register(function($className) {
	include str_replace(array('\\', '_'), '/', $className) . '.php';
});

// require 'Plugins/Markdown.php';
// require 'Plugins/Flash.php';
require 'Verbier.php';
require 'Verbier/Helpers.php';


date_default_timezone_set('Europe/Oslo');

ActiveRecord::establishConnection(array(
	'adapter' => 'mysql',
	'host'    => 'localhost',
	'user'    => 'root',
	'pass'    => 'root',
	'dbname'  => 'dev_devmoods_blog'
));
