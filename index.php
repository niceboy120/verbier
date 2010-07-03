<?php

set_include_path(implode(PATH_SEPARATOR, array('lib/', get_include_path())));

require 'Verbier.php';

get('/', function($that) {
	return 'Hello World';
});

get('/:name', function($that) {
	$that->name = $that->params['name'];
	return $that->render('hello');
});


run();