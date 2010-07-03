<?php

set_include_path(implode(PATH_SEPARATOR, array(
	'../',
	'../lib',
	'../models',
	get_include_path()
)));

require 'Verbier.php';
require 'app.php';
run();