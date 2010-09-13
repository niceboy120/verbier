<?php

set_include_path(implode(PATH_SEPARATOR, array('../../lib/', get_include_path())));

require 'Verbier.php';

set('name', 'Hanse');

get('/', function() {
	return 'Hello' . setting('Hanse');
})