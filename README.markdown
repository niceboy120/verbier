verbier
=======

verbier is a framework inspired by Sinatra.

	pear install pearhub/verbier

## Why?
Every programming language ought to have a Sinatra inspired framework.
The idea of Sinatra is great and it is well suited for small apps.

## How?
Utilizing the power of PHP 5.3, we can make stuff like this:

	get('/posts/:slug', function($that) {
		$that->post = Post::findBySlug($that->params['slug']);
		return $that->render('posts/show');
	});
	
Hey, what is that `$that`-thingy? Inside the closure we don't have access to `$this` and stuff like that. Therefore I have this crazy hacky macky context object `$that` which is an instance of `Verbier\Context`.  Makes life easier for all of us.

All stuff you put into `$that` will be available in your views from `$this`. In the template posts/index.phtml you can call `$this->posts` to grab the posts we assigned earlier.

Of course, you can use POST, PUT, DELETE as well. `post()`, `put()` and `delete()` are your friends.

## Dude, show me an app!
Ok, here you go!

	<?php
	
	// set include paths and stuff
	
	require 'Verbier.php';
	
	get('/', function() {
		return 'Hello World';
	});
	
	run();

Go to http://localhost/ or where your stuff are and you will see Hello World. Pretty cool?

Sorry, I don't do Hello World. Okay, fine:

	get('/posts', function($that) {
		$that->posts = Post::findAll();
		switch ($that->getFormat()) {
			case 'json':
			$that->response->setContentType('application/json');
			return $that->posts->toJson();
			break;
			
			default:
			return $that->render('posts/index');
		}
	});

## Setting up an app
You should try and use this structure for your apps:

	app.php
	templates/
	  hello.phtml
	models/
	README.markdown
	lib/
	  // Put all your libraries including verbier here
	public/
	  index.php

The public folder should be the only folder accessible from the browser and you should put `run()` in index.php.

All route definitions should be put in app.php. what you use templates/ and models/ for is up to you, but it should be for template files and model classes.




