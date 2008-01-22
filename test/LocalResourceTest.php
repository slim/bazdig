<?php
/**
 * PHPUnit test case for LocalResource
 * 
 * The method skeletons below need to be filled in with 
 * real data so that the tests will run correctly. Replace 
 * all EXPECTED_VAL and PARAM strings with real data. 
 * 
 * Created with PHPUnit_Skeleton on 2007-04-09
 */
require_once 'PHPUnit.php';
class LocalResourceTest extends PHPUnit_TestCase {

    var $LocalResource;

    function LocalResourceTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        require_once '../lib/localresource.php';
        $this->LocalResource =& new LocalResource("http://localhost/warak/test", "/var/www/teh/warak/test" );
    }

    function tearDown()
    {
        unset($this->LocalResource);
    }

    function testget()
    {
        $result   = $this->LocalResource->get("test.txt");
        $expected = new LocalResource("http://localhost/warak/test/test.txt", "/var/www/teh/warak/test/test.txt" );
        $this->assertEquals($expected, $result);
    }

    function testget_file()
    {
        $result   = $this->LocalResource->get_file();
        $expected = "/var/www/teh/warak/test";
        $this->assertEquals($expected, $result);
    }

    function testget_url()
    {
        $result   = $this->LocalResource->get_url();
        $expected = "http://localhost/warak/test";
        $this->assertEquals($expected, $result);
    }

	function testabsolutize()
	{
		$result = absolutize("/xxx/../YYY/./zzz/../aaa");
		$expected = "/YYY/aaa";
        $this->assertEquals($expected, $result);
		$result = absolutize("/xxx/YYY/../../zzz/./aaa");
		$expected = "/zzz/aaa";
        $this->assertEquals($expected, $result);
	}

}
// Running the test.
$suite  = new PHPUnit_TestSuite('LocalResourceTest');
$result = PHPUnit::run($suite);
echo $result->toString();
?>
