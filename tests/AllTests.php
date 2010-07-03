<?php

class AllTests {
	
	static public function suite() {
		$suite = new \PHPUnit_Framework_TestSuite('Verbier All Tests');
		$suite->addTestSuite('\Verbier\RequestHandlerTest');
		return $suite;
	}
}