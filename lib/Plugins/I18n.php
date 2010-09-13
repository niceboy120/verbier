<?php

Application::registerPlugin('I18n', function($app) {
	$app->helper('t', function() use($app) {
		
	});
});

class I18n {
	
}

I18n::setLocale('nb_NO');

t('The post was added.'); // Innlegget ble lagt til.
t('Username'); // Brukernavn

t('The post <em>:title</em> was added', $title);

class I18n {
	
	public function getLocale() {}
}

putenv('LANG=nb_NO');
setlocale(LC_ALL, 'nb_NO');