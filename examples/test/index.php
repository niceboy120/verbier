<?php

require 'Verbier.php';

$verbier->get('/', function($self, $params) {
	return $self->render('hello');
});