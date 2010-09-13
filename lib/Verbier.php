<?php


$verbier = new \Verbier\Application();

$verbier->helper('content_type', function($contentType) use($verbier) {
	$verbier->response->setContentType($contentType);
});

foreach ($verbier->getHelpers() as $helper) {
	eval("function {$helper}() {
		global \$verbier;
		return call_user_func_array(array(\$verbier, '{$helper}'), func_get_args());
	}");
}
