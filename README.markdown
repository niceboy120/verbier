verbier
=======

verbier is a framework inspired by Sinatra.

	[sudo] pear install pearhub/verbier

## Why?
Every programming language ought to have at least five Sinatra inspired frameworks.
The idea of Sinatra is great and it is well suited for small apps.

## How?
Utilizing the power of PHP 5.3, we can make stuff like this:

	get('/posts', function($self) {
		render('posts/index', array(
			'posts' => Post::findAll()
		));
	});
	
`$self` is an instance of `Verbier\Application`

All stuff you put into `$self` will be available in your views from `$this`. In the template posts/index.phtml you can call `$this->posts` to grab the posts we assigned earlier.

Of course, you can use POST, PUT, DELETE as well. `post()`, `put()` and `delete()` are your friends.

## API
The API consist of these methods that makes the framework awesome:

* `before()` Add a filter that runs before the actions.
* `after()` Add a filter that runs after the actions.
* `get()` Add an action responding to GET.
* `post()` Add an action responding to POST.
* `put()` Add an action responding to PUT.
* `delete()` Add an action responding to DELETE.
* `set()` Set a setting value.
* `enable()` Enable a setting.
* `disable()` Disable a setting.
* `flash()` Add a flash message.
* `render()` Render a template.
* `redirect()` Perform a HTTP redirect.

[Read full API docs](http://)

## Dude, show me an app!
Ok, here you go! 30 seconds blog tutorial:

	<?php
	
	// set include paths and stuff
	
	require 'Verbier.php';
	
	get('/', function() {
		render('posts/index', array(
			'post' => Post::findAll()
		));
	});
	
	get('/:slug', function($self, $slug) {
		render('posts/show', array(
			'post' => Post::findBySlug($slug)
		));
	});
	
	post('/', function($self) {
		$post = new Post($self->request->param('post'));
		if ($post->save()) {
			redirect('/', array('notice' => 'The post was created.'));
		}
		render('posts/new', array(
			'post' => $post
		));
	});
	
	get('/posts/new', function() {
		render('posts/new', array('post' => new Post()));
	});
	
	run();


## Setting up an app
Copy `examples/skeleton` and start writing your awesome app.

The public folder should be the only folder accessible from the browser and you should put `run()` in index.php.

All route definitions should be put in app.php. what you use templates/ and models/ for is up to you, but it should be for template files and model classes.

## Configuration
Configuration can be done `configure()`. It takes two parameters: the environment and a closure.

	configure('dev', function() {
		enable('errors');
	});
	
	configure('prod', function() {
		disable('errors');
	})
	
	configure('*', function() {
		set('root', __DIR__);
	});

Set values with `set()`, `enable()` and `disable()`. Get your values through `$self->settings`.
