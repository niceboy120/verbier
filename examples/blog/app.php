<?php

configure('*', function() {
	enable('sessions');
	enable('flash');
	enable('errors');
});

get('/', function($that) {
	$that->posts = Post::findAll();
	return $that->render('posts/index');
});

post('/posts', function($that) {
	$that->post = new Post($that->params['post']);
	if ($that->post->save()) {
		$that->setFlashNotice('The post was added');
		return $that->redirect('/');
	}
	return $that->render('posts/new');
});

get('/posts/new', function($that) {
	$that->post = new Post();
	return $that->render('posts/new');
});