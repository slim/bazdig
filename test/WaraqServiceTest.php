<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class WaraqServiceTest extends UnitTestCase {

    var $WaraqService;

    function setUp()
    {
        require_once '../lib/waraqservice.php';
        $this->WaraqService =& new WaraqService("http://localhost/teh/warak");
		$this->WaraqService->setparam("test/1", 1);
        $this->WaraqService2 =& new WaraqService("http://localhost/teh/waraq/markkit", "/var/www/teh/waraq/markkit");
		$this->WaraqService2->setparam("test/1", 1);
    }

    function tearDown()
    {
        unset($this->WaraqService);
    }

    function testget()
    {
        $result   = $this->WaraqService2->get("/test");
        $expected = new LocalResource("http://localhost/teh/waraq/markkit//test", "/var/www/teh/waraq/markkit//test");
        $this->assertEqual($expected, $result);
    }

    function testgetparam()
    {
        $result   = $this->WaraqService->getparam("test/1");
        $expected = 1;
        $this->assertEqual($expected, $result);
    }

    function testsetparam()
    {
        $result   = $this->WaraqService->setparam("test/2", "testval2");
        $expected = $this->WaraqService;
        $this->assertEqual($expected, $result);
    }

}
// Running the test.
$test = &new WaraqServiceTest;
$test->run(new TextReporter());
?>
