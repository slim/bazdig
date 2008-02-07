<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class PageSelectorTest extends UnitTestCase {
	function setUp() {
        require_once '../lib/pageselector.php';
		$this->PageSelector =& new PageSelector("http://localhost/?p=", 2, 10);
		$this->PageSelector2 =& new PageSelector("http://localhost/?p=", 2, 30);
	}

	function test_getLinks() {
		$result = $this->PageSelector->getLinks(4);
		$expected = array('previous' => 'http://localhost/?p=3',
						  '1' => 'http://localhost/?p=1',
						  '2' => 'http://localhost/?p=2',
						  '3' => 'http://localhost/?p=3',
						  '4' => '#',
						  '5' => 'http://localhost/?p=5',
						  'next' => 'http://localhost/?p=5');
		$this->assertEqual( $expected, $result );
		$result = $this->PageSelector2->getLinks(10);
		$expected = array('previous' => 'http://localhost/?p=9',
						  '5' => 'http://localhost/?p=5',
						  '6' => 'http://localhost/?p=6',
						  '7' => 'http://localhost/?p=7',
						  '8' => 'http://localhost/?p=8',
						  '9' => 'http://localhost/?p=9',
						  '10' => '#',
						  '11' => 'http://localhost/?p=11',
						  '12' => 'http://localhost/?p=12',
						  '13' => 'http://localhost/?p=13',
						  '14' => 'http://localhost/?p=14',
						  '15' => 'http://localhost/?p=15',
						  'next' => 'http://localhost/?p=11');
		$this->assertEqual( $expected, $result );
		$result = $this->PageSelector2->getLinks(3);
		$expected = array('previous' => 'http://localhost/?p=2',
						  '1' => 'http://localhost/?p=1',
						  '2' => 'http://localhost/?p=2',
						  '3' => '#',
						  '4' => 'http://localhost/?p=4',
						  '5' => 'http://localhost/?p=5',
						  '6' => 'http://localhost/?p=6',
						  '7' => 'http://localhost/?p=7',
						  '8' => 'http://localhost/?p=8',
						  '9' => 'http://localhost/?p=9',
						  '10' => 'http://localhost/?p=10',
						  '11' => 'http://localhost/?p=11',
						  'next' => 'http://localhost/?p=4');
		$this->assertEqual( $expected, $result );
		$result = $this->PageSelector2->getLinks(14);
		$expected = array('previous' => 'http://localhost/?p=13',
						  '5' => 'http://localhost/?p=5',
						  '6' => 'http://localhost/?p=6',
						  '7' => 'http://localhost/?p=7',
						  '8' => 'http://localhost/?p=8',
						  '9' => 'http://localhost/?p=9',
						  '10' => 'http://localhost/?p=10',
						  '11' => 'http://localhost/?p=11',
						  '12' => 'http://localhost/?p=12',
						  '13' => 'http://localhost/?p=13',
						  '14' => '#',
						  '15' => 'http://localhost/?p=15',
						  'next' => 'http://localhost/?p=15');
		$this->assertEqual( $expected, $result );
	}
}
// Running the test.
$test = &new PageSelectorTest;
$test->run(new HtmlReporter());
