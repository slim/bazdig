<?php

require_once('simpletest/unit_tester.php');
require_once('simpletest/reporter.php');

class SqlCodeTest extends UnitTestCase {

    function setUp()
    {
        require_once '../lib/code.php';
		$this->query = 'select * from test';
		$this->md5 = md5($this->query);
		$this->sqlCode = new SqlCode($this->query);
		$this->db =& new PDO("sqlite:bazdig-test.db");
    }

    function tearDown()
    {
    }

    function testtoSQLinsert()
    {
        $result   = $this->sqlCode->toSQLinsert();
        $expected = "/insert/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/'md5:".$this->md5."'/";
        $this->assertWantedPattern($expected, $result);
    }

    function testtoSQLselect()
    {
        $result   = $this->sqlCode->toSQLselect();
        $expected = "/select/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/id='md5:".$this->md5."'/";
        $this->assertWantedPattern($expected, $result);
    }

    function testsql_select()
    {
		$options = " where code like 'select %'";
        $result   = SqlCode::sql_select($options);
        $expected = "/$options/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/select.+id.+from/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/select.+code.+from/i";
        $this->assertWantedPattern($expected, $result);
        $expected = "/select.+date.+from/i";
        $this->assertWantedPattern($expected, $result);
    }

    function testselect()
    {
    }

	function testcount()
	{
	}

    function testsave()
    {
    }

    function testload()
    {
    }

    function testset_db()
    {
    }
	
}
// Running the test.
$test =& new SqlCodeTest;
$test->run(new HtmlReporter());
?>
