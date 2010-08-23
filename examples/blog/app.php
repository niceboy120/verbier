<?php

get('/', function($self, $params) {
	$self->posts = Post::all();
	return $self->render('index');
});

get('/:slug', function($self, $slug) {
	$self->post = Post::where('slug = ?', array($slug));
	return $self->render('show');
});