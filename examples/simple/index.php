<?php

set_include_path(implode(PATH_SEPARATOR, array('../../lib/', get_include_path())));

require 'Verbier.php';

set('app_file', __FILE__);
set('root', __DIR__);

before(function() {
	content_type('text/html');
});

get('/', function($self) {
	return 'Message: ' . flash('notice');
});

get('/:name', function($self, $name) {
	flash('notice', "Hello {$name}");
	redirect('/');
});

run();