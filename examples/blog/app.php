<?php

get('/', function($self, $params) {
	$self->posts = Post::all();
	return $self->render('index');
});

get('/:slug', function($self, $params) {
	$self->post = Post::where('slug = ?', array($params['slug']));
	return $self->render('show');
});