<?php

set_include_path(implode(PATH_SEPARATOR, array('../../lib/', get_include_path())));

require 'Verbier.php';

get('/', function() {
	return 'Hello World';
});

get('/:name', function($self, $params) {
	$self->name = $params['name'];
	return $self->render('hello');
});

run();

