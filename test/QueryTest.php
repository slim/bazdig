<?php
/**
 * PHPUnit test case for Query
 * 
 * The method skeletons below need to be filled in with 
 * real data so that the tests will run correctly. Replace 
 * all EXPECTED_VAL and PARAM strings with real data. 
 * 
 * Created with PHPUnit_Skeleton on 2006-11-29
 */
require_once 'PHPUnit.php';
class QueryTest extends PHPUnit_TestCase {

    var $Query;

    function QueryTest($name)
    {
        $this->PHPUnit_TestCase($name);
    }

    function setUp()
    {
        require_once '../lib/database.php';
        $this->Query =& new Query();
		$this->DataBase =& new DataBase("sqlite://home/slim/waraq/test/database.db");
    }

    function tearDown()
    {
        unset($this->Query);
    }

    function testdb()
    {
        $result   = $this->Query->db(PARAM);
        $expected = EXPECTED_VAL;
        $this->assertEquals($expected, $result);
    }

	function testinsert()
	{
		$addItemQuery = new Query;
		//$addItemQuery->insert("item")->set("p1='test'")->then("p2='test'");

		$addItemQuery->string();


		
		$p1 = $getItemQuery->db();
        $this->assertEquals($expected, $result);

	}

    function testselect()
    {
        $result   = $this->Query->select("p1")->from("item")->where("p2='test'")->string();
        $expected = "select p1 from item where p2='test'";
        $this->assertEquals($expected, $result);
    }

    function testfrom()
    {
        $result   = $this->Query->from(PARAM);
        $expected = EXPECTED_VAL;
        $this->assertEquals($expected, $result);
    }

    function testwhere()
    {
        $result   = $this->Query->where(PARAM);
        $expected = EXPECTED_VAL;
        $this->assertEquals($expected, $result);
    }

}
// Running the test.
$suite  = new PHPUnit_TestSuite('QueryTest');
$result = PHPUnit::run($suite);
echo $result->toString();
?>
